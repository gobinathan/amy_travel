<?php
if (is_numeric($_SESSION['member']['user_id'])) {
	header("Location: $config[base_url]");
}
if (isset($_GET['logout']) OR $request[0]=="logout") {
	unset($_SESSION['member']);
//	session_unset();
//	session_destroy();
	header("Location: $config[base_url]");
}
if (isset($_POST['submit'])) {
	$username=sqlx($_POST['username']);
	$member=fetch_member($username);
	$password=sqlx($_POST['password']);
	$number = sqlx($_POST['txtNumber']);
	$redirect_to=sqlx($_POST['redirect_to']);
	if (md5($number) !== $_SESSION['image_random_value'] AND $conf['require_captcha']=="1") { $error[]=$lang_errors['wrong_captcha']; }
	if ($password!==decryptPass($member['password'],$username)) {	$error[]=$lang_errors['login_wrong_pass'];  }
	if ($member[email_confirmed]=="0" AND $conf[member_confirm_email]=="1") { $error[]=$lang_errors['email_not_confirmed']; }
	if ($member[approved]=="0" AND $conf[member_approve]=="1") { $error[]=$lang_errors['account_not_approved']; }
	if (count($error)) {
		$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->assign('page_title', $lang_globals['login']);
		$t->display("frontend/$template/member_login.tpl");	
	}	
	// Check for incompleted orders
	$getorder=mysql_query("SELECT `order_id` from `transactions` WHERE `user_id`='$member[user_id]' AND `confirmed`='0' ORDER BY `date_added` DESC LIMIT 1");
	if (@mysql_num_rows($getorder) > "0") {
		$order_id=@mysql_result($getorder,0,"order_id");
		header("Location: $config[base_url]/booking/complete_payment/$order_id");		
	}
	elseif (!count($error)) {
		$_SESSION['member']=$member;
		$ipaddr=$_SERVER['REMOTE_ADDR'];
		$new_last_login="".date("d-M-Y H:i:s")." from $ipaddr";
		mysql_query("UPDATE `members` SET `last_login`='$new_last_login' WHERE `user_id`='$member[user_id]'");
//		if (!empty($redirect_to)) {
//			header("Location: $config[base_url]/$redirect_to");
//		}else{
			header("Location: $config[base_url]");
//		}
	}
}else {
	$t->assign('page_title', $lang_globals['login']);
	$t->display("frontend/$template/member_login.tpl");
}
?>
