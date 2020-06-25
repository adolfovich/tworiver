<?php

include('connect.php');

//var_dump($_GET);

if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  if ($is_admin) {
    $json['html'] = '';

    $json['header'] = 'Вывод из эксплуатации счетчика ';
    $json['html'] .= '<form enctype="multipart/form-data" method="POST">';

    $json['html'] .= '<a href="#" class="btn btn-primary" onClick="return false;">Распечатать акт сверки</a>';
    $json['html'] .= '<input type="hidden" name="counter_contract" value="'.$_GET['counter'].'">';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="act">Акт сверки:</label>';
    $json['html'] .= '<input type="file" name="act" class="form-control forcheck" id="act" aria-describedby="actHelp">';
    $json['html'] .= '<small id="actHelp" class="form-text text-muted">Загрузить подписаный акт сверки</small>';
    $json['html'] .= '</div>';


    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="end_date">Дата вывода из эксплуатации</label>';
    $json['html'] .= '<input type="date" name="end_date" class="form-control forcheck" id="end_date" aria-describedby="numbertHelp">';
    $json['html'] .= '<small id="numbertHelp" class="form-text text-muted">Не позднее текущей даты</small>';
    $json['html'] .= '</div>';



    $json['html'] .= '<div class="text-right">';
    $json['html'] .= '<button class="btn btn-primary">Применить</button>';
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
