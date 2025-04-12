<?php
/*
+---------------------------------------------------------------------------+
| SNT-control v 2.0.1                                                       |
| ============                                                              |
| Copyright (c) by Alexandr Doroshenko                                      |
| For contact details:                                                      |
| adolfovich@list.ru                                                        |
|	                                                                          |
| PHP7.2 & MYSQL5.8                                                         |
+---------------------------------------------------------------------------+
*/
session_start();

/*
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/


include ('_conf.php');
include ('classes/safemysql.class.php');
$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

require_once('classes/core.class.php');

$core  = new Core();

$url = $core->url;
$form = $core->form;
$ip = $core->ip;
$get = $core->setGet();

if (isset($_SESSION['id'])) {
  $user_id = $_SESSION['id'];
  $user_info = $db->getRow("SELECT * FROM users WHERE id = ?i", $user_id);
}

$month_name = array(
  1 => 'января',
  2 => 'февраля',
  3 => 'марта',
  4 => 'апреля',
  5 => 'мая',
  6 => 'июня',
  7 => 'июля',
  8 => 'августа',
  9 => 'сентября',
  10 => 'октября',
  11 => 'ноября',
  12 => 'декабря'
);

$curdate = date("Y-m-d");

require_once('pages/controller.php');
