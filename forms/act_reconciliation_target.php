<?php
	include_once "../core/db_connect.php";
	include_once "../core/func.php";
	
	$user_id = $_GET['user'];
	
	//выбираем данные по пользователю
	$result_user_data = mysql_query("SELECT * FROM users WHERE id = $user_id") or die(mysql_error());
			
	while ($user_data = mysql_fetch_assoc($result_user_data)) {
		$user_uchastok = $user_data['uchastok'];
		$user_name = $user_data['name'];
		$user_sch_model = $user_data['sch_model'];
		$user_sch_num = $user_data['sch_num'];
		$user_sch_plomb_num = $user_data['sch_plomb_num'];
		
	}
	
	//выбираем данные договора на энергопотребление
	$result_user_contracts = mysql_query("SELECT * FROM users_contracts WHERE user = $user_id AND date_end IS NULL") or die(mysql_error());
	
	while ($user_contracts = mysql_fetch_assoc($result_user_contracts)) {
		$contract_num = $user_contracts['num'];
		$contract_date = $user_contracts['date_start'];
	}
	
	//Выбираем все членские взносы
	$result_user_contributions = mysql_query("SELECT * FROM users_contributions WHERE user = $user_id AND contribution_type = 2") or die(mysql_error());
	//users_contributions
	
	$sum = 0;
	$sum_paid = 0;
?>

<!DOCTYPE html>
<!-- <html lang="ru" onMouseOver="window.close();"> -->
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Печать акта сверки - Система управления СНТ</title>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
		<!-- Latest compiled and minified JavaScript -->
	</head>
	<body>

	<style type="text/css" media="print">
      div.page
      {
        page-break-after: always;
        page-break-inside: avoid;
      }
    </style>
	
	<div class="container" style="width: 100%;"">
		<div class="row">
			<div class="col-md-12">
					
				<table style="width: 100%;">
					<tr>
						<td colspan="3" style="text-align: center;"><h4>Акт сверки оплаты по целевым взносам</h4></td>
					</tr>
					<tr>
						<td style="text-align: left;"><h5>СНТ "Двуречье"</h5></td>
						<td style="text-align: right;" colspan="2"><h5>"___" _________________ 201__ г.</h5></td>
					</tr>
					<tr>
						<td style="text-align: left;"></td>
						<td style="text-align: right;" colspan="2"></td>
					</tr>
					<tr>
						<td style="text-align: left; font-weight: 700;">Участок №: <?php echo $user_uchastok; ?></td>
						<td style="text-align: left; font-weight: 700;"></td>
						<td style="text-align: left; font-weight: 700;"></td>
					</tr>
					<tr>
						<td style="text-align: left; font-weight: 700;">Владелец: <?php echo $user_name; ?></td>
						<td style="text-align: left; font-weight: 700;"></td>
						<td style="text-align: left; font-weight: 700;"></td>
					</tr>
					<tr>
						<td style="text-align: left; font-weight: 700;"></td>
						<td style="text-align: left; font-weight: 700;"></td>
						<td style="text-align: left; font-weight: 700;"></td>
					</tr>
				</table>
			</div>
		</div>
			
		<div class="row">
			<div class="col-md-12">
				<p><u>Взносы</u></p>
				<table class="table table-bordered table-condensed" style="font-size: 10px;">
					<tr>
						<th style="padding: 2px;">Дата</th>
						<th style="padding: 2px;">Назначение</th>
						<th style="padding: 2px;">Сумма, руб.</th>
						<th style="padding: 2px;">Оплачен</th>
						<th style="padding: 2px;">Дата оплаты</th>
					</tr>
						<?php 
						while ($user_contributions = mysql_fetch_assoc($result_user_contributions)) {
							echo '<tr>';
							echo '<td>'.date( 'd.m.Y',strtotime($user_contributions['date'])).'</td>';
							echo '<td>'.$user_contributions['comment'].'</td>';
							echo '<td>'.$user_contributions['sum'].'</td>';
							if ($user_contributions['paid'] == 0) {
								echo '<td>Нет</td>';
								
							}
							else {
								echo '<td>Да</td>';
								$sum_paid = $sum_paid + $user_contributions['sum'];
							}
							if (is_null($user_contributions['paid_date'])) {
								echo '<td>---</td>';
							}
							else {
								echo '<td>'.date( 'd.m.Y',strtotime($user_contributions['paid_date'])).'</td>';
							}
							echo '</tr>';
							$sum = $sum + $user_contributions['sum'];
						}
						
						?>
					
				</table>
			</div>
		</div>
		
		<?php 
			
			$saldo = $sum - $sum_paid;
			if ($saldo < 0) {
				$saldo_name = 'Дебет';
				$saldo_cuirsive = num2str(-$saldo);
				$saldo = -$saldo;
			}
			else if ($saldo > 0) {
				$saldo_name = 'Кредит';
				$saldo_cuirsive = num2str($saldo);
				
			}
			else if ($saldo == 0) {
				$saldo_name = '';
				$saldo_cuirsive = num2str($saldo);
			}
		?>
		
		<div class="row">
			<div class="col-md-12">
				<p>Сумма по членским взносам: <?php echo $sum;?></p>
				<p>Из них оплачено: <?php echo $sum_paid;?></p>
				<p><u><b>Сальдо: <?php echo $saldo_name . ' ' . $saldo . ' ('.$saldo_cuirsive.')'; ?></b></u></p>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<table style="width: 100%; text-align: center; margin-top: 30px; margin-bottom: 30px;">
					<tr>
						<td>Сверку провели: </td>
						<td>Хакало Владимир Олегович _______________</td>
						<td><?php echo $user_name; ?> _______________</td>
					</tr>
				</table>				
			</div>
		</div>
	</div>
	<script>
		//window.print();

	</script>
	</body>
</html>