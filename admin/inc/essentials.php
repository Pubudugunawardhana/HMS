<?php

// ==================== FRONTEND IMAGE & FILE PATHS ====================

define('SITE_URL','http://127.0.0.1/hms/');

define('ABOUT_IMG_PATH',         SITE_URL.'images/about/');
define('CAROUSEL_IMG_PATH',      SITE_URL.'images/carousel/');
define('FACILITIES_IMG_PATH',    SITE_URL.'images/facilities/');
define('ROOMS_IMG_PATH',         SITE_URL.'images/rooms/');
define('USERS_IMG_PATH',         SITE_URL.'images/users/');
define('WEDDING_IMG_PATH',       SITE_URL.'images/wedding/');
define('DESTINATION_IMG_PATH',   SITE_URL.'images/destinations/');
define('RESTAURANT_IMG_PATH',    SITE_URL.'images/restaurants/');
define('WEDDING_PDF_PATH',       SITE_URL.'uploads/wedding_pdfs/');
define('RESTAURANT_MENU_PATH',   SITE_URL.'uploads/restaurant_menus/');

// ==================== BACKEND UPLOAD PATHS ====================

define('UPLOAD_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'].'/hms/images/');
define('UPLOAD_PDF_PATH',   $_SERVER['DOCUMENT_ROOT'].'/hms/uploads/');

define('ABOUT_FOLDER',           'about/');
define('CAROUSEL_FOLDER',        'carousel/');
define('FACILITIES_FOLDER',      'facilities/');
define('ROOMS_FOLDER',           'rooms/');
define('USERS_FOLDER',           'users/');
define('WEDDING_FOLDER',         'wedding/');
define('DESTINATION_FOLDER',     'destinations/');
define('RESTAURANT_FOLDER',      'restaurants/');
define('WEDDING_PDF_FOLDER',     'wedding_pdfs/');
define('RESTAURANT_MENU_FOLDER', 'restaurant_menus/');

// ==================== SENDGRID EMAIL CONFIGURATION ====================

define('SENDGRID_API_KEY', "PASTE YOUR API KEY GENERATED FROM SENDGRID WEBSITE");
define('SENDGRID_EMAIL',   "PUT YOU EMAIL");
define('SENDGRID_NAME',    "ANY NAME");

// ==================== HELPER FUNCTIONS ====================

function adminLogin()
{
  session_start();
  if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
  }
}

function redirect($url)
{
  echo "<script>window.location.href='$url';</script>";
  exit;
}

function alert($type, $msg)
{
  $bs_class = ($type == "success") ? "alert-success" : "alert-danger";
  echo <<<alert
    <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
      <strong class="me-3">$msg</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  alert;
}

// ==================== IMAGE UPLOAD FUNCTIONS ====================

function uploadImage($image, $folder)
{
  $valid_mime = ['image/jpeg', 'image/png', 'image/webp'];
  $img_mime = $image['type'];

  if (!in_array($img_mime, $valid_mime)) {
    return 'inv_img';
  } elseif (($image['size'] / (1024 * 1024)) > 2) {
    return 'inv_size';
  } else {
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $rname = 'IMG_' . random_int(11111, 99999) . ".$ext";

    $img_path = UPLOAD_IMAGE_PATH . $folder . $rname;
    if (move_uploaded_file($image['tmp_name'], $img_path)) {
      return $rname;
    } else {
      return 'upd_failed';
    }
  }
}

function deleteImage($image, $folder)
{
  return unlink(UPLOAD_IMAGE_PATH . $folder . $image);
}

function uploadSVGImage($image, $folder)
{
  $valid_mime = ['image/svg+xml'];
  $img_mime = $image['type'];

  if (!in_array($img_mime, $valid_mime)) {
    return 'inv_img';
  } elseif (($image['size'] / (1024 * 1024)) > 1) {
    return 'inv_size';
  } else {
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $rname = 'IMG_' . random_int(11111, 99999) . ".$ext";

    $img_path = UPLOAD_IMAGE_PATH . $folder . $rname;
    if (move_uploaded_file($image['tmp_name'], $img_path)) {
      return $rname;
    } else {
      return 'upd_failed';
    }
  }
}

function uploadUserImage($image)
{
  $valid_mime = ['image/jpeg', 'image/png', 'image/webp'];
  $img_mime = $image['type'];

  if (!in_array($img_mime, $valid_mime)) {
    return 'inv_img';
  } else {
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $rname = 'IMG_' . random_int(11111, 99999) . ".jpeg";

    $img_path = UPLOAD_IMAGE_PATH . USERS_FOLDER . $rname;

    if ($ext == 'png' || $ext == 'PNG') {
      $img = imagecreatefrompng($image['tmp_name']);
    } elseif ($ext == 'webp' || $ext == 'WEBP') {
      $img = imagecreatefromwebp($image['tmp_name']);
    } else {
      $img = imagecreatefromjpeg($image['tmp_name']);
    }

    if (imagejpeg($img, $img_path, 75)) {
      return $rname;
    } else {
      return 'upd_failed';
    }
  }
}

?>
