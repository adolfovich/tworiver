<?php

	include_once "core/db_connect.php";
	include_once "core/func.php";
	include_once "include/auth.php";

	$curdate = date("Y-m-d");
	$curmonth = date("m");
	$curyear = date("Y");

//$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

$file_suffix = substr(str_shuffle($permitted_chars), 0, 16);

	$result_user_is_admin = mysql_query("SELECT is_admin FROM users WHERE email = '".$_COOKIE["user"]."'") or die(mysql_error());

	while ($user_is_admin = mysql_fetch_assoc($result_user_is_admin)) {
			$is_admin = $user_is_admin['is_admin'];
		}

	if ($is_auth == 1) {
		if ($is_admin == 1) {

			if (isset($_GET['electric'])) {
				$result_electric = mysql_query("SELECT u.*, (SELECT uc.num FROM users_contracts uc WHERE uc.user = u.id LIMIT 1) as cnum FROM users u WHERE u.is_del = 0") or die(mysql_error());

				$current = '';
				while ($electric = mysql_fetch_assoc($result_electric)) {
					$current = $current . $electric['uchastok'].','.$electric['name'].',+'.$electric['phone'].','.$electric['cnum'].','.$electric['sch_plomb_num'].','.number_format($electric['balans'], 2, '.', ' ').'р.'.PHP_EOL;
				}
				$file = 'temp/electric.csv';

				$fp = fopen($file, "w"); // ("r" - считывать "w" - создавать "a" - добовлять к тексту),мы создаем файл
				fwrite($fp, $current);
				fclose($fp);

				echo "<script>window.open('temp/electric.csv');</script>";
			} else if (isset($_GET['payments'])) {
				//var_dump($_POST);
				$first_day = date("Y-".$_POST['month']."-01 00:00:00");
				$last_day = date("Y-".$_POST['month']."-t 23:59:59");
				$sql = "SELECT p.*, (SELECT uchastok FROM users WHERE id = p.user) as uchastok FROM payments p WHERE p.date BETWEEN '$first_day' AND '$last_day'";
				//echo $sql;
				$result_payments = mysql_query($sql) or die(mysql_error());

				if (mysql_num_rows($result_payments)) {

					$current = '';
					while ($payments = mysql_fetch_assoc($result_payments)) {
						$current .= date("d.m.Y", strtotime($payments['date'])).','.
						date("H:i:s", strtotime($payments['date'])).','.
						$payments['uchastok'].','.
						number_format($payments['sum'], 2, '.', ' ').'р.,'.
						$payments['base'].','.PHP_EOL;
					}
					$file = 'temp/payments-'.$file_suffix.'.csv';
//echo '<br>';
//echo $current;
//echo '<br>';
					$fp = fopen($file, "w");
//var_dump($fp);
//echo '<br>';
					$fwrite = fwrite($fp, $current);

//var_dump($fwrite);
//echo '<br>';
					fclose($fp);

//$homepage = file_get_contents($file);
//var_dump($homepage);

					echo "<script>window.open('".$file."');</script>";
				} else {
					echo "<script>alert('За данный период нет данных!')</script>";
				}

			}
		}
	}



?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Система управления СНТ</title>

		<script src="js/jquery-3.3.1.min.js"></script>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">		<!-- Optional theme -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">		<!-- Latest compiled and minified JavaScript -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/sweetalert.css">
		<script src="js/sweetalert.min.js"></script>
		<link rel="stylesheet" href="css/my.css">

		<style>
			#header {
				background: url(img/header.jpg);
				min-height: 280px;
				background-size: cover;
				background-repeat: no-repeat;
			}
			.news_date {
				color: #777;
			}
			.del_user{
				color:Crimson;
			}
			.del_user:hover{
				color:red;
			}
		</style>

	</head>
	<body>
		<?php echo $error_msg; ?>
		<?php include_once "include/head.php"; ?>

		<div class="container">

					<?php
					if ($is_auth == 1) {
						if ($is_admin == 1) {
					?>
							<div class="row">
								<div class="col-md-12">
									<h2>Панель администратора</h2>
									<hr>
									<?php include_once "include/admin_menu.php"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<h3>Отчеты</h3>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
										<h4>Сводная балансово-расчетная ведомость потребителей электроэнергии <a class="btn btn-default" href="?electric"/>Сформировать</a></h4>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<form method="POST" action="?payments">
										<h4>
											Сводная ведомость по платежам за месяц &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<select name="month">
												<option value="01" <?php if (date("m") == 1) echo 'selected';?>>Январь</option>
												<option value="02" <?php if (date("m") == 2) echo 'selected';?>>Февраль</option>
												<option value="03" <?php if (date("m") == 3) echo 'selected';?>>Март</option>
												<option value="04" <?php if (date("m") == 4) echo 'selected';?>>Апрель</option>
												<option value="05" <?php if (date("m") == 5) echo 'selected';?>>Май</option>
												<option value="06" <?php if (date("m") == 6) echo 'selected';?>>Июнь</option>
												<option value="07" <?php if (date("m") == 7) echo 'selected';?>>Июль</option>
												<option value="08" <?php if (date("m") == 8) echo 'selected';?>>Август</option>
												<option value="09" <?php if (date("m") == 9) echo 'selected';?>>Сентябрь</option>
												<option value="10" <?php if (date("m") == 10) echo 'selected';?>>Октябрь</option>
												<option value="11" <?php if (date("m") == 11) echo 'selected';?>>Ноябрь</option>
												<option value="12" <?php if (date("m") == 12) echo 'selected';?>>Декабрь</option>
											</select>
											<button type="submit" class="btn btn-default"/>Сформировать</button>
										</h4>
									</form>
								</div>
							</div>
							<hr>
					<?php
						}
					}
					?>
		</div>

	</body>
</html>
