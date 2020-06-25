<?php

include('connect.php');
//var_dump($_SESSION['id']);
if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  if ($is_admin) {

    $indications = $db->getAll("SELECT * FROM Indications WHERE counter_id = ?i AND date >= ?s", $form['counter'], $form['date_from']);

    foreach ($indications as $indication) {
      //изменяем баланс
      $sql = $db->parse("UPDATE purses SET balance = balance + ?s WHERE type = 1 AND user_id = ?i", $indication['additional_sum'], $indication['user']);
      $db->query($sql);

      //удаляем показания
      $sql = $db->parse("DELETE FROM Indications WHERE id = ?i", $indication['id']);
      $db->query($sql);
    }

    $json['status'] = 'success';
    $json['text'] = 'Показания удалены. Баланс обновлен';

  } else {
    $json['status'] = 'error';
    $json['text'] = 'Ошибка доступа';
  }

  echo json_encode($json);
}
