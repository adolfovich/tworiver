<?php
	
	include_once "core/db_connect.php";
	include_once "include/auth.php";
	
	$curdate = date("Y-m-d");
	
	$curdate1 = date("d.m.Y");
	
	if ($is_auth == 1) { 
	
		$result_user_is_admin = mysql_query("SELECT is_admin FROM users WHERE email = '".$_COOKIE["user"]."'") or die(mysql_error());
		
		while ($user_is_admin = mysql_fetch_assoc($result_user_is_admin)) {
			$is_admin = $user_is_admin['is_admin'];
		}
		
		if ($is_admin == 1) {
			
			if (isset($_GET['del_payment'])) {  
				echo 'Удаляем платеж';
				mysql_query("DELETE FROM payments WHERE id = ".$_GET['del_payment']) or die(mysql_error());
				//echo 'Откатываем баланс';
				//echo "UPDATE users SET balans = (balans - ".$_GET['del_sum'].") WHERE id = ".$_GET['select_user'];				
				mysql_query("UPDATE users SET balans = (balans - ".$_GET['del_sum']."), total_balance = (total_balance - ".$_GET['del_sum'].") WHERE id = ".$_GET['select_user']) or die(mysql_error()); 
				
				//header("Location: admin_payments.php?select_user=".$_GET['select_user']);
			}
			
			//выбираем всех пользователей 
			$result_select_user = mysql_query("SELECT * FROM users WHERE is_del = 0") or die(mysql_error());
			
			if (isset($_GET['select_user']) && strlen($_GET['select_user']) != 0) {
				$selected_user = $_GET['select_user'];
				//Выбираем все платежи пользователя
				$result_user_payments = mysql_query("SELECT * FROM payments WHERE user = ".$_GET['select_user']) or die(mysql_error());
			}
			
			if (isset($_GET['sum']) && strlen($_GET['sum']) == 0) {
				$error_msg = '<script type="text/javascript">swal("Внимание!", "Не введена сумма", "error")</script>';
			}
			else if (isset($_GET['sum']) && $_GET['sum'] == 0) {
				$error_msg = '<script type="text/javascript">swal("Внимание!", "Сумма не может быть равна нулю", "error")</script>';
			}
			else if (isset($_GET['sum'])){
				//Приводим сумму к float 
				$sum = str_replace(",", ".", $_GET['sum']);
				$sum = (float)$sum;
				
				//Добавляем платеж пользователю 
				$q_add_payment = "INSERT INTO payments SET user = ".$selected_user.", sum = $sum, date = '".$_GET['payment_date']."',	base = '".$_GET['base']."'";
				mysql_query($q_add_payment) or die(mysql_error());
				
				//Обновляем баланс пользователя
				$q_upd_balans = "UPDATE users u SET u.balans = (u.balans + $sum), u.total_balance = (u.total_balance + $sum) WHERE u.id = ".$selected_user; 
				//echo $q_upd_balans;
				mysql_query($q_upd_balans) or die(mysql_error());
				
				header("Location: admin_payments.php?select_user=".$_GET['select_user']);
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
		
		
		
		<div class="container" style="padding-bottom: 50px;">
			
				
				
				  
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
								  <h3>Платежи</h3>
									<form method="GET">
										<div class="form-group">
											<label for="InputFIO">Выбрать пользователя</label>
											
											<select class="form-control" name="select_user" onchange="this.form.submit ()">
												<option value="">---</option>
												<?php
												while ($select_user = mysql_fetch_assoc($result_select_user)) {
													if ($selected_user == $select_user['id']) {
														echo '<option value="'.$select_user['id'].'" selected="selected">'.$select_user['name'].' Участок №'.$select_user['uchastok'].'</option>';
													}
													else {
														echo '<option value="'.$select_user['id'].'">'.$select_user['name'].' Участок №'.$select_user['uchastok'].'</option>';
													}
													
													
												}
												?>
											</select>
										</div>
										
									</form>
									<br>
									<?php 
									if (isset($_GET['select_user']) && strlen($_GET['select_user']) != 0) {
										echo '<div class="table-responsive">';
										
										echo '<table class="table table-condensed">';
										echo '<tr>';
										echo '<th>Дата</th>';
										echo '<th>Сумма</th>';
										echo '<th>Основание</th>';
										
										echo '<th></th>';
										echo '</tr>';
										
										while ($user_payments = mysql_fetch_assoc($result_user_payments)) {
											$date_payment = date( 'd.m.Y',strtotime($user_payments['date']));
											echo '<tr>';
											echo '<td>'. $date_payment.'</td>';
											echo '<td>'. $user_payments['sum'].'</td>';
											echo '<td>'. $user_payments['base'].'</td>';
											
											echo '<td><a class="del_user" href="#" onclick="ConfirmDelInd('.$user_payments['id'].','.$selected_user.','.$user_payments['sum'].')"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
											echo '</tr>';
										}
										echo '</table>';
										
										echo '<a href="#addPay" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> Добавить платеж</a>';
										echo '</div>';
									}
									?>
									
									<script>
										function ConfirmDelInd(payment_id, user_id, sum) 
										{
											swal({
												title: 'Удалить платеж?',
												text: 'Восстановление будет невозможно!',
												type: 'warning',
												showCancelButton: true,
												confirmButtonColor: '#dd6b55',
												cancelButtonColor: '#999',
												confirmButtonText: 'Да, удалить',
												cancelButtonText: 'Отмена',
												closeOnConfirm: false
											}, function() {
												swal(
												  'Выполнено!',
												  'Показания удалены.',
												  'success'
												);
												document.location.href = "admin_payments.php?del_payment="+payment_id+"&select_user="+user_id+"&del_sum="+sum;
											})
										}
									</script>
									
									
									<!-- HTML-код модального окна -->
									<div id="addPay" class="modal fade">
									  <div class="modal-dialog">
										<div class="modal-content">
										  <!-- Заголовок модального окна -->
										  <div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">Добавление платежа</h4>
										  </div>
										  <!-- Основное содержимое модального окна -->
										  <div class="modal-body">
											<form method="GET" role="form" id="AddPayment">
												
												<div class="form-group">
													<label for="payment_date">Дата</label>
													<input name="payment_date" type="date" class="form-control" id="payment_date" value="<?php echo $curdate; ?>">
												</div>
												<div class="form-group">
													<label for="sum">Сумма</label>
													<input name="sum" type="text" class="form-control" id="sum" placeholder="0,00">
												</div>
												<div class="form-group">
													<label for="base">Основание</label>
													<input name="base" type="text" class="form-control" id="base" placeholder="Основание" value="Ручной ввод">
												</div>
												<input name="select_user" type="hidden" value="<?php echo $selected_user; ?>">
											</form>
										  </div>
										  <!-- Футер модального окна -->
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
											<button type="button" class="btn btn-primary" onclick="document.getElementById('AddPayment').submit(); return false;" >Сохранить</button>
										  </div>
										</div>
									  </div>
									</div>
									
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
						  
					  </div>
					<?php
					}
					?>
			   
			
			
		</div>
		
		
		
		<?php include_once "include/footer.php"; ?>

				
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>

		
	</body>
</html>
