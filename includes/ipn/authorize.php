<?php
// Get Authorize.net variables
$order_id=trim($_POST['x_invoice_num']);
$user_id=sqlx($_POST['x_cust_id']);
$payment_amount=sqlx($_POST['x_amount']);
$payer_email=sqlx($_POST['x_email']);
$md5hash=sqlx($_POST['x_MD5_Hash']);
include("includes/authorizenet_lib.php");
if (isset($order_id) AND $payment_amount>0 AND isset($md5hash)) {
	$getgw=mysql_query("SELECT * from `payment_gw`");
	$payment_gw=@mysql_fetch_array($getgw);
	$check_key = CalculateFP ($payment_gw[authorize_id], $payment_gw[authorize_key], $payment_amount, $order_id, $order[date_added]);
	$order=@mysql_fetch_array(mysql_query("SELECT * from `orders` WHERE `order_id`='$order_id'"));
//	if (is_numeric($order_id)) { mysql_query("UPDATE `orders` SET `status`='$payment_status' WHERE `order_id`='$order_id'"); }
	//check for errors
	if ($payment_amount !== $order[price]) { $error[]=$lang_errors['invalid_payment_amount']; }
	if ($order[confirmed] == "1") { $error[]=$lang_errors['order_already_confirmed']; }
	if ($order[payment_gw] !== "authorize") { $error[]=$lang_errors['invalid_payment_gateway']; }
	if ($md5hash!==$check_key) { $error[]="Invalid Order. Trying to break into the system?!"; }
	// process payment
	if (count($error)=="0"){
		if ($settings['authorize_approve']=="0") {$approved="1";}else{$approved="0";}	
		// Confirm order
		mysql_query("UPDATE `orders` SET `confirmed`='1',`approved`='$approved' WHERE `order_id`='$order_id'");	
		$member=@mysql_fetch_array(mysql_query("SELECT * from `members` WHERE `member_id`='$order[member_id]'"));
		$credit_plan=@mysql_fetch_array(mysql_query("SELECT * from `credit_packs` WHERE `plan_id`='$order[plan_id]'"));
		apply_credit_plan($member[member_id],$order[plan_id]);
		// assign template variables
		$t->assign('member',$member);
		$t->assign('order',$order);
		$t->assign('credit_plan',$credit_plan);
		$t->display("frontend/$template/payment_completed.tpl");
	}else{
	   	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("frontend/$template/payment_error.tpl");
	}
}
?>