<?php

include('connect.php');

if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  if ($is_admin) {
    $json['html'] = '';

    /*if ($_GET['type'] == 'target') {
      $type = 'целевого';
      $type_id = 3;
    } else if ( 'membership') {
      $type = 'членского';
      $type_id = 2;
    }*/
	
	
	$loan_data = $db->getRow("SELECT * FROM loans WHERE id = ?i", $_GET['load_id']);

    $json['header'] = 'Выплата по договору займа '.$loan_data['agreement_num']; //
		
    $json['html'] .= '<form id="add_loan_payout">';

    $json['html'] .= '<input type="hidden" name="loan_id" value="'.$loan_data['id'].'">';
	
	$json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="loan_payout_date">Дата выплаты:</label>';
    $json['html'] .= '<input type="date" name="loan_payout_date" class="form-control forcheck" id="loan_payout_date" aria-describedby="numbertHelp">';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="loan_payout_amount">Сумма выплаты:</label>';
    $json['html'] .= '<input type="number" name="loan_payout_amount" class="form-control forcheck" id="loan_payout_amount" aria-describedby="numbertHelp">';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="text-right">';
    $json['html'] .= '<a href="#" class="btn btn-primary" onClick="addLoanPayout(); return false;">Сохранить</a>';
    $json['html'] .= '<button class="btn btn-outline-secondary ml-2" data-dismiss="modal" aria-label="Close">Закрыть</button>';
    $json['html'] .= '</div>';

    $json['html'] .= '</form>';
  } else {
    $json['header'] = 'Ошибка';
    $json['html'] .= '<form>';
    $json['html'] .= '<h4>Доступ запрещен</h4>';
    $json['html'] .= '<div class="text-right">';
    $json['html'] .= '<button class="btn btn-outline-secondary ml-2" data-dismiss="modal" aria-label="Close">Закрыть</button>';
    $json['html'] .= '</div>';
    $json['html'] .= '</form>';
  }

  echo json_encode($json);
}
