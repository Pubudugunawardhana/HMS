<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
require('../classes/RoomManager.php');

adminLogin();
$room = new RoomManager($con, ROOMS_FOLDER);

if (isset($_POST['add_room'])) {
    $features = filteration(json_decode($_POST['features']));
    $facilities = filteration(json_decode($_POST['facilities']));
    $meal_plans = filteration(json_decode($_POST['meal_plans'], true));
    $data = filteration($_POST);
    echo $room->addRoom($data, $features, $facilities, $meal_plans);
}

if (isset($_POST['get_all_rooms'])) {
    echo $room->getAllRooms();
}

if (isset($_POST['get_room'])) {
    $data = filteration($_POST);
    echo $room->getRoomDetails($data['get_room']);
}

if (isset($_POST['edit_room'])) {
    $features = filteration(json_decode($_POST['features']));
    $facilities = filteration(json_decode($_POST['facilities']));
    $meal_plans = filteration(json_decode($_POST['meal_plans'], true));
    $data = filteration($_POST);
    echo $room->editRoom($data, $features, $facilities, $meal_plans);
}

if (isset($_POST['toggle_status'])) {
    $data = filteration($_POST);
    echo $room->toggleStatus($data['toggle_status'], $data['value']);
}

if (isset($_POST['add_image'])) {
    $data = filteration($_POST);
    echo $room->uploadImage($data['room_id'], $_FILES['image']);
}

if (isset($_POST['get_room_images'])) {
    $data = filteration($_POST);
    echo $room->getRoomImages($data['get_room_images']);
}

if (isset($_POST['rem_image'])) {
    $data = filteration($_POST);
    echo $room->removeImage($data['image_id'], $data['room_id']);
}

if (isset($_POST['thumb_image'])) {
    $data = filteration($_POST);
    echo $room->setThumbnail($data['room_id'], $data['image_id']);
}

if (isset($_POST['remove_room'])) {
    $data = filteration($_POST);
    echo $room->removeRoom($data['room_id']);
}

?>