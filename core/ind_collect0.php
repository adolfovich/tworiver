<?php
	//include_once "db_connect.php";
	include ('../_conf.php');
	include ('../classes/safemysql.class.php');
	$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

	require_once('../classes/core.class.php');

	$core  = new Core();

	$url = $core->url;
	$form = $core->form;
	$ip = $core->ip;
	$get = $core->setGet();

$profiles_descr = [
	'1.0.0' => 'дата-время записи [timestamp]',
	'1.8.0' => 'Активная энергия к потребителю по сумме фаз и тарифов с момента сброса [Вт ч]',
	'1.8.1' => 'Активная энергия к потребителю по сумме фаз тариф 1 с момента сброса [Вт ч]',
	'1.8.2' => 'Активная энергия к потребителю по сумме фаз тариф 2 с момента сброса [Вт ч]',
	'1.8.3' => 'Активная энергия к потребителю по сумме фаз тариф 3 с момента сброса [Вт ч]',
	'1.8.4' => 'Активная энергия к потребителю по сумме фаз тариф 4 с момента сброса [Вт ч]',
	'2.8.0' => 'Активная энергия от потребителя по сумме фаз и тарифов с момента сброса [Вт ч]',
	'2.8.1' => 'Активная энергия от потребителя по сумме фаз тариф 1 с момента сброса [Вт ч]',
	'2.8.2' => 'Активная энергия от потребителя по сумме фаз тариф 2 с момента сброса [Вт ч]',
	'2.8.3' => 'Активная энергия от потребителя по сумме фаз тариф 3 с момента сброса [Вт ч]',
	'2.8.4' => 'Активная энергия от потребителя по сумме фаз тариф 4 с момента сброса [Вт ч]',
	'3.8.0' => 'Реактивная энергия к потребителю по сумме фаз и тарифов с момента сброса[Варч]',
	'3.8.1' => 'Реактивная энергия к потребителю по сумме фаз тариф 1 с момента сброса [Вар ч]',
	'3.8.2' => 'Реактивная энергия к потребителю по сумме фаз тариф 2 с момента сброса [Вар ч]',
	'3.8.3' => 'Реактивная энергия к потребителю по сумме фаз тариф 3 с момента сброса [Вар ч]',
	'3.8.4' => 'Реактивная энергия к потребителю по сумме фаз тариф 4 с момента сброса [Вар ч]',
	'4.8.0' => 'Реактивная энергия от потребителя по сумме фаз и тариф. с момента сброса[Варч]',
	'4.8.1' => 'Реактивная энергия от потребителя по сумме фаз тариф 1 с момента сброса [Вар ч]',
	'4.8.2' => 'Реактивная энергия от потребителя по сумме фаз тариф 2 с момента сброса [Вар ч]',
	'4.8.3' => 'Реактивная энергия от потребителя по сумме фаз тариф 3 с момента сброса [Вар ч]',
	'4.8.4' => 'Реактивная энергия от потребителя по сумме фаз тариф 4 с момента сброса [Вар ч]',
	'9.8.0' => 'Полная энергия к потребителю по сумме фаз и тарифов с момента сброса [ВА ч]',
	'9.8.1' => 'Полная энергия к потребителю по сумме фаз тариф 1 с момента сброса [ВА ч]',
	'9.8.2' => 'Полная энергия к потребителю по сумме фаз тариф 2 с момента сброса [ВА ч]',
	'9.8.3' => 'Полная энергия к потребителю по сумме фаз тариф 3 с момента сброса [ВА ч]',
	'9.8.4' => 'Полная энергия к потребителю по сумме фаз тариф 4 с момента сброса [ВА ч]',
	'1.6.0' => 'Максимум мощности по сумме фаз за период учета [Вт]',
	'1.7.0' => 'Активная мощность по сумме фаз [Вт]',
	'21.7.0' => 'Активная мощность по фазе А [Вт]',
	'41.7.0' => 'Активная мощность по фазе В [Вт]',
	'61.7.0' => 'Активная мощность по фазе С [Вт]',
	'3.7.0' => 'Реактивная мощность по сумме фаз [Вар]',
	'23.7.0' => 'Реактивная мощность по фазе А [Вар]',
	'43.7.0' => 'Реактивная мощность по фазе В [Вар]',
	'63.7.0' => 'Реактивная мощность по фазе С [Вар]',
	'9.7.0' => 'Полная мощность по сумме фаз [ВА]',
	'29.7.0' => 'Полная мощность по фазе А [ВА]',
	'49.7.0' => 'Полная мощность по фазе В [ВА]',
	'69.7.0' => 'Полная мощность по фазе С [ВА]',
	'11.7.0' => 'Ток по сумме фаз [А]',
	'31.7.0' => 'Ток по фазе А [А]',
	'51.7.0' => 'Ток по фазе В [А]',
	'71.7.0' => 'Ток по фазе С [А]',
	'91.7.0' => 'Ток нейтрали [А]',
	'32.7.0' => 'Напряжение фазы А [В]',
	'52.7.0' => 'Напряжение фазы В [В]',
	'72.7.0' => 'Напряжение фазы С [В]',
	'14.7.0' => 'Частота [Гц]',
	'13.7.0' => 'cosFi суммы фаз',
	'33.7.0' => 'cosFi фазы А',
	'53.7.0' => 'cosFi фазы В',
	'73.7.0' => 'cosFi фазы С',
	'81.7.10' => 'угол между фазами А-В [градусы]',
	'81.7.21' => 'угол между фазами В-С [градусы]',
	'81.7.2' => 'угол между фазами А-С [градусы]'
];

	//авторизация

	if( $curl = curl_init() ) {
		curl_setopt($curl, CURLOPT_URL, 'https://api.waviot.ru/auth');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, '{"login":"hakalo@bk.ru","password":"0604196911sql11"}' );
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-type: application/json',
    'user-agent: SNT-Dvurech`e',
		'x-requested-with: XMLHttpRequest'
		));

		$out = curl_exec($curl);
		//echo "<br>AUTH OUT<br>  \r\n";
		//var_dump($out);
		//echo "<br> \r\n";
		$str = json_decode($out, TRUE);
		$WAVIOT_JWT = $str['WAVIOT_JWT'];
		//var_dump($str);
		//echo "<br> \r\n";
	}

	echo '<hr><hr>';

