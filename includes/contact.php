<?php

// START "CONTACT"

if (isset($_POST['contact_form']) AND $_POST['contact_form']=="go") {
	$name=sqlx($_POST['name']);
	$from_email=sqlx($_POST['email']);
	$phone=sqlx($_POST['phone']);
	$interested_in=sqlx($_POST['interested_in']);
	$message=sqlx($_POST['message']);
	$number = sqlx($_POST['txtNumber']);
			
	// Check for errors
	if (empty($name)) { $error[]=$lang_errors['empty_fullname']; }
	if (empty($from_email)) { $error[]=$lang_errors['empty_from_email']; }
	if (empty($phone)) { $error[]=$lang_errors['empty_phone']; }		
	if (!is_email($from_email)) { $error[]=$lang_errors['invalid_from_email']; }
	if (md5($number) !== $_SESSION['image_random_value'] AND $conf['require_captcha'] == "1") { $error[]=$lang_errors['wrong_captcha']; }
	// If no errors...continue
	if (count($error)=="0")	{
		// ---------- Send email to Admin -------------
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
	$tpl_email->assign('name', $name);
	$tpl_email->assign('email', $from_email);
	$tpl_email->assign('phone', $phone);
	$tpl_email->assign('interested_in', $interested_in);
	$tpl_email->assign('message', $message);		
	$tpl_email->assign('listing', $listing);
	$subject = $tpl_email->fetch("email_subject:contact_member");
	$email_message = $tpl_email->fetch("email:contact_member");
	
	// Send E-Mail
	send_mail($conf[system_email],"$name <$from_email>",$subject,$email_message);
	// EOF ---------- Send email to Subscriber -------------				
		$t->assign('send_status',"1");
	}else{
	   	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->assign('send_status',"0");  
	}
}else {
	$t->assign('send_status',"0");  
}

// EOF "CONTACT"
$t->assign('title',$lang_frontend['contact']);
$t->display("frontend/$template/contact.tpl");
?>