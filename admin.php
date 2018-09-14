<?php
/* test2 */
	
	include_once "core/db_connect.php";
	include_once "include/auth.php";
	
	
	
	$curdate = date("Y-m-d");
	
	if ($is_auth == 1) { 
	
		$result_user_is_admin = mysql_query("SELECT is_admin FROM users WHERE email = '".$_COOKIE["user"]."'") or die(mysql_error());
		
		while ($user_is_admin = mysql_fetch_assoc($result_user_is_admin)) {
			$is_admin = $user_is_admin['is_admin'];
		}
		
		if ($is_admin == 1) {
			
			
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
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

		<link rel="stylesheet" href="css/font-awesome.min.css">

		<link rel="stylesheet" href="css/sweetalert.css">
		<link rel="stylesheet" href="css/my.css">
		
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
								<div class="col-md-6">
								  <h3>Должники</h3>
								  <p></p>
								  <h4><u>Электроэнергия</u></h4>
								  <p></p>
								  <?php 
									//$sum_debtos = 0;
									
									//ищем всех пользователей у кого баланс меньше нуля
									$result_debtos = mysql_query("SELECT * FROM users WHERE balans < 0 AND is_del = 0") or die(mysql_error());
		
									while ($debtos = mysql_fetch_assoc($result_debtos)) {
										echo '<p>'. $debtos['name'].', участок №'.$debtos['uchastok'].' <span style="color: red;">'.$debtos['balans'].'</span></p>';
										$sum_debtos_balans = $sum_debtos_balans + $debtos['balans'];
									}
								  ?>
								  <h4>Итого по электроэнергии: <?=$sum_debtos_balans;?></h4>
								  <h4><u>Членские взносы</u></h4>
								  <p></p>
								  <?php 
									//ищем всех пользователей у кого баланс меньше нуля
									$result_debtos = mysql_query("SELECT * FROM users WHERE membership_balans < 0 AND is_del = 0") or die(mysql_error());
		
									while ($debtos = mysql_fetch_assoc($result_debtos)) {
										echo '<p>'. $debtos['name'].', участок №'.$debtos['uchastok'].' <span style="color: red;">'.$debtos['membership_balans'].'</span></p>';
										$sum_debtos_membership_balans = $sum_debtos_membership_balans + $debtos['membership_balans'];
									}
								  ?>
								  <h4>Итого по членским взносам: <?=$sum_debtos_membership_balans;?></h4>
								  <h4><u>Целевые взносы</u></h4>
								  <p></p>
								  <?php 
									//ищем всех пользователей у кого баланс меньше нуля
									$result_debtos = mysql_query("SELECT * FROM users WHERE target_balans < 0 AND is_del = 0") or die(mysql_error());
		
									while ($debtos = mysql_fetch_assoc($result_debtos)) {
										echo '<p>'. $debtos['name'].', участок №'.$debtos['uchastok'].' <span style="color: red;">'.$debtos['target_balans'].'</span></p>';
										$sum_debtos_target_balans = $sum_debtos_target_balans + $debtos['target_balans'];
									}
								  ?>
								  <h4>Итого по целевым взносам: <?=$sum_debtos_membership_balans;?></h4>
								  <p></p>
								  <h3><strong>Итого по СНТ: <span style="color: red;"><?php echo $sum_debtos_balans+$sum_debtos_membership_balans+$sum_debtos_membership_balans; ?></span></strong></h3>
								</div>
								<div class="col-md-4">
								
								</div>
							</div>
						
						<?php 
						}
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
