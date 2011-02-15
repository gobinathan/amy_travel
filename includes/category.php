<?php

// START "FETCH SUBCATEGORIES"

// get main category info
$cat_uri=sqlx($request[1]);
$getcatinfo=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `uri`='$cat_uri' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
if (@mysql_num_rows($getcatinfo) > "0") {
	$main_category=@mysql_fetch_array($getcatinfo);
	$t->assign('main_category',$main_category); // Assign smarty array for the selected category
    $title=$main_category[title];
}else {
	$error[]=$lang_errors['invalid_category'];
}
// get selected subcategory info
if (isset($request[2]) AND $request[2] !== "page" AND $request[2] !== "sortby") {
$scat_uri=sqlx($request[2]);
$sgetcatinfo=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `uri`='$scat_uri' AND `parent`='$main_category[cat_id]' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
if (@mysql_num_rows($sgetcatinfo) > "0") {
	$selected_category=@mysql_fetch_array($sgetcatinfo);
	$title="$selected_category[title] $title";
}else {
	$error[]=$lang_errors['invalid_category'];
}
}else {
	$selected_category=$main_category;
}
$t->assign('selected_category',$selected_category); // Assign smarty array for the selected category
$t->assign('title', $title);
// get main subcategories
$getcats=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='$main_category[cat_id]' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang'),`position`");
$subcategories=array();
while($cat=@mysql_fetch_array($getcats)) {
	if ($conf['show_empty_categories']=="0") {
	if (count_active_listings($cat[cat_id])) {
		if (multiarray_search($subcategories, 'cat_id', $cat[cat_id]) == "-1") {
			array_push($subcategories, $cat);
		}
	}}else {
		if (multiarray_search($subcategories, 'cat_id', $cat[cat_id]) == "-1") {
			array_push($subcategories, $cat);
		}	  
	}
}
$t->assign('subcategories',$subcategories);
// EOF "FETCH SUBCATEGORIES"
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// START "FETCH OFFERS IN SELECTED CATEGORY AND SUBCATEGORIES"
$getlistings=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `cat_id`='$selected_category[cat_id]' AND `active`='1' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");

$listings=array();
while($listing=@mysql_fetch_array($getlistings)) {
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
			// Convert price
			if ($conf[auto_convert_currency]=="1" AND $listing[currency]!==$conf[currency]) {
				$listing[price]=convert_currency($listing[currency],$conf[currency],$listing[price]);
				$listing[currency]=$conf[currency];
			}
			array_push($listings, $listing);
		}
	}
}

if (!isset($request[2]) OR $request[2] == "page" OR $request[2] == "sortby") {
	foreach ($subcategories as $subcat) {
		$getslistings=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `cat_id`='$subcat[cat_id]' AND `active`='1' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
		while($slisting=@mysql_fetch_array($getslistings)) {
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
					array_push($listings, $slisting);
				}
			}
		}
	}
}
//$t->assign('listings',$listings);
// EOF "FETCH OFFERS IN SELECTED CATEGORY AND SUBCATEGORIES"

// START "FETCH ARTICLES IN SELECTED CATEGORY"
$articles=array();
// fetch from parent category
$getarticles=mysql_query("SELECT * from `articles` LEFT JOIN `articles_text` ON (articles.article_id=articles_text.article_id) WHERE `cat_id`='$selected_category[parent]' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
while($article=@mysql_fetch_array($getarticles)) {
	if (multiarray_search($articles, 'article_id', $article[article_id]) == "-1") {
			array_push($articles, $article);
	}
}
$getarticles=mysql_query("SELECT * from `articles` LEFT JOIN `articles_text` ON (articles.article_id=articles_text.article_id) WHERE `cat_id`='$selected_category[cat_id]' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
while($article=@mysql_fetch_array($getarticles)) {
	if (multiarray_search($articles, 'article_id', $article[article_id]) == "-1") {
			array_push($articles, $article);
	}
}
$t->assign('articles',$articles);
// EOF "FETCH ARTICLES IN SELECTED CATEGORY"

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
if ($request[2]!=="page" AND $request[2]!=="sortby" AND !empty($request[2])) {
	$page_uri="$baseurl/category/$request[1]/$request[2]";
}else {
	$page_uri="$baseurl/category/$request[1]";
}
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
if (count($error)) {
   	$t->assign('error',$error);
	$t->assign('error_count',count($error));  
}
$t->display("frontend/$template/list_listings.tpl");
?>