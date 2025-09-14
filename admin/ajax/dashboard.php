<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
require('../classes/DashboardAnalytics.php');

adminLogin();

$dashboard = new DashboardAnalytics($con);

if (isset($_POST['booking_analytics'])) {
    $data = filteration($_POST);
    $result = $dashboard->getBookingAnalytics($data['period']);
    echo json_encode($result);
}

if (isset($_POST['user_analytics'])) {
    $data = filteration($_POST);
    $result = $dashboard->getUserAnalytics($data['period']);
    echo json_encode($result);
}
