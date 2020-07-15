<?php

include('connect.php');
//var_dump($_SESSION['id']);
if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  //var_dump($form);

  if ($is_admin) {

    if (!isset($form['contribution_user']) || !$form['contribution_user'] || $form['contribution_user'] == 0) {
      $json['status'] = 'error';
      $json['text'] = 'Не выбран пользователь';
      $json['error_input'] = 'contribution_user';
    } else if (!$form['contribution_amount'] || $form['contribution_amount'] <= 0) {
      $json['status'] = 'error';
      $json['text'] = 'Сумма взноса должна быть больше нуля';
      $json['error_input'] = 'contribution_amount';
    } else {

      $contribution_user = $db->getRow("SELECT * FROM users WHERE id = ?i", $form['contribution_user']);

      //начисление взноса

      $amount = str_replace(',', '.', $form['contribution_amount']);

      $core->changeBalance($contribution_user['id'], $form['contribution_type'], $form['contribution_type'], $amount, $form['contribution_comment']);


      $json['status'] = 'success';
      $json['text'] = 'Взнос добавлен';
      $json['redirect'] = '/cab/admin_contributions';

      /*$insert = [
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
      }*/



    }

  } else {
    $json['status'] = 'error';
    $json['text'] = 'Ошибка доступа';
  }

  echo json_encode($json);
}
