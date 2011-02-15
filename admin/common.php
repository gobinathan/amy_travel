<?php
$t = & new_smarty();
$t->assign('last_login',$_SESSION['last_login']);
if ($demo=="1") {
	$t->assign('demo_mode','1');
}
include("../languages/cache_config.php");
// START LANGUAGE SECTION
// Get default language
$sql_langs=mysql_query("SELECT * from `languages` WHERE `default`='1' AND `active`='1'");
$default_lang=@mysql_result($sql_langs,0,"lang_name");  
$default_lang_encoding=@mysql_result($sql_langs,0,"encoding");  

if (isset($_GET['lang'])) {
	$language=sqlx($_GET['lang']); // Da se proveri
	$sql=mysql_query("SELECT * from `languages` WHERE `lang_name`='$language' AND `active`='1'");
	if (@mysql_num_rows($sql)>0 AND is_dir("../languages/$language")) {
		$language_encoding=@mysql_result($sql,0,"encoding");
		$_SESSION['language']=$language;
		$_SESSION['language_encoding']=$language_encoding;
		if (file_exists("../languages/$language/lang_config.php")) {
			include("../languages/$language/lang_config.php");
		}else{
			include("../languages/$default_lang/lang_config.php");
		}
	}
}else {	
	if (isset($_SESSION['language'])) {
		$language=sqlx($_SESSION['language']);
		$language_encoding=sqlx($_SESSION['language_encoding']);		
		if (!is_dir("../languages/$language")) {
			$errors[]="Trying to load not existing language!"; // Hack attempt?!
			$language=$default_lang;
			$language_encoding=$default_lang_encoding;
		}
		if (file_exists("../languages/$language/lang_config.php")) {
			include("../languages/$language/lang_config.php");
		}else{
			include("../languages/$default_lang/lang_config.php");
		}
	}
	else {
		$language=$default_lang;
		$language_encoding=$default_lang_encoding;
		$_SESSION['language']=$default_lang;
		$_SESSION['language_encoding']=$default_lang_encoding;		
		include("../languages/$default_lang/lang_config.php");		
	}
}
if (!isset($_GET['edit_lang']) AND !isset($_POST['edit_lang'])) {
	$edit_lang=$language;
}else {
	if (isset($_POST['edit_lang'])) {
		$edit_lang=sqlx($_POST['edit_lang']);
	}else {
		$edit_lang=sqlx($_GET['edit_lang']);
	}
}
$t->assign('edit_lang',$edit_lang);
// Set template name
//$conf['template']="default";
$template=$conf['template'];
$t->assign('template',$template);
$t->assign('conf',$conf);
$t->config_load("$language/globals.lng");
$t->config_load("$language/admin.lng");
$t->config_load("$language/hints.lng");
$t->config_load("$language/errors.lng");
$t->assign('language',$language);
$t->assign('language_encoding',$language_encoding);
$t->assign('languages_array',$languages_array);
//$t->assign('get_query',before("lang=",$get_query));

$t->assign('time',time());
$lang_globals=parse_ini_file("../languages/$language/globals.lng");
$lang_admin=parse_ini_file("../languages/$language/admin.lng");
$lang_errors=parse_ini_file("../languages/$language/errors.lng");
// EOF LANGUAGE SECTION
//Update currencies if needed
$today = date("Y-m-d");
if ($conf[last_currency_update] != $today) { update_rates(); }
?>