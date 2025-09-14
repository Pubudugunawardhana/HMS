<?php
// Save this as hash_admin_pass.php and run it once in your browser

// $plain_password = 'admin';

$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

echo "Hashed password: " . $hashed_password;
?>