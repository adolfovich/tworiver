<?php
	
	include_once "core/db_connect.php";
	include_once "include/auth.php";
	
	$curdate = date("Y-m-d");
	
	$curdate1 = date("d.m.Y");
	
	$quarters = array( 1 => '1 квартал', 2 => '2 квартал', 3 => '3 квартал', 4 => '4 квартал');
	
	if ($is_auth == 1) { 
	
		
		
		$result_user_is_admin = mysql_query("SELECT is_admin FROM users WHERE email = '".$_COOKIE["user"]."'") or die(mysql_error());
		
		while ($user_is_admin = mysql_fetch_assoc($result_user_is_admin)) {
			$is_admin = $user_is_admin['is_admin'];
		}
		
		if ($is_admin == 1) {
			
			//выбираем всех пользователей 
			$result_select_user = mysql_query("SELECT * FROM users WHERE is_del = 0 ORDER BY CONVERT(uchastok,SIGNED)") or die(mysql_error());
			
			if (isset($_GET['select_user']) && strlen($_GET['select_user']) != 0) {
				$selected_user = $_GET['select_user'];
				//Выбираем все платежи пользователя
				$result_user_payments = mysql_query("SELECT * FROM payments WHERE user = ".$_GET['select_user']) or die(mysql_error());
				
				//Выбираем последний акт сверки по членским взносам
				$result_last_act = mysql_query("SELECT date_end FROM acts WHERE type = 2 AND user = $selected_user ORDER BY date_end DESC LIMIT 1") or die(mysql_error());
				$last_act_date = mysql_result($result_last_act, 0);
				//Выбираем последний акт сверки по целевым взносам
				$result_last_act_target = mysql_query("SELECT date_end FROM acts WHERE type = 3 AND user = $selected_user ORDER BY date_end DESC LIMIT 1") or die(mysql_error());
				$last_act_date_target = mysql_result($result_last_act_target, 0);
			}
			
			if (isset($_GET['del_сontrib']) && strlen($_GET['del_сontrib']) != 0 && $_GET['del_сontrib'] != 0) {
				$del_contrib_id = $_GET['del_сontrib'];
				$del_contrib_user = $_GET['select_user'];
				$del_contrib_paid = $_GET['paid'];
				$del_contrib_sum = $_GET['sum'];
								
				if ($_GET['paid'] == 1) {
					$q_del_contrib = "DELETE FROM users_contributions WHERE id = $del_contrib_id";
					
					mysql_query($q_del_contrib) or die(mysql_error());
				}
				else if ($_GET['paid'] == 0) {
					$q_del_contrib = "DELETE FROM users_contributions WHERE id = $del_contrib_id";
					
					
					$q_select_type_contribution = "SELECT * FROM users_contributions WHERE id = $del_contrib_id";
					
					$result_q_select_type_contribution = mysql_query($q_select_type_contribution) or die(mysql_error());
					while ($type_contribution = mysql_fetch_assoc($result_q_select_type_contribution)) {
						$type_contribution1 = $type_contribution['contribution_type'];	
											
					}
					
					//echo '$type_contribution1='.$type_contribution1;
					//echo '<br>';
					
					if ($type_contribution1 == '1') {
						
						
						$q_edit_contribution_balans = "UPDATE users SET membership_balans = (membership_balans + $del_contrib_sum) WHERE id = $del_contrib_user";
					}
					else if ($type_contribution1 == '2') {
												
						$q_edit_contribution_balans = "UPDATE users SET target_balans = (target_balans + $del_contrib_sum) WHERE id = $del_contrib_user";
					}
					//echo 'q = ';
					//echo $q_edit_contribution_balans;
					//echo '<br>';
					mysql_query($q_edit_contribution_balans) or die(mysql_error());
					
					$q_edit_total_balans = "UPDATE users SET total_balance = (total_balance + $del_contrib_sum) WHERE id = $del_contrib_user";
					//echo $q_edit_total_balans;
					//echo '<br>';
					mysql_query($q_edit_total_balans) or die(mysql_error());
					
					mysql_query($q_del_contrib) or die(mysql_error());
				}
			}
			
			//paid_contribution
			if (isset($_GET['paid_contribution']) && strlen($_GET['paid_contribution']) != 0 && $_GET['paid_contribution'] != 0) {
				$paid_contribution = $_GET['paid_contribution'];
				$paid_contribution_date = $_GET['paid_contribution_date'];
				
				//Устанавливаем признак оплаты взносу
				$q_paid_contribution = "UPDATE users_contributions SET paid = 1, paid_date = '$paid_contribution_date' WHERE id = $paid_contribution";
				mysql_query($q_paid_contribution) or die(mysql_error());
				
				//узнаем тип оплаченого взноса и его сумму
				$q_select_typesum_contribution = "SELECT * FROM users_contributions WHERE id = $paid_contribution";
				$result_select_typesum_contribution = mysql_query($q_select_typesum_contribution) or die(mysql_error());
				while ($typesum_contribution = mysql_fetch_assoc($result_select_typesum_contribution)) {
					$type_contribution = $typesum_contribution['contribution_type'];
					$sum_contribution = $typesum_contribution['sum'];
					$user_contribution = $typesum_contribution['user'];
				}
				
				//Редактируем соответствующий баланс пользователя
				if ($type_contribution == 1){
					$q_edit_contribution_balans = "UPDATE users SET membership_balans = (membership_balans + $sum_contribution) WHERE id = $user_contribution";
				}
				else if ($type_contribution == 2){
					$q_edit_contribution_balans = "UPDATE users SET target_balans = (target_balans + $sum_contribution) WHERE id = $user_contribution";
				}
				mysql_query($q_edit_contribution_balans) or die(mysql_error());
				
				//Редактируем общий баланс пользователя
				$q_edit_total_balans = "UPDATE users SET total_balance = (total_balance + $sum_contribution) WHERE id = $user_contribution";
				mysql_query($q_edit_total_balans) or die(mysql_error());
				
			}
			
			
			if (isset($_GET['contributions_add'])) {
				if ((isset($_GET['contributions_sum']) && ($_GET['contributions_sum'] == 0 || strlen($_GET['contributions_sum']) == 0))) {  
					$error_msg = '<script type="text/javascript">swal("Внимание!", "Не введена сумма или она равна нулю", "error")</script>';
				}
				else {
					$contributions_add_type = $_GET['contributions_type'];
					$contributions_add_date = $_GET['contributions_date'];
					$contributions_add_period_year = $_GET['contributions_period_year'];
					$contributions_add_period_quarter = $_GET['contributions_period_quarter'];
					$contributions_add_sum = str_replace(",", ".", $_GET['contributions_sum']);
					$contributions_add_users = $_GET['contributions_users'];
								
					//добавляем пользователям взнос
					foreach ($contributions_add_users as $value) {
						if ($contributions_add_type == 1){
							$q_insert_contributions = "INSERT INTO users_contributions SET user = $value, date = '$contributions_add_date', contribution_type = $contributions_add_type, year = '$contributions_add_period_year', quarter = '$contributions_add_period_quarter', sum = '$contributions_add_sum'";
						}
						else if ($contributions_add_type == 2) {
							$contributions_add_comment = $_GET['contributions_comment'];
							$q_insert_contributions = "INSERT INTO users_contributions SET user = $value, date = '$contributions_add_date', contribution_type = $contributions_add_type, sum = '$contributions_add_sum', comment = '$contributions_add_comment'";
						}
						
						//echo $q_insert_contributions;
						//echo '<br>';
						mysql_query($q_insert_contributions) or die(mysql_error());
						
						//редактируем соответствующий баланс
						
						if ($contributions_add_type == 1) {
							$q_edit_contributions_balans = "UPDATE users SET membership_balans = (membership_balans - $contributions_add_sum) WHERE id = $value";
						}
						else if ($contributions_add_type == 2) {
							$q_edit_contributions_balans = "UPDATE users SET target_balans = (target_balans - $contributions_add_sum) WHERE id = $value";
						}
						
						//echo $q_edit_contributions_balans;
						//echo '<br>';
						mysql_query($q_edit_contributions_balans) or die(mysql_error());
					
						//редактируем общий баланс
						
						$q_edit_total_balans = "UPDATE users SET total_balance = (total_balance - $contributions_add_sum) WHERE id = $value";
						
						//echo $q_edit_total_balans;
						//echo '<br>';
						mysql_query($q_edit_total_balans) or die(mysql_error());
						
					}
					
				}
			}
			
			
			
			//contribution_type=1&contribution_date=2017-09-13&contribution_period_year=2017&contribution_period_quarter=1&contribution_sum=2000&contribution_comment=999&select_user=1
			if (isset($_GET['contribution_type'])) {
				if ($_GET['contribution_sum'] == 0 || strlen($_GET['contribution_sum']) == 0) {
					$error_msg = '<script type="text/javascript">swal("Внимание! 2", "Не введена сумма или она равнв нулю", "error")</script>';
				}
				else {
					$contribution_add_type = $_GET['contribution_type'];
					$contribution_add_date = $_GET['contribution_date'];
					$contribution_add_period_year = $_GET['contribution_period_year'];
					$contribution_add_period_quarter = $_GET['contribution_period_quarter'];
					$contribution_add_sum = str_replace(",", ".", $_GET['contribution_sum']);
					$contribution_add_comment = $_GET['contribution_comment'];
					$contribution_add_user = $_GET['select_user'];
					$contribution_add_paid = $_GET['contribution_paid'];
					$contribution_add_paid_date = $_GET['contribution_paid_date'];
					
					//добавляем пользователю взнос
					
					if ($contribution_add_paid == 'on') { //Если стоит галка Оплачен
					
						if ($contribution_add_type == 1){
							$q_insert_contribution = "INSERT INTO users_contributions SET user = $contribution_add_user, date = '$contribution_add_date', contribution_type = $contribution_add_type, year = '$contribution_add_period_year', quarter = '$contribution_add_period_quarter', sum = '$contribution_add_sum', paid = 1, paid_date = '$contribution_add_paid_date'";
						}
						else if ($contribution_add_type == 2) {
							$contributions_add_comment = $_GET['contributions_comment'];
							$q_insert_contribution = "INSERT INTO users_contributions SET user = $contribution_add_user, date = '$contribution_add_date', contribution_type = $contribution_add_type, sum = '$contribution_add_sum', comment = '$contribution_add_comment', paid = 1, paid_date = '$contribution_add_paid_date'";
						}
							
						//echo $q_insert_contribution;
						//echo '<br>';
						mysql_query($q_insert_contribution) or die(mysql_error());
					}
					else { //Если не стоит галка Оплачен
						if ($contribution_add_type == 1){
							$q_insert_contribution = "INSERT INTO users_contributions SET user = $contribution_add_user, date = '$contribution_add_date', contribution_type = $contribution_add_type, year = '$contribution_add_period_year', quarter = '$contribution_add_period_quarter', sum = '$contribution_add_sum'";
						}
						else if ($contribution_add_type == 2) {
							$contributions_add_comment = $_GET['contributions_comment'];
							$q_insert_contribution = "INSERT INTO users_contributions SET user = $contribution_add_user, date = '$contribution_add_date', contribution_type = $contribution_add_type, sum = '$contribution_add_sum', comment = '$contribution_add_comment'";
						}
							
						//echo $q_insert_contribution;
						//echo '<br>';
						mysql_query($q_insert_contribution) or die(mysql_error());
						
						//редактируем соответствующий баланс
						
						if ($contribution_add_type == 1) {
							$q_edit_contribution_balans = "UPDATE users SET membership_balans = (membership_balans - $contribution_add_sum) WHERE id = $contribution_add_user";
						}
						else if ($contribution_add_type == 2) {
							$q_edit_contribution_balans = "UPDATE users SET target_balans = (target_balans - $contribution_add_sum) WHERE id = $contribution_add_user";
						}
							
						//echo $q_edit_contribution_balans;
						//echo '<br>';
						mysql_query($q_edit_contribution_balans) or die(mysql_error());
						
						//редактируем общий баланс
						
						$q_edit_total_balans = "UPDATE users SET total_balance = (total_balance - $contribution_add_sum) WHERE id = $contribution_add_user";
							
						//echo $q_edit_total_balans;
						//echo '<br>';
						mysql_query($q_edit_total_balans) or die(mysql_error());
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
								  <h3>Взносы</h3>
									
									<a href="#addContributions" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> Добавление нескольким пользователям</a><br><br>
									
									<!-- HTML-код модального окна -->
									<div id="addContributions" class="modal fade">
									  <div class="modal-dialog">
										<div class="modal-content">
										  <!-- Заголовок модального окна -->
										  <div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">Добавление взноса нескольким пользователям</h4>
										  </div>
										  <!-- Основное содержимое модального окна -->
										  <div class="modal-body">
											<form method="GET" role="form" id="AddContributions">
												<?php
												//выбираем типы взносов
												$result_contributions_types = mysql_query("SELECT * FROM contribution_type ") or die(mysql_error());
												?>
												<div class="form-group">
													<label for="contributions_type">Тип взноса</label>
													<select name="contributions_type" class="form-control" id="contributions_type" onchange="changeTypeContributions(this.value)">
													<?php
													while ($contributions_type = mysql_fetch_assoc($result_contributions_types)) {
														echo '<option value="'.$contributions_type['id'].'">'.$contributions_type['name'].'</option>';
													}
													?>
													</select>
													
												</div>
												<div class="form-group">
													<label for="contributions_date">Дата</label>
													<input name="contributions_date" type="date" class="form-control" id="contributions_date" value="<?php echo $curdate; ?>">
												</div>
												
												<div class="form-group">
													<label for="contributions_period">Период</label>
													<select name="contributions_period_year" class="form-control" id="contributions_period_year" style="width: 150px; display: inline;">
													<?php
													$curent_year = date("Y");
													for ($i = $curent_year; $i >= ($curent_year - 10); $i--) {
														echo '<option value="'.$i.'">'.$i.'</option>';
													}
													?>
													</select>
													
													<select name="contributions_period_quarter" class="form-control" id="contributions_period_quarter" style="width: 150px; display: inline;">
													<?php
													foreach ($quarters as $key => $value) {
														echo '<option value="'.$key.'">'.$value.'</option>';
													}
													?>
													</select>
												</div>
												<div class="form-group">
													<label for="contributions_sum">Сумма</label>
													<input name="contributions_sum" type="text" class="form-control" id="contributions_sum" placeholder="0,00">
												</div>
												<div class="form-group">
													<label for="contributions_comment">Комментарий</label>
													<input name="contributions_comment" type="text" class="form-control" id="contributions_comment" placeholder="" disabled>
												</div>
												
												<div class="form-group">
													<label for="contributions_users">Пользователи</label>
													<p class="help-block">Выбрать несколько пользователей можно зажав клавишу Ctrl</p>
													<select name="contributions_users[]" class="form-control" id="contributions_users" multiple>
													<?php
													$result_users = mysql_query("SELECT * FROM users WHERE is_del = 0 ORDER BY uchastok") or die(mysql_error());
													while ($users = mysql_fetch_assoc($result_users)) {
														echo '<option value="'.$users['id'].'" selected>'.$users['uchastok'].' '. $users['name'].'</option>';
													}
													?>
													</select>
												</div>
												<input name="contributions_add" type="hidden" value="1">
												
												
											</form>
										  </div>
										  <!-- Футер модального окна -->
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
											<button type="button" class="btn btn-primary" onclick="document.getElementById('AddContributions').submit(); return false;" >Сохранить</button>
										  </div>
										</div>
									  </div>
									</div>
									
									<script>
										function changeTypeContributions(contributionsType) {
											if (contributionsType == 1) {
												document.getElementById("contributions_comment").setAttribute('disabled', 'disabled');
												document.getElementById("contributions_period_year").removeAttribute('disabled');
												document.getElementById("contributions_period_quarter").removeAttribute('disabled');
											}
											if (contributionsType == 2) {
												document.getElementById("contributions_period_year").setAttribute('disabled', 'disabled');
												document.getElementById("contributions_period_quarter").setAttribute('disabled', 'disabled');
												document.getElementById("contributions_comment").removeAttribute('disabled');
											}
										}
									</script>
									
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
													}//uchastok
													
													
												}
												?>
											</select>
										</div>
										
									</form>
									
									
									<?php
									if (isset($_GET['select_user']) && strlen($_GET['select_user']) != 0) {
									?>
									<a href="#addContribution" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> Добавить взнос</a><br><br>
									
									<!-- HTML-код модального окна -->
									<div id="addContribution" class="modal fade">
									  <div class="modal-dialog">
										<div class="modal-content">
										  <!-- Заголовок модального окна -->
										  <div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">Добавление взноса</h4>
										  </div>
										  <!-- Основное содержимое модального окна -->
										  <div class="modal-body">
											<form method="GET" role="form" id="AddContribution">
												<?php
												//выбираем типы взносов
												$result_contribution_types = mysql_query("SELECT * FROM contribution_type ") or die(mysql_error());
												?>
												<div class="form-group">
													<label for="contribution_type">Тип взноса</label>
													<select name="contribution_type" class="form-control" id="contribution_type" onchange="changeTypeContribution(this.value)">
													<?php
													while ($contribution_type = mysql_fetch_assoc($result_contribution_types)) {
														echo '<option value="'.$contribution_type['id'].'">'.$contribution_type['name'].'</option>';
													}
													?>
													</select>
													
												</div>
												<div class="form-group">
													<label for="contribution_date">Дата</label>
													<input name="contribution_date" type="date" class="form-control" id="contribution_date" value="<?php echo $curdate; ?>">
												</div>
												
												<div class="form-group">
													<label for="contribution_period">Период</label>
													<select name="contribution_period_year" class="form-control" id="contribution_period_year" style="width: 150px; display: inline;">
													<?php
													$curent_year = date("Y");
													for ($i = $curent_year; $i >= ($curent_year - 10); $i--) {
														echo '<option value="'.$i.'">'.$i.'</option>';
													}
													?>
													</select>
													
													<select name="contribution_period_quarter" class="form-control" id="contribution_period_quarter" style="width: 150px; display: inline;">
													<?php
													foreach ($quarters as $key => $value) {
														echo '<option value="'.$key.'">'.$value.'</option>';
													}
													?>
													</select>
												</div>
												<div class="form-group">
													<label for="contribution_sum">Сумма</label>
													<input name="contribution_sum" type="text" class="form-control" id="contribution_sum" placeholder="0,00">
												</div>
												<div class="form-group">
													<label for="contribution_comment">Комментарий</label>
													<input name="contribution_comment" type="text" class="form-control" id="contribution_comment" placeholder="" disabled>
												</div>
												<div class="form-group">
													<label for="contribution_paid">Оплачен</label>
													<input name="contribution_paid" type="checkbox" id="contribution_paid" onclick="dateChangeStatus()">
													<input name="contribution_paid_date" type="date" id="contribution_paid_date" class="form-control" style="display: inline; width: 200px; margin-left: 20px;" disabled>
												</div>
												
												<input name="select_user" type="hidden" value="<?php echo $selected_user; ?>">
												<input name="contribution_add" type="hidden" value="1">
											</form>
										  </div>
										  <!-- Футер модального окна -->
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
											<button type="button" class="btn btn-primary" onclick="checkContributionDate(); return false;" >Сохранить</button>
										  </div>
										</div>
									  </div>
									</div>
									
									<script>
										function checkContributionDate() {
											var ContributionType = document.getElementById('contribution_type').value;
											var ContributionDate = document.getElementById('contribution_date').value;
											if (ContributionType == 1) {
												var actType = 2;
											}
											else if (ContributionType == 2) {
												var actType = 3;
											}
											$.post(
											  "ajax/check_contribution_date.php",
											  {
												type: actType,
												date: ContributionDate,
												user: <?= $selected_user; ?>
											  },
											  onAjaxSuccess
											);
											function onAjaxSuccess(data)
											{					  
											  //alert(data);
											  if (data == 'check OK') {
												 document.getElementById('AddContribution').submit(); 
											  }
											  else {
												  swal("Внимание!", "Нельзя добавлять взнос в закрытый период", "error")
											  }
											  
											  //document.getElementById('AddContribution').submit()
											  
											}
										}
										
										function changeTypeContribution(contributionType) {
											if (contributionType == 1) {
												document.getElementById("contribution_comment").setAttribute('disabled', 'disabled');
												document.getElementById("contribution_period_year").removeAttribute('disabled');
												document.getElementById("contribution_period_quarter").removeAttribute('disabled');
											}
											if (contributionType == 2) {
												document.getElementById("contribution_period_year").setAttribute('disabled', 'disabled');
												document.getElementById("contribution_period_quarter").setAttribute('disabled', 'disabled');
												document.getElementById("contribution_comment").removeAttribute('disabled');
											}
										}
										function dateChangeStatus() {
											var statusPaid = document.getElementById("contribution_paid").checked;
											//alert(dis);
											if (statusPaid) {
												document.getElementById("contribution_paid_date").removeAttribute('disabled');
											}
											else if (!statusPaid) {
												document.getElementById("contribution_paid_date").setAttribute('disabled', 'disabled');
											}
										}
									</script>
									
									<!-- Nav tabs -->
									<ul class="nav nav-tabs">
									  <li class="active"><a href="#membership" data-toggle="tab">Членские взносы</a></li>
									  <li><a href="#target" data-toggle="tab">Целевые взносы</a></li>
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
													<th style="width: 30px;"></th>
												</tr>
												<?php
												//Выбираем все членские взносы
												$result_user_membership = mysql_query("SELECT * FROM users_contributions WHERE user = ".$_GET['select_user']." AND contribution_type = 1") or die(mysql_error());
												while ($user_membership = mysql_fetch_assoc($result_user_membership)) {
													$date_membership = date( 'd.m.Y',strtotime($user_membership['date']));
													$paid_date = date( 'd.m.Y',strtotime($user_membership['paid_date']));
																					
													echo '<tr>';
													echo '<td>'.$date_membership.'</td>';
													echo '<td>'.$user_membership['quarter'].' квартал '.$user_membership['year'].'</td>';
													echo '<td>'.$user_membership['sum'].'</td>';
													if ($user_membership['paid'] == 1) {
														echo '<td>'.$paid_date.'</td>';
													}
													else {
														echo '<td><a href="#addPaid'.$user_membership['id'].'" class="btn btn-success btn-xs" data-toggle="modal">Установить оплату</a>';
														?>
														<!-- HTML-код модального окна -->
														<div id="addPaid<?php echo $user_membership['id']; ?>" class="modal fade">
														  <div class="modal-dialog">
															<div class="modal-content">
															  <!-- Заголовок модального окна -->
															  <div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
																<h4 class="modal-title">Установка оплаты</h4>
															  </div>
															  <!-- Основное содержимое модального окна -->
															  <div class="modal-body">
																<form method="GET" role="form" id="AddPaid">
																	
																	<div class="form-group">
																		<label for="paid_contribution_date">Дата</label>
																		<input name="select_user" type="hidden" value="<?php echo $_GET['select_user']; ?>" />
																		<input name="paid_contribution" type="hidden" value="<?php echo $user_membership['id']; ?>" />
																		<input name="paid_contribution_date" type="date" class="form-control" id="paid_contribution_date" value="">
																	</div>
																	
																	<input name="select_user" type="hidden" value="<?php echo $selected_user; ?>">
																</form>
															  </div>
															  <!-- Футер модального окна -->
															  <div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
																<button type="button" class="btn btn-primary" onclick="document.getElementById('AddPaid').submit(); return false;" >Установить</button>
															  </div>
															</div>
														  </div>
														</div>
														<?php
														echo '</td>';
													}
													if (strtotime($user_membership['date']) <= strtotime($last_act_date)) {
														echo '<td style="text-align:center"><span class="fa-stack fa-lg"><i class="fa fa-trash fa-stack-1x" aria-hidden="true"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span></td>';
													}
													else {														
														echo '<td style="text-align:center"><a class="del_user" href="#" onclick="ConfirmDelContrib('.$user_membership['id'].','.$_GET['select_user'].','.$user_membership['paid'].','.$user_membership['sum'].')"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></a></td>';
														
													}
													echo '</tr>';
												}
												
												
												?>
											</table>
											
										</div>
									  <div class="tab-pane fade" id="target">
											
											<table class="table table-striped">
												<tr>
													<th>Дата</th>
													<th>Сумма</th>
													<th>Коментарий</th>
													<th>Оплачен</th>
													<th style="width: 30px;"></th>
												</tr>
												<?php
												//Выбираем все целевые взносы
												$result_user_target = mysql_query("SELECT * FROM users_contributions WHERE user = ".$_GET['select_user']." AND contribution_type = 2") or die(mysql_error());
												while ($user_target = mysql_fetch_assoc($result_user_target)) {
													$date_target = date( 'd.m.Y',strtotime($user_target['date']));
													$paid_target_date = date( 'd.m.Y',strtotime($user_target['paid_date']));
																			
													echo '<tr>';
													echo '<td>'.$date_target.'</td>';
													echo '<td>'.$user_target['sum'].'</td>';
													echo '<td>'.$user_target['comment'].'</td>';
													
													if ($user_target['paid'] == 1) {
														echo '<td>'.$paid_target_date.'</td>';
													}
													else {
														echo '<td><a href="#addPaid'.$user_target['id'].'" class="btn btn-success btn-xs" data-toggle="modal">Установить оплату</a>';
														?>
														<!-- HTML-код модального окна -->
														<div id="addPaid<?php echo $user_target['id']; ?>" class="modal fade">
														  <div class="modal-dialog">
															<div class="modal-content">
															  <!-- Заголовок модального окна -->
															  <div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
																<h4 class="modal-title">Установка оплаты</h4>
															  </div>
															  <!-- Основное содержимое модального окна -->
															  <div class="modal-body">
																<form method="GET" role="form" id="AddPaid">
																	
																	<div class="form-group">
																		<label for="paid_contribution_date">Дата</label>
																		<input name="select_user" type="hidden" value="<?php echo $_GET['select_user']; ?>" />
																		<input name="paid_contribution" type="hidden" value="<?php echo $user_target['id']; ?>" />
																		<input name="paid_contribution_date" type="date" class="form-control" id="paid_contribution_date" value="">
																	</div>
																	
																	<input name="select_user" type="hidden" value="<?php echo $selected_user; ?>">
																</form>
															  </div>
															  <!-- Футер модального окна -->
															  <div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
																<button type="button" class="btn btn-primary" onclick="document.getElementById('AddPaid').submit(); return false;" >Установить</button>
															  </div>
															</div>
														  </div>
														</div>
														<?php
														echo '</td>';
													}
													if (strtotime($user_target['date']) <= strtotime($last_act_date_target)) {
														echo '<td style="text-align:center"><span class="fa-stack fa-lg"><i class="fa fa-trash fa-stack-1x" aria-hidden="true"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span></td>';
													}
													else {														
														echo '<td style="text-align:center"><a class="del_user" href="#" onclick="ConfirmDelContrib('.$user_target['id'].','.$_GET['select_user'].','.$user_target['paid'].','.$user_target['sum'].')"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></a></td>';
														
													}
													
													echo '</tr>';
												}
												?>
											</table>
										</div>
									</div>
									
									<script>
										function ConfirmDelContrib(contrib_id, user_id, contrib_paid, contrib_sum) 
										{
											swal({
												title: 'Удалить взнос?',
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
												  'Взнос удален.',
												  'success'
												);
												document.location.href = "admin_contribution.php?del_сontrib="+contrib_id+"&select_user="+user_id+"&paid="+contrib_paid+"&sum="+contrib_sum;
											})
										}
									</script>
									
									<?php } ?>
									
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
		<div>
		
		<?php include_once "include/footer.php"; ?>
		</div>
		
		<script>
		$('#membership a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		})
		$('#target a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		})
		</script>
				
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>

		
	</body>
</html>
