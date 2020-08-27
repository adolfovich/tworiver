<?php

include('connect.php');

if (isset($_SESSION['id'])) {
  if (strlen($_POST['month']) == 1) {
    $month = "0".$_POST['month'];
  } else {
    $month = $_POST['month'];
  }

  $year = $_POST['year'];

  $arr = [];

  $arr['month'] = ucfirst($core->getMonthName($month)).' '.$year;

  for($i = 1; $i <= date('t', strtotime($year.'-'.$month.'-01')); $i++) {
    $arr['labels'][] = $i.'.'.date('m', strtotime($year.'-'.$month.'-01'));
  }

  $currentDate = '01.'.$month.'.'.$year;
//T1
  for ($i = 1; $i <= date('t', strtotime($currentDate)); $i++) {
    $ind = $db->getRow("SELECT * FROM `Indications` WHERE `date`BETWEEN ?s AND ?s AND `user` = ?i AND `tarif` = 2", $year.'-'.$month.'-'.$i.' 00:00:00', $year.'-'.$month.'-'.$i.' 23:59:59', $_SESSION['id']);
    if ($ind) {
      $arr['data2'][] = ($ind['Indications'] - $ind['prev_indications']);
    } else {
      $arr['data2'][] = 0;
    }
  }
  //T2
  for ($i = 1; $i <= date('t', strtotime($currentDate)); $i++) {
    $ind = $db->getRow("SELECT * FROM `Indications` WHERE `date`BETWEEN ?s AND ?s AND `user` = ?i AND `tarif` = 3", $year.'-'.$month.'-'.$i.' 00:00:00', $year.'-'.$month.'-'.$i.' 23:59:59', $_SESSION['id']);
    if ($ind) {
      $arr['data1'][] = ($ind['Indications'] - $ind['prev_indications']);
    } else {
      $arr['data1'][] = 0;
    }
  }

  echo json_encode($arr);
}
