<?php
class BookingAssignmentManager {
    private $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    function calculateNights($checkin_date, $checkout_date) {
      $checkin = new DateTime($checkin_date);
      $checkout = new DateTime($checkout_date);
      $interval = $checkin->diff($checkout);
      return $interval->days;
    }

    public function getPendingBookings($search) {
        $query = "SELECT bo.*, bd.*, 
                  mp.id AS meal_plan_id,
                  mp.code AS meal_plan_code,
                  mp.name AS meal_plan_name,
                  mp.description AS meal_plan_description,
                  rmp.price_modifier AS meal_plan_price_modifier FROM booking_order bo
                  INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id
                  LEFT JOIN `room_meal_plans` rmp ON bd.meal_plan_id = rmp.id
                  LEFT JOIN `meal_plans` mp ON rmp.meal_plan_id = mp.id
                  WHERE (
                      bo.order_id LIKE ? OR 
                      bd.phonenum LIKE ? OR 
                      bd.user_name LIKE ?
                  )
                  AND (bo.booking_status IN (?, ?) AND bo.arrival = ?)
                  ORDER BY bo.booking_id ASC";

        $values = ["%{$search}%", "%{$search}%", "%{$search}%", "pending", "booked", 0];
        $res = select($query, $values, 'ssssss');

        if (mysqli_num_rows($res) === 0) {
            return "<b>No Data Found!</b>";
        }

        $i = 1;
        $table_data = "";
        while ($data = mysqli_fetch_assoc($res)) {
            $date = date("d-m-Y", strtotime($data['datentime']));
            $checkin = date("d-m-Y", strtotime($data['check_in']));
            $checkout = date("d-m-Y", strtotime($data['check_out']));

            $calculateNights = $this->calculateNights($data['check_in'], $data['check_out']);
            $totalPrice = number_format($data['price'] , 2, '.');

            $status_class = match($data['booking_status']) {
                'pending' => 'bg-warning text-dark',
                'booked' => 'bg-success',
                default => 'bg-danger',
            };

            $data['trans_amt'] = number_format($data['trans_amt'], 2, '.');
            $table_data .= <<<HTML
                <tr>
                  <td>{$i}</td>
                  <td>
                    <span class='badge bg-primary'>Order ID: {$data['order_id']}</span><br>
                    <span class='badge $status_class'>Status: {$data['booking_status']}</span><br>
                    <b>Name:</b> {$data['user_name']}<br>
                    <b>Phone No:</b> {$data['phonenum']}
                  </td>
                  <td>
                    <b>Room:</b> {$data['room_name']}<br>
                    <b>Total Price:</b> LKR {$totalPrice}<br>
                    <b>Paid:</b> LKR {$data['trans_amt']}<br>
                    <b>Meal Plan:</b> {$data['meal_plan_name']}<br>
                  </td>
                  <td>
                    <b>Check-in:</b> {$checkin}<br>
                    <b>Check-out:</b> {$checkout}<br>
                    <b>Date:</b> {$date}
                  </td>
                  <td>
                    <button type='button' onclick='assign_room({$data['booking_id']})' 
                      class='btn text-white btn-sm fw-bold custom-bg shadow-none' 
                      data-bs-toggle='modal' data-bs-target='#assign-room'>
                      <i class='bi bi-check2-square'></i> Assign Room
                    </button>
                    <br>
                    <button type='button' onclick='cancel_booking({$data['booking_id']})' 
                      class='mt-2 btn btn-outline-danger btn-sm fw-bold shadow-none'>
                      <i class='bi bi-trash'></i> Cancel Booking
                    </button>
                  </td>
                </tr>
            HTML;

            $i++;
        }

        return $table_data;
    }

    public function assignRoom($bookingId, $roomNo) {
        $query = "UPDATE booking_order bo 
                  INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id
                  SET bo.arrival = ?, bo.rate_review = ?, bd.room_no = ?
                  WHERE bo.booking_id = ?";

        $values = [1, 0, $roomNo, $bookingId];
        $res = update($query, $values, 'iisi');

        return ($res === 2) ? 1 : 0;
    }

    public function cancelBooking($bookingId) {
        $query = "UPDATE booking_order SET booking_status = ?, refund = ? WHERE booking_id = ?";
        $values = ['cancelled', 0, $bookingId];

        return update($query, $values, 'sii');
    }
}
