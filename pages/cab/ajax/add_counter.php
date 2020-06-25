<?php

include('connect.php');
//var_dump($_SESSION['id']);
if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  //var_dump($form);

  if ($is_admin) {

    if (!$form['counter_model'] || strlen($form['counter_model']) < 5) {
      $json['status'] = 'error';
      $json['text'] = 'Модель счетчика должна содержать минимум 5 знаков';
      $json['error_input'] = 'counter_model';
    } else if (!$form['counter_number'] || strlen($form['counter_number']) < 5) {
      $json['status'] = 'error';
      $json['text'] = 'Номер счетчика должна содержать минимум 5 знаков';
      $json['error_input'] = 'counter_number';
    } else if (!$form['counter_date_start'] || strtotime($form['counter_date_start']) > time()) {
      $json['status'] = 'error';
      $json['text'] = 'Дата установки не указана или больше текущей даты';
      $json['error_input'] = 'counter_date_start';
    } else {

      $user_id = $db->getOne("SELECT user FROM users_contracts WHERE id = ?i", $form['counter_contract']);

      $insert = [
        'user_id' => $user_id,
        'contract_id' => $form['counter_contract'],
        'model' => $form['counter_model'],
        'num' => $form['counter_number'],
        'plomb' => $form['counter_plomb'],
        'modem_num' => $form['counter_modem'],
        'install_date' => $form['counter_date_start']
      ];

      if ($db->query("INSERT INTO counters SET ?u", $insert)) {
        $json['status'] = 'success';
        $json['text'] = 'Счетчик добавлен';
        $json['redirect'] = '/cab/admin_edit_contract?id='.$form['counter_contract'];
      } else {
        $json['status'] = 'error';
        $json['text'] = 'Ошибка сохранения, обратитесь в тех. поддержку';
        $json['error_input'] = '';
      }



    }

  } else {
    $json['status'] = 'error';
    $json['text'] = 'Ошибка доступа';
  }

  echo json_encode($json);
}
