<?php
session_start();

include ('../../_conf.php');
include ('../../classes/safemysql.class.php');
$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

require_once('../../classes/core.class.php');
$core  = new Core();

$form = $core->form;

$users_ids = false;

if ($form['string'] != '') {
  $users_ids = $db->getAll("SELECT id FROM users WHERE is_del = 0 AND (name LIKE ?s OR email LIKE ?s OR phone LIKE ?s OR uchastok LIKE ?s)", '%'.$form['string'].'%', '%'.$form['string'].'%', '%'.$form['string'].'%', '%'.$form['string'].'%');
}

$users_ids_arr = [];

if (isset($users_ids)) {
  foreach ($users_ids as $users_id) {
    $users_ids_arr[] = $users_id['id'];
  }
}

if (isset($users_ids)) {
  $sql = $db->parse("SELECT oj.*, (SELECT name FROM users WHERE id = oj.user_id) as name, (SELECT uchastok FROM users WHERE id = oj.user_id) as uchastok, (SELECT name FROM operations_jornal_types WHERE id = oj.op_type) as op_name FROM operations_jornal oj WHERE op_type IN (2, 3) AND user_id IN (?a)", $users_ids_arr);
} else {
  $sql = $db->parse("SELECT oj.*, (SELECT name FROM users WHERE id = oj.user_id) as name, (SELECT uchastok FROM users WHERE id = oj.user_id) as uchastok, (SELECT name FROM operations_jornal_types WHERE id = oj.op_type) as op_name FROM operations_jornal oj WHERE op_type IN (2, 3)");
}

$contributions = $db->getAll($sql);

$html = '';

if ($contributions) {
  foreach ($contributions as $contribution) {

    $html .= '<tr>';
    $html .= '<td class="budget">'.$contribution['id'].'</td>';
    $html .= '<td class="budget">'.date("d.m.Y", strtotime($contribution['date'])).'</td>';
    $html .= '<td class="budget">'.$contribution['uchastok'].' ('.$contribution['name'].')</td>';
    $html .= '<td class="budget">'.$contribution['op_name'].'</td>';
    $html .= '<td class="budget">'.$contribution['comment'].'</td>';
    $html .= '<td class="budget">'.$contribution['amount'].'р.</td>';
    $html .= '</tr>';
  }
} else {
  $html .= '<tr>';
  $html .= '<td class="budget text-center" colspan="6">Ничего не найдено</td>';
  $html .= '</tr>';
}

echo $html;
