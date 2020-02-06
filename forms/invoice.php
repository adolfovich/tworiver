<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

	include_once "../core/db_connect.php";
?>

<!DOCTYPE html>
<!-- <html lang="ru" onMouseOver="window.close();"> -->
<html>
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

	if (isset($_GET['pay_electric']) || isset($_GET['member_id']) || isset($_GET['target_id']) || isset($_GET['user'])) {

		//получаем реквизиты для платежа
		$result_requisites = mysql_query("SELECT * FROM requisites WHERE id = 1") or die(mysql_error());
		while ($requisites = mysql_fetch_assoc($result_requisites)) {
			$snt_name = $requisites['name'];
			$snt_inn = $requisites['inn'];
			$snt_bank_name = $requisites['bank_name'];
			$snt_bank_bik = $requisites['bank_bik'];
			$snt_bank_rs = $requisites['bank_rs'];
			$snt_bank_ks = $requisites['bank_ks'];
		}

		//var_dump($_GET['target_id']);
		$pay_members = $_GET['member_id'];
		$pay_targets = $_GET['target_id'];

		$pay_electric = str_replace(",", ".", $_GET['pay_electric']);

		$pay_electric = explode(".", $pay_electric);

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



		if ($pay_electric[0] > 0) {
			echo '<div class="page">';
			include "invoice_electric.php";
			include "invoice_electric.php";
			echo '</div>';
		}
		if (count($pay_members) > 0) {
			$members = [];
			$members_sum = 0;
			foreach ($pay_members as $value) {
				//выбираем платеж по его id что бы прописать сумму и назначение
				$result_member_detail = mysql_query("SELECT * FROM users_contributions WHERE id = $value") or die(mysql_error());
				while ($member_detail = mysql_fetch_assoc($result_member_detail)) {
					$member_period = $member_detail['quarter'] . ' квартал ' . $member_detail['year'].'('.$member_detail['sum'].'руб.)';
					$member_sum = $member_detail['sum'];
					//добавляем информацию в массив $members
					array_push($members, $member_period);
					//добавляем сумму взноса в общую сумму
					$members_sum = $members_sum + $member_detail['sum'];
				}
			}
			$members_sum = explode(".", $members_sum);
			echo '<div class="page">';
			include "invoice_member.php";
			include "invoice_member.php";
			echo '</div>';
		}
		if (count($pay_targets) > 0) {
			$targets = [];
			$targets_sum = 0;
			foreach ($pay_targets as $value) {
				//выбираем платеж по его id что бы прописать сумму и назначение
				$result_targets_detail = mysql_query("SELECT * FROM users_contributions WHERE id = $value") or die(mysql_error());
				while ($target_detail = mysql_fetch_assoc($result_targets_detail)) {
					$target_comment = $target_detail['comment'].'('.$target_detail['sum'].'руб.)';
					$target_sum = $target_detail['sum'];
					//добавляем информацию в массив $members
					array_push($targets, $target_comment);
					//добавляем сумму взноса в общую сумму
					$targets_sum = $targets_sum + $target_detail['sum'];
				}
			}
			$targets_sum = explode(".", $targets_sum);
			echo '<div class="page">';
			include "invoice_target.php";
			include "invoice_target.php";
			echo '</div>';
		}


	}


?>
	<script>
		//window.print();
		//window.close();
	</script>
	<script>
	 //setTimeout(function(){window.print();}, 500);
	 //window.onfocus = function(){setTimeout(function(){window.close();}, 500);};
	</script>
	</body>
</html>
