<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
require('../classes/CarouselManager.php');

adminLogin();

$carousel = new CarouselManager($con, CAROUSEL_FOLDER);

if (isset($_POST['add_image'])) {
    echo $carousel->addImage($_FILES['picture']);
}

if (isset($_POST['get_carousel'])) {
    echo $carousel->getCarouselImages();
}

if (isset($_POST['rem_image'])) {
    $data = filteration($_POST);
    echo $carousel->removeImage($data['rem_image']);
}
