<?php


if (isset($form['setting_email']) && $form['setting_email'] != $user_data['email']) {
  if (!filter_var($form['setting_email'], FILTER_VALIDATE_EMAIL)) {
    $swal_message["type"] = 'error';
    $swal_message["text"] = 'Неверный адрес электронной почты';
  } else {
    $db->query("UPDATE users SET email = ?s WHERE id = ?i", $form['setting_email'], $user_data['id']);
    $email_saved = TRUE;
  }
}

if (isset($form['setting_new_pass']) && $form['setting_new_pass'] != '') {
  if (strlen($form['setting_new_pass']) < 8) {
    $swal_message["type"] = 'error';
    $swal_message["text"] = 'Пароль должен быть 8 символов или более';
  } else {
    if ($form['setting_new_pass'] != $form['setting_new_pass2']) {
      $swal_message["type"] = 'error';
      $swal_message["text"] = 'Введенные пароли не совпадают';
    } else {
      $new_pass = md5($form['setting_new_pass']);
      $db->query("UPDATE users SET pass = ?s WHERE id = ?i", $new_pass, $user_data['id']);
      $pass_saved = TRUE;
      unset($form['setting_new_pass']);
      unset($form['setting_new_pass2']);
    }
  }
}

if ((isset($email_saved) || isset($pass_saved)) && !isset($swal_message)) {
  $swal_message["type"] = 'success';
  $swal_message["text"] = 'Настройки сохранены';
}

$user_email = $db->getOne("SELECT email FROM users WHERE id = ?i", $user_data['id']);


include('tpl/cab/settings.tpl');
