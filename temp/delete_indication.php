<?php

  include_once "../core/db_connect.php";

  $date = '2019-01-06 00:00:00';

  $sql = "SELECT * FROM `Indications` WHERE date >= '$date'";

  echo $sql;
  echo '<hr>';
    echo '<hr>';

  $result = mysql_query($sql);

  while ($delete_ind = mysql_fetch_assoc($result)) {
    //удаляем показания
    $delete_sql = "DELETE FROM Indications WHERE id = ".$delete_ind['id'];
    echo $delete_sql.'<br>';
    mysql_query($delete_sql);
    //возвращаем на баланс пользователю деньги
    $recalc_sql = "UPDATE users SET balans = balans + '".$delete_ind['additional_sum']."' WHERE id = ".$delete_ind['user'];
    echo $recalc_sql.'<br>';
    mysql_query($recalc_sql);
    echo '<hr>';
  }
