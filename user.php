<?php
	
	include_once "core/db_connect.php";
	include_once "include/auth.php";
	
	$month_name = array(
		1 => 'января', 
		2 => 'февраля', 
		3 => 'марта',
		4 => 'апреля', 
		5 => 'мая', 
		6 => 'июня', 
		7 => 'июля', 
		8 => 'августа', 
		9 => 'сентября', 
		10 => 'октября', 
		11 => 'ноября', 
		12 => 'декабря' 
	);
	
	$curdate = date("Y-m-d");
	
	if ($is_auth == 1) { 
	
		$q_user_detail = "SELECT * FROM users WHERE email = '".$_COOKIE["user"]."'";
		$result_user_detail = mysql_query($q_user_detail) or die(mysql_error());
		
		while ($user_detail = mysql_fetch_assoc($result_user_detail)) {
			$user_id = $user_detail['id'];
			$user_uchastok = $user_detail['uchastok'];
			$user_sch_model = $user_detail['sch_model'];
			$user_sch_num = $user_detail['sch_num'];
			$user_sch_plomb_num = $user_detail['sch_plomb_num'];
			$balans = $user_detail['balans'];
			
		}
		
		//Выбираем действующий договор пользователя
		$q_user_conrtact = "SELECT * FROM users_contracts WHERE user = $user_id AND type = 1 AND date_end IS NULL";
		$result_user_conrtact = mysql_query($q_user_conrtact) or die(mysql_error());
		while ($user_conrtact = mysql_fetch_assoc($result_user_conrtact)) {
			$user_conrtact_num = $user_conrtact['num'];
			$user_conrtact_date = date('d.m.Y',strtotime($user_conrtact['date_start']));//echo $user_conrtact_date;
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
		</style>
		
	</head>
	<body>
		<?php echo $error_msg; ?>
		<?php include_once "include/head.php"; ?>
		
		<div class="jumbotron" id="header">
			<div class="container" ></div>
		</div>
		
		<div class="container" style="padding-bottom: 50px;">
			
				
				
				  
					<?php 
					if ($is_auth == 1) { 
					?>
						<div class="row">
							<div class="col-md-12">
								<h2>Личный кабинет: участок №<?php echo $user_uchastok;?></h2>
								<hr>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<h3>Договор на электропотребление №<?php echo $user_conrtact_num;?> от <?php echo $user_conrtact_date;?></h3>
								<hr>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
							  <h3>Данные об установленном счетчике</h3>
							  <p><strong>Марка: </strong><?php echo $user_sch_model;?></p>
							  <p><strong>Номер: </strong><?php echo $user_sch_num;?></p>
							  <p><strong>Пломба №: </strong><?php echo $user_sch_plomb_num;?></p>
							</div>
							<div class="col-md-4">
								<?php
									if ($balans < 0) {
										$balans_color = "color: red;";
									}
								


								?>
								<h3>
									Баланс: <span style="<?php echo $balans_color; ?>"><?php echo $balans;?></span> 
									
									<?php
									if ($balans < 0) {
										echo '<button type="button" class="btn btn-primary">Оплатить</button>';
									}
									else {
										echo '<button type="button" class="btn btn-primary" disabled="disabled" >Оплатить</button>';
									}
									?>
									
									</h3>
								<?php 
								//выбираем тарифы которые есть у пользователя
								//echo ;

								$result_user_tarifs = mysql_query("SELECT t.id as id, ut.tarif as tarif_id, t.name as tarif_name FROM users_tarifs ut, tarifs t WHERE ut.tarif = t.id AND ut.user = (SELECT id FROM users WHERE email = '".$_COOKIE["user"]."')") or die(mysql_error());
								
								echo '<p><strong>Последние показания: </strong></p>';
								while ($user_tarif = mysql_fetch_assoc($result_user_tarifs)) {
									
									//Выбираем показания по тарифу
									$result_user_indications = mysql_query("SELECT * FROM Indications WHERE user = (SELECT id FROM users WHERE email = '".$_COOKIE['user']."') AND tarif = ".$user_tarif['id']." ORDER BY date DESC LIMIT 1") or die(mysql_error());
																		
									while ($user_indications = mysql_fetch_assoc($result_user_indications)) {
										
										
										echo '<p><strong>'. $user_tarif['tarif_name'].': </strong>'.$user_indications['Indications'].'</p>';
									}
										
								}
								
								//Выбираем последний платеж пользователя
								$result_user_payment = mysql_query("SELECT * FROM payments WHERE user = (SELECT id FROM users WHERE email = '".$_COOKIE['user']."') ORDER BY date DESC LIMIT 1") or die(mysql_error());
								while ($user_payment = mysql_fetch_assoc($result_user_payment)) {
									$payment_date = date("d.m.Y", strtotime($user_payment['date']));
									echo '<p><strong>Последний платеж: </strong> '.$user_payment['sum'].'р от '.$payment_date.'</p>';
								}
								?>

								
								
							</div>
						</div>
					<?php 
					} 
					else 
					{
					?>
					  <div class="col-md-12">
						  <h2>Вы не авторизованы</h2>
						  <hr>
					  </div>
					<?php
					}
					?>
			   
			
			<hr>
		</div>
		
		<?php include_once "include/footer.php"; ?>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>

		
	</body>
</html>