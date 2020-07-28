<?php

include('connect.php');

if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  if ($is_admin) {
    $json['html'] = '';

    $json['header'] = 'Загрузка акта сверки';
    $json['html'] .= '<form id="upload_act" enctype="multipart/form-data">';
    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="uploadActDateFrom">Дата начала периода:</label>';
    $json['html'] .= '<input type="date" name="uploadActDateFrom" class="form-control forcheck" id="uploadActDateFrom" >';
    $json['html'] .= '</div>';
    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="uploadActDateTo">Дата окончания периода:</label>';
    $json['html'] .= '<input type="date" name="uploadActDateTo" class="form-control forcheck" id="uploadActDateTo" >';
    $json['html'] .= '</div>';


    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="uploadActType">Тип акта:</label>';
    $json['html'] .= '<select name="uploadActType" class="form-control forcheck" id="uploadActType">';
    $json['html'] .= '<option value="0" disabled selected>Выберите тип акта</option>';
    $json['html'] .= '<option value="1">Электроэнергия</option>';
    $json['html'] .= '<option value="2">Членские взносы</option>';
    $json['html'] .= '<option value="3">Целевые взносы</option>';
    $json['html'] .= '</select>';
    $json['html'] .= '</div>';


    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="uploadActFile">Скан акта:</label>';
    $json['html'] .= '<input type="file" name="uploadActFile" class="form-control forcheck" id="uploadActFile" >';
    $json['html'] .= '</div>';

    $json['html'] .= '<input type="hidden" name="uploadActUser" id="uploadActUser" class="form-control" value="'.$_GET['user_id'].'">';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="uploadActComment">Комментарий:</label>';
    $json['html'] .= '<input type="text" name="uploadActComment" class="form-control forcheck" id="uploadActComment" >';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="text-right">';
    $json['html'] .= '<a href="#" class="btn btn-success" onClick="uploadAct(); return false;">Загрузить</a>';
    $json['html'] .= '<button class="btn btn-outline-secondary ml-2" data-dismiss="modal" aria-label="Close">Закрыть</button>';
    $json['html'] .= '</div>';
    $json['html'] .= '</form>';


    $json['html'] .= '<script>

    </script>';
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
