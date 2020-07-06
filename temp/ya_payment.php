<?php
/*
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/

include ('../_conf.php');
include ('../classes/safemysql.class.php');
$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

require_once('../classes/core.class.php');

$core  = new Core();

$url = $core->url;
$form = $core->form;
$ip = $core->ip;
$get = $core->setGet();

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

include '../classes/ya_lib/autoload.php';
use YandexCheckout\Model\Notification\NotificationSucceeded;
use YandexCheckout\Model\Notification\NotificationWaitingForCapture;
use YandexCheckout\Model\NotificationEventType;

try {
  $notification = ($requestBody['event'] === NotificationEventType::PAYMENT_SUCCEEDED)
    ? new NotificationSucceeded($requestBody)
    : new NotificationWaitingForCapture($requestBody);
} catch (Exception $e) {
  // Обработка ошибок при неверных данных
}

$payment = $notification->getObject();

if ($payment->_status == 'succeeded') {
  $pay_id = $payment->_id;
  $pay_description = $payment->_description;
  $pay_amount = $payment->_amount->_value;
  $pay_order = explode('#', $pay_description);
  $pay_order = $pay_order[1];
  $sql = "SELECT * FROM pre_payments WHERE id = '$pay_order'";

  $order_data = $db->getRow($sql);
  //$order_data_result = mysql_query($sql);
  //$order_data = mysql_fetch_assoc($order_data_result);

  if ($order_data) {
    if ($order_data['status'] == 0) {

      //$update_user_sql = "UPDATE users SET balans = balans + '".$order_data['amount']."', total_balance = total_balance + '".$order_data['amount']."' WHERE id = ".$order_data['user_id'];

      //mysql_query("INSERT INTO payment_logs SET type = 'debug', text = '".mysql_real_escape_string($update_user_sql)."'");
      //mysql_query($update_user_sql);

      $core->changeBalance($order_data['user_id'], $order_data['variant'], 4, $order_data['amount'], 'Онлайн оплата #'.$pay_id);

      $db->query("UPDATE pre_payments SET status = 1, destanation_order_id = '".$pay_id."' WHERE id = '$pay_order'");

      //mysql_query("INSERT INTO payments SET user = '".$order_data['user_id']."', sum = '".$order_data['amount']."', base = 'Онлайн оплата #".$order_data['id']."'");
      $db->query("INSERT INTO payments SET user = ?i, sum = ?s, base = ?s", $order_data['user_id'], $order_data['amount'], 'Онлайн оплата #'.$order_data['id']);
    } else {
      $db->query("INSERT INTO payment_logs SET type = 'error', text = 'order has already been paid'");
    }
  } else {
    $db->query("INSERT INTO payment_logs SET type = 'error', text = 'order not found'");
  }
}
