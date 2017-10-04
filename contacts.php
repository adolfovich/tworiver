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
		</div>
		
		<?php include_once "include/footer.php"; ?>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>

		
	</body>
</html>