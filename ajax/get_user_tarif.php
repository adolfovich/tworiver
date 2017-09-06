<?php
include_once "../core/db_connect.php";
if (isset($_GET['tarif'])) {
	$result_user_tarif = mysql_query("SELECT price FROM `tarifs` WHERE id = ". $_GET['tarif']) or die(mysql_error());
	echo mysql_result($result_user_tarif, 0);
}
?>