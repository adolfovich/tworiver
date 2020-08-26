<?php

include('connect.php');

if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  //var_dump($form['uploadActDateTo']);
  //var_dump($_FILES);

  if ($is_admin) {
    if (strtotime($form['uploadActDateFrom']) > time()) {
      $json['status'] = 'error';
      $json['text'] = 'Дата начала периода больше текущей даты ';
      $json['error_input'] = 'uploadActDateFrom';
    } else if ($form['uploadActDateTo'] == '') {
      $json['status'] = 'error';
      $json['text'] = 'Не выбрана дата окончания периода';
      $json['error_input'] = 'uploadActDateTo';
    } else if (strtotime($form['uploadActDateTo']) > time()) {
      $json['status'] = 'error';
      $json['text'] = 'Дата окончания периода больше текущей даты ';
      $json['error_input'] = 'uploadActDateTo';
    } else if (strtotime($form['uploadActDateFrom']) > strtotime($form['uploadActDateTo'])) {
      $json['status'] = 'error';
      $json['text'] = 'Дата начала периода больше даты окончания';
      $json['error_input'] = 'uploadActDateFrom';
    } else if (!isset($form['uploadActType']) || $form['uploadActType'] == 0) { //uploadActType
      $json['status'] = 'error';
      $json['text'] = 'Не выбран тип акта';
      $json['error_input'] = 'uploadActType';
    } else if (!count($_FILES)) {
      $json['status'] = 'error';
      $json['text'] = 'Не прикреплен скан акта';
      $json['error_input'] = 'uploadActType';
    } else {
      $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
      $file_name = explode('.', $_FILES[0]['name']);
      $rand_name = substr(str_shuffle($permitted_chars), 0, 10);

      //var_dump($_SERVER['DOCUMENT_ROOT']);

      $uploaddir = $_SERVER['DOCUMENT_ROOT']."/uploads/";
      $uploadfile = $uploaddir . $rand_name.'.'.$file_name[1];
      if (move_uploaded_file($_FILES[0]['tmp_name'], $uploadfile)) {
          //echo "Файл корректен и был успешно загружен.\n";
          if ($form['uploadActDateFrom'] == '') $form['uploadActDateFrom'] = '1970-01-01';
          if (!isset($form['uploadActVisible'])) $form['uploadActVisible'] = 0;
          $insert = [
            'user' => $form['uploadActUser'],
            'date_start' => $form['uploadActDateFrom'],
            'date_end' => $form['uploadActDateTo'],
            'comment' => $form['uploadActComment'],
            'path' => 'uploads/'.$rand_name.'.'.$file_name[1],
            'type' => $form['uploadActType'],
            'visible' => $form['uploadActVisible']
          ];
          $db->query("INSERT INTO acts SET ?u", $insert);

          $json['status'] = 'success';
          $json['text'] = 'Акт загружен';
          $json['redirect'] = '/cab/admin_user?id='.$form['uploadActUser'];
      } else {
        $json['status'] = 'error';
        $json['text'] = 'Ошибка загрузки файла на сервер';
        $json['error_input'] = 'uploadActType';
      }
      //var_dump($uploadfile);
    }


  } else {
    $json['status'] = 'error';
    $json['text'] = 'Ошибка доступа';
  }

  echo json_encode($json);
}
