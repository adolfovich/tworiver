<?php


if ($user_data['is_admin']) {

  $membership_debt = $db->getOne("SELECT SUM(balance) FROM purses WHERE balance < 0 AND type = 2");
  $target_debt = $db->getOne("SELECT SUM(balance) FROM purses WHERE balance < 0 AND type = 3");



  include('tpl/cab/admin_contributions.tpl');
} else {
  include('tpl/cab/403.tpl');
}
