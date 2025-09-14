<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
require('../classes/EquipmentManager.php');

adminLogin();

$equipment = new EquipmentManager($con);

if (isset($_POST['add_equipment'])) {
    $data = filteration($_POST);
    echo $equipment->addEquipment($data);
}

if (isset($_POST['get_all_equipment'])) {
    echo $equipment->getAllEquipment();
}

if (isset($_POST['get_equipment'])) {
    $data = filteration($_POST);
    echo $equipment->getEquipmentDetails($data['get_equipment']);
}

if (isset($_POST['edit_equipment'])) {
    $data = filteration($_POST);
    echo $equipment->editEquipment($data);
}

if (isset($_POST['delete_equipment'])) {
    $data = filteration($_POST);
    echo $equipment->deleteEquipment($data['equipment_id']);
}
?>
