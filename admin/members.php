<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");
if ($gd=="yes") {include("../includes/thumb.class.php");}

if (isset($_GET['add'])) {
	$t->display("admin/add_member.tpl");
}
elseif (isset($_POST['add_member'])) {
  $password=sqlx($_POST['password']);
  $email=sqlx($_POST['email']);
  $fullname=sqlx($_POST['fullname']);

  $added_time=time();
  if ($_FILES['picture']['size']) { // Add Image submit
	$upload_dir = "../uploads/member_photos/";
	$uploaded = do_upload($upload_dir,"picture","$added_time");
	$uploaded=substr($uploaded, 1);
	$image=after_last('/',$uploaded);
	// Resize Image
	if ($gd=="yes") {
		$tm = new dThumbMaker; 
		$load = $tm->loadFile($upload_dir.$image);
		if($load === true){ // Note three '='      
		    $tm->resizeMaxSize($conf[thumb_resize_h], $conf[thumb_resize_w]); 
//			$tm->addWaterMark('images/watermark.gif', 64, 64, true);
		    $tm->build($upload_dir.$image); 
		}
	  }
	//EOF Resize Image
	}else {
		$image="default.gif";
	}
      
	// Check for errors
  	if (empty($password)) { $error[]=$lang_errors['empty_password']; }
	if (empty($email)) { $error[]=$lang_errors['empty_email']; }
	if (!is_email($email)) { $error[]=$lang_errors['invalid_email']; }
	  	
	$check_user_exists=mquery("SELECT * from `members` WHERE `username`='$username'");
	if (@mysql_num_rows($check_user_exists)>0) { $error[]=$lang_errors['register_username_exists']; }

	// If no errors...continue
	if (count($error)=="0")	{
		$last_update=time();
		$passwd=cryptPass("$password","$username");
		echo $username;
		exit;
    	mquery("INSERT into `members` values ('$email','$passwd','never','1','1','$last_update','$fullname','$image')") or die(mysql_error());
		set_msg("Member <b>$email</b> added successfuly.");		
		header("Location: $config[base_url]/admin/members.php");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/add_member.tpl");		
	}
}

