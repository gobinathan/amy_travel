<?php
@session_start();
include("../config.php");
include("../includes/functions.php");
include("common.php");

$admin_id=$_SESSION['admin_id'];
if (is_numeric($admin_id)) {
	header("Location: $config[base_url]/admin/index.php");
}
if (isset($_GET['logout'])) {
	session_unset();
	session_destroy();
	header("Location: $config[base_url]/admin/login.php");
}
$t->assign('title', "Admin Panel");

if (isset($_POST['submit'])) {
	$username=sqlx($_POST['username']);
	$password=cryptPass(sqlx($_POST['password']),$username);
	if ($conf['require_captcha']) {
		$number = sqlx($_POST['txtNumber']);
		if (md5($number) !== $_SESSION['image_random_value']) { $error[]=$lang_errors['wrong_captcha']; }
	}
	$getadmin=mysql_query("SELECT * from `admins` WHERE `username`='$username' AND `password`='$password'");
	if (@mysql_num_rows($getadmin)=="0") {	$error[]=$lang_errors['login_wrong_pass'];	}
	if (count($error)) {
		$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/login.tpl");	
	}
	else {
		$admin=@mysql_fetch_array($getadmin);
		$_SESSION['last_login']=$admin['last_login'];
		$ipaddr=$_SERVER['REMOTE_ADDR'];
		$new_last_login="".date("d-M-Y H:i:s")." from $ipaddr";
		mysql_query("UPDATE `admins` SET `last_login`='$new_last_login' WHERE `admin_id`='$admin[admin_id]'");
		$_SESSION['admin_id']=$admin["admin_id"];
		$language=sqlx($_POST['lang']); // Da se proveri
		$sql=mysql_query("SELECT * from `languages` WHERE `lang_name`='$language' AND `active`='1'");
		if (@mysql_num_rows($sql)>0 AND is_dir("../languages/$language")) {
			$language_encoding=@mysql_result($sql,0,"encoding");
			$_SESSION['language']=$language;
			$_SESSION['language_encoding']=$language_encoding;
		}
		header("Location: $config[base_url]/admin/index.php");
	}
}else {
	$t->display("admin/login.tpl");
}
?>
