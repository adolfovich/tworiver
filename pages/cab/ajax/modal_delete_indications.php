<?php

include('connect.php');

if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  if ($is_admin) {
    $json['html'] = '';

    $json['header'] = '<span class="text-danger"><b>ВНИМАНИЕ!!!</b><br>Удаление показаний произойдет от даты начала до текущей даты</span>';
    $json['html'] .= '<form>';

    //$json['html'] .= ;
    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="electricAmount">Дата начала удаления:</label>';
    $json['html'] .= '<input type="date" name="date_from" class="form-control" id="date_from" aria-describedby="amountHelp">';
    $json['html'] .= '<small id="amountHelp" class="form-text text-muted"></small>';
    $json['html'] .= '</div>';
    $json['html'] .= '<div class="text-right">';
    $json['html'] .= '<a href="#" class="btn btn-danger" onClick="deleteIndications('.$get['counter'].'); return false;" >Удалить</a>';
    $json['html'] .= '<button class="btn btn-outline-secondary ml-2" data-dismiss="modal" aria-label="Close">Закрыть</button>';
    $json['html'] .= '</div>';
    $json['html'] .= '</form>';
  } else {
    $json['header'] = 'Ошибка';
    $json['html'] .= '<form>';
    $json['html'] .= '<h4>Доступ запрещен</h4>';
    $json['html'] .= '<div class="text-right">';
    $json['html'] .= '<button class="btn btn-outline-secondary ml-2" data-dismiss="modal" aria-label="Close">Закрыть</button>';
    $json['html'] .= '</div>';
    $json['html'] .= '</form>';
  }

  echo json_encode($json);
}
