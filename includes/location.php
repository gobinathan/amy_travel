<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$location_uri=sqlx($request[1]);
//get location details
//$getlocation=mysql_query("SELECT * from `locations` WHERE `uri`='$location_uri'");
$getlocation=mysql_query("SELECT * from `locations` LEFT JOIN `locations_text` ON (locations.location_id=locations_text.location_id) WHERE locations.uri='$location_uri' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
$location=mysql_fetch_array($getlocation);
$title=$location[title];
$t->assign('title', $title);

// START "FETCH OFFERS IN SELECTED LOCATION"
$getlistings=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `location_id`='$location[location_id]' AND `active`='1' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");

$listings=array();
while($listing=@mysql_fetch_array($getlistings)) {
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

// EOF "FETCH OFFERS IN SELECTED LOCATION"

// STAR PAGINATION
foreach ($request as $reqnum => $req) {
	if ($req == "page") {
		$page_num=$request[$reqnum+1];
	}
	if ($req == "sortby") {
		$_SESSION['sortby']=$request[$reqnum+1];
		if (!empty($request[$reqnum+2])) {
			$_SESSION['sortby_w']=$request[$reqnum+2];
		}else { $_SESSION['sortby_w']="desc"; }
	}
}
if (empty($page_num)) {$page_num="0";}
$count_listings=count($listings);
$t->assign('page_index',$page_num);
$t->assign('page_limit',$conf[items_per_page]);
$t->assign('page_total',$count_listings);
if ($request[2]!=="page" AND $request[2]!=="sortby" AND !empty($request[2])) {
	$page_uri="$baseurl/location/$request[1]/$request[2]";
}else {
	$page_uri="$baseurl/location/$request[1]";
}
$t->assign('page_uri',$page_uri);
if (!empty($_SESSION['sortby'])) {
	$sortby=$_SESSION['sortby'];
	if ($_SESSION['sortby_w'] == "asc") { $sortby_true=false; }
	if ($_SESSION['sortby_w'] == "desc") { $sortby_true=true; }
	if ($sortby == "price") { $listings=sortArrayByField($listings,"price",$sortby_true); }
	if ($sortby == "date") { $listings=sortArrayByField($listings,"added_date",$sortby_true); }
	if ($sortby == "stars") { $listings=sortArrayByField($listings,"stars",$sortby_true); }
}else {
	$listings=sortArrayByField($listings,"added_date",true); 
}

$listings_onpage=array_slice($listings,$page_num,$conf[items_per_page]);
$t->assign('count_page_listings', count($listings_onpage));
$t->assign('listings', $listings_onpage);
// EOF PAGINATION
if (count($error)) {
   	$t->assign('error',$error);
	$t->assign('error_count',count($error));  
}
$t->display("frontend/$template/list_listings.tpl");
?>