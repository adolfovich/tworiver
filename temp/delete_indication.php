<?php

  include_once "../core/db_connect.php";

  if (isset($_GET['newstartdate'])) {
    $date = $_GET['newstartdate'];
  } else {
    $date = 0;
  }

  if (isset($_GET['deleteuser'])) {
    $user_id = int($_GET['deleteuser']);
  } else {
    $user_id = 0;
  }

  if ($date && $user_id) {
    $sql = "SELECT * FROM `Indications` WHERE date >= '$date' AND user = $user_id";

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
  }
