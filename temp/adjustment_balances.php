<?php

  include_once "../core/db_connect.php";

  //$date = '2019-01-06 00:00:00';

  //$sql = "SELECT * FROM `users` WHERE id = 122";
  $sql = "SELECT * FROM `users`";
  echo $sql;
  echo '<hr>';
  echo '<hr>';

  $result = mysql_query($sql);

  while ($user = mysql_fetch_assoc($result)) {
    $total = $user['balans'] + $user['membership_balans'] + $user['target_balans'];
    $recalc_sql = "UPDATE users SET total_balance = '".$total."'  WHERE id = ".$user['id'];
    echo $recalc_sql.'<br>';
    mysql_query($recalc_sql) or die(mysql_error());
    echo '<hr>';
  }


