<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");

if (isset($_GET['edit'])) {
	$country_id=sqlx($_GET['edit']);
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
	$chkdb=mquery("SELECT * from `countries_text` WHERE `country_id`='$country_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
		$getcountryinfo=mquery("SELECT * from `countries` LEFT JOIN `countries_text` ON (countries.country_id=countries_text.country_id) WHERE countries.country_id='$country_id' AND `lang`='$edit_lang'");		
	}else {
		// Load default
		$getcountryinfo=mquery("SELECT * from `countries` LEFT JOIN `countries_text` ON (countries.country_id=countries_text.country_id) WHERE countries.country_id='$country_id' AND `lang`='$default_lang'");		
	}
	if (@mysql_num_rows($getcountryinfo)=="0") { $error[]=$lang_errors['invalid_country']; }
	if (count($error)=="0") {
		$country=@mysql_fetch_array($getcountryinfo);
	   	$t->assign('country',$country);
		if (!empty($edit_lang)) {
			$t->assign('edit_lang', $edit_lang);
		}else {
			$t->assign('edit_lang', $default_lang);	  
		}
		$t->display("admin/edit_country.tpl");
	} else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}	
}
elseif (isset($_POST['edit_country'])) {
	$country_id=sqlx($_POST['edit_country']);
	$title=sqlx($_POST['title']);
	$country_code=sqlx($_POST['country_code']);
	$edit_lang=sqlx($_POST['edit_lang']);
	// Check for errors
	if (empty($title)) { $error[]="No Title!"; }
	$check_country_exists=mquery("SELECT * from `countries` WHERE `country_id`='$country_id'");
	if (@mysql_num_rows($check_country_exists)==0) { $error[]=$lang_errors['invalid_country']; }
	// If no errors...continue
	if (count($error)=="0")	{
   		mquery("UPDATE `countries` SET `country_code`='$country_code' WHERE `country_id`='$country_id'");
	// Check if there is edit_lang in the DB
	$chkdb=mquery("SELECT * from `countries_text` WHERE `country_id`='$country_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
   		mquery("UPDATE `countries_text` SET `title`='$title' WHERE `country_id`='$country_id' AND `lang`='$edit_lang'");
	}else {
   		mquery("INSERT into `countries_text` values ('$country_id','$edit_lang','$title')");
	}
		set_msg("Country <b>$title</b> updated successfuly!");
		header("Location: $config[base_url]/admin/countries.php?edit=$country_id&edit_lang=$edit_lang");
  	}else{ // Else Show errors
   		$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
}
elseif (isset($_GET['add'])) {
	$t->display("admin/add_country.tpl");
}
elseif (isset($_POST['add_country'])) {
  $title=sqlx($_POST['title']);
  $country_code=sqlx($_POST['country_code']);
  
	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['country_title_empty']; }
	$check_country_exists=mquery("SELECT * from `countries` WHERE `country_code`='$country_code'");
	if (@mysql_num_rows($check_country_exists)>0) { $error[]=$lang_errors['country_code_exists']; }

	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("INSERT into `countries` values ('','$country_code')");
		$new_country_id=@mysql_insert_id();
    	mquery("INSERT into `countries_text` values ('$new_country_id','$default_lang','$title')");
		set_msg("Country <b>$title</b> added successfuly!");
		header("Location: $config[base_url]/admin/countries.php?edit=$new_country_id");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/add_country.tpl");		
	}
}
elseif (isset($_GET['delete'])) {
	$country_id=sqlx($_GET['delete']);
	$check_country_exists=mquery("SELECT * from `countries` WHERE `country_id`='$country_id'");
	if (@mysql_num_rows($check_country_exists)==0) { $error[]=$lang_errors['invalid_country']; }
	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("DELETE from `countries` WHERE `country_id`='$country_id'");
    	mquery("DELETE from `countries_text` WHERE `country_id`='$country_id'");
		set_msg("Country <b>$country_id</b> deleted successfuly!");
		header("Location: $config[base_url]/admin/countries.php");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
	$t->display("admin/countries.tpl");
}
else {
	$sql=mquery("SELECT * from `countries` LEFT JOIN `countries_text` ON (countries.country_id=countries_text.country_id) WHERE `lang`='$default_lang' OR `lang`='$edit_lang' ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
	$countries=array();
	while($country=@mysql_fetch_array($sql)) {
		if (multiarray_search($countries, 'country_id', $country[country_id]) == "-1") {
			$country['cities']=fetch_cities($country['country_id']);
			array_push($countries, $country);
		}
	}
	$t->assign('dynamic_table',true);
   	$t->assign('countries',sortArrayByField($countries,"country_code"));
	$t->display("admin/countries.tpl");
}
?>