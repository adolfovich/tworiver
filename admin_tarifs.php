<?php
	
	include_once "core/db_connect.php";
	include_once "include/auth.php";
	
	
	
	$curdate = date("Y-m-d");
	
	if ($is_auth == 1) { 
	
		
		
		$result_user_is_admin = mysql_query("SELECT is_admin FROM users WHERE email = '".$_COOKIE["user"]."'") or die(mysql_error());
		
		while ($user_is_admin = mysql_fetch_assoc($result_user_is_admin)) {
			$is_admin = $user_is_admin['is_admin'];
		}
		
		if ($is_admin == 1) {
			if (isset($_POST['name']) && strlen($_POST['name']) != 0) {
										
					$q_ins_tarifs = "INSERT INTO tarifs SET name = '".$_POST['name']."', price = '".$_POST['price']."', id_waviot = '".$_POST['id_waviot']."'";
					mysql_query($q_ins_tarifs) or die(mysql_error());
					$error_msg = '<script type="text/javascript">swal("", "Тариф добавлен ", "success")</script>';
					header("Location: admin_tarifs.php");
			}
//print_r($_POST);
			if (isset($_POST['editedTarifs'])) {
				
				$q_upd_tarifs = "UPDATE tarifs SET name = '".$_POST['editedName']."', price = '".$_POST['editedPrice']."', id_waviot = '".$_POST['editedId_waviot']."'  WHERE id = " . $_POST['editedTarifs'];
				//echo $q_upd_tarifs;
				mysql_query($q_upd_tarifs) or die(mysql_error());
				header("Location: admin_tarifs.php");
			}
			if (isset($_GET['del_tarifs']) && strlen($_GET['del_tarifs']) != 0 && $_GET['del_tarifs'] != 0){
				mysql_query("DELETE FROM tarifs WHERE id = " . $_GET['del_tarifs']) or die(mysql_error());
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
		
		<script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
 
		<script type="text/javascript">
			tinymce.init({
				selector: 'textarea',
				language: 'ru',
				height: 300,
				theme: 'modern',
				plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
				toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
				image_advtab: true,
				templates: [
					{ title: 'Test template 1', content: 'Test 1' },
					{ title: 'Test template 2', content: 'Test 2' }
				],
				content_css: [
					'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
					'//www.tinymce.com/css/codepen.min.css'
				]
			});
		</script>
		

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
									<nav>
										<?php include_once "include/admin_menu.php"; ?>
									</nav>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
								  <h3>Управление тарифами</h3>
									<hr>
								</div>
								
							</div>
							<div class="row">
								<a href="#addTarifs" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> Добавить тариф</a>
								<!-- HTML-код модального окна -->
									<div id="addTarifs" class="modal fade">
									  <div class="modal-dialog">
										<div class="modal-content">
										  <!-- Заголовок модального окна -->
										  <div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">Добавление тарифа</h4>
										  </div>
										  <!-- Основное содержимое модального окна -->
										  <div class="modal-body">
											<form enctype="multipart/form-data" method="POST" id="addTarifsForm">
												<div class="form-group">
													<label for="name">Название</label>
													<input type="text" name="name" class="form-control" value="<?php echo $_POST['name']; ?>" id="addName">
												</div>
												<div class="form-group">
													<label for="price">Цена</label>
													<input type="text" name="price" class="form-control" value="<?php echo $_POST['price']; ?>" id="addPrice">
												</div>
												<div class="form-group">
													<label for="id_waviot">№ тарифа в ВАВИОТ</label>
													<input type="text" name="id_waviot" class="form-control" value="<?php echo $_POST['id_waviot']; ?>" id="id_waviot">
												</div>
											</form>
										  </div>
										  <!-- Футер модального окна -->
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
											<button type="button" class="btn btn-primary" onclick="checkAddForm()">Сохранить</button>
											<script>
												function checkAddForm() {
													var name = document.getElementById('addName').value.length;
													
													if (name == 0) {
														swal("Внимание!", "Название или цена не может быть пустым", "error");
													}
													
													else {
														swal("", "Тариф добавлен ", "success");
														setTimeout(function() { document.getElementById('addTarifsForm').submit(); return false }, 2000);
													}													
												}
											</script>
										  </div>
										</div>
									  </div>
									</div>
								<br>
								<?php $result_tarifs = mysql_query("SELECT * FROM tarifs") or die(mysql_error()); ?>
								<div class="col-md-12" style="margin-top: 20px;">
									<h4>Существующие тарифы</h3>
									<table class="table table-condensed">
										<tr>
											<th style="text-align: center;">Имя</th>
											<th style="text-align: center;">Стоимость</th>
											<th style="text-align: center;">Редактировать</th>
											<th style="text-align: center;">Удалить</th>
											
										</tr>
										<!-- Вывод всех тарифов из базы -->
										<?php
										while ($tarifs = mysql_fetch_assoc($result_tarifs)) {
											echo '<tr>';
											echo '<td style="text-align: center;">'.$tarifs['name'].'</td>';
											echo '<td style="text-align: center;">'.$tarifs['price'].'</td>';

											echo '<td style="text-align: center;"><a href="#editTarifs'.$tarifs['id'].'" data-toggle="modal"><i class="fa fa-pencil" aria-hidden="true" title="Редактировать тариф"></i></a>
											<!-- HTML-код модального окна -->
												<div id="editTarifs'.$tarifs['id'].'" class="modal fade">
												  <div class="modal-dialog">
													<div class="modal-content">
													  <!-- Заголовок модального окна -->
													  <div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
														<h4 class="modal-title">Редактирование тарифа</h4>
													  </div>
													  <!-- Основное содержимое модального окна -->
													  <div class="modal-body">
														<form method="POST" role="form" id="formEditTarifs'.$tarifs['id'].'">
															<input name="editedTarifs" type="hidden" value="'.$tarifs['id'].'">
															<div class="form-group">
																<label for="name">Название</label>
																<input type="text" name="editedName" class="form-control" value="'.$tarifs['name'].'">
															</div>
															<div class="form-group">
																<label for="price">Цена</label>
																<input type="text" name="editedPrice" class="form-control changePrice" value="'.$tarifs['price'].'">
															</div>
															<div class="form-group">
																<label for="editedId_waviot">№ тарифа в ВАВИОТ</label>
																<input type="text" name="editedId_waviot" class="form-control" value="'.$tarifs['id_waviot'].'" id="editedId_waviot">
															</div>
														</form>
													  </div>
													  <!-- Футер модального окна -->
													  <div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
														<button type="button" class="btn btn-primary" onclick="checkUpdForm('.$tarifs['id'].')">Сохранить</button>
													  </div>
													  
													</div>
												  </div>
												</div>
											</td>';
											echo '<td style="text-align: center;"><a class="del_user" href="#" onclick="ConfirmDelTarifs('.$tarifs['id'].')"><i class="fa fa-trash" aria-hidden="true" title="Удалить тариф"></i></a></td>';
											echo '</tr>';
										}
										?>
										<script>
										function ConfirmDelTarifs(tarifs_id)
										{
											swal({
												title: 'Удалить тариф?',
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
												  'Тариф удалён.',
												  'success'
												);
												document.location.href = "admin_tarifs.php?del_tarifs="+tarifs_id;
											})
										}
										</script>
									</table>
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

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/jquery.maskedinput.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
			
			<script type="text/javascript">
  					jQuery(function($){
     						$("#addPrice").mask("9.99");
  					});
			</script>
			<script type="text/javascript">
  					jQuery(function($){
     						$(".changePrice").mask("9.99");
  					});
			</script>
			<script>
				function checkUpdForm(tarifId) {
					swal("", "Тариф отредактирован", "success");
					setTimeout(function() { document.getElementById('formEditTarifs'+tarifId).submit(); return false }, 2000);
				}
			</script>
	</body>
</html>