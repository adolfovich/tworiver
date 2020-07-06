<?php

$electric_debt = $db->getOne("SELECT SUM(balance) FROM purses WHERE balance < 0 AND type = 1");
$membership_debt = $db->getOne("SELECT SUM(balance) FROM purses WHERE balance < 0 AND type = 2");
$target_debt = $db->getOne("SELECT SUM(balance) FROM purses WHERE balance < 0 AND type = 3");

$electric_debtors = $db->getAll("SELECT p.*, (SELECT name FROM users WHERE id = p.user_id) as name, (SELECT uchastok FROM users WHERE id = p.user_id) as uchastok FROM purses p WHERE p.balance < 0 AND p.type = 1 ORDER BY p.balance");
$membership_debtors = $db->getAll("SELECT p.*, (SELECT name FROM users WHERE id = p.user_id) as name, (SELECT uchastok FROM users WHERE id = p.user_id) as uchastok FROM purses p WHERE p.balance < 0 AND p.type = 2 ORDER BY p.balance");
$target_debtors = $db->getAll("SELECT p.*, (SELECT name FROM users WHERE id = p.user_id) as name, (SELECT uchastok FROM users WHERE id = p.user_id) as uchastok FROM purses p WHERE p.balance < 0 AND p.type = 3 ORDER BY p.balance");


include('tpl/cab/admin.tpl');
