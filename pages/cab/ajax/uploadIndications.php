<?php

include('connect.php');

$log = '';
$input_name = 'csvFile';
$allow = array();
$deny = array(
    'csv'
);


// Директория куда будут загружаться файлы.
$path = $_SERVER['DOCUMENT_ROOT'].'/uploads/';

if (!isset($_FILES[$input_name])) {
    $json['status'] = 'ERROR';
    $json['error'] = 'Файл не выбран.';
} else {
    $file = $_FILES[$input_name];

    // Проверим на ошибки загрузки.
    if (!empty($file['error']) || empty($file['tmp_name'])) {
        $json['status'] = 'ERROR';
        $json['error'] = 'Ошибка загрузки файла.('.$file['error'].')';
    } elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
        $json['status'] = 'ERROR';
        $json['error'] = 'Ошибка загрузки файла.';
    } else {

        $name = uniqid().'.csv';

        if ($_FILES["csvFile"]["type"] != 'text/csv') {
            $json['status'] = 'ERROR';
            $json['error'] = 'Недопустимый тип файла.';
        } else {
            // Перемещаем файл в директорию.
            if (move_uploaded_file($file['tmp_name'], $path . $name)) {

                // Далее можно сохранить название файла в БД и т.п.
                $json['status'] = 'OK';
                //$json['msg'] = 'Файл «' . $name . '» успешно загружен...';
                $json['msg'] = 'Файл успешно загружен...';

                //Создание бэкапа
                $backupResult = $core->backupDatabase($db_host, $db_name, $db_user, $db_pass);

                if ($backupResult['status'] != 'success') {
                    $json['status'] = 'ERROR';
                    $json['error'] = 'Ошибка создания бэкапа: '.$backupResult['text'];
                } else {

                    $json['msg'] .= '<br> '.$backupResult['text'];

                    //отключение онлайн оплаты
                    $db->query("UPDATE settings SET data = '1' WHERE cfgname = 'pay_enable'");
                    $json['msg'] .= '<br> Онлайн оплата отключена...';

                    //Проверка дат
                    $date_from = strtotime($_POST['startDate']);
                    $date_to = strtotime($_POST['endDate']);
                    if ($date_from > $date_to) {
                        $json['status'] = 'ERROR';
                        $json['error'] = 'Дата начала больше даты окончания';
                    } else if ($db->getRow('SELECT * FROM indications_log WHERE period = ?s', date('m.Y', $date_to))) {
                        $json['status'] = 'ERROR';
                        $json['error'] = 'За выбраный период '.date('m.Y', $date_to).' в систему уже загружены показания';
                    } else {

                        $date_from_str = date("d-m-Y", $date_from);
                        $date_to_str = date("d-m-Y", $date_to);

                        $nam = $path . $name;
                        $separator=";";
                        $fop = fopen($nam , "r");

                        $i=1;
                        $j=0;

                        while (!feof($fop)) {
                            $read = fgets($fop);
                            $read = iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $read);
                            if ($i >= 10) {
                                $row = explode($separator ,$read);
                                $modem = $row[10];
                                $new_ind_t1 = round(str_replace(',','.',$row[20]), 2);
                                $new_ind_t2 = round(floatval($row[23]), 2);

                                if ($modem) {
                                    $log .= 'модем '.$modem.' \ Т1 '.$new_ind_t1.' \ Т2 '.$new_ind_t2;
                                    $log .= '<br>';

                                    $counter = $db->getRow("SELECT co.user_id as counter_user_id, co.id as counter_id, co.modem_num as counter_modem_num FROM counters co LEFT JOIN users us ON co.user_id = us.id WHERE co.modem_num = ?s AND co.dismantling_date IS NULL AND us.is_del = 0", $modem);
                                    if ($counter) {

                                        //ТАРИФ 1
                                        $prev_ind_t1 = $db->getOne("SELECT Indications FROM Indications WHERE counter_id = ".$counter['counter_id']." AND tarif = 2 ORDER BY date DESC LIMIT 1");
                                        if (!$prev_ind_t1) $prev_ind_t1 = 0;

                                        $log .= 'показания в базе Т1 '.$prev_ind_t1;
                                        $log .= '<br>';

                                        $diff_t1 = $new_ind_t1 - $prev_ind_t1;

                                        $log .= '<b>Добавляем показания ТАРИФ 1</b>'."<br> \r\n";

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

                                        $log .= $insert_q . "<br> \r\n";

                                        $db->query($insert_q);

                                        $price = $db->getOne("SELECT price FROM tarifs WHERE id_waviot = 'electro_ac_p_lsum_t1'");

                                        $amount = $diff_t1 * $price;

                                        if ($amount < 0) $amount = 0;

                                        $log .= '<b>Обновляем баланс пользователя '.$counter['counter_user_id'].' на сумму '.$amount.'</b>'."<br> \r\n";
                                        $core->changeBalance($counter['counter_user_id'], 1, 5, $amount);

                                        $log .= '<b>Обновляем дату последних показаний</b>'."<br> \r\n";
                                        $db->query("UPDATE counters SET last_ind_date = ?s WHERE modem_num = ?s", date('Y-m-d', strtotime($date_to_str)), $counter['counter_modem_num']);

                                        //ТАРИФ 2
                                        $prev_ind_t2 = $db->getOne("SELECT Indications FROM Indications WHERE counter_id = ".$counter['counter_id']." AND tarif = 3 ORDER BY date DESC LIMIT 1");
                                        if (!$prev_ind_t2) $prev_ind_t2 = 0;
                                        $log .= 'показания в базе Т2 '.$prev_ind_t2;
                                        $log .= '<br>';

                                        $diff_t2 = $new_ind_t2 - $prev_ind_t2;

                                        $log .= '<b>Добавляем показания ТАРИФ 2</b>'."<br> \r\n";

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

                                        $log .= $insert_q . "<br> \r\n";
                                        $db->query($insert_q);

                                        $price = $db->getOne("SELECT price FROM tarifs WHERE id_waviot = 'electro_ac_p_lsum_t2'");

                                        $amount = $diff_t2 * $price;

                                        $log .= '<b>Обновляем баланс пользователя '.$counter['counter_user_id'].' на сумму '.$amount.'</b>'."<br> \r\n";
                                        $core->changeBalance($counter['counter_user_id'], 1, 5, $amount);

                                        $log .= '<b>Обновляем дату последних показаний</b>'."<br> \r\n";
                                        $db->query("UPDATE counters SET last_ind_date = ?s WHERE modem_num = ?s", date('Y-m-d', strtotime($date_to_str)), $counter['counter_modem_num']);

                                        $j++;

                                    } else {
                                        $log .= 'модем '.$modem.' в базе НЕ НАЙДЕН! ';
                                        $log .= '<br>';
                                    }
                                }
                                $log .= '<br>';
                            }
                            $i++;
                        }
                        fclose($fop);

                        $json['msg'] .= '<br> Успешно обработано счетчиков: '.$j;
                        $json['msg'] .= '<br> Загрузка завершена';
                        $json['msg'] .= '<br> После проверки не забудьте включить онлайн оплату';

                        $db->query('INSERT INTO indications_log SET log = ?s, period = ?s', $log, date('m.Y', $date_to));
                    }
                }

            } else {
                $json['status'] = 'ERROR';
                $json['error'] = 'Ошибка копирования файла. Проверьте права на директорию '.$path. ' ' . $_FILES["file"]["error"];
            }
        }
    }
}

//var_dump($json);
echo json_encode($json);