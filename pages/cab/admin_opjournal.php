<?php

if ($user_data['is_admin']) {

  $types = $db->getAll("SELECT * FROM `operations_jornal_types`");

  include('tpl/cab/admin_opjournal.tpl');
} else {
  include('tpl/cab/403.tpl');
}
