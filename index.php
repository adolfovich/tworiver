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
<<<<<<< HEAD
			
=======
>>>>>>> 3ba20411f30ec0122e316e8ce77b2f72ddc8b0c4
			img {
				width: 100%;
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
				
				<div class="col-md-8">
					<h2>Историческая справка</h2>
					<hr>
					<p><img style="width: 450px;" src="img/history.jpg" align="left" vspace="5" hspace="5" />Урочище Широкая Балка находится в исторически и географически значимом месте.<br>
					На территории бывшего античного царства Боспор, существовавшего более 2000 лет назад.<br>
					СНТ «Двуречье» расположенное почти в самом начале Широкой Балки знаменательно историческими памятниками.<br>
					На восточной стороне СНТ расположены развалины некогда богатой усадьбы 19 века на ее территории так же находится античный колодец, который использовался на протяжении 2000 лет и в настоящее время находится в рабочем состоянии. Другой подобный колодец, но гораздо большего диаметра, находится на северо-востоке, ближе к новому кладбищу.<br>
					К западу, также интересное место. По крутой старой дороге можно подняться к карьеру, где добывают строительный камень, который отправляли в Новороссийск, Анапу и Кабардинку. Карьер был открыт в правлении Николая II.<br>
					Если знать дорогу, от карьера можно дойти до родника, где так же была усадьба вдовы офицера Кавказских войн.	<br>
					И еще много всего происходило на этой благодатной земле в средние века.</p>
				</div>
				
				<div class="col-md-4">
				  <h2>Новости</h2>
				  <hr>
				  <?php
					while ($news = mysql_fetch_assoc($result_news)) {
						$time = strtotime($news['date_crate']);
						$month = $month_name[ date( 'n',$time ) ]; 
						$day   = date( 'j',$time ); 
						$year  = date( 'Y',$time ); 
						$news_date = "[$day $month $year]";
						$words = explode(' ',$news['text']);

						if(count($words) > 20 && 20 > 0) {
							$text = implode(' ',array_slice($words, 0, 20)).'...';
						}
											
						echo '<h3>'.$news['header'].'</h3>';
						echo '<span class="news_date">'.$news_date.'</span>';
						echo '<p>'. $text .'</p>';
						echo '<a class="btn btn-default navbar-btn" href="news.php?news='.$news['id'].'"> Подробнее </a>';
					}
				  ?>
				  			  
			   	</div>
			</div>
			<hr>
		</div>
		
		<?php include_once "include/footer.php"; ?>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>

		
	</body>
</html>