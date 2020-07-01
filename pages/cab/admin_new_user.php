<?php

if ($user_data['is_admin']) {
  $sms_checked = '';

  if (isset($_POST['saveNewUser'])) {
    if ($_POST['name'] != '') {
      if ($_POST['email'] != '') {
        if ($_POST['phone']) {
          if ($_POST['uchastok'] != '') {
            if ($_POST['pass'] != '') {
              if ($_POST['pass'] == $_POST['repass']) {

                $pass = md5($_POST['pass']);

                if ($_POST['send_monthly_sms']) {
                  $sms_notice = 1;
                } else {
                  $sms_notice = 1;
                }

                $insert = [
                  'name' => $_POST['name'],
                  'email' => $_POST['email'],
                  'pass' => $pass,
                  'phone' => $_POST['phone'],
                  'uchastok' => $_POST['uchastok'],
                  'email_notice' => 0,
                  'send_monthly_sms' => $sms_notice
                ];

                //добавляем пользователя
                $db->query("INSERT INTO users SET ?u", $insert);

                $new_user_id = $db->insertId();

                //добавляем счета
                $db->query("INSERT INTO purses SET user_id = ?i, type = 1", $new_user_id);
                $db->query("INSERT INTO purses SET user_id = ?i, type = 2", $new_user_id);
                $db->query("INSERT INTO purses SET user_id = ?i, type = 3", $new_user_id);

                //header("Location: admin_user?id=".$new_user_id);

                $core->jsredir('admin_user?id='.$new_user_id);


              } else {
                $swal_message["type"] = 'error';
                $swal_message["text"] = 'Пароли не совпадают';
              }
            } else {
              $swal_message["type"] = 'error';
              $swal_message["text"] = 'Не заполнено поле Пароль';
            }
          } else {
            $swal_message["type"] = 'error';
            $swal_message["text"] = 'Не заполнено поле Номер участка';
          }
        } else {
          $swal_message["type"] = 'error';
          $swal_message["text"] = 'Не заполнено поле Телефон';
        }
      } else {
        $swal_message["type"] = 'error';
        $swal_message["text"] = 'Не заполнено поле Email';
      }
    } else {
      $swal_message["type"] = 'error';
      $swal_message["text"] = 'Не заполнено поле ФИО';
    }
  }

  include('tpl/cab/admin_new_user.tpl');
} else {
  include('tpl/cab/403.tpl');
}
