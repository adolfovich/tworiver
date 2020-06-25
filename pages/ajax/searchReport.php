<?php
session_start();

include ('../../_conf.php');
include ('../../classes/safemysql.class.php');
$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

require_once('../../classes/core.class.php');
$core  = new Core();

$form = $core->form;

$html = '';

if ($form['type'] == 'fhd') {

  $reports = $db->getAll("SELECT * FROM `reports_fhd` WHERE date BETWEEN ?s AND ?s", $form['year'].'-01-01', $form['year'].'-12-31');
  //var_dump($db->parse("SELECT * FROM `reports_fhd` WHERE date BETWEEN ?s AND ?s", $form['year'].'-01-01', $form['year'].'-12-31'));
  if ($reports) {
    foreach ($reports as $report) {
      $html .= '<tr>';
      $html .= '<td>'.date("d.m.Y", strtotime($report['date'])).'</td>';
      $html .= '<td>'.$report['name'].'</td>';
      $html .= '<td class="text-center"><a href="/'.$report['path'].'" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a></td>';
      $html .= '</tr>';
    }
  } else {
    $html .= '<tr>';
    $html .= '<td colspan="3">Нет данных за выбраный период</td>';
    $html .= '</tr>';
  }
}

echo $html;
