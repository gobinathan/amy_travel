<?php
$lng = $t->get_config_vars(); 

$order_id=$_SESSION['order_id'];
unset($_SESSION['order_id']);
$order=@mysql_fetch_array(mysql_query("SELECT * from `transactions` WHERE `order_id`='$order_id'"));
$getgw=mysql_query("SELECT * from `payment_gw`");
$payment_gw=@mysql_fetch_array($getgw);
//check for errors
//if ($order['status'] !== "Completed") { $error[]=$lang_errors['payment_not_completed']; }
if ($order['confirmed_by_gw'] == '0') { $error[]=$lang_members['payment_not_confirmed']; }
//if ($order['approved'] == '0') { $error[]=$lang_members['payment_not_approved']; }
// process payment
if (count($error)=="0") {
	unset($_SESSION['res']); // unset booking
	// assign template variables
	$t->assign('order',$order);
	$t->assign('title', $lang_members['payment_completed']);
	$t->display("frontend/$template/payment_completed.tpl");
}else{
   	$t->assign('error',$error);
	$t->assign('error_count',count($error));
	$t->assign('title', $lang_members['payment_error']);
	$t->display("frontend/$template/payment_error.tpl");
}
?>