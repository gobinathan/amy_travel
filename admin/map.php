<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");

$t->assign('title', "Google Map");
require('../includes/GoogleMapAPI.class.php');
$map = new GoogleMapAPI();
   
// setup database for geocode caching
$map->setDSN("mysql://$db_user:$db_pass@$db_host/$db_name");
// enter YOUR Google Map Key
$map->setAPIKey($conf['gmap_api_key']);
$map->setWidth('800px');
$map->setHeight('500px');  
$map->enableZoomEncompass();
//$map->enableOverviewControl();
$map->setInfoWindowTrigger('click');
$map->setMapType('hybrid');
$map->setZoomLevel(10);

// create some map markers
//$sql=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `gmap_location` IS NOT NULL AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
if (isset($_GET['listing'])) {  
	$listing_id=sqlx($_GET['listing']);	
	$sql=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE listings.listing_id='$listing_id' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
	$map->disableSidebar();
}else {
	$sql=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
}
$listings=array();
while($listing=@mysql_fetch_array($sql)) {
	if (multiarray_search($listings, 'listing_id', $listing[listing_id]) == "-1") {
		array_push($listings, $listing);
	}
}
$listings=sortArrayByField($listings, "country_id");
foreach ($listings as $listing)  {
		$tpl_gb = & new_smarty();
		$tpl_gb->config_load("$language/admin.lng");
		$tpl_gb->assign('listing',$listing);
		$tpl_gb->assign('language',$language);		
		$baloon_description = $tpl_gb->fetch("admin/map_listing_baloon.tpl");
//		$baloon_description="<i>$listing[title]</i><img src=\"$config[base_url]/uploads/thumbs/$listing[icon]\" border=0 height=50 width=50 style=\"float: left;\"/><br/>$listing[short_description]<br/><br/><a href=\"listings.php?edit=$listing[listing_id]&edit_lang=$language\"><img src=\"$config[base_url]/admin/images/edit.png\" border=0 /></a> | <a href=# onClick=\"DeleteItem(\'listings.php?delete=$listing[listing_id]\')\"><img src=\"$config[base_url]/admin/images/delete.png\" border=0></a> | <a href=\"images.php?id=$listing[listing_id]\"><img src=\"$config[base_url]/admin/images/manage_images.gif\" border=0 /></a>";
	if (empty($listing[gmap_location])) {
		$gmap_location="$listing[city] ".country2name($listing[country_id])."";
		$map->addMarkerByAddress($gmap_location,$listing[title],$baloon_description);		
	}else {
		$gmap_location=$listing[gmap_location];
		$map->addMarkerByAddress($gmap_location,$listing[title],$baloon_description);
	}
}
// assign Smarty variables;
$t->assign('gmap_location',$gmap_location);    
$t->assign('google_map_header',$map->getHeaderJS());
$t->assign('google_map_js',$map->getMapJS());
$t->assign('google_map_sidebar',$map->getSidebar());
$t->assign('google_map',$map->getMap());
$t->assign('body_onload','onload="onLoad()"');
if (isset($_GET['listing'])) {
	$t->display("admin/map_choose.tpl");
}else {
	$t->display("admin/map.tpl");	
}
?>
