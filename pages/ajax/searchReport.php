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
      $html .= '<td><a href="/'.$report['path'].'" target="_blank">'.$report['name'].'</a></td>';
      $html .= '<td class="text-center"><a href="/'.$report['path'].'" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a></td>';
      $html .= '</tr>';
    }
  } else {
    $html .= '<tr>';
    $html .= '<td colspan="3">Нет данных за выбраный период</td>';
    $html .= '</tr>';
  }
} else if ($form['type'] == 'act') {
  $acts = $db->getAll("SELECT * FROM acts WHERE visible = 1 AND date_start BETWEEN ?s AND ?s", $form['year'].'-01-01', $form['year'].'-12-31');
  if ($acts) {
    foreach ($acts as $act) {
      $html .= '<tr>';
      $html .= '<td>'.date("d.m.Y", strtotime($act['date_start'])).'</td>';
      if ($act['user']) {
        $act_user_data = $db->getRow("SELECT * FROM users WHERE id = ?i", $act['user']);
        $html .= '<td>'.$act_user_data['uchastok'].'</td>';
      }
      $html .= '<td>'.$db->getOne("SELECT name FROM acts_type WHERE id = ?i", $act['type']).'</td>';
      $html .= '<td><a href="/'.$act['path'].'" target="_blank">'.$act['comment'].'</a></td>';
      $html .= '<td class="text-center"><a href="/'.$act['path'].'" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a></td>';
      $html .= '</tr>';
    }
  } else {
    $html .= '<tr>';
    $html .= '<td colspan="4">Нет данных за выбраный период</td>';
    $html .= '</tr>';
  }
}

echo $html;
