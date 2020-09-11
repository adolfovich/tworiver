<?php

include('connect.php');
//var_dump($_SESSION['id']);
if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  //var_dump($form);

  if ($is_admin) {

    if (!$form['act_date_start'] || strtotime($form['act_date_start']) > strtotime(date("d.m.Y"))) {
      $json['status'] = 'error';
      $json['text'] = 'Не указана дата начала акта или она больше текущей';
      $json['error_input'] = 'act_date_start';
    } else if (!$form['act_date_end'] || strtotime($form['act_date_end']) > strtotime(date("d.m.Y"))) {
      $json['status'] = 'error';
      $json['text'] = 'Не указана дата окончания акта или она больше текущей';
      $json['error_input'] = 'act_date_end';
    } else if (strtotime($form['act_date_end']) < strtotime($form['act_date_start'])) {
      $json['status'] = 'error';
      $json['text'] = 'Дата окончания раньше даты начала';
      $json['error_input'] = 'act_date_end';
    } else {

      $act_link = '/forms/act_reconciliation.php?user='.$form['act_user'].'&datefrom='.$form['act_date_start'].'&dateto='.$form['act_date_end'];

      $json['status'] = 'success';
      $json['text'] = 'Акт создан';
      $json['link'] = $act_link;




    }

  } else {
    $json['status'] = 'error';
    $json['text'] = 'Ошибка доступа';
  }

  echo json_encode($json);
}
