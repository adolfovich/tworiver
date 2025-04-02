<?php

include('connect.php');
if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  //var_dump($form);

  if ($is_admin) {
	  
	  $loan_data = $db->getRow("SELECT lo.*, (SELECT SUM(payment_amount) FROM loans_payments WHERE loan = lo.id) as payouts FROM loans lo WHERE lo.id = ?i", $form['loan_id']);

    if (!$form['loan_payout_date'] || $form['loan_payout_date'] == '') {
      $json['status'] = 'error';
      $json['text'] = 'Не заполнена дата выплаты';
      $json['error_input'] = 'loan_payout_date';
    } else if (!$form['loan_payout_amount'] || $form['loan_payout_amount'] <= 0) {
      $json['status'] = 'error';
      $json['text'] = 'Сумма должна быть больше нуля';
      $json['error_input'] = 'loan_payout_amount';
    } else if ( ($loan_data['amount'] - $loan_data['payouts']) - $form['loan_payout_amount'] < 0 ) {
	  $json['status'] = 'error';
      $json['text'] = 'Сумма должна быть не больше '.($loan_data['amount'] - $loan_data['payouts']). 'р.';
      $json['error_input'] = 'loan_payout_amount';
	} else {

      

      //создание выплаты

      $amount = str_replace(',', '.', $form['loan_payout_amount']);

	  
	  $loan_payout_insert = [
			'loan' => $loan_data['id'],
			'payment_date' => $form['loan_payout_date'],
			'payment_amount' => $amount
	  ];
	  
	  $db->query("INSERT INTO loans_payments SET ?u", $loan_payout_insert);	


      $json['status'] = 'success';
      $json['text'] = 'Выплата добавлена ';
      $json['redirect'] = '/cab/admin_loan?id='.$loan_data['id'];

      



    }

  } else {
    $json['status'] = 'error';
    $json['text'] = 'Ошибка доступа';
  }

  echo json_encode($json);
}
