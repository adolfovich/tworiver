<?php
include ('../_conf.php');
include ('../classes/safemysql.class.php');
$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

require_once('../classes/core.class.php');

	$core  = new Core();
	$link = 'http://api.prostor-sms.ru/messages/v2/send/';
	$sms_login = 't89181116385';
	$sms_pass = '305446';
	$sender = 'SNTDvureche';

$result = $db->getAll("SELECT * FROM `users` WHERE `send_monthly_sms` = 1");

foreach ($result as $row) {
		$text = 'Ваша задолженность за электроэнергию в СНТ Двуречье %sum%руб.';
		$balance = $db->getOne("SELECT balance FROM purses WHERE type = 1 AND user_id = ?i", $row['id']);
		if ($balance < 0) {
			echo 'user: '.$row['name']."\r\n";
			$sum = abs($balance);
			echo 'sum: '.$sum."\r\n";
			$text = str_replace('%sum%', $sum, $text);
			$text = str_replace(' ', '%20', $text);
      $url = $link.'?login='.$sms_login.'&password='.$sms_pass.'&sender='.$sender.'&phone=%2B'.$row['phone'].'&text='.$text;
      echo $url."\r\n";
    	$result_sms = file_get_contents($url);
    	echo $result_url."\r\n";
		}
	}
