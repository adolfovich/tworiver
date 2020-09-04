<?php


if ($user_data['is_admin']) {

  $rates = $db->getAll("SELECT * FROM `tarifs`");


  $membership_rate = $db->getOne("SELECT data FROM settings WHERE cfgname = 'membership_rate'");
  $membership_rate_period = $db->getOne("SELECT data FROM settings WHERE cfgname = 'membership_period'");


  include('tpl/cab/admin_rates.tpl');
} else {
  include('tpl/cab/403.tpl');
}
