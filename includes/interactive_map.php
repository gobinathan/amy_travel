<?php
require('GoogleMapAPI.class.php');
$map = new GoogleMapAPI();

// setup database for geocode caching
$map->setDSN("mysql://$db_user:$db_pass@$db_host/$db_name");
// enter YOUR Google Map Key
$map->setAPIKey($conf['gmap_api_key']);
$map->enableZoomEncompass();
//$map->enableOverviewControl();
$map->setInfoWindowTrigger('click');
$map->setMapType('hybrid');
$map->setZoomLevel(10);

// create some map markers
//$sql=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `gmap_location` IS NOT NULL AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
if (!empty($request[1]) AND $request[1]!=="search") {
	$listing_uri=sqlx($request[1]);
	$sql=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `uri`='$listing_uri' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
	$listings=array();
	while($listing=@mysql_fetch_array($sql)) {
		if (multiarray_search($listings, 'listing_id', $listing[listing_id]) == "-1") {
			if (listing_active($listing[start_date],$listing[end_date]) == true) {
				// Convert price
				if ($conf[auto_convert_currency]=="1" AND $listing[currency]!==$conf[currency]) {
					$listing[price]=convert_currency($listing[currency],$conf[currency],$listing[price]);
					$listing[currency]=$conf[currency];
				}
				array_push($listings, $listing);
			}
		}
	}	
	$map->disableSidebar();
	$map->setWidth('500px');
	$map->setHeight('450px');  
}else {
	// SEARCH
	if ($request[1]=="search") {
// START "SUBMIT SEARCH"
if (isset($_POST['search'])) {
	$cat_id=sqlx($_POST['cat_id']);
	$country_id=sqlx($_POST['country_id']);
	$keyword=sqlx($_POST['keyword']);
	$zip=sqlx($_POST['zip']);
	$price_from=sqlx($_POST['price_from']);
	$price_to=sqlx($_POST['price_to']);
	$city=sqlx($_POST['city']);
	$location_id=sqlx($_POST['location_id']);
	
	// SET SESSION values to REMEMBER SEARCH
	$_SESSION['search_cat_id']=$cat_id;
	$_SESSION['search_country_id']=$country_id;
	$_SESSION['search_city']=$city;
	$_SESSION['search_location_id']=$location_id;
	$_SESSION['search_keyword']=$keyword;
	$_SESSION['search_zip']=$zip;
	$_SESSION['search_price_from']=$price_from;
	$_SESSION['search_price_to']=$price_to;
	
if ($cat_id>"0") {
// START "FETCH OFFER CATEGORY SUBCATEGORIES"
// get main category info
$getcatinfo=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `cat_id`='$cat_id' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
if (@mysql_num_rows($getcatinfo) > "0") {
	$selected_category=@mysql_fetch_array($getcatinfo);
	$t->assign('selected_category',$selected_category); // Assign smarty array for the selected category
}else {
	$errors[]="No such category";
}

$getcats=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='$cat_id' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
$subcategories=array();
while($cat=@mysql_fetch_array($getcats)) {
	if (multiarray_search($subcategories, 'cat_id', $cat[cat_id]) == "-1") {
		array_push($subcategories, $cat);
	}
}
$t->assign('subcategories',$subcategories);
// EOF "FETCH OFFER CATEGORY SUBCATEGORIES"
}

// START "Construct search query"
$query_start="SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `active`='1' ";
if ($cat_id>"0") { $query .= "AND `cat_id`='$cat_id' "; }
if ($country_id>"0") { $query .= "AND `country_id`='$country_id' "; }
if ($city !== "0") { $query .= "AND `city` LIKE '%$city%' "; }
if ($location_id>"0") { $query .= "AND `location_id`='$location_id' "; }
if ($keyword!==$lang_globals['search_keyword'] AND !empty($keyword)) { $query .= "AND `title` LIKE '%$keyword%' OR `short_description` LIKE '%$keyword%' OR `description` LIKE '%$keyword%' OR `uri` LIKE '%$keyword%' "; }
if ($zip!==$lang_globals['zip'] AND !empty($zip)) { $query .= "AND `zip`='$zip' "; }
if (!empty($price_from)) {$price_min_value="`price`>='$price_from' ";}
if (!empty($price_to)) {$price_max_value="`price`<='$price_to' ";}
if (!empty($price_min_value) AND !empty($price_max_value)) {$price="$price_min_value AND $price_max_value ";}
if (!empty($price_min_value) AND empty($price_max_value)) {$price=$price_min_value;}
if (!empty($price_max_value) AND empty($price_min_value)) {$price=$price_max_value;}
if (!empty($price))	{ $query .= "AND $price "; }
$query_end="AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')";
$sql_query=$query_start.$query.$query_end;	
// EOF "Construct search query"

// START "FETCH OFFERS"
$getlistings=mysql_query("$sql_query");

$listings=array();
while($listing=@mysql_fetch_array($getlistings)) {
	if (multiarray_search($listings, 'listing_id', $listing[listing_id]) == "-1") {
		if (listing_active($listing[start_date],$listing[end_date]) == true) {
			array_push($listings, $listing);
		}
	}
}

if (count($subcategories)) {
	foreach ($subcategories as $subcat) {
		$subquery="";
		// START "Construct search subquery"
		if ($subcat[cat_id]>"0") { $subquery .= "AND `cat_id`='$subcat[cat_id]' "; }
		if ($country_id>"0") { $subquery .= "AND `country_id`='$country_id' "; }
		if ($keyword!==$lang_globals['search_keyword']) { $subquery .= "AND `title` LIKE '%$keyword%' OR `short_description` LIKE '%$keyword%' OR `description` LIKE '%$keyword%' OR `uri` LIKE '%$keyword%' "; }
		if ($zip!==$lang_globals['zip'] AND !empty($zip)) { $subquery .= "AND `zip`='$zip' "; }
		if (!empty($price))	{ $subquery .= "AND $price "; }
		$sql_subquery=$query_start.$subquery.$query_end;	
		// EOF "Construct search subquery"	  
		$getslistings=mysql_query("$sql_subquery");
		while($slisting=@mysql_fetch_array($getslistings)) {
			if (multiarray_search($listings, 'listing_id', $slisting[listing_id]) == "-1") {
				if (listing_active($slisting[start_date],$slisting[end_date]) == true) {
					array_push($listings, $slisting);
				}
			}
		}
	}
}
// EOF "FETCH OFFERS"
$_SESSION['search_listings_array']=$listings;
}
// EOF "SUBMIT SEARCH"	
	}
	// EOF SEARCH
	else {
		$sql=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `active`='1' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");	
		$listings=array();
		while($listing=@mysql_fetch_array($sql)) {
			if (multiarray_search($listings, 'listing_id', $listing[listing_id]) == "-1") {
				// Convert price
				if ($conf[auto_convert_currency]=="1" AND $listing[currency]!==$conf[currency]) {
					$listing[price]=convert_currency($listing[currency],$conf[currency],$listing[price]);
					$listing[currency]=$conf[currency];
				}
				array_push($listings, $listing);
			}
		}
	}
	$map->setWidth('800px');
	$map->setHeight('600px');  
	$t->assign('multi_listing',true);	
}
$listings=sortArrayByField($listings, "country_id");
foreach ($listings as $listing)  {
	$tpl_gb = & new_smarty();
	$tpl_gb->config_load("$language/globals.lng");
	$tpl_gb->config_load("$language/frontend.lng");
	$tpl_gb->config_load("$language/members.lng");
	$tpl_gb->assign('baseurl',$baseurl);
	$tpl_gb->assign('listing',$listing);
	$tpl_gb->assign('language',$language);		
	$baloon_description = $tpl_gb->fetch("frontend/$template/map_listing_baloon.tpl");
	$gmap_location="$listing[city], ".country2name($listing[country_id])."";  
	if (!empty($listing[gmap_location])) {
		$map->addMarkerByAddress($listing[gmap_location],$listing[title],$baloon_description);
	}else {
		$map->addMarkerByAddress($gmap_location,$listing[title],$baloon_description);
	}
}
// assign Smarty variables;
if (count($listings)) { $t->assign('listings',$listings); }
$t->assign('google_map_header',$map->getHeaderJS());
$t->assign('google_map_js',$map->getMapJS());
$t->assign('google_map_sidebar',$map->getSidebar());
$t->assign('google_map',$map->getMap());

$t->display("frontend/$template/interactive_map.tpl");
?>