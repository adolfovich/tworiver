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

      $sql = $db->parse("SELECT * FROM Indications WHERE (user = ?i OR (SELECT is_admin FROM users WHERE id = ?i) = 1) AND counter_id = ?i AND tarif = ?i AND date BETWEEN ?s AND ?s", $_SESSION['id'], $_SESSION['id'], $form['counterId'], $tarif['id'], $start_date, $end_date);

      $indications = $db->getAll($sql);

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
              $total = 0;
              $i = 0;
              foreach ($indications as $indication) {
                if ($i == 0) {
                  $start_indications = $indication['prev_indications'];
                }
                $html .= '<tr>';
                  $html .= '<td class="text-center">'.date("d.m.Y", strtotime($indication['date'])).'</td>';
                  $html .= '<td class="text-center">'.$indication['prev_indications'].'</td>';
                  $html .= '<td class="text-center">'.$indication['Indications'].'</td>';
                  $html .= '<td class="text-center">'.number_format(($indication['Indications']-$indication['prev_indications']), 2, '.', '').'</td>';
                  $html .= '<td class="text-center">'.$indication['additional'].'</td>';
                  $html .= '<td class="text-center">'.$indication['additional_sum'].'</td>';
                $html .= '</tr>';
                $total += $indication['additional_sum'];
                $i++;
              }
              $html .= '<tr>';
              $html .= '<td class="text-right"><b>ИТОГО:</b></td>';
              $html .= '<td class="text-center"><b>'.$start_indications.'</b></td>';
              $html .= '<td class="text-center"><b>'.$indication['Indications'].'</b></td>';
              $rashod = $indication['Indications'] - $start_indications;
              $html .= '<td class="text-center"><b>'.number_format($rashod, 2, '.', '').'</b></td>';
              $html .= '<td class="text-center"></td>';
              $html .= '<td class="text-center"><b>'.number_format($total, 2, '.', '').'</b></td>';
              $html .= '';
              $html .= '</tr>';
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
