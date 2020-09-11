<?php

include('connect.php');

if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  if ($is_admin) {
    $json['html'] = '';

    $json['header'] = 'Печать акта';

      $json['html'] .= '<form id="print_act">';

    if ($get['type'] == 'electric') {
      $json['html'] .= '<input type="hidden" name="act_user" value="'.$get['user_id'].'">';
      $json['html'] .= '<input type="hidden" name="act_type" value="electric">';

      $json['html'] .= '<div class="form-group">';
      $json['html'] .= '<label for="act_date_start">Дата начала:</label>';
      $json['html'] .= '<input type="date" name="act_date_start" class="form-control forcheck" id="act_date_start" aria-describedby="dateStarttHelp" value="2020-06-24">';
      $json['html'] .= '<small id="dateStarttHelp" class="form-text text-muted">Дата начала акта, не ранее текущей даты</small>';
      $json['html'] .= '</div>';

      $json['html'] .= '<div class="form-group">';
      $json['html'] .= '<label for="act_date_end">Дата окончания:</label>';
      $json['html'] .= '<input type="date" name="act_date_end" class="form-control forcheck" id="act_date_end" aria-describedby="dateEndHelp" value="'.date('Y-m-d').'">';
      $json['html'] .= '<small id="dateEndHelp" class="form-text text-muted">Дата окончания акта, не позднее текущей даты</small>';
      $json['html'] .= '</div>';
    }

    $json['html'] .= '<div class="text-right">';
    $json['html'] .= '<a href="#" class="btn btn-primary" onClick="printActModal(); return false;">Распечатать</a>';
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
