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

		if (isset($_GET['hagreement']) && isset($_GET['agreement']) && $_GET['agreement'] == 1) {
			//обновляем информацию о соглажении у пользователя
			mysql_query("UPDATE users SET user_agreement = 1 WHERE email = '".$_COOKIE["user"]."'") or die(mysql_error());

		}
		else if (isset($_GET['hagreement'])) {
			$error_msg = '<script type="text/javascript">swal("", "Вы не приняли Пользовательское соглашение", "error")</script>';
		}

		$q_user_detail = "SELECT * FROM users WHERE email = '".$_COOKIE["user"]."'";
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
		}

		//echo $user_agreement;



		//Выбираем действующий договор пользователя
		$q_user_conrtact = "SELECT * FROM users_contracts WHERE user = $user_id AND type = 1 AND date_end IS NULL";
		$result_user_conrtact = mysql_query($q_user_conrtact) or die(mysql_error());
		while ($user_conrtact = mysql_fetch_assoc($result_user_conrtact)) {
			$user_conrtact_num = $user_conrtact['num'];
			$user_conrtact_date = date('d.m.Y',strtotime($user_conrtact['date_start']));//echo $user_conrtact_date;
		}

		if (isset($_GET['changePhone'])) {
			if (strlen($_GET['changePhone']) != 0) {
				$chPhone = $_GET['changePhone'];
				if ($chPhone[0] == '8') {
					$chPhone = '7' . substr($chPhone, 1);
				}
				else if ($chPhone[0] == '+') {
					$chPhone = substr($chPhone, 1);
				}
				if (strlen($_GET['changeEmail']) != 0) {
					$chEmail = $_GET['changeEmail'];
					if (isset($_GET['changeSmsNotice']) && $_GET['changeSmsNotice'] == 1) {
						$changeSmsNotice = 1;
					}
					else {
						$changeSmsNotice = 0;
					}
					if (isset($_GET['changeEmailNotice']) && $_GET['changeEmailNotice'] == 1) {
						$changeEmailNotice = 1;
					}
					else {
						$changeEmailNotice = 0;
					}
					if (isset($_GET['oldPass']) && strlen($_GET['oldPass']) != 0) {
						//echo 'from base'.$pass_md5 . '<br>';
						//echo 'from GET'.md5($_GET['oldPass']) . '<br>';
						if ($pass_md5 == md5($_GET['oldPass'])) {
							if ($_GET['newPass'] == $_GET['newPass2']) {
								//меняем настройки и пароль
								$q_upd = "UPDATE users SET phone = '$chPhone', email = '$chEmail', sms_notice = $changeSmsNotice, email_notice = $changeEmailNotice, pass = '".md5($_GET['newPass'])."' WHERE email = '".$_COOKIE["user"]."'";
								//echo $q_upd;
								mysql_query($q_upd) or die(mysql_error());
								$error_msg = '<script type="text/javascript">swal("", "Настройки сохранены, пароль изменен", "success")</script>';
								header("Location: user.php");
							}
							else {
								$error = 'Веденные пароли не совпадают';
							}
						}
						else {
							$error = 'Старый пароль неверный';
						}
					}
					else {
						//меняем настройки и не меняем пароль
						$q_upd = "UPDATE users SET phone = '$chPhone', email = '$chEmail', sms_notice = $changeSmsNotice, email_notice = $changeEmailNotice WHERE email = '".$_COOKIE["user"]."'";
						//echo $q_upd;
						mysql_query($q_upd) or die(mysql_error());
						$error_msg = '<script type="text/javascript">swal("", "Настройки сохранены", "success")</script>';
						header("Location: user.php");
					}
				}
				else {
					$error = 'Поле Email не может быть пустым';
				}
			}
			else {
				$error = 'Поле номер телефона не может быть пустым';
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
		<?php echo $error; ?>
		<?php include_once "include/head.php"; ?>

		<div class="jumbotron" id="header">
			<div class="container" ></div>
		</div>

		<div class="container" style="padding-bottom: 50px;">

					<?php
					if ($is_auth == 1) {
					?>

						<?php
							if ($user_agreement == 0) {
						?>

						<div class="row">
							<div class="col-md-12">
								<center><h3>Принимая соглашение вы подверждаете что ознакомлены во всеми условиями. Прочитать полный текст солашения можно открывего по <a href="doc/user_agreement.html" target="_blank">ссылке</a></h3></center>
								<hr>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<p></p>

								<form method="GET">
									<div class="form-group">
										<center><label for="agreement">
											<input name="hagreement" type="hidden" value="1" >
											<input name="agreement" type="checkbox" id="agreement" value="1" >
											 Я принимаю условия соглашения
										</label></center>
									</div>
									<div class="form-group">
										<center><input type="submit" id="agreement" value="Подтвердить" id="submit_agree" ></center>
									</div>
								</form>
							</div>
						</div>

						<?php
							}
							else {
						?>
						<div class="row">
							<div class="col-md-6">
								<h2>Личный кабинет: участок №<?php echo $user_uchastok;?></h2>





								<p><a href="#editSettings" class="btn btn-primary" data-toggle="modal"><i class="fa fa-sliders" aria-hidden="true"></i> Изменить настройки</a></p>
									<!-- HTML-код модального окна -->
									<div id="editSettings" class="modal fade">
									  <div class="modal-dialog">
										<div class="modal-content">
										  <!-- Заголовок модального окна -->
										  <div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">Редактирование настроек</h4>
										  </div>
										  <!-- Основное содержимое модального окна -->
										  <div class="modal-body">
											<form method="GET" role="form" id="ChangeSettings">
												<div class="form-group">
													<label for="changePhone">Номер телефона</label>
													<input name="changePhone" type="text" class="form-control" id="changePhone" placeholder="+7XXXXXXXXXX" value="<?php echo '+'.$phone; ?>">
												</div>
												<div class="form-group">
													<label for="changeEmail">Email</label>
													<input name="changeEmail" type="text" class="form-control" id="changeEmail" placeholder="email@domine.ru" value="<?php echo $email; ?>">
												</div>
												<div class="form-group">
													<label for="changeSmsNotice">
													<?php
													if ($sms_notice == 1) {
														echo '<input name="changeSmsNotice" type="checkbox" id="changeSmsNotice" checked value="1">';
													}
													else {
														echo '<input name="changeSmsNotice" type="checkbox" id="changeSmsNotice" value="1">';
													}
													?>
													 Получать уведомление по SMS
												</label>
												</div>
												<div class="form-group">
													<label for="changeEmailNotice">
													<?php
													if ($email_notice == 1) {
														echo '<input name="changeEmailNotice" type="checkbox" id="changeEmailNotice" checked value="1">';
													}
													else {
														echo '<input name="changeEmailNotice" type="checkbox" id="changeEmailNotice" value="1">';
													}
													?>
													 Получать уведомление по Email
													</label>
												</div>
												<div class="form-group">
													<label for="changePass">Изменить пароль</label>
													<input name="oldPass" type="password" class="form-control" id="changePass" placeholder="Старый пароль" >
													<input name="newPass" type="password" class="form-control" id="newPass" placeholder="Новый пароль" >
													<input name="newPass2" type="password" class="form-control" id="newPass2" placeholder="Повторить новый пароль" >
												</div>
											</form>
										  </div>
										  <!-- Футер модального окна -->
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
											<button type="button" class="btn btn-primary" onclick="document.getElementById('ChangeSettings').submit(); return false;" >Сохранить</button>
										  </div>
										</div>
									  </div>
									</div>



							</div>
							<div class="col-md-6">
								<?php
									if ($total_balance < 0) {
										$total_balans_color = "color: red;";
									}
								?>
								<h2>Общий баланс <span style="<?php echo $total_balans_color; ?>"><?php echo $total_balance; ?> руб.</span>
								<?php
								if ($balans < 0) {
									echo '<a href="#PaymentVariant" class="btn btn-primary" data-toggle="modal">Оплатить</a>';
								}
								else {
									echo '<a href="#PaymentVariant" class="btn btn-primary" data-toggle="modal" disabled="disabled" >Оплатить</a>';
								}
								?>
								</h2>
								<!-- HTML-код модального окна -->
								<div id="PaymentVariant" class="modal fade">
								  <div class="modal-dialog">
									<div class="modal-content">
									  <!-- Заголовок модального окна -->
									  <div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h4 class="modal-title">Варианты оплаты</h4>
									  </div>
									  <!-- Основное содержимое модального окна -->
									  <div class="modal-body">
											<form  role="form">
												<div class="form-inline">
													<div class="form-group">
														<?php
															if ($balans < 0) {
																echo '<input class="form-control" type="text" value="'.-$balans.'" id="pay_electric">&nbsp;<label><input id="checked_el" type="checkbox" checked > Электроэнергия</label>';
															}
															else {
																echo '<input class="form-control" type="text" value="0" id="pay_electric">&nbsp;<label><input id="checked_el" type="checkbox" > Электроэнергия</label>';
															}
														?>
													</div>
												</div>
												<div class="form-inline">
													<div class="form-group">
														<?php
															if ($member_balans < 0) {
																echo '<input class="form-control" type="hidden" value="'.-$member_balans.'" id="pay_member">&nbsp;<!-- <label><input id="checked_mem" type="checkbox" checked> Членские взносы</label>-->';
															}
															else {
																echo '<input class="form-control" type="hidden" value="0" id="pay_member">&nbsp;<!--<label><input id="checked_mem" type="checkbox"> Членские взносы</label>-->';
															}
														?>

													</div>
												</div>
												<div class="form-inline">
													<div class="form-group">
														<?php
															if ($target_balans < 0) {
																echo '<input class="form-control" type="hidden" value="'.-$target_balans.'"  id="pay_target">&nbsp;<!--<label><input id="checked_tar" type="checkbox" checked> Целевые взносы</label>-->';
															}
															else {
																echo '<input class="form-control" type="hidden" value="0" id="pay_target">&nbsp;<!--<label><input id="checked_tar" type="checkbox"> Целевые взносы</label>-->';
															}
														?>

													</div>
												</div>
											</form>
									  </div>
									  <!-- Футер модального окна -->
									  <div class="modal-footer">
											<button type="button" class="btn btn-primary" disabled>Онлайн оплата</button>
											<button type="button" class="btn btn-primary" onclick="toPrintInvoice(); return false;">Распечатать квитанцию</button>
											<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
										</div>
									</div>
								  </div>
								</div>
							</div>
							<script>
								function toPrintInvoice() {
									var pay_electric
									var pay_member = document.getElementById("pay_member").value; //удалить присваивание когда появится членский взнос
									var pay_target = document.getElementById("pay_target").value; //удалить присваивание когда появится целевой взнос


									  if (document.getElementById("checked_el").checked) { pay_electric = document.getElementById("pay_electric").value;}
									  else { pay_electric = 0 ; }

										//Раскоментировать когда появится членский взнос
										/*if (document.getElementById("checked_mem").checked) { pay_member = document.getElementById("pay_member").value;}
									  else { pay_member = 0 ; }

										//Раскоментировать когда появится целевой взнос
										if (document.getElementById("checked_tar").checked) { pay_target = document.getElementById("pay_target").value;}
									  else { pay_target = 0 ; }*/

										if ( pay_electric <= 0 && pay_member <= 0 && pay_target <= 0) {
											swal("", "Не выбран ни один платеж или все платежи равны нулю", "error");
										}
										else {
											window.open('forms/invoice.php?pay_electric='+pay_electric+'&pay_member='+pay_member+'&pay_target='+pay_target+'&user=<?php echo $user_id; ?>','_blank');
										}

								}
							</script>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<h3>Договор на электропотребление №<?php echo $user_conrtact_num;?> от <?php echo $user_conrtact_date;?></h3>

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
						<div class="row">
								<div class="col-md-12">
									<!-- Nav tabs -->
									<ul class="nav nav-tabs">
									  <li class="active"><a href="#indications" data-toggle="tab">Детализация показаний</a></li>
									  <li><a href="#payments" data-toggle="tab">Детализация платежей</a></li>
									</ul>
									<!-- Tab panes -->
									<div class="tab-content">
									  <div class="tab-pane fade in active" id="indications">
											<h4>Начальные показания: <?php echo $start_indications; ?> кВт*ч</h4>
											<table class="table table-striped">
												<tr>
													<th>Дата</th>
													<th>Тариф</th>
													<th>Показания кВт*ч</th>
													<th>Сумма по тарифу руб.</th>
												</tr>
												<?php
												//Выбираем все показания пользователя
												$result_user_all_indications = mysql_query("SELECT i.id, i.date, i.Indications, i.additional, i.additional_sum, t.name as tarif FROM Indications i, tarifs t WHERE user = (SELECT id FROM users WHERE email = '".$_COOKIE['user']."') AND t.id = i.tarif") or die(mysql_error());
												while ($user_all_indications = mysql_fetch_assoc($result_user_all_indications)) {
													$ind_date = date("d.m.Y", strtotime($user_all_indications['date']));
													echo '<tr>';
													echo '<td>'.$ind_date.'</td>';
													echo '<td>'.$user_all_indications['tarif'].' - '.$user_all_indications['additional'].' руб./кВт*ч</td>';
													echo '<td>'.$user_all_indications['Indications'].'</td>';
													echo '<td>'.$user_all_indications['additional_sum'].'</td>';
													echo '</tr>';
												}
												?>
											</table>
										</div>
									  <div class="tab-pane fade" id="payments">
											<h4>Начальный баланс: <?php echo $start_balans; ?> руб.</h4>
											<table class="table table-striped">
												<tr>
													<th>Дата</th>
													<th>Сумма, руб.</th>
													<th>Основание</th>
												</tr>
												<?php
												//Выбираем все показания пользователя
												$result_user_all_payments = mysql_query("SELECT * FROM payments WHERE user = (SELECT id FROM users WHERE email = '".$_COOKIE['user']."')") or die(mysql_error());
												while ($user_all_payments = mysql_fetch_assoc($result_user_all_payments)) {
													$p_date = date("d.m.Y", strtotime($user_all_payments['date']));
													echo '<tr>';
													echo '<td>'.$p_date.'</td>';
													echo '<td>'.$user_all_payments['sum'].'</td>';
													echo '<td>'.$user_all_payments['base'].'</td>';
													echo '</tr>';
												}
												?>
										</div>
									</div>
								</div>
						</div>
						<?php } ?>
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

		<script>
		$('#indications a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		})
		$('#payments a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		})
		</script>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>


	</body>
</html>
