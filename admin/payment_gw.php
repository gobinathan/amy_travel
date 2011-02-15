<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");

if (isset($_POST['submit'])) {
	$paypal_enabled=sqlx($_POST['paypal_enabled']);
	if ($paypal_enabled=="on") {$paypal_enabled="1";}else{$paypal_enabled="0";}
	$paypal_id=sqlx($_POST['paypal_id']);
	$paypal_approve=sqlx($_POST['paypal_approve']);
	if ($paypal_approve=="on") {$paypal_approve="1";}else{$paypal_approve="0";}

	$checkout_enabled=sqlx($_POST['2checkout_enabled']);
	if ($checkout_enabled=="on") {$checkout_enabled="1";}else{$checkout_enabled="0";}
	$checkout_id=sqlx($_POST['2checkout_id']);
	$checkout_secret=sqlx($_POST['2checkout_secret']);
	$checkout_approve=sqlx($_POST['2checkout_approve']);
	if ($checkout_approve=="on") {$checkout_approve="1";}else{$checkout_approve="0";}

	$authorize_enabled=sqlx($_POST['authorize_enabled']);
	if ($authorize_enabled=="on") {$authorize_enabled="1";}else{$authorize_enabled="0";}
	$authorize_id=sqlx($_POST['authorize_id']);
	$authorize_key=sqlx($_POST['authorize_key']);
	$authorize_approve=sqlx($_POST['authorize_approve']);
	if ($authorize_approve=="on") {$authorize_approve="1";}else{$authorize_approve="0";}

	$bw_enabled=sqlx($_POST['bw_enabled']);
	if ($bw_enabled=="on") {$bw_enabled="1";}else{$bw_enabled="0";}
	$bw_approve="1";
	$bw_recipient=sqlx($_POST['bw_recipient']);
	$bw_currency=sqlx($_POST['bw_currency']);
	$bw_bank_name=sqlx($_POST['bw_bank_name']);
	$bw_bank_phone=sqlx($_POST['bw_bank_phone']);
	$bw_bank_address1=sqlx($_POST['bw_bank_address1']);
	$bw_bank_address2=sqlx($_POST['bw_bank_address2']);		
	$bw_bank_city=sqlx($_POST['bw_bank_city']);
	$bw_bank_state=sqlx($_POST['bw_bank_state']);
	$bw_bank_zip=sqlx($_POST['bw_bank_zip']);	
	$bw_bank_country=sqlx($_POST['bw_bank_country']);
	$bw_account_number=sqlx($_POST['bw_account_number']);
	$bw_swift_code=sqlx($_POST['bw_swift_code']);
	$bw_iban=sqlx($_POST['bw_iban']);			
	mquery("UPDATE `payment_gw` SET `paypal_enabled`='$paypal_enabled',`paypal_id`='$paypal_id',`paypal_approve`='$paypal_approve',`paypal_subscription_enabled`='$paypal_subscription_enabled',`paypal_subscription_id`='$paypal_subscription_id',`paypal_subscription_approve`='$paypal_subscription_approve',`2checkout_enabled`='$checkout_enabled',`2checkout_id`='$checkout_id',`2checkout_secret`='$checkout_secret',`2checkout_approve`='$checkout_approve',`authorize_enabled`='$authorize_enabled',`authorize_id`='$authorize_id',`authorize_key`='$authorize_key',`authorize_approve`='$authorize_approve',`bw_enabled`='$bw_enabled',`bw_approve`='$bw_approve',`bw_recipient`='$bw_recipient',`bw_currency`='$bw_currency',`bw_bank_name`='$bw_bank_name',`bw_bank_phone`='$bw_bank_phone',`bw_bank_address1`='$bw_bank_address1',`bw_bank_address2`='$bw_bank_address2',`bw_bank_city`='$bw_bank_city',`bw_bank_state`='$bw_bank_state',`bw_bank_zip`='$bw_bank_zip',`bw_bank_country`='$bw_bank_country',`bw_account_number`='$bw_account_number',`bw_swift_code`='$bw_swift_code',`bw_iban`='$bw_iban'");
	set_msg("Payment Settings updated successfuly!");		
	header("Location: $config[base_url]/admin/payment_gw.php");
  	}
else {
	$sql=mquery("SELECT * from `payment_gw`");
	$payment_gw=@mysql_fetch_array($sql);
   	$t->assign('payment_gw',$payment_gw);
	$t->display("admin/payment_gw.tpl");
}
?>