<?php

$db_host = '127.0.0.1';
$bd_user = 'root';
$db_pass = '12881988';
$db_name = 'snt_test';

$link = mysql_connect($db_host, $bd_user, $db_pass)
	or die('Не удалось соединиться: ' . mysql_error());
//echo 'Соединение успешно установлено';
mysql_select_db($db_name) or die('Не удалось выбрать базу данных');

mysql_query("SET NAMES 'utf8'");

?>