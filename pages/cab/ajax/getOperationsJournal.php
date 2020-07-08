<?php

include('connect.php');
//var_dump($_POST);
if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);
  $html = '';
  $json = [];

  if ($is_admin) {

    if ($form['number'] == "" && $form['start_date'] == "" && $form['end_date'] == "" && !isset($form['optype'])) {
      $json['status'] = 'error';
      $json['text'] = 'Не выбрано ни одного параметра';
    } else {
      if ($form['number'] != '') {
        $user = $db->getRow("SELECT * FROM users WHERE uchastok LIKE ?s", $form['number']);
        if ($user) {
          $user_q = " user_id = ".$user['id']."";
        } else {
          $user_q = '';
        }
      } else {
        $user_q = '';
      }

      if (isset($form['optype']) && $form['optype'] != 0) {
        $optype_q = ' op_type = '.$form['optype'];
      } else {
        $optype_q = '';
      }

      if ($form['start_date'] || $form['end_date']) {
        if ($form['start_date'] && $form['end_date']) {
          $date_q = $db->parse(" date BETWEEN ?s AND ?s", $form['start_date'], $form['end_date']);
          //$date_q = " date BETWEEN '".$form['start_date']."' AND '".$form['end_date']."'";
        } else if ($form['start_date'] && !$form['end_date']) {
          $date_q = $db->parse("date >= ?s", $form['start_date']);
          //$date_q = " date >= '".$form['start_date']."'";
        } else if (!$form['start_date'] && $form['end_date']) {
          $date_q = $db->parse("date <= ?s", $form['end_date']);
        } else {
          $date_q = '';
        }
      } else {
        $date_q = '';
      }

      if ($user_q || $optype_q || $date_q) {
        $where = "WHERE";
      } else {
        $where = "";
      }

      if ($user_q && $optype_q) {
        $and = ' AND ';
      } else {
        $and = '';
      }

      if ($user_q || $optype_q) {
        $andDate = ' AND ';
      } else {
        $andDate = '';
      }

      if (!isset($date_q) || $date_q == '') {
        $andDate = '';
      }

      $q = $db->getAll("SELECT * FROM operations_jornal ".$where." ".$user_q . $and . $optype_q . $andDate . "?p", $date_q);

      $json['status'] = 'success';

      $balances_names = [
        1 => "Электричество",
        2 => "Членские взносы",
        3 => "Целевые взносы"
      ];

      foreach ($q as $row) {
        $html .= '<tr>';
        $html .= '<td>'.$row['id'].'</td>';
        $html .= '<td>'.date("d.m.Y H:i", strtotime($row['date'])).'</td>';
        $html .= '<td>'.$db->getOne("SELECT uchastok FROM users WHERE id = ?i", $row['user_id']).'</td>';
        $html .= '<td>'.$balances_names[$row['balance_type']].'</td>';
        $html .= '<td>'.$db->getOne("SELECT name FROM operations_jornal_types WHERE id = ?i", $row['op_type']).'</td>';
        $html .= '<td>'.$row['amount'].'</td>';
        $html .= '<td>'.$row['comment'].'</td>';
        $html .= '';
        $html .= '</tr>';
      }
      $json['html'] = $html;
    }

  } else {
    $json['status'] = 'error';
    $json['text'] = 'Ошибка доступа';
  }

  echo json_encode($json);
}
