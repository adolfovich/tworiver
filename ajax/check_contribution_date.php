<?php
include_once "../core/db_connect.php";

if (isset($_POST['type']) && isset($_POST['date']) && isset($_POST['user'])) {
	$result_contribution_check = mysql_query("SELECT * FROM acts WHERE type = ".$_POST['type']." AND user = ".$_POST['user']." AND date_end >= '".$_POST['date']."'") or die(mysql_error());
	if (mysql_num_rows($result_contribution_check) != 0) {
		echo 'check FAIL';
		//echo "SELECT * FROM acts WHERE type = ".$_POST['type']." AND user = ".$_POST['user']." AND date > '".$_POST['date']."'";
	}
	else {
		echo 'check OK';
		//echo "SELECT * FROM acts WHERE type = ".$_POST['type']." AND user = ".$_POST['user']." AND date > '".$_POST['date']."'";
	}
}