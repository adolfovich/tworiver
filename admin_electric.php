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
			
			
			
			
			if (isset($_GET['del_ind'])) {
				//Удаляем показания
				mysql_query("DELETE FROM Indications WHERE id = ".$_GET['del_ind']) or die(mysql_error());
				//Откатываем баланс 
				mysql_query("UPDATE users SET balans = (balans + ".$_GET['sum']."), total_balance = (total_balance + ".$_GET['sum'].") WHERE id = ".$_GET['select_user']) or die(mysql_error());
				
				
				header("Location: admin_electric.php?select_user=".$_GET['select_user']);
			}
			if (isset($_GET['del_payment'])) {  
				//echo 'Удаляем платеж';
				mysql_query("DELETE FROM payments WHERE id = ".$_GET['del_payment']) or die(mysql_error());
				//echo 'Откатываем баланс';
				//echo "UPDATE users SET balans = (balans - ".$_GET['del_sum'].") WHERE id = ".$_GET['select_user'];				
				mysql_query("UPDATE users SET balans = (balans - ".$_GET['del_sum']."), total_balance = (total_balance - ".$_GET['del_sum'].") WHERE id = ".$_GET['select_user']) or die(mysql_error()); 
				
				//header("Location: admin_payments.php?select_user=".$_GET['select_user']);
			}
			
			
			//выбираем всех пользователей 
			$result_select_user = mysql_query("SELECT * FROM users WHERE is_del = 0 ORDER BY CONVERT(uchastok,SIGNED)") or die(mysql_error());
			
			if (isset($_GET['select_user']) && strlen($_GET['select_user']) != 0) {
				
				$selected_user = $_GET['select_user'];
				
				//Выбираем последний акт сверки по электроэнергии
				$result_last_act = mysql_query("SELECT date FROM acts WHERE type = 1 AND user = $selected_user ORDER BY date DESC LIMIT 1") or die(mysql_error());
				$last_act_date = mysql_result($result_last_act, 0);
				//echo 'actdate '.$last_act_date;
				
				$result_indications = mysql_query("SELECT i.id, i.additional_sum, i.date, i.Indications, i.additional as price, t.name AS tarif FROM Indications i, tarifs t WHERE i.user = ".$_GET['select_user']." AND i.tarif = t.id") or die(mysql_error());
				$result_user_payments = mysql_query("SELECT * FROM payments WHERE user = ".$_GET['select_user']) or die(mysql_error());
				
				//Выбираем все тарифы пользователя
				$result_user_tarifs = mysql_query("SELECT t.id, t.name FROM users_tarifs ut, tarifs t WHERE user = ".$_GET['select_user']." AND ut.tarif = t.id") or die(mysql_error());
				
				
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
				
				header("Location: admin_electric.php?select_user=".$_GET['select_user']);
			}
			//если отправлена форма с новыми показаниями
			if (isset($_GET['indications'])) {
				if (strlen($_GET['indications']) == 0 || $_GET['indications'] == 0 || $_GET['indications'] == '0' || $_GET['indications'] == '0.0' || $_GET['indications'] == '0.00' || $_GET['indications'] == '0,0' || $_GET['indications'] == '0,00' || $_GET['price'] == '0,00'  || strlen($_GET['price']) == 0) {
					$error_msg = '<script type="text/javascript">swal("Внимание!", "Не введены показания или введен 0 или цена 0", "error")</script>';
				}
				else {
					//Выбираем предыдущие показания
					$result_prev_inications = mysql_query("SELECT * FROM Indications WHERE user = ".$selected_user." AND tarif = ".$_GET['tarif']." ORDER BY id DESC LIMIT 1") or die(mysql_error());
					
					if (mysql_num_rows($result_prev_inications) == 0) {
						//$result_user_start_indications = mysql_query("SELECT * FROM users WHERE id = ".$selected_user) or die(mysql_error());
						$result_user_start_indications = mysql_query("SELECT * FROM users_tarifs WHERE user = ".$selected_user." AND tarif = ".$_GET['tarif']) or die(mysql_error());
						while ($start_indications = mysql_fetch_assoc($result_user_start_indications)) {
							$prev_inication = $start_indications['start_indications'];
						}						
					}
					else {
						while ($prev_inications = mysql_fetch_assoc($result_prev_inications)) {
							$prev_inication = $prev_inications['Indications'];
						}
					}
					
					
					
					//Приводим показания к float 
					$prev_inication = (float)$prev_inication;
					$inication = str_replace(",", ".", $_GET['indications']);
					$inication = (float)$inication;
					
					//Приводим стоимость к float 
					$price = str_replace(",", ".", $_GET['price']);
					$price = (float)$price;
					
					/*var_dump($prev_inication);
					echo '<br>';
					var_dump($inication);*/
					
					$diff_inication = $inication - $prev_inication;
					
					//Проверяем что бы новые показания былыи больше предыдущих
					if ($diff_inication > 0) {
						//Добавляем показания пользователю 
						$q_add_inications = "INSERT INTO Indications SET user = ".$selected_user.", tarif = ".$_GET['tarif'].", Indications = '".$inication."',	additional = $price, additional_sum = (".$diff_inication."*$price), date = '".$_GET['ind_date']."'";
						//echo $q_add_inications;
						mysql_query($q_add_inications) or die(mysql_error());
						//Обновляем баланс пользователя
						$q_upd_balans = "UPDATE users u SET u.balans = (u.balans - (".$diff_inication."*$price)), u.total_balance = (u.total_balance - (".$diff_inication."*$price)) WHERE u.id = ".$selected_user;
						mysql_query($q_upd_balans) or die(mysql_error());
						
						
						header("Location: admin_electric.php?select_user=".$_GET['select_user']);
					}
					else {
						$error_msg = '<script type="text/javascript">swal("Внимание!", "Введенные показания меньше предыдущих", "error")</script>';
					}
					
					
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
		
		<script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>

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
			.form_error {
				color: red;
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
									<h3>Показания</h3>
									<form method="GET">
										<div class="form-group">
											<label for="InputFIO">Выбрать пользователя</label>
											
											<select class="form-control" name="select_user" onchange="this.form.submit ()">
												<option value="">---</option>
												<?php
												while ($select_user = mysql_fetch_assoc($result_select_user)) {
													if ($selected_user == $select_user['id']) {
														echo '<option value="'.$select_user['id'].'" selected="selected">'.$select_user['uchastok'].' '.$select_user['name'].'</option>';
													}
													else {
														echo '<option value="'.$select_user['id'].'">'.$select_user['uchastok'].' '.$select_user['name'].'</option>';
													}
													
													
												}
												?>
											</select>
										</div>
										
									</form>
									
									<?php
									if (!isset($_GET['select_user']) || $_GET['select_user'] == '') $disabled_button = 'disabled';
									?>
									
									<a href="#addInd" class="btn btn-primary" data-toggle="modal" <?= $disabled_button;?>><i class="fa fa-plus" aria-hidden="true"></i> Добавить показания</a>
									<a href="#addPay" class="btn btn-primary" data-toggle="modal" <?= $disabled_button;?>><i class="fa fa-plus" aria-hidden="true"></i> Добавить платеж</a>
									<br><br>	
									
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
												<div class="form_error" id="AddPaymentError"></div>
											</form>
										  </div>
										  <!-- Футер модального окна -->
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
											<button type="button" class="btn btn-primary" onclick="checkAddPayment()">Сохранить</button>
											<script>
												function checkAddPayment() {													
													if (new Date(document.getElementById('payment_date').value) <= new Date("<?= $last_act_date;?>")) {
														document.getElementById('AddPaymentError').innerHTML = 'Дата платежа находится в закрытом периоде';
													} else {
														document.getElementById('AddPayment').submit(); return false;
													}
												}												
											</script>
										  </div>
										</div>
									  </div>
									</div>
									<!-- HTML-код модального окна -->
									<div id="addInd" class="modal fade">
									  <div class="modal-dialog">
										<div class="modal-content">
										  <!-- Заголовок модального окна -->
										  <div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">Добавление показаний</h4>
										  </div>
										  <!-- Основное содержимое модального окна -->
										  <div class="modal-body">
											<form method="GET" role="form" id="AddIndications">
												<div class="form-group">
													<label for="tarif">Тариф</label>
													<select class="form-control" name="tarif" id="tarif" onchange="getTarifPrice(this.value)">
														<?php
														while ($user_tarif = mysql_fetch_assoc($result_user_tarifs)) {
															$user_tarif_price = $user_tarif['price'];
															echo '<option value="'.$user_tarif['id'].'">'.$user_tarif['name'].'</option>';
														}
														?>
													</select>
												</div>
												<div class="form-group">
													<label for="indications_date">Дата</label>
													<input name="ind_date" type="date" class="form-control" id="indications_date" value="<?php echo $curdate; ?>">
												</div>
												<div class="form-group">
													<label for="indications">Показания</label>
													<input name="indications" type="text" class="form-control" id="indications" placeholder="0,00">
												</div>
												<div class="form-group">
													<label for="price">Стоимость</label>
													<input name="price" type="text" class="form-control" id="price" placeholder="0,00">
												</div>
												<input name="select_user" type="hidden" value="<?php echo $selected_user; ?>">
												<div class="form_error" id="AddIndicationsError"></div>
											</form>
										  </div>
										  <!-- Футер модального окна -->
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
											<button type="button" class="btn btn-primary" onclick="document.getElementById('AddIndications').submit(); return false;" >Сохранить</button>
											<script>
												function checkAddPayment() {													
													if (new Date(document.getElementById('indications_date').value) <= new Date("<?= $last_act_date;?>")) {
														document.getElementById('AddIndicationsError').innerHTML = 'Дата показаний находится в закрытом периоде';
													} else {
														document.getElementById('AddIndications').submit(); return false;
													}
												}												
											</script>
										  </div>
										</div>
									  </div>
									</div>
												
																		
									<ul class="nav nav-tabs">
									  <li class="active"><a href="#indications11" data-toggle="tab" >Показания</a></li>
									  <li><a href="#paymets" data-toggle="tab">Платежи</a></li>
									</ul>
									
									<div class="tab-content">
									  <div class="tab-pane fade in active" id="indications11">
										<br>
											<?php 
											if (isset($_GET['select_user']) && strlen($_GET['select_user']) != 0) {
												echo '<div class="table-responsive">';												
												echo '<table class="table table-condensed">';
												echo '<tr>';
												echo '<th>Дата</th>';
												echo '<th>Тариф</th>';
												echo '<th>Показания</th>';												
												echo '<th>Начислено</th>';
												echo '<th></th>';
												echo '</tr>';												
												while ($indications = mysql_fetch_assoc($result_indications)) {
													$date_indications = date( 'd.m.Y',strtotime($indications['date']));
													echo '<tr>';
													echo '<td>'. $date_indications.'</td>';
													echo '<td>'. $indications['tarif'].' - '.$indications['price'].' руб/кВт*ч</td>';
													echo '<td>'. $indications['Indications'].'</td>';													
													echo '<td>'. $indications['additional_sum'].'</td>';
													if (strtotime($indications['date']) <= strtotime($last_act_date)) {
														echo '<td style="text-align:center"><span class="fa-stack fa-lg"><i class="fa fa-trash fa-stack-1x" aria-hidden="true"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span></td>';
													}
													else {														
														echo '<td style="text-align:center"><a class="del_user" href="#" onclick="ConfirmDelInd('.$indications['id'].','.$selected_user.','.$indications['additional_sum'].')"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></a></td>';
													}													
													echo '</tr>';												}
												echo '</table>';												
												echo '';
												echo '</div>';
											}
											?>											
											<script>
												function ConfirmDelInd(ind_id, user_id, sum) 
												{
													swal({
														title: 'Удалить показания?',
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
														document.location.href = "admin_electric.php?del_ind="+ind_id+"&select_user="+user_id+"&sum="+sum;
													})
												}
											</script>
									  </div>
									  <div class="tab-pane fade" id="paymets">
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
													if (strtotime($user_payments['date']) <= strtotime($last_act_date)) {
														echo '<td style="text-align:center"><span class="fa-stack fa-lg"><i class="fa fa-trash fa-stack-1x" aria-hidden="true"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span></td>';
													}
													else {														
														echo '<td style="text-align:center"><a class="del_user" href="#" onclick="ConfirmDelPay('.$user_payments['id'].','.$selected_user.','.$user_payments['sum'].')"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></a></td>';
													}
													echo '</tr>';
												}
												echo '</table>';												
												echo '';
												echo '</div>';
											}
											?>											
											<script>
												function ConfirmDelPay(payment_id, user_id, sum) 
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
														  'Платеж удален.',
														  'success'
														);
														document.location.href = "admin_electric.php?del_payment="+payment_id+"&select_user="+user_id+"&del_sum="+sum;
													})
												}
											</script>
									  </div>
									  
									
									<!-- Tab panes
									<div class="tab-content">
										<div class="tab-pane fade in active" id="indications">
											
										</div>
										<div class="tab-pane fade" id="paymets">
																						
										</div>											
									</div>	 -->
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

		<script>
		function getXmlHttp(){
		  var xmlhttp;
		  try {
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		  } catch (e) {
			try {
			  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (E) {
			  xmlhttp = false;
			}
		  }
		  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
			xmlhttp = new XMLHttpRequest();
		  }
		  return xmlhttp;
		}
		
		
		function getTarifPrice(tarif) {
			var req = getXmlHttp()  
			req.onreadystatechange = function() {  
				if (req.readyState == 4) { 
					if(req.status == 200) { 
						document.getElementById("price").value = req.responseText;
					}
				}
			}
			req.open('GET', 'ajax/get_user_tarif.php?tarif='+tarif, true);  
			req.send(null);  
		}
		
		var price = document.getElementById("tarif").value;
		getTarifPrice(price);

		</script>
		
		
		

		
	</body>
</html>