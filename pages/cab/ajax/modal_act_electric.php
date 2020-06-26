<?php
include('connect.php');

if (isset($_SESSION['id'])) {

  $json = [];
  $json['html'] = '';

  $json['header'] = 'Акт сверки электроэнергии';
  $json['html'] .= '<form id="act_params">';
  $json['html'] .= '<div class="form-group">';
  $json['html'] .= '<label for="actDateFrom">Дата начала:</label>';
  $json['html'] .= '<input type="date" name="actDateFrom" class="form-control" id="actDateFrom" >';
  $json['html'] .= '</div>';
  $json['html'] .= '<div class="form-group">';
  $json['html'] .= '<label for="actDateTo">Дата окончания:</label>';
  $json['html'] .= '<input type="date" name="actDateTo" class="form-control" id="actDateTo" >';
  $json['html'] .= '</div>';
  $json['html'] .= '<input type="hidden" id="actCounter" name="actCounter" value="'.$_GET['counter'].'">';



  $json['html'] .= '<div class="text-right">';
  $json['html'] .= '<a href="#" class="btn btn-success" onClick="printAct(\'electric\'); return false;">Распечатать</a>';
  $json['html'] .= '<button class="btn btn-outline-secondary ml-2" data-dismiss="modal" aria-label="Close">Закрыть</button>';
  $json['html'] .= '</div>';
  $json['html'] .= '</form>';


  $json['html'] .= '<script>

  </script>';

  echo json_encode($json);
}
