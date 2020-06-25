<?php


ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include ('../_conf.php');
include ('../classes/safemysql.class.php');
$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

$db->query("TRUNCATE TABLE `counters`");

$users = $db->getAll("SELECT * FROM users");

foreach ($users as $user) {
  echo 'user '.$user['id'].'<br>';
  if ($user['sch_model'] || $user['sch_num'] || $user['sch_plomb_num'] || $user['modem_num']) {
    //echo 'sch_model '.$user['sch_model'].'<br>';
    //echo 'sch_num '.$user['sch_num'].'<br>';
    //echo 'sch_plomb_num '.$user['sch_plomb_num'].'<br>';

    $contract = $db->getRow("SELECT * FROM users_contracts WHERE user = ?i AND date_end IS NULL", $user['id']);

    $insert = [
      'user_id' => $user['id'],
      'contract_id' => $contract['id'],
      'model' => $user['sch_model'],
      'num' => $user['sch_num'],
      'plomb' => $user['sch_plomb_num'],
      'modem_num' => $user['modem_num']
    ];

    $db->query("INSERT INTO counters SET ?u", $insert);

    $counter_id = $db->insertId();
    echo '$counter_id '.$counter_id.'<br>';

    $db->query("UPDATE Indications SET counter_id = ?i WHERE user = ?i", $counter_id, $user['id']);

  } else {
    //echo '<b>COUNTER NOT FOUND</b><br>';
  }
}
