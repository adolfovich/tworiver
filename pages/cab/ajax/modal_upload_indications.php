<?php
include('connect.php');

if (isset($_SESSION['id'])) {

  $json = [];
  $json['html'] = '';

  $json['header'] = 'Загрузка показаний';
  $json['html'] .= '<form id="act_params">';
  $json['html'] .= '<div class="form-group">';
  $json['html'] .= '<label for="indDateFrom">Дата начала:</label>';
  $json['html'] .= '<input type="date" name="indDateFrom" class="form-control" id="indDateFrom" >';
  $json['html'] .= '</div>';
  $json['html'] .= '<div class="form-group">';
  $json['html'] .= '<label for="indDateTo">Дата окончания:</label>';
  $json['html'] .= '<input type="date" name="indDateTo" class="form-control" id="indDateTo" >';
  $json['html'] .= '</div>';
  //$json['html'] .= '<input type="hidden" id="actCounter" name="actCounter" value="'.$_GET['counter'].'">';
  $json['html'] .= '<div class="form-group">';
  $json['html'] .= '<label for="indFile">CSV файл</label>';
  $json['html'] .= '<input type="file" name="indFile" class="form-control" id="indFile" >';
  $json['html'] .= '</div>';



  $json['html'] .= '<div class="text-right">';
  $json['html'] .= '<a href="#" class="btn btn-success" onClick="loadIndications(); return false;">Загрузить</a>';
  $json['html'] .= '<button class="btn btn-outline-secondary ml-2" data-dismiss="modal" aria-label="Close">Закрыть</button>';
  $json['html'] .= '</div>';
  $json['html'] .= '</form>';


  $json['html'] .= '<script>

  </script>';

  echo json_encode($json);
}
