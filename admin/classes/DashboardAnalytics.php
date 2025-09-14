<?php
class DashboardAnalytics {
    private $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    private function buildPeriodCondition($period) {
        return match ($period) {
            1 => "WHERE datentime BETWEEN NOW() - INTERVAL 30 DAY AND NOW()",
            2 => "WHERE datentime BETWEEN NOW() - INTERVAL 90 DAY AND NOW()",
            3 => "WHERE datentime BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()",
            default => ""
        };
    }

    public function getBookingAnalytics($period) {
        $condition = $this->buildPeriodCondition($period);

        $query = "
            SELECT 
              COUNT(CASE WHEN booking_status != 'pending' AND booking_status != 'payment failed' THEN 1 END) AS total_bookings,
              SUM(CASE WHEN booking_status != 'pending' AND booking_status != 'payment failed' THEN trans_amt END) AS total_amt,
              COUNT(CASE WHEN booking_status = 'booked' AND arrival = 1 THEN 1 END) AS active_bookings,
              SUM(CASE WHEN booking_status = 'booked' AND arrival = 1 THEN trans_amt END) AS active_amt,
              COUNT(CASE WHEN booking_status = 'cancelled' AND refund = 1 THEN 1 END) AS cancelled_bookings,
              SUM(CASE WHEN booking_status = 'cancelled' AND refund = 1 THEN trans_amt END) AS cancelled_amt
            FROM booking_order
            $condition
        ";

        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }

    public function getUserAnalytics($period) {
        $condition = $this->buildPeriodCondition($period);

        $reviewsQuery = "SELECT COUNT(sr_no) AS count FROM rating_review $condition";
        $queriesQuery = "SELECT COUNT(sr_no) AS count FROM user_queries $condition";
        $usersQuery   = "SELECT COUNT(id) AS count FROM user_cred $condition";

        $reviews = $this->conn->query($reviewsQuery)->fetch_assoc();
        $queries = $this->conn->query($queriesQuery)->fetch_assoc();
        $users   = $this->conn->query($usersQuery)->fetch_assoc();

        return [
            'total_reviews' => $reviews['count'],
            'total_queries' => $queries['count'],
            'total_new_reg' => $users['count'],
        ];
    }
}
