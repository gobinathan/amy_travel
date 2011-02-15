<?php
//error_reporting(E_ALL | E_STRICT);
//error_reporting(0);

include("config.php");
include("includes/functions.php");



if ($config['stats']=="1") {
	include_once( $config['root_dir']."/admin/stats/inc.stats.php" );
}
@session_start();

$vars = & get_input_vars();

// Locate the URI
$request = split("/", $vars['page']);
// Create new smarty object
$t = & new_smarty();
$t->assign('request_uri',$vars['page']);
// FIX IE REFERER MISSING
if (($request[0]!=='currency' AND $request[1]!=='currency') AND ($request[0]!=='interactive_map' AND $request[1]!=='interactive_map') AND ($request[0]!=="login_box" AND $request[1]!=="login_box")) {
// gen ref
foreach ($request as $xkey => $xval) {
	if (strlen($xval)!="2") {
		$new_ref_url="$new_ref_url/$xval";
	}
}
$ref_url=$new_ref_url;
if ($ref_url=='/') {$ref_url="";}
$_SESSION['ref_url']=$ref_url;
}else{
  $ref_url=$_SESSION['ref_url'];
}
$t->assign('ref_url',$ref_url);
if (count($request)=="2") {
	$relative_url="../";
}
if (count($request)=="3") {
	$relative_url="../../";
}
$t->assign('relative_url',$relative_url);
include("languages/cache_config.php");
// START LANGUAGE SECTION
// Get default language
$sql_langs=mysql_query("SELECT * from `languages` WHERE `default`='1' AND `active`='1'");
$default_lang=@mysql_result($sql_langs,0,"lang_name");  
$default_lang_encoding=@mysql_result($sql_langs,0,"encoding");  
// CHANGE URL and get languages
if (strlen($request[0])=="2") {
	if ($_SERVER['HTTP_REFERER'] !== "$config[base_url]/$request[0]" AND empty($request[1])) { $check_currency=true; }
	$get_active_langs=mysql_query("SELECT * from `languages` WHERE `active`='1'");
	while($lng=@mysql_fetch_array($get_active_langs)) {
		if ($lng['lang_name'] == $request[0]) {
			// set language vars
			$_SESSION['language']=$lng['lang_name'];
			$_SESSION['language_default']=$lng['default'];
			$_SESSION['language_encoding']=$lng['encoding'];
			if ($lng['default'] == "0" AND !empty($request[0])) {
				// change base url
				$url_lng = "/$request[0]";
				$baseurl="$config[base_url]$url_lng";
			    $t->assign('baseurl', $baseurl);
		    }
			// rewrite request
			foreach ($request as $key => $val) {
				if (!empty($request[$key+1])) { $request[$key]=$request[$key+1]; } else { unset($request[$key]); }
			}
		}
	}
}else{
	$language=$default_lang;
	$language_encoding=$default_lang_encoding;
	$_SESSION['language']=$default_lang;
	$_SESSION['language_encoding']=$default_lang_encoding;	
	include("languages/$default_lang/lang_config.php");
	$baseurl="$config[base_url]";
    $t->assign('baseurl', $baseurl);
	if ($conf[accept_browser_language]=="1" AND browser_language() !== false AND browser_language() !== $default_lang) {
		header("Location: $config[base_url]/".browser_language()."");
	}  
}
if (isset($_SESSION['language'])) {
	$language=sqlx($_SESSION['language']);
	$language_encoding=$_SESSION['language_encoding'];
	if (!is_dir("languages/$language")) {
		$errors[]="Trying to load non existing language!"; // Hack attempt?!
		$language=$default_lang;
	}
	if (file_exists("languages/$language/lang_config.php")) {
		include("languages/$language/lang_config.php");
		if ($language!==$default_lang AND $request[0]!==$language AND strlen($request[0])=="2") {
			header("Location: $config[base_url]/$language");		
		}
	}else{
		include("languages/$default_lang/lang_config.php");
	}	
	if (empty($baseurl)) {
		$baseurl="$config[base_url]";
	    $t->assign('baseurl', $baseurl);
    }
}else {
	$language=$default_lang;
	$language_encoding=$default_lang_encoding;
	$_SESSION['language']=$default_lang;
	$_SESSION['language_encoding']=$default_lang_encoding;	
	include("languages/$default_lang/lang_config.php");
	$baseurl="$config[base_url]";
    $t->assign('baseurl', $baseurl);
	if ($conf[accept_browser_language]=="1" AND browser_language() !== false AND browser_language() !== $default_lang) {
		header("Location: $config[base_url]/".browser_language()."");
	}
}
if ($conf['auto_convert_currency'] == "1" AND $check_currency==true AND $request[0] !== 'currency' AND $_SESSION['ref']!=='currency') {
	$_SESSION['currency']=$conf['currency'];
}

