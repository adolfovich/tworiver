<?php

if ($user_data['is_admin']) {

  $contract_user_data = $db->getRow("SELECT * FROM users WHERE id = ?i", $_GET['user_id']);

  if (isset($_POST['saveNewContract'])) {
    if ($_POST['num'] != '') {
      if ($_POST['dateStart'] != '') {
        //добавляем договор

        $insert = [
          'user' => $_POST['user_id'],
          'type' => 1,
          'num' => $_POST['num'],
          'date_start' => $_POST['dateStart']
        ];

        $db->query("INSERT INTO users_contracts SET ?u", $insert);
        $new_contract_id = $db->insertId();
        //переход на договор
        $core->jsredir('admin_edit_contract?id='.$new_contract_id);

      } else {
        $swal_message["type"] = 'error';
        $swal_message["text"] = 'Дата начала договора не может быть пустой';
      }
    } else {
      $swal_message["type"] = 'error';
      $swal_message["text"] = 'Номер договора не может быть пустым';
    }
  }

  include('tpl/cab/admin_new_contract.tpl');
} else {
  include('tpl/cab/403.tpl');
}
