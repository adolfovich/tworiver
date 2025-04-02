<?php


if ($user_data['is_admin']) {

  $loan = $db->getRow("SELECT * FROM loans WHERE id = ?i", $_GET['id']);
  
  $loan_payments = $db->getAll("SELECT * FROM loans_payments WHERE loan = ?i", $loan['id']);
  
  $loan_balance = $loan['amount'] - $db->getOne("SELECT SUM(payment_amount) FROM loans_payments WHERE loan = ?i", $loan['id']);

  include('tpl/cab/admin_loan.tpl');
} else {
  include('tpl/cab/403.tpl');
}