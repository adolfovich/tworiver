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
				mysql_query("UPDATE users SET balans = (balans + ".$_GET['sum'].") WHERE id = ".$_GET['select_user']) or die(mysql_error());
				
				header("Location: admin_indications.php?select_user=".$_GET['select_user']);
			}
			
			
			//выбираем всех пользователей 
			$result_select_user = mysql_query("SELECT * FROM users WHERE is_del = 0") or die(mysql_error());
			
			if (isset($_GET['select_user']) && strlen($_GET['select_user']) != 0) {
				
				$selected_user = $_GET['select_user'];
				//$q_indications = ;
				$result_indications = mysql_query("SELECT i.id, i.additional_sum, i.date, i.Indications, i.additional as price, t.name AS tarif FROM Indications i, tarifs t WHERE i.user = ".$_GET['select_user']." AND i.tarif = t.id") or die(mysql_error());
				
				//Выбираем все тарифы пользователя
				$result_user_tarifs = mysql_query("SELECT t.id, t.name FROM users_tarifs ut, tarifs t WHERE user = ".$_GET['select_user']." AND ut.tarif = t.id") or die(mysql_error());
				
				
			}
			
			//если отправлена форма с новыми показаниями
			if (isset($_GET['indications'])) {
				if (strlen($_GET['indications']) == 0 || $_GET['indications'] == 0 || $_GET['indications'] == '0' || $_GET['indications'] == '0.0' || $_GET['indications'] == '0.00' || $_GET['indications'] == '0,0' || $_GET['indications'] == '0,00' || $_GET['price'] == '0,00'  || strlen($_GET['price']) == 0) {
					$error_msg = '<script type="text/javascript">swal("Внимание!", "Не введены показания или введен 0 или цена 0", "error")</script>';
				}
				else {
					//Выбираем предыдущие показания
					$result_prev_inications = mysql_query("SELECT * FROM Indications WHERE user = ".$selected_user." ORDER BY id DESC LIMIT 1") or die(mysql_error());
					
					while ($prev_inications = mysql_fetch_assoc($result_prev_inications)) {
						$prev_inication = $prev_inications['Indications'];
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
						$q_upd_balans = "UPDATE users u SET u.balans = (u.balans - (".$diff_inication."*$price)) WHERE u.id = ".$selected_user;
						mysql_query($q_upd_balans) or die(mysql_error());
						//echo $q_upd_balans;
						
						header("Location: admin_indications.php?select_user=".$_GET['select_user']);
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
									<nav>
										<ul class="nav navbar-nav">
											<li><a href="admin_users.php">Пользователи</a></li>
											<li><a href="admin_indications.php">Показания</a></li>
											<li><a href="admin_payments.php">Платежи</a></li>
										</ul>
									</nav>
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
											//echo '<td><a href="#" class="btn btn-primary" data-toggle="modal" disabled="disabled"><i class="fa fa-rub" aria-hidden="true"></i> Оплатить</a></td>';
											echo '<td><a class="del_user" href="#" onclick="ConfirmDelInd('.$indications['id'].','.$selected_user.','.$indications['additional_sum'].')"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
											echo '</tr>';
										}
										echo '</table>';
										//echo '<br>';
										echo '<a href="#addInd" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> Добавить показания</a>';
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
												document.location.href = "admin_indications.php?del_ind="+ind_id+"&select_user="+user_id+"&sum="+sum;
											})
										}
									</script>
									
									
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
											</form>
										  </div>
										  <!-- Футер модального окна -->
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
											<button type="button" class="btn btn-primary" onclick="document.getElementById('AddIndications').submit(); return false;" >Сохранить</button>
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
		
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>

		
	</body>
</html>