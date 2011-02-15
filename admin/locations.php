<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");

if (isset($_GET['edit'])) {
	$location_id=sqlx($_GET['edit']);
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
	$chkdb=mquery("SELECT * from `locations_text` WHERE `location_id`='$location_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
		$getlocationinfo=mquery("SELECT * from `locations` LEFT JOIN `locations_text` ON (locations.location_id=locations_text.location_id) WHERE locations.location_id='$location_id' AND `lang`='$edit_lang'");		
	}else {
		// Load default
		$getlocationinfo=mquery("SELECT * from `locations` LEFT JOIN `locations_text` ON (locations.location_id=locations_text.location_id) WHERE locations.location_id='$location_id' AND `lang`='$default_lang'");		
	}
	if (@mysql_num_rows($getlocationinfo)=="0") { $error[]=$lang_errors['invalid_location']; }
	if (count($error)=="0") {
		$location=@mysql_fetch_array($getlocationinfo);
	   	$t->assign('location',$location);
		if (!empty($edit_lang)) {
			$t->assign('edit_lang', $edit_lang);
		}else {
			$t->assign('edit_lang', $default_lang);	  
		}
		$t->display("admin/edit_location.tpl");
	} else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}	
}
elseif (isset($_POST['edit_location'])) {
	$location_id=sqlx($_POST['edit_location']);
	$title=sqlx($_POST['title']);
	$edit_lang=sqlx($_POST['edit_lang']);
	$uri=sqlx($_POST['uri']);

	// Check for errors
	if (empty($title)) { $error[]="No Title!"; }

	$check_location_exists=mquery("SELECT * from `locations` WHERE `location_id`='$location_id'");
	if (@mysql_num_rows($check_location_exists)==0) { $error[]=$lang_errors['invalid_location']; }
	if (!empty($uri)) {
		$check_uri_exists=mquery("SELECT * from `locations` WHERE `uri`='$uri' AND `location_id`!='$location_id'");
		if (@mysql_num_rows($check_uri_exists)>0) { $error[]=$lang_errors['cat_uri_exists']; }
	}else{
		$uri=make_uri("$title",$location_id);
	}
	// If no errors...continue
	if (count($error)=="0")	{
   		mquery("UPDATE `locations` SET `uri`='$uri' WHERE `location_id`='$location_id'");
   		
	// Check if there is edit_lang in the DB
	$chkdb=mquery("SELECT * from `locations_text` WHERE `location_id`='$location_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
   		mquery("UPDATE `locations_text` SET `title`='$title' WHERE `location_id`='$location_id' AND `lang`='$edit_lang'");
	}else {
   		mquery("INSERT into `locations_text` values ('$location_id','$edit_lang','$title')");
	}
		set_msg("Location ID <b>$location_id</b> updated successfuly!");		
		header("Location: $config[base_url]/admin/locations.php?edit=$location_id&edit_lang=$edit_lang");
  	}else{ // Else Show errors
   		$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
}
elseif (isset($_POST['add_location'])) {
  $title=sqlx($_POST['title']);
  $uri=sqlx($_POST['uri']);
	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['location_title_empty']; }
	if (!empty($uri)) {
		$check_uri_exists=mquery("SELECT * from `locations` WHERE `uri`='$uri'");
		if (@mysql_num_rows($check_uri_exists)>0) { $error[]=$lang_errors['cat_uri_exists']; }
	}

	// If no errors...continue
	if (count($error)=="0")	{
	  $get_max_position=mquery("SELECT MAX(`position`) from `locations`");
	  $max_position=@mysql_result($get_max_position,0);  
	  $position=$max_position+1;  
    	mquery("INSERT into `locations` values ('','','$position')");
		$new_location_id=@mysql_insert_id();
    	if (empty($uri)) {
			$uri=make_uri("$title",$new_location_id);
			mquery("UPDATE `locations` SET `uri`='$uri' WHERE `location_id`='$new_location_id'");
		}
    	mquery("INSERT into `locations_text` values ('$new_location_id','$default_lang','$title')");
		set_msg("Location <b>$title</b> added successfuly!");
		header("Location: $config[base_url]/admin/locations.php?edit=$new_location_id");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/add_location.tpl");		
	}
}
elseif (isset($_GET['delete'])) {
	$location_id=sqlx($_GET['delete']);
	$check_location_exists=mquery("SELECT * from `locations` WHERE `location_id`='$location_id'");
	if (@mysql_num_rows($check_location_exists)==0) { $error[]=$lang_errors['invalid_location']; }
	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("DELETE from `locations` WHERE `location_id`='$location_id'");
    	mquery("DELETE from `locations_text` WHERE `location_id`='$location_id'");
		set_msg("Location ID <b>$location_id</b> deleted successfuly!");		
		header("Location: $config[base_url]/admin/locations.php");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
	$t->display("admin/locations.tpl");
}
// START "LOCATION POSITION"
elseif (isset($_GET['move_up'])) {
	$location_id=sqlx($_GET['move_up']);
	$get_pos=mquery("SELECT `position` from `locations` WHERE `location_id`='$location_id'");
	$cur_pos=@mysql_result($get_pos,0,"position");
	$new_pos=$cur_pos-1;
	$get_up_pos=mquery("UPDATE `locations` SET `position`='$cur_pos' WHERE `position`='$new_pos'");
	$set_up_pos=mquery("UPDATE `locations` SET `position`='$new_pos' WHERE `location_id`='$location_id'");	
	header("Location: $config[base_url]/admin/locations.php");
}
elseif (isset($_GET['move_down'])) {
	$location_id=sqlx($_GET['move_down']);
	$get_pos=mquery("SELECT `position` from `locations` WHERE `location_id`='$location_id'");
	$cur_pos=@mysql_result($get_pos,0,"position");
	$new_pos=$cur_pos+1;
	$get_up_pos=mquery("UPDATE `locations` SET `position`='$cur_pos' WHERE `position`='$new_pos'");
	$set_up_pos=mquery("UPDATE `locations` SET `position`='$new_pos' WHERE `location_id`='$location_id'");
	header("Location: $config[base_url]/admin/locations.php");
}
// EOF "LOCATION POSITION"

else {
	$sql=mquery("SELECT * from `locations` LEFT JOIN `locations_text` ON (locations.location_id=locations_text.location_id) WHERE `lang`='$default_lang' OR `lang`='$edit_lang' ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`position`");
	$locations=array();
	while($location=@mysql_fetch_array($sql)) {
		if (multiarray_search($locations, 'location_id', $location[location_id]) == "-1") {
			array_push($locations, $location);
		}
	}
   	$t->assign('locations',$locations);
	$get_min_position=mquery("SELECT MIN(`position`) from `locations`");
	$min_position=@mysql_result($get_min_position,0);
	$get_max_position=mquery("SELECT MAX(`position`) from `locations`");
	$max_position=@mysql_result($get_max_position,0);
	$t->assign('min_position',$min_position);
	$t->assign('max_position',$max_position);
   	
	$t->display("admin/locations.tpl");
}
?>