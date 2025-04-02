<?php
	
	include ('../_conf.php');
	include ('../classes/safemysql.class.php');
	$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

	require_once('../classes/core.class.php');

	$core  = new Core();

	$url = $core->url;
	$form = $core->form;
	$ip = $core->ip;
	$get = $core->setGet();
	
	
	//авторизация

	if( $curl = curl_init() ) {
		curl_setopt($curl, CURLOPT_URL, 'https://auth.waviot.ru/?action=user-login');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, '{"login":"hakalo@bk.ru","password":"(!aRQ_UYQ(46v"}' );
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Content-type: application/json',
		'user-agent: SNT-Dvurech`e',
		'x-requested-with: XMLHttpRequest'
		));

		$out = curl_exec($curl);
		if ($out === false) {
			echo '<br>curl_error '.curl_error($curl).'<br>';
			die();
		}
		echo "<br>AUTH OUT<br>  \r\n";
		var_dump($out);
		echo "<br> \r\n";
		$str = json_decode($out, TRUE);
		$WAVIOT_JWT = $str['WAVIOT_JWT'];
		var_dump($WAVIOT_JWT);
		echo "<br> \r\n";
	}
	
	//die();
	
	echo '<pre>';

	//Сегодняшняя дата
	$curdate = date("Y-m-d");
	echo 'Сегодня '.$curdate."<br> \r\n";
	//Вчерашняя дата
	$yesterday = date('Y-m-d', strtotime('yesterday'));
	echo 'Вчера '.$yesterday."<br> \r\n";

	//Выбираем всех пользователей у которых есть номер модема
	$result_counters = $db->getAll("SELECT c.* FROM counters c WHERE c.modem_num IS NOT NULL AND c.modem_num NOT LIKE '' AND (SELECT date_end FROM users_contracts WHERE id = c.contract_id) IS NULL AND dismantling_date IS NULL");

	
	//var_dump($result_counters);
	//die();

	//Перебор пользователей
	foreach ($result_counters as $counter) {
		echo 'Счетчик '.$counter['id']."<br> \r\n";
		//Выбираем дату последних показаний пользователя
		$last_date_q = "SELECT date FROM Indications WHERE counter_id = ".$counter['id']." ORDER BY date DESC LIMIT 1";
		$last_date_result = $db->getOne($last_date_q);

		//Если есть показания
		if ($last_date_result) {
			$last_date = date("Y-m-d", strtotime($last_date_result));
		}	else {
			if ($counter['install_date']) {
				$last_date = date("Y-m-d", strtotime($counter['install_date']) - 86400);
			} else {
				//$last_date = '2017-09-01';
				$last_date = '2018-12-31';
			}
		}
		echo 'Дата последних показаний '.$last_date . "<br> \r\n";

		if ($last_date == $yesterday) {
			echo 'Новых показаний нет'."<br> \r\n";
		}	else {

			//перебираем даты, пока не не будет сегодняшняя
			while (date("Y-m-d", strtotime('+1 day', strtotime($last_date))) != $curdate) {

				$date_q = date("Y-m-d", strtotime('+2 day', strtotime($last_date)));
				echo 'дата в запросе '.$last_date."<br> \r\n";

				//$indication_q = 'https://lk.waviot.ru/api/report/?template=a2f5261236bf3e2aede89cae168d2c2d&period=P1D&from='.$date_q.'&raw=1&modem='.$counter['modem_num'].'&to='.$date_q . '&key=1e3a109e8a4ffdeb4715bf022a04a3bf';
				
				$counter['modem_num'] = '865d2e';
				
				$indication_q = 'https://lk.waviot.ru/api.data/get_modem_channel_values/?modem_id='.$counter['modem_num'].'&from='.strtotime($last_date." 00:01:00").'&to='.(strtotime($last_date)+3600).'&channel=electro_ac_p_lsum_t1';
				echo "indication_q <br> \r\n";
				echo $indication_q . " \r\n";

				if( $curl = curl_init() ) {
					curl_setopt($curl, CURLOPT_URL, $indication_q);
					curl_setopt($curl, CURLOPT_HEADER, 0);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
					curl_setopt($curl, CURLOPT_COOKIESESSION,true);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($curl, CURLOPT_HTTPHEADER, array(
					'Content-type: application/json',
					'user-agent: SNT-Dvurech`e',
					'x-requested-with: XMLHttpRequest',
					'authorization: bearer '.$WAVIOT_JWT
					));

					$out = curl_exec($curl);
					if($out === false)
					{
    						die( 'Curl error: ' . curl_error($curl));
					}
					echo "OUT <br><pre> \r\n";
					var_dump($out);
					echo " </pre><br> \r\n";
					
					if ($out === false) {
						echo '<br>curl_error '.curl_error($curl).'<br>';
						die();
					}
					
					echo '<pre>';
					
					$indications = json_decode($out, TRUE);
					//var_dump($indications);
					
					foreach ($indications["values"] as $key => $value) {
						
						//var_dump($value);
						//echo " <br> \r\n";
						
						echo date("d-m-Y H:m", $key)." => ".$value."<br> \r\n";
					}
					
					echo '</pre>';
					
					die();

				}

				//расшифровываем ответ
				$indications = json_decode($out);
				var_dump($indications);

				if (isset($indications->result) && $indications->result == 'error') {
					//die('ERROR: '.$indications->message." \r\n");
					
					echo 'ERROR: '.$indications->message;
					echo " <br> \r\n";
					
					
				}

				//Подсчитываем косичество элементов массива
				$count_tarifs = count((array)$indications);
				echo 'элементов вмассиве '.$count_tarifs. "<br> \r\n";
				//var_dump($indications->electro_ac_p_lsum_t2);

				for ($i=1; $i<=$count_tarifs; $i++) {

					$tarif = 'electro_ac_p_lsum_t'.$i;

					//Проверяем, есть ли такой тариф
					$check_tarif_result = $db->getRow("SELECT * FROM tarifs WHERE id_waviot = '".$tarif."'");

					if ($check_tarif_result) {
						echo "<b>$i</b>"."<br> \r\n";
						echo "<b>$tarif</b>"."<br> \r\n";
						$tarif_data = $indications->$tarif;
						//var_dump($tarif_data);
						echo '<br>'."<br> \r\n";

						foreach ($tarif_data as $j => $value) {
							$date = $value->datetime;
							$value_ind = round($value->value, 2);
							//var_dump($value_ind);
							//echo '<hr>';
							if ($value_ind > 0 || $value_ind != '0') {
								$prev_ind = $db->getOne("SELECT Indications FROM Indications WHERE counter_id = ".$counter['id']." AND tarif = (SELECT id FROM tarifs WHERE id_waviot = '".$tarif."') ORDER BY date DESC LIMIT 1");
								
								echo '<b>Предыдущие показания</b>'."<br> \r\n";
								var_dump($prev_ind);
								echo '<hr>';
								
								if (!$prev_ind) $prev_ind = 0;
								
								$ind_diff = $value_ind - $prev_ind;
								
								
								
								//проверяем, есть-ли показания в базе 
								$exist_ind = $db->getRow("SELECT * FROM Indications WHERE date = ?s AND counter_id = ?s AND tarif = (SELECT id FROM tarifs WHERE id_waviot = ?s)", date('Y-m-d', strtotime($date)-86400), $counter['id'], $tarif);
								
								if (!$exist_ind) {
									echo '<b>Добавляем показания</b>'."<br> \r\n";

									$insert_q = "
										INSERT INTO Indications SET
											date='".date('Y-m-d', strtotime($date)-86400)."',
											user=".$counter['user_id'].",
											counter_id = ".$counter['id'].",
											tarif=(SELECT id FROM tarifs WHERE id_waviot = '".$tarif."'),
											Indications=".$value_ind.",
											prev_indications = '".$prev_ind."',
											additional=(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'),
											additional_sum= $ind_diff*(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'),
											auto = 1";

									echo $insert_q . "<br> \r\n";
									
									$test_q_r = $db->query($insert_q);
									
									//var_dump($insert_q);									
									//var_dump($test_q_r);

									$price = $db->getOne("SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'");

									$amount = $ind_diff * $price;
																		
									echo '<b>Обновляем баланс пользователя '.$counter['user_id'].' на сумму '.$amount.'</b>'."<br> \r\n";
									$core->changeBalance($counter['user_id'], 1, 5, $amount);
									
									echo '<b>Обновляем дату последних показаний</b>'."<br> \r\n";									
									$db->query("UPDATE counters SET last_ind_date = ?s WHERE modem_num = ?s", date('Y-m-d', strtotime($date)-86400), $counter['modem_num']);
									
								} else {
									echo '<b>ПОКАЗАНИЯ УЖЕ ЕСТЬ</b>'."<br> \r\n";
								}

								

							}
						}
					}
					else {echo 'Нет тарифа в базе'."<br> \r\n";}
				}
				//die();

				$last_date = date("Y-m-d", strtotime('+1 day', strtotime($last_date)));
				echo '<hr>'."<br> \r\n";
			}
		}
		//die();
	}
