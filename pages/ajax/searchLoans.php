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
} else {
  $users_ids = $db->getAll("SELECT id FROM users WHERE is_del = 0");
}

$users_ids_arr = [];

//var_dump($users_ids);

if (isset($users_ids) && count($users_ids) && $users_ids !== FALSE) {
  foreach ($users_ids as $users_id) {
    $users_ids_arr[] = $users_id['id'];
  }
}

if (isset($users_ids)) {
  $sql = $db->parse("SELECT lo.*, (SELECT name FROM users WHERE id = lo.user_id) as name, (SELECT uchastok FROM users WHERE id = lo.user_id) as uchastok, (SELECT SUM(payment_amount) FROM loans_payments WHERE loan = lo.id) as payouts FROM loans lo WHERE user_id IN (?a) ORDER BY lo.id DESC", $users_ids_arr);
} else {
  $sql = $db->parse("SELECT lo.*, (SELECT name FROM users WHERE id = lo.user_id) as name, (SELECT uchastok FROM users WHERE id = lo.user_id) as uchastok, (SELECT SUM(payment_amount) FROM loans_payments WHERE loan = lo.id) as payouts FROM loans lo ORDER BY lo.id DESC");
}

$loans = $db->getAll($sql);

$html = '';

if ($loans) {
  foreach ($loans as $loan) {
	  
	  if ($loan['amount'] == $loan['payouts']) {
		  $tr_color = '#98FB98';
	  } else {
		  $tr_color = '';
	  }

    $html .= '<tr style="background:'.$tr_color.'">';
    $html .= '<td class="budget">'.$loan['id'].'</td>';
	$html .= '<td class="budget"><a href="/cab/admin_loan?id='.$loan['id'].'">'.$loan['agreement_num'].'</a></td>';
    $html .= '<td class="budget">'.date("d.m.Y", strtotime($loan['agreement_date'])).'</td>';
    $html .= '<td class="budget">'.$loan['uchastok'].' ('.$loan['name'].')</td>';
    $html .= '<td class="budget">'.$loan['amount'].'р.</td>';
	if (!$loan['payouts']) $loan['payouts'] = 0;
	$html .= '<td class="budget">'.$loan['payouts'].'р.</td>';
    $html .= '<td class="budget">'.$loan['comment'].'</td>';
    $html .= '</tr>';
  }
} else {
  $html .= '<tr>';
  $html .= '<td class="budget text-center" colspan="6">Ничего не найдено</td>';
  $html .= '</tr>';
}

echo $html;
