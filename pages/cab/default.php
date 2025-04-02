<?php


$electric_balance = $db->getOne("SELECT balance FROM purses WHERE user_id = ?i AND type = 1", $user_data['id']);
//if ($electric_balance === FALSE) $electric_balance = 0;
$membership_balance = $db->getOne("SELECT balance FROM purses WHERE user_id = ?i AND type = 2", $user_data['id']);
//if ($membership_balance === FALSE) $electric_balance = 0;
$target_balance = $db->getOne("SELECT balance FROM purses WHERE user_id = ?i AND type = 3", $user_data['id']);
//var_dump($target_balance);
//if ($target_balance === FALSE) $electric_balance = 0;
$total_balance = $electric_balance + $membership_balance + $target_balance;


$loans = $db->getOne("SELECT SUM(amount) FROM loans WHERE user_id = ?i", $user_data['id']);
$loans_payout = $db->getOne("SELECT SUM(payment_amount) FROM loans_payments WHERE loan IN (SELECT id FROM loans WHERE user_id = ?i)", $user_data['id']);
$loan_balance = $loans - $loans_payout;


include('tpl/cab/default.tpl');
