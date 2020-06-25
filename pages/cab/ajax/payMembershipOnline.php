<?php
include('connect.php');
include '../../../classes/ya_lib/autoload.php';
use YandexCheckout\Client;

$paysystem = $core->cfgRead('payment_system');

if ($paysystem == 'yandex') {
  $shop_id = $core->cfgRead('yandex_shop_id2');
  $secret_key = $core->cfgRead('yandex_secret_key2');

  $client = new Client();

  $user['id'] = $form['user'];
  $pay_variant = 2;
  $amount = $form['amount'];

  if ($amount > 0 && $amount <= 15000) {

    $setAuth = $client->setAuth($shop_id, $secret_key);

    $db->query("INSERT INTO pre_payments SET user_id = ?i, variant = ?s, amount = ?s", $user['id'], $pay_variant, $amount);
    $order_id = $db->insertId();
    $description = 'Оплата членских взносов #'.$order_id;

    $payment = $client->createPayment(
    		array(
    				'amount' => array(
    						'value' => $amount,
    						'currency' => 'RUB',
    				),
    				'confirmation' => array(
    						'type' => 'redirect',
    						'return_url' => 'https://xn----dtbffa7byadkn0c6c.xn--p1ai/cab/membership?payment=success',
    				),
    				'capture' => true,
    				'description' => $description,
    		),
    		uniqid('', true)
    );

    $url = $payment->_confirmation->_confirmationUrl;

    if ($url) {
      $json['status'] = 'OK';
      $json['url'] = $url;
    } else {
      $json['status'] = 'ERROR';
      $json['error'] = 'Возникла ошибка. Попробуйте позже.';
    }
  } else {
    $json['status'] = 'ERROR';
    $json['error'] = 'Сумма должна быть от 1 до 15 000 рублей';
  }
}

echo json_encode($json);
