<?php
require_once('../inc/db_config.php');
require_once('../inc/essentials.php');
require('../classes/UserManager.php');

adminLogin();

$user = new UserManager($con, USERS_IMG_PATH);

if (isset($_POST['get_users'])) {
    echo $user->getAllUsers();
}

if (isset($_POST['toggle_status'])) {
    $data = filteration($_POST);
    echo $user->toggleUserStatus($data['toggle_status'], $data['value']);
}

if (isset($_POST['remove_user'])) {
    $data = filteration($_POST);
    echo $user->removeUnverifiedUser($data['user_id']);
}

if (isset($_POST['search_user'])) {
    $data = filteration($_POST);
    echo $user->searchUsers($data['name']);
}
