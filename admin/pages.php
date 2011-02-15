<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php"); // Check if user is logged in as admin
include("common.php");

// START "PAGE ADD"
if (isset($_GET['add'])) {
	$t->display("admin/add_page.tpl");
}
// EOF "PAGE ADD"

// START "PAGE ADD SUBMIT FORM"
elseif (isset($_POST['add_page'])) {
  $title=sqlx($_POST['title']);
  $uri=sqlx($_POST['uri']);
  $where=sqlx($_POST['where']);
  $page_content=sqlx($_POST['page_content']);
  $get_max_position=mquery("SELECT MAX(`position`) from `pages` WHERE `where`='$where'");
  $max_position=@mysql_result($get_max_position,0);  
  $position=$max_position+1;  
  $meta_keywords=sqlx($_POST['meta_keywords']);
  $meta_description=addslashes(sqlx($_POST['meta_description']));
  $date_added=time();
	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['page_title_empty']; }
	if (empty($page_content)) { $error[]=$lang_errors['page_content_empty']; }
	if (!empty($uri)) {
		$check_uri_exists=mquery("SELECT * from `pages` WHERE `uri`='$uri'");
		if (@mysql_num_rows($check_uri_exists)>0) { $error[]=$lang_errors['page_uri_exists']; }
	}
	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("INSERT into `pages` values ('','$uri','$position','$date_added','$where')");
    	$new_page_id=@mysql_insert_id();
    	if (empty($uri)) {
			$uri=make_uri("$title",$new_page_id);
			mquery("UPDATE `pages` SET `uri`='$uri' WHERE `page_id`='$new_page_id'");
		}    	
    	mquery("INSERT into `pages_text` values ('$new_page_id','$default_lang','$title','$page_content','$meta_keywords','$meta_description')");
		set_msg("Page <b>$title</b> added successfuly!");		
		header("Location: $config[base_url]/admin/pages.php?edit=$new_page_id");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/add_page.tpl");		
	}	
}
// EOF "PAGE ADD SUBMIT FORM"

// START "PAGE EDIT"
elseif (isset($_GET['edit'])) {
	$page_id=sqlx($_GET['edit']);
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
	$chkdb=mquery("SELECT * from `pages_text` WHERE `page_id`='$page_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
		$getpageinfo=mquery("SELECT * from `pages` LEFT JOIN `pages_text` ON (pages.page_id=pages_text.page_id) WHERE pages.page_id='$page_id' AND `lang`='$edit_lang'");		
	}else {
		// Load default
		$getpageinfo=mquery("SELECT * from `pages` LEFT JOIN `pages_text` ON (pages.page_id=pages_text.page_id) WHERE pages.page_id='$page_id' AND `lang`='$default_lang'");		
	}

	if (@mysql_num_rows($getpageinfo)=="0") { $error[]=$lang_errors['invalid_page']; }
	if (count($error)=="0") {
		$page=@mysql_fetch_array($getpageinfo);
	   	$t->assign('page',$page);
		if (!empty($edit_lang)) {
			$t->assign('edit_lang', $edit_lang);
		}else {
			$t->assign('edit_lang', $default_lang);	  
		}	   	
		$t->display("admin/edit_page.tpl");
	} else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}		
}
// EOF "PAGE EDIT"

// START "PAGE EDIT SUBMIT FORM"
elseif (isset($_POST['edit_page'])) {
  $page_id=sqlx($_POST['edit_page']);
  $title=sqlx($_POST['title']);
  $uri=sqlx($_POST['uri']);
  $where=sqlx($_POST['where']);
  $page_content=sqlx($_POST['page_content']);
  $meta_keywords=sqlx($_POST['meta_keywords']);
  $meta_description=addslashes(sqlx($_POST['meta_description']));
  $edit_lang=sqlx($_POST['edit_lang']);  
  $date_added=time();
	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['page_title_empty']; }
	if (empty($uri)) { $error[]=$lang_errors['page_uri_empty']; }
	if (empty($page_content)) { $error[]=$lang_errors['page_content_empty']; }
	$check_uri_exists=mquery("SELECT * from `pages` WHERE `uri`='$uri' AND `page_id`!='$page_id'");
	if (@mysql_num_rows($check_uri_exists)>0) { $error[]=$lang_errors['page_uri_exists']; }

	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("UPDATE `pages` SET `uri`='$uri',`date_added`='$date_added',`where`='$where' WHERE `page_id`='$page_id'");
		// Check if there is edit_lang in the DB
		$chkdb=mquery("SELECT * from `pages_text` WHERE `page_id`='$page_id' AND `lang`='$edit_lang'");
		if (@mysql_num_rows($chkdb)>0) {
   			mquery("UPDATE `pages_text` SET `title`='$title',`content`='$page_content',`meta_keywords`='$meta_keywords',`meta_description`='$meta_description' WHERE `page_id`='$page_id' AND `lang`='$edit_lang'");
		}else {
   			mquery("INSERT into `pages_text` values ('$page_id','$edit_lang','$title','$page_content','$meta_keywords','$meta_description')");
		}
		set_msg("Page <b>$title</b> updated successfuly!");		
		header("Location: $config[base_url]/admin/pages.php?edit=$page_id&edit_lang=$edit_lang");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/edit_page.tpl");		
	}	
}
// EOF "PAGE EDIT SUBMIT FORM"

