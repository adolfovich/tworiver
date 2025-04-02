<?php


if ($user_data['is_admin']) {

  $loans_sum = $db->getOne("SELECT SUM(amount) FROM loans");

  include('tpl/cab/admin_loans.tpl');
} else {
  include('tpl/cab/403.tpl');
}
