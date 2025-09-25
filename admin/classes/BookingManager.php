<?php
class BookingManager {
    private $conn;

    //Encapsulation
    //Each class encapsulates related properties and methods, 
    //hiding internal details and exposing only necessary functionality.
    
    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    public function getFilteredBookings($search, $page, $limit = 10) {
        $start = ($page - 1) * $limit;

        $query = "  SELECT bo.*, bd.*,
                    mp.id AS meal_plan_id,
                    mp.code AS meal_plan_code,
                    mp.name AS meal_plan_name,
                    mp.description AS meal_plan_description,
                    rmp.price_modifier AS meal_plan_price_modifier
                    FROM booking_order bo
                    INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id
                    LEFT JOIN `room_meal_plans` rmp ON bd.meal_plan_id = rmp.id
                    LEFT JOIN `meal_plans` mp ON rmp.meal_plan_id = mp.id
                    WHERE (
                        (bo.booking_status='booked' AND bo.arrival=1) 
                        OR (bo.booking_status='cancelled' AND bo.refund=1)
                        OR (bo.booking_status='payment failed')
                    ) 
                    AND (
                        bo.order_id LIKE ? 
                        OR bd.phonenum LIKE ? 
                        OR bd.user_name LIKE ?
                    )
                    ORDER BY bo.booking_id DESC";

        $stmt_all = $this->conn->prepare($query);
        $likeSearch = "%{$search}%";
        $stmt_all->bind_param("sss", $likeSearch, $likeSearch, $likeSearch);
        $stmt_all->execute();
        $res_all = $stmt_all->get_result();
        $total_rows = $res_all->num_rows;

        if ($total_rows == 0) {
            return [
                'table_data' => "<b>No Data Found!</b>",
                'pagination' => ''
            ];
        }

        $query .= " LIMIT ?, ?";
        $stmt_limit = $this->conn->prepare($query);
        $stmt_limit->bind_param("sssii", $likeSearch, $likeSearch, $likeSearch, $start, $limit);
        $stmt_limit->execute();
        $result = $stmt_limit->get_result();

        $table_data = "";
        $i = $start + 1;

        while ($data = $result->fetch_assoc()) {
            $date = date("d-m-Y", strtotime($data['datentime']));
            $checkin = date("d-m-Y", strtotime($data['check_in']));
            $checkout = date("d-m-Y", strtotime($data['check_out']));

            $status_class = match($data['booking_status']) {
                'booked' => 'bg-success',
                'cancelled' => 'bg-danger',
                default => 'bg-warning text-dark',
            };

            $data['trans_amt'] = number_format($data['trans_amt'], 2, '.');
            $data['total_pay'] = number_format($data['total_pay'], 2, '.');
            
            $table_data .= "
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
                    </td>
                    <td>
                        <b>Amount:</b> LKR {$data['trans_amt']}<br>
                        <b>Paid:</b> LKR {$data['total_pay']}<br>
                        <b>Date:</b> {$date}<br>
                        <b>Meal Plan:</b> {$data['meal_plan_name']}<br>
                    </td>
                    <td>
                        <span class='badge {$status_class}'>{$data['booking_status']}</span>
                    </td>
                    <td>
                        <button type='button' onclick='download({$data['booking_id']})' class='btn btn-outline-success btn-sm fw-bold shadow-none'>
                            <i class='bi bi-file-earmark-arrow-down-fill'></i>
                        </button>
                    </td>
                </tr>
            ";

            $i++;
        }

        $pagination = $this->buildPagination($total_rows, $page, $limit);
        return ['table_data' => $table_data, 'pagination' => $pagination];
    }

    private function buildPagination($total, $current_page, $limit) {
        $total_pages = ceil($total / $limit);
        $pagination = "";

        if ($current_page != 1) {
            $pagination .= "<li class='page-item'><button onclick='change_page(1)' class='page-link shadow-none'>First</button></li>";
        }

        $prev = $current_page - 1;
        $pagination .= "<li class='page-item " . ($current_page == 1 ? 'disabled' : '') . "'>
            <button onclick='change_page($prev)' class='page-link shadow-none'>Prev</button>
        </li>";

        $next = $current_page + 1;
        $pagination .= "<li class='page-item " . ($current_page == $total_pages ? 'disabled' : '') . "'>
            <button onclick='change_page($next)' class='page-link shadow-none'>Next</button>
        </li>";

        if ($current_page != $total_pages) {
            $pagination .= "<li class='page-item'><button onclick='change_page($total_pages)' class='page-link shadow-none'>Last</button></li>";
        }

        return $pagination;
    }
}
