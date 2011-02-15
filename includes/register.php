<?php
if (is_numeric($_SESSION['member']['user_id'])) {
	header("Location: $config[base_url]");
}
$t->assign('title', $lang_globals['register']);
$lng = $t->get_config_vars(); 

// START "NEW member REGISTER SUBMIT"
if (isset($_POST['submit'])) {
	if ($conf[member_allow_register] == "0") { $error[]=$lang_errors['member_register_not_allowed']; }
	$email=sqlx($_POST['email']);
    $fullname=sqlx($_POST['fullname']);
    $password=sqlx($_POST['password']);
    $repeat_password=sqlx($_POST['repeat_password']);
	$number = sqlx($_POST['txtNumber']);
	$redirect_to=sqlx($_POST['redirect_to']);
	$t->assign('req_uri',$redirect_to);
	// Check for errors
	if (md5($number) !== $_SESSION['image_random_value'] AND $conf['require_captcha'] == "1") { $error[]=$lang_errors['wrong_captcha']; }	
	if (empty($fullname)) { $error[]=$lang_errors['empty_fullname']; }
	if (empty($email)) { $error[]=$lang_errors['empty_email']; }
	if (!is_email($email)) { $error[]=$lang_errors['invalid_email']; }
	if (empty($password)) { $error[]=$lang_errors['register_empty_password']; }
	if (empty($repeat_password)) { $error[]=$lang_errors['register_empty_password_repeat']; }
	if ($password!==$repeat_password) { $error[]=$lang_errors['register_passwords_match']; }	
	$check_email_exists=mysql_query("SELECT * from `members` WHERE `email`='$email'");
	if (@mysql_num_rows($check_email_exists)>0) { $error[]=$lang_errors['register_email_exists']; }
	// If no errors...continue
	if (count($error)=="0")	{
		$member_id=add_member($email);
	}else{
	   	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}	
}
// EOF "NEW member REGISTER SUBMIT"
// START "NEW member E-MAIL CONFIRM"
elseif ($request[1]=="confirm") {
	$user_id=sqlx($request[2]);
	$vcode=sqlx($request[3]);
	// get member details
	$member=fetch_member($user_id);
	if (!$member[user_id]) { $error[]=$lang_errors['invalid_user']; }
	if ($member[email_confirmed]=="1") { $error[]=$lang_errors['register_email_confirmed']; }
	if (md5($member[email])!==$vcode) { $error[]=$lang_errors['wrong_validation_code']; }
	if (!is_email($member[email])) { $error[]=$lang_errors['invalid_email']; }
	$t->assign('member', $member);
	
	// If no errors...continue
	if (count($error)=="0")	{
		mysql_query("UPDATE `members` SET `email_confirmed`='1' WHERE `member_id`='$member[member_id]'");
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
		$tpl_email->assign('username', $member[username]);
		$tpl_email->assign('password', $member[password]);
		$tpl_email->assign('fullname', $member[fullname]);
		$tpl_email->assign('email', $member[email]);                                       
		$subject = $tpl_email->fetch("email_subject:member_register");
		$email_message = $tpl_email->fetch("email:member_register");
		// Get member_register from email
		$gettpl=mysql_query("SELECT `from_email` from `email_templates` WHERE `tpl_name`='member_register'");
		$from_email=@mysql_result($gettpl,0,'from_email');
		// assign additional template variables
		// Send E-Mail
		send_mail($member[email],"$conf[system_name] <$from_email>",$subject,$email_message);
		// EOF ---------- Send email to New Member -------------		
		if ($conf[member_approve]=="1") {
			$t->assign('status',"register_approve");
		}else {
			$t->assign('status',"register_success");
		}
	}else{
	   	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}			  
}
// EOF "NEW member E-MAIL CONFIRM"
elseif ($request[1]=="complete_payment") {
		$order_id=$request[2];
		$getorder=mysql_query("SELECT * from `orders` WHERE `order_id`='$order_id'");
		$order=@mysql_fetch_array($getorder);		
		$t->assign('status',"must_complete_payment");
		$t->assign('payment_required',true);
		$t->assign('payment_order_id',$order[order_id]);
		$getgw=mysql_query("SELECT * from `payment_gw`");
		$payment_gw=@mysql_fetch_array($getgw);
	   	$t->assign('payment_gw',$payment_gw);
}
else {
//get refering page
$req_uri="";
foreach ($request as $reqnum => $req) {
	if ($req == "redirect_to") {
		$req_true = true;
	}else{
		if ($req_true) {
			if (empty($req_uri)) {
				$req_uri=$req; 
			}else{
				$req_uri="$req_uri/$req";
			}
		}
	}
}
	$t->assign('req_uri',$req_uri);
}

$t->display("frontend/$template/register.tpl");
?>