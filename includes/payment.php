<?php
$t->assign('title', "Payment");
$lng = $t->get_config_vars(); 

if (isset($_POST['order_id'])) {
	$order_id=sqlx($_POST['order_id']);
	$_SESSION['order_id']=$order_id;
	$payment_gw=sqlx($_POST['payment_gw']);
	// get order info
	$order=mysql_fetch_array(mysql_query("SELECT * from `orders` WHERE `order_id`='$order_id'"));
	if (count($order) > "0") {
		// get payment gateway needed variables
		$getgw=mysql_query("SELECT * from `payment_gw`");
		$p_gw=@mysql_fetch_array($getgw);
	   	$t->assign('payment_gw',$p_gw);
	   	$t->assign('order',$order);
		// START "PAYPAL GATEWAY"
		if ($payment_gw=="paypal")	{
			mysql_query("UPDATE `orders` SET `payment_gw`='paypal' WHERE `order_id`='$order_id'");			
			$t->display("frontend/$template/payment_paypal.tpl");
		}
		// EOF "PAYPAL GATEWAY"		
		// START "PAYPAL SUBSCRIPTION GATEWAY"
		if ($payment_gw=="paypal_subscription")	{
			mysql_query("UPDATE `orders` SET `payment_gw`='paypal_subscription' WHERE `order_id`='$order_id'");			
			$t->display("frontend/$template/payment_paypal_subscription.tpl");
		}
		// EOF "PAYPAL SUBSCRIPTION GATEWAY"		
		// START "2CHECKOUT GATEWAY"
		if ($payment_gw=="2checkout")	{
			mysql_query("UPDATE `orders` SET `payment_gw`='2checkout' WHERE `order_id`='$order_id'");			
			$t->display("frontend/$template/payment_2checkout.tpl");
		}
		// EOF "2CHECKOUT GATEWAY"		
		// START "AUTHORIZE.NET GATEWAY"
		if ($payment_gw=="authorize")	{
			mysql_query("UPDATE `orders` SET `payment_gw`='authorize' WHERE `order_id`='$order_id'");			
			$t->display("frontend/$template/payment_2checkout.tpl");
		}
		// EOF "AUTHORIZE.NET GATEWAY"		
	}else{
		$error[]=$lang_errors['invalid_order_id'];
	   	$t->assign('error',$error);
		$t->assign('error_count',count($error));	  
	}
}
?>