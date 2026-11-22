<?php
/**
 * Shared bootstrap for the Vercel front controller (api/index.php).
 * - loads env
 * - stores PHP sessions in MySQL so logins survive across serverless instances
 */

require_once __DIR__ . '/admin/inc/env_loader.php';
// Must be included in global scope so $con is a global for select()/insert()/...
require_once __DIR__ . '/admin/inc/db_config.php';

class DbSessionHandler implements SessionHandlerInterface
{
  private $db;

  public function open($path, $name): bool
  {
    $this->db = $GLOBALS['con'];
    @mysqli_query($this->db, "CREATE TABLE IF NOT EXISTS `sessions` (
      `id` varchar(128) NOT NULL PRIMARY KEY,
      `data` mediumblob NOT NULL,
      `updated_at` int NOT NULL,
      KEY `updated_at` (`updated_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    return true;
  }

  public function close(): bool
  {
    return true;
  }

  public function read($id): string|false
  {
    $stmt = mysqli_prepare($this->db, "SELECT `data` FROM `sessions` WHERE `id`=? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = $res ? mysqli_fetch_assoc($res) : null;
    mysqli_stmt_close($stmt);
    return $row ? $row['data'] : '';
  }

  public function write($id, $data): bool
  {
    $now = time();
    $stmt = mysqli_prepare($this->db, "REPLACE INTO `sessions` (`id`,`data`,`updated_at`) VALUES (?,?,?)");
    mysqli_stmt_bind_param($stmt, 'ssi', $id, $data, $now);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return (bool)$ok;
  }

  public function destroy($id): bool
  {
    $stmt = mysqli_prepare($this->db, "DELETE FROM `sessions` WHERE `id`=?");
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return true;
  }

  public function gc($max_lifetime): int|false
  {
    $limit = time() - $max_lifetime;
    $stmt = mysqli_prepare($this->db, "DELETE FROM `sessions` WHERE `updated_at` < ?");
    mysqli_stmt_bind_param($stmt, 'i', $limit);
    mysqli_stmt_execute($stmt);
    $n = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $n;
  }
}

if (session_status() === PHP_SESSION_NONE) {
  ini_set('session.gc_maxlifetime', '86400');
  ini_set('session.gc_probability', '1');
  ini_set('session.gc_divisor', '100');
  session_set_save_handler(new DbSessionHandler(), true);
  session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Lax',
  ]);
}
