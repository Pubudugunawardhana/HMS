<?php
/**
 * File storage abstraction.
 *
 * - When BLOB_READ_WRITE_TOKEN is set (Vercel), files are stored in Vercel Blob
 *   at the same relative path they would have on disk (e.g. images/rooms/IMG_1.jpg),
 *   so public URLs are ASSET_BASE_URL . $relPath.
 * - Otherwise (local XAMPP), files are written to the project folder as before.
 */

if (!defined('PROJECT_ROOT')) {
  define('PROJECT_ROOT', dirname(__DIR__) . '/');
}

function storage_using_blob()
{
  return (string)getenv('BLOB_READ_WRITE_TOKEN') !== '';
}

function storage_blob_api()
{
  $base = getenv('BLOB_API_URL');
  return rtrim($base ?: 'https://vercel.com/api/blob', '/');
}

/**
 * Store a file. $src is a local file path (uploaded tmp file or generated file).
 * $relPath is relative to the project root, e.g. "images/rooms/IMG_123.jpg".
 */
function storage_put($src, $relPath, $mime = 'application/octet-stream', $isUploaded = true)
{
  $relPath = ltrim($relPath, '/');

  if (!storage_using_blob()) {
    $dest = PROJECT_ROOT . $relPath;
    $dir = dirname($dest);
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
      return false;
    }
    return $isUploaded ? move_uploaded_file($src, $dest) : rename($src, $dest);
  }

  $body = file_get_contents($src);
  if ($body === false) {
    return false;
  }

  $ch = curl_init(storage_blob_api() . '/?pathname=' . rawurlencode($relPath));
  curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST  => 'PUT',
    CURLOPT_POSTFIELDS     => $body,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 60,
    CURLOPT_HTTPHEADER     => [
      'authorization: Bearer ' . getenv('BLOB_READ_WRITE_TOKEN'),
      'x-api-version: 11',
      'x-vercel-blob-access: public',
      'x-add-random-suffix: 0',
      'x-allow-overwrite: 1',
      'x-content-type: ' . $mime,
      'x-content-length: ' . strlen($body),
    ],
  ]);
  curl_exec($ch);
  $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if (!$isUploaded && is_file($src)) {
    @unlink($src);
  }
  return $code >= 200 && $code < 300;
}

/**
 * Delete a stored file. Missing files count as deleted.
 */
function storage_delete($relPath)
{
  $relPath = ltrim((string)$relPath, '/');
  if ($relPath === '' || substr($relPath, -1) === '/') {
    return true;
  }

  if (!storage_using_blob()) {
    $path = PROJECT_ROOT . $relPath;
    return is_file($path) ? unlink($path) : true;
  }

  $url = rtrim(getenv('ASSET_BASE_URL'), '/') . '/' . $relPath;
  $ch = curl_init(storage_blob_api() . '/delete');
  curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode(['urls' => [$url]]),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_HTTPHEADER     => [
      'authorization: Bearer ' . getenv('BLOB_READ_WRITE_TOKEN'),
      'x-api-version: 11',
      'content-type: application/json',
    ],
  ]);
  curl_exec($ch);
  curl_close($ch);
  return true;
}
