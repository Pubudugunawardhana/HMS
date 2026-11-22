<?php
/**
 * PayHere server-to-server payment notification (notify_url).
 * PayHere POSTs here after a payment; we verify md5sig and update the booking.
 * status_code: 2 = success, 0 = pending, -1 = canceled, -2 = failed, -3 = chargedback
 */

require_once('admin/inc/db_config.php');
require_once('admin/inc/essentials.php');
require_once('inc/Payhere.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit;
}

$merchant_id      = $_POST['merchant_id'] ?? '';
$order_id         = $_POST['order_id'] ?? '';
$payment_id       = $_POST['payment_id'] ?? '';
$payhere_amount   = $_POST['payhere_amount'] ?? '';
$payhere_currency = $_POST['payhere_currency'] ?? '';
$status_code      = $_POST['status_code'] ?? '';
$md5sig           = $_POST['md5sig'] ?? '';
$status_message   = substr($_POST['status_message'] ?? '', 0, 200);

$payhere = new Payhere();
if (!$payhere->verifyNotification($merchant_id, $order_id, $payhere_amount, $payhere_currency, $status_code, $md5sig)) {
  http_response_code(400);
  error_log("PayHere notify: invalid signature for order $order_id");
  exit;
}

$code = (int)$status_code;
if ($code === 2) {
  $booking_status = 'booked';
  $trans_status   = 'TXN_SUCCESS';
} elseif ($code === 0) {
  $booking_status = 'pending';
  $trans_status   = 'pending';
} else {
  $booking_status = 'payment failed';
  $trans_status   = 'TXN_FAILURE';
}

update(
  "UPDATE `booking_order` SET `booking_status`=?, `trans_id`=?, `trans_status`=?, `trans_resp_msg`=?
   WHERE `order_id`=? AND `booking_status` IN ('pending','payment failed')",
  [$booking_status, $payment_id, $trans_status, $status_message ?: $trans_status, $order_id],
  'sssss'
);

http_response_code(200);
echo 'OK';
