<?php

$electric_balance = $db->getOne("SELECT balance FROM purses WHERE user_id = ?i AND type = 1", $user_data['id']);

$contracts = $db->getAll("SELECT * FROM users_contracts WHERE user = ?i AND date_end IS NULL", $user_data['id']);

$current_tarifs = $db->getAll("SELECT * FROM tarifs");

$tarifs_greed = 12 / count($current_tarifs);

function getCounters($contract_num)
{
  global $db;

  $counters = $db->getAll("SELECT * FROM counters WHERE contract_id = ?i", $contract_num);

  return $counters;
}

$currentmomth = date("m");

$currentyear = date("Y");


$operations = $db->getAll("SELECT oj.*, (SELECT name FROM operations_jornal_types WHERE id = oj.op_type) as operation_name FROM operations_jornal oj WHERE oj.user_id = ?i AND oj.balance_type = 1 ORDER BY date DESC", $user_data['id']);

//var_dump($contract);

include('tpl/cab/electricpower.tpl');
