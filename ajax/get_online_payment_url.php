<?php
include_once "../core/db_connect.php";

//$amount = 110.00;
$amount = $_POST['amount'];
$pay_variant = $_POST['pay_variant'];
$user = $_POST['user'];

$result = mysql_query("SELECT id FROM users WHERE email = '$user'");
$user_id = mysql_result($result, 0);

mysql_query("INSERT INTO pre_payments SET user_id = '".$user_id."', variant = '$pay_variant', amount = '$amount'");
$order_id = mysql_insert_id();


$shop_id = '672180';
$secret_key = 'test_To0Nlw9VD8h3EYtME_WrJpqTgCPxBqsIskx60RDkVIc';

$description = 'Оплата электроэнергии #'.$order_id;

include 'ya_lib/autoload.php';

use YandexCheckout\Client;

    $client = new Client();
    $client->setAuth($shop_id, $secret_key);
    $payment = $client->createPayment(
        array(
            'amount' => array(
                'value' => $amount,
                'currency' => 'RUB',
            ),
            'confirmation' => array(
                'type' => 'redirect',
                'return_url' => 'https://xn----dtbffa7byadkn0c6c.xn--p1ai/user.php?payment=success',
            ),
            'capture' => true,
            'description' => $description,
        ),
        uniqid('', true)
    );

//echo '<pre>';
//var_dump($payment->_id);
//var_dump($payment->_confirmation->_confirmationUrl);

$url = $payment->_confirmation->_confirmationUrl;
//header("Location: $url");
//echo '</pre>';
echo $url;
