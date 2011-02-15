<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php"); // Check if user is logged in as admin
include("common.php");

// START "NEWS ADD"
if (isset($_GET['add'])) {
	$t->display("admin/add_news.tpl");
}
// EOF "NEWS ADD"

// START "NEWS ADD SUBMIT FORM"
elseif (isset($_POST['add_news'])) {
  $title=sqlx($_POST['title']);
  $brief_description=addslashes(sqlx($_POST['brief_description']));
  $full_article=sqlx($_POST['full_article']);
  $visible=sqlx($_POST['visible']);
  if ($visible=="on") {$visible="1";}else{$visible="0";}
  $get_max_position=mquery("SELECT MAX(`position`) from `news`");
  $max_position=@mysql_result($get_max_position,0);  
  $position=$max_position+1;  
  $date_added=time();
	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['news_title_empty']; }
//	if (empty($brief_description)) { $error[]=$lang_errors['news_brief_desc_empty']; }
//	if (empty($full_article)) { $error[]=$lang_errors['news_content_empty']; }	

	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("INSERT into `news` values ('','$position','$date_added','$admin_id','$visible')");
    	$new_news_id=@mysql_insert_id();
    	mquery("INSERT into `news_text` values ('$new_news_id','$default_lang','$title','$brief_description','$full_article')");
		set_msg("News ID <b>$new_news_id</b> added successfuly!");		
		header("Location: $config[base_url]/admin/news.php?edit=$new_news_id");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/add_news.tpl");		
	}	
}
// EOF "NEWS ADD SUBMIT FORM"

// START "NEWS EDIT"
elseif (isset($_GET['edit'])) {
	$news_id=sqlx($_GET['edit']);
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
	$chkdb=mquery("SELECT * from `news_text` WHERE `news_id`='$news_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
		$getnewsinfo=mquery("SELECT * from `news` LEFT JOIN `news_text` ON (news.news_id=news_text.news_id) WHERE news.news_id='$news_id' AND `lang`='$edit_lang'");		
	}else {
		// Load default
		$getnewsinfo=mquery("SELECT * from `news` LEFT JOIN `news_text` ON (news.news_id=news_text.news_id) WHERE news.news_id='$news_id' AND `lang`='$default_lang'");		
	}

	if (@mysql_num_rows($getnewsinfo)=="0") { $error[]=$lang_errors['invalid_article']; }
	if (count($error)=="0") {
		$news=@mysql_fetch_array($getnewsinfo);
	   	$t->assign('news',$news);
		if (!empty($edit_lang)) {
			$t->assign('edit_lang', $edit_lang);
		}else {
			$t->assign('edit_lang', $default_lang);	  
		}	   	
		$t->display("admin/edit_news.tpl");
	} else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}		
}
// EOF "NEWS EDIT"

// START "NEWS EDIT SUBMIT FORM"
elseif (isset($_POST['edit_news'])) {
  $news_id=sqlx($_POST['edit_news']);
  $title=sqlx($_POST['title']);
  $brief_description=addslashes(sqlx($_POST['brief_description']));
  $full_article=sqlx($_POST['full_article']);
  $visible=sqlx($_POST['visible']);
  if ($visible=="on") {$visible="1";}else{$visible="0";}
  $edit_lang=sqlx($_POST['edit_lang']);  
  $date_added=time();
	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['news_title_empty']; }
//	if (empty($brief_description)) { $error[]=$lang_errors['news_brief_desc_empty']; }
//	if (empty($full_article)) { $error[]=$lang_errors['news_content_empty']; }	

	// If no errors...continue
	if (count($error)=="0")	{
		// Check if there is edit_lang in the DB
		$chkdb=mquery("SELECT * from `news_text` WHERE `news_id`='$news_id' AND `lang`='$edit_lang'");
		if (@mysql_num_rows($chkdb)>0) {
   			mquery("UPDATE `news` SET `visible`='$visible' WHERE `news_id`='$news_id'");
   			mquery("UPDATE `news_text` SET `title`='$title',`brief_description`='$brief_description',`full_article`='$full_article' WHERE `news_id`='$news_id' AND `lang`='$edit_lang'");
		}else {
   			mquery("INSERT into `news_text` values ('$news_id','$edit_lang','$title','$brief_description','$full_article')");
		}
		set_msg("News ID <b>$news_id</b> updated successfuly!");		
		header("Location: $config[base_url]/admin/news.php?edit=$news_id&edit_lang=$edit_lang");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/edit_news.tpl");		
	}	
}
// EOF "NEWS EDIT SUBMIT FORM"

// START "NEWS DELETE"
elseif (isset($_GET['delete'])) {
  $news_id=trim($_GET['delete']);
  mquery("DELETE from `news` WHERE `news_id`='$news_id'");
  mquery("DELETE from `news_text` WHERE `news_id`='$news_id'");  
	set_msg("News ID <b>$news_id</b> deleted successfuly!");		
  header("Location: $config[base_url]/admin/news.php");
}
// EOF "NEWS DELETE"

// START "NEWS POSITION"
elseif (isset($_GET['move_up'])) {
	$news_id=sqlx($_GET['move_up']);
	$get_pos=mquery("SELECT `position` from `news` WHERE `news_id`='$news_id'");
	$cur_pos=@mysql_result($get_pos,0,"position");
	$new_pos=$cur_pos-1;
	$get_up_pos=mquery("UPDATE `news` SET `position`='$cur_pos' WHERE `position`='$new_pos'");
	$set_up_pos=mquery("UPDATE `news` SET `position`='$new_pos' WHERE `news_id`='$news_id'");	
	header("Location: $config[base_url]/admin/news.php");
}
elseif (isset($_GET['move_down'])) {
	$news_id=sqlx($_GET['move_down']);
	$get_pos=mquery("SELECT `position` from `news` WHERE `news_id`='$news_id'");
	$cur_pos=@mysql_result($get_pos,0,"position");
	$new_pos=$cur_pos+1;
	$get_up_pos=mquery("UPDATE `news` SET `position`='$cur_pos' WHERE `position`='$new_pos'");
	$set_up_pos=mquery("UPDATE `news` SET `position`='$new_pos' WHERE `news_id`='$news_id'");
	header("Location: $config[base_url]/admin/news.php");
}
// EOF "NEWS POSITION"

// START "SHOW NEWS"
else {
	$getnews=mquery("SELECT * from `news` LEFT JOIN `news_text` ON (news.news_id=news_text.news_id) WHERE `lang`='$default_lang' OR `lang`='$edit_lang' ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`position`");
	$news=array();
	while($nw=@mysql_fetch_array($getnews)) {
		if (multiarray_search($news, 'news_id', $nw[news_id]) == "-1") {
			array_push($news, $nw);
		}
	}
	$get_min_position=mquery("SELECT MIN(`position`) from `news`");
	$min_position=@mysql_result($get_min_position,0);
	$get_max_position=mquery("SELECT MAX(`position`) from `news`");
	$max_position=@mysql_result($get_max_position,0);
	$t->assign('min_position',$min_position);
	$t->assign('max_position',$max_position);
   	$t->assign('news',$news);
	$t->display("admin/news.tpl");
}
// EOF "SHOW NEWS"

?>
