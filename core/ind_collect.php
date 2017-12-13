<?php
	include_once "db_connect.php";

	$curdate = date("Y-m-d");
	$yesterday = date('Y-m-d', strtotime('yesterday'));

	$result_users = mysql_query("SELECT * FROM users WHERE modem_num IS NOT NULL") or die(mysql_error());

	while ($users = mysql_fetch_assoc($result_users)) {
		$last_date_q = "SELECT date FROM Indications WHERE user = ".$users['id']." ORDER BY date DESC LIMIT 1";
		//echo $last_date_q . '<hr>';
		$last_date_result = mysql_query($last_date_q) or die(mysql_error());

		if (mysql_num_rows($last_date_result) != 0) {
			$last_date = date("Y-m-d", strtotime(mysql_result($last_date_result, 0)));
		}
		else {
			$last_date = '2017-09-01';
		}

		//echo mysql_result($last_date_result, 0) . '<hr>';
		//$prev_date = date("Y-m-d", strtotime(mysql_result($prev_ind_result, 0)));
		//echo $prev_date . '<hr>';
		if ($last_date != $yesterday) {
			$date_start = date("Y-m-d", strtotime($last_date)+86400*2);
		}
		else {
			$date_start = $curdate;
		}
		//echo $date_start;

		$indication_q = 'https://lk.waviot.ru/api/report/?template=a2f5261236bf3e2aede89cae168d2c2d&period=P1D&from='.$date_start.'&raw=1&modem='.$users['modem_num'].'&to='.$curdate;
		//echo $indication_q . '<hr>';
		$indications = json_decode(file_get_contents($indication_q));
		$count_tarifs = count((array)$indications);
		//echo $count_tarifs . '<hr>';
		//var_dump($indications);
		//echo '<hr>';
		//var_dump($indications->electro_ac_p_lsum_t2);
		//echo '<hr>';

		for ($i=1; $i<=$count_tarifs; $i++) {
			//echo "<b>$i</b><br>";
			$tarif = 'electro_ac_p_lsum_t'.$i;
			//echo "<b>$tarif</b><br>";
			$check_tarif_result = mysql_query("SELECT * FROM tarifs WHERE id_waviot = '".$tarif."'") or die(mysql_error());

			if (mysql_num_rows($check_tarif_result) != 0) {
				$tarif_data = $indications->$tarif;
				//var_dump($tarif_data);
				//echo '<hr>';

				foreach ($tarif_data as $j => $value) {
					$date = $value->datetime;
					$value_ind = $value->value;
					//var_dump($value_ind);
					//echo '<hr>';
					if ($value_ind > 0 || $value_ind != '0') {
						$prev_ind_result = mysql_query("SELECT Indications FROM Indications WHERE user = ".$users['id']." AND tarif = (SELECT id FROM tarifs WHERE id_waviot = '".$tarif."') ORDER BY date DESC LIMIT 1") or die(mysql_error());
						$prev_ind = mysql_result($prev_ind_result, 0);
						$ind_diff = $value_ind - $prev_ind;

						$insert_q = "INSERT INTO Indications SET
																									date='".date('Y-m-d', strtotime($date)-86400)."',
																									user=".$users['id'].",
																									tarif=(SELECT id FROM tarifs WHERE id_waviot = '".$tarif."'),
																									Indications=".$value_ind.",
																									prev_indications = '".$prev_ind."',
																									additional=(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'),
																									additional_sum= $ind_diff*(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'),
																									auto = 1";
						//echo $insert_q . '<hr>';
						mysql_query($insert_q) or die(mysql_error());
						//Обновляем баланс пользователя
						$q_upd_balans = "UPDATE users u SET u.balans = (u.balans - ($ind_diff*(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'))), u.total_balance = (u.total_balance - ($ind_diff*(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'))) WHERE u.id = ".$users['id'];
						//echo $q_upd_balans;
						mysql_query($q_upd_balans) or die(mysql_error());

					}
				}
			}
		}
	}
