<?php
	include_once "../core/db_connect.php";
?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Печать платежа - Система управления СНТ</title>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
		<!-- Latest compiled and minified JavaScript -->
	</head>
	<body>

	<style type="text/css" media="print">
      div.page
      {
        page-break-after: always;
        page-break-inside: avoid;
      }
    </style>

<?php

	if (isset($_GET['pay_electric']) && isset($_GET['pay_member']) && isset($_GET['pay_target'])  && isset($_GET['user'])) {

		$pay_electric = explode(".", $_GET['pay_electric']);
		$pay_member = $_GET['pay_member'];
		$pay_target = $_GET['pay_target'];
		$id_user = $_GET['user'];

		$q_user_detail = "SELECT * FROM users WHERE id = $id_user";
		$result_user_detail = mysql_query($q_user_detail) or die(mysql_error());

		while ($user_detail = mysql_fetch_assoc($result_user_detail)) {
			$user_id = $user_detail['id'];
			$user_uchastok = $user_detail['uchastok'];
			$user_sch_model = $user_detail['sch_model'];
			$user_sch_num = $user_detail['sch_num'];
			$user_sch_plomb_num = $user_detail['sch_plomb_num'];
			$balans = $user_detail['balans'];
			$total_balance = $user_detail['total_balance'];
			$phone = $user_detail['phone'];
			$email = $user_detail['email'];
			$sms_notice = $user_detail['sms_notice'];
			$email_notice = $user_detail['email_notice'];
			$pass_md5 = $user_detail['pass'];
			$user_agreement = $user_detail['user_agreement'];
			$start_indications = $user_detail['start_indications'];
			$start_balans = $user_detail['start_balans'];
			$user_name = $user_detail['name'];
		}
		//echo $user_name;
		/*foreach ($user_uchastok as $value) {
			echo "вывод<br>";
			echo "$value<br>";
		}*/
		/*$tmp = str_split($pay_electric[1]);
		var_dump($tmp[0]);*/

		if ($pay_electric > 0) {
			echo '<div class="page">';
			include "invoice_electric.php";
			include "invoice_electric.php";
			echo '</div>';
		}
		if ($pay_member > 0) {
			/*echo '<div class="page">';
			include "invoice_member.php";
			include "invoice_member.php";
			echo '</div>';*/
		}
		if ($pay_target > 0) {
			/*echo '<div class="page">';
			include "invoice_target.php";
			include "invoice_target.php";
			echo '</div>';*/
		}
	}

?>
	<script>//window.print();</script>
	</body>
</html>
