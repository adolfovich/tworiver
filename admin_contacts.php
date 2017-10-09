<?php

	include_once "core/db_connect.php";
	include_once "core/func.php";
	include_once "include/auth.php";

	$curdate = date("Y-m-d");
	$curmonth = date("m");
	$curyear = date("Y");
	
	$result_user_is_admin = mysql_query("SELECT is_admin FROM users WHERE email = '".$_COOKIE["user"]."'") or die(mysql_error());
	
	while ($user_is_admin = mysql_fetch_assoc($result_user_is_admin)) {
			$is_admin = $user_is_admin['is_admin'];
		}
	
	if ($is_auth == 1) {
		
		if ($is_admin == 1) {
			
			if (isset($_POST['name'])) {
				mysql_query("INSERT INTO contacts SET name='".$_POST['name']."', post='".$_POST['post']."', phone='".$_POST['phone']."', email='".$_POST['email']."'") or die(mysql_error());
				$error_msg = '<script type="text/javascript">swal("", "Контакт добавлен ", "success")</script>';
			}
			
			if (isset($_GET['del_contact']) && strlen($_GET['del_contact']) && $_GET['del_contact'] != 0) {
				mysql_query("DELETE FROM contacts WHERE id = ".$_GET['del_contact']) or die(mysql_error());
				header("Location: admin_contacts.php");
			}
			
			if (isset($_POST['editedContact']) && $_POST['editedContact'] != 0) {
				mysql_query("UPDATE contacts SET name = '".$_POST['editName']."', post = '".$_POST['editPost']."', phone = '".$_POST['editPhone']."', email = '".$_POST['editEmail']."' WHERE id = " . $_POST['editedContact']) or die(mysql_error()); 
				$error_msg = '<script type="text/javascript">swal("", "Контакт изменен ", "success")</script>';
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
					<?php
						}
					}
					?>					
							<div class="row">
								<div class="col-md-12">
									<h3>Контакты</h3>
								</div>
							</div>
							<div class="row">
								<a href="#addContact" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> Добавить контакт</a>
								<!-- HTML-код модального окна -->
									<div id="addContact" class="modal fade">
									  <div class="modal-dialog">
										<div class="modal-content">
										  <!-- Заголовок модального окна -->
										  <div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">Добавление контакта</h4>
										  </div>
										  <!-- Основное содержимое модального окна -->
										  <div class="modal-body">
											<form enctype="multipart/form-data" method="POST" id="addContactForm">
												<div class="form-group">
													<label for="name">ФИО</label>
													<input type="text" name="name" class="form-control">
												</div>
												<div class="form-group">
													<label for="post">Должность</label>
													<input type="text" name="post" class="form-control">
												</div>
												<div class="form-group">
													<label for="phone">Телефон</label>
													<input type="text" name="phone" class="form-control">
												</div>
												<div class="form-group">
													<label for="email">Email</label>
													<input type="text" name="email" class="form-control">
												</div>																				
												
											</form>
										  </div>
										  <!-- Футер модального окна -->
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
											<button type="button" class="btn btn-primary" onclick="document.getElementById('addContactForm').submit(); return false;" >Сохранить</button>
										  </div>
										</div>
									  </div>
									</div>
									
									
									
									
									
								
							</div>
							<?php $result_contacts = mysql_query("SELECT * FROM contacts") or die(mysql_error()); ?>
							<hr>
							<div class="row">
								<div class="col-md-12">
									<h4>Существующие контакты</h3>
									<table class="table table-condensed">
										<tr>
											<th>ФИО</th>
											<th>Должность</th>
											<th>Телефон</th>
											<th>Email</th>
											<th></th>
											<th></th>
										</tr>
										<?php
										while ($contacts = mysql_fetch_assoc($result_contacts)) {
											echo '<tr>';
											echo '<td>'.$contacts['name'].'</td>';
											echo '<td>'.$contacts['post'].'</td>';
											echo '<td>'.$contacts['phone'].'</td>';
											echo '<td>'.$contacts['email'].'</td>';											
											echo '<td><a href="#editContact'.$contacts['id'].'" data-toggle="modal"><i class="fa fa-pencil" aria-hidden="true" title="Редактировать контакт"></i></a>
											<!-- HTML-код модального окна -->
												<div id="editContact'.$contacts['id'].'" class="modal fade">
												  <div class="modal-dialog">
													<div class="modal-content">
													  <!-- Заголовок модального окна -->
													  <div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
														<h4 class="modal-title">Редактирование контакта</h4>
													  </div>
													  <!-- Основное содержимое модального окна -->
													  <div class="modal-body">
														<form method="POST" role="form" id="formEditContact'.$contacts['id'].'">
															<input name="editedContact" type="hidden" value="'.$contacts['id'].'">
															<div class="form-group">
																<label for="editName">ФИО</label>
																<input name="editName" type="text" class="form-control" id="editName" value="'.$contacts['name'].'">
															</div>
															<div class="form-group">
																<label for="editPost">Должность</label>
																<input name="editPost" type="text" class="form-control" id="editPost" value="'.$contacts['post'].'">
															</div>
															<div class="form-group">
																<label for="editPhone">Телефон</label>
																<input name="editPhone" type="text" class="form-control" id="editPhone" value="'.$contacts['phone'].'">
															</div>
															<div class="form-group">
																<label for="editEmail">Email</label>
																<input name="editEmail" type="text" class="form-control" id="editEmail" value="'.$contacts['email'].'">
															</div>
														</form>
													  </div>
													  <!-- Футер модального окна -->
													  <div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
														<button type="button" class="btn btn-primary" onclick="document.getElementById(\'formEditContact'.$contacts['id'].'\').submit(); return false;" >Сохранить</button>
													  </div>
													</div>
												  </div>
												</div>
											
											</td>';
											echo '<td><a class="del_user" href="#" onclick="ConfirmDelContact('.$contacts['id'].')"><i class="fa fa-trash" aria-hidden="true" title="Удалить контакт"></i></a></td>';
											echo '</tr>';
										}
										?>
									</table>
								</div>
							</div>
		
	<script>
	function ConfirmDelContact(contact_id)
										{
											swal({
												title: 'Удалить контакт?',
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
												  'Контакт удален.',
												  'success'
												);
												document.location.href = "admin_contacts.php?del_contact="+contact_id;
											})
										}
	</script>
		
		</div>	
	</body>
</html>