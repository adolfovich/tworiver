<?php

	if (isset($_GET['news'])) {
		include_once "core/db_connect.php";
		
		$month_name = array( 1 => 'января', 2 => 'февраля', 3 => 'марта', 
		4 => 'апреля', 5 => 'мая', 6 => 'июня', 
		7 => 'июля', 8 => 'августа', 9 => 'сентября', 
		10 => 'октября', 11 => 'ноября', 12 => 'декабря' 
				   );
	
		$curdate = date("Y-m-d");
						
		$result_news = mysql_query("SELECT * FROM news WHERE id = ".$_GET['news']) or die(mysql_error());
	

?>


<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Система управления СНТ</title>
		
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
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
			img {
				width: 100%;
			}
		</style>
		
	</head>
	<body>
		
		<?php include_once "include/head.php"; ?>
		
		
		
		<div class="jumbotron" id="header">
		  <div class="container" >
			
			
		  </div>
		</div>
		
		<div class="container" style="padding-bottom: 50px;">
		  <!-- Example row of columns -->
		  <div class="row">
			
			<div class="col-md-12">
			  <h2>Новости</h2>
			  <hr>
			  <?php
				while ($news = mysql_fetch_assoc($result_news)) {
					$time = strtotime($news['date_crate']);
					$month = $month_name[ date( 'n',$time ) ]; 
					$day   = date( 'j',$time ); 
					$year  = date( 'Y',$time ); 
					
					$news_date = "[$day $month $year]";
					
					$text = $news['text'];

					
										
					echo '<h3>'.$news['header'].'</h3>';
					echo '<span class="news_date">'.$news_date.'</span>';
					echo '<p>'. $text .'</p>';
					
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









<?php	
	}
?>
	
	
	
	