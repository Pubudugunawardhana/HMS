<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
require('../classes/FeatureFacilityManager.php');

adminLogin();

$manager = new FeatureFacilityManager($con, FACILITIES_FOLDER);

if (isset($_POST['add_feature'])) {
    $data = filteration($_POST);
    echo $manager->addFeature($data['name']);
}

if (isset($_POST['get_features'])) {
    echo $manager->getFeatures();
}

if (isset($_POST['rem_feature'])) {
    $data = filteration($_POST);
    echo $manager->removeFeature($data['rem_feature']);
}

if (isset($_POST['add_facility'])) {
    $data = filteration($_POST);
    echo $manager->addFacility($_FILES['icon'], $data['name'], $data['desc']);
}

if (isset($_POST['get_facilities'])) {
    echo $manager->getFacilities();
}

if (isset($_POST['rem_facility'])) {
    $data = filteration($_POST);
    echo $manager->removeFacility($data['rem_facility']);
}
