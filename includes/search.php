<?php
$t->assign('title', $lang_globals['search_title']);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// START "ADVANCED SEARCH"
if ($request[1]=="advanced") {
// START "SUBMIT SEARCH"
if (isset($_POST['search'])) {
	$cat_id=sqlx($_POST['cat_id']);
	$country_id=sqlx($_POST['country_id']);
	$city=sqlx($_POST['city']);
	$state_id=sqlx($_POST['state_id']);	
	$location_id=sqlx($_POST['location_id']);
	$price_from=sqlx($_POST['price_from']);
	$price_to=sqlx($_POST['price_to']);
	$price_currency=sqlx($_POST['price_currency']);
	$keyword=sqlx($_POST['keyword']);
	$internal_area_from=sqlx($_POST['internal_from']);
	$internal_area_to=sqlx($_POST['internal_to']);
	$external_area_from=sqlx($_POST['external_from']);
	$external_area_to=sqlx($_POST['external_to']);
	$rooms_from=sqlx($_POST['rooms_from']);
	$rooms_to=sqlx($_POST['rooms_to']);
	$bathrooms_from=sqlx($_POST['bathrooms_from']);
	$bathrooms_to=sqlx($_POST['bathrooms_to']);
	$bedrooms_from=sqlx($_POST['bedrooms_from']);
	$bedrooms_to=sqlx($_POST['bedrooms_to']);
	$floors_from=sqlx($_POST['floors_from']);
	$floors_to=sqlx($_POST['floors_to']);
	$yb_from=sqlx($_POST['yb_from']);
	$yb_to=sqlx($_POST['yb_to']);
	$garages_from=sqlx($_POST['garages_from']);
	$garages_to=sqlx($_POST['garages_to']);
  	$types=@implode("|",$_POST['types']);
	$order_by=sqlx($_POST['order_by']);
	$per_page=sqlx($_POST['per_page']);
						
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
if ($cat_id>"0") { $catquery .= "AND `cat_id`='$cat_id' "; }
if ($country_id>"0") { $query .= "AND `country_id`='$country_id' "; }
if ($city !== "0") { $query .= "AND `city` LIKE '%$city%' "; }
if ($location_id>"0") { $query .= "AND `location_id`='$location_id' "; }
if ($keyword!==$lang_globals['search_keyword'] AND !empty($keyword)) { $query .= "AND `title` LIKE '%$keyword%' OR `short_description` LIKE '%$keyword%' OR `description` LIKE '%$keyword%' OR `uri` LIKE '%$keyword%' "; }
if (!empty($internal_area_from)) { $query .= "AND `internal_area`>='$internal_area_from' "; }
if (!empty($internal_area_to)) { $query .= "AND `internal_area`<='$internal_area_to' "; }
if (!empty($external_area_from)) { $query .= "AND `external_area`>='$external_area_from' "; }
if (!empty($external_area_to)) { $query .= "AND `external_area`<='$external_area_to' "; }
if (!empty($rooms_from)) { $query .= "AND `rooms`>='$rooms_from' "; }
if (!empty($rooms_to)) { $query .= "AND `rooms`<='$rooms_to' "; }
if (!empty($bedrooms_from)) { $query .= "AND `bedrooms`>='$bedrooms_from' "; }
if (!empty($bedrooms_to)) { $query .= "AND `bedrooms`<='$bedrooms_to' "; }
if (!empty($bathrooms_from)) { $query .= "AND `bathrooms`>='$bathrooms_from' "; }
if (!empty($bathrooms_to)) { $query .= "AND `bathrooms`<='$bathrooms_to' "; }
if (!empty($garages_from)) { $query .= "AND `garages`>='$garages_from' "; }
if (!empty($garages_to)) { $query .= "AND `garages`<='$garages_to' "; }
if (!empty($floors_from)) { $query .= "AND `floors`>='$floors_from' "; }
if (!empty($floors_to)) { $query .= "AND `floors`<='$floors_to' "; }
if (!empty($yb_from)) { $query .= "AND `year_built`>='$yb_from' "; }
if (!empty($yb_to)) { $query .= "AND `year_built`<='$yb_to' "; }
if (!empty($types)) {
	$types=explode("|",$types);
	foreach ($types as $type) {
		$query .= "AND `types` LIKE '%$type%' ";
	}
}
$query_end="AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')";
$sql_query=$query_start.$catquery.$query.$query_end;	
//echo $sql_query;
// EOF "Construct search query"

// START "FETCH OFFERS"
$getlistings=mysql_query("$sql_query");
$listings=array();
while($listing=@mysql_fetch_array($getlistings)) {
	$listing_price_to_match="";
	$listing_price_from_match="";
	if (multiarray_search($listings, 'listing_id', $listing[listing_id]) == "-1") {
		if (listing_active($listing[start_date],$listing[end_date])) {
			if ($listing['price_set']=='package') {
				$now=time();
				$package=fetch_package($listing['listing_id'],$now,$now);
				$listing['price']=$package['base_price'];
				$listing['price_desc']=$package['price_period'];
				if ($package['price_period']=="1") {$listing['price_desc']=$lang_globals['day'];}
				if ($package['price_period']=="7") {$listing['price_desc']=$lang_globals['week'];}
				if ($package['price_period']=="30") {$listing['price_desc']=$lang_globals['month'];}
				if ($package['price_period']=="365") {$listing['price_desc']=$lang_globals['year'];}			  
			}

			// Convert price if auto_convert is on
			if ($conf[auto_convert_currency]=="1" AND $listing[currency]!==$conf[currency]) {
				$listing[price]=convert_currency($listing[currency],$conf[currency],$listing[price]);
				$listing[currency]=$conf[currency];
			}
			// Check price and convert if necesary
			if (!empty($price_from) AND is_numeric($price_from)) {
				if ($listing[currency]!==$price_currency) {
					// calculate currency
					$listing[price]=convert_currency($listing[currency],$price_currency,$listing[price]);
					$listing[currency]=$price_currency;
				}
				if ($price_from > $listing[price]) { 
					$listing_price_from_match=false;
				}
			}
			if (!empty($price_to) AND is_numeric($price_to)) {
				if ($listing[currency]!==$price_currency) {
					// calculate currency
					$listing[price]=convert_currency($listing[currency],$price_currency,$listing[price]);
					$listing[currency]=$price_currency;
				}
				if ($price_to < $listing[price]) { 
					$listing_price_to_match=false;
				}
			}
			// EOF Check price
		if ($listing_price_from_match !== false AND $listing_price_to_match !== false) {
			array_push($listings, $listing);
		}
		}		  
	}
}

if (count($subcategories)) {
	foreach ($subcategories as $subcat) {
		$subquery="";
		// START "Construct search subquery"
		if ($subcat[cat_id]>"0") { $subcatquery .= "AND `cat_id`='$subcat[cat_id]' "; }
		$sql_subquery=$query_start.$subcatquery.$query.$query_end;	
		// EOF "Construct search subquery"	  
		$getslistings=mysql_query("$sql_subquery");
		while($slisting=@mysql_fetch_array($getslistings)) {
			$listing_price_to_match="";
			$listing_price_from_match="";
			if (multiarray_search($listings, 'listing_id', $slisting[listing_id]) == "-1") {
				if (listing_active($slisting[start_date],$slisting[end_date]) == true) {
					if ($slisting['price_set']=='package') {
						$now=time();
						$spackage=fetch_package($slisting['listing_id'],$now,$now);
						$slisting['price']=$spackage['base_price'];
						$slisting['price_desc']=$spackage['price_period'];
						if ($spackage['price_period']=="1") {$slisting['price_desc']=$lang_globals['day'];}
						if ($spackage['price_period']=="7") {$slisting['price_desc']=$lang_globals['week'];}
						if ($spackage['price_period']=="30") {$slisting['price_desc']=$lang_globals['month'];}
						if ($spackage['price_period']=="365") {$slisting['price_desc']=$lang_globals['year'];}			  
					}

					// Convert price
					if ($conf[auto_convert_currency]=="1" AND $slisting[currency]!==$conf[currency]) {
						$slisting[price]=convert_currency($slisting[currency],$conf[currency],$slisting[price]);
						$slisting[currency]=$conf[currency];
					}
					// Check price and convert if necesary
					if (!empty($price_from) AND is_numeric($price_from)) {
						if ($slisting[currency]!==$price_currency) {
							// calculate currency
							$slisting[price]=convert_currency($slisting[currency],$price_currency,$slisting[price]);
							$slisting[currency]=$price_currency;
						}
						if ($price_from > $slisting[price]) { 
							$listing_price_from_match=false;
						}
					}
					if (!empty($price_to) AND is_numeric($price_to)) {
						if ($slisting[currency]!==$price_currency) {
							// calculate currency
							$slisting[price]=convert_currency($slisting[currency],$price_currency,$slisting[price]);
							$slisting[currency]=$price_currency;
						}
						if ($price_to < $listing[price]) { 
							$listing_price_to_match=false;
						}
					}
					// EOF Check price
				if ($listing_price_from_match !== false AND $listing_price_to_match !== false) {
					array_push($listings, $slisting);
				}
				}
			}
		}
	}
}
// EOF "FETCH OFFERS"
//unset($_SESSION['sortby']);
if ($_SESSION['sortby_w'] == "asc") { $sortby_true=false; }
if ($_SESSION['sortby_w'] == "desc") { $sortby_true=true; }
elseif (!empty($_SESSION['sortby_w'])){ $sortby_true=true; }
if ($sort_by=="date") { $listings=sortArrayByField($listings,"added_date",$sortby_true); }
if ($sort_by=="price") { $listings=sortArrayByField($listings,"price",$sortby_true); }
if ($sort_by=="category") { $listings=sortArrayByField($listings,"cat_id",$sortby_true); }
if ($sort_by=="location") { $listings=sortArrayByField($listings,"location_id",$sortby_true); }
if ($sort_by=="country") { $listings=sortArrayByField($listings,"country_id",$sortby_true); }
if ($sort_by=="state") { $listings=sortArrayByField($listings,"state_id",$sortby_true); }
if ($sort_by=="year_built") { $listings=sortArrayByField($listings,"year_built",$sortby_true); }
if ($sort_by=="floors") { $listings=sortArrayByField($listings,"floors",$sortby_true); }
if ($sort_by=="rooms") { $listings=sortArrayByField($listings,"rooms",$sortby_true); }
if ($sort_by=="internal_area") { $listings=sortArrayByField($listings,"internal_area",$sortby_true); }
if ($sort_by=="external_area") { $listings=sortArrayByField($listings,"external_area",$sortby_true); }
$_SESSION['per_page']=$per_page;
$_SESSION['search_listings_array']=$listings;
}else {
if (empty($request[2])) {
	$show_tpl=true;
	// get states
	$getstates=mysql_query("SELECT * from `states` LEFT JOIN `states_text` ON (states.state_id=states_text.state_id) WHERE (states_text.lang='$default_lang' OR states_text.lang='$language') ORDER BY FIELD(lang,'$language','$default_lang'),states.state_code");
	$states=array();
	while($st=@mysql_fetch_array($getstates)) {
		if (multiarray_search($states, 'state_id', $st[state_id]) == "-1") {
			array_push($states, $st);
		}
	}
   	$t->assign('states',$states);
	//get types
//////////////////////
	$sql=mysql_query("SELECT * from `types_c` LEFT JOIN `types_c_text` ON (types_c.type_c_id=types_c_text.type_c_id) WHERE `lang`='$default_lang' OR `lang`='$edit_lang' ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
	$types_c=array();
	while($type_c=@mysql_fetch_array($sql)) {
		if (multiarray_search($types_c, 'type_c_id', $type_c[type_c_id]) == "-1") {	  
			array_push($types_c, $type_c);
		}
	}
	$types_final=array();	
	foreach($types_c as $key => $row) { 
		$get_types=mysql_query("SELECT * from `types` LEFT JOIN `types_text` ON (types.type_id=types_text.type_id) WHERE `type_c_id`='$row[type_c_id]' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
		$types=array();
		while($tps=@mysql_fetch_array($get_types)) {
			if (multiarray_search($types, 'type_id', $tps[type_id]) == "-1") {
				array_push($types, $tps);
			}
		}
	   	 $row['types'] = $types;		
		array_push($types_final, $row);
	} 

	// Assign a template variable 
	$t->assign('types_c', $types_final);	
	$t->display("frontend/$template/advanced_search.tpl");	
	}  
	$listings=$_SESSION['search_listings_array'];
}
// EOF "SUBMIT SEARCH"
if (count($listings) AND $show_tpl!==true) {
// STAR PAGINATION
foreach ($request as $reqnum => $req) {
	if ($req == "page") {
		$page_num=$request[$reqnum+1];
	}
	if ($req == "sortby") {
		$_SESSION['sortby']=$request[$reqnum+1];
		if (!empty($request[$reqnum+2])) {
			if ($request[$reqnum+2]=='asc') {
				$_SESSION['sortby_w']=$request[$reqnum+2];
			}
			if ($request[$reqnum+2]=='desc') {
				$_SESSION['sortby_w']=$request[$reqnum+2];
			}
		}
	}
}
if (empty($page_num)) {$page_num="0";}
$count_listings=count($listings);
$t->assign('page_index',$page_num);
if (!empty($_SESSION['per_page'])) {
	$per_page=$_SESSION['per_page'];
}else{
	$per_page=$conf[items_per_page];
}
$t->assign('page_limit',$per_page);
$t->assign('page_total',$count_listings);
$page_uri="$baseurl/search/advanced";

$t->assign('page_uri',$page_uri);
if (!empty($_SESSION['sortby'])) {
	$sortby=$_SESSION['sortby'];
	if ($_SESSION['sortby_w'] == "asc") { $sortby_true=false; }
	if ($_SESSION['sortby_w'] == "desc") { $sortby_true=true; }
	if ($sortby == "price") { $listings=sortArrayByField($listings,"price",$sortby_true); }
	if ($sortby == "stars") { $listings=sortArrayByField($listings,"stars",$sortby_true); }
	if ($sortby == "date") { $listings=sortArrayByField($listings,"added_date",$sortby_true); }
	if ($sortby == "location") { $listings=sortArrayByField($listings,"location_id",$sortby_true); }	
	if ($sortby == "city") { $listings=sortArrayByField($listings,"city",$sortby_true); }	
}
$listings_onpage=array_slice($listings,$page_num,$per_page);
$t->assign('count_page_listings', count($listings_onpage));
$t->assign('listings', $listings_onpage);
// EOF PAGINATION
$t->display("frontend/$template/list_listings.tpl");
} 
elseif (!count($listings) AND $show_tpl!==true) {
	$t->display("frontend/$template/list_listings.tpl");	
}
}
// EOF "ADVANCED SEARCH"

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
else {
// START "SUBMIT SEARCH"
if (isset($_POST['search']) OR $request[1]=="all") {
	$cat_id=sqlx($_POST['cat_id']);
	$country_id=sqlx($_POST['country_id']);
	$keyword=sqlx($_POST['keyword']);
	$city=sqlx($_POST['city']);
	$price_from=sqlx($_POST['price_from']);
	$price_to=sqlx($_POST['price_to']);
	
	// SET SESSION values to REMEMBER SEARCH
	$_SESSION['search_cat_id']=$cat_id;
	$_SESSION['search_country_id']=$country_id;
	$_SESSION['search_keyword']=$keyword;
	$_SESSION['search_city']=$city;
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
if ($keyword!==$lang_globals['search_keyword'] AND !empty($keyword)) { $query .= "AND `title` LIKE '%$keyword%' OR `short_description` LIKE '%$keyword%' OR `description` LIKE '%$keyword%' OR `uri` LIKE '%$keyword%' "; }
if (!empty($city) AND $city > "0") { $query .= "AND `city` LIKE '%$city%' "; }
//if (!empty($price_from)) {$price_min_value="`price`>='$price_from' ";}
//if (!empty($price_to)) {$price_max_value="`price`<='$price_to' ";}
//if (!empty($price_min_value) AND !empty($price_max_value)) {$price="$price_min_value AND $price_max_value ";}
//if (!empty($price_min_value) AND empty($price_max_value)) {$price=$price_min_value;}
//if (!empty($price_max_value) AND empty($price_min_value)) {$price=$price_max_value;}
//if (!empty($price))	{ $query .= "AND $price "; }
$query_end="AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')";
$sql_query=$query_start.$query.$query_end;	
// EOF "Construct search query"

// START "FETCH OFFERS"
$getlistings=mysql_query("$sql_query");

$listings=array();
while($listing=@mysql_fetch_array($getlistings)) {
	$listing_price_to_match="";
	$listing_price_from_match="";
	if (multiarray_search($listings, 'listing_id', $listing[listing_id]) == "-1") {
		if (listing_active($listing[start_date],$listing[end_date]) == true) {
			if ($listing['price_set']=='package') {
				$now=time();
				$package=fetch_package($listing['listing_id'],$now,$now);
				$listing['price']=$package['base_price'];
				$listing['price_desc']=$package['price_period'];
				if ($package['price_period']=="1") {$listing['price_desc']=$lang_globals['day'];}
				if ($package['price_period']=="7") {$listing['price_desc']=$lang_globals['week'];}
				if ($package['price_period']=="30") {$listing['price_desc']=$lang_globals['month'];}
				if ($package['price_period']=="365") {$listing['price_desc']=$lang_globals['year'];}			  
			}

			// Convert price if auto_convert is on
			if ($conf[auto_convert_currency]=="1" AND $listing[currency]!==$conf[currency]) {
				$listing[price]=convert_currency($listing[currency],$conf[currency],$listing[price]);
				$listing[currency]=$conf[currency];
			}

			// Check price and convert if necesary
			if (!empty($price_from) AND is_numeric($price_from)) {
				if ($listing[currency]!==$conf[currency]) {
					// calculate currency
					$listing[price]=convert_currency($listing[currency],$conf[currency],$listing[price]);
					$listing[currency]=$conf[currency];
				}
				if ($price_from > $listing[price]) { 
					$listing_price_from_match=false;
				}
			}
			if (!empty($price_to) AND is_numeric($price_to)) {
				if ($listing[currency]!==$conf[currency]) {
					// calculate currency
					$listing[price]=convert_currency($listing[currency],$conf[currency],$listing[price]);
					$listing[currency]=$conf[currency];
				}
				if ($price_to < $listing[price]) { 
					$listing_price_to_match=false;
				}
			}
			// EOF Check price
			if ($listing_price_from_match !== false AND $listing_price_to_match !== false) {
				array_push($listings, $listing);
			}
			
//				array_push($listings, $listing);
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
					if ($listing['price_set']=='package') {
						$now=time();
						$package=fetch_package($listing['listing_id'],$now,$now);
						$listing['price']=$package['base_price'];
						$listing['price_desc']=$package['price_period'];
						if ($package['price_period']=="1") {$listing['price_desc']=$lang_globals['day'];}
						if ($package['price_period']=="7") {$listing['price_desc']=$lang_globals['week'];}
						if ($package['price_period']=="30") {$listing['price_desc']=$lang_globals['month'];}
						if ($package['price_period']=="365") {$listing['price_desc']=$lang_globals['year'];}			  
					}
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
else {
	$listings=$_SESSION['search_listings_array'];
}
if (count($listings)) {
// STAR PAGINATION
foreach ($request as $reqnum => $req) {
	if ($req == "page") {
		$page_num=$request[$reqnum+1];
	}
	if ($req == "sortby") {
		$_SESSION['sortby']=$request[$reqnum+1];
		if (!empty($request[$reqnum+2])) {
			if ($request[$reqnum+2]=='asc') {
				$_SESSION['sortby_w']=$request[$reqnum+2];
			}
			if ($request[$reqnum+2]=='desc') {
				$_SESSION['sortby_w']=$request[$reqnum+2];
			}
		}
	}
}
if (empty($page_num)) {$page_num="0";}
$count_listings=count($listings);
$t->assign('page_index',$page_num);
$t->assign('page_limit',$conf[items_per_page]);
$t->assign('page_total',$count_listings);
$page_uri="$baseurl/search";

$t->assign('page_uri',$page_uri);
if (!empty($_SESSION['sortby'])) {
	$sortby=$_SESSION['sortby'];
	if ($_SESSION['sortby_w'] == "asc") { $sortby_true=false; }
	if ($_SESSION['sortby_w'] == "desc") { $sortby_true=true; }
	if ($sortby == "price") { $listings=sortArrayByField($listings,"price",$sortby_true); }
	if ($sortby == "stars") { $listings=sortArrayByField($listings,"stars",$sortby_true); }
	if ($sortby == "date") { $listings=sortArrayByField($listings,"added_date",$sortby_true); }
	if ($sortby == "location") { $listings=sortArrayByField($listings,"location_id",$sortby_true); }	
	if ($sortby == "city") { $listings=sortArrayByField($listings,"city",$sortby_true); }	
}else {
	$listings=sortArrayByField($listings,"added_date",true); 
}
$listings_onpage=array_slice($listings,$page_num,$conf[items_per_page]);
$t->assign('count_page_listings', count($listings_onpage));
$t->assign('listings', $listings_onpage);
// EOF PAGINATION
}

$t->display("frontend/$template/list_listings.tpl");
}
?>