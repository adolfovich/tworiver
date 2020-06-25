<?php
include('connect.php');

if (isset($_SESSION['id'])) {
  $amount = $db->getOne("SELECT balance FROM purses WHERE type = 2 AND user_id = ?i", $_SESSION['id']);
  //var_dump($amount);
  if ($amount >= 0) {
    $amount = '';
  } else {
    $amount = -$amount;
  }

  $json = [];
  $json['html'] = '';

  $json['header'] = 'Квитанция для оплаты членских взносов';
  $json['html'] .= '<form>';
  $json['html'] .= '<div class="form-group">';
  $json['html'] .= '<label for="electricAmount">Сумма оплаты:</label>';
  $json['html'] .= '<input type="number" name="numbers" class="form-control" id="electricAmount" aria-describedby="amountHelp" value="'.$amount.'">';
  $json['html'] .= '<small id="amountHelp" class="form-text text-muted"></small>';
  $json['html'] .= '</div>';
  $json['html'] .= '<div class="text-right">';
  $json['html'] .= '<button class="btn btn-success" onClick="window.open(\'/pages/cab/forms/?pay_membership=\'+document.getElementById(\'electricAmount\').value, \'_blank\');" >Распечатать</button>';
  $json['html'] .= '<button class="btn btn-outline-secondary ml-2" data-dismiss="modal" aria-label="Close">Закрыть</button>';
  $json['html'] .= '</div>';
  $json['html'] .= '</form>';


  $json['html'] .= '<script>

  </script>';

  echo json_encode($json);
}


//
