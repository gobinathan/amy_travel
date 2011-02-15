<?php
$order_id=$request[1];
if (is_numeric($order_id)) { 
	$order=@mysql_fetch_array(mysql_query("SELECT * from `transactions` WHERE `order_id`='$order_id'"));
	if ($order[confirmed] !== "1") { 
		mysql_query("UPDATE `transactions` SET `payment_data`='Transaction cancelled by user' WHERE `order_id`='$order_id'");	
	}
}
$t->assign('title', $lang_members['payment_cancelled']);
$error[]=$lang_globals['payment_cancelled'];
$t->assign('error',$error);
$t->assign('error_count',count($error));
$t->display("frontend/$template/payment_error.tpl");
?>