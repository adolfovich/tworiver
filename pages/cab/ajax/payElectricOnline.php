<?php
include('connect.php');

//$paysystem = 'sberpay';

$amount = $form['amount'];

$user['id'] = $form['user'];
$user = $db->getRow("SELECT * FROM users WHERE id = ?i", $user['id']);

if ($user['paysystem']) {
	$paysystem = $user['paysystem'];
} else {
	$paysystem = $db->getOne("SELECT data FROM settings WHERE cfgname = 'default_paysystem'");
}

$pay_variant = 1;
if ($paysystem == 'sberpay') {
	
	if ($amount > 0 && $amount <= 500000) {
		
		$db->query("INSERT INTO pre_payments SET user_id = ?i, variant = ?s, amount = ?s", $user['id'], $pay_variant, $amount);
		$order_id = $db->insertId();
		$description = 'Оплата электроэнергии #'.$order_id." | участок №".$user['uchastok'];
		
		//$url = 'https://securepayments.sberbank.ru/payment/rest/register.do';
		$url = 'https://3dsec-payments.yookassa.ru/payment/rest/register.do';
		$post_data = array(
			'userName' => '420065',
			'password' => 'live_uPInOz8-HypOH1rSWAe3oisagQXdpnCzHSyneO34vXw',
			'orderNumber' => $order_id,
			'returnUrl' => 'https://xn----dtbffa7byadkn0c6c.xn--p1ai/cab/electricpower',
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
		
		if($result_json === false) {
			echo 'Ошибка curl: ' . curl_error($curl);
		}

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
	
} else if ($paysystem == 'yookassa') {
	
	var_dump($paysystem);
	
	/*$db->query("INSERT INTO pre_payments SET user_id = ?i, variant = ?s, amount = ?s", $user['id'], $pay_variant, $amount);
		$order_id = $db->insertId();
		$description = 'Оплата #'.$order_id;
		
		$url = 'https://securepayments.sberbank.ru/payment/rest/register.do';
		$post_data = array(
			'userName' => 'p2315139264-api',
			'password' => 'MhhuympX',
			'orderNumber' => $order_id,
			'returnUrl' => 'https://xn----dtbffa7byadkn0c6c.xn--p1ai/cab/electricpower',
			'description' => $description,
			'amount' => $amount * 100, 
			'email' => $user['email'],
			'phone' => $user['phone'],
		);*/
	
}

echo json_encode($json);
