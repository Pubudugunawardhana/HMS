<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
require('../classes/SettingsManager.php');

adminLogin();

$settings_crud = new SettingsManager($con, ABOUT_FOLDER);

// General
if (isset($_POST['get_general'])) {
    echo $settings_crud->getGeneralSettings();
}

if (isset($_POST['upd_general'])) {
    $data = filteration($_POST);
    echo $settings_crud->updateGeneralSettings($data['site_title'], $data['site_about']);
}

if (isset($_POST['upd_shutdown'])) {
    echo $settings_crud->toggleShutdown($_POST['upd_shutdown']);
}

// Contact
if (isset($_POST['get_contacts'])) {
    echo $settings_crud->getContactDetails();
}

if (isset($_POST['upd_contacts'])) {
    $data = filteration($_POST);
    echo $settings_crud->updateContactDetails($data);
}

// Team
if (isset($_POST['add_member'])) {
    $data = filteration($_POST);
    echo $settings_crud->addTeamMember($data['name'], $_FILES['picture']);
}

if (isset($_POST['get_members'])) {
    echo $settings_crud->getTeamMembers();
}

if (isset($_POST['rem_member'])) {
    $data = filteration($_POST);
    echo $settings_crud->removeTeamMember($data['rem_member']);
}
