<?php

	include_once "core/db_connect.php";
	include_once "core/func.php";
	include_once "include/auth.php";

	$curdate = date("Y-m-d");
	$curmonth = date("m");
	$curyear = date("Y");
	
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

		<script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
		<!-- Latest compiled and minified JavaScript -->
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
							<hr>
					<?php
						}
					}
					?>					
							
							
		</div>
	
	</body>
</html>