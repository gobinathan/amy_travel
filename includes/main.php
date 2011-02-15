<?php
$t->assign('title', $t->get_config_vars('index_page'));

// START "FETCH NEWS"
$getnews=mysql_query("SELECT * from `news` LEFT JOIN `news_text` ON (news.news_id=news_text.news_id) WHERE news.visible='1' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang'),`position`");
$news=array();
while($nws=@mysql_fetch_array($getnews)) {
	if (multiarray_search($news, 'news_id', $nws[news_id]) == "-1") {
//			echo "ID: $listing[listing_id] : ".multiarray_search($listings, 'listing_id', $listing[listing_id])." : $edit_lang<br/>";
		array_push($news, $nws);
	}
}
$t->assign('news',$news);
// EOF "FETCH NEWS"

// START "FETCH POPULAR OFFERS"
$getpopularlistings=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `active`='1' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang'),`views` DESC LIMIT $conf[items_per_page]");
$popular_listings=array();
while($p_listing=@mysql_fetch_array($getpopularlistings)) {
	if (multiarray_search($popular_listings, 'listing_id', $p_listing[listing_id]) == "-1") {
		if (listing_active($p_listing[start_date],$p_listing[end_date]) == true) {
			// Convert price
			if ($conf[auto_convert_currency]=="1" AND $p_listing[currency]!==$conf[currency]) {
				$p_listing[price]=convert_currency($p_listing[currency],$conf[currency],$p_listing[price]);
				$p_listing[currency]=$conf[currency];
			}
			if ($p_listing['price_set']=='package') {
				$now=time();
				$package=fetch_package($p_listing['listing_id'],$now,$now);
				$p_listing['price']=$package['base_price'];
				$p_listing['price_desc']=$package['price_period'];
				if ($package['price_period']=="1") {$p_listing['price_desc']=$lang_globals['day'];}
				if ($package['price_period']=="7") {$p_listing['price_desc']=$lang_globals['week'];}
				if ($package['price_period']=="30") {$p_listing['price_desc']=$lang_globals['month'];}
				if ($package['price_period']=="365") {$p_listing['price_desc']=$lang_globals['year'];}			  
			}
			array_push($popular_listings, $p_listing);
		}
	}
}
$t->assign('popular_listings',$popular_listings);
// EOF "FETCH POPULAR OFFERS"

// START "FETCH LATEST OFFERS"
$getlatestlistings=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `active`='1' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang'),`added_date` DESC LIMIT $conf[items_per_page]");
$latest_listings=array();
while($l_listing=@mysql_fetch_array($getlatestlistings)) {
	if (multiarray_search($latest_listings, 'listing_id', $l_listing[listing_id]) == "-1") {
		if (listing_active($l_listing[start_date],$l_listing[end_date]) == true) {
			// Convert price
			if ($conf[auto_convert_currency]=="1" AND $l_listing[currency]!==$conf[currency]) {
				$l_listing[price]=convert_currency($l_listing[currency],$conf[currency],$l_listing[price]);
				$l_listing[currency]=$conf[currency];
			}
			if ($l_listing['price_set']=='package') {
				$now=time();
				$package=fetch_package($l_listing['listing_id'],$now,$now);
				$l_listing['price']=$package['base_price'];
				$l_listing['price_desc']=$package['price_period'];
				if ($package['price_period']=="1") {$l_listing['price_desc']=$lang_globals['day'];}
				if ($package['price_period']=="7") {$l_listing['price_desc']=$lang_globals['week'];}
				if ($package['price_period']=="30") {$l_listing['price_desc']=$lang_globals['month'];}
				if ($package['price_period']=="365") {$l_listing['price_desc']=$lang_globals['year'];}			  
			}
			array_push($latest_listings, $l_listing);
		}
	}
}
$t->assign('latest_listings',$latest_listings);
// EOF "FETCH LATEST OFFERS"

$t->display("frontend/$template/index.tpl");
?>