<?php

if ($user_data['is_admin']) {

  if (isset($_GET['action']) && $_GET['action'] == 'save_settings') {
    foreach ($form as $s_key => $s_value) {
      $db->query("UPDATE settings SET data = ?s WHERE cfgname = ?s", $s_value, $s_key);
    }

    $swal_message = [
      'text' => 'Настройки сохранены',
      'type' => 'success'
    ];
  }

  $all_settings = $db->getAll("SELECT * FROM settings");

  include('tpl/cab/admin_settings.tpl');
} else {
  include('tpl/cab/403.tpl');
}
