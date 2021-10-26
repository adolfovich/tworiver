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
      $html .= '<td style="white-space:normal;"><a href="/'.$report['path'].'" target="_blank">'.$report['name'].'</a></td>';
      $html .= '<td class="text-center">';
      $html .= '<a href="/'.$report['path'].'" target="_blank" class="btn btn-outline-primary btn-sm" style="padding: 0.3rem 0.5rem;"><i class="fa fa-download" aria-hidden="true"></i></a>';
      if ($form['role'] == 'admin') {
        $html .= '<a href="#" class="btn btn-outline-danger btn-sm" style="padding: 0.3rem 0.5rem; margin-left: 5px;"><i class="fa fa-trash" aria-hidden="true" onClick="loadModal(\'modal_fhd_delete\', \'id='.$report['id'].'\'); return false;"></i></a>';
      }
      $html .= '</td>';
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
      $html .= '<td class="text-center">';
      $html .= '<a href="/'.$act['path'].'" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>';
      $html .= '</td>';
      $html .= '</tr>';
    }
  } else {
    $html .= '<tr>';
    $html .= '<td colspan="4">Нет данных за выбраный период</td>';
    $html .= '</tr>';
  }
}

echo $html;
