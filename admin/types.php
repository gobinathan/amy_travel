<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");

$manage=sqlx($_REQUEST['manage']);
if (!empty($manage) AND is_numeric($manage)) {
	$sql=mquery("SELECT `title` from `types_c_text` WHERE `type_c_id`='$manage' AND `lang`='$default_lang'");
	$t->assign('manage', @mysql_result($sql,0));
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
}
if (isset($_POST['edit_lang_values'])) {
	$edit_lang=sqlx($_POST['edit_lang']);
	$form = array_map('trim', $_POST['type']);
	foreach ($form as $type_id => $title) {
		$type_id=sqlx($type_id);
		$title=sqlx($title);
		// Check if there is edit_lang in the DB
		$chkdb=mquery("SELECT * from `types_text` WHERE `type_id`='$type_id' AND `lang`='$edit_lang'");
		if (@mysql_num_rows($chkdb)>0) {
			mquery("UPDATE `types_text` SET `title`='$title' WHERE `type_id`='$type_id' AND `lang`='$edit_lang'");
		}else {
			mquery("INSERT into `types_text` values ('$type_id','$edit_lang','$title')");
		}
	}  
	set_msg("Custom Features updated successfuly!");		
	header("Location: $config[base_url]/admin/types.php?manage=$manage&edit_lang=$edit_lang");
}
if (isset($_POST['add_new_type'])) {
  $manage=sqlx($_POST['manage']);
  $title=sqlx($_POST['title']);

	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['type_title_empty']; }

	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("INSERT into `types` values ('','$manage')");
		$new_type_id=@mysql_insert_id();
    	mquery("INSERT into `types_text` values ('$new_type_id','$default_lang','$title')");		
		set_msg("New Custom Feature <b>$title</b> added successfuly!");		
		header("Location: $config[base_url]/admin/types.php?manage=$manage&edit=$new_type_id&edit_lang=$default_lang");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/add_type.tpl");		
	}
}
elseif (isset($_GET['delete'])) {
	$type_id=sqlx($_GET['delete']);
	$check_type_exists=mquery("SELECT * from `types` WHERE `type_id`='$type_id'");
	if (@mysql_num_rows($check_type_exists)==0) { $error[]=$lang_errors['invalid_type']; }
	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("DELETE from `types` WHERE `type_id`='$type_id'");
    	mquery("DELETE from `types_text` WHERE `type_id`='$type_id'");    	
		set_msg("Custom Feature ID <b>$type_id</b> deleted successfuly!");		
		header("Location: $config[base_url]/admin/types.php?manage=$manage");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
	$t->display("admin/types.tpl");
}
else {
	$sql=mquery("SELECT * from `types` LEFT JOIN `types_text` ON (types.type_id=types_text.type_id) WHERE `type_c_id`='$manage' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang'),types.type_id");
	$types=array();
	while($type=@mysql_fetch_array($sql)) {
		if (multiarray_search($types, 'type_id', $type[type_id]) == "-1") {
			array_push($types, $type);
		}
	}
   	$t->assign('types',$types);
	$t->display("admin/types.tpl");
}
?>