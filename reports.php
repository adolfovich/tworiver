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

	$curdate = date("Y-m-d");

	if ($is_auth == 1) {}
	
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
		<link rel="stylesheet" href="css/my.css">
		<script src="js/sweetalert.min.js"></script>

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
		
		
		<?php 
		if (isset($error_msg)) {
			echo $error_msg; 
		}
		
		if (isset($error)) {
			echo $error; 
		}
		?>
		<?php include_once "include/head.php"; ?>

		<div class="jumbotron" id="header">
			<div class="container" ></div>
		</div>

		<div class="container" style="padding-bottom: 50px;">

			<?php
			if ($is_auth == 1) {
			?>
				<div class="row">
					<div class="col-md-12">
						<h2>Отчеты о финансовой и хозяйственной деятельности СНТ</h2>
					</div>
				</div>
				<?php $result_reports_fhd = mysql_query("SELECT * FROM reports_fhd ORDER BY date") or die(mysql_error()); ?>
				<div class="row">
					<div class="col-md-12">
						<table class="table table-condensed">
							<tr>
								<th>Период</th>
								<th>Название</th>
								<th style="width: 60px;">Скачать</th>
							</tr>
							<?php
							while ($reports_fhd = mysql_fetch_assoc($result_reports_fhd)) {
								echo '<tr>';
								echo '<td>'.date( 'm.Y',strtotime($reports_fhd['date'])).'</td>';
								echo '<td>'.$reports_fhd['name'].'</td>';
								echo '<td style="text-align: center;"><a class="del_user" href="'.$reports_fhd['path'].'" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a></td>';
								echo '</tr>';
							}
							?>
						</table>
					</div>
				</div>
					
			<?php
			}
			else
			{
			?>
			  <div class="col-md-12">
				  <h2>Вы не авторизованы</h2>
				  <hr>
			  </div>
			<?php
			}
			?>
			
			<hr>
		</div>

		<?php include_once "include/footer.php"; ?>

		

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>


	</body>
</html>