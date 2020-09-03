<?php

include('connect.php');

if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  if ($is_admin) {
    $json['html'] = '';

    $json['header'] = 'Добавление операции';
    $json['html'] .= '<form id="add_operation">';

    //$json['html'] .= ;

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="balance_type">Тип баланса:</label>';
    $json['html'] .= '<select name="balance_type" class="form-control" id="balance_type">';
    $json['html'] .= '<option value="0" disabled selected>Выбрать баланс</option>';
    $json['html'] .= '<option value="1">Электричество</option>';
    $json['html'] .= '<option value="2">Членские взносы</option>';
    $json['html'] .= '<option value="3">целевые взносы</option>';
    $json['html'] .= '</select>';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="operation_type">Тип операции:</label>';
    $json['html'] .= '<select name="operation_type" class="form-control" id="operation_type">';
    if (!$get['operation_type']) {
      $json['html'] .= '<option value="0" disabled selected>Выбрать операцию</option>';
    }

    $operations = $db->getAll("SELECT * FROM operations_jornal_types");
    foreach ($operations as $operation) {
      if ($get['operation_type'] == $operation['id']) {
        $json['html'] .= '<option value="'.$operation['id'].'" selected>'.$operation['name'].'</option>';
      } else {
        $json['html'] .= '<option value="'.$operation['id'].'">'.$operation['name'].'</option>';
      }

    }
    $json['html'] .= '</select>';
    $json['html'] .= '</div>';


    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="area_number">Номер участка:</label>';
    $json['html'] .= '<input type="text" name="area_number" class="form-control forcheck" id="area_number" aria-describedby="numbertHelp" value="'.$get['area_number'].'">';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="operation_date">Дата операции:</label>';
    $json['html'] .= '<input type="date" name="operation_date" class="form-control forcheck" id="operation_date" aria-describedby="plombtHelp">';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="operation_amount">Сумма операции:</label>';
    $json['html'] .= '<input type="number" name="operation_amount" class="form-control forcheck" id="operation_amount" aria-describedby="modemtHelp">';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="operation_comment">Комментарий:</label>';
    $json['html'] .= '<input type="text" name="operation_comment" class="form-control forcheck" id="operation_comment" aria-describedby="modemtHelp">';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="text-right">';
    $json['html'] .= '<a href="#" class="btn btn-primary" onClick="addOperation(); return false;">Сохранить</a>';
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
