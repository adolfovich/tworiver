<?php
	include_once "db_connect.php";


  if (isset($_POST['month']) && isset($_POST['user'])) {
    $user = $_POST['user'];
    $month = $_POST['month'];

    //echo 'месяц'.$_POST['month'].' пользователь'. $_POST['user'];

    echo '<ul class="nav nav-tabs">';

    $active = 1;
    $all_tarifs_result = mysql_query("SELECT * FROM tarifs") or die(mysql_error());
    while ($all_tarifs = mysql_fetch_assoc($all_tarifs_result)) {
      if ($active == 1) {
        echo '<li class="active"><a href="#'.$all_tarifs['id_waviot'].'" data-toggle="tab" >'.$all_tarifs['name'].'</a></li>';
      }
      else {
        echo '<li><a href="#'.$all_tarifs['id_waviot'].'" data-toggle="tab" >'.$all_tarifs['name'].'</a></li>';
      }
      $active = 0;
    }

    echo '</ul>';
    echo '<div class="tab-content">';

    //Выбираем все существующие тарифы
    $active = 1;
    $all_tarifs_result = mysql_query("SELECT * FROM tarifs") or die(mysql_error());
    while ($all_tarifs = mysql_fetch_assoc($all_tarifs_result)) {
      if ($active == 1) {
        echo '<div class="tab-pane fade in active" id="'.$all_tarifs['id_waviot'].'">';
      }
      else {
        echo '<div class="tab-pane fade" id="'.$all_tarifs['id_waviot'].'">';
      }

      echo '<table class="table table-condensed">';
      echo '<tr>';
      echo '<th rowspan="2">Дата</th>';
      echo '<th rowspan="2">Тариф</th>';
      echo '<th colspan="3">Показания</th>';
      echo '<th rowspan="2">Цена</th>';
      echo '<th rowspan="2">Начислено</th>';
      echo '<th rowspan="2"></th>';
      echo '</tr>';
      echo '<tr>';
      echo '<th>Начало</th>';
      echo '<th>Конец</th>';
      echo '<th>Расход</th>';
      echo '</tr>';
      $result_indications = mysql_query("SELECT
                                              i.auto,
                                              i.id,
                                              i.additional_sum,
                                              i.date,
                                              i.prev_indications,
                                              i.Indications,
                                              i.additional as price,
                                              t.name AS tarif
                                        FROM Indications i, tarifs t
                                        WHERE
                                              i.user = ".$user." AND
                                              t.id_waviot = '".$all_tarifs['id_waviot']."' AND
                                              i.tarif = t.id AND
                                              i.date BETWEEN '".$month."-01'
                                              AND '".$month."-31'
                                        ") or die(mysql_error());

      while ($indications = mysql_fetch_assoc($result_indications)) {
        $date_indications = date( 'd.m.Y',strtotime($indications['date']));
        echo '<tr>';
        echo '<td>'. $date_indications.'</td>';
        echo '<td>'. $indications['tarif'].'</td>';
        echo '<td>'. $indications['prev_indications'].'</td>';
        echo '<td>'. $indications['Indications'].'</td>';
        echo '<td>'.($indications['Indications'] - $indications['prev_indications']).'</td>';
        echo '<td>'. $indications['price'].'</td>';
        echo '<td>'. $indications['additional_sum'].'</td>';
        if (strtotime($indications['date']) <= strtotime($last_act_date) || $indications['auto'] == 1) {
          echo '<td style="text-align:center"><span class="fa-stack fa-lg"><i class="fa fa-trash fa-stack-1x" aria-hidden="true"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span></td>';
        }
        else {
          echo '<td style="text-align:center"><a class="del_user" href="#" onclick="ConfirmDelInd('.$indications['id'].','.$selected_user.','.$indications['additional_sum'].')"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></a></td>';
        }
        echo '</tr>';

      }
    echo '</table>';
    echo '</div>';
    $active = 0;
    }
    echo '</div>';
  }
