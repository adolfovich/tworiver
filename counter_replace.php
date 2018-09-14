<?php

	include_once "core/db_connect.php";
	include_once "core/func.php";
	include_once "include/auth.php";

	$curdate = date("Y-m-d");
	
	$change_user = $_GET['user'];
	
	
	
	if ($is_auth == 1) {

		if (isset($_POST['sch_model']) && strlen($_POST['sch_model']) != 0) {
			mysql_query("UPDATE users SET sch_model = '".$_POST['sch_model']."', sch_num = '".$_POST['sch_num']."', sch_plomb_num = '".$_POST['sch_plomb_num']."' WHERE id =".$_POST['user']) or die(mysql_error());
			mysql_query("UPDATE users SET sch_step = 0 WHERE id =".$_POST['user']) or die(mysql_error());
			mysql_query("DELETE FROM Indications WHERE user =".$_POST['user']) or die(mysql_error());
			mysql_query("DELETE FROM payments WHERE user =".$_POST['user']) or die(mysql_error());
			header("Location:admin_users.php?change_counter=1&change_user=".$_POST['user']);
		}
		
		if (isset($_FILES['userfile']['tmp_name'])) {
			$uploaddir = 'uploads/';
			$uploadfile = $uploaddir . generatestr(12).'.pdf';
			//var_dump($uploadfile);
			//var_dump($_FILES['userfile']);
			
			if ($_FILES['userfile']['type'] != 'application/pdf') {
				$error_msg = '<script type="text/javascript">swal("Внимание!", "Файл не является PDF документом", "error")</script>';
				
			}
			else if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
				
				//var_dump($uploadfile);
				$q_file_path = "INSERT INTO acts SET user = ".$_POST['user'].", date_end = '".$_POST['date']."', comment = '".$_POST['comment']."', path = '$uploadfile', type = 1";
				//echo $q_file_path;
				mysql_query($q_file_path) or die(mysql_error());
				mysql_query("UPDATE users SET sch_step = 2 WHERE id =".$_POST['user']) or die(mysql_error());
				header("Location:counter_replace.php?user=".$_POST['user']);
			} else {
				//echo "Возможная атака с помощью файловой загрузки!\n";
			}
		}		

		$result_user_is_admin = mysql_query("SELECT is_admin FROM users WHERE email = '".$_COOKIE["user"]."'") or die(mysql_error());

		while ($user_is_admin = mysql_fetch_assoc($result_user_is_admin)) {
			$is_admin = $user_is_admin['is_admin'];
		}

		//выбираем существующие тарифы



		if ($is_admin == 1) {
			//Выгружаем из базы тарифы на электроэнергию
			$result_tarifs = mysql_query("SELECT * FROM tarifs") or die(mysql_error());
			while ($row = mysql_fetch_assoc($result_tarifs)){
				$tarifs_arr[]=$row;
			}
			
			if (isset($_POST['sch_model']) && strlen($_POST['sch_model']) != 0) {
				mysql_query("UPDATE users SET sch_model = '".$_POST['sch_model']."', sch_num = '".$_POST['sch_num']."', sch_plomb_num = '".$_POST['sch_plomb_num']."', modem_num = '".$_POST['modem_num']."' WHERE id =".$_POST['user']) or die(mysql_error());
				mysql_query("UPDATE users SET sch_step = 0 WHERE id =".$_POST['user']) or die(mysql_error());
				mysql_query("DELETE FROM Indications WHERE user =".$_POST['user']) or die(mysql_error());
				mysql_query("DELETE FROM payments WHERE user =".$_POST['user']) or die(mysql_error());
				mysql_query("DELETE FROM users_tarifs WHERE user =".$_POST['user']) or die(mysql_error());
				
				if (isset($_POST['tarif1']) && strlen($_POST['tarif1']) != 0 && $_POST['tarif1'] != 0) {
					$q_add_tarif1 = "INSERT INTO users_tarifs SET user = ".$_POST['user'].", tarif = ".$_POST['tarif1'].", start_indications = ". $_POST['start_ind1'];
					mysql_query($q_add_tarif1) or die(mysql_error());
				}
				if (isset($_POST['tarif2']) && strlen($_POST['tarif2']) != 0 && $_POST['tarif2'] != 0) {
					$q_add_tarif2 = "INSERT INTO users_tarifs SET user = ".$_POST['user'].", tarif = ".$_POST['tarif2'].", start_indications = ". $_POST['start_ind2'];
					mysql_query($q_add_tarif2) or die(mysql_error());
				}
				if (isset($_POST['tarif3']) && strlen($_POST['tarif3']) != 0 && $_POST['tarif3'] != 0) {
					$q_add_tarif3 = "INSERT INTO users_tarifs SET user = ".$_POST['user'].", tarif = ".$_POST['tarif3'].", start_indications = ". $_POST['start_ind3'];
					mysql_query($q_add_tarif3) or die(mysql_error());
				}
				if (isset($_POST['tarif4']) && strlen($_POST['tarif4']) != 0 && $_POST['tarif4'] != 0) {
					$q_add_tarif4 = "INSERT INTO users_tarifs SET user = ".$_POST['user'].", tarif = ".$_POST['tarif4'].", start_indications = ". $_POST['start_ind4'];
					mysql_query($q_add_tarif4) or die(mysql_error());
				}
				
				header("Location:admin_users.php?change_counter=1&change_user=".$_POST['user']);
			}
			
			if (isset($_FILES['userfile']['tmp_name'])) {
				$uploaddir = 'uploads/';
				$uploadfile = $uploaddir . generatestr(12).'.pdf';
				//var_dump($uploadfile);
				//var_dump($_FILES['userfile']);
				
				if ($_FILES['userfile']['type'] != 'application/pdf') {
					$error_msg = '<script type="text/javascript">swal("Внимание!", "Файл не является PDF документом", "error")</script>';
					
				}
				else if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
					
					//var_dump($uploadfile);
					$q_file_path = "INSERT INTO acts SET user = ".$_POST['user'].", date = '".$_POST['date']."', comment = '".$_POST['comment']."', path = '$uploadfile'";
					//echo $q_file_path;
					mysql_query($q_file_path) or die(mysql_error());
					mysql_query("UPDATE users SET sch_step = 2 WHERE id =".$_POST['user']) or die(mysql_error());
					header("Location:counter_replace.php?user=".$_POST['user']);
				} else {
					//echo "Возможная атака с помощью файловой загрузки!\n";
				}
			}		
			
			$result_change_step = mysql_query("SELECT sch_step FROM users WHERE id = $change_user") or die(mysql_error());
			
			while ($change_steps = mysql_fetch_assoc($result_change_step)) {
				$change_step = $change_steps['sch_step'];
			}
			//echo $change_step;
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
					<?php
						}
					}
					?>					
							<div class="row">
								<div class="col-md-12">
									<h3>Замена счетчика</h3>
										
									<?php if ($change_step == 0) { ?>
							
										<h3>Шаг 1</h3>
										<form method="GET" action="forms/act_reconciliation.php" target="_blank">
											<input name="user" type="hidden" value="<?=$change_user?>">
											<div class="form-group">
												<label for="date">Дата начала периода</label>
												<input name="datefrom" type="date" class="form-control" id="date" style="width: 200px;">
											</div>
											<div class="form-group">
												<label for="date">Дата окончания периода</label>
												<input name="dateto" type="date" class="form-control" id="date" style="width: 200px;">
											</div>
											<input class="btn btn-default" type="submit" value="Распечатать акт сверки"/>
											<input class="btn btn-default" type="button" value="Далее" onclick="changeStep(1,<?php echo $change_user; ?>);"/>
										</form>										
								
									<?php } else if ($change_step == 1) { ?>
							
										<h3>Шаг 2</h3>
										<h4>Загрузка подписанного акта сверки</h4>
										<form enctype="multipart/form-data" method="POST">
											<div class="form-group">
												<label for="date">Дата акта сверки</label>
												<input name="date" type="date" class="form-control" id="date" style="width: 200px;">
											</div>
											<div class="form-group">
												<label for="comment">Коментарий</label>
												<input name="comment" type="text" class="form-control" id="comment" style="width: 200px;">
											</div>
											<div class="form-group">
												<label for="InputFile">Файл акта сверки</label>
												<input type="hidden" name="user" value="<?php echo $change_user; ?>">
												<!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
												<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
												<!-- Название элемента input определяет имя в массиве $_FILES -->
												<input name="userfile" type="file" id="InputFile"/>
											</div>
											
											<input class="btn btn-default" type="submit" value="Отправить" />
										</form>
										
										
											
										  
								
									<?php }	else if ($change_step == 2) { ?>
							
										<h3>Шаг 3</h3>
										<h4>Ввести данные нового счетчика</h4>
										<form role="form" method="POST">
											<div class="form-group">
												<label for="sch_model">Модель счетчика</label>
												<input name="sch_model" type="text" class="form-control" id="sch_model" placeholder="Модель счетчика">
											</div>
											<div class="form-group">
												<label for="sch_num">Номер счетчика</label>
												<input name="sch_num" type="text" class="form-control" id="sch_num" placeholder="Номер счетчика">
											</div>
											<div class="form-group">
												<label for="sch_plomb_num">Номер пломбы</label>
												<input name="sch_plomb_num" type="text" class="form-control" id="sch_plomb_num" placeholder="Номер пломбы">
											</div>
											<div class="form-group">
												<label for="modem_num">Номер модема</label>
												<input name="modem_num" type="text" class="form-control" id="modem_num" placeholder="Номер пломбы">
											</div>
											<input type="hidden" name="user" value="<?php echo $change_user; ?>">
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
										  <button type="submit" class="btn btn-default">Отправить</button>
										</form>
								
									<?php }	?>
								</div>
							</div>
		</div>
		
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
		
		function changeStep(step, user) {
			//alert(step+'|'+user);
			var req = getXmlHttp()  
			req.onreadystatechange = function() {  
				if (req.readyState == 4) { 
					if(req.status == 200) { 
						//document.getElementById("price").value = req.responseText;
					}
				}
			}
			req.open('GET', 'ajax/change_step.php?step='+step+'&user='+user, true);  
			req.send(null);  
			window.location="counter_replace.php?user="+user;
		}
		</script>
	</body>
</html>
