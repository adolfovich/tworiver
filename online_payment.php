<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include ('_conf.php');
include ('classes/safemysql.class.php');
$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

require_once('classes/core.class.php');

$core  = new Core();

function getIp() {
  $keys = [
    'HTTP_CLIENT_IP',
    'HTTP_X_FORWARDED_FOR',
    'REMOTE_ADDR'
  ];
  foreach ($keys as $key) {
    if (!empty($_SERVER[$key])) {
      $ip = trim(end(explode(',', $_SERVER[$key])));
      if (filter_var($ip, FILTER_VALIDATE_IP)) {
        return $ip;
      }
    }
  }
}

$ip = getIp();
$request = 'ip => '.$ip.', ';
$source = file_get_contents('php://input');
$requestBody = json_decode($source, true);

$payment = $_GET;

if ($payment['status'] == '1') {
	$pay_order = $payment['orderNumber'];
	$pay_id = $payment['mdOrder'];
	$pay_operation = $payment['operation'];

  $order_data = $db->getRow("SELECT * FROM pre_payments WHERE id = ?i", $pay_order);

  if ($order_data) {
    if ($order_data['status'] == 0) {

      $core->changeBalance($order_data['user_id'], 1, 4, $order_data['amount'], 'Онлайн оплата Yookassa#'.$pay_id);

      $db->query("UPDATE pre_payments SET status = 1 WHERE id = ?i", $pay_order);

      $insert = [
        'user' => $order_data['user_id'],
        'sum' => $order_data['amount'],
        'base' => 'Онлайн оплата Yookassa #'.$order_data['id']
      ];
      $db->query("INSERT INTO payments SET ?u", $insert);

    } else {
      $db->query("INSERT INTO payment_logs SET type = 'error', text = 'order has already been paid'");
    }
  } else {
    $db->query("INSERT INTO payment_logs SET type = 'error', text = 'order not found'");
  }
}