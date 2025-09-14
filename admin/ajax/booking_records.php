<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
require('../classes/BookingManager.php');

date_default_timezone_set("Asia/Colombo");
adminLogin();

if (isset($_POST['get_bookings'])) {
    $frm_data = filteration($_POST);

    $manager = new BookingManager($con); // $con from db_config.php
    $result = $manager->getFilteredBookings($frm_data['search'], $frm_data['page']);

    echo json_encode($result);
}
?>
