<?php
/**
 * Vercel front controller.
 * Every *.php request (and directory request) is rewritten here by vercel.json
 * with ?__route=<original path>. We resolve it to a real PHP file inside the
 * project and run it as if it had been requested directly (like on XAMPP).
 */

ob_start();
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
date_default_timezone_set('Asia/Colombo');

define('PROJECT_ROOT', dirname(__DIR__) . '/');

function hms_not_found()
{
  http_response_code(404);
  header('Content-Type: text/html; charset=UTF-8');
  echo '<!doctype html><title>404 Not Found</title><h1>404 Not Found</h1>';
  exit;
}

// ---- resolve requested path ----
$route = $_GET['__route'] ?? '';
unset($_GET['__route']);
if (isset($_SERVER['QUERY_STRING'])) {
  $_SERVER['QUERY_STRING'] = http_build_query($_GET);
}

$route = '/' . ltrim(rawurldecode(parse_url('/' . $route, PHP_URL_PATH) ?? ''), '/');
if (strpos($route, "\0") !== false || preg_match('#(^|/)\.\.?(/|$)#', $route)) {
  hms_not_found();
}

if ($route === '/' || substr($route, -1) === '/') {
  $route .= 'index.php';
} elseif (substr($route, -4) !== '.php') {
  // "/admin" style directory request
  $route .= '/index.php';
}

$relative = ltrim($route, '/');

// Only real entry points may be executed directly.
$blocked = [
  '#^api/#',
  '#^bootstrap\.php$#',
  '#^scripts/#',
  '#^inc/#',
  '#^admin/inc/#',
  '#^admin/classes/#',
  '#^images/#',
  '#^uploads/#',
  '#^README/#',
];
foreach ($blocked as $re) {
  if (preg_match($re, $relative)) {
    hms_not_found();
  }
}

$file = realpath(PROJECT_ROOT . $relative);
$root = realpath(PROJECT_ROOT);
if ($file === false || !is_file($file) || strpos($file, $root . DIRECTORY_SEPARATOR) !== 0) {
  hms_not_found();
}

// ---- emulate a normal web-server request for that file ----
$scriptUrl = '/' . $relative;
$_SERVER['SCRIPT_FILENAME'] = $file;
$_SERVER['SCRIPT_NAME']     = $scriptUrl;
$_SERVER['PHP_SELF']        = $scriptUrl;
$_SERVER['DOCUMENT_ROOT']   = rtrim($root, '/');
$_SERVER['REQUEST_URI']     = $scriptUrl . (count($_GET) ? '?' . http_build_query($_GET) : '');
$_REQUEST = array_merge($_GET, $_POST);

require PROJECT_ROOT . 'bootstrap.php';

chdir(dirname($file));
require $file;
