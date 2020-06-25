<?php

include ('../_conf.php');
include ('../classes/safemysql.class.php');
$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

$db->query("TRUNCATE TABLE operations_jornal");

$all_users = $db->getAll("SELECT * FROM users");

foreach ($all_users as $user) {
  //electricpower
  $db->query("INSERT INTO purses SET user_id = ?i, type = ?i, balance = ?s", $user['id'], 1, $user['balans']);
  if ($user['balans'] != 0) $db->query("INSERT INTO operations_jornal SET user_id = ?i, op_type = 1, balance_type = 1, amount = ?s", $user['id'], $user['balans']);
  //membership
  $db->query("INSERT INTO purses SET user_id = ?i, type = ?i, balance = ?s", $user['id'], 2, $user['membership_balans']);
  if ($user['membership_balans'] != 0) $db->query("INSERT INTO operations_jornal SET user_id = ?i, op_type = 1, balance_type = 2, amount = ?s", $user['id'], $user['membership_balans']);
  //target
  $db->query("INSERT INTO purses SET user_id = ?i, type = ?i, balance = ?s", $user['id'], 3, $user['target_balans']);
  if ($user['target_balans'] != 0) $db->query("INSERT INTO operations_jornal SET user_id = ?i, op_type = 1, balance_type = 3, amount = ?s", $user['id'], $user['target_balans']);
}
