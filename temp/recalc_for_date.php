<?php

https://xn----dtbffa7byadkn0c6c.xn--p1ai/temp/recalc_for_date.php?start_date=2024-01-09

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include ('../_conf.php');
include ('../classes/safemysql.class.php');
$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));


 if (isset($_GET['start_date'])) {
	
	var_dump($_GET['start_date']);
	echo '<br>';
	//delete indications 
	
	$delsql = "DELETE FROM Indications WHERE date >= '".date("Y-m-d", strtotime($_GET['start_date']))."' ";
	
	var_dump($delsql);
	echo '<br>';
	
	$db->query($delsql);
	
	
	//recalc balances
	
	$journal = $db->getAll("SELECT * FROM operations_jornal WHERE date >= ?s AND op_type = 5", date("Y-m-d", strtotime($_GET['start_date']) + 86400));
	
	foreach ($journal as $journal_str) {
		var_dump($journal_str);
		echo '<br>';
		
		$db->query("UPDATE purses SET balance = balance + ?s WHERE user_id = ?i AND type = 1", abs($journal_str['amount']), $journal_str['user_id']);		
		$db->query("DELETE FROM operations_jornal WHERE id = ?i", $journal_str['id']);
	}
	
} else {
?>
<form method="GET">
	<span>Start date</span>
	<input name="start_date" type="date" />
	<input type="submit" />
</form>
<?php
}
?>



