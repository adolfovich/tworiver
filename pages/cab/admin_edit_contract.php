<?php

if ($user_data['is_admin']) {
//var_dump($_POST);
  if (isset($_POST) && $_POST) {
    //var_dump($form);
    //var_dump($form['counter_contract']);

    if ($form['end_date'] != '' && strtotime($form['end_date']) < time() && $_FILES['act']['name'] != '') {

      $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
      $uploaddir = 'uploads/';
      //$uploadfile = $uploaddir . basename($_FILES['act']['name']);
      $file_name = explode('.', $_FILES['act']['name']);
      $uploadfile = $uploaddir . substr(str_shuffle($permitted_chars), 0, 10).'.'.$file_name[1];

      if (move_uploaded_file($_FILES['act']['tmp_name'], $uploadfile)) {

        //$user_id = $db->getOne("SELECT user_id FROM counters WHERE id = ?i", $form['counter_contract'])
        $counter_data = $db->getRow("SELECT * FROM counters WHERE id = ?i", $form['counter_contract']);

        //добавляем в таблицу актов
        $insert = [
          'user' => $counter_data['user_id'],
          'date_start' => '1970-01-01',
          'date_end' => $form['end_date'],
          'comment' => 'Вывод из експлуатации счетчика '.$counter_data['model'].' №'.$counter_data['num'],
          'path' => $uploadfile,
          'type' => 1
        ];

        $db->query("INSERT INTO acts SET ?u", $insert);

        //обновляем счетчик
        $db->query("UPDATE counters SET dismantling_date = ?s WHERE id = ?i", $form['end_date'], $counter_data['id']);

        $swal_message["type"] = 'success';
        $swal_message["text"] = 'Счетчик успешно выведен из эксплуатации';
        //$swal_message["redirect"] = 'admin_edit_contract?id='.$form['counter_contract'];

      } else {
        $swal_message["type"] = 'error';
        $swal_message["text"] = 'Ошибка загрузки файла';
      }

    } else {
      $swal_message["type"] = 'error';
      $swal_message["text"] = 'Файл не загружен или дата не установлена или дата больше текущей';
    }



  }

  $disabled = '';

  $contract = $db->getRow("SELECT * FROM users_contracts WHERE id = ?i", $_GET['id']);

  $contract_user_data = $db->getRow("SELECT * FROM users WHERE id = ?i", $contract['user']);

  if (!$contract['num']) {
    $contract_num = 'Б/Н';
  } else {
    $contract_num = $contract['num'];
  }

  if ($contract['date_start'] == '0000-00-00') {
    $contract_date_start = '--.--.----';
  } else {
    $contract_date_start = date("d.m.Y", strtotime($contract['date_start']));
  }

  if ($contract['date_end']) {
    $disabled = 'disabled';
  }

  $counters = $db->getAll("SELECT * FROM counters WHERE contract_id = ?i ORDER BY dismantling_date", $contract['id']);

  include('tpl/cab/admin_edit_contract.tpl');
} else {
  include('tpl/cab/403.tpl');
}
