<?php

  //222

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

	if (isset($_GET['ind_period'])) {
		$curmonth = $_GET['ind_period'];
	}
	else {
		$curmonth = date("Y-m");
	}

	//echo $curmonth;

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

		//получаем баланс пользователя по целевым взносам
		$result_target_balans = mysql_query("SELECT target_balans FROM users WHERE email = '".$_COOKIE['user']."'") or die(mysql_error());
		while ($target_balans = mysql_fetch_assoc($result_target_balans)) {
			$user_target_balans = $target_balans['target_balans'];
		}
		if ($user_target_balans < 0) {
			$target_balans_color = "color: red;";
		}
		else {
			$target_balans_color = '';
		}

		//получаем баланс пользователя по членским взносам
		$result_membership_balans = mysql_query("SELECT membership_balans FROM users WHERE email = '".$_COOKIE['user']."'") or die(mysql_error());
		while ($membership_balans = mysql_fetch_assoc($result_membership_balans)) {
			$user_membership_balans = $membership_balans['membership_balans'];
		}
		if ($user_membership_balans < 0) {
			$membership_balans_color = "color: red;";
		}
		else {
			$membership_balans_color = '';
		}

		$result_acts = mysql_query("SELECT * FROM acts WHERE user = (SELECT id FROM users WHERE email = '".$_COOKIE['user']."')") or die(mysql_error());

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
			th {text-align:center; vertical-align: middle !important; border: 1px rgb(221, 221, 221) solid;}
			td {text-align:center; vertical-align: middle !important;}
		</style>

		<script>
			var arr = [];
			var i = 0;
			$(":checkbox").change(function(){
				if(this.checked){
					arr[i] = $(this).val();
					i++;
				}else{
					var val = $(this).val();
					var index = arr.indexOf(val);
					arr.splice(index, 1);
					i--;
				}
				console.log(arr);
			});
		</script>

	</head>
	<body>


		<?php
		if (isset($error_msg)) {
			echo $error_msg;
		}

		if (isset($error)) {
			echo $error;
		}
		?>
		<?php include_once "include/head.php"; ?>

		<div class="jumbotron" id="header">
			<div class="container" ></div>
		</div>

		<div class="container">

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
								<div class="row">
									<h2 class="pull-right">Общий баланс <span style="<?php echo $total_balans_color; ?>"><?php echo $total_balance; ?> руб.</span></h2>
								</div>
								<div class="row">
									<?php
									if ($total_balance < 0) {
										echo '<a href="#PaymentVariant" class="btn btn-primary pull-right" data-toggle="modal">Оплатить</a>';
									}
									else {
										echo '<a href="#PaymentVariant" class="btn btn-primary pull-right" data-toggle="modal" disabled="disabled" >Оплатить</a>';
									}
								?>
								</div>
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
															if ($user_membership_balans < 0) {
																echo '<br>';
																echo '<input class="form-control" type="text" value="'.-$user_membership_balans.'" id="pay_member" disabled>&nbsp;<label>Членские взносы</label>';
																echo '<br>';
																echo '<div id="member_elements">';
																$result_no_paid_member = mysql_query("SELECT * FROM users_contributions WHERE user = (SELECT id FROM users WHERE email = '".$_COOKIE['user']."') AND contribution_type = 1 AND paid = 0") or die(mysql_error());
																while ($no_paid_member = mysql_fetch_assoc($result_no_paid_member)) {
																	echo '<p style="margin: 0;">';
																	echo '<label class="pay_label">';
																	echo '<input type="checkbox" value="'.$no_paid_member['sum'].'" data-id="'.$no_paid_member['id'].'" onclick="editMember(this.value, this.checked)" checked> '.$no_paid_member['quarter'].' кватрал '.$no_paid_member['year'].' ('.$no_paid_member['sum'].'руб.)';
																	echo '</label>';
																	echo '</p>';
																}
																echo '</div>';
															}
															else {
																echo '<input class="form-control" type="hidden" value="0" id="pay_member">&nbsp;<!--<label><input id="checked_mem" type="checkbox"> Членские взносы</label>-->';
																echo '<div id="member_elements"></div>';
															}
														?>
														<script>
														function editMember(sum, checked) {
															var itog;
															var memberInput = document.getElementById("pay_member");
															if (checked) {
																itog = Number(memberInput.value) + Number(sum);
																memberInput.value = itog;
															}
															else if (!checked) {
																itog = Number(memberInput.value) - Number(sum);
																memberInput.value = itog;
															}

															//alert(itog);
														}
														</script>
													</div>
												</div>
												<div class="form-inline">
													<div class="form-group">
														<?php
															if ($user_target_balans < 0) {
																echo '<br>';
																echo '<input class="form-control" type="text" value="'.-$user_target_balans.'" id="pay_target" disabled>&nbsp;<label>Целевые взносы</label>';
																echo '<br>';
																echo '<div id="target_elements">';
																$result_no_paid_target = mysql_query("SELECT * FROM users_contributions WHERE user = (SELECT id FROM users WHERE email = '".$_COOKIE['user']."') AND contribution_type = 2 AND paid = 0") or die(mysql_error());
																while ($no_paid_target = mysql_fetch_assoc($result_no_paid_target)) {
																	echo '<p style="margin: 0;">';
																	echo '<label class="pay_label">';
																	echo '<input type="checkbox" value="'.$no_paid_target['sum'].'" data-id="'.$no_paid_target['id'].'" onclick="editTarget(this.value, this.checked)" checked> '.$no_paid_target['comment'].' ('.$no_paid_target['sum'].'руб.)';
																	echo '</label>';
																	echo '</p>';
																}
																echo '</div>';
															}
															else {
																echo '<input class="form-control" type="hidden" value="0" id="pay_target">&nbsp;<!--<label><input id="checked_mem" type="checkbox"> Целевые взносы</label>-->';
																echo '<div id="target_elements"></div>';
															}
														?>
														<script>
														function editTarget(sum, checked) {
															var itog;
															var targetInput = document.getElementById("pay_target");
															if (checked) {
																itog = Number(targetInput.value) + Number(sum);
																targetInput.value = itog;
															}
															else if (!checked) {
																itog = Number(targetInput.value) - Number(sum);
																targetInput.value = itog;
															}

															//alert(itog);
														}
														</script>
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
									var pay_member = document.getElementById("pay_member").value;
									var pay_target = document.getElementById("pay_target").value;


									  if (document.getElementById("checked_el").checked) { pay_electric = document.getElementById("pay_electric").value;}
									  else { pay_electric = 0 ; }

										var allMember = document.getElementById('member_elements').getElementsByTagName('input').length;
										var urlMember = '';
										for (var i = 0; i <= allMember-1; i++) {
											if (document.getElementById('member_elements').getElementsByTagName('input')[i].checked) {
												urlMember = urlMember + '&member_id[]=' + document.getElementById('member_elements').getElementsByTagName('input')[i].getAttribute('data-id');
											}

										}

										var allTarget = document.getElementById('target_elements').getElementsByTagName('input').length;
										var urlTarget = '';
										for (var i = 0; i <= allTarget-1; i++) {
											if (document.getElementById('target_elements').getElementsByTagName('input')[i].checked){
												urlTarget = urlTarget + '&target_id[]=' + document.getElementById('target_elements').getElementsByTagName('input')[i].getAttribute('data-id');
											}
										}

										if ( pay_electric <= 0 && pay_member <= 0 && pay_target <= 0) {
											swal("", "Не выбран ни один платеж или все платежи равны нулю", "error");
										}
										else {
											var fullUrl = 'forms/invoice.php?pay_electric='+pay_electric + urlMember + urlTarget + '&user=<?php echo $user_id; ?>';

											window.open(fullUrl,'_blank');

										}

								}
							</script>
						</div>
						<hr>

						<?php
							if ($balans < 0) {
								$balans_color = "color: red;";
							}
						?>

						<div class="row">
							<div class="panel panel-default">
								<div class="panel-heading spoiler-trigger" data-toggle="collapse" style="padding: 0; border: none; background: none;">
									<button type="button" class="btn btn-default spoiler-trigger" data-toggle="collapse" style="width: 100%; box-shadow: none; border: none; border-radius: 0;">
										<h3>
											<span class="pull-left">Энергопотребление</span>
											<span class="pull-right">Баланс:
												<span style="<?php echo $balans_color; ?>"><?php echo $balans;?></span>
											руб.</span>
										</h3>
									</button>
								</div>
								<div class="panel-collapse collapse out">
									<div class="panel-body">
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

											<?php $result_all_tarifs = mysql_query("SELECT * FROM tarifs") or die(mysql_error()); ?>

											<div class="col-md-6">
											  <h3>Текущие тарифы на энергопотребление</h3>
											  <?php
											  while ($all_tarifs = mysql_fetch_assoc($result_all_tarifs)) {
												  echo '<p>'.$all_tarifs['name'].' - <span style="color: red;">'.$all_tarifs['price'].'</span> р/кВт*ч</p>';
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
												  <li><a href="#acts" data-toggle="tab">Акты сверки</a></li>
												</ul>
												<!-- Tab panes -->
												<div class="tab-content">
												  <div class="tab-pane fade in active" id="indications">
														<h4>Начальные показания: <?php echo $start_indications; ?> кВт*ч</h4>

														<!---------------------------------------->
														<form class="form-inline" id="ind_period">
															<label class="" for="inlineFormInput">Месяц/год</label>&nbsp&nbsp
															<input class="form-control" type="month" name="ind_period" value="<?= $curmonth; ?>" onChange="document.getElementById('ind_period').submit()">
															<input name="select_user" type="hidden" value="<?= $selected_user; ?>">
														</form>
														<br>
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

															echo '</tr>';
															echo '<tr>';
															echo '<th>Начало</th>';
															echo '<th>Конец</th>';
															echo '<th>Расход</th>';
															echo '</tr>';

															////////////////
															$result_indications = mysql_query("SELECT i.auto, i.id, i.additional_sum, i.date, i.prev_indications, i.Indications, i.additional as price, t.name AS tarif FROM Indications i, tarifs t WHERE i.user = (SELECT id FROM users WHERE email = '".$_COOKIE['user']."') AND t.id_waviot = '".$all_tarifs['id_waviot']."' AND i.tarif = t.id AND i.date BETWEEN '".$curmonth."-01' AND '".$curmonth."-31'") or die(mysql_error());

															while ($indications = mysql_fetch_assoc($result_indications)) {
																$date_indications = date( 'd.m.Y',strtotime($indications['date']));
																echo '<tr>';
																echo '<td>'. $date_indications.'</td>';
																echo '<td>'. $indications['tarif'].'</td>';
																echo '<td>'. $indications['prev_indications'].'</td>';
																echo '<td>'. $indications['Indications'].'</td>';
																echo '<td>'.($indications['Indications'] - $indications['prev_indications']).'</td>';
																echo '<td>'. $indications['price'].'</td>';
																echo '<td>'. $indications['additional_sum'].'</td>';

																echo '</tr>';

															}
															////////////////

														echo '</table>';
														echo '</div>';
														$active = 0;
														}
														?>

														</div>
														<!---------------------------------------->



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
													</table>
												  </div>
												  <div class="tab-pane fade" id="acts">
														<p></p>
														<a href="forms/act_reconciliation.php?user=<?php echo $user_id; ?>" class="btn btn-default" target="_blank"><i class="fa fa-print" aria-hidden="true"></i> Распечатать акт сверки</a>
														<p></p>
														<?php
														if(mysql_num_rows($result_acts) > 0) {
															while ($acts = mysql_fetch_assoc($result_acts)) {
																echo '<p><a href="'.$acts['path'].'" target="_blank">'.date( 'd.m.Y',strtotime($acts['date'])).' - '.$acts['comment'].'</a></p>';
															}
														}
														else {
															echo '<p>Актов не найдено</p>';
														}
														?>
												  </div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="panel panel-default">
								<div class="panel-heading spoiler-trigger" data-toggle="collapse" style="padding: 0; border: none; background: none;">
									<button type="button" class="btn btn-default spoiler-trigger" data-toggle="collapse" style="width: 100%; box-shadow: none; border: none; border-radius: 0;">
										<h3>
											<span class="pull-left">Членские взносы</span>
											<span class="pull-right">Баланс:
												<span style="<?php echo $membership_balans_color; ?>"><?php echo $user_membership_balans; ?></span>
											руб.</span>
										</h3>
									</button>
								</div>
								<div class="panel-collapse collapse out">
									<div class="panel-body">
										<div class="row">
											<div class="col-md-12">
												<h3></h3>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">

											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<!-- Nav tabs -->
												<ul class="nav nav-tabs">
												  <li class="active"><a href="#membership" data-toggle="tab">Детализация взносов</a></li>
												</ul>
												<!-- Tab panes -->
												<div class="tab-content">
												  <div class="tab-pane fade in active" id="membership">
													<table class="table table-striped">
														<tr>
															<th>Дата</th>
															<th>Период</th>
															<th>Сумма</th>
															<th>Оплачен</th>
														</tr>
														<?php
															$result_membership_contribution = mysql_query("SELECT * FROM users_contributions WHERE user = (SELECT id FROM users WHERE email = '".$_COOKIE['user']."') AND contribution_type = 1") or die(mysql_error());
															while ($membership_contribution = mysql_fetch_assoc($result_membership_contribution)) {
																$date_membership = date( 'd.m.Y',strtotime($membership_contribution['date']));
																$date_membership_paid = date( 'd.m.Y',strtotime($membership_contribution['paid_date']));

																if ($membership_contribution['paid'] == 1) {
																	$tr_style = '';
																}
																else {
																	$tr_style = 'danger';
																}

																echo '<tr class="'.$tr_style.'">';
																	echo '<td>'.$date_membership.'</td>';
																	echo '<td>'.$membership_contribution['quarter'].' квартал '.$membership_contribution['year'].'</td>';
																	echo '<td>'.$membership_contribution['sum'].'руб.</td>';
																	if ($membership_contribution['paid'] == 1) {
																		echo '<td>'.$date_membership_paid.'</td>';
																	}
																	else {
																		echo '<td >Не оплачен</td>';
																	}
																echo '</tr>';

															}
														?>
													</table>
												  </div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="panel panel-default">
								<div class="panel-heading spoiler-trigger" data-toggle="collapse" style="padding: 0; border: none; background: none;">
									<button type="button" class="btn btn-default spoiler-trigger" data-toggle="collapse" style="width: 100%; box-shadow: none; border: none; border-radius: 0;">
										<h3>
											<span class="pull-left">Целевые взносы</span>
											<span class="pull-right">Баланс:
												<span style="<?php echo $target_balans_color; ?>"><?php echo $user_target_balans; ?></span>
											руб.</span>
										</h3>
									</button>
								</div>
								<div class="panel-collapse collapse out">
									<div class="panel-body">
										<div class="row">
											<div class="col-md-12">
												<h3></h3>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">

											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<!-- Nav tabs -->
												<ul class="nav nav-tabs">
												  <li class="active"><a href="#membership" data-toggle="tab">Детализация взносов</a></li>
												</ul>
												<!-- Tab panes -->
												<div class="tab-content">
												  <div class="tab-pane fade in active" id="membership">
													<table class="table table-striped">
														<tr>
															<th>Дата</th>
															<th>Сумма</th>
															<th>Комментарий</th>
															<th>Оплата</th>
														</tr>
														<?php
															$result_target_contribution = mysql_query("SELECT * FROM users_contributions WHERE user = (SELECT id FROM users WHERE email = '".$_COOKIE['user']."') AND contribution_type = 2") or die(mysql_error());
															while ($target_contribution = mysql_fetch_assoc($result_target_contribution)) {
																$date_target = date( 'd.m.Y',strtotime($target_contribution['date']));
																$date_target_paid = date( 'd.m.Y',strtotime($target_contribution['paid_date']));

																if ($target_contribution['paid'] == 1) {
																	$tr_style = '';
																}
																else {
																	$tr_style = 'danger';
																}

																echo '<tr class="'.$tr_style.'">';
																	echo '<td>'.$date_target.'</td>';
																	echo '<td>'.$target_contribution['sum'].'руб.</td>';
																	echo '<td>'.$target_contribution['comment'].'</td>';
																	if ($target_contribution['paid'] == 1) {
																		echo '<td>'.$date_target_paid.'</td>';
																	}
																	else {
																		echo '<td >Не оплачен</td>';
																	}
																echo '</tr>';

															}
														?>
													</table>
												  </div>
												</div>
											</div>
										</div>
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
			$(".spoiler-trigger").click(function() {
				$(this).parent().next().collapse('toggle');
			});
		</script>

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
