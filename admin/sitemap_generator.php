<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");
if (isset($_POST['save_sitemap']) AND $demo == "0") {
	$links=array();
	$form = array_map('trim', $_POST['links']);
	foreach ($form as $key => $value) {
		$link['url']=$key;
		$link['priority']=$value;
		array_push($links, $link);
	}
	$tpl_sitemap = & new_smarty();
    $tpl_sitemap->force_compile = true;
    $tpl_sitemap->assign('links',$links);
	$xml_sitemap = $tpl_sitemap->fetch("admin/sitemap_xml.tpl");
	// Generate XML sitemap file
	$fh = @fopen("../sitemap.xml", 'wb');
	if (!$fh) { die("Cannot open sitemap.xml for writting. Please make sure the file exists and it have writable permissions"); }
	fwrite($fh, $xml_sitemap);
	fclose($fh);	
	set_msg("<b>sitemap.xml</b> generated successfuly!");		
}else {
$links=array();
// GET LANGUAGES
$getlangs=mysql_query("SELECT * from `languages` WHERE `active`='1' ORDER BY `default` DESC");
while($lng=@mysql_fetch_array($getlangs)) {
	$selected_lang=$lng['lang_name'];
	if ($lng['default']=="1") { 
		$priority="0";
		$root_url="$config[base_url]";
	}else{
		$priority="0.10";
		$root_url="$config[base_url]/$selected_lang";
	}

$main_link['url']=$root_url;
$main_link['priority']="1.0"-$priority;
array_push($links, $main_link);

// FETCH PAGES
$getpages=mysql_query("SELECT * from `pages` LEFT JOIN `pages_text` ON (pages.page_id=pages_text.page_id) WHERE `lang`='$selected_lang' ORDER BY `pages`.`added_date`");
while($page=@mysql_fetch_array($getpages)) {
		$link['url']="$root_url/page/$page[page_id]";
		$link['priority']="0.95"-$priority;
		array_push($links, $link);
}
// FETCH CATEGORIES AND SUBCATEGORIES
$getcats=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='0' AND categories_text.lang='$selected_lang' ORDER BY `position`");
while($cat=@mysql_fetch_array($getcats)) {
		$link['url']="$root_url/category/$cat[uri]";
		$link['priority']="0.95"-$priority;
		array_push($links, $link);
		$getsubcats=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='$cat[cat_id]' AND categories_text.lang='$selected_lang' ORDER BY `position`");
		while($subcat=@mysql_fetch_array($getsubcats)) {
			$link['url']="$root_url/category/$cat[uri]/$subcat[uri]";
			$link['priority']="0.93"-$priority;
			array_push($links, $link);
		}
} 
// FETCH LOCATIONS
$getlocations=mysql_query("SELECT * from `locations` LEFT JOIN `locations_text` ON (locations.location_id=locations_text.location_id) WHERE `lang`='$selected_lang' ORDER BY `position`");
while($location=@mysql_fetch_array($getlocations)) {
		$link['url']="$root_url/location/$location[uri]";
		$link['priority']="0.95"-$priority;
		array_push($links, $link);
}
// FETCH LISTINGS
$getlistings=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `lang`='$selected_lang' AND `include_sitemap`='1' ORDER BY `listings`.`added_date`");
while($listing=@mysql_fetch_array($getlistings)) {
		$link['url']="$root_url/listing/$listing[uri]";
		$link['priority']="0.90"-$priority;
		array_push($links, $link);
}
}//EOF OF LANGUAGES LOOP
}
$t->assign('links',$links);
$t->display("admin/sitemap.tpl");
?>