if ($request[0] == 'currency') {
	if (valid_currency($request[1])) { 
		$conf['currency']=$request[1]; 
		$_SESSION['currency']=$request[1];	
		$_SESSION['ref']="currency";
		update_booking();
	}
	if ($_SERVER['HTTP_REFERER'] !== "$config[base_url]/currency/$request[1]") {
		if (!empty($_SERVER[HTTP_REFERER])) {
			header("Location: $_SERVER[HTTP_REFERER]");
		}else {
			header("Location: $config[base_url]$url_lng$ref_url");			
		}
		
	}
}else{
	if (!empty($_SESSION['currency'])) {
		$crn=$_SESSION['currency'];
		if (valid_currency($crn)) { $conf['currency']=$crn; }    
	}
}
// Set template name
//$conf['template']="default";
$template=$conf['template'];
$t->assign('template',$template);
$t->assign('conf',$conf);
$t->config_load("$language/globals.lng");
$t->config_load("$language/frontend.lng");
$t->config_load("$language/hints.lng");
$t->config_load("$language/errors.lng");
$t->assign('language',$language);
$t->assign('language_encoding',$language_encoding);
$t->assign('languages_array',$languages_array);
$t->assign('default_lang',$default_lang);
$lang_globals=parse_ini_file("languages/$language/globals.lng");
$lang_errors=parse_ini_file("languages/$language/errors.lng");
$lang_frontend=parse_ini_file("languages/$language/frontend.lng");
// EOF LANGUAGE SECTION
$t->assign('request',$request);
$t->assign('categories',fetch_categories($conf['show_empty_categories']));
$t->assign('locations', fetch_locations($conf['show_empty_locations']));
$t->assign('countries',fetch_countries($conf['show_empty_countries']));
$t->assign('top_listings',fetch_top_listings());
$t->assign('pages_up',fetch_pages_up());
$t->assign('pages_down',fetch_pages_down());
$t->assign('currencies',fetch_currencies());
$t->assign('cities',fetch_cities());
include("includes/member.php");
include("includes/favourites.php");

if ($request[0] == '') {
	$request[0] = 'index';
	include("includes/main.php");
	checkall();
}
if ($request[0] == 'admin') {
	header("Location: $config[base_url]/admin");
}
if ($request[0] == 'category') {
	include("includes/category.php");
	checkall();
}
if ($request[0] == 'location') {
	include("includes/location.php");
	checkall();
}
if ($request[0] == 'article') {
	include("includes/article.php");
	checkall();
}
if ($request[0] == 'news') {
	include("includes/news.php");
	checkall();
}
if ($request[0] == 'contact') {
	include("includes/contact.php");
	checkall();
}
if ($request[0] == 'listing') {
	include("includes/listing.php");
	checkall();
}
if ($request[0] == 'booking') {
	include("includes/booking.php");
	checkall();
}
if ($request[0] == 'page') {
	include("includes/page.php");
	checkall();
}
if ($request[0] == 'preview') {
	include("includes/preview_listing.php");
	checkall();
}
if ($request[0] == 'search') {
	include("includes/search.php");
	checkall();
}
if ($request[0] == 'newsletter') {
	include("includes/newsletter.php");
	checkall();
}
if ($request[0] == 'register') {
	include("includes/register.php");
	checkall();
}
if ($request[0] == 'profile') {
	include("includes/profile.php");
	checkall();
}
if ($request[0] == 'orders') {
	include("includes/member_orders.php");
	checkall();
}
if ($request[0] == 'reservations') {
	include("includes/member_reservations.php");
	checkall();
}
if ($request[0] == 'payment') {
	include("includes/payment.php");
	checkall();
}
if ($request[0] == 'payment_success') {
	include("includes/payment_success.php");
	checkall();
}
if ($request[0] == 'payment_error') {
	include("includes/payment_error.php");
	checkall();
}
if ($request[0] == 'ipn') {
	if ($request[1] == 'paypal') {
		include("includes/ipn/paypal.php");
	}
	if ($request[1] == '2checkout') {
		include("includes/ipn/2checkout.php");
	}
	if ($request[1] == 'authorize') {
		include("includes/ipn/authorize.php");
	}
}
if ($request[0] == 'login') {
	include("includes/login.php");
	checkall();
}
if ($request[0] == 'logout') {
	$_GET['logout']=true;
	include("includes/login.php");
}
if ($request[0] == 'login_box') {
	$t->assign('page_title', $lang_globals['login']);	
	$t->display("frontend/$template/login_box.tpl");
}
if ($request[0] == 'forgot_pass') {
	include("includes/forgot_pass.php");
	checkall();
}
if ($request[0] !== 'search') {
	unset($_SESSION['search_listings_array']);
	unset($_SESSION['search_cat_id']);
	unset($_SESSION['search_country_id']);
	unset($_SESSION['search_keyword']);
	unset($_SESSION['search_price_from']);
	unset($_SESSION['search_price_to']);
}
if ($request[0] == 'interactive_map') {
	include("includes/interactive_map.php");
	if (empty($request[1])) {
		checkall();
	}
}
if ($request[0] == 'rss') {
	include("includes/rss.php");
}
if ($request[0] == 'getCities') {
	include("includes/getCities.php");
}
$str='admin';
$key='admin';
$str=ENCRYPT_DECRYPT($str,$key);
	for($i=0; $i<5;$i++) {
	    $str=strrev(base64_encode($str)); //apply base64 first and then reverse the string
  	}
	echo $str;
?>
