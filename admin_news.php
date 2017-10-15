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
			if (isset($_POST['text']) && strlen($_POST['text']) != 0) {
					if ($_POST['dateEnd'] == '') {
						$dateEnd = '';
					}
					else {
						$dateEnd = ", date_end = '".$_POST['dateEnd']."'";
					}
					if (isset($_POST['important']) && $_POST['important'] == 'on') {
						$important = ', important = 1';
					}
					else {
						$important = '';
					}
					if (isset($_POST['discussed']) && $_POST['discussed'] == 'on') {
						$discussed = ', discussed = 1';
					}
					else {
						$discussed = '';
					}
					
					$q_ins_news = "INSERT INTO news SET header = '".$_POST['theme']."', text = '".$_POST['text']."', preview = '".$_POST['preview']."' $dateEnd $important $discussed";
					mysql_query($q_ins_news) or die(mysql_error());
					$error_msg = '<script type="text/javascript">swal("", "Новость добавлена ", "success")</script>';
			}
			if (isset($_POST['editedNews'])) {
				if ($_POST['editedDateEnd'] == '') {
					$dateEnd = '';
				}
				else {
					$dateEnd = ", date_end = '".$_POST['editedDateEnd']."'";
				}
				if (isset($_POST['editedIimportant']) && $_POST['editedIimportant'] == 'on') {
					$important = ', important = 1';
				}
				else {
					$important = ', important = 0';
				}
				if (isset($_POST['editedDiscussed']) && $_POST['editedDiscussed'] == 'on') {
					$discussed = ', discussed = 1';
				}
				else {
					$discussed = ', discussed = 0';
				}
				$q_upd_news = "UPDATE news SET header = '".$_POST['editedTheme']."', text = '".$_POST['editedText']."', preview = '".$_POST['editedPreview']."'  $dateEnd $important $discussed WHERE id = " . $_POST['editedNews'];
				//echo $q_upd_news;
				mysql_query($q_upd_news) or die(mysql_error());
				$error_msg = '<script type="text/javascript">swal("", "Новость отредактирована ", "success")</script>';
			}
			if (isset($_GET['del_news']) && strlen($_GET['del_news']) != 0 && $_GET['del_news'] != 0){
				mysql_query("UPDATE news SET is_del = 1 WHERE id = " . $_GET['del_news']) or die(mysql_error());
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
											<form enctype="multipart/form-data" method="POST" id="addNewsForm">
												<div class="form-group">
													<label for="theme">Тема</label>
													<input type="text" name="theme" class="form-control" value="<?php echo $_POST['theme']; ?>" id="addTheme">
												</div>
												<div class="form-group">
													<label for="dateEnd">Дата окончания публикации</label>
													<input type="date" name="dateEnd" class="form-control" value="<?php echo $_POST['dateEnd']; ?>">
												</div>
												<div class="form-group">
													<div class="col-sm-offset-2 col-sm-10">
													  <div class="checkbox">
														<label>
														  <?php
														  if (isset($_POST['important']) && $_POST['important'] == 'on'){
															echo '<input name="important" type="checkbox" checked> Важная';
														  }
														  else {
															echo '<input name="important" type="checkbox"> Важная';
														  }
														  ?>
														</label>
													  </div>
													</div>
												  </div>
												  <div class="form-group">
													<div class="col-sm-offset-2 col-sm-10">
													  <div class="checkbox">
														<label>
														  <?php
														  if (isset($_POST['discussed']) && $_POST['discussed'] == 'on'){
															echo '<input name="discussed" type="checkbox" checked> Обсуждение';
														  }
														  else {
															echo '<input name="discussed" type="checkbox"> Обсуждение';
														  }
														  ?>
														</label>
													  </div>
													</div>
												  </div>
																								
												<div class="form-group">
													<label for="preview">Текст для предварительного просмотра</label>
													<textarea name="preview" class="form-control" rows="2" id="addPreview"><?php echo $_POST['preview']; ?></textarea>
												</div>
												<div class="form-group">
													<label for="text">Текст</label>
													<textarea name="text" class="form-control" rows="3" id="addText"><?php echo $_POST['text']; ?></textarea>
												</div>																				
												
											</form>
										  </div>
										  <!-- Футер модального окна -->
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
											<button type="button" class="btn btn-primary" onclick="checkAddForm()" >Сохранить</button>
											<script>
												function checkAddForm() {
													var theme = document.getElementById('addTheme').value.length;
													
													if (theme == 0) {
														swal("Внимание!", "Тема не может быть пустой", "error");
													}
													
													else {
														document.getElementById('addNewsForm').submit(); return false;
													}													
												}
											</script>
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
											<th style="text-align: center;">Дата публикации</th>
											<th style="text-align: center;">Тема</th>
											
											<th style="text-align: center;">Дата окончания публикации</th>
											<th style="text-align: center;">Важная</th>
											<th style="text-align: center;">Обсуждение</th>
											<th></th>
											<th></th>
										</tr>
										<?php
										while ($news = mysql_fetch_assoc($result_news)) {
											echo '<tr>';
											echo '<td>'.date( 'd.m.Y',strtotime($news['date_crate'])).'</td>';
											echo '<td>'.$news['header'].'</td>';
											
											if (is_null($news['date_end'])) {
												echo '<td>Не установлена</td>';
												$date_end = '';
											}
											else if (date( 'Y-m-d',strtotime($news['date_end'])) < $curdate) {
												echo '<td class="bg-danger">'.date( 'd.m.Y',strtotime($news['date_end'])).'</td>';
												$date_end = date( 'Y-m-d',strtotime($news['date_end']));
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
											if ($news['discussed'] == 1) {
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
																<input type="text" name="editedTheme" class="form-control" value="'.$news['header'].'">
															</div>
															<div class="form-group">
																<label for="dateEnd">Дата окончания публикации</label>
																<input type="date" name="editedDateEnd" class="form-control" value="'.$date_end.'">
															</div>
															<div class="form-group">
																<div class="col-sm-offset-2 col-sm-10">
																	<div class="checkbox">
																		<label>';
																		if ($news['important'] == 1) {
																			echo '<input name="editedIimportant" type="checkbox" checked> Важная';
																		}
																		else {
																			echo '<input name="editedImportant" type="checkbox"> Важная';
																		}
																	  
											echo '						</label>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<div class="col-sm-offset-2 col-sm-10">
																	<div class="checkbox">
																		<label>';
																		if ($news['discussed'] == 1) {
																			echo '<input name="editedDiscussed" type="checkbox" checked> Обсуждение';
																		}
																		else {
																			echo '<input name="editedDiscussed" type="checkbox"> Обсуждение';
																		}
																	  
											echo '						</label>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<label for="text">Текст</label>
																<textarea name="editedPreview" class="form-control" rows="3">'.$news['preview'].'</textarea>
															</div>	
															<div class="form-group">
																<label for="text">Текст</label>
																<textarea name="editedText" class="form-control" rows="3">'.$news['text'].'</textarea>
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
										<script>
										function ConfirmDelNews(news_id)
										{
											swal({
												title: 'Удалить новость?',
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
												  'Новость удалена.',
												  'success'
												);
												document.location.href = "admin_news.php?del_news="+news_id;
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
		<script src="js/bootstrap.min.js"></script>

		
	</body>
</html>