<?php
// Get 2CheckOut variables
$cc_processed=sqlx($_POST['credit_card_processed']);
$message_type=sqlx($_POST['message_type']);
$message_description=sqlx($_POST['message_description']);
$vendor_id=sqlx($_POST['vendor_id']);
$order_id=sqlx($_POST['vendor_order_id']);
$payment_currency=sqlx($_POST['list_currency']);
$payment_status=sqlx($_POST['invoice_status']);
$payment_amount=sqlx($_POST['invoice_list_amount']);
$md5_hash=sqlx($_POST['md5_hash']); // UPPERCASE(MD5_ENCRYPTED(sale_id + vendor_id + invoice_id + Secret Word)); 
if (isset($order_id) AND isset($payment_status) AND $payment_amount>0 AND isset($md5_hash)) {
	$getgw=mysql_query("SELECT * from `payment_gw`");
	$payment_gw=@mysql_fetch_array($getgw);
	$hash_string = $_POST['sale_id'].$vendor_id.$_POST['invoice_id'].$payment_gw['2checkout_secret'];
	$check_key = strtoupper(md5($hash_string));
	$order=@mysql_fetch_array(mysql_query("SELECT * from `orders` WHERE `order_id`='$order_id'"));
	if (is_numeric($order_id)) { mysql_query("UPDATE `orders` SET `status`='$payment_status' WHERE `order_id`='$order_id'"); }
	//check for errors
	if ($cc_processed !== "Y") { $error[]=$lang_errors['payment_not_completed']; }
	if ($payment_status == "declined") { $error[]=$lang_errors['payment_not_completed']; }
	if ($vendor_id !== $payment_gw['2checkout_id'])  { $error[]=$lang_errors['invalid_payment_receiver']; }
	if ($payment_amount !== $order[price]) { $error[]=$lang_errors['invalid_payment_amount']; }
	if ($payment_currency !== $order[currency]) { $error[]=$lang_errors['invalid_payment_currency']; }
	if ($order[confirmed] == "1") { $error[]=$lang_errors['order_already_confirmed']; }
	if ($order[payment_gw] !== "2checkout") { $error[]=$lang_errors['invalid_payment_gateway']; }
	if ($md5_hash!==$check_key) { $error[]="Invalid Order. Trying to break into the system?!"; }
	// process payment
	if (count($error)=="0"){
		if ($settings['2checkout_approve']=="0") {$approved="1";}else{$approved="0";}	
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