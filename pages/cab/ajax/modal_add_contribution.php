<?php

include('connect.php');

if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  if ($is_admin) {
    $json['html'] = '';

    if ($_GET['type'] == 'target') {
      $type = 'целевого';
      $type_id = 3;
    } else if ( 'membership') {
      $type = 'членского';
      $type_id = 2;
    }

    $json['header'] = 'Добавдение '.$type.' взноса';
    $json['html'] .= '<form id="add_contribution">';

    $json['html'] .= '<input type="hidden" name="contribution_type" value="'.$type_id.'"/>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="contribution_user">Пользователь:</label>';
    $json['html'] .= '<select name="contribution_user" class="form-control forcheck" id="contribution_user" aria-describedby="contribution_userHelp">';
    $json['html'] .= '<option value="0" selected disabled>Выберите пользователя</option>';
    $users = $db->getAll("SELECT * FROM users WHERE is_del = 0 ORDER BY uchastok");
    foreach ($users as $user) {
      $json['html'] .= '<option value="'.$user['id'].'">уч. - '.$user['uchastok'].' '.$user['name'].'</option>';
    }
    $json['html'] .= '</select>';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="contribution_amount">Сумма:</label>';
    $json['html'] .= '<input type="number" name="contribution_amount" class="form-control forcheck" id="contribution_amount" aria-describedby="numbertHelp">';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="contribution_comment">Коментарий:</label>';
    $json['html'] .= '<input type="text" name="contribution_comment" class="form-control forcheck" id="contribution_comment" aria-describedby="plombtHelp">';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="text-right">';
    $json['html'] .= '<a href="#" class="btn btn-primary" onClick="addContribution(); return false;">Сохранить</a>';
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
