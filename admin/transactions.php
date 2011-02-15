<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");

if (isset($_GET['delete'])) {
	$order_id=sqlx($_GET['delete']);
	$check_c_exists=mquery("SELECT * from `transactions` WHERE `order_id`='$order_id'");
	if (@mysql_num_rows($check_c_exists)==0) { $error[]=$lang_errors['invalid_order_id']; }
	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("DELETE from `transactions` WHERE `order_id`='$order_id'");
		set_msg("Order ID <b>$order_id</b> deleted successfuly!");		
		echo "<html><head></head><body onload=\"";
		echo "opener.document.location.href='transactions.php';window.close();";
		echo "\"></body></html>";
//		header("Location: $config[base_url]/admin/transactions.php");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/transactions.tpl");
	}
}
elseif (isset($_GET['approve'])) {
	$order_id=sqlx($_GET['approve']);
	mquery("UPDATE `transactions` SET `approved_by_admin`='1' WHERE `order_id`='$order_id'");
	$order=@mysql_fetch_array(mquery("SELECT * from `transactions` WHERE `order_id`='$order_id'"));	
//	$member=@mysql_fetch_array(mquery("SELECT * from `members` WHERE `member_id`='$order[member_id]'"));
		//  ---------- Send email to Member -------------		
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
		$tpl_email->assign('member',$member);
		$tpl_email->assign('order',$order);
		$tpl_email->assign('credit_plan',$credit_plan);
		$subject = $tpl_email->fetch("email_subject:payment_approved");
		$email_message = $tpl_email->fetch("email:payment_approved");
		// Get member_register from email
		$gettpl=mquery("SELECT `from_email` from `email_templates` WHERE `tpl_name`='payment_approved'");
		$from_email=@mysql_result($gettpl,0,'from_email');
		// assign additional template variables
		// Send E-Mail
		send_mail($member[email],"$conf[system_name] <$from_email>",$subject,$email_message);
		set_msg("Order ID <b>$order_id</b> approved and notification email sent successfuly to $member[email] !");		
		// EOF ---------- Send email to New Member -------------		
	header("Location: $config[base_url]/admin/transactions.php");
}
elseif (isset($_GET['unapprove'])) {
	$order_id=sqlx($_GET['unapprove']);
	$check_c_exists=mquery("SELECT * from `transactions` WHERE `order_id`='$order_id'");
	if (@mysql_num_rows($check_c_exists)==0) { $error[]=$lang_errors['invalid_order_id']; }
	// If no errors...continue
	if (count($error)=="0")	{
		mquery("UPDATE `transactions` SET `approved_by_admin`='0' WHERE `order_id`='$order_id'");
		set_msg("Order ID <b>$order_id</b> is now Not approved!");		
		header("Location: $config[base_url]/admin/transactions.php");
	}else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/transactions.tpl");
	}	
}
elseif (isset($_GET['confirm'])) {
	$order_id=sqlx($_GET['confirm']);
	mquery("UPDATE `transactions` SET `confirmed_by_gw`='1' WHERE `order_id`='$order_id'");
	set_msg("Order ID <b>$order_id</b> is now confirmed!");		
	header("Location: $config[base_url]/admin/transactions.php");
}
elseif (isset($_GET['unconfirm'])) {
	$order_id=sqlx($_GET['unconfirm']);
	$check_c_exists=mquery("SELECT * from `transactions` WHERE `order_id`='$order_id'");
	if (@mysql_num_rows($check_c_exists)==0) { $error[]=$lang_errors['invalid_order_id']; }
	// If no errors...continue
	if (count($error)=="0")	{
		mquery("UPDATE `transactions` SET `confirmed_by_gw`='0' WHERE `order_id`='$order_id'");
		set_msg("Order ID <b>$order_id</b> is now unconfirmed!");		
		header("Location: $config[base_url]/admin/transactions.php");
	}else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/transactions.tpl");
	}	
}
elseif (isset($_GET['details'])) {
	$order_id=sqlx($_GET['details']);
	$getr=mysql_query("SELECT * from `transactions` WHERE `order_id`='$order_id'");
	$order=@mysql_fetch_array($getr);
	$order['booking']=fetch_booking($order['booking_id']);
   	$t->assign('order',$order);
	$t->display("admin/transaction_details.tpl");  
}
elseif (isset($_GET['member'])) {
	$member_id=sqlx($_GET['member']);
	$sql=mquery("SELECT * from `orders` WHERE `member_id`='$member_id' ORDER BY `approved`,`confirmed` DESC");
	$orders=array();
	while($order=@mysql_fetch_array($sql)) {
		// get member details
		$member=@mysql_fetch_array(mquery("SELECT * from `members` WHERE `member_id`='$order[member_id]'"));
		$order['member']=$member;
		// get credit plan details
		$credit_plan=@mysql_fetch_array(mquery("SELECT * from `credit_packs` WHERE `plan_id`='$order[plan_id]'"));
		$order['plan']=$credit_plan;
		if ($order['currency']!==$conf[currency]) {
			$order['price']=convert_currency ($order[currency], $conf['currency'], $order[price]);
		}
		if ($order['confirmed']=="1" AND $order['approved']=="1"){$closed_sales=$closed_sales+$order['price'];}
		$total_sales=$total_sales+$order['price'];
		array_push($orders, $order);
	}
   	$t->assign('closed_sales',$closed_sales);
   	$t->assign('total_sales',$total_sales);
   	$t->assign('orders',$orders);
	$t->display("admin/transactions.tpl");	
}
else {
	// FILTER
	if (isset($_REQUEST['filter'])) {
		$filter_cpack=sqlx($_REQUEST['filter_cpack']);
		$filter_gw=sqlx($_REQUEST['filter_gw']);
		$filter_status=sqlx($_REQUEST['filter_status']);
		$start_date=sqlx($_POST['start_date']);
		if ($start_date=="From Date" OR empty($start_date)) { $start_date = "0"; }
		else {
		  	$start_date_str=explode('/',$start_date);
		  	$start_date=mktime(0, 0, 0, $start_date_str[1], $start_date_str[0], $start_date_str[2]);
		}
		$end_date=sqlx($_POST['end_date']);
		if ($end_date=="To Date" OR empty($end_date)) { $end_date = "0"; }
		else {
		  $end_date_str=explode('/',$end_date);
		  $end_date=mktime(0, 0, 0, $end_date_str[1], $end_date_str[0], $end_date_str[2]);
		}  
		if (is_numeric($filter_cpack)) { $query .= "AND `plan_id`='$filter_cpack' "; }		
		if (!empty($filter_gw)) { $query .= "AND `payment_gw` LIKE '%$filter_gw%' "; }		
		if (!empty($filter_status)) { 
			if ($filter_status == "confirmed") { $query.= "AND `confirmed_by_gw`='1' "; }
			if ($filter_status == "approved") { $query.= "AND `approved_by_admin`='1' "; }
			if ($filter_status == "unconfirmed") { $query.= "AND `confirmed_by_gw`='0' "; }
			if ($filter_status == "unapproved") { $query.= "AND `approved_by_admin`='0' "; }
		}		
		if ($start_date!=="0") {
			$query.= "AND `date_added`>='$start_date' ";
		}
		if ($end_date!=="0") {
			$query.= "AND `date_added`<='$end_date' ";
		}
	}

	$sql=mquery("SELECT * from `transactions` WHERE `order_id`>'0' $query ORDER BY `approved_by_admin`,`confirmed_by_gw` DESC");
	$orders=array();
	while($order=@mysql_fetch_array($sql)) {
		$order['booking']=fetch_booking($order['booking_id']);
		if ($order['currency']!==$conf[currency]) {
			$order['total_amount']=convert_currency ($order['currency'], $conf['currency'], $order['total_amount']);
		}
		if ($order['confirmed_by_gw']=="1" AND $order['approved_by_admin']=="1"){$closed_sales=$closed_sales+$order['total_amount'];}
		$total_sales=$total_sales+$order['total_amount'];
		array_push($orders, $order);
	}
   	$t->assign('closed_sales',$closed_sales);
   	$t->assign('total_sales',$total_sales);
   	$t->assign('orders',$orders);
	$t->display("admin/transactions.tpl");
}
?>