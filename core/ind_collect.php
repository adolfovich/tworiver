<?php
	include_once "db_connect.php";
	
	$curdate = date("Y-m-d");
	
	$result_users = mysql_query("SELECT * FROM users WHERE modem_num IS NOT NULL") or die(mysql_error());
	
	while ($users = mysql_fetch_assoc($result_users)) {
		$indication_q = 'https://lk.waviot.ru/api/report/?template=a2f5261236bf3e2aede89cae168d2c2d&period=P1D&from='.$curdate.'&raw=1&modem='.$users['modem_num'].'&to='.$curdate;
		//echo $indication_q;
		$indications = $obj = json_decode(file_get_contents($indication_q));
		
		$count_tarifs = count((array)$indications);
		//var_dump($indications);
		//echo '<hr>';
		//var_dump($indications->electro_ac_p_lsum_t1);
		
		for ($i=1; $i<=$count_tarifs; $i++) {
			$tarif = 'electro_ac_p_lsum_t'.$i;
			
			//Проверяем, есть ли тариф из счетчика в системе
			
			$check_tarif_result = mysql_query("SELECT * FROM tarifs WHERE id_waviot = '".$tarif."'") or die(mysql_error());
			
			if (mysql_num_rows($check_tarif_result) != 0) {
				$tarif_data = $indications->$tarif;
				$date = $tarif_data[0]->datetime;
				$value = $tarif_data[0]->value;
				//var_dump($value);
				//echo '<hr>';
				if ($value > 0) {
					$prev_ind_result = mysql_query("SELECT Indications FROM Indications WHERE user = ".$users['id']." AND tarif = (SELECT id FROM tarifs WHERE id_waviot = '".$tarif."') ORDER BY date DESC LIMIT 1") or die(mysql_error());
					$prev_ind = mysql_result($prev_ind_result, 0);
					
				}
				$ind_diff = $value - $prev_ind;
				//добавляем показания
				$insert_q = "INSERT INTO Indications SET date='".date("Y-m-d",strtotime($curdate)-86400)."', user=".$users['id'].", tarif=(SELECT id FROM tarifs WHERE id_waviot = '".$tarif."'), Indications=".$value.", prev_indications = '".$prev_ind."', additional=(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'), additional_sum= $ind_diff*(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'), auto = 1";
				//echo $insert_q;
				mysql_query($insert_q) or die(mysql_error());
				//Обновляем баланс пользователя
				$q_upd_balans = "UPDATE users u SET u.balans = (u.balans - ($ind_diff*(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'))), u.total_balance = (u.total_balance - ($ind_diff*(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'))) WHERE u.id = ".$users['id'];
				//echo $q_upd_balans;
				mysql_query($q_upd_balans) or die(mysql_error());
			}
		}
	}