<?php
	include_once "../core/db_connect.php";
	include_once "../core/func.php";

	$user_id = $_GET['user'];
	$date_from = $_GET['datefrom'];
	$date_to = $_GET['dateto'];

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

?>

<!DOCTYPE html>
<!--html lang="ru" onMouseOver="window.close();"-->
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

	<div class="container" style="width: 100%;">
		<div class="row">
			<div class="col-md-12">

				<table style="width: 100%;">
					<tr>
						<td colspan="3" style="text-align: center;">
							<h4>Акт сверки оплаты за электроэнергию пользователем <?php echo $contract_num; ?></h4>
							<h4>за период с <?= date( 'd.m.Y',strtotime($date_from)); ?> по <?= date( 'd.m.Y',strtotime($date_to)); ?></h4>
						</td>
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
						<td style="text-align: right; font-weight: 700;">Электросчетчик Марка</td>
						<td style="text-align: right; width: 200px; border-bottom: 1px solid #000;"><?php echo $user_sch_model; ?></td>
					</tr>
					<tr>
						<td style="text-align: left; font-weight: 700;">Владелец: <?php echo $user_name; ?></td>
						<td style="text-align: right; font-weight: 700;">№</td>
						<td style="text-align: right; width: 200px; border-bottom: 1px solid #000;"><?php echo $user_sch_num; ?></td>
					</tr>
					<tr>
						<td style="text-align: left; font-weight: 700;"></td>
						<td style="text-align: right; font-weight: 700;">Пломба №</td>
						<td style="text-align: right; width: 200px; border-bottom: 1px solid #000;"><?php echo $user_sch_plomb_num; ?></td>
					</tr>
				</table>
			</div>
		</div>

		<?php
			//выбираем все показания счетчика за указанный период $date_from - $date_to
			$result_user_indications = mysql_query("SELECT i.date, i.prev_indications, i.Indications, i.additional as price, i.additional_sum, t.name as tarif FROM Indications i, tarifs t WHERE i.user = $user_id AND i.tarif = t.id AND i.date BETWEEN '$date_from' AND '$date_to'") or die(mysql_error());
			//echo "SELECT i.date, i.Indications, i.additional, i.additional_sum, t.name as tarif FROM Indications i, tarifs t WHERE i.user = $user_id AND i.tarif = t.id AND i.date BETWEEN '$date_from' AND '$date_to'";
		?>

		<div class="row">
			<div class="col-md-12">
				<p><u>Показания счетчика</u></p>
				<table class="table table-bordered table-condensed" style="font-size: 10px;">
					<tr>
						<th style="padding: 2px;" rowspan="2" align="center">Дата</th>
						<th style="padding: 2px;" rowspan="2" align="center">Тариф</th>
						<th style="padding: 2px;" colspan="3" align="center">Показания</th>
						<th style="padding: 2px;" rowspan="2" align="center">Цена</th>
						<th style="padding: 2px;" rowspan="2" align="center">Начислено</th>
					</tr>
					<tr>
						<th align="center">Начало</th>
						<th align="center">Конец</th>
						<th align="center">Расход</th>
					</tr>
						<?php

							$month_name = [
								1 => 'Январь',
								2 => 'Февраль',
								3 => 'Март',
								4 => 'Апрель',
								5 => 'Май',
								6 => 'Июнь',
								7 => 'Июль',
								8 => 'Август',
								9 => 'Сентябрь',
								10 => 'Октябрь',
								11 => 'Ноябрь',
								12 => 'Декабрь'
							];

							$sum_ind = 0;
							//$sum_ind =  $sum_ind;
							$curr_month = date("m");
							$curr_year = date("Y");
							$prev_month = 0;
							//echo '<script>console.log("cur_month '.$curr_month.'")</script>';
							while ($user_indications = mysql_fetch_assoc($result_user_indications)) {
								$user_indications_m = date( "m",strtotime($user_indications['date']));
								$user_indications_Y = date( "Y",strtotime($user_indications['date']));
								//echo '<script>console.log("$user_indications_m '.$user_indications_m.'")</script>';
								if ($user_indications_m != $curr_month || $user_indications_Y != $curr_year) {
									//echo '<script>console.log("'.date('n',strtotime($user_indications['date'])).'")</script>';
									if ($user_indications_m == $prev_month) {
										//echo '<script>console.log("!=")</script>';
										$prev_month_name = $month_name[date('n',strtotime($user_indications['date']))];
										$prev_month_tarif = $user_indications['tarif'];

										if ($user_indications['tarif'] == 'Электроэнергия Т1') {
											$prev_month_price_t1 = $user_indications['price'];
											$prev_month_count_consumption_t1 = $prev_month_count_consumption_t1 + round($user_indications['Indications'] - $user_indications['prev_indications'], 2);
											$prev_month_count_additional_sum_t1 = $prev_month_count_additional_sum_t1 + $user_indications['additional_sum'];
											$prev_month_end1 = $user_indications['Indications'];
											$prev_month_start1 = $user_indications['prev_indications'];
										} else {
											$prev_month_price_t2 = $user_indications['price'];
											$prev_month_count_consumption_t2 = $prev_month_count_consumption_t2 + round($user_indications['Indications'] - $user_indications['prev_indications'], 2);
											$prev_month_count_additional_sum_t2 = $prev_month_count_additional_sum_t2 + $user_indications['additional_sum'];
											$prev_month_end2 = $user_indications['Indications'];
											$prev_month_start2 = $user_indications['prev_indications'];
										}

									} else {
										if ($prev_month != 0) {
											//echo '<script>console.log("tarif '.$user_indications['tarif'].'")</script>';
											if ($user_indications['tarif'] == 'Электроэнергия Т1') {
												$prev_month_start1 = $user_indications['prev_indications'];
											} else {
												$prev_month_start2 = $user_indications['prev_indications'];
											}
											echo '<tr>';
											echo '<td style="padding: 1px;" align="center">' . $prev_month_name . ' '.date( "Y",strtotime($user_indications['date'])).'</td>';
											echo '<td style="padding: 1px;" align="center">Электроэнергия Т1</td>';
											echo '<td style="padding: 1px;" align="center">'.$prev_month_start1.'</td>';
											echo '<td style="padding: 1px;" align="center">'.$prev_month_end1.'</td>';
											echo '<td style="padding: 1px;" align="center">' . $prev_month_count_consumption_t1 .'</td>';
											echo '<td style="padding: 1px;" align="center">' . $prev_month_price_t1 . '</td>';
											echo '<td style="padding: 1px;" align="center">' . $prev_month_count_additional_sum_t1 . '</td>';
											echo '</tr>';

											echo '<tr>';
											echo '<td style="padding: 1px;" align="center">' . $prev_month_name . ' '.date( "Y",strtotime($user_indications['date'])).'</td>';
											echo '<td style="padding: 1px;" align="center">Электроэнергия Т2</td>';
											echo '<td style="padding: 1px;" align="center">'.$prev_month_start2.'</td>';
											echo '<td style="padding: 1px;" align="center">'.$prev_month_end2.'</td>';
											echo '<td style="padding: 1px;" align="center">' . $prev_month_count_consumption_t2 .'</td>';
											echo '<td style="padding: 1px;" align="center">' . $prev_month_price_t2 . '</td>';
											echo '<td style="padding: 1px;" align="center">' . $prev_month_count_additional_sum_t2 . '</td>';
											echo '</tr>';

											$prev_month = date( 'm',strtotime($user_indications['date']));

											$prev_month_price_t1 = 0;
											$prev_month_price_t2 = 0;
											$prev_month_count_consumption_t1 = 0;
											$prev_month_count_consumption_t2 = 0;
											$prev_month_count_additional_sum_t1 = 0;
											$prev_month_count_additional_sum_t2 = 0;

											$sum_ind = $sum_ind + $prev_month_count_additional_sum_t1 + $prev_month_count_additional_sum_t2;

										} else {
											$prev_month = date( 'm',strtotime($user_indications['date']));
											//echo '<script>console.log("tarif '.$user_indications['tarif'].'")</script>';
											if ($user_indications['tarif'] == 'Электроэнергия Т1') {
												$prev_month_start1 = $user_indications['prev_indications'];
											} else {
												$prev_month_start2 = $user_indications['prev_indications'];
											}

											$prev_month_price_t1 = 0;
											$prev_month_price_t2 = 0;
											$prev_month_count_consumption_t1 = 0;
											$prev_month_count_consumption_t2 = 0;
											$prev_month_count_additional_sum_t1 = 0;
											$prev_month_count_additional_sum_t2 = 0;
										}
									}
								} else {
									if ($prev_month != 0) {
										echo '<tr>';
										echo '<td style="padding: 1px;" align="center">' . $prev_month_name . ' '.date( "Y",strtotime($user_indications['date'])).'</td>';
										echo '<td style="padding: 1px;" align="center">Электроэнергия Т1</td>';
										echo '<td style="padding: 1px;" align="center">'.$prev_month_start1.'</td>';
										echo '<td style="padding: 1px;" align="center">'.$prev_month_end1.'</td>';
										echo '<td style="padding: 1px;" align="center">' . $prev_month_count_consumption_t1 .'</td>';
										echo '<td style="padding: 1px;" align="center">' . $prev_month_price_t1 . '</td>';
										echo '<td style="padding: 1px;" align="center">' . $prev_month_count_additional_sum_t1 . '</td>';
										echo '</tr>';

										echo '<tr>';
										echo '<td style="padding: 1px;" align="center">' . $prev_month_name . ' '.date( "Y",strtotime($user_indications['date'])).'</td>';
										echo '<td style="padding: 1px;" align="center">Электроэнергия Т2</td>';
										echo '<td style="padding: 1px;" align="center">'.$prev_month_start2.'</td>';
										echo '<td style="padding: 1px;" align="center">'.$prev_month_end2.'</td>';
										echo '<td style="padding: 1px;" align="center">' . $prev_month_count_consumption_t2 .'</td>';
										echo '<td style="padding: 1px;" align="center">' . $prev_month_price_t2 . '</td>';
										echo '<td style="padding: 1px;" align="center">' . $prev_month_count_additional_sum_t2 . '</td>';
										echo '</tr>';

										$prev_month = date( 'm',strtotime($user_indications['date']));

										$prev_month_price_t1 = 0;
										$prev_month_price_t2 = 0;
										$prev_month_count_consumption_t1 = 0;
										$prev_month_count_consumption_t2 = 0;
										$prev_month_count_additional_sum_t1 = 0;
										$prev_month_count_additional_sum_t2 = 0;

									}

									$prev_month = 0;
									echo '<tr>';
									echo '<td style="padding: 1px;" align="center">' . date( 'd.m.Y',strtotime($user_indications['date'])) . '</td>';
									echo '<td style="padding: 1px;" align="center">' . $user_indications['tarif'] . '</td>';
									echo '<td style="padding: 1px;" align="center">' . $user_indications['prev_indications'] . '</td>';
									echo '<td style="padding: 1px;" align="center">' . $user_indications['Indications'] . '</td>';
									echo '<td style="padding: 1px;" align="center">' . round($user_indications['Indications'] - $user_indications['prev_indications'], 2).'</td>';
									echo '<td style="padding: 1px;" align="center">' . $user_indications['price'] . '</td>';
									echo '<td style="padding: 1px;" align="center">' . $user_indications['additional_sum'] . '</td>';
									echo '</tr>';
								}



								$sum_ind = $sum_ind + $user_indications['additional_sum'];
								//$sum_ind = (float) $sum_ind;

							}
						?>
					<tr>
						<td colspan="6" style="font-weight: 700;">ИТОГО:</td>
						<td style="font-weight: 700;"><?php echo sprintf("%01.2f", $sum_ind); ?></td>
					</tr>
				</table>
			</div>
		</div>
		<?php
			//выбираем все оплаты
			$result_user_payments = mysql_query("SELECT * FROM payments WHERE user = $user_id AND date BETWEEN '$date_from' AND '$date_to'") or die(mysql_error());
		?>
		<div class="row">
			<div class="col-md-12">
				<p><u>Оплаты</u></p>
				<table class="table table-bordered table-condensed" style="font-size: 10px;">
					<tr>
						<th style="padding: 2px;">Дата</th>
						<th style="padding: 2px;">Основание</th>
						<th style="padding: 2px;">Сумма</th>
					</tr>
						<?php
							$sum_payments = 0;
							while ($user_payments = mysql_fetch_assoc($result_user_payments)) {
								echo '<tr>';
								echo '<td style="padding: 1px;">' . date( 'd.m.Y',strtotime($user_payments['date'])) . '</td>';
								echo '<td style="padding: 1px;">' . $user_payments['base'] . '</td>';
								echo '<td style="padding: 1px;">' . $user_payments['sum'] . '</td>';
								echo '</tr>';

								$sum_payments = $sum_payments + $user_payments['sum'];
							}
						?>
					<tr>
						<td colspan="2" style="font-weight: 700;">ИТОГО:</td>
						<td style="font-weight: 700;"><?php echo sprintf("%01.2f", $sum_payments); ?></td>
					</tr>
				</table>
			</div>
		</div>

		<?php

			$saldo = $sum_payments - $sum_ind;
			if ($saldo > 0) {
				$saldo_name = 'Дебет';
				$saldo_cuirsive = num2str($saldo);
			}
			else if ($saldo < 0) {
				$saldo_name = 'Кредит';
				$saldo_cuirsive = num2str(-$saldo);
				$saldo = -$saldo;
			}
			else if ($saldo == 0) {
				$saldo_name = '';
				$saldo_cuirsive = num2str($saldo);
			}
		?>

		<div class="row">
			<div class="col-md-12">
				<p><u><b>Сальдо: <?php echo $saldo_name . ' ' . sprintf("%01.2f", $saldo) . ' ('.$saldo_cuirsive.')'; ?></b></u></p>
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
	<!--script>
		window.print();

	</script-->
	</body>
</html>
