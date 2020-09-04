<?php

include('connect.php');

if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  if ($is_admin) {

    $rate = $db->getRow("SELECT * FROM tarifs WHERE id = ?i", $get['rate']);

    $json['html'] = '';


    $json['header'] = 'Изменение тарифа "'.$rate['name'].'" ';
    $json['html'] .= '<form id="change_rate">';

    $json['html'] .= '<input type="hidden" name="rate_id" value="'.$rate['id'].'"/>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="rate_name">Название:</label>';
    $json['html'] .= '<input type="text" name="rate_name" class="form-control forcheck" id="rate_name" value="'.$rate['name'].'"/>';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="rate_waviot_id">waviot_id:</label>';
    $json['html'] .= '<input type="text" name="rate_waviot_id" class="form-control forcheck" id="rate_waviot_id" value="'.$rate['id_waviot'].'"/>';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="rate_price">Стоимость кВт⋅ч:</label>';
    $json['html'] .= '<input type="text" name="rate_price" class="form-control forcheck" id="rate_price" value="'.number_format($rate['price'], 2, '.', '').'"/>';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="text-right">';
    $json['html'] .= '<a href="#" class="btn btn-primary" onClick="changeRate(); return false;">Сохранить</a>';
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
