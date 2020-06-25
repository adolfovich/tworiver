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

  $t2_ind = $db->getAll(
    "SELECT * FROM `Indications` WHERE `date` BETWEEN ?s AND ?s AND `user` = ?i AND `tarif` = 3",
    date("Y-m-01", strtotime($year.'-'.$month.'-01')),
    date("Y-m-t", strtotime($year.'-'.$month.'-01')),
    $_SESSION['id']
  );

  foreach ($t2_ind as $ind) {
    $arr['data1'][] = ($ind['Indications'] - $ind['prev_indications']);
  }

  $t1_ind = $db->getAll(
    "SELECT * FROM `Indications` WHERE `date` BETWEEN ?s AND ?s AND `user` = ?i AND `tarif` = 2",
    date("Y-m-01", strtotime($year.'-'.$month.'-01')),
    date("Y-m-t", strtotime($year.'-'.$month.'-01')),
    $_SESSION['id']
  );
  foreach ($t1_ind as $ind) {
    $arr['data2'][] = ($ind['Indications'] - $ind['prev_indications']);
  }

  echo json_encode($arr);
}
