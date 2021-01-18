<?php

if ($user_data['is_admin']) {
//var_dump($_POST);
  if (isset($_POST) && $_POST) {

    if (isset($form['action']) && $form['action'] == 'disable_counter') {

      if($form['disable_counter_date'])

      $disable_counter_date = $form['disable_counter_date'];

    } else {
      //var_dump($form);
      //var_dump($form['counter_contract']);

      if (!$form['date_start']) $form['date_start'] = NULL;
      if (!$form['date_end']) $form['date_end'] = NULL;

      $update = [
        'num' => $form['num'],
        'date_start' => $form['date_start'],
        'date_end' => $form['date_end']
      ];

      if ($db->query("UPDATE users_contracts SET ?u WHERE id = ?i", $update, $form['id'])) {
        $swal_message["type"] = 'success';
        $swal_message["text"] = 'Договор сохранен';
      }
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
