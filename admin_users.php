<?php

	include_once "core/db_connect.php";
	include_once "core/func.php";
	include_once "include/auth.php";

	$curdate = date("Y-m-d");

	if ($is_auth == 1) {
		$result_user_is_admin = mysql_query("SELECT is_admin FROM users WHERE email = '".$_COOKIE["user"]."'") or die(mysql_error());
		while ($user_is_admin = mysql_fetch_assoc($result_user_is_admin)) {
			$is_admin = $user_is_admin['is_admin'];
		}
		if ($is_admin == 1) {
			if (isset($_GET['change_counter']) && $_GET['change_counter'] == 1) {
				$error_msg = '<script type="text/javascript">swal("", "Счетчик заменен", "success")</script>';
			}
			//Выгружаем из базы тарифы на электроэнергию
			$result_tarifs = mysql_query("SELECT * FROM tarifs") or die(mysql_error());
			while ($row = mysql_fetch_assoc($result_tarifs)){
				$tarifs_arr[]=$row;
			}
			//загрузка акта сверки
			if (isset($_FILES['addActFile']['tmp_name'])) {
				$uploaddir = 'uploads/';
				$uploadfile = $uploaddir . generatestr(12).'.pdf';
				if ($_FILES['addActFile']['type'] != 'application/pdf') {
					$error_msg = '<script type="text/javascript">swal("Внимание!", "Файл не является PDF документом", "error")</script>';					
				}
				else if (move_uploaded_file($_FILES['addActFile']['tmp_name'], $uploadfile)) {
					$q_file_path = "INSERT INTO acts SET user = ".$_POST['addActUch'].", date_start = '".$_POST['addActDateStart']."', date_end = '".$_POST['addActDateEnd']."', comment = '".$_POST['addActComment']."', path = '$uploadfile', type = " . $_POST['addActType'];
					mysql_query($q_file_path) or die(mysql_error());
					$error_msg = '<script type="text/javascript">swal("", "Файл загружен ", "success")</script>';
				}
			}
			if (isset($_GET['del_user']) && strlen($_GET['del_user'])!=0) {
				//Ставим пользователю пометку об удалении
				mysql_query("UPDATE users SET is_del = 1 WHERE id = ".$_GET['del_user']) or die(mysql_error());
				header("Location: admin_users.php");
			}
			if (isset($_GET['fio']) && strlen($_GET['fio'])!=0) {
				$add_fio = $_GET['fio'];
				$add_email = $_GET['email'];
				$create_email = $_GET['create_email'];
				$add_phone = $_GET['phone'];
				$add_password = md5($_GET['password']);
				$add_uchastok = $_GET['uchastok'];
				$add_sch_model = $_GET['sch_model'];
				$add_sch_num = $_GET['sch_num'];
				$add_sch_modem_num = $_GET['sch_modem_num'];
				$add_sch_pl_num = $_GET['sch_pl_num'];
				$add_start_ind = $_GET['start_ind'];
				$add_start_bal = $_GET['start_bal'];
				$add_contract_num = $_GET['contract_num'];
				$add_contract_date = $_GET['contract_date'];

				if ($add_phone[0] == '8') {
					$phone = '7' . substr($add_phone, 1);
				}
				else if ($add_phone[0] == '+') {
					$phone = substr($add_phone, 1);
				}

				$q_add_user = "INSERT INTO users SET modem_num = '$add_sch_modem_num', name = '$add_fio', email = '$add_email', pass = '$add_password', phone='$phone', uchastok = '$add_uchastok', sch_model = '$add_sch_model', sch_num = '$add_sch_num', sch_plomb_num = '$add_sch_pl_num', balans = $add_start_bal, start_balans = $add_start_bal";
				//echo $q_add_user;
				mysql_query($q_add_user) or die(mysql_error());

				if ($create_email == 1) {				
					//Обрезаем email до имени пользователя
					$email_user_name = substr($add_email, 0, strpos($add_email, "@"));
					//Создаем попьзователю почтовый ящик на домене
					shell_exec("curl -H 'PddToken: WXDQN7U72I7E5YYIZBGIQIJC6KR7O4X2WUYB2J5WRHT7ZVO4RPNQ' -d 'domain=tworiver.ru&login=".$email_user_name."&password=".$_GET['password']."' 'https://pddimp.yandex.ru/api2/admin/email/add'");
				}
				
				$add_user_id = mysql_insert_id();
				//добавляем пользователю тарифы

				if (isset($_GET['tarif1']) && strlen($_GET['tarif1']) != 0 && $_GET['tarif1'] != 0) {
					$q_add_tarif1 = "INSERT INTO users_tarifs SET user = $add_user_id, tarif = ".$_GET['tarif1'].", start_indications = ". $_GET['start_ind1'];
					mysql_query($q_add_tarif1) or die(mysql_error());
				}
				if (isset($_GET['tarif2']) && strlen($_GET['tarif2']) != 0 && $_GET['tarif2'] != 0) {
					$q_add_tarif2 = "INSERT INTO users_tarifs SET user = $add_user_id, tarif = ".$_GET['tarif2'].", start_indications = ". $_GET['start_ind2'];
					mysql_query($q_add_tarif2) or die(mysql_error());
				}
				if (isset($_GET['tarif3']) && strlen($_GET['tarif3']) != 0 && $_GET['tarif3'] != 0) {
					$q_add_tarif3 = "INSERT INTO users_tarifs SET user = $add_user_id, tarif = ".$_GET['tarif3'].", start_indications = ". $_GET['start_ind3'];
					mysql_query($q_add_tarif3) or die(mysql_error());
				}
				if (isset($_GET['tarif4']) && strlen($_GET['tarif4']) != 0 && $_GET['tarif4'] != 0) {
					$q_add_tarif4 = "INSERT INTO users_tarifs SET user = $add_user_id, tarif = ".$_GET['tarif4'].", start_indications = ". $_GET['start_ind4'];
					mysql_query($q_add_tarif4) or die(mysql_error());
				}

				//Добавляем пользователю договор на энергопотребление
				$q_add_contract = "INSERT INTO users_contracts SET user = $add_user_id, type = 1, num = '$add_contract_num', date_start = '$add_contract_date'";
				mysql_query($q_add_contract) or die(mysql_error());

				//Отправляем пользователю смс с паролем
				//$sms_text = urlencode('Личный кабинет СНТ "Двуречье". снт-двуречье.рф Логин '.$add_email.' Пароль '.$add_password);
				//echo $sms_text;
				//shell_exec("curl http://195.128.126.48/sendsms.php?user=sador1&pwd=d3330&sadr=SNT&dadr=$add_phone&text=$sms_text");

				$error_msg = '<script type="text/javascript">swal("", "Пользователь добавлен ", "success")</script>';
				
				header("Location: admin_users.php");

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
		

		<link rel="stylesheet" href="css/font-awesome.min.css">

		<link rel="stylesheet" href="css/sweetalert.css">

		<script src="js/sweetalert.min.js"></script>
		<link rel="stylesheet" href="css/my.css">
		
		<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@17.10.0/dist/css/suggestions.min.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<!--[if lt IE 10]>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxtransport-xdomainrequest/1.0.1/jquery.xdomainrequest.min.js"></script>
		<![endif]-->
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/suggestions-jquery@17.10.0/dist/js/jquery.suggestions.min.js"></script>
		<script type="text/javascript" src="js/jquery.uitablefilter.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>


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
			th {
				text-align: center;
			}
			td.center {
				text-align: center;
			}
			.acts {
				display: none;
				padding:10px;
				background: #eee;
				border-radius: 5px;
				position: absolute;
				-webkit-box-shadow: 1px 2px 15px 1px rgba(0,0,0,0.35);
				-moz-box-shadow: 1px 2px 15px 1px rgba(0,0,0,0.35);
				box-shadow: 1px 2px 15px 1px rgba(0,0,0,0.35);
			}
			a.close {
				    font-size: 12px;
					color: #000000;
			}
		</style>



	</head>
	<body>
		<?php 		
		if (isset($error_msg)) {
			echo $error_msg; 
		}		
		?>
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
								  <h3>Список пользователей</h3>
									<div class="table-responsive" style="overflow: hidden;">
									<a href="#myModal" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> Добавить пользователя</a>
									<!-- HTML-код модального окна -->
									<div id="myModal" class="modal fade">
									  <div class="modal-dialog">
										<div class="modal-content">
										  <!-- Заголовок модального окна -->
										  <div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">Добавление пользователя</h4>
										  </div>
										  <!-- Основное содержимое модального окна -->
										  <div class="modal-body">
											<form method="GET" role="form" id="AddUser">
												<div class="form-group">
													<label for="InputFIO">ФИО</label>
													<input name="fio" type="text" class="form-control" placeholder="ФИО" id="InputFIO">
													<script type="text/javascript">
														$("#InputFIO").suggestions({
															token: "33e20f2df5ca93e3cd57af7da34cd903e45a5218",
															type: "NAME",
															count: 5,
															/* Вызывается, когда пользователь выбирает одну из подсказок */
															onSelect: function(suggestion) {
																console.log(suggestion);
															}
														});
													</script>
												</div>
												<div class="form-group">
													<label for="InputUcastok">Номер участка</label>
													<input name="uchastok" type="text" class="form-control" id="InputUcastok" placeholder="Номер участка" onchange="generateEmail(this.value)">
												</div>
												<div class="form-group">
													<label for="InputEmail">Email</label>
													<input name="email" type="email" class="form-control" id="InputEmail" placeholder="Email" readonly>
												</div>
												<div class="form-group">
													<label for="create_email">Создать ящик</label>
													<input name="create_email" type="checkbox" class="form-control" id="create_email" checked value="1">
												</div>
												<div class="form-group">
													<label for="InputPhone">Телефон</label>
													<input name="phone" type="text" class="form-control" id="InputPhone" placeholder="Телефон">
												</div>
												<div class="form-group">
													<label for="InputPass">Пароль</label>
													<input name="password" type="password" class="form-control" id="InputPass" placeholder="Пароль">
													<a href="#" class="btn btn-default" onclick="generatePass()"><i class="fa fa-key" aria-hidden="true"> Создать пароль</i></a>
												</div>

												<div class="form-group">
													<label for="InputSchModel">Модель счетчика</label>
													<input name="sch_model" type="text" class="form-control" id="InputSchModel" placeholder="Модель счетчика">
												</div>
												<div class="form-group">
													<label for="InputSchNum">Номер счетчика</label>
													<input name="sch_num" type="text" class="form-control" id="InputSchNum" placeholder="Номер счетчика">
												</div>
												<div class="form-group">
													<label for="InputSchModemNum">Номер модема</label>
													<input name="sch_modem_num" type="text" class="form-control" id="InputSchModemNum" placeholder="Номер модема">
												</div>
												<div class="form-group">
													<label for="InputSchPlumbNum">Номер пломбы</label>
													<input name="sch_pl_num" type="text" class="form-control" id="InputSchPlumbNum" placeholder="Номер пломбы">
												</div>
												<div class="form-group">
													<label for="InputStartBalans">Начальный баланс</label>
													<input name="start_bal" type="text" class="form-control" id="InputStartBalans" value="0">
												</div>
												
												<div class="form-group">
													<label for="InputContractNum">Договор на электропотребление</label>
													<input name="contract_num" type="text" class="form-control" id="InputContractNum" placeholder="Номер договора">

													<input name="contract_date" type="date" class="form-control" id="InputContractNum" placeholder="дата договора">
												</div>
												<div class="form-group">
													<label for="InputСolTarif">Количество тарифов счетчика</label>
													<select class="form-control" name="colTarif" id="InputСolTarif" onChange="selectColTarifs(this.value)">
														<option selected>1</option>
														<option>2</option>
														<option>3</option>
														<option>4</option>
													</select>
												</div>
												<script>
													function selectColTarifs(colTarifs) {
														//alert(colTarifs);
														if (colTarifs == 1) {
															document.getElementById('formTarif2').style.display="none";
															document.getElementById('InputTarif2').disabled="disabled";
															document.getElementById('InputStartIndications2').disabled="disabled";
															document.getElementById('formTarif3').style.display="none";
															document.getElementById('InputTarif3').disabled="disabled";
															document.getElementById('InputStartIndications3').disabled="disabled";
															document.getElementById('formTarif4').style.display="none";
															document.getElementById('InputTarif4').disabled="disabled";
															document.getElementById('InputStartIndications4').disabled="disabled";
														}
														else if (colTarifs == 2) {
															document.getElementById('formTarif2').style.display="block";
															document.getElementById('InputTarif2').disabled="";
															document.getElementById('InputStartIndications2').disabled="";
															document.getElementById('formTarif3').style.display="none";
															document.getElementById('InputTarif3').disabled="disabled";
															document.getElementById('InputStartIndications3').disabled="disabled";
															document.getElementById('formTarif4').style.display="none";
															document.getElementById('InputTarif4').disabled="disabled";
															document.getElementById('InputStartIndications4').disabled="disabled";
														}
														else if (colTarifs == 3) {
															document.getElementById('formTarif2').style.display="block";
															document.getElementById('InputTarif2').disabled="";
															document.getElementById('InputStartIndications2').disabled="";
															document.getElementById('formTarif3').style.display="block";
															document.getElementById('InputTarif3').disabled="";
															document.getElementById('InputStartIndications3').disabled="";
															document.getElementById('formTarif4').style.display="none";
															document.getElementById('InputTarif4').disabled="disabled";
															document.getElementById('InputStartIndications4').disabled="disabled";
														}
														else if (colTarifs == 4) {
															document.getElementById('formTarif2').style.display="block";
															document.getElementById('InputTarif2').disabled="";
															document.getElementById('InputStartIndications2').disabled="";
															document.getElementById('formTarif3').style.display="block";
															document.getElementById('InputTarif3').disabled="";
															document.getElementById('InputStartIndications3').disabled="";
															document.getElementById('formTarif4').style.display="block";
															document.getElementById('InputTarif4').disabled="";
															document.getElementById('InputStartIndications4').disabled="";
														}
													}
												</script>
												<div class="form-group">
													<div class="row">
														<div class="col-sm-5">
															<label for="InputTarif1">Тариф 1</label>
															<select class="form-control" name="tarif1" id="InputTarif1">
																<?php
																foreach ($tarifs_arr as $value) {
																	echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
																}
																?>
															</select>
														</div>
														<div class="col-sm-7">
															<label for="InputStartIndications1">Начальные показания по тарифу 1</label>
															<input name="start_ind1" type="text" class="form-control" id="InputStartIndications1" value="0">
														</div>
													</div>
												</div>
												<div class="form-group" style="display:none;" id="formTarif2">
													<div class="row">
														<div class="col-sm-5">
															<label for="InputTarif2">Тариф 2</label>
															<select class="form-control" name="tarif2" id="InputTarif2">
																<option>Нет</option>
																<?php
																foreach ($tarifs_arr as $value) {
																	echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
																}																
																?>
															</select>
														</div>
														<div class="col-sm-7">
															<label for="InputStartIndications2">Начальные показания по тарифу 2</label>
															<input name="start_ind2" type="text" class="form-control" id="InputStartIndications2" value="0">
														</div>
													</div>
												</div>
												<div class="form-group" style="display:none;" id="formTarif3">
													<div class="row">
														<div class="col-sm-5">
															<label for="InputTarif3">Тариф 3</label>
															<select class="form-control" name="tarif3" id="InputTarif3">
																<option>Нет</option>
																<?php
																foreach ($tarifs_arr as $value) {
																	echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
																}
																?>
															</select>
														</div>
														<div class="col-sm-7">
															<label for="InputStartIndications3">Начальные показания по тарифу 3</label>
															<input name="start_ind3" type="text" class="form-control" id="InputStartIndications3" value="0">
														</div>
													</div>
												</div>
												<div class="form-group" style="display:none;" id="formTarif4">
													<div class="row">
														<div class="col-sm-5">
															<label for="InputTarif4">Тариф 4</label>
															<select class="form-control" name="tarif4" id="InputTarif4">
																<option>Нет</option>
																<?php
																foreach ($tarifs_arr as $value) {
																	echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
																}
																?>
															</select>
														</div>
														<div class="col-sm-7">
															<label for="InputStartIndications4">Начальные показания по тарифу 4</label>
															<input name="start_ind4" type="text" class="form-control" id="InputStartIndications4" value="0">
														</div>
													</div>
												</div>

											</form>
										  </div>
										  <!-- Футер модального окна -->
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
											<button type="button" class="btn btn-primary" onclick="document.getElementById('AddUser').submit(); return false;" >Сохранить</button>
										  </div>
										</div>
									  </div>
									</div>
									<br><br>
									<form id="filter-form" class="form-horizontal" role="form">
										<div class="form-group">
											<label class="col-sm-2 control-label">Поиск по таблице</label>
											<div class="col-sm-10">
												<input name="filter" id="filter" type="text" class="form-control" placeholder="номер участка, ФИО или номер телефона">
											</div>
										</div>
									</form>

								  <?php
									//выбираем всех пользователей
									$result_all_users = mysql_query("SELECT u.id, u.uchastok, u.name, u.phone, u.sch_model, u.sch_num, u.sch_plomb_num, u.balans, uc.num, uc.date_start FROM users u, users_contracts uc WHERE u.is_del = 0 AND u.id = uc.user AND uc.date_end IS NULL ORDER BY CONVERT(u.uchastok,SIGNED)") or die(mysql_error());


									echo '<table class="table table-condensed" style="margin-bottom: 0px;">';
									echo '<tr>';
									echo '<th>Участок</th>';
									echo '<th>ФИО</th>';
									echo '<th>Телефон</th>';
									echo '<th>Номер<br>договора</th>';
									echo '<th>Модель<br>счетчика</th>';
									echo '<th>Номер<br>счетчика</th>';
									echo '<th>Номер<br>пломбы</th>';
									echo '<th>Акты<br>сверок</th>';
									echo '<th>Баланс</th>';
									echo '<th></th>';
									echo '<th></th>';
									echo '<th></th>';
									echo '</tr>';
									echo '</table>';
									echo '<table class="table table-condensed table-users">';
									while ($users = mysql_fetch_assoc($result_all_users)) {
										if ($users['balans'] >= 0) {
											echo '<tr class="table-tr">';
										}
										else {
											echo '<tr class="table-tr danger">';
										}
										
										echo '<td>'. $users['uchastok'].'</td>';
										echo '<td>'. $users['name'].'</td>';
										echo '<td>+'. $users['phone'].'</td>';
										//$date_indications = date( 'd.m.Y',strtotime($users['date_start']));
										echo '<td>'. $users['num'].' от '.date( 'd.m.Y',strtotime($users['date_start'])).'</td>';
										echo '<td>'. $users['sch_model'].'</td>';
										echo '<td>'. $users['sch_num'].'</td>';
										echo '<td>'. $users['sch_plomb_num'].'</td>';
										
										$result_acts = mysql_query("SELECT * FROM acts WHERE user = ".$users['id']) or die(mysql_error());
										$result_acts_type = mysql_query("SELECT * FROM acts_type") or die(mysql_error());
										echo '<td class="center">';
											echo '<a href="#acts-'.$users['id'].'" onhover="" onclick="showActs('.$users['id'].'); return false;">';
												echo '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>';
											echo '</a>';
											echo '<div name="acts-'.$users['id'].'" id="acts-'.$users['id'].'" class="acts">';
												echo '<table class="user-acts">';
													echo '<tr class="user-acts">';
														echo '<td style="font-size: 12px; color: #000000; text-align: left;">Акты</td>';
														echo '<td style="font-size: 12px; color: #000000;"><a class="close" href="#" onclick="closeActs('.$users['id'].')">X</a></td>';
													echo '</tr>';
													echo '<tr class="user-acts">';
														echo '<td style="padding-bottom: 10px;">';
														echo '<div class="btn-group">
																<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
																	Распечатать акт
																	<span class="caret"></span>
																  </button>
																<ul class="dropdown-menu">';
																  while ($acts_type = mysql_fetch_assoc($result_acts_type)) {
																		//echo '<li><a href="'.$acts_type['link'].'?user='.$users['id'].'" target="_blank">'.$acts_type['name'].'</a></li>';
																		echo '<li><a href="#" onClick="printAct(\''.$acts_type['name'].'\',\''.$acts_type['link'].'\', '.$users['id'].', '.$acts_type['id'].')">'.$acts_type['name'].'</a></li>';
																	}
														
														echo	'</ul>
															  </div>';
														echo '</td>';
														echo '<td style="padding-bottom: 10px;">';
															echo '<a href="#addAct'.$users['id'].'" class="btn btn-default" data-toggle="modal">Загрузить акт</a>';
														echo '</td>';
													echo '</tr>';
													while ($acts = mysql_fetch_assoc($result_acts)) {
														echo '<tr class="user-acts">
																<td colspan="2"><a href="'.$acts['path'].'" target="_blank">'.date( 'd.m.Y',strtotime($acts['date'])).' - '.$acts['comment'].'</a></td>
																
															</tr>';
													}
												echo '</table>';
												?>
												
												<!-- HTML-код модального окна -->
												<div id="printAct" class="modal fade">
												  <div class="modal-dialog">
													<div class="modal-content">
													  <!-- Заголовок модального окна -->
													  <div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
														<h4 class="modal-title">Создание акта сверки по </h4>
														<h4 class="modal-title" id="printActHeader"></h4>
													  </div>
													  <!-- Основное содержимое модального окна -->
													  <div class="modal-body">
														<form id="printActForm">
															<div class="form-group">
																<label for="actDateFrom">Дата начала периода</label>
																<input type="date" class="form-control" id="actDateFrom" onChange="checkAct(document.getElementById('actUser').value, this.value, document.getElementById('actTypeId').value)">
															</div>
															<div class="form-group">
																<label for="actDateTo">Дата окончания периода</label>
																<input type="date" class="form-control" id="actDateTo" onChange="checkAct(document.getElementById('actUser').value, this.value, document.getElementById('actTypeId').value)">
															</div>
															<input type="hidden" id="actUser">
															<input type="hidden" id="linkforact">
															<input type="hidden" id="actTypeId">
														</form>
													  </div>
													  <!-- Футер модального окна -->
													  <div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
														<a href="#" class="btn btn-primary" onclick="openAct(); return false;">Печать</a>
													  </div>
													</div>
												  </div>
												</div>
												
												<div id="addAct<?php echo $users['id']; ?>" class="modal fade">
												  <div class="modal-dialog">
													<div class="modal-content">
													  <!-- Заголовок модального окна -->
													  <div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
														<h4 class="modal-title">Загрузка акта сверки</h4>
													  </div>
													  <!-- Основное содержимое модального окна -->
													  <div class="modal-body">
														<form enctype="multipart/form-data" method="POST" id="addActForm<?php echo $users['id']; ?>">
															<div class="form-group">
																<label for="addActUch">Участок</label>
																<input type="hidden" name="addActUch" class="form-control" value="<?php echo $users['id']; ?>">
																<input type="text" class="form-control" value="<?php echo $users['uchastok'] .' - '. $users['name']; ?> " disabled>  
															</div>
															<?php $result_acts_type = mysql_query("SELECT * FROM acts_type") or die(mysql_error()); ?>
															<div class="form-group">
																<label for="addActComment">Тип акта</label>
																<select name="addActType" class="form-control" id="addActType-<?= $users['id']; ?>">
																	<?php 
																	while ($acts_type = mysql_fetch_assoc($result_acts_type)) {
																		echo '<option value="'.$acts_type['id'].'">'.$acts_type['name'].'</option>';
																	}
																	?>
																</select>
															</div>
															<div class="form-group">
																<label for="addActComment">Комментарий</label>
																<input type="text" name="addActComment" class="form-control">
															</div>
															<div class="form-group">
																<label for="addActDateStart">Дата начала акта сверки</label>
																<input type="date" name="addActDateStart" class="form-control" onChange="checkAct(<?= $users['id']; ?>, this.value, document.getElementById('addActType-<?= $users['id']; ?>').value)">
															</div>
															<div class="form-group">
																<label for="addActDateEnd">Дата окончания акта сверки</label>
																<input type="date" name="addActDateEnd" class="form-control" onChange="checkAct(<?= $users['id']; ?>, this.value, document.getElementById('addActType-<?= $users['id']; ?>').value)">
															</div>
															<div class="form-group">
																<label for="addActFile">Файл акта сверки</label>
																<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
																<input type="file" name="addActFile" class="form-control" id="InputFile"/>
															</div>
														</form>
													  </div>
													  <!-- Футер модального окна -->
													  <div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
														<button type="button" class="btn btn-primary" onclick="document.getElementById('addActForm<?php echo $users['id']; ?>').submit(); return false;" >Загрузить</button>
														
													  </div>
													</div>
												  </div>
												</div>
												<script>
												</script>
												<?php
											echo '	</div>';
										echo '</td>';
										
										echo '<td>'. $users['balans'].'</td>';
										echo '<td class="center"><button href="#" class="btn btn-danger btn-xs" onclick="ConfirmCounterReplace('.$users['id'].')" >Замена счетчика</button></td>';
										echo '<td class="center"><a href="admin_user_edit.php?edit_user='.$users['id'].'"><i class="fa fa-pencil" aria-hidden="true" title="Редактировать пользователя"></i></a></td>';
										//echo '<td><a class="del_user" href="admin_users.php?del_user='.$users['id'].'"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
										echo '<td class="center"><a class="del_user" href="#" onclick="ConfirmDelUser('.$users['id'].')"><i class="fa fa-trash" aria-hidden="true" title="Удалить пользователя"></i></a></td>';
										echo '</tr>';
									}
									echo '</table>';

								  ?>

									<script>
										function showActs(userId) {
											showDiv = document.getElementById('acts-'+userId);
											$( ".acts" ).css({"display":"none"});
											$( ".user-acts" ).css({"display":"table-row"}); //user-acts
											showDiv.style.display="block";
											
										}
										function closeActs(userId) {
											showDiv = document.getElementById('acts-'+userId);
											showDiv.style.display="none";
										}
										
										
										function ConfirmDelUser(user_id)
										{
											swal({
												title: 'Удалить пользователя?',
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
												  'Пользователь удален.',
												  'success'
												);
												document.location.href = "admin_users.php?del_user="+user_id;
											})
										}
									</script>

									<script>
										function ConfirmCounterReplace(user_id)
										{
											swal({
												title: 'Внимание!!!',
												text: 'Эта операция необратима!',
												type: 'warning',
												showCancelButton: true,
												confirmButtonColor: '#dd6b55',
												cancelButtonColor: '#999',
												confirmButtonText: 'Продолжить',
												cancelButtonText: 'Отмена',
												closeOnConfirm: false
											}, function() {

												document.location.href = "counter_replace.php?user="+user_id;
											})
										}
									</script>

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
		$(function() {
		
		  var theTable = $('table.table-users')
		
		  $("#filter").keyup(function() {
		
			$.uiTableFilter( theTable, this.value );
		
		  })
		
		 $('#filter-form').submit(function(){
		
			theTable.find("tbody > tr.:visible > td:eq(1)").mousedown();
		
			return false;
		
		  }).focus();
		
		});

		</script>
		
		
		
		<script>
		function makeRand(max){
		        // Generating random number from 0 to max (argument)
		        return Math.floor(Math.random() * max);
		}
		function generatePass(){
		        // password Lenght
		        var length = 8;
		        var result = '';
		        // allowed characters
		        var symbols = new Array(
		                                'q','w','e','r','t','y','u','i','o','p',
		                                'a','s','d','f','g','h','j','k','l',
		                                'z','x','c','v','b','n','m',
		                                'Q','W','E','R','T','Y','U','I','O','P',
		                                'A','S','D','F','G','H','J','K','L',
		                                'Z','X','C','V','B','N','M',
		                                1,2,3,4,5,6,7,8,9,0
		        );
		        for (i = 0; i < length; i++){
		                result += symbols[makeRand(symbols.length)];
		        }
		        // id="pass"
						document.getElementById('InputPass').type = "text";
						document.getElementById('InputPass').value = result;

		        // id="retype"
		        //document.getElementById('retype').value = result;
		}
		</script>

		<script>
		function generateEmail(dogNum){
				var domain = '@tworiver.ru';
				var result = '';
				result = transliterate(dogNum) + domain;
				document.getElementById('InputEmail').value = result;

		}
		</script>

		<script>
		//Если с английского на русский, то передаём вторым параметром true.
		transliterate = (
			function() {
				var
					rus = "щ   ш  ч  ц  ю  я  ё  ж  ъ  ы  э  а б в г д е з и й к л м н о п р с т у ф х ь".split(/ +/g),
					eng = "shh sh ch cz yu ya yo zh `` y' e` a b v g d e z i j k l m n o p r s t u f x `".split(/ +/g)
				;
				return function(text, engToRus) {
					var x;
					for(x = 0; x < rus.length; x++) {
						text = text.split(engToRus ? eng[x] : rus[x]).join(engToRus ? rus[x] : eng[x]);
						text = text.split(engToRus ? eng[x].toUpperCase() : rus[x].toUpperCase()).join(engToRus ? rus[x].toUpperCase() : eng[x].toUpperCase());
					}
					return text;
				}
			}
		)();


		</script>
		<script>
			function printAct(actType,link111,userId,actTypeId){
				//alert(actType+', '+link111+','+userId);
				$("#printAct").modal('show');
				document.getElementById('printActHeader').innerHTML = actType;
				document.getElementById('linkforact').value = link111;
				document.getElementById('actUser').value = userId;
				document.getElementById('actTypeId').value = actTypeId;
				
			}
			function openAct() {
				var dateFrom = document.getElementById('actDateFrom').value;
				var dateTo = document.getElementById('actDateTo').value;
				var link111 = document.getElementById('linkforact').value;
				var user = document.getElementById('actUser').value;
				
				window.open(link111+'?user='+user+'&datefrom='+dateFrom+'&dateto='+dateTo,'_blank');
			}
		</script>
		<script>
			function checkAct(userAct,dateAct,typeAct){
				$.post(
				  "ajax/check_act_date.php",
				  {
					dateAct: dateAct,
					userAct: userAct,
					typeAct: typeAct
				  },
				  onAjaxSuccess
				);
											 
				function onAjaxSuccess(data)
				{
				  if (data == 403) {
					swal(
					  'Ошибка!',
					  'Дата находится в закрытом периоде.',
					  'error'
					);
				  }
				}
			}
												</script>
		

	</body>
</html>
