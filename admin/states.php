<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");

if (isset($_GET['edit'])) {
	$state_id=sqlx($_GET['edit']);
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
	$chkdb=mquery("SELECT * from `states_text` WHERE `state_id`='$state_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
		$getstateinfo=mquery("SELECT * from `states` LEFT JOIN `states_text` ON (states.state_id=states_text.state_id) WHERE states.state_id='$state_id' AND `lang`='$edit_lang'");		
	}else {
		// Load default
		$getstateinfo=mquery("SELECT * from `states` LEFT JOIN `states_text` ON (states.state_id=states_text.state_id) WHERE states.state_id='$state_id' AND `lang`='$default_lang'");		
	}
	if (@mysql_num_rows($getstateinfo)=="0") { $error[]=$lang_errors['invalid_state']; }
	if (count($error)=="0") {
		$state=@mysql_fetch_array($getstateinfo);
	   	$t->assign('state',$state);
		if (!empty($edit_lang)) {
			$t->assign('edit_lang', $edit_lang);
		}else {
			$t->assign('edit_lang', $default_lang);	  
		}
		$t->display("admin/edit_state.tpl");
	} else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}	
}
elseif (isset($_POST['edit_state'])) {
	$state_id=sqlx($_POST['edit_state']);
	$title=sqlx($_POST['title']);
	$state_code=sqlx($_POST['state_code']);
	$edit_lang=sqlx($_POST['edit_lang']);
	// Check for errors
	if (empty($title)) { $error[]="No Title!"; }
	$check_state_exists=mquery("SELECT * from `states` WHERE `state_id`='$state_id'");
	if (@mysql_num_rows($check_state_exists)==0) { $error[]=$lang_errors['invalid_state']; }
	// If no errors...continue
	if (count($error)=="0")	{
   		mquery("UPDATE `states` SET `state_code`='$state_code' WHERE `state_id`='$state_id'");
	// Check if there is edit_lang in the DB
	$chkdb=mquery("SELECT * from `states_text` WHERE `state_id`='$state_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
   		mquery("UPDATE `states_text` SET `title`='$title' WHERE `state_id`='$state_id' AND `lang`='$edit_lang'");
	}else {
   		mquery("INSERT into `states_text` values ('$state_id','$edit_lang','$title')");
	}
		set_msg("State <b>$title</b> updated successfuly!");		
		header("Location: $config[base_url]/admin/states.php?edit=$state_id&edit_lang=$edit_lang");
  	}else{ // Else Show errors
   		$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
}
elseif (isset($_POST['add_state'])) {
  $title=sqlx($_POST['title']);
  $state_code=sqlx($_POST['state_code']);
  
	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['state_title_empty']; }
	$check_state_exists=mquery("SELECT * from `states` WHERE `state_code`='$state_code'");
	if (@mysql_num_rows($check_state_exists)>0) { $error[]=$lang_errors['state_code_exists']; }

	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("INSERT into `states` values ('','$state_code')");
		$new_state_id=@mysql_insert_id();
    	mquery("INSERT into `states_text` values ('$new_state_id','$default_lang','$title')");
		header("Location: $config[base_url]/admin/states.php?edit=$new_state_id");
		set_msg("State <b>$title</b> added successfuly!");		
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/add_state.tpl");		
	}
}
elseif (isset($_GET['delete'])) {
	$state_id=sqlx($_GET['delete']);
	$check_state_exists=mquery("SELECT * from `states` WHERE `state_id`='$state_id'");
	if (@mysql_num_rows($check_state_exists)==0) { $error[]=$lang_errors['invalid_state']; }
	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("DELETE from `states` WHERE `state_id`='$state_id'");
    	mquery("DELETE from `states_text` WHERE `state_id`='$state_id'");
		set_msg("State ID <b>$state_id</b> deleted successfuly!");		
//		mquery("DELETE from `cities` WHERE `state_id`='$state_id'");
//		mquery("DELETE from `cities_text` WHERE `state_id`='$state_id'");
		header("Location: $config[base_url]/admin/states.php");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
	$t->display("admin/states.tpl");
}
else {
	$sql=mquery("SELECT * from `states` LEFT JOIN `states_text` ON (states.state_id=states_text.state_id) WHERE `lang`='$default_lang' OR `lang`='$edit_lang' ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
	$states=array();
	while($state=@mysql_fetch_array($sql)) {
		if (multiarray_search($states, 'state_id', $state[state_id]) == "-1") {
			array_push($states, $state);
		}
	}
	$t->assign('dynamic_table',true);
	$t->assign('states', sortArrayByField($states,"state_code"));
	$t->display("admin/states.tpl");
}
?>