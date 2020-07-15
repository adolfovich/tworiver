<?php

if ($user_data['is_admin']) {
  $sms_checked = '';

  if (isset($_GET['action']) && $_GET['action'] == 'save_user') {
    //var_dump($form);
    if ($form['pass'] == '') {
      unset($form['pass']);
    } else {
      $form['pass'] = md5($form['pass']);
    }
    if ($db->query("UPDATE users SET ?u WHERE id = ?i", $form, $_GET['id'])) {
      $swal_message = [
        'text' => 'Данные сохранены',
        'type' => 'success'
      ];
    } else {
      $swal_message = [
        'text' => 'Ошибка сохранения. Перезагрузите страницу',
        'type' => 'error'
      ];
    }
  }

  $curr_user_data = $db->getRow("SELECT * FROM users WHERE id = ?i", $_GET['id']);

  $curr_user_contracts = $db->getAll("SELECT * FROM `users_contracts` WHERE user = ?i", $curr_user_data['id']);

  $curr_user_acts = $db->getAll("SELECT * FROM `acts` WHERE user = ?i", $curr_user_data['id']);

  $curr_electric_balance = $db->getOne("SELECT balance FROM purses WHERE user_id = ?i AND type = 1", $curr_user_data['id']);
  $curr_membership_balance = $db->getOne("SELECT balance FROM purses WHERE user_id = ?i AND type = 2", $curr_user_data['id']);
  $curr_target_balance = $db->getOne("SELECT balance FROM purses WHERE user_id = ?i AND type = 3", $curr_user_data['id']);

  $curr_count_balance = $curr_electric_balance + $curr_membership_balance + $curr_target_balance;

  include('tpl/cab/admin_user.tpl');
} else {
  include('tpl/cab/403.tpl');
}
