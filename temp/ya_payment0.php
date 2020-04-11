<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once "../core/db_connect.php";


$order_data['amount'] = '100';
$order_data['user_id'] = '1';


$update_user_sql = "UPDATE users SET balans = balans + '".$order_data['amount']."', total_balance = total_balance + '".$order_data['amount']."' WHERE id = ".$order_data['user_id'];

$logs_sql = "INSERT INTO payment_logs SET type = 'debug', text = '".mysql_real_escape_string($update_user_sql)."'";
//mysql_query();

echo $logs_sql;
//mysql_query("INSERT INTO payment_logs SET type = 'debug', text = '".$update_user_sql."'");
//mysql_query($update_user_sql);


      //mysql_query("UPDATE pre_payments SET status = 1, destanation_order_id = '".$pay_id."' WHERE id = '$pay_order'");

      //mysql_query("INSERT INTO payments SET user = '".$order_data['user_id']."', sum = '".$order_data['amount']."', base = 'Онлайн оплата #".$order_data['id']."'");