//чтение списка устройств

if( $curl = curl_init() ) {
	curl_setopt($curl, CURLOPT_URL, 'https://api.waviot.ru/telecom/api/device?list=true');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl, CURLOPT_COOKIESESSION,true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	'Content-type: application/json',
	'user-agent: SNT-Dvurech`e',
	'x-requested-with: XMLHttpRequest',
	'authorization: bearer '.$WAVIOT_JWT
	));

	$out = curl_exec($curl);
	//echo "OUT<br>  \r\n";
	//var_dump($out);
	//echo "<br> \r\n";
	$data = json_decode($out, TRUE);
	//var_dump($data);
	//echo "<br> \r\n";
	//echo '<hr><hr>';


}

foreach ($data["x_ids"] as $value) {
	//echo $value.' ('.dechex($value).')<br>';

	$counter = $db->getRow("SELECT * FROM counters WHERE modem_num = ?s", dechex($value));
	//var_dump($counter);
	if (count($counter)) {
		//echo '<p style="color:green">FOUND</p>';
		//https://lk.waviot.ru/api.data/get_modem_channel_values/?modem_id=70B3B9&channel=160
		/*
		if( $curl = curl_init() ) {
			curl_setopt($curl, CURLOPT_URL, 'https://lk.waviot.ru/api.data/get_modem_channel_values/?modem_id='.dechex($value).'&channel=electro_current_l1');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl, CURLOPT_COOKIESESSION,true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-type: application/json',
			'user-agent: SNT-Dvurech`e',
			'x-requested-with: XMLHttpRequest',
			'authorization: bearer '.$WAVIOT_JWT
			));

			$out = curl_exec($curl);
			//var_dump($out);
			$data = json_decode($out, TRUE);
			//echo '<hr>';
		}
		//var_dump($data['values']);

		if (isset($data['values']) && count($data['values'])) {
			foreach ($data['values'] as $key => $value) {
				echo date('d.m.Y H:i:s', $key).' : '. $value .'<br>';
			}
		}*/
		//die();
	} else {
		//echo '<p style="color:red">NOT FOUND</p>';
	}
	//echo '<br>';
}


