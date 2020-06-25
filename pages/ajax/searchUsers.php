<?php
session_start();

include ('../../_conf.php');
include ('../../classes/safemysql.class.php');
$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

require_once('../../classes/core.class.php');
$core  = new Core();

$form = $core->form;

$users = $db->getAll("SELECT * FROM users WHERE is_del = 0 AND (name LIKE ?s OR email LIKE ?s OR phone LIKE ?s OR uchastok LIKE ?s)", '%'.$form['string'].'%', '%'.$form['string'].'%', '%'.$form['string'].'%', '%'.$form['string'].'%');

$html = '';

foreach ($users as $user) {
  $html .= '<tr>';
  $html .= '<td scope="row">';
  $html .= '<div class="media align-items-center">';
  $html .= '<div class="media-body">';
  $html .= '<span class="name mb-0 text-sm">'.$user['uchastok'].'</span>';
  $html .= '</div>';
  $html .= '</div>';
  $html .= '</td>';
  $html .= '<td class="budget">';
  $html .= '<a href="/cab/admin_user?id='.$user['id'].'">'.$user['name'].'</a>';
  $html .= '</td>';
  $html .= '<td class="budget">';
  if ($user['phone']) $html .= '+'.$user['phone'];
  $html .= '</td>';
  $user_contracts = $db->getAll("SELECT * FROM users_contracts WHERE user = ?i", $user['id']);
  $html .= '<td class="budget">';
  foreach ($user_contracts as $user_contract) {
    if (!$user_contract['num']) $user_contract['num'] = 'Б/Н';
    if ($user_contract['date_start'] != '0000-00-00') {
      $user_contract['date_start'] = date("d.m.Y", strtotime($user_contract['date_start']));
    } else {
      $user_contract['date_start'] = '---';
    }
    $html .= '<p><a href="admin_edit_contract?id='.$user_contract['id'].'">№'.$user_contract['num'].' от '.$user_contract['date_start'].'</a></p>';
  }
  $html .= '</td>';
  $user_counters = $db->getAll("SELECT * FROM counters WHERE user_id = ?i", $user['id']);
  $html .= '<td class="budget">';
  foreach ($user_counters as $user_counter) {
    $html .= '<p>'.$user_counter['model'].' №'.$user_counter['num'].'<br>('.$user_counter['plomb'].')</p>';
  }
  $html .= '</td>';
  $u_balance = $db->getOne("SELECT SUM(balance) FROM purses WHERE user_id = ?i", $user['id']);
  $html .= '<td class="budget">'.$u_balance.'р.</td>';
  $html .= '<td class="budget">';
  $html .= '<a class="text-danger" href="?action=delete&id='.$user['id'].'"><i class="fa fa-ban" aria-hidden="true"></i></a>';
  $html .= '</td>';
  $html .= '</tr>';
}

//var_dump($users);
echo $html;
