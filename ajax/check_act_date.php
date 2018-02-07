<?php
	include_once "../core/db_connect.php";
	
	if (isset($_POST['dateAct']) && isset($_POST['userAct'])) {
		$q = "SELECT * FROM `acts` WHERE `user` = ".$_POST['userAct']." AND `type` = ".$_POST['typeAct']." AND '".$_POST['dateAct']."' BETWEEN `date_start` AND `date_end`";
		//echo $q;
		$result = mysql_query($q) or die(mysql_error());
		if (mysql_num_rows($result) != 0) {
			echo '403';
		}
		else {
			echo '200';
		}
	}