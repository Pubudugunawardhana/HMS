<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
require('../classes/BookingAssignmentManager.php');

adminLogin();

$manager = new BookingAssignmentManager($con);

if (isset($_POST['get_bookings'])) {
    $data = filteration($_POST);
    echo $manager->getPendingBookings($data['search']);
}

if (isset($_POST['assign_room'])) {
    $data = filteration($_POST);
    echo $manager->assignRoom($data['booking_id'], $data['room_no']);
}

if (isset($_POST['cancel_booking'])) {
    $data = filteration($_POST);
    echo $manager->cancelBooking($data['booking_id']);
}
