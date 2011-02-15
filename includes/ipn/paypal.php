<?php

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

$fp = @fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);



if (!$fp) {
// HTTP ERROR
} else {
fputs ($fp, $header . $req);
while (!feof($fp)) {
$res = fgets ($fp, 1024);
if (strcmp ($res, "VERIFIED") == 0) {
// check the payment_status is Completed
// check that txn_id has not been previously processed
// check that receiver_email is your Primary PayPal email
// check that payment_amount/payment_currency are correct
// get payment gateway needed variables
$payment_status=sqlx($_POST['payment_status']);
$receiver_email=sqlx($_POST['receiver_email']);
$payment_amount=sqlx($_POST['payment_amount']);
if (empty($payment_amount)) {
	$payment_amount=sqlx($_POST['mc_gross']);
}
$payment_currency=sqlx($_POST['mc_currency']);
$order_id=sqlx($_POST['option_selection1']);
$order=@mysql_fetch_array(mysql_query("SELECT * from `transactions` WHERE `order_id`='$order_id'"));
$getgw=mysql_query("SELECT * from `payment_gw`");
$payment_gw=@mysql_fetch_array($getgw);
if (is_numeric($order_id)) { mysql_query("UPDATE `transactions` SET `status`='$payment_status' WHERE `order_id`='$order_id'"); }
//check for errors
if ($payment_status !== "Completed") { $error[]=$lang_errors['payment_not_completed']; }
if ($receiver_email !== $payment_gw['paypal_id'])  { $error[]=$lang_errors['invalid_payment_receiver']; }
if ($payment_amount !== $order['total_amount']) { $error[]=$lang_errors['invalid_payment_amount']; }
if ($payment_currency !== $order['currency']) { $error[]=$lang_errors['invalid_payment_currency']; }
if ($order[confirmed] == "1") { $error[]=$lang_errors['order_already_confirmed']; }
if ($order[payment_gw] !== "paypal") { $error[]=$lang_errors['invalid_payment_gateway']; }
// process payment
if (count($error)=="0"){
	if ($settings['paypal_approve']=="0") {$approved="1";}else{$approved="0";}	
	// Confirm order
	mysql_query("UPDATE `transactions` SET `confirmed_by_gw`='1',`approved_by_admin`='$approved',`payment_data`='$res' WHERE `order_id`='$order_id'");	
	$member=@mysql_fetch_array(mysql_query("SELECT * from `members` WHERE `member_id`='$order[member_id]'"));
	// assign template variables
		//  ---------- Send email to Notify Member -------------		
		// Parse Email Template
		$tpl_email = & new_smarty();
	    $tpl_email->force_compile = true;
		$t->register_resource("email", array("email_get_template",
                                       "email_get_timestamp",
                                       "email_get_secure",
                                       "email_get_trusted"));
		$t->register_resource("email_subject", array("email_subject_get_template",
                                       "email_subject_get_timestamp",
                                       "email_subject_get_secure",
                                       "email_subject_get_trusted"));  
		// assign additional template variables
	$tpl_email->assign('member',$member);
	$tpl_email->assign('order',$order);
		$subject = $tpl_email->fetch("email_subject:payment_completed");
		$email_message = $tpl_email->fetch("email:payment_completed");
		// Get member_register from email
		$gettpl=mysql_query("SELECT `from_email` from `email_templates` WHERE `tpl_name`='payment_completed'");
		$from_email=@mysql_result($gettpl,0,'from_email');
		$headers = "From: $conf[system_name] <$from_email>";
		// assign additional template variables
		// Send E-Mail
		if ($conf[use_smtp_mail] == "1") {
			mail_smtp($member[email], $subject, $email_message);
		}else {
			mail($member[email], $subject, $email_message, $headers);
		}
		// EOF ---------- Send email to New Member -------------		
}else{
//   	$t->assign('error',$error);
//	$t->assign('error_count',count($error));
//	$t->display("frontend/$template/payment_error.tpl");
}

// echo the response
//echo "The response from IPN was: <b>" .$res ."</b><br><br>";

//loop through the $_POST array and print all vars to the screen.

foreach($_POST as $key => $value){
//        echo $key." = ". $value."<br>";
}


}
else if (strcmp ($res, "INVALID") == 0) {
// log for manual investigation

// echo the response
//echo "The response from IPN was: <b>" .$res ."</b>";

  }

}
fclose ($fp);
}
?>