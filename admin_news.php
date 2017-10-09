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
										<?php include_once "include/admin_menu.php"; ?>
									</nav>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
								  <h3>Управление новостями</h3>
									<hr>
								</div>
								
							</div>
							<div class="row">
								<a href="#addNews" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> Добавить Новость</a>
								<!-- HTML-код модального окна -->
									<div id="addNews" class="modal fade">
									  <div class="modal-dialog">
										<div class="modal-content">
										  <!-- Заголовок модального окна -->
										  <div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">Добавление новости</h4>
										  </div>
										  <!-- Основное содержимое модального окна -->
										  <div class="modal-body">
											<form enctype="multipart/form-data" method="POST" id="addContactForm">
												<div class="form-group">
													<label for="theme">Тема</label>
													<input type="text" name="theme" class="form-control">
												</div>
												<div class="form-group">
													<label for="dateEnd">Дата окончания публикации</label>
													<input type="date" name="dateEnd" class="form-control">
												</div>
												<div class="form-group">
													<div class="col-sm-offset-2 col-sm-10">
													  <div class="checkbox">
														<label>
														  <input name="important" type="checkbox"> Важная
														</label>
													  </div>
													</div>
												  </div>
																								
												
												<div class="form-group">
													<label for="text">Текст</label>
													<textarea name="text" class="form-control" rows="3"></textarea>
													
													
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
								<br>
								<?php $result_news = mysql_query("SELECT * FROM news WHERE is_del = 0") or die(mysql_error()); ?>
								<div class="col-md-12" style="margin-top: 20px;">
									<h4>Существующие новости</h3>
									<table class="table table-condensed">
										<tr>
											<th>Дата публикации</th>
											<th>Тема</th>
											<th>Текст</th>
											<th>Дата окончания публикации</th>
											<th>Важная</th>
											<th></th>
											<th></th>
										</tr>
										<?php
										while ($news = mysql_fetch_assoc($result_news)) {
											echo '<tr>';
											echo '<td>'.date( 'd.m.Y',strtotime($news['date_crate'])).'</td>';
											echo '<td>'.$news['header'].'</td>';
											$words = explode(' ',$news['text']);
											if(count($words) > 20 && 20 > 0) {
												$text = implode(' ',array_slice($words, 0, 20)).'...';
											}
											echo '<td>'.$text.'</td>';
											if (is_null($news['date_end'])) {
												echo '<td>Не устоновлена</td>';
												$date_end = '';
												
											}
											else {
												echo '<td>'.date( 'd.m.Y',strtotime($news['date_end'])).'</td>';
												$date_end = date( 'Y-m-d',strtotime($news['date_end']));
											}
											
											if ($news['important'] == 1) {
												echo '<td style="text-align: center;"><input type="checkbox" checked disabled></td>';
											}
											else {
												echo '<td style="text-align: center;"><input type="checkbox" disabled></td>';
											}
																						
											echo '<td><a href="#editNews'.$news['id'].'" data-toggle="modal"><i class="fa fa-pencil" aria-hidden="true" title="Редактировать новость"></i></a>
											<!-- HTML-код модального окна -->
												<div id="editNews'.$news['id'].'" class="modal fade">
												  <div class="modal-dialog">
													<div class="modal-content">
													  <!-- Заголовок модального окна -->
													  <div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
														<h4 class="modal-title">Редактирование новости</h4>
													  </div>
													  <!-- Основное содержимое модального окна -->
													  <div class="modal-body">
														<form method="POST" role="form" id="formEditNews'.$news['id'].'">
															<input name="editedNews" type="hidden" value="'.$news['id'].'">
															<div class="form-group">
																<label for="theme">Тема</label>
																<input type="text" name="theme" class="form-control" value="'.$news['header'].'">
															</div>
															<div class="form-group">
																<label for="dateEnd">Дата окончания публикации</label>
																<input type="date" name="dateEnd" class="form-control" value="'.$date_end.'">
															</div>
															<div class="form-group">
																<div class="col-sm-offset-2 col-sm-10">
																  <div class="checkbox">
																	<label>';
																	if ($news['important'] == 1) {
																		echo '<input name="important" type="checkbox" checked> Важная';
																	}
																	else {
																		echo '<input name="important" type="checkbox"> Важная';
																	}
																	  
											echo '					</label>
															  </div>
																</div>
															  </div>
															<div class="form-group">
																<label for="text">Текст</label>
																<textarea name="text" class="form-control" rows="3">'.$news['text'].'</textarea>
															</div>	
														</form>
													  </div>
													  <!-- Футер модального окна -->
													  <div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
														<button type="button" class="btn btn-primary" onclick="document.getElementById(\'formEditNews'.$news['id'].'\').submit(); return false;" >Сохранить</button>
													  </div>
													</div>
												  </div>
												</div>
											</td>';
											echo '<td><a class="del_user" href="#" onclick="ConfirmDelNews('.$news['id'].')"><i class="fa fa-trash" aria-hidden="true" title="Удалить новость"></i></a></td>';
											echo '</tr>';
										}
										?>
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
		<script src="js/bootstrap.min.js"></script>

		
	</body>
</html>