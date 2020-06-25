<?php
session_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

include ('../../../_conf.php');
include ('../../../classes/safemysql.class.php');
$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

require_once('../../../classes/core.class.php');

$core  = new Core();

$url = $core->url;
$form = $core->form;
$ip = $core->ip;
$get = $core->setGet();

if (isset($_SESSION['id'])) {
  $user_id = $_SESSION['id'];
  $user_info = $db->getRow("SELECT * FROM users WHERE id = ?i", $user_id);
}
?>

<!DOCTYPE html>
<!-- <html lang="ru" onMouseOver="window.close();"> -->
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Печать квитанции - Система управления СНТ</title>

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

	if (isset($_GET['pay_electric']) || isset($_GET['pay_membership']) || isset($_GET['pay_target'])) {

		//получаем реквизиты для платежа
		$snt_name = $core->cfgRead('requisites_full_name');
		$snt_inn = $core->cfgRead('requisites_inn');
		$snt_bank_name = $core->cfgRead('requisites_bank_name');
		$snt_bank_bik = $core->cfgRead('requisites_bank_bik');
		$snt_bank_rs = $core->cfgRead('requisites_bank_rs');
		$snt_bank_rs2 = $core->cfgRead('requisites_bank_rs2');
		$snt_bank_ks = $core->cfgRead('requisites_bank_ks');

		$pay_members = $_GET['member_id'];
		$pay_targets = $_GET['target_id'];

		$pay_electric = str_replace(",", ".", $_GET['pay_electric']);
		$pay_electric = explode(".", $pay_electric);

		$id_user = $_GET['user'];

		$user_detail = $user_info;
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

		if (isset($_GET['pay_electric']) && $pay_electric[0] > 0) {
			echo '<div class="page">';
			include "invoice_electric.php";
			include "invoice_electric.php";
			echo '</div>';
		} else if (isset($_GET['pay_membership']) && $_GET['pay_membership'] > 0) {
			$members_sum = explode(".", $_GET['pay_membership']);
			echo '<div class="page">';
			include "invoice_member.php";
			include "invoice_member.php";
			echo '</div>';
		} else if (isset($_GET['pay_target']) && $_GET['pay_target'] > 0) {
			
			$targets_sum = explode(".", $_GET['pay_target']);
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
