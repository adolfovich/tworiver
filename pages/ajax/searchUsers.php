<?php
session_start();

include ('../../_conf.php');
include ('../../classes/safemysql.class.php');
$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

require_once('../../classes/core.class.php');
$core  = new Core();

$form = $core->form;



$ordering = '';



$sql = $db->parse("SELECT u.*, (SELECT SUM(balance) FROM purses WHERE user_id = u.id) as balance FROM users u WHERE u.is_del = 0 AND (u.name LIKE ?s OR u.email LIKE ?s OR u.phone LIKE ?s OR u.uchastok LIKE ?s)".$ordering, '%'.$form['string'].'%', '%'.$form['string'].'%', '%'.$form['string'].'%', '%'.$form['string'].'%');

$users = $db->getAll($sql);


if ($form['sorting'] == 'area') {
  foreach ($users as $key => $value) {
    $users_arr[$value['id']] = $value['uchastok'];
  }
  natsort($users_arr);
} else if ($form['sorting'] == 'name') {
  foreach ($users as $key => $value) {
    $users_arr[$value['id']] = $value['name'];
  }
  natsort($users_arr);
} else if ($form['sorting'] == 'balance') {
  foreach ($users as $key => $value) {
    $users_arr[$value['id']] = $value['balance'];
  }
  asort($users_arr);
} else {
  foreach ($users as $key => $value) {
    $users_arr[$value['id']] = $value['id'];
  }
  asort($users_arr);
}

$btype = $form['balance'];


$html = '';

foreach ($users_arr as $user_id => $user_area) {
  $user = $db->getRow("SELECT * FROM users WHERE id = ?i", $user_id);
  $user_balances = $db->getOne("SELECT SUM(balance) FROM purses WHERE user_id = ?i", $user['id']);

  $html .= '<tr>';
  $html .= '<td scope="row">';
  $html .= '<div class="media align-items-center">';
  $html .= '<div class="media-body">';
  $html .= '<span class="name mb-0 text-sm">'.$user['uchastok'].'</span>';
  $html .= '</div>';
  $html .= '</div>';
  $html .= '</td>';
  $html .= '<td class="budget text-wrap">';
  $html .= '<a href="/cab/admin_user?id='.$user['id'].'">'.$user['name'].'</a>';
  $html .= '</td>';
  $html .= '<td class="budget">';
  if ($user['phone']) $html .= '+'.$user['phone'];
  $html .= '</td>';
  ////////////////////////////////////
  $user_contracts = $db->getAll("SELECT * FROM users_contracts WHERE user = ?i AND date_end IS NULL", $user['id']);
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
  $user_counters = $db->getAll("SELECT c.* FROM counters c WHERE c.user_id = ?i AND (SELECT date_end FROM users_contracts WHERE id = c.contract_id) IS NULL AND dismantling_date IS NULL", $user['id']);
  $html .= '<td class="budget">';
  foreach ($user_counters as $user_counter) {
    $html .= '<p>'.$user_counter['model'].' №'.$user_counter['num'].'</p>';
  }
  $html .= '</td>';
  
  
  $html .= '<td>';
	$user_counters = $db->getAll("SELECT c.* FROM counters c WHERE c.user_id = ?i AND (SELECT date_end FROM users_contracts WHERE id = c.contract_id) IS NULL AND dismantling_date IS NULL", $user['id']);
	foreach ($user_counters as $user_counter) {
		$html .= '<p>'.$user_counter['modem_num'].'</p>';
	  }
  
  $html .= '</td>';
  
  $user_balance = $db->getOne("SELECT balance FROM purses WHERE user_id = ?i AND type = ?i", $user['id'], $btype);
  $html .= '<td class="budget">';
  $html .= $user_balance;
  $html .= '</td>';
  
  $yesterday_ind_date_q = $db->parse("SELECT last_ind_date FROM counters WHERE user_id = ?i AND dismantling_date IS NULL AND last_ind_date IS NOT NULL", $user['id']);
  $yesterday_ind_date = $db->getOne($yesterday_ind_date_q);
  
  
  /*if ($db->getAll("SELECT c.* FROM counters c WHERE c.user_id = ?i AND (SELECT date_end FROM users_contracts WHERE id = c.contract_id) IS NULL AND dismantling_date IS NULL", $user['id'])) {
	  if ($yesterday_ind_date == date("Y-m-d", strtotime("yesterday"))) {
		  $ind_td = '<i class="fa fa-check-circle text-success" aria-hidden="true"></i>';
	  } else {
		  $ind_td = '<i class="fa fa-exclamation-circle text-warning" aria-hidden="true"></i>';
	  }
  } else {
	 $ind_td = ''; 
  }*/
   
  
  $html .= '<td class="budget text-center ">';
  //$html .= $user_counter['modem_num'];
  $html .= $ind_td;
  $html .= '</td>';
  
  
  $html .= '<td class="budget">';
  $html .= '<a class="text-danger" href="?action=delete&id='.$user['id'].'"><i class="fa fa-ban" aria-hidden="true"></i></a>';
  $html .= '</td>';
  $html .= '</tr>';
}

//var_dump($users);
echo $html;
