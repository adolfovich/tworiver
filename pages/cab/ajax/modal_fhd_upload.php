<?php
include('connect.php');

if (isset($_SESSION['id'])) {

  $json = [];
  $json['html'] = '';

  $json['header'] = 'Загрузка документа';
  $json['html'] .= '<form class="form-inline" id="fhd_delete" method="POST" enctype="multipart/form-data">';
  $json['html'] .= '<input type="hidden" name="fhd_upload" />';
  $json['html'] .= '<div class="form-group mb-2">';
  $json['html'] .= '<label for="fhd_date" style="margin-right: 10px; margin-top: 6px;">Период</label>';
  $json['html'] .= '<input type="date" class="form-control" name="fhd_date">';
  $json['html'] .= '</div>';

  $json['html'] .= '<div class="form-group mx-sm-3 mb-2">';
  $json['html'] .=   '<label for="fhd_file" class="sr-only">Файл</label>';
  $json['html'] .=   '<input type="file" class="form-control-plaintext" name="fhd_file" >';
  $json['html'] .= '</div>';


  $json['html'] .= '<button type="submit" class="btn btn-primary mb-2">Загрузить</button>';
/*
  $json['html'] .= '<br>';
  $json['html'] .= '<br>';



  $json['html'] .= '<div class="text-right">';
  $json['html'] .= '<a href="#" class="btn btn-danger" onClick="$(\'#fhd_delete\').submit(); return false;">Удалить</a>';
  $json['html'] .= '<button class="btn btn-outline-secondary ml-2" data-dismiss="modal" aria-label="Close">Закрыть</button>';
  $json['html'] .= '</div>';*/
  $json['html'] .= '</form>';


  $json['html'] .= '<script>

  </script>';

  echo json_encode($json);
}
