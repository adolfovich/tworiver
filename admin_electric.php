<?php

	include_once "core/db_connect.php";
	include_once "include/auth.php";

	$curdate = date("Y-m-d");

	$curdate1 = date("d.m.Y");

	if (isset($_GET['ind_period'])) {
		$curmonth = $_GET['ind_period'];
	}
	else {
		$curmonth = date("Y-m");
	}

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
				mysql_query("UPDATE users SET balans = (balans - ".$_GET['del_sum']."), total_balance = (total_balance - ".$_GET['del_sum'].") WHERE id = ".$_GET['select_user']) or die(mysql_error());

				//header("Location: admin_payments.php?select_user=".$_GET['select_user']);
			}


			//выбираем всех пользователей
			$result_select_user = mysql_query("SELECT * FROM users WHERE is_del = 0 ORDER BY CONVERT(uchastok,SIGNED)") or die(mysql_error());


			if (isset($_GET['payment_sum']) && strlen($_GET['payment_sum']) == 0) {
				$error_msg = '<script type="text/javascript">swal("Внимание!", "Не введена сумма", "error")</script>';
			}
			else if (isset($_GET['payment_sum']) && $_GET['payment_sum'] == 0) {
				$error_msg = '<script type="text/javascript">swal("Внимание!", "Сумма не может быть равна нулю", "error")</script>';
			}
			else if (isset($_GET['payment_sum'])){
				//Приводим сумму к float
				$sum = str_replace(",", ".", $_GET['payment_sum']);
				$sum = (float)$sum;

				//Добавляем платеж пользователю
				$q_add_payment = "INSERT INTO payments SET user = ".$_GET['select_user'].", sum = $sum, date = '".$_GET['payment_date']."',	base = '".mysql_real_escape_string($_GET['payment_base'])."'";
				var_dump($q_add_payment);
				mysql_query($q_add_payment) or die(mysql_error());

				//Обновляем баланс пользователя
				$q_upd_balans = "UPDATE users u SET u.balans = (u.balans + $sum), u.total_balance = (u.total_balance + $sum) WHERE u.id = ".$_GET['select_user'];
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
					$result_prev_inications = mysql_query("SELECT * FROM Indications WHERE user = ".$_GET['select_user']." AND tarif = ".$_GET['tarif']." ORDER BY id DESC LIMIT 1") or die(mysql_error());

					if (mysql_num_rows($result_prev_inications) == 0) {
						//$result_user_start_indications = mysql_query("SELECT * FROM users WHERE id = ".$selected_user) or die(mysql_error());
						$result_user_start_indications = mysql_query("SELECT * FROM users_tarifs WHERE user = ".$_GET['select_user']." AND tarif = ".$_GET['tarif']) or die(mysql_error());
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

					$diff_inication = $inication - $prev_inication;

					//Проверяем что бы новые показания былыи больше предыдущих
					if ($diff_inication > 0) {
						//Добавляем показания пользователю
						$q_add_inications = "INSERT INTO Indications SET user = ".$_GET['select_user'].", tarif = ".$_GET['tarif'].", Indications = '".$inication."', prev_indications = '".$prev_inication."',	additional = $price, additional_sum = (".$diff_inication."*$price), date = '".$_GET['ind_date']."'";
						//echo $q_add_inications;
						mysql_query($q_add_inications) or die(mysql_error());
						//Обновляем баланс пользователя
						$q_upd_balans = "UPDATE users u SET u.balans = (u.balans - (".$diff_inication."*$price)), u.total_balance = (u.total_balance - (".$diff_inication."*$price)) WHERE u.id = ".$_GET['select_user'];
						mysql_query($q_upd_balans) or die(mysql_error());

						header("Location: admin_electric.php?select_user=".$_GET['select_user']);
					}
					else {
						$error_msg = '<script type="text/javascript">swal("Внимание!", "Введенные показания меньше предыдущих", "error")</script>';
					}
				}
			}
			if (isset($_GET['select_user']) && strlen($_GET['select_user']) != 0) {

				$selected_user = $_GET['select_user'];

				//Выбираем последний акт сверки по электроэнергии
				$result_last_act = mysql_query("SELECT date_end FROM acts WHERE type = 1 AND user = $selected_user ORDER BY date_end DESC LIMIT 1") or die(mysql_error());
				$last_act_date = mysql_result($result_last_act, 0);
				//echo 'actdate '.$last_act_date;

				$result_user_payments = mysql_query("SELECT * FROM payments WHERE user = ".$_GET['select_user']) or die(mysql_error());

				//Выбираем все тарифы пользователя
				$result_user_tarifs = mysql_query("SELECT t.id, t.name FROM users_tarifs ut, tarifs t WHERE user = ".$_GET['select_user']." AND ut.tarif = t.id") or die(mysql_error());
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
			th {text-align:center; vertical-align: middle !important; border: 1px rgb(221, 221, 221) solid;}
			td {text-align:center; vertical-align: middle !important;}
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
											<label for="payment_sum">Сумма</label>
											<input name="payment_sum" type="text" class="form-control" id="payment_sum" placeholder="0,00">
										</div>
										<div class="form-group">
											<label for="payment_base">Основание</label>
											<input name="payment_base" type="text" class="form-control" id="payment_base" placeholder="Основание" value="Ручной ввод">
										</div>
										<input name="select_user" type="hidden" value="<?php echo $selected_user; ?>">
										<div class="form_error" id="AddPaymentError"></div>
									</form>
								</div>
								<!-- Футер модального окна -->
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
									<button type="button" class="btn btn-primary" onclick="checkAddPayment(); return false;">Сохранить</button>
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
									<button type="button" class="btn btn-primary" onclick="checkAddIndications(); return false;" >Сохранить</button>
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
							?>
							<div class="table-responsive">
								<form class="form-inline" id="ind_period">
									<label for="inlineFormInput">Месяц/год</label> &nbsp;&nbsp;
									<input name="ind_period" id="ind_period_input" class="form-control" type="month" value="<?= $curmonth; ?>" onChange="changeIndPeriod()">
									<input name="ind_select_user" id="ind_user_input" type="hidden" value="<?= $selected_user; ?>">
								</form>
								<script>
									function changeIndPeriod() {
										var month = document.getElementById('ind_period_input').value;
										var user = document.getElementById('ind_user_input').value;
										//alert(month+' '+user);

										$.post(
										  "core/select_ind.php",
										  {
										    month: month,
										    user: user
										  },
										  onAjaxSuccess
										);

										function onAjaxSuccess(data)
										{
										  //alert(data);
											document.getElementById('ind_contaner').innerHTML = data;
										}
									}
								</script>


								<br>
								<div id='ind_contaner'>
									<ul class="nav nav-tabs">
									<?php
									//Выбираем все существующие тарифы
									$active = 1;
									$all_tarifs_result = mysql_query("SELECT * FROM tarifs") or die(mysql_error());
									while ($all_tarifs = mysql_fetch_assoc($all_tarifs_result)) {
										if ($active == 1) {
											echo '<li class="active"><a href="#'.$all_tarifs['id_waviot'].'" data-toggle="tab" >'.$all_tarifs['name'].'</a></li>';
										}
										else {
											echo '<li><a href="#'.$all_tarifs['id_waviot'].'" data-toggle="tab" >'.$all_tarifs['name'].'</a></li>';
										}
										$active = 0;
									}
									?>
									</ul>
								<div class="tab-content">
								<?php
								//Выбираем все существующие тарифы
								$active = 1;
								$all_tarifs_result = mysql_query("SELECT * FROM tarifs") or die(mysql_error());
								while ($all_tarifs = mysql_fetch_assoc($all_tarifs_result)) {
									if ($active == 1) {
										echo '<div class="tab-pane fade in active" id="'.$all_tarifs['id_waviot'].'">';
									}
									else {
										echo '<div class="tab-pane fade" id="'.$all_tarifs['id_waviot'].'">';
									}

									echo '<table class="table table-condensed">';
									echo '<tr>';
									echo '<th rowspan="2">Дата</th>';
									echo '<th rowspan="2">Тариф</th>';
									echo '<th colspan="3">Показания</th>';
									echo '<th rowspan="2">Цена</th>';
									echo '<th rowspan="2">Начислено</th>';
									echo '<th rowspan="2"></th>';
									echo '</tr>';
									echo '<tr>';
									echo '<th>Начало</th>';
									echo '<th>Конец</th>';
									echo '<th>Расход</th>';
									echo '</tr>';
									$result_indications = mysql_query("SELECT i.auto, i.id, i.additional_sum, i.date, i.prev_indications, i.Indications, i.additional as price, t.name AS tarif FROM Indications i, tarifs t WHERE i.user = ".$_GET['select_user']." AND t.id_waviot = '".$all_tarifs['id_waviot']."' AND i.tarif = t.id AND i.date BETWEEN '".$curmonth."-01' AND '".$curmonth."-31'") or die(mysql_error());

									while ($indications = mysql_fetch_assoc($result_indications)) {
										echo '<tr>';
										echo '<td>'. date( 'd.m.Y',strtotime($indications['date'])).'</td>';
										echo '<td>'. $indications['tarif'].'</td>';
										echo '<td>'. $indications['prev_indications'].'</td>';
										echo '<td>'. $indications['Indications'].'</td>';
										echo '<td>'. round($indications['Indications'] - $indications['prev_indications'], 2).'</td>';
										echo '<td>'. $indications['price'].'</td>';
										echo '<td>'. $indications['additional_sum'].'</td>';
										if (strtotime($indications['date']) <= strtotime($last_act_date) || $indications['auto'] == 1) {
											echo '<td style="text-align:center"><span class="fa-stack fa-lg"><i class="fa fa-trash fa-stack-1x" aria-hidden="true"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span></td>';
										}
										else {
											echo '<td style="text-align:center"><a class="del_user" href="#" onclick="ConfirmDelInd('.$indications['id'].','.$selected_user.','.$indications['additional_sum'].')"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></a></td>';
										}
										echo '</tr>';

									}
								echo '</table>';
								echo '</div>';
								$active = 0;
								}
								?>

								</div>
							<?php
							}
							?>
							</div>
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
								echo '</div>';
							}
							?>
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
		// prepare the form when the DOM is ready
		$(document).ready(function() {

		// Setup the ajax indicator
		$('body').append('<div id="ajaxBusy"><p><img src="img/load.gif"></p></div>');

		$('#ajaxBusy').css({
			display:"none",
			margin:"0px",
			paddingLeft:"0px",
			paddingRight:"0px",
			paddingTop:"0px",
			paddingBottom:"0px",
			position:"absolute",
			right:"50%",
			top:"500px",
			 width:"auto"
		});
		});

		// Ajax activity indicator bound to ajax start/stop document events
		$(document).ajaxStart(function(){
		$('#ajaxBusy').show();
		}).ajaxStop(function(){
		$('#ajaxBusy').hide();
		});

			function ConfirmDelPay(payment_id, user_id, sum) {
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

			function ConfirmDelInd(ind_id, user_id, sum) {
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

			function checkAddIndications() {
				if (new Date(document.getElementById('indications_date').value) <= new Date("<?= $last_act_date;?>")) {
					document.getElementById('AddIndicationsError').innerHTML = 'Дата показаний находится в закрытом периоде';
				} else if (document.getElementById('indications').value.length == 0 || document.getElementById('indications').value == 0) {
					document.getElementById('AddIndicationsError').innerHTML = 'Показания не могут быть пустыми или равняться нулю';
				} else if (document.getElementById('price').value.length == 0 || document.getElementById('price').value == 0) {
					document.getElementById('AddIndicationsError').innerHTML = 'Стоимость не может быть пустой или равняться нулю';
				} else {
					document.getElementById('AddIndications').submit(); return false;
				}
			}

			function checkAddPayment() {
				if (new Date(document.getElementById('payment_date').value) <= new Date("<?= $last_act_date;?>")) {
					document.getElementById('AddPaymentError').innerHTML = 'Дата платежа находится в закрытом периоде';
				} else if (document.getElementById('payment_sum').value == 0 || document.getElementById('payment_sum').value.length == 0) {
					document.getElementById('AddPaymentError').innerHTML = 'Сумма не может быть пустой или равна нулю';
				} else {
					document.getElementById('AddPayment').submit();
				}
			}

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
