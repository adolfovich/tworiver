<?php

include('connect.php');

$html = '';

$html .= '';

if (isset($_SESSION['id'])) {
  if (isset($form['counterId']) && $form['counterId']) {

    $month = $form['month'];
    $year = $form['year'];

    $start_date = $year.'-'.$month.'-1';
    $end_date = $year.'-'.$month.'-'.cal_days_in_month(CAL_GREGORIAN, $month, $year);
    //echo 'counter - '.$form['counterId'].'<br>'."\r\n";

    $tarifs = $db->getAll("SELECT * FROM tarifs");

    $html .= '<div class="container mt-3">';

    $html .= '<ul class="nav nav-tabs">';

    $i = 1;

    foreach ($tarifs as $tarif) {
      if ($i == 1) {
        $active = 'active';
      } else {
        $active = '';
      }
      $html .= '<li class="nav-item">';
        $html .= '<a class="nav-link '.$active.'" data-toggle="tab" href="#'.$tarif['id_waviot'].'">'.$tarif['name'].'</a>';
      $html .= '</li>';

      $i++;
    }

    $html .= '</ul>';
    $html .= '<div class="tab-content">';

    $i = 1;
    foreach ($tarifs as $tarif) {
      if ($i == 1) {
        $active = 'active';
      } else {
        $active = '';
      }
      $indications = $db->getAll("SELECT * FROM Indications WHERE user = ?i AND counter_id = ?i AND tarif = ?i AND date BETWEEN ?s AND ?s", $_SESSION['id'], $form['counterId'], $tarif['id'], $start_date, $end_date);


        $html .= '<div id="'.$tarif['id_waviot'].'" class="container tab-pane '.$active.'">';
          $html .= '<table class="table table-bordered table-sm table-hover">';

            $html .= '<thead class="thead-dark">';
              $html .= '<tr>';
                $html .= '<th rowspan="2" style="vertical-align: middle; text-align: center;">Дата</th>';
                $html .= '<th colspan="3" style="vertical-align: middle; text-align: center;">Показания</th>';
                $html .= '<th rowspan="2" style="vertical-align: middle; text-align: center;">Цена</th>';
                $html .= '<th rowspan="2" style="vertical-align: middle; text-align: center;">Начислено</th>';
              $html .= '</tr>';
              $html .= '<tr>';
                $html .= '<th  style="vertical-align: middle; text-align: center;">Начало</th>';
                $html .= '<th  style="vertical-align: middle; text-align: center;">Конец</th>';
                $html .= '<th style="vertical-align: middle; text-align: center;">Расход</th>';
              $html .= '</tr>';
            $html .= '</thead>';

            $html .= '<tbody class="table-striped">';
            if ($indications) {
              foreach ($indications as $indication) {
                //echo 'indication - '.$indication['date'].' | '.$indication['Indications'].'<br>'."\r\n";
                $html .= '<tr>';
                  $html .= '<td class="text-center">'.date("d.m.Y", strtotime($indication['date'])).'</td>';
                  $html .= '<td class="text-center">'.$indication['prev_indications'].'</td>';
                  $html .= '<td class="text-center">'.$indication['Indications'].'</td>';
                  $html .= '<td class="text-center">'.number_format(($indication['Indications']-$indication['prev_indications']), 2, '.', '').'</td>';
                  //$html .= '<td></td>';
                  $html .= '<td class="text-center">'.$indication['additional'].'</td>';
                  $html .= '<td class="text-center">'.$indication['additional_sum'].'</td>';
                $html .= '</tr>';
              }
            } else {
              $html .= '<tr>';
                $html .= '<td colspan="6"><p class="text-center">За выбраный период показаний не найдено</p></td>';
              $html .= '</tr>';
            }

            $html .= '</tbody>';
          $html .= '</table>';
        $html .= '</div>';


      $i++;
    }

    $html .= '</div>';

    $html .= '</div>';



  } else {
    $arr['error'] = 'Ошибка';
  }
}

echo $html;
