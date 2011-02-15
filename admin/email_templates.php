<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");

if (isset($_GET['edit'])) {
	$tpl_name=sqlx($_GET['edit']);
	$edit_lang=sqlx($_GET['edit_lang']);
	if (empty($edit_lang)) { $edit_lang=$default_lang; }
	// Check if this language exists, and if exists, change the page encoding
	if ($edit_lang !== $default_lang) {
		$checklang=mquery("SELECT * from `languages` WHERE `lang_name`='$edit_lang'");
		if (@mysql_num_rows($checklang)=="0") { $error[]=$lang_errors['invalid_language']; }
		if (count($error)=="0") {
			$lang=@mysql_fetch_array($checklang);
			$language_encoding=$lang[encoding];
			$t->assign('language_encoding',$language_encoding);
			$t->assign('load_google_api',true);
		}
	}	

	// Check if there is edit_lang in the DB
	$chkdb=mquery("SELECT * from `email_templates` WHERE `tpl_name`='$tpl_name' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
		$sql=mquery("SELECT * from `email_templates` WHERE `tpl_name`='$tpl_name' AND `lang`='$edit_lang'");
	}else {
		// Load default
		$sql=mquery("SELECT * from `email_templates` WHERE `tpl_name`='$tpl_name' AND `lang`='$default_lang'");
	}
	if (@mysql_num_rows($sql)=="0") { $error[]=$lang_errors['invalid_template']; }
	if (count($error)=="0") {
		$etpl=@mysql_fetch_array($sql);
	   	$t->assign('tpl',$etpl);
		$t->display("admin/edit_email_template.tpl");
	} else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}	
}
elseif (isset($_POST['edit_template'])) {
	$tpl_name=sqlx($_POST['edit_template']);
	$from_email=sqlx($_POST['from_email']);
	$tpl_subject=sqlx($_POST['subject']);
	$tpl_source=sqlx($_POST['tpl_source']);
	$description=sqlx($_POST['description']);
		
	// Check for errors
	if (empty($tpl_name)) { $error[]=$lang_errors['email_tpl_empty_name']; }
	$check_tpl_exists=mquery("SELECT * from `email_templates` WHERE `tpl_name`='$tpl_name'");
	if (@mysql_num_rows($check_tpl_exists)==0) { $error[]=$lang_errors['invalid_language']; }
	// If no errors...continue
	if (count($error)=="0")	{
		$now=time();
		// Check if there is edit_lang in the DB
		$chkdb=mquery("SELECT * from `email_templates` WHERE `tpl_name`='$tpl_name' AND `lang`='$edit_lang'");
		if (@mysql_num_rows($chkdb)>0) {
   			mquery("UPDATE `email_templates` SET `from_email`='$from_email',`tpl_subject`='$tpl_subject',`tpl_source`='$tpl_source',`last_update`='$now',`description`='$description' WHERE `tpl_name`='$tpl_name' AND `lang`='$edit_lang'");
		}else {
   			mquery("INSERT into `email_templates` values ('$tpl_name','$from_email','$tpl_subject','$tpl_source','$now','$description','$edit_lang')");
		}
		set_msg("E-Mail template <b>$tpl_name</b> updated successfuly");
		header("Location: $config[base_url]/admin/email_templates.php?edit=$tpl_name&edit_lang=$edit_lang");
  	}else{ // Else Show errors
   		$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
}
else {
	$sql=mquery("SELECT * from `email_templates` WHERE `lang`='$default_lang' OR `lang`='$edit_lang' ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`tpl_name`,`last_update`");
	$email_templates=array();
	while($etpl=@mysql_fetch_array($sql)) {
		if (multiarray_search($email_templates, 'tpl_name', $etpl[tpl_name]) == "-1") {
			array_push($email_templates, $etpl);
		}
	}
   	$t->assign('email_templates',$email_templates);
	$t->display("admin/email_templates.tpl");
}
?>