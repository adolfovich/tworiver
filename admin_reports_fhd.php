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
			
			if (isset($_GET['del_report']) && strlen($_GET['del_report'] != 0 && $_GET['del_report'] > 0)) {
				mysql_query("DELETE FROM reports_fhd WHERE id = ".$_GET['del_report']) or die(mysql_error());
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
					$q_file_path = "INSERT INTO reports_fhd SET date = '".$_POST['year']."-".$_POST['month']."-01', path = '$uploadfile'";
					//echo $q_file_path;
					mysql_query($q_file_path) or die(mysql_error());
					$error_msg = '<script type="text/javascript">swal("", "Файл загружен ", "success")</script>';
					
				} else {
					//echo "Возможная атака с помощью файловой загрузки!\n";
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
									<h3>Отчеты ФХД</h3>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
										<h4>Загрузка отчета</h4>
										<form enctype="multipart/form-data" method="POST">
											<div class="form-group">
												<label for="month">Месяц/Год</label>
												<select name="month" class="form-control" style="width: 100px; display: inline;">
													<?php 
													for ($i = 1; $i <= 12; $i++){
														if ($i == $curmonth) {
															echo '<option selected>'.$i.'</option>';
														}
														else {
															echo '<option>'.$i.'</option>';
														}
													}
													?>
												</select>
												/
												<select name="year" class="form-control" style="width: 100px; display: inline;">
													<?php 
													for ($i = $curyear - 10; $i <= $curyear; $i++){
														if ($i == $curyear) {
															echo '<option selected>'.$i.'</option>';
														}
														else {
															echo '<option>'.$i.'</option>';
														}
														
														
													}
													?>
												</select>
											</div>
											
											<div class="form-group">
												<label for="InputFile">Файл отчета</label>
												<input type="hidden" name="user" value="<?php echo $change_user; ?>">
												<!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
												<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
												<!-- Название элемента input определяет имя в массиве $_FILES -->
												<input name="userfile" type="file" id="InputFile"/>
											</div>
											
											<input class="btn btn-default" type="submit" value="Отправить" />
										</form>
								</div>
							</div>
							<?php $result_reports = mysql_query("SELECT * FROM reports_fhd ORDER BY date") or die(mysql_error()); ?>
							<hr>
							<div class="row">
								<div class="col-md-12">
									<h4>Загруженные отчеты</h3>
									<table class="table table-condensed">
										<tr>
											<th>Дата</th>
											<th>Файл</th>
											<th></th>
										</tr>
										<?php
										while ($reports = mysql_fetch_assoc($result_reports)) {
											echo '<tr>';
											echo '<td>'.date( 'm.Y',strtotime($reports['date'])).'</td>';
											echo '<td>'.$reports['path'].'</td>';
											echo '<td><a class="del_user" href="#" onclick="ConfirmDelReport('.$reports['id'].')"><i class="fa fa-trash" aria-hidden="true" title="Удалить файл"></i></a></td>';
											echo '</tr>';
										}
										?>
									</table>
								</div>
							</div>
		</div>
	
	<script>
	function ConfirmDelReport(report_id)
										{
											swal({
												title: 'Удалить файл отчета?',
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
												  'Файл удален.',
												  'success'
												);
												document.location.href = "admin_reports_fhd.php?del_report="+report_id;
											})
										}
	</script>
	
	</body>
</html>