<?php
  include_once "../core/db_connect.php";
  if (isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $sms = intval($_POST['set']);
    if(mysql_query("UPDATE users SET send_monthly_sms = $sms WHERE id = $user_id") or die(mysql_error())) {
      echo 'OK';
    } else {
      echo 'ERROR';
    }  	
  } else {
    echo 'ERROR';
  }
