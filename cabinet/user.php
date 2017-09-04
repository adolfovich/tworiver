<?php
	
	include_once "./core/db_connect.php";
	include_once "./include/auth.php";
	
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
	
	$curdate = date("Y-m-d");
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

		<link rel="stylesheet" href="../css/font-awesome.min.css">

		<link rel="stylesheet" href="../css/sweetalert.css">
		
		<script src="../js/sweetalert.min.js"></script>

		

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
		
		<?php include_once "../include/head.php"; ?>
		
		<div class="jumbotron" id="header">
			<div class="container" ></div>
		</div>
		
		<div class="container" style="padding-bottom: 50px;">
			<div class="row">
				
				<div class="col-md-12">
				  <h2>Личный кабинет</h2>
				  <hr>
				  
				  
				  			  
			   	</div>
			</div>
			<hr>
		</div>
		
		<?php include_once "../include/footer.php"; ?>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>

		
	</body>
</html>