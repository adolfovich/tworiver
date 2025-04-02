<?php

include('connect.php');

if (isset($_SESSION['id'])) {

  $is_admin = $db->getOne("SELECT is_admin FROM users WHERE id = ?i", $_SESSION['id']);

  $json = [];

  if ($is_admin) {
    $json['html'] = '';

    /*if ($_GET['type'] == 'target') {
      $type = 'целевого';
      $type_id = 3;
    } else if ( 'membership') {
      $type = 'членского';
      $type_id = 2;
    }*/

    $json['header'] = 'Договор займа';
    $json['html'] .= '<form id="add_loan">';

    

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="loan_user">Пользователь:</label>';
    $json['html'] .= '<select name="loan_user" class="form-control forcheck" id="loan_user" aria-describedby="contribution_userHelp">';
    $json['html'] .= '<option value="0" selected disabled>Выберите пользователя</option>';
    $users = $db->getAll("SELECT * FROM users WHERE is_del = 0 ORDER BY uchastok");
    foreach ($users as $key => $value) {
      $users_arr[$value['id']] = $value['uchastok'];
    }
    natsort($users_arr);
    foreach ($users_arr as  $user_id => $user_area) {
        $user = $db->getRow("SELECT * FROM users WHERE id = ?i", $user_id);
        $json['html'] .= '<option value="'.$user['id'].'">уч. - '.$user['uchastok'].' '.$user['name'].'</option>';
    }
    $json['html'] .= '</select>';
    $json['html'] .= '</div>';
	
	$json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="loan_agreement_num">Номер договора:</label>';
    $json['html'] .= '<input type="text" name="loan_agreement_num" class="form-control forcheck" id="loan_agreement_num" aria-describedby="numbertHelp">';
    $json['html'] .= '</div>';
	
	$json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="loan_agreement_date">Дата договора:</label>';
    $json['html'] .= '<input type="date" name="loan_agreement_date" class="form-control forcheck" id="loan_agreement_date" aria-describedby="numbertHelp">';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="loan_amount">Сумма займа:</label>';
    $json['html'] .= '<input type="number" name="loan_amount" class="form-control forcheck" id="loan_amount" aria-describedby="numbertHelp">';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="form-group">';
    $json['html'] .= '<label for="loan_comment">Комментарий:</label>';
    $json['html'] .= '<input type="text" name="loan_comment" class="form-control forcheck" id="loan_comment" aria-describedby="plombtHelp">';
    $json['html'] .= '</div>';

    $json['html'] .= '<div class="text-right">';
    $json['html'] .= '<a href="#" class="btn btn-primary" onClick="addLoan(); return false;">Сохранить</a>';
    $json['html'] .= '<button class="btn btn-outline-secondary ml-2" data-dismiss="modal" aria-label="Close">Закрыть</button>';
    $json['html'] .= '</div>';

    $json['html'] .= '</form>';
  } else {
    $json['header'] = 'Ошибка';
    $json['html'] .= '<form>';
    $json['html'] .= '<h4>Доступ запрещен</h4>';
    $json['html'] .= '<div class="text-right">';
    $json['html'] .= '<button class="btn btn-outline-secondary ml-2" data-dismiss="modal" aria-label="Close">Закрыть</button>';
    $json['html'] .= '</div>';
    $json['html'] .= '</form>';
  }

  echo json_encode($json);
}
