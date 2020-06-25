<?php

$membership_balance = $db->getOne("SELECT balance FROM purses WHERE user_id = ?i AND type = 2", $user_data['id']);

$memberchips = $db->getAll("SELECT oj.*, (SELECT name FROM operations_jornal_types WHERE id = oj.op_type) as operation_name FROM operations_jornal oj WHERE oj.balance_type = 2 AND user_id = ?i ORDER BY oj.id DESC", $user_data['id']);

//var_dump($memberchips);

include('tpl/cab/membership.tpl');
