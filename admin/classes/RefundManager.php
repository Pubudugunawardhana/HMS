<?php
class RefundManager {
    private $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    public function getRefundableBookings(string $search): string {
        $query = "SELECT bo.*, bd.* FROM booking_order bo
                  INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id
                  WHERE (
                      bo.order_id LIKE ? OR 
                      bd.phonenum LIKE ? OR 
                      bd.user_name LIKE ?
                  ) 
                  AND (bo.booking_status = ? AND bo.refund = ?)
                  ORDER BY bo.booking_id ASC";

        $values = ["%{$search}%", "%{$search}%", "%{$search}%", "cancelled", 0];
        $res = select($query, $values, 'sssss');

        if (mysqli_num_rows($res) === 0) {
            return "<b>No Data Found!</b>";
        }

        $i = 1;
        $table_data = "";

        while ($data = mysqli_fetch_assoc($res)) {
            $date = date("d-m-Y", strtotime($data['datentime']));
            $checkin = date("d-m-Y", strtotime($data['check_in']));
            $checkout = date("d-m-Y", strtotime($data['check_out']));

            $data['trans_amt'] = number_format($data['trans_amt'],2, '.');

            $table_data .= <<<HTML
                <tr>
                  <td>{$i}</td>
                  <td>
                    <span class='badge bg-primary'>Order ID: {$data['order_id']}</span><br>
                    <b>Name:</b> {$data['user_name']}<br>
                    <b>Phone No:</b> {$data['phonenum']}
                  </td>
                  <td>
                    <b>Room:</b> {$data['room_name']}<br>
                    <b>Check-in:</b> {$checkin}<br>
                    <b>Check-out:</b> {$checkout}<br>
                    <b>Date:</b> {$date}<br>
                  </td>
                  <td>
                    <b>LKR {$data['trans_amt']}</b>
                  </td>
                  <td>
                    <button type='button' onclick='refund_booking({$data['booking_id']})' 
                      class='btn btn-success btn-sm fw-bold shadow-none'>
                      <i class='bi bi-cash-stack'></i> Refund
                    </button>
                  </td>
                </tr>
            HTML;

            $i++;
        }

        return $table_data;
    }

    public function processRefund(int $bookingId): int {
        $query = "UPDATE booking_order SET refund = ? WHERE booking_id = ?";
        return update($query, [1, $bookingId], 'ii');
    }
}
