<?php

$electric_balance = $db->getOne("SELECT balance FROM purses WHERE user_id = ?i AND type = 1", $user_data['id']);

$contracts = $db->getAll("SELECT * FROM users_contracts WHERE user = ?i AND date_end IS NULL", $user_data['id']);

$current_tarifs = $db->getAll("SELECT * FROM tarifs");

$tarifs_greed = 12 / count($current_tarifs);

function getCounters($contract_num)
{
  global $db;

  $counters = $db->getAll("SELECT * FROM counters WHERE contract_id = ?i AND dismantling_date IS NULL", $contract_num);

  return $counters;
}

$currentmomth = date("m");

$currentyear = date("Y");

$showOperations = 0;

if (isset($_GET['datefrom']) || isset($_GET['dateto'])) {
	$showOperations = 1;
}

if (isset($_GET['datefrom'])) {
	$opStartDate = $_GET['datefrom'];
} else {
	$opStartDate = date("Y-m-01");
}

if (isset($_GET['dateto'])) {
	$opEndDate = $_GET['dateto'];
} else {
	$opEndDate = date("Y-m-d");
}

$opEndDateTime = $opEndDate . ' 23:59:59';


$operations = $db->getAll("SELECT oj.*, (SELECT name FROM operations_jornal_types WHERE id = oj.op_type) as operation_name FROM operations_jornal oj WHERE oj.user_id = ?i AND oj.balance_type = 1 AND oj.date BETWEEN ?s AND ?s ORDER BY date DESC", $user_data['id'], $opStartDate, $opEndDateTime);

$acts = $db->getAll("SELECT * FROM acts WHERE user = ?i AND type = 1", $user_data['id']);


include('tpl/cab/electricpower.tpl');
