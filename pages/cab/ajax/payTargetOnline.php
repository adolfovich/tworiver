<?php

include('connect.php');

$paysystem = 'sberpay';

$amount = $form['amount'];

$user['id'] = $form['user'];
$user = $db->getRow("SELECT * FROM users WHERE id = ?i", $user['id']);

$pay_variant = 3;

if ($paysystem == 'sberpay') {
	
	if ($amount > 0 && $amount <= 1500000) {
		
		$db->query("INSERT INTO pre_payments SET user_id = ?i, variant = ?s, amount = ?s", $user['id'], $pay_variant, $amount);
		$order_id = $db->insertId();
		
		$description = 'Оплата целевых взносов #'.$order_id." | участок №".$user['uchastok'];
		
		//$url = 'https://3dsec.sberbank.ru/payment/rest/register.do'; //тестовый
		//$url = 'https://securepayments.sberbank.ru/payment/rest/register.do'; //боевой
		$url = 'https://3dsec-payments.yookassa.ru/payment/rest/register.do';
		$post_data = array(
			'userName' => '424583',
			'password' => 'live_dBc2uvqxlwvUW_CQ5yqPjiRDtFv7I5_ia4iYNzkDbf0',
			'orderNumber' => $order_id,
			'returnUrl' => 'https://xn----dtbffa7byadkn0c6c.xn--p1ai/cab/target',
			'description' => $description,
			'amount' => $amount * 100, 
			'email' => $user['email'],
			'phone' => $user['phone'],
		);
		
		$headers = ['Content-Type: application/x-www-form-urlencoded'];

		$post_data = http_build_query($post_data);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true); // true - означает, что отправляется POST запрос

		$result_json = curl_exec($curl);

		//var_dump($result_json);
		
		$result = json_decode($result_json, TRUE);
		
		if (isset($result['orderId'])) {
			$db->query("UPDATE pre_payments SET destanation_order_id = ?s WHERE id = ?i", $result['orderId'], $order_id);
			
			$json['status'] = 'OK';
			$json['url'] = $result['formUrl'];
		} else {
		  $json['status'] = 'ERROR';
		  $json['error'] = 'Ошибка связи с банком. Попробуйте позже.';
		}
		
	} else {
		$json['status'] = 'ERROR';
		$json['error'] = 'Сумма должна быть от 1 до 15 000 рублей';
	  }	
	
}

echo json_encode($json);

