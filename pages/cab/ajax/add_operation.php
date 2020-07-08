<?php

include('connect.php');
//var_dump($_SESSION['id']);
if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  //var_dump($form);

  $form['operation_amount'] = str_replace(",", ".", $form['operation_amount']);

  if ($is_admin) {
    if (!isset($form['balance_type']) || $form['balance_type'] == 0) {
      $json['status'] = 'error';
      $json['text'] = 'Не указан тип баланса';
      $json['error_input'] = 'balance_type';
    } else if (!isset($form['operation_type']) || $form['operation_type'] == 0) {
      $json['status'] = 'error';
      $json['text'] = 'Не указан тип операции';
      $json['error_input'] = 'operation_type';
    } else if (!isset($form['area_number']) || $form['area_number'] == '') {
      $json['status'] = 'error';
      $json['text'] = 'Номер участка не может быть пустым';
      $json['error_input'] = 'area_number';
    } else if (!$user = $db->getRow("SELECT * FROM users WHERE uchastok LIKE ?s", $form['area_number'])) {
      $json['status'] = 'error';
      $json['text'] = 'Номер участка не найден';
      $json['error_input'] = 'area_number';
    } else if (!isset($form['operation_date']) || $form['operation_date'] == '') {
      $json['status'] = 'error';
      $json['text'] = 'Не указана дата операции';
      $json['error_input'] = 'operation_date';
    } else if (strtotime($form['operation_date']) >= time()) {
      $json['status'] = 'error';
      $json['text'] = 'Дата не может быть больше текущей';
      $json['error_input'] = 'operation_date';
    } else if (!isset($form['operation_amount']) || $form['operation_amount'] == '' || $form['operation_amount'] == 0) {
      $json['status'] = 'error';
      $json['text'] = 'Сумма операции не может быть 0';
      $json['error_input'] = 'operation_amount';
    } else {
      //var_dump($form);
      $core->changeBalance($user['id'], $form['balance_type'], $form['operation_type'], $form['operation_amount'], $form['operation_comment']);
      $json['status'] = 'success';
      $json['text'] = 'Операция добавлена';
    }


  } else {
    $json['status'] = 'error';
    $json['text'] = 'Ошибка доступа';
  }

  echo json_encode($json);
}
