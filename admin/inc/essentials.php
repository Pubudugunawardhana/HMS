<?php

require_once __DIR__ . '/env_loader.php';
require_once __DIR__ . '/../../inc/Storage.php';

// ==================== SITE / ASSET URLS ====================
// APP_URL: public URL of the site (e.g. https://hms.vercel.app/ or http://127.0.0.1/hms/)
// ASSET_BASE_URL: where images/uploads are served from (Vercel Blob public URL). Defaults to APP_URL.

if (!function_exists('app_base_url')) {
  function app_base_url()
  {
    $url = getenv('APP_URL');
    if (!$url) {
      $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
      $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? ($_SERVER['HTTP_HOST'] ?? '127.0.0.1');
      $url = ($https ? 'https://' : 'http://') . $host . '/';
    }
    return rtrim($url, '/') . '/';
  }
}

if (!defined('SITE_URL')) {
  define('SITE_URL', app_base_url());
  define('ASSET_URL', getenv('ASSET_BASE_URL') ? rtrim(getenv('ASSET_BASE_URL'), '/') . '/' : SITE_URL);

  define('ABOUT_IMG_PATH',         ASSET_URL.'images/about/');
  define('CAROUSEL_IMG_PATH',      ASSET_URL.'images/carousel/');
  define('FACILITIES_IMG_PATH',    ASSET_URL.'images/facilities/');
  define('ROOMS_IMG_PATH',         ASSET_URL.'images/rooms/');
  define('USERS_IMG_PATH',         ASSET_URL.'images/users/');
  define('WEDDING_IMG_PATH',       ASSET_URL.'images/wedding/');
  define('DESTINATION_IMG_PATH',   ASSET_URL.'images/destinations/');
  define('RESTAURANT_IMG_PATH',    ASSET_URL.'images/restaurants/');
  define('DINE_IN_IMG_PATH',       ASSET_URL.'images/dine_in/');
  define('EXPERIENCE_IMG_PATH',    ASSET_URL.'images/experience/');
  define('WEDDING_PDF_PATH',       ASSET_URL.'uploads/wedding_pdfs/');
  define('RESTAURANT_MENU_PATH',   ASSET_URL.'uploads/restaurant_menus/');

  // ==================== BACKEND UPLOAD PATHS (relative to project root) ====================

  define('UPLOAD_IMAGE_PATH', 'images/');
  define('UPLOAD_PDF_PATH',   'uploads/');

  define('ABOUT_FOLDER',           'about/');
  define('CAROUSEL_FOLDER',        'carousel/');
  define('FACILITIES_FOLDER',      'facilities/');
  define('ROOMS_FOLDER',           'rooms/');
  define('USERS_FOLDER',           'users/');
  define('WEDDING_FOLDER',         'wedding/');
  define('DESTINATION_FOLDER',     'destinations/');
  define('RESTAURANT_FOLDER',      'restaurants/');
  define('DINE_IN_FOLDER',         'dine_in/');
  define('EXPERIENCE_FOLDER',      'experience/');
  define('WEDDING_PDF_FOLDER',     'wedding_pdfs/');
  define('RESTAURANT_MENU_FOLDER', 'restaurant_menus/');
}

// ==================== HELPER FUNCTIONS ====================

function adminLogin()
{
  if (session_status() === PHP_SESSION_NONE) session_start();
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
    if (storage_put($image['tmp_name'], $img_path, $img_mime)) {
      return $rname;
    } else {
      return 'upd_failed';
    }
  }
}

function deleteImage($image, $folder)
{
  if ($image === '' || $image === null) return true;
  return storage_delete(UPLOAD_IMAGE_PATH . $folder . $image);
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
    if (storage_put($image['tmp_name'], $img_path, $img_mime)) {
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

    $tmp = tempnam(sys_get_temp_dir(), 'usr');
    if ($img && imagejpeg($img, $tmp, 75) && storage_put($tmp, $img_path, 'image/jpeg', false)) {
      return $rname;
    } else {
      return 'upd_failed';
    }
  }
}

?>
