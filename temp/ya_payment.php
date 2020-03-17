<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once "../core/db_connect.php";


function getIp() {
  $keys = [
    'HTTP_CLIENT_IP',
    'HTTP_X_FORWARDED_FOR',
    'REMOTE_ADDR'
  ];
  foreach ($keys as $key) {
    if (!empty($_SERVER[$key])) {
      $ip = trim(end(explode(',', $_SERVER[$key])));
      if (filter_var($ip, FILTER_VALIDATE_IP)) {
        return $ip;
      }
    }
  }
}

$ip = getIp();

$request = 'ip => '.$ip.', ';

$source = file_get_contents('php://input');
mysql_query("INSERT INTO payment_logs SET type = 'debug', text = '$source'") or die(mysql_error());
$requestBody = json_decode($source, true);


if (isset($_GET) && $_GET) {
  $request .= 'type => GET, ';
  var_dump('$_GET ');
  var_dump($_GET);
}

if (isset($_POST) && $_POST) {
  $request .= 'type => POST, ';
  var_dump('$_POST ');
  var_dump($_POST);
}

if (isset($request)) {
  foreach ($_REQUEST as $key => $value) {
    $request .= $key.' => '.$value.", ";
  }

  mysql_query("INSERT INTO payment_logs SET type = 'debug', text = '$request'") or die(mysql_error());
}



/*if (isset($_REQUEST)) {
  $fd = fopen("log.txt", 'w') or die("не удалось создать файл");
  //var_dump($fd);
  foreach ($_REQUEST as $key => $value) {
    fwrite($fd, $key.' => '.$value."\r\n");
  }
  fclose($fd);
}
*/
