<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");

if (isset($_GET['edit'])) {
	$c_id=sqlx($_GET['edit']);
	$sql=mquery("SELECT * from `currency` WHERE `c_id`='$c_id'");
	if (@mysql_num_rows($sql)=="0") { $error[]=$lang_errors['invalid_currency']; }
	if (count($error)=="0") {
		$currency=@mysql_fetch_array($sql);
	   	$t->assign('currency',$currency);
		$currency_list=currency_list();
		array_push($currency_list,$currency);	   			
		$t->assign('currency_list',$currency_list);
		$t->display("admin/edit_currency.tpl");
	} else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}	
}
elseif (isset($_POST['edit_currency'])) {
	$c_id=sqlx($_POST['edit_currency']);
	$check_c_exists=mquery("SELECT * from `currency` WHERE `c_id`='$c_id'");
	if (@mysql_num_rows($check_c_exists)==0) { $error[]=$lang_errors['invalid_currency']; }
	$code=@mysql_result($check_c_exists,0,"code");
	$rate=sqlx($_POST['rate']);
	$manual_update=sqlx($_POST['manual_update']);
	if ($manual_update=="on") { $manual_update="1"; }else { $manual_update="0"; }
	$active=sqlx($_POST['active']);
	if ($active=="on") { $active="1"; }else { $active="0"; }

	// Check for errors
	if (empty($code)) { $error[]=$lang_errors['currency_code_empty']; }
	// If no errors...continue
	if (count($error)=="0")	{
   		mquery("UPDATE `currency` SET `rate`='$rate',`manual_update`='$manual_update',`active`='$active' WHERE `c_id`='$c_id'");
		set_msg("Currency <b>$code</b> updated successfuly!");
		header("Location: $config[base_url]/admin/currency.php");
  	}else{ // Else Show errors
   		$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
}
elseif (isset($_GET['add'])) {  
	$t->assign('currency_list',currency_list());
	$t->display("admin/add_currency.tpl");
}
elseif (isset($_POST['add_currency'])) {
  $code=sqlx($_POST['currency']);
  $title=currency_list($code);
  if (empty($title)) {
	$title=sqlx($_POST['currency']);
	$code=sqlx($_POST['c_code']);
  }
  $rate=sqlx($_POST['rate']);
	$manual_update=sqlx($_POST['manual_update']);
	if ($manual_update=="on") { $manual_update="1"; }else { $manual_update="0"; }
	$active=sqlx($_POST['active']);
	if ($active=="on") { $active="1"; }else { $active="0"; }

	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['currency_title_empty'];; }
	if (empty($code)) { $error[]=$lang_errors['currency_code_empty']; }
	$check_user_exists=mquery("SELECT * from `currency` WHERE `code`='$code'");
	if (@mysql_num_rows($check_user_exists)>0) { $error[]=$lang_errors['currency_exists']; }

	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("INSERT into `currency` values ('','$title','$code','$rate','0','$active','$manual_update')");
		if ($manual_update == "0") { update_rates($code);  }
		set_msg("Added Currency <b>$code</b> successfuly!");
		header("Location: $config[base_url]/admin/currency.php");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/add_currency.tpl");		
	}
}
elseif (isset($_GET['delete'])) {
	$c_id=sqlx($_GET['delete']);
	$check_user_exists=mquery("SELECT * from `currency` WHERE `c_id`='$c_id'");
	if (@mysql_num_rows($check_user_exists)==0) { $error[]=$lang_errors['invalid_currency']; }
	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("DELETE from `currency` WHERE `c_id`='$c_id'");
		set_msg("Currency <b>$c_id</b> deleted successfuly!");
		header("Location: $config[base_url]/admin/currency.php");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
	$t->display("admin/currency.tpl");
}
elseif (isset($_GET['activate'])) {
	$c_id=sqlx($_GET['activate']);
	mquery("UPDATE `currency` SET `active`='1' WHERE `c_id`='$c_id'");
	set_msg("Currency <b>$c_id</b> is now active");
	header("Location: $config[base_url]/admin/currency.php");
}
elseif (isset($_GET['deactivate'])) {
	$c_id=sqlx($_GET['deactivate']);
	$check_c_exists=mquery("SELECT * from `currency` WHERE `c_id`='$c_id'");
	if (@mysql_num_rows($check_c_exists)==0) { $error[]=$lang_errors['invalid_currency']; }
	if (@mysql_result($check_c_exists,0,"default") == "1") { $error[]=$lang_errors['currency_deactivate_default']; }
	// If no errors...continue
	if (count($error)=="0")	{
		mquery("UPDATE `currency` SET `active`='0' WHERE `c_id`='$c_id'");
		set_msg("Currency <b>$c_id</b> is now inactive");
		header("Location: $config[base_url]/admin/currency.php");
	}else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/currency.tpl");
	}	
}
elseif (isset($_GET['default'])) {
	$c_id=sqlx($_GET['default']);
	$check_c_exists=mquery("SELECT * from `currency` WHERE `c_id`='$c_id'");
	if (@mysql_num_rows($check_c_exists)==0) { $error[]=$lang_errors['invalid_currency']; }
	// If no errors...continue
	if (count($error)=="0")	{
		mquery("UPDATE `currency` SET `default`='1',`active`='1' WHERE `c_id`='$c_id'");
		mquery("UPDATE `currency` SET `default`='0' WHERE `c_id`!='$c_id'");
		set_msg("Currency <b>$c_id</b> set as default");
		header("Location: $config[base_url]/admin/currency.php");
	}else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/currency.tpl");
	}	
}
// 	START "UPDATE CURRENCY RATES"
elseif (isset($_GET['update_rates'])) {
	update_rates();
	set_msg("Currency Rates updated successfuly!");
	header("Location: $config[base_url]/admin/currency.php");
}
// 	EOF "UPDATE CURRENCY RATES"
else {
	$sql=mquery("SELECT * from `currency` ORDER BY `default` DESC");
	$currency=array();
	while($user=@mysql_fetch_array($sql)) {
		array_push($currency, $user);
	}
	$today = date("Y-m-d");
   	$t->assign('today',$today);
   	$t->assign('currencies',$currency);
	$t->display("admin/currency.tpl");
}
//echo convert_currency("EUR","USD","500");
?>