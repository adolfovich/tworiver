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
	
	/*if ($is_auth == 1) {
		echo 'вы авторизованы под именем'.$_COOKIE["user_name"];
	}*/
	
	$curdate = date("Y-m-d");
	$result_news = mysql_query("SELECT * FROM news WHERE date_end IS NULL OR date_end < $curdate") or die(mysql_error());
	
	$q_user_detail = "SELECT * FROM users WHERE email = '".$_COOKIE["user"]."'";
		
		$result_user_detail = mysql_query($q_user_detail) or die(mysql_error());

		while ($user_detail = mysql_fetch_assoc($result_user_detail)) {
			$user_id = $user_detail['id'];
			$user_name = $user_detail['name'];
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
		
	if (isset($_POST['send_email']) && $_POST['send_email'] == 1) {
		if (isset($_POST['input_name']) && strlen($_POST['input_name']) > 0) {
			if (isset($_POST['input_subject']) && strlen($_POST['input_subject']) > 0) {
				if (isset($_POST['input_text']) && strlen($_POST['input_text']) > 0) {
					if (isset($_POST['input_email']) && strlen($_POST['input_text']) > 0) {
										
						$name = trim($_POST['input_name']);
						$sub = trim($_POST['input_subject']);
						$text = trim($_POST['input_text']);
						$email = trim($_POST['input_email']);


						date_default_timezone_set('Etc/UTC');
						require 'core/PHPM/PHPMailerAutoload.php';
						$mail = new PHPMailer;
						$mail->isSMTP();
						$mail->CharSet = "utf-8";
						$mail->SMTPDebug = 0;
						$mail->Debugoutput = 'html';
						$mail->Host = "smtp.yandex.ru";
						$mail->Port = 465;
						$mail->SMTPSecure = 'ssl';
						$mail->SMTPAuth = true;
						$mail->Username = "robot@tworiver.ru";
						$mail->Password = "6hg3mVBzBCru";
						//$mail->Password = "6hg3m";
						$mail->setFrom('robot@tworiver.ru', 'Система управления СНТ');
						$mail->addAddress('adolfovich.alexashka@gmail.com');
						$mail->addAddress('hakalo@bk.ru');
						$mail->Subject = $sub;
						$mail->Body    = "<b>Имя:</b> $name<hr><b>Email:</b> $email<hr><b>Сообщение:</b> $text";
						$mail->IsHTML(true); 
						if (!$mail->send()) {
						   $error_msg = '<script type="text/javascript">swal("", "Письмо не отправлено '.$mail->ErrorInfo.'", "error")</script>';
						} else {
							$error_msg = '<script type="text/javascript">swal("", "Письмо отправлено", "success")</script>';
						}
					}
					else {
						$error_msg = '<script type="text/javascript">swal("", "Не заполнено поле Email", "error")</script>';
					}
				}
				else {
					$error_msg = '<script type="text/javascript">swal("", "Не заполнено поле Сообщение", "error")</script>';
				}
			}
			else {
				$error_msg = '<script type="text/javascript">swal("", "Не заполнено поле Тема сообщения", "error")</script>';
			}
		}
		else {
			$error_msg = '<script type="text/javascript">swal("", "Не заполнено поле ФИО", "error")</script>';
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
		
		
		<!-- вызов сообщения sweetalert
		<script type="text/javascript">swal("А вот и сообщение!", "Красивое, правда?", "info")</script>
		-->
		
		<?php echo $error_msg; ?>
		
		<?php include_once "include/head.php"; ?>
		
		<div class="jumbotron" id="header">
			<div class="container" ></div>
		</div>
		
		<div class="container" style="padding-bottom: 50px;">
			<div class="row">
				
				<div class="col-md-12">
				  <h2>Контакты</h2>
				  <hr>
				  <p><strong>Председатель правления:</strong> Хакало Владимир Олегович</p>
				  <p><strong>Тел.:</strong> +7-918-450-38-81</p>
				  <p><strong>Email:</strong> hakalo@bk.ru</p>
				  			  
			   	</div>
				<div class="col-md-12">
				  <h2>Реквизиты</h2>
				  <hr>
				  <p><strong>Садоводческое некоммерческое товарищество «Двуречье»</strong></p>
				  <p><strong>Юридический адрес:</strong> 353910, Краснодарский край,г. Новороссийск, пр-т Ленина д.47а</p>
				  <p><strong>ИНН:</strong> 2315139264</p>
				  <p><strong>КПП:</strong> 231501001</p>
				  <p><strong>р/с:</strong> 40703810800230000041</p>
				  <p><strong>Банк:</strong> КБ «Кубань Кредит» ООО</p>
				  <p><strong>БИК:</strong> 040349722</p>
				  <p><strong>к/с:</strong> 30101810200000000722</p>
				  			  
			   	</div>
			</div>
			<hr>
			<?php
				if (isset($_POST['send_email'])) {
					$sender = $_POST['input_name'];
					$subject = $_POST['input_subject'];
					$text = $_POST['input_text'];
					$email = $_POST['input_email'];
				}
				else {
					$sender = $user_name;
					$subject = '';
					$text = '';
					$email = '';
				}
			?>
			
			<div class="row">				
				<div class="col-md-12">
					<h2>Форма обратной связи</h2>
					<p>Для того что бы отправить сообщение Председателю правления СНТ заполните форму. Все поля обязательны для заполнения</p>
					<form class="form-horizontal" role="form" method="POST">
					  <input type="hidden" name="send_email" value="1">
					  <div class="form-group">
						<label for="input_name" class="col-sm-2 control-label">ФИО</label>
						<div class="col-sm-10">
						  <input name="input_name" type="text" class="form-control" id="input_name" placeholder="ФИО" value="<?php echo $sender; ?>">
						</div>
					  </div>	
					  <div class="form-group">
						<label for="input_email" class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10">
						  <input name="input_email" type="text" class="form-control" id="input_email" placeholder="address@yourmail.ru" value="<?php echo $email; ?>">
						</div>
					  </div>					  
					  <div class="form-group">
						<label for="input_subject" class="col-sm-2 control-label">Тема сообщения</label>
						<div class="col-sm-10">
						  <input name="input_subject" type="text" class="form-control" id="input_subject" placeholder="Тема" value="<?php echo $subject; ?>">
						</div>
					  </div>
					  <div class="form-group">
						<label for="input_text" class="col-sm-2 control-label">Сообщение</label>
						<div class="col-sm-10">
						  <textarea name="input_text" class="form-control" rows="5" id="input_text"><?php echo $text; ?></textarea>
						</div>
					  </div>					  
					  <div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
						  <button type="submit" class="btn btn-default">Отправить</button>
						</div>
					  </div>
					</form>
				</div>
			</div>
			<hr>
		</div>
		
		<?php include_once "include/footer.php"; ?>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>

		
	</body>
</html>