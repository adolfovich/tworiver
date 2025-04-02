<?php

// https://xn----dtbffa7byadkn0c6c.xn--p1ai/core/load_ind.php?from=2023-01-22&to=2024-01-31
	
	ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	
	include ('../_conf.php');
	include ('../classes/safemysql.class.php');
	$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

	require_once('../classes/core.class.php');

	$core  = new Core();

	$url = $core->url;
	$form = $core->form;
	$ip = $core->ip;
	$get = $core->setGet();
	
	if (isset($get['from']) && isset($get['to'])) {
		$date_from = strtotime($get['from']);
		$date_to = strtotime($get['to']);
	} else if (isset($_POST['from']) && isset($_POST['to'])) {
		$date_from = strtotime($_POST['from']);
		$date_to = strtotime($_POST['to']);
	} else {
		die('Не указана дата начала или окончания');
	}
	
	if ($date_from > $date_to) {
		die('Дата начала больше чем дата окончания');
	}
	
	$date_from_str = date("d-m-Y", $date_from); 
	$date_to_str = date("d-m-Y", $date_to); 
	
	echo ' Дата начала '.$date_from_str.' дата окончания '. $date_to_str;
	
	echo '<pre>';
	
	$nam="indications.csv";
	$separator=";";
	$fop = fopen($nam , "r");
	
	$i=1;
	$j=0;
	
	while (!feof($fop))
	{
		//echo 'i='.$i.'<br>';
		$read = fgets($fop);
		$read = iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $read);
		if ($i >= 10) {
			$row = explode($separator ,$read);
			//var_dump($row);
			$modem = $row[10];
			$new_ind_t1 = round(str_replace(',','.',$row[20]), 2);
			$new_ind_t2 = round(floatval($row[23]), 2);
			
			if ($modem) {
			
			echo 'модем '.$modem.' \ Т1 '.$new_ind_t1.' \ Т2 '.$new_ind_t2;
			echo '<br>';
			
			
			$counter = $db->getRow("SELECT co.user_id as counter_user_id, co.id as counter_id, co.modem_num as counter_modem_num FROM counters co LEFT JOIN users us ON co.user_id = us.id WHERE co.modem_num = ?s AND co.dismantling_date IS NULL AND us.is_del = 0", $modem);
			if ($counter) {
				
				
				//ТАРИФ 1
				$prev_ind_t1 = $db->getOne("SELECT Indications FROM Indications WHERE counter_id = ".$counter['counter_id']." AND tarif = 2 ORDER BY date DESC LIMIT 1");
				if (!$prev_ind_t1) $prev_ind_t1 = 0;
				echo 'показания в базе Т1 '.$prev_ind_t1;
				echo '<br>';
				$diff_t1 = $new_ind_t1 - $prev_ind_t1;
				
				echo '<b>Добавляем показания ТАРИФ 1</b>'."<br> \r\n";

				$insert_q = "
					INSERT INTO Indications SET
						date='".date('Y-m-d', strtotime($date_to_str))."',
						user=".$counter['counter_user_id'].",
						counter_id = ".$counter['counter_id'].",
						tarif = 2,
						Indications='".$new_ind_t1."',
						prev_indications = '".$prev_ind_t1."',
						additional=(SELECT price FROM tarifs WHERE id_waviot = 'electro_ac_p_lsum_t1'),
						additional_sum= $diff_t1*(SELECT price FROM tarifs WHERE id_waviot = 'electro_ac_p_lsum_t1'),
						auto = 1";

				echo $insert_q . "<br> \r\n";
				$db->query($insert_q);
				
				$price = $db->getOne("SELECT price FROM tarifs WHERE id_waviot = 'electro_ac_p_lsum_t1'");

				$amount = $diff_t1 * $price;

				if ($amount < 0) $amount = 0;
																		
				echo '<b>Обновляем баланс пользователя '.$counter['counter_user_id'].' на сумму '.$amount.'</b>'."<br> \r\n";
				$core->changeBalance($counter['counter_user_id'], 1, 5, $amount);
									
				echo '<b>Обновляем дату последних показаний</b>'."<br> \r\n";									
				$db->query("UPDATE counters SET last_ind_date = ?s WHERE modem_num = ?s", date('Y-m-d', strtotime($date_to_str)), $counter['counter_modem_num']);
									
				//ТАРИФ 2
				$prev_ind_t2 = $db->getOne("SELECT Indications FROM Indications WHERE counter_id = ".$counter['counter_id']." AND tarif = 3 ORDER BY date DESC LIMIT 1");
				if (!$prev_ind_t2) $prev_ind_t2 = 0;
				echo 'показания в базе Т2 '.$prev_ind_t2;
				echo '<br>';
				
				$diff_t2 = $new_ind_t2 - $prev_ind_t2;
				
				echo '<b>Добавляем показания ТАРИФ 2</b>'."<br> \r\n";

				$insert_q = "
					INSERT INTO Indications SET
						date='".date('Y-m-d', strtotime($date_to_str))."',
						user=".$counter['counter_user_id'].",
						counter_id = ".$counter['counter_id'].",
						tarif = 3,
						Indications='".$new_ind_t2."',
						prev_indications = '".$prev_ind_t2."',
						additional=(SELECT price FROM tarifs WHERE id_waviot = 'electro_ac_p_lsum_t2'),
						additional_sum= $diff_t2*(SELECT price FROM tarifs WHERE id_waviot = 'electro_ac_p_lsum_t2'),
						auto = 1";

				echo $insert_q . "<br> \r\n";
				$db->query($insert_q);
				
				$price = $db->getOne("SELECT price FROM tarifs WHERE id_waviot = 'electro_ac_p_lsum_t2'");

				$amount = $diff_t2 * $price;
																		
				echo '<b>Обновляем баланс пользователя '.$counter['counter_user_id'].' на сумму '.$amount.'</b>'."<br> \r\n";
				$core->changeBalance($counter['counter_user_id'], 1, 5, $amount);
									
				echo '<b>Обновляем дату последних показаний</b>'."<br> \r\n";									
				$db->query("UPDATE counters SET last_ind_date = ?s WHERE modem_num = ?s", date('Y-m-d', strtotime($date_to_str)), $counter['counter_modem_num']);
				
				$j++;
				
			} else {
				echo 'модем '.$modem.' в базе НЕ НАЙДЕН! ';
				echo '<br>';
			}
			
			}
			
			echo '<br>';
			
		}
		
		$i++;
	}
	fclose($fop);
echo "Успешно обработано показаний: ".$j;


echo '</pre>';

	
	