<?php

if ($user_data['is_admin']) {

  if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $db->query("UPDATE users SET is_del = 1 WHERE id = ?i", $_GET['id']);

    $swal_message = [
      'text' => 'Пользователь удален',
      'type' => 'success'
    ];
  }

  $all_users = $db->getAll("SELECT * FROM users");

  include('tpl/cab/admin_users.tpl');
} else {
  include('tpl/cab/403.tpl');
}
