<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php"); // Check if user is logged in as admin
include("common.php");

// START "ARTICLE ADD"
if (isset($_GET['add'])) {
   	$t->assign('categories',fetch_categories('1'));
	$t->display("admin/add_article.tpl");
}
// EOF "ARTICLE ADD"

// START "ARTICLE ADD SUBMIT FORM"
elseif (isset($_POST['add_article'])) {
  $title=sqlx($_POST['title']);
  $cat_id=sqlx($_POST['cat_id']);
  $article=addslashes(sqlx($_POST['article']));
  $date_added=time();
	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['articles_title_empty']; }
	if (empty($cat_id)) { $error[]=$lang_errors['articles_category_empty']; }
	if (empty($article)) { $error[]=$lang_errors['articles_content_empty']; }	

	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("INSERT into `articles` values ('','$cat_id','$date_added','$admin_id')");
    	$new_article_id=@mysql_insert_id();
    	mquery("INSERT into `articles_text` values ('$new_article_id','$default_lang','$title','$article')");
		set_msg("Article <b>$new_article_id</b> added successfuly!");
		header("Location: $config[base_url]/admin/articles.php?edit=$new_article_id");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	   	$t->assign('categories',fetch_categories('1'));		
		$t->display("admin/add_article.tpl");		
	}	
}
// EOF "ARTICLE ADD SUBMIT FORM"

// START "ARTICLE EDIT"
elseif (isset($_GET['edit'])) {
	$article_id=sqlx($_GET['edit']);
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
	$chkdb=mquery("SELECT * from `articles_text` WHERE `article_id`='$article_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
		$getarticleinfo=mquery("SELECT * from `articles` LEFT JOIN `articles_text` ON (articles.article_id=articles_text.article_id) WHERE articles.article_id='$article_id' AND `lang`='$edit_lang'");		
	}else {
		// Load default
		$getarticleinfo=mquery("SELECT * from `articles` LEFT JOIN `articles_text` ON (articles.article_id=articles_text.article_id) WHERE articles.article_id='$article_id' AND `lang`='$default_lang'");		
	}

	if (@mysql_num_rows($getarticleinfo)=="0") { $error[]=$lang_errors['invalid_article']; }
	if (count($error)=="0") {
		// Get categories
		$getcats=mquery("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='0' AND (categories_text.lang='$default_lang' OR categories_text.lang='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`position`");
		$categories=array();
		while($cat=@mysql_fetch_array($getcats)) {
			if (multiarray_search($categories, 'cat_id', $cat[cat_id]) == "-1") {
				array_push($categories, $cat);
			}
		} 
		$cats_final=array();	
		foreach($categories as $key => $row) { 
			$get_subcats=mquery("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='$row[cat_id]' AND (categories_text.lang='$default_lang' OR categories_text.lang='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`position`");
			$subcats=array();
			while($tps=@mysql_fetch_array($get_subcats)) {
				if (multiarray_search($subcats, 'cat_id', $tps[cat_id]) == "-1") {
					array_push($subcats, $tps);
				}
			}
		   	 $row['subcats'] = $subcats;		
			array_push($cats_final, $row);
		} 
	   	$t->assign('categories',$cats_final);
		$article=@mysql_fetch_array($getarticleinfo);
	   	$t->assign('article',$article);
		if (!empty($edit_lang)) {
			$t->assign('edit_lang', $edit_lang);
		}else {
			$t->assign('edit_lang', $default_lang);	  
		}	   	
		$t->display("admin/edit_article.tpl");
	} else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}		
}
// EOF "ARTICLE EDIT"

// START "ARTICLE EDIT SUBMIT FORM"
elseif (isset($_POST['edit_article'])) {
  $article_id=sqlx($_POST['edit_article']);
  $title=sqlx($_POST['title']);
  $cat_id=sqlx($_POST['cat_id']);
  $article=addslashes(sqlx($_POST['article']));
  $edit_lang=sqlx($_POST['edit_lang']);  
  $date_added=time();
	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['articles_title_empty']; }
	if (empty($cat_id)) { $error[]=$lang_errors['articles_brief_desc_empty']; }
	if (empty($article)) { $error[]=$lang_errors['articles_content_empty']; }	

	// If no errors...continue
	if (count($error)=="0")	{
		mquery("UPDATE `articles` SET `cat_id`='$cat_id' WHERE `article_id`='$article_id'");
		// Check if there is edit_lang in the DB
		$chkdb=mquery("SELECT * from `articles_text` WHERE `article_id`='$article_id' AND `lang`='$edit_lang'");
		if (@mysql_num_rows($chkdb)>0) {
   			mquery("UPDATE `articles_text` SET `title`='$title',`article`='$article' WHERE `article_id`='$article_id' AND `lang`='$edit_lang'");
		}else {
   			mquery("INSERT into `articles_text` values ('$article_id','$edit_lang','$title','$article')");
		}
		set_msg("Article <b>$article_id</b> updated successfuly!");
		header("Location: $config[base_url]/admin/articles.php?edit=$article_id&edit_lang=$edit_lang");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/edit_article.tpl");		
	}	
}
// EOF "ARTICLE EDIT SUBMIT FORM"

// START "ARTICLE DELETE"
elseif (isset($_GET['delete'])) {
  $article_id=trim($_GET['delete']);
  mquery("DELETE from `articles` WHERE `article_id`='$article_id'");
  mquery("DELETE from `articles_text` WHERE `article_id`='$article_id'");  
  set_msg("Article <b>$article_id</b> deleted successfuly!");
  header("Location: $config[base_url]/admin/articles.php");
}
// EOF "ARTICLE DELETE"

// START "SHOW ARTICLES"
else {
	$getarticles=mquery("SELECT * from `articles` LEFT JOIN `articles_text` ON (articles.article_id=articles_text.article_id) WHERE `lang`='$default_lang' OR `lang`='$edit_lang' ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`date_added`");
	$articles=array();
	while($nw=@mysql_fetch_array($getarticles)) {
		if (multiarray_search($articles, 'article_id', $nw[article_id]) == "-1") {
			array_push($articles, $nw);
		}
	}
   	$t->assign('articles',$articles);
	$t->display("admin/articles.tpl");
}
// EOF "SHOW ARTICLES"

?>
