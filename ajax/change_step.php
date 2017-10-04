<?php
	include_once "../core/db_connect.php";
	if (isset($_GET['step']) && isset($_GET['user'])) {
		if ($_GET['step'] < 2) {
			mysql_query("UPDATE users SET sch_step = ".$_GET['step']." WHERE id =".$_GET['user']) or die(mysql_error());
		}
		else if ($_GET['step'] == 3) {
			mysql_query("UPDATE users SET sch_step = 0 WHERE id =".$_GET['user']) or die(mysql_error());
		}
	}

?>