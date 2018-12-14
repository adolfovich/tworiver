<?php
	include_once "db_connect.php";
	$link = 'http://api.prostor-sms.ru/messages/v2/send/';
	$sms_login = 't89181116385';
	$sms_pass = '305446';
	$sender = 'SNTDvureche';
	$text = 'Ваша задолженность за электроэнергию в СНТ Двуречье %sum%руб.';

	$result = mysql_query("SELECT * FROM `users` WHERE `send_monthly_sms` = 1");

	while ($row = mysql_fetch_array($result)) {
		if ($row['balans'] < 0) {
			echo 'user: '.$row['name']."\r\n";
			$sum = abs($row['balans']);
			$text = str_replace('%sum%', $sum, $text);
			$text = str_replace(' ', '%20', $text);
	                $url = $link.'?login='.$sms_login.'&password='.$sms_pass.'&sender='.$sender.'&phone=%2B'.$row['phone'].'&text='.$text;
        	        echo $url."\r\n";
                	$result_sms = file_get_contents($url);
                	echo $result_url."\r\n";
		}
	}

