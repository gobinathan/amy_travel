<?php
$t->assign('title', "Newsletter");

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$lng = $t->get_config_vars(); 

// START "SUBSCRIBE to Newsletter"
if (isset($_POST['newsletter_subscribe'])) {
	$n_email=sqlx($_POST['email']);
	// Check for errors
	if (empty($n_email)) { $error[]=$lang_errors['empty_email']; }
	if (!is_email($n_email)) { $error[]=$lang_errors['invalid_email']; }
	$t->assign('email', $n_email);
	// Check for duplicate email
	$chk_dup=mysql_query("SELECT * from `subscribers` WHERE `email`='$n_email'");
	if (@mysql_result($chk_dup,0,"confirmed")=="1") { $error[]="The email $n_email already exists!"; }
	// If no errors...continue
	if (count($error)=="0")	{
		if (@mysql_num_rows($chk_dup)=="0") {
			mysql_query("INSERT into `subscribers` values ('$n_email','','0','0')");
		}
		// ---------- Send email to Subscriber -------------
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
		// Get newsletter_subscribe from email
		$gettpl=mysql_query("SELECT `from_email` from `email_templates` WHERE `tpl_name`='newsletter_subscribe'");
		$from_email=@mysql_result($gettpl,0,'from_email');
		// assign additional template variables
		$tpl_email->assign('email', $n_email);
		$confirm_link="$baseurl/newsletter/confirm/$n_email/".md5($n_email)."";
		$tpl_email->assign('confirm_link', $confirm_link);		
		$subject = $tpl_email->fetch("email_subject:newsletter_subscribe");
		$email_message = $tpl_email->fetch("email:newsletter_subscribe");
		// Send E-Mail
		send_mail($n_email,"$conf[system_name] <$from_email>",$subject,$email_message);
		// EOF ---------- Send email to Subscriber -------------				
	}else{
	   	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}	
}	
// EOF "SUBSCRIBE to Newsletter"

// START "CONFIRM Subscription to Newsletter"
if ($request[1]=="confirm") {
	$email=sqlx($request[2]);
	$vcode=sqlx($request[3]);
	if (md5($email)!==$vcode) { $error[]=$lang_errors['wrong_captcha']; }
	if (!is_email($email)) { $error[]=$lang_errors['invalid_email']; }
	$t->assign('email', $email);
	
	// If no errors...continue
	if (count($error)=="0")	{
		mysql_query("UPDATE `subscribers` SET `confirmed`='1' WHERE `email`='$email'");
		// ---------- Send email to Subscriber -------------
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
		// Get newsletter_subscribe from email
		$gettpl=mysql_query("SELECT `from_email` from `email_templates` WHERE `tpl_name`='newsletter_subscribe_confirmed'");
		$from_email=@mysql_result($gettpl,0,'from_email');
		// assign additional template variables
		$tpl_email->assign('email', $email);
		$unsubscribe_link="$baseurl/newsletter/confirm_unsubscribe/$email/".md5($email)."";
		$tpl_email->assign('unsubscribe_link', $unsubscribe_link);		
		$subject = $tpl_email->fetch("email_subject:newsletter_subscribe_confirmed");
		$email_message = $tpl_email->fetch("email:newsletter_subscribe_confirmed");
		send_mail($email,"$conf[system_name] <$from_email>",$subject,$email_message);
		// EOF ---------- Send email to Subscriber -------------		
	}else{
	   	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}			
}
// EOF "CONFIRM Subscription to Newsletter"

// START "UNSUBSCRIBE from Newsletter"
if (isset($_POST['newsletter_unsubscribe'])) {
	$n_email=sqlx($_POST['email']);
	// Check for errors
	if (empty($n_email)) { $error[]=$lang_errors['empty_email']; }
	if (!is_email($n_email)) { $error[]=$lang_errors['invalid_email']; }
	$t->assign('email', $n_email);
	// Check if subscriber exists
	$chk_ex=mysql_query("SELECT * from `subscribers` WHERE `email`='$n_email'");
	if (@mysql_num_rows($chk_ex)=="0") { $error[]="The email $n_email doesn't exists'"; }
	// If no errors...continue
	if (count($error)=="0")	{
		// ---------- Send email to Subscriber -------------
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
		// Get newsletter_subscribe from email
		$gettpl=mysql_query("SELECT `from_email` from `email_templates` WHERE `tpl_name`='newsletter_unsubscribe'");
		$from_email=@mysql_result($gettpl,0,'from_email');
		// assign additional template variables
		$tpl_email->assign('email', $n_email);
		$confirm_link="$baseurl/newsletter/confirm_unsubscribe/$n_email/".md5($n_email)."";
		$tpl_email->assign('confirm_link', $confirm_link);		
		$subject = $tpl_email->fetch("email_subject:newsletter_unsubscribe");
		$email_message = $tpl_email->fetch("email:newsletter_unsubscribe");
		// Send E-Mail
		send_mail($n_email,"$conf[system_name] <$from_email>",$subject,$email_message);
		// EOF ---------- Send email to Subscriber -------------		
	}else{
	   	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}	
}	
// EOF "UNSUBSCRIBE from Newsletter"

// START "CONFIRM UnSubscription from Newsletter"
if ($request[1]=="confirm_unsubscribe") {
	$email=sqlx($request[2]);
	$vcode=sqlx($request[3]);
	if (md5($email)!==$vcode) { $error[]=$lang_errors['wrong_captcha']; }
	if (!is_email($email)) { $error[]=$lang_errors['invalid_email']; }
	$t->assign('email', $email);
	
	// If no errors...continue
	if (count($error)=="0")	{
		mysql_query("DELETE from `subscribers` WHERE `email`='$email'");
		// ---------- Send email to Subscriber -------------
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
		// Get newsletter_subscribe from email
		$gettpl=mysql_query("SELECT `from_email` from `email_templates` WHERE `tpl_name`='newsletter_unsubscribe_confirmed'");
		$from_email=@mysql_result($gettpl,0,'from_email');
		// assign additional template variables
		$tpl_email->assign('email', $email);
		$unsubscribe_link="$baseurl/newsletter/unsubscribe/$email/".md5($email)."";
		$subject = $tpl_email->fetch("email_subject:newsletter_unsubscribe_confirmed");
		$email_message = $tpl_email->fetch("email:newsletter_unsubscribe_confirmed");

		send_mail($email,"$conf[system_name] <$from_email>",$subject,$email_message);
		// EOF ---------- Send email to Subscriber -------------		

	}else{
	   	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}			
}
// EOF "CONFIRM UnSubscription from Newsletter"

$t->display("frontend/$template/newsletter.tpl");
?>