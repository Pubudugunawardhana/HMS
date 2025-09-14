<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
require('../classes/RefundManager.php');

adminLogin();

$refundManager = new RefundManager($con);

if (isset($_POST['get_bookings'])) {
    $data = filteration($_POST);
    echo $refundManager->getRefundableBookings($data['search']);
}

if (isset($_POST['refund_booking'])) {
    $data = filteration($_POST);
    echo $refundManager->processRefund($data['booking_id']);
}
