<?php

$loans = $db->getOne("SELECT SUM(amount) FROM loans WHERE user_id = ?i", $user_data['id']);
$loans_payout = $db->getOne("SELECT SUM(payment_amount) FROM loans_payments WHERE loan IN (SELECT id FROM loans WHERE user_id = ?i)", $user_data['id']);
$loan_balance = $loans - $loans_payout;

$loans_list = $db->getAll("SELECT lo.*, (SELECT SUM(payment_amount) FROM loans_payments WHERE loan = lo.id) as payouts FROM loans lo WHERE lo.user_id = ?i", $user_data['id']);

include('tpl/cab/loans.tpl');
