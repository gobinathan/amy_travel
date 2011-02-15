<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");

if (isset($_GET['edit'])) {
	$type_c_id=sqlx($_GET['edit']);
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
	$chkdb=mquery("SELECT * from `types_c_text` WHERE `type_c_id`='$type_c_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
		$getinfo=mquery("SELECT * from `types_c` LEFT JOIN `types_c_text` ON (types_c.type_c_id=types_c_text.type_c_id) WHERE types_c.type_c_id='$type_c_id' AND `lang`='$edit_lang'");		
	} else {
		// Load default
		$getinfo=mquery("SELECT * from `types_c` LEFT JOIN `types_c_text` ON (types_c.type_c_id=types_c_text.type_c_id) WHERE types_c.type_c_id='$type_c_id' AND `lang`='$default_lang'");		
	}
	if (isset($_GET['refresh'])) {
		$t->assign('body_onload','onload="top.menu.location.href=\'menu.php?select=types\';"');
	}
	if (@mysql_num_rows($getinfo)=="0") { $error[]=$lang_errors['invalid_type']; }
	if (count($error)=="0") {
		$type_c=@mysql_fetch_array($getinfo);
	   	$t->assign('type_c',$type_c);
		if (!empty($edit_lang)) {
			$t->assign('edit_lang', $edit_lang);
		}else {
			$t->assign('edit_lang', $default_lang);	  
		}
		$t->display("admin/edit_type_c.tpl");
	} else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}	
}
elseif (isset($_POST['edit_type_c'])) {
	$type_c_id=sqlx($_POST['edit_type_c']);
	$title=sqlx($_POST['title']);
	$edit_lang=sqlx($_POST['edit_lang']);
	
	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['type_title_empty']; }
	$check_type_c_exists=mquery("SELECT * from `types_c` WHERE `type_c_id`='$type_c_id'");
	if (@mysql_num_rows($check_type_c_exists)==0) { $error[]=$lang_errors['invalid_type']; }
	// If no errors...continue
	if (count($error)=="0")	{
	// Check if there is edit_lang in the DB
	$chkdb=mquery("SELECT * from `types_c_text` WHERE `type_c_id`='$type_c_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
   		mquery("UPDATE `types_c_text` SET `title`='$title' WHERE `type_c_id`='$type_c_id' AND `lang`='$edit_lang'");
	}else {
   		mquery("INSERT into `types_c_text` values ('$type_c_id','$edit_lang','$title')");
	}
		set_msg("Custom Feature category <b>$title</b> updated successfuly!");		
		header("Location: $config[base_url]/admin/types_c.php?edit=$type_c_id&edit_lang=$edit_lang&refresh");
  	}else{ // Else Show errors
   		$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
}
elseif (isset($_GET['add'])) {
	$t->display("admin/add_type_c.tpl");
}
elseif (isset($_POST['add_type_c'])) {
  $title=sqlx($_POST['title']);

	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['type_title_empty']; }
	$check_type_c_exists=mquery("SELECT * from `types_c` WHERE `title`='$title'");
	if (@mysql_num_rows($check_type_c_exists)>0) { $error[]=$lang_errors['type_exists']; }

	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("INSERT into `types_c` values ('')");
		$ins_id=@mysql_insert_id();
    	mquery("INSERT into `types_c_text` values ('$ins_id','$default_lang','$title')");
		set_msg("Custom Feature category <b>$title</b> added successfuly!");		
		header("Location: $config[base_url]/admin/types_c.php?edit=$ins_id&edit_lang=$default_lang&refresh");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/add_type_c.tpl");		
	}
}
elseif (isset($_GET['delete'])) {
	$type_c_id=sqlx($_GET['delete']);
	$check_type_c_exists=mquery("SELECT * from `types_c` WHERE `type_c_id`='$type_c_id'");
	if (@mysql_num_rows($check_type_c_exists)==0) { $error[]=$lang_errors['invalid_type']; }
	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("DELETE from `types_c` WHERE `type_c_id`='$type_c_id'");
    	mquery("DELETE from `types_c_text` WHERE `type_c_id`='$type_c_id'");    	
    	mquery("DELETE from `types` INNER JOIN `types_text` ON (types.type_id=types_text.type_id) WHERE types.type_c_id='$type_c_id'");
		set_msg("Custom Feature category ID <b>$type_c_id</b> deleted successfuly!");		
		header("Location: $config[base_url]/admin/types_c.php");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
	$t->display("admin/types_c.tpl");
}
// Show the types categories
else {
	$sql=mquery("SELECT * from `types_c` LEFT JOIN `types_c_text` ON (types_c.type_c_id=types_c_text.type_c_id) WHERE `lang`='$default_lang' OR `lang`='$edit_lang' ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
	$types_c=array();
	while($type_c=@mysql_fetch_array($sql)) {
		if (multiarray_search($types_c, 'type_c_id', $type_c[type_c_id]) == "-1") {
			array_push($types_c, $type_c);
		}
	}
   	$t->assign('types_c',$types_c);
	$t->assign('body_onload','onload="top.menu.location.href=\'menu.php?select=types\';"');   	
	$t->display("admin/types_c.tpl");
}
?>