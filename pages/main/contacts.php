<?php

$contacts = $db->getAll("SELECT * FROM contacts");

$requisites = $db->getRow("SELECT * FROM requisites");
$r_name = $requisites['name'];
$r_addres = $requisites['addres'];
$r_addres_post = $requisites['addres_post'];
$r_inn = $requisites['inn'];
$r_kpp = $requisites['kpp'];
$r_bank_name = $requisites['bank_name'];
$r_bank_bik = $requisites['bank_bik'];
$r_bank_ks = $requisites['bank_ks'];
$r_bank_rs = $requisites['bank_rs'];

if (isset($_POST['send_email'])) {
  $sender = $_POST['input_name'];
  $subject = $_POST['input_subject'];
  $text = $_POST['input_text'];
  $email = $_POST['input_email'];
}
else {
  $sender = $user_name;
  $subject = '';
  $text = '';
  $email = '';
}


include ('tpl/main/contacts.tpl');
