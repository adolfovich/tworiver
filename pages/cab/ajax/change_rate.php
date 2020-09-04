<?php

include('connect.php');

if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  if ($is_admin) {

    if (!$form['rate_name'] || strlen($form['rate_name']) < 5) {
      $json['status'] = 'error';
      $json['text'] = 'Название тарифа должно содержать минимум 5 знаков';
      $json['error_input'] = 'rate_name';
    } else if (!$form['rate_waviot_id'] || $form['rate_waviot_id'] == '') {
      $json['status'] = 'error';
      $json['text'] = 'waviot_id не может быть пустым';
      $json['error_input'] = 'rate_waviot_id';
    } else {

      $update = [
        'name' => $form['rate_name'],
        'id_waviot' => $form['rate_waviot_id'],
        'price' => $form['rate_price']
      ];

      if ($db->query("UPDATE tarifs SET ?u WHERE id = ?i", $update, $form['rate_id'])) {
        $json['status'] = 'success';
        $json['text'] = 'Тариф сохранен';
        $json['redirect'] = '/cab/admin_rates';
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
