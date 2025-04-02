<?php

include('connect.php');
if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  //var_dump($form);

  if ($is_admin) {

    if (!isset($form['loan_user']) || !$form['loan_user'] || $form['loan_user'] == 0) {
      $json['status'] = 'error';
      $json['text'] = 'Не выбран пользователь';
      $json['error_input'] = 'loan_user';
    } else if (!$form['loan_agreement_num'] || $form['loan_agreement_num'] == '') {
      $json['status'] = 'error';
      $json['text'] = 'Не заполнен номер договора';
      $json['error_input'] = 'loan_agreement_num';
    } else if (!$form['loan_agreement_date'] || $form['loan_agreement_date'] == '') {
      $json['status'] = 'error';
      $json['text'] = 'Не заполнена дата договора';
      $json['error_input'] = 'loan_agreement_date';
    } else if (!$form['loan_amount'] || $form['loan_amount'] <= 0) {
      $json['status'] = 'error';
      $json['text'] = 'Сумма должна быть больше нуля';
      $json['error_input'] = 'loan_amount';
    } else {

      $loan_user = $db->getRow("SELECT * FROM users WHERE id = ?i", $form['loan_user']);

      //начисление взноса

      $amount = str_replace(',', '.', $form['loan_amount']);

      //$core->changeBalance($contribution_user['id'], $form['contribution_type'], $form['contribution_type'], $amount, $form['contribution_comment']);
	  
	  $loan_insert = [
			'user_id' => $loan_user['id'],
			'agreement_num' => $form['loan_agreement_num'],
			'agreement_date' => $form['loan_agreement_date'],
			'amount' => $amount,
			'comment' => $form['loan_comment']
	  ];
	  
	  $db->query("INSERT INTO loans SET ?u", $loan_insert);


      $json['status'] = 'success';
      $json['text'] = 'Договор добавлен';
      $json['redirect'] = '/cab/admin_loans';

      /*$insert = [
        'user_id' => $user_id,
        'contract_id' => $form['counter_contract'],
        'model' => $form['counter_model'],
        'num' => $form['counter_number'],
        'plomb' => $form['counter_plomb'],
        'modem_num' => $form['counter_modem'],
        'install_date' => $form['counter_date_start']
      ];

      if ($db->query("INSERT INTO counters SET ?u", $insert)) {
        $json['status'] = 'success';
        $json['text'] = 'Счетчик добавлен';
        $json['redirect'] = '/cab/admin_edit_contract?id='.$form['counter_contract'];
      } else {
        $json['status'] = 'error';
        $json['text'] = 'Ошибка сохранения, обратитесь в тех. поддержку';
        $json['error_input'] = '';
      }*/



    }

  } else {
    $json['status'] = 'error';
    $json['text'] = 'Ошибка доступа';
  }

  echo json_encode($json);
}
