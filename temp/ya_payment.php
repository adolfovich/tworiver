<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once "../core/db_connect.php";


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
//var_dump($source);
//echo '<hr>';
//mysql_query("INSERT INTO payment_logs SET type = 'debug', text = '$source'") or die(mysql_error());
$requestBody = json_decode($source, true);

//var_dump($requestBody);
//echo '<hr>';


include '../ajax/ya_lib/autoload.php';
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

//var_dump($payment->description);

if ($payment->_status == 'succeeded') {
  //echo 'OK';
  $pay_id = $payment->_id;
  $pay_description = $payment->_description;
  $pay_amount = $payment->_amount->_value;
  $pay_order = explode('#', $pay_description);
  $pay_order = $pay_order[1];
  $sql = "SELECT * FROM pre_payments WHERE id = '$pay_order'";
  //var_dump($sql);
  $order_data_result = mysql_query($sql);
  //var_dump($order_data_result);
  $order_data = mysql_fetch_assoc($order_data_result);
  var_dump($order_data);

  if ($order_data) {
    if ($order_data['status'] == 0) {
      mysql_query("UPDATE pre_payments SET status = 1, destanation_order_id = '".$pay_id."' WHERE id = '$pay_order'");

      mysql_query("INSERT INTO payments SET user = '".$order_data['user_id']."', sum = '".$order_data['amount']."', base = 'Онлайн оплата #".$order_data['id']."'");
    } else {
      mysql_query("INSERT INTO payment_logs SET type = 'error', text = 'order has already been paid'");
    }
  } else {
    mysql_query("INSERT INTO payment_logs SET type = 'error', text = 'order not found'");
  }
  //var_dump($order_data);


}
