
<?php
	include_once "db_connect.php";

	//Сегодняшняя дата
	$curdate = date("Y-m-d");
	echo 'Сегодня '.$curdate.'<br>';
	//Вчерашняя дата
	$yesterday = date('Y-m-d', strtotime('yesterday'));
	echo 'Вчера '.$yesterday.'<br>';

	//Выбираем всех пользователей у которых есть номер модема
	$result_users = mysql_query("SELECT * FROM users WHERE modem_num IS NOT NULL") or die(mysql_error());

	//Перебор пользователей
	while ($users = mysql_fetch_assoc($result_users)) {
		echo 'Пользователь '.$users['email'].'<br>';
		//Выбираем дату последних показаний пользователя
		$last_date_q = "SELECT date FROM Indications WHERE user = ".$users['id']." ORDER BY date DESC LIMIT 1";
		
		$last_date_result = mysql_query($last_date_q) or die(mysql_error());

		//Если есть показаний
		if (mysql_num_rows($last_date_result) != 0) {
			$last_date = date("Y-m-d", strtotime(mysql_result($last_date_result, 0)));
			
		}
		//Если нет показаний 2017-09-01
		else {
			$last_date = '2017-09-01';
		}
		echo 'Дата последних показаний '.$last_date . '<br>';
				
		if ($last_date == $yesterday) {
			echo 'Новых показаний нет<br>';
		}
		else {
		
			//перебираем даты, пока не не будет сегодняшняя
			while (date("Y-m-d", strtotime('+1 day', strtotime($last_date))) != $curdate) {
								
				
				$date_q = date("Y-m-d", strtotime('+2 day', strtotime($last_date)));
				
				$indication_q = 'https://lk.waviot.ru/api/report/?template=a2f5261236bf3e2aede89cae168d2c2d&period=P1D&from='.$date_q.'&raw=1&modem='.$users['modem_num'].'&to='.$date_q; 
				
				echo $indication_q . '<br>';
				
				//получаем данные с вавиота
				$indications = file_get_contents($indication_q) or die('Connection timed out');
				//расшифровываем ответ
				$indications = json_decode($indications);
				//Подсчитываем косичество элементов массива
				$count_tarifs = count((array)$indications);
				echo 'элементов вмассиве '.$count_tarifs. '<br>';
				//var_dump($indications->electro_ac_p_lsum_t2);
				
				for ($i=1; $i<=$count_tarifs; $i++) {
					
					$tarif = 'electro_ac_p_lsum_t'.$i;
					
					//Проверяем, есть ли такой тариф
					$check_tarif_result = mysql_query("SELECT * FROM tarifs WHERE id_waviot = '".$tarif."'") or die(mysql_error());

					if (mysql_num_rows($check_tarif_result) != 0) {
						echo "<b>$i</b><br>";
						echo "<b>$tarif</b><br>";
						$tarif_data = $indications->$tarif;
						//var_dump($tarif_data);
						echo '<br>';

						foreach ($tarif_data as $j => $value) {
							$date = $value->datetime;
							$value_ind = $value->value;
							//var_dump($value_ind);
							//echo '<hr>';
							if ($value_ind > 0 || $value_ind != '0') {
								$prev_ind_result = mysql_query("SELECT Indications FROM Indications WHERE user = ".$users['id']." AND tarif = (SELECT id FROM tarifs WHERE id_waviot = '".$tarif."') ORDER BY date DESC LIMIT 1") or die(mysql_error());
								$prev_ind = mysql_result($prev_ind_result, 0);
								$ind_diff = $value_ind - $prev_ind;
								
								
								echo '<b>Добавляем показания</b><br>';
								$insert_q = "INSERT INTO Indications SET
																											date='".date('Y-m-d', strtotime($date)-86400)."',
																											user=".$users['id'].",
																											tarif=(SELECT id FROM tarifs WHERE id_waviot = '".$tarif."'),
																											Indications=".$value_ind.",
																											prev_indications = '".$prev_ind."',
																											additional=(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'),
																											additional_sum= $ind_diff*(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'),
																											auto = 1";
								echo $insert_q . '<br>';
								mysql_query($insert_q) or die(mysql_error());
																
								echo '<b>Обновляем баланс</b><br>';
								$q_upd_balans = "UPDATE users u SET u.balans = (u.balans - ($ind_diff*(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'))), u.total_balance = (u.total_balance - ($ind_diff*(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'))) WHERE u.id = ".$users['id'];
								echo $q_upd_balans;
								echo '<hr>';
								mysql_query($q_upd_balans) or die(mysql_error());

							}
						}
					}
					else {echo 'Нет тарифа в базе<br>';}
				}
				
				$last_date = date("Y-m-d", strtotime('+1 day', strtotime($last_date)));
				echo '<hr>';
			}
		}
	}
