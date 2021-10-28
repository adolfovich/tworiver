<?php

include('connect.php');
//var_dump($_POST);
if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);
  $html = '';
  $json = [];

  if ($is_admin) {

    if ($form['number'] == "" && $form['start_date'] == "" && $form['end_date'] == "" && !isset($form['optype']) && $form['comment'] == "") {
      $json['status'] = 'error';
      $json['text'] = 'Не выбрано ни одного параметра';
    } else {
      if ($form['number'] != '') {
        $user = $db->getRow("SELECT * FROM users WHERE uchastok LIKE ?s AND is_del = 0", $form['number']);
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

      if (isset($form['balancetype']) && $form['balancetype'] != 0) {
        $balancetype_q = ' 	balance_type = '.$form['balancetype'];
      } else {
        $balancetype_q = '';
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

      if ($form['comment']) {
        $comment_q = "  comment LIKE '%".$form['comment']."%'" ;
      }

      if ($user_q || $optype_q || $balancetype_q || $date_q || $comment_q) {
        $where = "WHERE";
      } else {
        $where = "";
      }

      if ($user_q && $optype_q) {
        $and = ' AND ';
      } else {
        $and = '';
      }

      if ($optype_q && $balancetype_q) {
        $andBalance = ' AND ';
      } else {
        $andBalance = '';
      }

      if ($user_q || $optype_q) {
        $andDate = ' AND ';
      } else {
        $andDate = '';
      }

      if (!isset($date_q) || $date_q == '') {
        $andDate = '';
      }

      if (isset($comment_q)) {

        $andComment = $and.$comment_q;
      }

      $q = $db->getAll("SELECT * FROM operations_jornal ".$where." ".$user_q . $and . $optype_q . $andBalance . $balancetype_q . $andComment . $andDate . "?p ORDER BY date DESC", $date_q);

      $json['status'] = 'success';

      $balances_names = [
        1 => "Электричество",
        2 => "Членские взносы",
        3 => "Целевые взносы"
      ];

      if (!count($q)) {
        $html .= '<tr><td colspan="7" style="padding: 10px; text-align: center; padding-top: 50px; padding-bottom: 50px;">Ничего не найдено</td></tr>';
      } else {
        foreach ($q as $row) {
          $html .= '<tr>';
          $html .= '<td>'.$row['id'].'</td>';
          $html .= '<td>'.date("d.m.Y H:i", strtotime($row['date'])).'</td>';
          $html .= '<td style="text-align:center;">'.$db->getOne("SELECT uchastok FROM users WHERE id = ?i", $row['user_id']).'</td>';
          $html .= '<td>'.$balances_names[$row['balance_type']].'</td>';
          $html .= '<td>'.$db->getOne("SELECT name FROM operations_jornal_types WHERE id = ?i", $row['op_type']).'</td>';
          $html .= '<td>'.$row['amount'].'</td>';
          $html .= '<td>'.$row['comment'].'</td>';
          $html .= '';
          $html .= '</tr>';
        }
      }

      $json['html'] = $html;
    }

  } else {
    $json['status'] = 'error';
    $json['text'] = 'Ошибка доступа';
  }

  echo json_encode($json);
}
