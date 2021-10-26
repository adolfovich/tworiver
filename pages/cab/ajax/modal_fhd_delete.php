<?php
include('connect.php');

if (isset($_SESSION['id'])) {

  $json = [];
  $json['html'] = '';

  $json['header'] = 'Подтвердите удаление';
  $json['html'] .= '<form id="fhd_delete" method="POST">';
  $json['html'] .= '<input type="hidden" name="fhd_delete_id" value="'.$_GET['id'].'" />';
  //$json['html'] .= '<input type="hidden" name="fhd_delete_id" value="'.$_GET['id'].'" />';

  $fhd_data = $db->getRow("SELECT * FROM reports_fhd WHERE id = ?i", $_GET['id']);

  $json['html'] .= '<div class="form-group">';
  $json['html'] .= '<label for="actDateTo">'.$fhd_data['name'].'</label>';
  $json['html'] .= '</div>';

  $json['html'] .= '<div class="text-right">';
  $json['html'] .= '<a href="#" class="btn btn-danger" onClick="$(\'#fhd_delete\').submit(); return false;">Удалить</a>';
  $json['html'] .= '<button class="btn btn-outline-secondary ml-2" data-dismiss="modal" aria-label="Close">Закрыть</button>';
  $json['html'] .= '</div>';
  $json['html'] .= '</form>';


  $json['html'] .= '<script>

  </script>';

  echo json_encode($json);
}
