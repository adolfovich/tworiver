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

$request .= 'ip => '.$ip.', ';

if (isset($_GET)) {
  $request .= 'type => GET, ';
} else if (isset($_POST)) {
  $request. = 'type => POST, ';
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
