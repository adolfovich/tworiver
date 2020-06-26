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

	//Сегодняшняя дата
	$curdate = date("Y-m-d");
	echo 'Сегодня '.$curdate."<br> \r\n";
	//Вчерашняя дата
	$yesterday = date('Y-m-d', strtotime('yesterday'));
	echo 'Вчера '.$yesterday."<br> \r\n";

	//Выбираем всех пользователей у которых есть номер модема
	//$result_users = mysql_query("SELECT * FROM users WHERE modem_num IS NOT NULL AND modem_num NOT LIKE ''") or die(mysql_error());
	$result_users = $db->getAll("SELECT * FROM users WHERE modem_num IS NOT NULL AND modem_num NOT LIKE ''");

	//Перебор пользователей
	foreach ($result_users as $users) {
		echo 'Пользователь '.$users['email']."<br> \r\n";
		//Выбираем дату последних показаний пользователя
		$last_date_q = "SELECT date FROM Indications WHERE user = ".$users['id']." ORDER BY date DESC LIMIT 1";

		$last_date_result = $db->getOne($last_date_q);

		//Если есть показаний
		if ($last_date_result) {
			$last_date = date("Y-m-d", strtotime($last_date_result));

		}
		//Если нет показаний 2017-09-01
		else {
			$last_date = '2017-09-01';
			$last_date = '2018-12-31';
		}
		echo 'Дата последних показаний '.$last_date . "<br> \r\n";

		if ($last_date == $yesterday) {
			echo 'Новых показаний нет'."<br> \r\n";
		}
		else {

			//перебираем даты, пока не не будет сегодняшняя
			while (date("Y-m-d", strtotime('+1 day', strtotime($last_date))) != $curdate) {


				$date_q = date("Y-m-d", strtotime('+2 day', strtotime($last_date)));

				$indication_q = 'https://lk.waviot.ru/api/report/?template=a2f5261236bf3e2aede89cae168d2c2d&period=P1D&from='.$date_q.'&raw=1&modem='.$users['modem_num'].'&to='.$date_q . '&key=1e3a109e8a4ffdeb4715bf022a04a3bf';

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
								$prev_ind = $db->getOne("SELECT Indications FROM Indications WHERE user = ".$users['id']." AND tarif = (SELECT id FROM tarifs WHERE id_waviot = '".$tarif."') ORDER BY date DESC LIMIT 1");
								//$prev_ind = mysql_result($prev_ind_result, 0);
								$ind_diff = $value_ind - $prev_ind;


								echo '<b>Добавляем показания</b>'."<br> \r\n";

								$insert_q = "INSERT INTO Indications SET date='".date('Y-m-d', strtotime($date)-86400)."', user=".$users['id'].", tarif=(SELECT id FROM tarifs WHERE id_waviot = '".$tarif."'), Indications=".$value_ind.",	prev_indications = '".$prev_ind."',	additional=(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'), additional_sum= $ind_diff*(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'), auto = 1";

								echo $insert_q . "<br> \r\n";
								$db->query($insert_q);

								$price = $db->getOne("SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'");

								$amount = $ind_diff * $price;

								echo '<b>Обновляем баланс</b>'."<br> \r\n";
								$core->changeBalance($users['id'], 1, 5, $amount);


								/*$q_upd_balans = "UPDATE users u SET u.balans = (u.balans - ($ind_diff*(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'))), u.total_balance = (u.total_balance - ($ind_diff*(SELECT price FROM tarifs WHERE id_waviot = '".$tarif."'))) WHERE u.id = ".$users['id'];
								echo $q_upd_balans;
								echo '<hr>'."<br> \r\n";
								mysql_query($q_upd_balans) or die(mysql_error());*/

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