elseif (isset($_GET['edit'])) {
	$user_id=sqlx($_GET['edit']);
	$sql=mquery("SELECT * from `members` WHERE `email`='$user_id'");
	if (@mysql_num_rows($sql)=="0") { $error[]=$lang_errors['invalid_member']; }
	if (count($error)=="0") {
		$user=@mysql_fetch_array($sql);
		$member_langs=explode("|",$user[access_langs]);
		$t->assign('member_langs',$member_langs);
	   	$t->assign('member',$user);
		$sql=mquery("SELECT * from `transactions` WHERE `email`='$user_id' ORDER BY `approved`,`confirmed` DESC");
		$orders=array();
		while($order=@mysql_fetch_array($sql)) {
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
		$t->display("admin/edit_member.tpl");
	} else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}	
}
elseif (isset($_POST['edit_member'])) {
  $email=sqlx($_POST['edit_member']);
  $password=sqlx($_POST['password']);
  $email=sqlx($_POST['email']);
  $fullname=sqlx($_POST['fullname']);
  $email_confirmed=sqlx($_POST['email_confirmed']);
  if ($email_confirmed=="on") {$email_confirmed="1";}else{$email_confirmed="0";}
  $approved=sqlx($_POST['approved']);
  if ($approved=="on") {$approved="1";}else{$approved="0";}

  $added_time=time();
  if ($_FILES['picture']['size']) { // Add Image submit
	$upload_dir = "../uploads/member_photos/";
	$uploaded = do_upload($upload_dir,"picture","$added_time");
	$uploaded=substr($uploaded, 1);
	$image=after_last('/',$uploaded);
	// Resize Image
	if ($gd=="yes" AND $conf[resize_member_photos]=="1") {
		$tm = new dThumbMaker; 
		$load = $tm->loadFile($upload_dir.$image);
		if($load === true){ // Note three '='      
		    $tm->resizeMaxSize($conf[member_resize_h], $conf[member_resize_w]); 
//			$tm->addWaterMark('images/watermark.gif', 64, 64, true);
		    $tm->build($upload_dir.$image); 
		}
	  }
	//EOF Resize Image
	}
      
	// Check for errors
//  	if (empty($password)) { $error[]=$lang_errors['empty_password']; }
	if (empty($email)) { $error[]=$lang_errors['empty_email']; }
	if (!is_email($email)) { $error[]=$lang_errors['invalid_email']; }
	  	
	$check_user_exists=mquery("SELECT * from `members` WHERE `email`='$email'");
	if (@mysql_num_rows($check_user_exists)==0) { $error[]=$lang_errors['invalid_member']; }
	if (!empty($image)) {
		$member_old_picture=@mysql_result($check_user_exists,0,"picture");
		if ($member_old_picture !== "default.gif") {
			@del_file("$upload_dir/$member_old_picture");
		}
		mquery("UPDATE `members` SET `picture`='$image' WHERE `email`='$email'");
	}
	// If no errors...continue
	if (count($error)=="0")	{
	  	$last_update=time();
		mquery("UPDATE `members` SET `email`='$email',`fullname`='$fullname',`email_confirmed`='$email_confirmed',`approved`='$approved' WHERE `email`='$email'");
		if (!empty($password)) {
			$passwd=cryptPass("$password","$email");
			mquery("UPDATE `members` SET `password`='$passwd' WHERE `email`='$email'");
		}
		set_msg("Member <b>$email</b> updated successfuly.");		
		header("Location: $config[base_url]/admin/members.php");
  	}else{ // Else Show errors
		$sql=mquery("SELECT * from `members` WHERE `email`='$email'");
		$user=@mysql_fetch_array($sql);
		$member_langs=explode("|",$user[access_langs]);
		$t->assign('member_langs',$member_langs);
	   	$t->assign('member',$user);
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/edit_member.tpl");		
	}
}
// START "member DELETE CONFIRM"
elseif (isset($_GET['delete'])) {
	$user_id=sqlx($_GET['delete']);
	$check_user_exists=mquery("SELECT * from `members` WHERE `email`='$user_id'");
	if (@mysql_num_rows($check_user_exists)==0) { $error[]=$lang_errors['invalid_member']; }
	// If no errors...continue
	if (count($error)=="0")	{
		$member=@mysql_fetch_array($check_user_exists);
		// get member orders
		$getorders=mquery("SELECT * from `orders` WHERE `email`='$member[email]' ORDER BY `approved`,`confirmed` DESC");
		$orders=array();
		while($order=@mysql_fetch_array($getorders)) {
			// get credit plan details
			$credit_plan=@mysql_fetch_array(mquery("SELECT * from `credit_packs` WHERE `plan_id`='$order[plan_id]'"));
			$order['plan']=$credit_plan;
			array_push($orders, $order);
		}				
		if (count($orders)) {
			$t->assign('member',$member);		
			$t->assign('members',fetch_members());
	   		$t->assign('orders',$orders);	
			$t->display("admin/delete_member.tpl");
		}else{
	    	mquery("DELETE from `members` WHERE `email`='$user_id'");
			set_msg("Member ID <b>$user_id</b> deleted successfuly.");		
			header("Location: $config[base_url]/admin/members.php");	    	
		}
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/members.tpl");		
	}
}
// EOF "member DELETE CONFIRM"
// START "member DELETE"
elseif (isset($_POST['delete'])) {
	$user_id=sqlx($_POST['delete_member']);
	$check_user_exists=mquery("SELECT * from `members` WHERE `email`='$user_id'");
	if (@mysql_num_rows($check_user_exists)==0) { $error[]=$lang_errors['invalid_member']; }
	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("DELETE from `members` WHERE `email`='$user_id'");
//    	mquery("DELETE from `orders` WHERE `email`='$user_id'");
		header("Location: $config[base_url]/admin/members.php");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
	$t->display("admin/members.tpl");
}
// EOF "member DELETE"
// START "member APPROVE"
elseif (isset($_GET['member_approve'])) {
	$user_id=sqlx($_GET['member_approve']);
	$check_user_exists=mquery("SELECT * from `members` WHERE `email`='$user_id'");
	if (@mysql_num_rows($check_user_exists)==0) { $error[]=$lang_errors['invalid_member']; }
	// fetch member details in array
	$member=@mysql_fetch_array($check_user_exists);
	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("UPDATE `members` SET `approved`='1' WHERE `email`='$user_id'");
		//  ---------- Send email to New Member -------------		
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
		$tpl_email->assign('password', $member[password]);
		$tpl_email->assign('fullname', $member[fullname]);
		$tpl_email->assign('email', $member[email]);                                       
		$subject = $tpl_email->fetch("email_subject:member_approve");
		$email_message = $tpl_email->fetch("email:member_approve");
		// Get member_approve from email
		$gettpl=mquery("SELECT `from_email` from `email_templates` WHERE `tpl_name`='member_approve'");
		$from_email=@mysql_result($gettpl,0,'from_email');
		$headers = "From: $conf[system_name] <$from_email>";
		// assign additional template variables
		// Send E-Mail
		send_mail($member[email],"$conf[system_name] <$from_email>",$subject,$email_message);		
		// EOF ---------- Send email to New Member -------------		
		set_msg("Member <b>$member[username]</b> approved and notification email sent successfuly to <b>$member[email]</b>.");		
		header("Location: $config[base_url]/admin/main.php");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
	$t->display("admin/members.tpl");
}
// EOF "member APPROVE"
else {
	$sql=mquery("SELECT * from `members` ORDER BY `date_register` DESC");
	$members=array();
	while($user=@mysql_fetch_array($sql)) {
		$count_orders=@mysql_result(mquery("SELECT COUNT(*) from `transactions` WHERE `email`='$user[email]'"),0);
		$user[count_orders]=$count_orders;
		array_push($members, $user);
	}
   	$t->assign('members',$members);
	$t->display("admin/members.tpl");
}
?>