<?php
if (isset($_POST['submit']) OR is_email($request[1])) {
	$email=sqlx($_POST['email']);
	if (is_email($request[1])) {$email=$request[1];}
//	$password=cryptPass(sqlx($_POST['password']),$username);
	if (empty($email)) { $error[]=$lang_errors['empty_email']; }
	if (!is_email($email)) { $error[]=$lang_errors['invalid_email']; }	
	$number = sqlx($_POST['txtNumber']);
	if (md5($number) !== $_SESSION['image_random_value'] AND $conf['require_captcha']=="1") { $error[]=$lang_errors['wrong_captcha']; }
	$member=fetch_member($email);
	if (!is_numeric($member[user_id])) { $error[]=$lang_errors['invalid_member']; }
	if ($member[email_confirmed]=="0" AND $conf[member_confirm_email]=="1") { $error[]=$lang_errors['email_not_confirmed']; }
	if ($member[approved]=="0" AND $conf[member_approve]=="1") { $error[]=$lang_errors['account_not_approved']; }
	if (count($error)) {
		$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->assign('page_title', $lang_frontend['forgot_pass']);
		$t->display("frontend/$template/forgot_pass.tpl");	
	}	
	else {
		$password=decryptPass($member[password],$member[user_id]);
		// ---------- Send password email to Member -------------
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
		$tpl_email->assign('password', $password);
		$tpl_email->assign('fullname', $member[fullname]);
		$tpl_email->assign('email', $email);

		$subject = $tpl_email->fetch("email_subject:forgot_password");
		$email_message = $tpl_email->fetch("email:forgot_password");
		// Get member_register from email
		$gettpl=mysql_query("SELECT `from_email` from `email_templates` WHERE `tpl_name`='forgot_password'");		  
		$from_email=@mysql_result($gettpl,0,'from_email');
		$headers = "From: $conf[system_name] <$from_email>";
		// Send E-Mail
		if ($conf[use_smtp_mail] == "1") {
			mail_smtp($email, $subject, $email_message);
		}else {
			mail($email, $subject, $email_message, $headers);
		}
		// EOF ---------- Send password email to Member -------------
		$t->assign('sendto',$email);
		$t->assign('status',"sent");
		$t->assign('page_title', $lang_frontend['forgot_pass']);
		$t->display("frontend/$template/forgot_pass.tpl");
	}
}else {
	$t->assign('page_title', $lang_frontend['forgot_pass']);
	$t->display("frontend/$template/forgot_pass.tpl");
}
?>
