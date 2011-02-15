<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");

// SET CUSTOM LANGUAGE EDITING PERMISSIONS
$lang_config['allow_add_globals']="1";
$lang_config['allow_del_globals']="1";
$lang_config['allow_add_frontend']="0";
$lang_config['allow_del_frontend']="0";
$lang_config['allow_add_members']="0";
$lang_config['allow_del_members']="0";
$lang_config['allow_add_errors']="0";
$lang_config['allow_del_errors']="0";
$lang_config['allow_add_hints']="0";
$lang_config['allow_del_hints']="0";
$lang_config['allow_add_admin']="0";
$lang_config['allow_del_admin']="0";
$t->assign('lang_config',$lang_config);

if (isset($_GET['edit'])) {
	$lang_name=sqlx($_GET['edit']);
	$sql=mquery("SELECT * from `languages` WHERE `lang_name`='$lang_name'");
	if (@mysql_num_rows($sql)=="0") { $error[]=$lang_errors['invalid_language']; }
	if (count($error)=="0") {
		$lang=@mysql_fetch_array($sql);
		$language_encoding=$lang[encoding];
		$t->assign('language_encoding',$language_encoding);
	   	$t->assign('lang',$lang);
	   	
		// Load arrays from language files for each section
		$lang_globals=parse_ini_file("../languages/$lang_name/globals.lng");
		$lang_frontend=parse_ini_file("../languages/$lang_name/frontend.lng");
		$lang_members=parse_ini_file("../languages/$lang_name/members.lng");
		$lang_errors=parse_ini_file("../languages/$lang_name/errors.lng");
		$lang_hints=parse_ini_file("../languages/$lang_name/hints.lng");
		$lang_admin=parse_ini_file("../languages/$lang_name/admin.lng");
		$t->assign('lang_globals',$lang_globals);		
		$t->assign('lang_frontend',$lang_frontend);		
		$t->assign('lang_members',$lang_members);
		$t->assign('lang_errors',$lang_errors);
		$t->assign('lang_hints',$lang_hints);		  		
		$t->assign('lang_admin',$lang_admin);				
		$t->assign('load_google_api',true);						
		$t->display("admin/edit_language.tpl");
	} else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}	
}
elseif (isset($_POST['edit_lang'])) {
	$lang_name=sqlx($_POST['edit_lang']);
	$lang_title=sqlx($_POST['lang_title']);
	$lang_encoding=sqlx($_POST['lang_encoding']);
		
	// Check for errors
	if (empty($lang_title)) { $error[]=$lang_errors['language_title_empty']; }
	$check_lang_exists=mquery("SELECT * from `languages` WHERE `lang_name`='$lang_name'");
	if (@mysql_num_rows($check_lang_exists)==0) { $error[]=$lang_errors['invalid_language']; }
	// If no errors...continue
	if (count($error)=="0")	{
   		mquery("UPDATE `languages` SET `lang_title`='$lang_title',`encoding`='$lang_encoding' WHERE `lang_name`='$lang_name'");
		set_msg("Language <b>$lang_title</b> updated successfuly");
		header("Location: $config[base_url]/admin/languages.php");
  	}else{ // Else Show errors
   		$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
}
elseif (isset($_GET['add'])) {
	$flags=array();
	if ($handle = opendir('../uploads/flags')) {
   		while (false !== ($file = readdir($handle))) {
       		if ($file != "." && $file != "..") {
				$flags[]=before(".gif",$file);
       		}
   		}
   		closedir($handle);
	}
	$t->assign('flags',$flags);
	$t->display("admin/add_language.tpl");
}
elseif (isset($_POST['add_lang']) AND $demo=="0") {
  $lang_name=sqlx($_POST['lang_name']);
  $lang_title=sqlx($_POST['lang_title']);
  $lang_encoding=sqlx($_POST['lang_encoding']);
  $lang_name=between('flags/','.gif',$lang_name);
	// Check for errors
	if (empty($lang_title)) { $error[]=$lang_errors['language_title_empty']; }
	$check_lang_exists=mquery("SELECT * from `languages` WHERE `lang_name`='$lang_name'");
	if (@mysql_num_rows($check_city_exists)>0) { $error[]=$lang_errors['language_title_exists']; }

	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("INSERT into `languages` values ('$lang_name','$lang_title','$lang_encoding','0','1')");
		// update settings
		mquery("INSERT into `settings` (`name`,`value`) SELECT `name`,`value` from `settings` WHERE `lang`='$default_lang'");
		mquery("UPDATE `settings` SET `lang`='$lang_name' WHERE `lang`=''");
		if (!is_dir("../languages/$lang_name")) { mkdir("../languages/$lang_name",0777); }
		chmod("../languages/$lang_name",0777);
		if (!copy("../languages/$default_lang/globals.lng","../languages/$lang_name/globals.lng")) {
			$error[]="Server Problem. You must create the directory manualy by FTP.<br/>mkdir languages/$lang_name<br/>chmod 777 languages/$lang_name<br/>";
	    	mquery("DELETE from `languages` WHERE `lang_name`='$lang_name'");
			@rmdir("../languages/$lang_name/");	    	
			$flags=array();
			if ($handle = opendir('../uploads/flags')) {
   				while (false !== ($file = readdir($handle))) {
		       		if ($file != "." && $file != "..") {
						$flags[]=before(".gif",$file);
       				}
   				}
	   			closedir($handle);
			}
			$t->assign('flags',$flags);
	    	$t->assign('error',$error);
			$t->assign('error_count',count($error));
			$t->display("admin/add_language.tpl");		
		}else {
			copy("../languages/$default_lang/frontend.lng","../languages/$lang_name/frontend.lng");
			copy("../languages/$default_lang/members.lng","../languages/$lang_name/members.lng");
			copy("../languages/$default_lang/errors.lng","../languages/$lang_name/errors.lng");
			copy("../languages/$default_lang/admin.lng","../languages/$lang_name/admin.lng");
			copy("../languages/$default_lang/hints.lng","../languages/$lang_name/hints.lng");
			copy("../languages/$default_lang/lang_config.php","../languages/$lang_name/lang_config.php");
			chmod("../languages/$lang_name/globals.lng",0666);
			chmod("../languages/$lang_name/frontend.lng",0666);
			chmod("../languages/$lang_name/members.lng",0666);
			chmod("../languages/$lang_name/errors.lng",0666);
			chmod("../languages/$lang_name/admin.lng",0666);
			chmod("../languages/$lang_name/hints.lng",0666);
			chmod("../languages/$lang_name/lang_config.php",0666);		  		  
			set_msg("Language <b>$lang_title</b> added successfuly");
			header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
		}
  	}else{ // Else Show errors
		$flags=array();
		if ($handle = opendir('../uploads/flags')) {
   			while (false !== ($file = readdir($handle))) {
       			if ($file != "." && $file != "..") {
					$flags[]=before(".gif",$file);
       			}
   			}
   			closedir($handle);
		}
		$t->assign('flags',$flags);
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/add_language.tpl");		
	}
}
elseif (isset($_POST['edit_lang_globals']) AND $demo=="0") {
	$lang_name=sqlx($_POST['edit_lang_globals']);
	// UPDATE LANGUAGE VALUES
	// Load arrays from language files
	$lang_globals=parse_ini_file("../languages/$lang_name/globals.lng");
	$form = array_map('trim', $_POST['lang']);
	foreach ($form as $key => $value) {
		// Only update values that have changed
		if (array_key_exists($key, $lang_globals)) {
			$output .= "$key = \"$value\"\n";
		}
	}
	if (!empty($output)) {
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/globals.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Language <b>$lang_name</b> <i>Globals</i> values updated successfuly");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}

elseif (isset($_POST['edit_lang_frontend']) AND $demo=="0") {
	$lang_name=sqlx($_POST['edit_lang_frontend']);
	// UPDATE LANGUAGE VALUES
	// Load arrays from language files
	$lang_frontend=parse_ini_file("../languages/$lang_name/frontend.lng");
	$form = array_map('trim', $_POST['lang']);
	foreach ($form as $key => $value) {
		// Only update values that have changed
		if (array_key_exists($key, $lang_frontend)) {
			$output .= "$key = \"$value\"\n";
		}
	}
	if (!empty($output)) {
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/frontend.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Language <b>$lang_name</b> <i>Front-End</i> values updated successfuly");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
elseif (isset($_POST['edit_lang_members']) AND $demo=="0") {
	$lang_name=sqlx($_POST['edit_lang_members']);
	if (!is_array($lang_members)) {
		$lang_members=parse_ini_file("../languages/$lang_name/members.lng");
	}
	// UPDATE LANGUAGE VALUES
	// Load arrays from language files
	$form = array_map('trim', $_POST['lang']);
	foreach ($form as $key => $value) {
		// Only update values that have changed
		if (array_key_exists($key, $lang_members)) {
			$output .= "$key = \"$value\"\n";
		}
	}
	if (!empty($output)) {	
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/members.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Language <b>$lang_name</b> <i>Members</i> values updated successfuly");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
elseif (isset($_POST['edit_lang_errors']) AND $demo=="0") {
	$lang_name=sqlx($_POST['edit_lang_errors']);
	// UPDATE LANGUAGE VALUES
	// Load arrays from language files
	$form = array_map('trim', $_POST['lang']);
	foreach ($form as $key => $value) {
		// Only update values that have changed
		if (array_key_exists($key, $lang_errors)) {
			$output .= "$key = \"$value\"\n";
		}
	}
	if (!empty($output)) {
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/errors.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Language <b>$lang_name</b> <i>Errors</i> values updated successfuly");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
elseif (isset($_POST['edit_lang_hints']) AND $demo=="0") {
	$lang_name=sqlx($_POST['edit_lang_hints']);
	// UPDATE LANGUAGE VALUES
	// Load arrays from language files
	$lang_hints=parse_ini_file("../languages/$lang_name/hints.lng");
	$form = array_map('trim', $_POST['lang']);
	foreach ($form as $key => $value) {
		// Only update values that have changed
		if (array_key_exists($key, $lang_hints)) {
			$output .= "$key = \"$value\"\n";
		}
	}
	if (!empty($output)) {
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/hints.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Language <b>$lang_name</b> <i>Hints</i> values updated successfuly");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
elseif (isset($_POST['edit_lang_admin']) AND $demo=="0") {
	$lang_name=sqlx($_POST['edit_lang_admin']);
	// UPDATE LANGUAGE VALUES
	// Load arrays from language files
	$lang_admin=parse_ini_file("../languages/$lang_name/admin.lng");   		
	$form = array_map('trim', $_POST['lang']);
	foreach ($form as $key => $value) {
		// Only update values that have changed
		if (array_key_exists($key, $lang_admin)) {
			$output .= "$key = \"$value\"\n";
		}
	}
	if (!empty($output)) {	
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/admin.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
	set_msg("Language <b>$lang_name</b> <i>Admin</i> values updated successfuly");
}

elseif (isset($_GET['delete'])) {
	$lang_name=sqlx($_GET['delete']);
	$check_lang_exists=mquery("SELECT * from `languages` WHERE `lang_name`='$lang_name'");
	if (@mysql_num_rows($check_lang_exists)==0) { $error[]=$lang_errors['invalid_language']; }
	if (@mysql_result($check_lang_exists,0,"default") == "1") { $error[]=$lang_errors['language_delete_default']; }
	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("DELETE from `languages` WHERE `lang_name`='$lang_name'");
    	@del_file("../languages/$lang_name/frontend.lng");
    	@del_file("../languages/$lang_name/admin.lng");
    	@del_file("../languages/$lang_name/hints.lng");
    	@del_file("../languages/$lang_name/errors.lng");
    	@del_file("../languages/$lang_name/members.lng");
    	@del_file("../languages/$lang_name/globals.lng");    	
    	@del_file("../languages/$lang_name/lang_config.php");
    	@rmdir("../languages/$lang_name/");
		// Delete data assigned to this language
    	mquery("DELETE from `categories_text` WHERE `lang`='$lang_name'");
    	mquery("DELETE from `cities_text` WHERE `lang`='$lang_name'");
    	mquery("DELETE from `countries_text` WHERE `lang`='$lang_name'");
    	mquery("DELETE from `images_text` WHERE `lang`='$lang_name'");    	
    	mquery("DELETE from `news_text` WHERE `lang`='$lang_name'");
    	mquery("DELETE from `listings_text` WHERE `lang`='$lang_name'");
    	mquery("DELETE from `pages_text` WHERE `lang`='$lang_name'");
    	mquery("DELETE from `types_text` WHERE `lang`='$lang_name'");
    	mquery("DELETE from `types_c_text` WHERE `lang`='$lang_name'");
		mquery("DELETE from `settings` WHERE `lang`='$lang_name'");
		mquery("DELETE from `locations_text` WHERE `lang`='$lang_name'");
		mquery("DELETE from `email_templates` WHERE `lang`='$lang_name'");
		mquery("DELETE from `articles_text` WHERE `lang`='$lang_name'");
		
    	// Check and Delete data that have no assigned text
    	$check_cats=mquery("SELECT * from `categories`");
    	while($category=@mysql_fetch_array($check_cats)) {
			$check_data_cats=mquery("SELECT COUNT(*) from `categories_text` WHERE `cat_id`='$category[cat_id]'");
			if (@mysql_result($check_data_cats,0)=="0") {
				mquery("DELETE from `categories` WHERE `cat_id`='$category[cat_id]'");
			}
		}
    	$check_cities=mquery("SELECT * from `cities`");
    	while($city=@mysql_fetch_array($check_cities)) {
			$check_data_cities=mquery("SELECT COUNT(*) from `cities_text` WHERE `city_id`='$city[city_id]'");
			if (@mysql_result($check_data_cities,0)=="0") {
				mquery("DELETE from `cities` WHERE `city_id`='$city[city_id]'");
			}
		}
    	$check_countries=mquery("SELECT * from `countries`");
    	while($country=@mysql_fetch_array($check_countries)) {
			$check_data_countries=mquery("SELECT COUNT(*) from `countries_text` WHERE `country_id`='$country[country_id]'");
			if (@mysql_result($check_data_countries,0)=="0") {
				mquery("DELETE from `countries` WHERE `country_id`='$country[country_id]'");
			}
		}
    	$check_news=mquery("SELECT * from `news`");
    	while($nw=@mysql_fetch_array($check_news)) {
			$check_data_news=mquery("SELECT COUNT(*) from `news_text` WHERE `news_id`='$nw[news_id]'");
			if (@mysql_result($check_data_news,0)=="0") {
				mquery("DELETE from `news` WHERE `news_id`='$nw[news_id]'");
			}
		}
    	$check_listings=mquery("SELECT * from `listings`");
    	while($listing=@mysql_fetch_array($check_listings)) {
			$check_data_listings=mquery("SELECT COUNT(*) from `listings_text` WHERE `listing_id`='$listing[listing_id]'");
			if (@mysql_result($check_data_listings,0)=="0") {
				mquery("DELETE from `listings` WHERE `listing_id`='$listing[listing_id]'");
			}
		}
    	$check_pages=mquery("SELECT * from `pages`");
    	while($page=@mysql_fetch_array($check_pages)) {
			$check_data_pages=mquery("SELECT COUNT(*) from `pages_text` WHERE `page_id`='$page[page_id]'");
			if (@mysql_result($check_data_pages,0)=="0") {
				mquery("DELETE from `pages` WHERE `page_id`='$page[page_id]'");
			}
		}
    	$check_types=mquery("SELECT * from `types`");
    	while($type=@mysql_fetch_array($check_types)) {
			$check_data_types=mquery("SELECT COUNT(*) from `types_text` WHERE `type_id`='$type[type_id]'");
			if (@mysql_result($check_data_types,0)=="0") {
				mquery("DELETE from `types` WHERE `type_id`='$type[type_id]'");
			}
		}
    	$check_types_c=mquery("SELECT * from `types_c`");
    	while($type_c=@mysql_fetch_array($check_types_c)) {
			$check_data_types_c=mquery("SELECT COUNT(*) from `types_c_text` WHERE `type_c_id`='$type_c[type_c_id]'");
			if (@mysql_result($check_data_types_c,0)=="0") {
				mquery("DELETE from `types_c` WHERE `type_c_id`='$type_c[type_c_id]'");
		    	mquery("DELETE from `types` INNER JOIN `types_text` ON (types.type_id=types_text.type_id) WHERE types.type_c_id='$type_c[type_c_id]'");
			}
		}
    	$check_locations=mquery("SELECT * from `locations`");
    	while($location=@mysql_fetch_array($check_locations)) {
			$check_data_locations=mquery("SELECT COUNT(*) from `locations_text` WHERE `location_id`='$location[location_id]'");
			if (@mysql_result($check_data_locations,0)=="0") {
				mquery("DELETE from `locations` WHERE `location_id`='$location[location_id]'");
			}
		}
    	$check_articles=mquery("SELECT * from `articles`");
    	while($article=@mysql_fetch_array($check_articles)) {
			$check_data_articles=mquery("SELECT COUNT(*) from `articles_text` WHERE `article_id`='$article[article_id]'");
			if (@mysql_result($check_data_articles,0)=="0") {
				mquery("DELETE from `articles` WHERE `article_id`='$article[article_id]'");
			}
		}
		set_msg("Language <b>$lang_name</b> deleted successfuly");		
		header("Location: $config[base_url]/admin/languages.php");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$sql=mquery("SELECT * from `languages` ORDER BY `active` DESC");
		$languages=array();
		while($lang=@mysql_fetch_array($sql)) {
			array_push($languages, $lang);
		}
   		$t->assign('languages',$languages);
	}
	$t->display("admin/languages.tpl");
}
elseif (isset($_GET['activate'])) {
	$lang_name=sqlx($_GET['activate']);
	mquery("UPDATE `languages` SET `active`='1' WHERE `lang_name`='$lang_name'");
	set_msg("Language <b>$lang_name</b> is now active!");		
	header("Location: $config[base_url]/admin/languages.php");
}
elseif (isset($_GET['deactivate'])) {
	$lang_name=sqlx($_GET['deactivate']);
	$check_lang_exists=mquery("SELECT * from `languages` WHERE `lang_name`='$lang_name'");
	if (@mysql_num_rows($check_lang_exists)==0) { $error[]=$lang_errors['invalid_language']; }
	if (@mysql_result($check_lang_exists,0,"default") == "1") { $error[]=$lang_errors['language_deactivate_default']; }
	// If no errors...continue
	if (count($error)=="0")	{
		mquery("UPDATE `languages` SET `active`='0' WHERE `lang_name`='$lang_name'");
		set_msg("Language <b>$lang_name</b> is now inactive!");		
		header("Location: $config[base_url]/admin/languages.php");
	}else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/languages.tpl");
	}	
}
elseif (isset($_GET['default'])) {
	$lang_name=sqlx($_GET['default']);
	$check_lang_exists=mquery("SELECT * from `languages` WHERE `lang_name`='$lang_name'");
	if (@mysql_num_rows($check_lang_exists)==0) { $error[]=$lang_errors['invalid_language']; }
	// If no errors...continue
	if (count($error)=="0")	{
		switch_default_lang($lang_name);
		mquery("UPDATE `languages` SET `default`='1',`active`='1' WHERE `lang_name`='$lang_name'");
		mquery("UPDATE `languages` SET `default`='0' WHERE `lang_name`!='$lang_name'");
		set_msg("Language <b>$lang_name</b> set as default system language!");		
		header("Location: $config[base_url]/admin/languages.php");
	}else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/languages.tpl");
	}	
	
}
// START "ADD GLOBAL LANG VAR"
elseif (isset($_POST['add_global']) AND !empty($_POST['new_key']) AND !empty($_POST['new_var']) AND $lang_config['allow_add_globals']=='1') {
	$lang_name=sqlx($_POST['add_global']);
	$new_lang_key=sqlx($_POST['new_key']);
	$new_lang_var=sqlx($_POST['new_var']);
	$lang_globals=parse_ini_file("../languages/$lang_name/globals.lng");
	foreach ($lang_globals as $key => $value) {
			$output .= "$key = \"$value\"\n";
	}
	if (!empty($output)) {
		// add the new variable
		$output .= "$new_lang_key = \"$new_lang_var\"\n";		
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/globals.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Added new language <b>$lang_name</b> Global variable: $new_lang_var");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
// EOF "ADD GLOBAL LANG VAR"
// START "DELETE GLOBAL LANG VAR"
elseif (isset($_GET['del_var']) AND isset($_GET['del_global']) AND $lang_config['allow_del_globals']=='1') {
	$var_key=sqlx($_GET['del_var']);
	$lang_name=sqlx($_GET['del_global']);	
	$lang_globals=parse_ini_file("../languages/$lang_name/globals.lng");
	foreach ($lang_globals as $key => $value) {
		if ($key!==$var_key) { $output .= "$key = \"$value\"\n"; }
	}
	if (!empty($output)) {
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/globals.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Deleted language <b>$lang_name</b> variable: $var_key");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
// EOF "DELETE GLOBAL LANG VAR"

// START "ADD FRONTEND LANG VAR"
elseif (isset($_POST['add_frontend']) AND !empty($_POST['new_key']) AND !empty($_POST['new_var']) AND $lang_config['allow_add_frontend']=='1') {
	$lang_name=sqlx($_POST['add_frontend']);
	$new_lang_key=sqlx($_POST['new_key']);
	$new_lang_var=sqlx($_POST['new_var']);
	$lang_frontend=parse_ini_file("../languages/$lang_name/frontend.lng");
	foreach ($lang_frontend as $key => $value) {
			$output .= "$key = \"$value\"\n";
	}
	if (!empty($output)) {
		// add the new variable
		$output .= "$new_lang_key = \"$new_lang_var\"\n";		
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/frontend.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Added new language <b>$lang_name</b> Frontend variable: $new_lang_var");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
// EOF "ADD FRONTEND LANG VAR"
// START "DELETE FRONTEND LANG VAR"
elseif (isset($_GET['del_var']) AND isset($_GET['del_frontend']) AND $lang_config['allow_del_frontend']=='1') {
	$var_key=sqlx($_GET['del_var']);
	$lang_name=sqlx($_GET['del_frontend']);	
	$lang_frontend=parse_ini_file("../languages/$lang_name/frontend.lng");
	foreach ($lang_frontend as $key => $value) {
		if ($key!==$var_key) { $output .= "$key = \"$value\"\n"; }
	}
	if (!empty($output)) {
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/frontend.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Deleted language <b>$lang_name</b> variable: $var_key");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
// EOF "DELETE FRONTEND LANG VAR"

// START "ADD HINTS LANG VAR"
elseif (isset($_POST['add_hints']) AND !empty($_POST['new_key']) AND !empty($_POST['new_var']) AND $lang_config['allow_add_hints']=='1') {
	$lang_name=sqlx($_POST['add_hints']);
	$new_lang_key=sqlx($_POST['new_key']);
	$new_lang_var=sqlx($_POST['new_var']);
	$lang_hints=parse_ini_file("../languages/$lang_name/hints.lng");
	foreach ($lang_hints as $key => $value) {
			$output .= "$key = \"$value\"\n";
	}
	if (!empty($output)) {
		// add the new variable
		$output .= "$new_lang_key = \"$new_lang_var\"\n";		
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/hints.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Added new language <b>$lang_name</b> hints variable: $new_lang_var");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
// EOF "ADD HINTS LANG VAR"
// START "DELETE HINTS LANG VAR"
elseif (isset($_GET['del_var']) AND isset($_GET['del_hints']) AND $lang_config['allow_del_hints']=='1') {
	$var_key=sqlx($_GET['del_var']);
	$lang_name=sqlx($_GET['del_hints']);	
	$lang_hints=parse_ini_file("../languages/$lang_name/hints.lng");
	foreach ($lang_hints as $key => $value) {
		if ($key!==$var_key) { $output .= "$key = \"$value\"\n"; }
	}
	if (!empty($output)) {
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/hints.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Deleted language <b>$lang_name</b> variable: $var_key");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
// EOF "DELETE HINTS LANG VAR"

// START "ADD MEMBERS LANG VAR"
elseif (isset($_POST['add_members']) AND !empty($_POST['new_key']) AND !empty($_POST['new_var']) AND $lang_config['allow_add_members']=='1') {
	$lang_name=sqlx($_POST['add_members']);
	$new_lang_key=sqlx($_POST['new_key']);
	$new_lang_var=sqlx($_POST['new_var']);
	$lang_members=parse_ini_file("../languages/$lang_name/members.lng");
	foreach ($lang_members as $key => $value) {
			$output .= "$key = \"$value\"\n";
	}
	if (!empty($output)) {
		// add the new variable
		$output .= "$new_lang_key = \"$new_lang_var\"\n";		
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/members.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Added new language <b>$lang_name</b> members variable: $new_lang_var");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
// EOF "ADD MEMBERS LANG VAR"
// START "DELETE MEMBERS LANG VAR"
elseif (isset($_GET['del_var']) AND isset($_GET['del_members']) AND $lang_config['allow_del_members']=='1') {
	$var_key=sqlx($_GET['del_var']);
	$lang_name=sqlx($_GET['del_members']);	
	$lang_members=parse_ini_file("../languages/$lang_name/members.lng");
	foreach ($lang_members as $key => $value) {
		if ($key!==$var_key) { $output .= "$key = \"$value\"\n"; }
	}
	if (!empty($output)) {
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/members.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Deleted language <b>$lang_name</b> variable: $var_key");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
// EOF "DELETE MEMBERS LANG VAR"

// START "ADD ERRORS LANG VAR"
elseif (isset($_POST['add_errors']) AND !empty($_POST['new_key']) AND !empty($_POST['new_var']) AND $lang_config['allow_add_errors']=='1') {
	$lang_name=sqlx($_POST['add_errors']);
	$new_lang_key=sqlx($_POST['new_key']);
	$new_lang_var=sqlx($_POST['new_var']);
	$lang_errors=parse_ini_file("../languages/$lang_name/errors.lng");
	foreach ($lang_errors as $key => $value) {
			$output .= "$key = \"$value\"\n";
	}
	if (!empty($output)) {
		// add the new variable
		$output .= "$new_lang_key = \"$new_lang_var\"\n";		
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/errors.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Added new language <b>$lang_name</b> errors variable: $new_lang_var");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
// EOF "ADD ERRORS LANG VAR"
// START "DELETE ERRORS LANG VAR"
elseif (isset($_GET['del_var']) AND isset($_GET['del_errors']) AND $lang_config['allow_del_errors']=='1') {
	$var_key=sqlx($_GET['del_var']);
	$lang_name=sqlx($_GET['del_errors']);	
	$lang_errors=parse_ini_file("../languages/$lang_name/errors.lng");
	foreach ($lang_errors as $key => $value) {
		if ($key!==$var_key) { $output .= "$key = \"$value\"\n"; }
	}
	if (!empty($output)) {
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/errors.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Deleted language <b>$lang_name</b> variable: $var_key");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
// EOF "DELETE ERRORS LANG VAR"

// START "ADD ADMIN LANG VAR"
elseif (isset($_POST['add_admin']) AND !empty($_POST['new_key']) AND !empty($_POST['new_var']) AND $lang_config['allow_add_admin']=='1') {
	$lang_name=sqlx($_POST['add_admin']);
	$new_lang_key=sqlx($_POST['new_key']);
	$new_lang_var=sqlx($_POST['new_var']);
	$lang_admin=parse_ini_file("../languages/$lang_name/admin.lng");
	foreach ($lang_admin as $key => $value) {
			$output .= "$key = \"$value\"\n";
	}
	if (!empty($output)) {
		// add the new variable
		$output .= "$new_lang_key = \"$new_lang_var\"\n";		
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/admin.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Added new language <b>$lang_name</b> admin variable: $new_lang_var");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
// EOF "ADD ADMIN LANG VAR"
// START "DELETE ADMIN LANG VAR"
elseif (isset($_GET['del_var']) AND isset($_GET['del_admin']) AND $lang_config['allow_del_admin']=='1') {
	$var_key=sqlx($_GET['del_var']);
	$lang_name=sqlx($_GET['del_admin']);	
	$lang_admin=parse_ini_file("../languages/$lang_name/admin.lng");
	foreach ($lang_admin as $key => $value) {
		if ($key!==$var_key) { $output .= "$key = \"$value\"\n"; }
	}
	if (!empty($output)) {
		// Output array as PHP code
		$fh = @fopen("../languages/$lang_name/admin.lng", 'wb');
		if (!$fh) { die($lang_errors['language_cannot_write']); }
		fwrite($fh, $output);
		fclose($fh);
	}
	set_msg("Deleted language <b>$lang_name</b> variable: $var_key");
	header("Location: $config[base_url]/admin/languages.php?edit=$lang_name");
}
// EOF "DELETE ADMIN LANG VAR"

else {
	$sql=mquery("SELECT * from `languages` ORDER BY `active` DESC");
	$languages=array();
	while($lang=@mysql_fetch_array($sql)) {
		array_push($languages, $lang);
	}
   	$t->assign('languages',$languages);
	$t->display("admin/languages.tpl");
}
?>