// START "PAGE DELETE"
elseif (isset($_GET['delete'])) {
  $page_id=trim($_GET['delete']);
  mquery("DELETE from `pages` WHERE `page_id`='$page_id'");
  mquery("DELETE from `pages_text` WHERE `page_id`='$page_id'");  
  set_msg("Page ID <b>$page_id</b> deleted successfuly!");		
  header("Location: $config[base_url]/admin/pages.php");
}
// EOF "PAGE DELETE"

// START "PAGE POSITION"
elseif (isset($_GET['move_up'])) {
	$page_id=sqlx($_GET['move_up']);
	$get_pos=mquery("SELECT `position`,`where` from `pages` WHERE `page_id`='$page_id'");
	$where=@mysql_result($get_pos,0,"where");
	$cur_pos=@mysql_result($get_pos,0,"position");
	$new_pos=$cur_pos-1;
	$get_up_pos=mquery("UPDATE `pages` SET `position`='$cur_pos' WHERE `position`='$new_pos' AND `where`='$where'");
	$set_up_pos=mquery("UPDATE `pages` SET `position`='$new_pos' WHERE `page_id`='$page_id'");	
	header("Location: $config[base_url]/admin/pages.php");
}
elseif (isset($_GET['move_down'])) {
	$page_id=sqlx($_GET['move_down']);
	$get_pos=mquery("SELECT `position`,`where` from `pages` WHERE `page_id`='$page_id'");
	$where=@mysql_result($get_pos,0,"where");	
	$cur_pos=@mysql_result($get_pos,0,"position");
	$new_pos=$cur_pos+1;
	$get_up_pos=mquery("UPDATE `pages` SET `position`='$cur_pos' WHERE `position`='$new_pos' AND `where`='$where'");
	$set_up_pos=mquery("UPDATE `pages` SET `position`='$new_pos' WHERE `page_id`='$page_id'");
	header("Location: $config[base_url]/admin/pages.php");
}
// EOF "PAGE POSITION"

// START "SHOW PAGES"
else {
	$getpages_up=mquery("SELECT * from `pages` LEFT JOIN `pages_text` ON (pages.page_id=pages_text.page_id) WHERE `where`='Up' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`position`");
	$pages_up=array();
	while($page=@mysql_fetch_array($getpages_up)) {
		if (multiarray_search($pages_up, 'page_id', $page[page_id]) == "-1") {
			array_push($pages_up, $page);
		}
	}
	$get_min_position_up=mquery("SELECT MIN(`position`) from `pages` WHERE `where`='Up'");
	$min_position_up=@mysql_result($get_min_position_up,0);
	$get_max_position_up=mquery("SELECT MAX(`position`) from `pages` WHERE `where`='Up'");
	$max_position_up=@mysql_result($get_max_position_up,0);
	$t->assign('min_position_up',$min_position_up);
	$t->assign('max_position_up',$max_position_up);
   	$t->assign('pages_up',$pages_up);

	$getpages_down=mquery("SELECT * from `pages` LEFT JOIN `pages_text` ON (pages.page_id=pages_text.page_id) WHERE `where`='Down' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`position`");
	$pages_down=array();
	while($page=@mysql_fetch_array($getpages_down)) {
		if (multiarray_search($pages_down, 'page_id', $page[page_id]) == "-1") {
			array_push($pages_down, $page);
		}
	}
	$get_min_position_down=mquery("SELECT MIN(`position`) from `pages` WHERE `where`='Down'");
	$min_position_down=@mysql_result($get_min_position_down,0);
	$get_max_position_down=mquery("SELECT MAX(`position`) from `pages` WHERE `where`='Down'");
	$max_position_down=@mysql_result($get_max_position_down,0);
	$t->assign('min_position_down',$min_position_down);
	$t->assign('max_position_down',$max_position_down);
   	$t->assign('pages_down',$pages_down);

	$t->display("admin/pages.tpl");
}
// EOF "SHOW PAGES"

?>
