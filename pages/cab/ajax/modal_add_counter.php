<?php

include('connect.php');

if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  if ($is_admin) {
    $json['html'] = '';

    $json['header'] = 'Добавление счетчика';
    $json['html'] .= '<form id="add_counter">';

    //$json['html'] .= ;
    $json['html'] .= '<input type="hidden" name="counter_contract" value="'.$get['contract'].'">';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="counter_model">Модель:</label>';
    $json['html'] .= '<input type="text" name="counter_model" class="form-control forcheck" id="counter_model" aria-describedby="modeltHelp">';
    $json['html'] .= '<small id="modeltHelp" class="form-text text-muted">Название модели счетчика</small>';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="counter_number">Номер:</label>';
    $json['html'] .= '<input type="text" name="counter_number" class="form-control forcheck" id="counter_number" aria-describedby="numbertHelp">';
    $json['html'] .= '<small id="numbertHelp" class="form-text text-muted">Серийный номер счетчика</small>';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="counter_plomb">Номера пломб:</label>';
    $json['html'] .= '<input type="text" name="counter_plomb" class="form-control forcheck" id="counter_plomb" aria-describedby="plombtHelp">';
    $json['html'] .= '<small id="plombtHelp" class="form-text text-muted">Номера пломб разделяются знаком ;</small>';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="counter_modem">Номер модема:</label>';
    $json['html'] .= '<input type="text" name="counter_modem" class="form-control forcheck" id="counter_modem" aria-describedby="modemtHelp">';
    $json['html'] .= '<small id="modemtHelp" class="form-text text-muted">Номер модема счетчика</small>';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="counter_date_start">Дата установки:</label>';
    $json['html'] .= '<input type="date" name="counter_date_start" class="form-control forcheck" id="counter_date_start" aria-describedby="dateStarttHelp">';
    $json['html'] .= '<small id="dateStarttHelp" class="form-text text-muted">Дата установки счетчика, не позднее текущей даты</small>';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="counter_start_indications">Начальные показания:</label>';
    $json['html'] .= '<input type="text" name="counter_start_indications" class="form-control forcheck" id="counter_start_indications" aria-describedby="indicationsStarttHelp">';
    $json['html'] .= '<small id="indicationsStarttHelp" class="form-text text-muted"></small>';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="text-right">';
    $json['html'] .= '<a href="#" class="btn btn-primary" onClick="addCounter(); return false;">Сохранить</a>';
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