//запрос данных

	$timefrom = time() - 86400;
	$timeto = time();

	$modem = '715DCF';

	if( $curl = curl_init() ) {
		curl_setopt($curl, CURLOPT_URL, 'https://api.waviot.ru/driver_electro5/api/report/history');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'modem='.hexdec($modem).'&profile=1.0.94.7.0.255' );
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-type: application/json',
    'user-agent: SNT-Dvurech`e',
		'x-requested-with: XMLHttpRequest',
		'authorization: bearer '.$WAVIOT_JWT
		));

		$out = curl_exec($curl);
		echo "<br>DATA OUT<br>  \r\n";
		var_dump($out);
		//echo "<br> \r\n";
		$str = json_decode($out, TRUE);
		echo '<pre>';
		//var_dump($str);
		echo '</pre>';
		echo "<br> \r\n";
		foreach ($str[0]["meterings"] as $meterings) {
			echo '<br>';
			foreach ($meterings as $key => $value) {
				if(isset($profiles_descr[$key])) {
					if ($key == '1.0.0') {
						echo $profiles_descr[$key] . ' : '. date("d.m.Y H:i:s", $value).'<br>';
					} else {
						echo $profiles_descr[$key] . ' : '. $value.'<br>';
					}

				}

			}
		}


	}

	/*$indication_q = 'https://api.waviot.ru/driver_electro5/api/report/history?modem=7E12D9&profile=31.7.0,51.7.0,71.7.0,91.7.0&timefrom=1633921200&timeto=1633953600&key=1e3a109e8a4ffdeb4715bf022a04a3bf';

	echo $indication_q . " \r\n";

	if( $curl = curl_init() ) {
		curl_setopt($curl, CURLOPT_URL, $indication_q);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_COOKIESESSION,true);

		$out = curl_exec($curl);
		echo "OUT<br>  \r\n";
		var_dump($out);
		echo "<br> \r\n";

	}

	//расшифровываем ответ
	$indications = json_decode($out);
	var_dump($indications);*/

	/*

	//Сегодняшняя дата
	$curdate = date("Y-m-d");
	echo 'Сегодня '.$curdate."<br> \r\n";
	//Вчерашняя дата
	$yesterday = date('Y-m-d', strtotime('yesterday'));
	echo 'Вчера '.$yesterday."<br> \r\n";

	//Выбираем всех пользователей у которых есть номер модема
	$result_counters = $db->getAll("SELECT c.* FROM counters c WHERE c.modem_num IS NOT NULL AND c.modem_num NOT LIKE '' AND (SELECT date_end FROM users_contracts WHERE id = c.contract_id) IS NULL");

	//Перебор пользователей
	foreach ($result_counters as $counter) {
		echo 'Счетчик '.$counter['id']."<br> \r\n";
		//Выбираем дату последних показаний пользователя
		$last_date_q = "SELECT date FROM Indications WHERE counter_id = ".$counter['id']." ORDER BY date DESC LIMIT 1";

		$last_date_result = $db->getOne($last_date_q);

		//Если есть показаний
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

				$indication_q = 'https://lk.waviot.ru/api/report/?template=a2f5261236bf3e2aede89cae168d2c2d&period=P1D&from='.$date_q.'&raw=1&modem='.$counter['modem_num'].'&to='.$date_q . '&key=1e3a109e8a4ffdeb4715bf022a04a3bf';

				echo $indication_q . " \r\n";

				if( $curl = curl_init() ) {
					curl_setopt($curl, CURLOPT_URL, $indication_q);
					curl_setopt($curl, CURLOPT_HEADER, 0);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
					curl_setopt($curl, CURLOPT_COOKIESESSION,true);

					$out = curl_exec($curl);
					echo "OUT  \r\n";
					var_dump($out);
					echo "\r\n";

				}

				//расшифровываем ответ
				$indications = json_decode($out);
				var_dump($indications);

				if (isset($indications->result) && $indications->result == 'error') {
					die('ERROR: '.$indications->message." \r\n");
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
								$ind_diff = $value_ind - $prev_ind;

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
								$db->query($insert_q);

								$price = $db->getOne("SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'");

								$amount = $ind_diff * $price;

								echo '<b>Обновляем баланс</b>'."<br> \r\n";
								$core->changeBalance($counter['user_id'], 1, 5, $amount);

							}
						}
					}
					else {echo 'Нет тарифа в базе'."<br> \r\n";}
				}

				$last_date = date("Y-m-d", strtotime('+1 day', strtotime($last_date)));
				echo '<hr>'."<br> \r\n";
			}
		}
	}
