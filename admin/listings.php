<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php"); // Check if user is logged in as admin
include("common.php");
if ($gd=="yes") {include("../includes/thumb.class.php");}

// START "OFFER ADD"
if (isset($_GET['add'])) {
   	$t->assign('categories',fetch_categories('1'));
   	$t->assign('countries',fetch_countries('1'));
	$t->assign('types_c', fetch_types());
	$t->assign('locations', fetch_locations('1'));
	$t->assign('states', fetch_states());
   	$t->assign('currencies',fetch_currencies());
	$t->display("admin/add_listing.tpl");
}
// EOF "OFFER ADD"

// START "OFFER ADD SUBMIT FORM"
elseif (isset($_POST['add_listing'])) {
  $title=sqlx($_POST['title']);
  $uri=sqlx($_POST['uri']);
  $cat_id=sqlx($_POST['cat_id']);
  $country_id=sqlx($_POST['country_id']);
  $city=sqlx($_POST['city']);
  $state_id=sqlx($_POST['state_id']);
  $location_id=sqlx($_POST['location_id']);  
  $description=sqlx($_POST['description']);
  $short_description=addslashes(sqlx($_POST['short_description']));
  $price=sqlx($_POST['price']);
  $currency=sqlx($_POST['currency']);
  $price_desc=sqlx($_POST['price_desc']);  
  $gmap_location=sqlx($_POST['gmap_location']);
  if (strpos($gmap_location,'(') OR strpos($gmap_location,')')) {
		$gmap_location=between('(',')',$gmap_location);
  }  
  $allow_reservation=sqlx($_POST['allow_reservation']);
  if ($allow_reservation=="on") {$allow_reservation="1";}else{$allow_reservation="0";}
  $allow_payment=sqlx($_POST['allow_payment']);
  if ($allow_payment=="on") {$allow_payment="1";}else{$allow_payment="0";}
  $require_payment=sqlx($_POST['require_payment']);
  if ($require_payment=="on") {$require_payment="1";}else{$require_payment="0";}
  $active=sqlx($_POST['active']);
  if ($active=="on") {$active="1";}else{$active="0";}
  $special=sqlx($_POST['special']);
  if ($special=="on") {$special="1";}else{$special="0";}
  $include_sitemap=sqlx($_POST['include_sitemap']);
  if ($include_sitemap=="on") {$include_sitemap="1";}else{$include_sitemap="0";}

  $start_date=sqlx($_POST['start_date']);
  if (empty($start_date)) { $start_date = "0"; }
  else {
  	$start_date_str=explode('/',$start_date);
  	$start_date=mktime(0, 0, 0, $start_date_str[1], $start_date_str[0], $start_date_str[2]);
  }
  $end_date=sqlx($_POST['end_date']);
  if (empty($end_date)) { $end_date = "0"; }
  else {
	  $end_date_str=explode('/',$end_date);
	  $end_date=mktime(0, 0, 0, $end_date_str[1], $end_date_str[0], $end_date_str[2]);
  }  
  $stars=sqlx($_POST['stars']);
  $types=@implode("|",$_POST['types']);
  $contact_details=sqlx($_POST['contact_details']);
  $added_date=time();

	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['listing_title_empty']; }
	if (empty($cat_id)) { $error[]=$lang_errors['listing_cat_empty']; }
	if (empty($country_id)) { $error[]=$lang_errors['listing_country_empty']; }
	if (empty($city)) { $error[]=$lang_errors['listing_city_empty']; }
	if (empty($location_id)) { $error[]=$lang_errors['listing_location_empty']; }
	if (empty($short_description)) { $error[]=$lang_errors['listing_short_desc_empty']; }
	if (empty($description)) { $error[]=$lang_errors['listing_desc_empty']; }
//	if (empty($price)) { $error[]=$lang_errors['listing_price_empty']; }
//	if (empty($currency)) { $error[]=$lang_errors['listing_currency_empty']; }
	if (!empty($price) AND !is_numeric($price)) { $error[]=$lang_errors['listing_price_not_num']; }	
	if (!empty($uri)) {
		$check_uri_exists=mquery("SELECT * from `listings` WHERE `uri`='$uri'");
		if (@mysql_num_rows($check_uri_exists)>0) { $error[]=$lang_errors['listing_uri_exists']; }
	}
	if (empty($gmap_location)) { $gmap_location="$city ".country2name($country_id).""; }
//die(print_r($type_complex_equipment));
	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("INSERT into `listings` values ('','$config[default_icon]','$uri','$cat_id','$country_id','$location_id','$state_id','static','$price','$currency','$stars','$types','$added_date','$admin_id','$special','$gmap_location','0','$active','$start_date','$end_date','$include_sitemap','$allow_reservation','$allow_payment','$require_payment')") or die(mysql_error());
    	$ins_id=@mysql_insert_id();
		////////// UPLOAD IMAGE    	
		if ($_FILES['icon']['size']) { // Add Image submit
			$now=time();
			// Get the thumbnail
			$upload_dir = "../uploads/images/";
			$upload_dir_thumbs = "../uploads/thumbs/";
			$uploaded = do_upload($upload_dir,"icon","$ins_id.$now");
			$uploaded=substr($uploaded, 1);
			$image=after_last('/',$uploaded);
			// Create Thumbnail
			if ($gd=="yes" AND $conf[create_thumbs]=="1") {
				$tm = new dThumbMaker; 
				$load = $tm->loadFile($upload_dir.$image);
				if($load === true){ // Note three '='      
				    $tm->resizeMaxSize($conf[thumb_resize_h], $conf[thumb_resize_w]); 
	//				$tm->addWaterMark('images/watermark.gif', 64, 64, true);
			    	$tm->build($upload_dir_thumbs.$image);
				}
			}
			//EOF THUMB creation	

			// Resize Image
			if ($gd=="yes" AND $conf[img_resize]=="1") {
				$tm = new dThumbMaker; 
				$load = $tm->loadFile($upload_dir.$image);
				if($load === true){ // Note three '='      
				    $tm->resizeMaxSize($conf[img_resize_h], $conf[img_resize_w]); 
					if ($conf[watermark_images]=="1") {
						$tm->addWaterMark("../uploads/$conf[watermark_image_file]", $conf[watermark_position_x], $conf[watermark_position_y], true);
					}
				    $tm->build($upload_dir.$image); 
				}
			}
			//EOF Resize Image
			// Watermark Image
			if ($gd=="yes" AND $conf[watermark_images]=="1" AND $conf[img_resize]!="1") {
				$tm = new dThumbMaker; 
				$load = $tm->loadFile($upload_dir.$image);
				if($load === true){ // Note three '='      
					$tm->addWaterMark("../uploads/$conf[watermark_image_file]", $conf[watermark_position_x], $conf[watermark_position_y], true);
				    $tm->build($upload_dir.$image); 
				}
			}		
			// EOF Watermark Image		
			mquery("INSERT into `images` values ('','$image','$ins_id')");
			$new_image_id=@mysql_insert_id();
			mquery("INSERT into `images_text` values ('$new_image_id','$default_lang','$title')");
		}else{
			$image=$config['default_icon'];
		}
		if (empty($mls)) { $mls=$ins_id; }				
		mquery("UPDATE `listings` SET `icon`='$image' WHERE `listing_id`='$ins_id'");		
		////////// EOF UPLOAD IMAGE
		
    	if (empty($uri)) {
			$uri=make_uri("$title",$ins_id);
			mquery("UPDATE `listings` SET `uri`='$uri' WHERE `listing_id`='$ins_id'");
		}
    	mquery("INSERT into `listings_text` values ('$ins_id','$default_lang','$title','$short_description','$description','$price_desc','$contact_details','$city')");
		set_msg("Listing ID <b>$ins_id</b> added successfuly!");		
		header("Location: $config[base_url]/admin/listings.php?edit=$ins_id&edit_lang=$default_lang");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	   	$t->assign('categories',fetch_categories('1'));
   		$t->assign('countries',fetch_countries('1'));
		$t->assign('types_c', fetch_types());
		$t->assign('locations', fetch_locations('1'));
		$t->assign('states', fetch_states());
	   	$t->assign('currencies',fetch_currencies());   	
		$t->display("admin/add_listing.tpl");		
	}	
}
// EOF "OFFER ADD SUBMIT FORM"

// START "OFFER EDIT"
elseif (isset($_GET['edit'])) {
	$listing_id=sqlx($_GET['edit']);
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
	$chkdb=mquery("SELECT * from `listings_text` WHERE `listing_id`='$listing_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
		$getlistinginfo=mquery("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE listings.listing_id='$listing_id' AND `lang`='$edit_lang'");		
	}else {
		// Load default
		$getlistinginfo=mquery("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE listings.listing_id='$listing_id' AND `lang`='$default_lang'");		
	}

	if (@mysql_num_rows($getlistinginfo)=="0") { $error[]=$lang_errors['invalid_listing']; }

	if (count($error)=="0") {
		$listing=@mysql_fetch_array($getlistinginfo);
		// Get Listing Images
		$getimages=mquery("SELECT * from `images` LEFT JOIN `images_text` ON (images.image_id=images_text.image_id) WHERE `listing_id`='$listing_id' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')"); 
		$images=array();
		while($img=@mysql_fetch_array($getimages)) {
			if (multiarray_search($images, 'image_id', $img[image_id]) == "-1") {
				array_push($images, $img[file]);
			}
		}
   		$t->assign('images',$images);
	   	$t->assign('listing',$listing);
		$listing_types=explode("|",$listing[types]);
		$t->assign('listing_types',$listing_types);																
   		$t->assign('categories',fetch_categories('1'));
	   	$t->assign('countries',fetch_countries('1'));
		$t->assign('types_c', fetch_types());
		$t->assign('locations', fetch_locations('1'));
		$t->assign('states', fetch_states());
   		$t->assign('currencies',fetch_currencies());

////////////////////////	
	if (!empty($edit_lang)) {
		$t->assign('edit_lang', $edit_lang);
	}else {
		$t->assign('edit_lang', $default_lang);	  
	}

	$t->display("admin/edit_listing.tpl");
	} else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}		
}
// EOF "OFFER EDIT"

// START "OFFER EDIT SUBMIT FORM"
elseif (isset($_POST['edit_listing'])) {
  $listing_id=sqlx($_POST['edit_listing']);
  $edit_lang=sqlx($_POST['edit_lang']);
  $title=sqlx($_POST['title']);
  $uri=sqlx($_POST['uri']);
  $cat_id=sqlx($_POST['cat_id']);
  $country_id=sqlx($_POST['country_id']);
  $city=sqlx($_POST['city']);  
  $location_id=sqlx($_POST['location_id']);  
  $state_id=sqlx($_POST['state_id']);
  $description=sqlx($_POST['description']);
  $short_description=addslashes(sqlx($_POST['short_description']));
  $price=sqlx($_POST['price']);
  $currency=sqlx($_POST['currency']);
  $price_desc=sqlx($_POST['price_desc']);  
  $gmap_location=sqlx($_POST['gmap_location']);
  if (strpos($gmap_location,'(') OR strpos($gmap_location,')')) {
		$gmap_location=between('(',')',$gmap_location);
  }
  $allow_reservation=sqlx($_POST['allow_reservation']);
  if ($allow_reservation=="on") {$allow_reservation="1";}else{$allow_reservation="0";}
  $allow_payment=sqlx($_POST['allow_payment']);
  if ($allow_payment=="on") {$allow_payment="1";}else{$allow_payment="0";}
  $require_payment=sqlx($_POST['require_payment']);
  if ($require_payment=="on") {$require_payment="1";}else{$require_payment="0";}  
  $active=sqlx($_POST['active']);
  if ($active=="on") {$active="1";}else{$active="0";}  
  $include_sitemap=sqlx($_POST['include_sitemap']);
  if ($include_sitemap=="on") {$include_sitemap="1";}else{$include_sitemap="0";}  
$listing=fetch_listing($listing_id);
  $special=sqlx($_POST['special']);
  if ($special=="on") {$special="1";}else{$special="0";}  
  $start_date=sqlx($_POST['start_date']);
  if (empty($start_date)) { $start_date = "0"; }
  else {
  	$start_date_str=explode('/',$start_date);
  	$start_date=mktime(0, 0, 0, $start_date_str[1], $start_date_str[0], $start_date_str[2]);
  }
  $end_date=sqlx($_POST['end_date']);
  if (empty($end_date)) { $end_date = "0"; }
  else {
	  $end_date_str=explode('/',$end_date);
	  $end_date=mktime(0, 0, 0, $end_date_str[1], $end_date_str[0], $end_date_str[2]);
  }  
  $stars=sqlx($_POST['stars']);
  $types=@implode("|",$_POST['types']);
  $contact_details=sqlx($_POST['contact_details']);
  $date_added=time();

	// Check if there is edit_lang in the DB
	$chkdb=mquery("SELECT * from `listings_text` WHERE `listing_id`='$listing_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
		$getlistinginfo=mquery("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE listings.listing_id='$listing_id' AND `lang`='$edit_lang'");		
	}else {
		// Load default
		$getlistinginfo=mquery("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE listings.listing_id='$listing_id' AND `lang`='$default_lang'");		
	}

	if (@mysql_num_rows($getlistinginfo)=="0") { $error[]=$lang_errors['invalid_listing']; }

		////////// UPLOAD IMAGE    	
		if ($_FILES['icon']['size']) { // Add Image submit
			$now=time();
			// Get the thumbnail
			$upload_dir = "../uploads/images/";
			$upload_dir_thumbs = "../uploads/thumbs/";
			$uploaded = do_upload($upload_dir,"icon","$listing_id.$now");
			$uploaded=substr($uploaded, 1);
			$image=after_last('/',$uploaded);
			// Create Thumbnail
			if ($gd=="yes" AND $conf[create_thumbs]=="1") {
				$tm = new dThumbMaker; 
				$load = $tm->loadFile($upload_dir.$image);
				if($load === true){ // Note three '='      
				    $tm->resizeMaxSize($conf[thumb_resize_h], $conf[thumb_resize_w]); 
	//				$tm->addWaterMark('images/watermark.gif', 64, 64, true);
			    	$tm->build($upload_dir_thumbs.$image);
				}
			}
			//EOF THUMB creation	

			// Resize Image
			if ($gd=="yes" AND $conf[img_resize]=="1") {
				$tm = new dThumbMaker; 
				$load = $tm->loadFile($upload_dir.$image);
				if($load === true){ // Note three '='      
				    $tm->resizeMaxSize($conf[img_resize_h], $conf[img_resize_w]); 
					if ($conf[watermark_images]=="1") {
						$tm->addWaterMark("../uploads/$conf[watermark_image_file]", $conf[watermark_position_x], $conf[watermark_position_y], true);
					}
				    $tm->build($upload_dir.$image); 
				}
			}
			//EOF Resize Image
			// Watermark Image
			if ($gd=="yes" AND $conf[watermark_images]=="1" AND $conf[img_resize]!="1") {
				$tm = new dThumbMaker; 
				$load = $tm->loadFile($upload_dir.$image);
				if($load === true){ // Note three '='      
					$tm->addWaterMark("../uploads/$conf[watermark_image_file]", $conf[watermark_position_x], $conf[watermark_position_y], true);
				    $tm->build($upload_dir.$image); 
				}
			}		
			// EOF Watermark Image		

			mquery("INSERT into `images` values ('','$image','$listing_id')");
			$new_image_id=@mysql_insert_id();
			mquery("INSERT into `images_text` values ('$new_image_id','$default_lang','$title')");
			mquery("UPDATE `listings` SET `icon`='$image' WHERE `listing_id`='$ins_id'");
		////////// EOF UPLOAD IMAGE					
		}else{
		  if (!empty($_POST['listing_icon_select'])) {
		  	$image=after_last('/',sqlx($_POST['listing_icon_select']));
		  }else{
		    $image=$config['default_icon'];
			} 
		}
		  
	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['listing_title_empty']; }
	if (empty($uri)) { $error[]=$lang_errors['listing_uri_empty']; }
	if (empty($cat_id)) { $error[]=$lang_errors['listing_cat_empty']; }
	if (empty($country_id)) { $error[]=$lang_errors['listing_country_empty']; }	
	if (empty($city)) { $error[]=$lang_errors['listing_city_empty']; }	
	if (empty($location_id)) { $error[]=$lang_errors['listing_location_empty']; }
	if (empty($short_description)) { $error[]=$lang_errors['listing_short_desc_empty']; }
	if (empty($description)) { $error[]=$lang_errors['listing_desc_empty']; }
//	if (empty($price)) { $error[]=$lang_errors['listing_price_empty']; }
	if (!empty($price) AND !is_numeric($price)) { $error[]=$lang_errors['listing_price_not_num']; }	
//	if (empty($currency)) { $error[]=$lang_errors['listing_currency_empty']; }	
	$check_uri_exists=mquery("SELECT * from `listings` WHERE `uri`='$uri' AND `listing_id`!='$listing_id'");
	if (@mysql_num_rows($check_uri_exists)>0) { $error[]=$lang_errors['listing_uri_exists']; }
	$check_listing_exists=mquery("SELECT * from `listings` WHERE `listing_id`='$listing_id'");
	if (@mysql_num_rows($check_listing_exists)==0) { $error[]=$lang_errors['invalid_listing']; }
	// If no errors...continue
	if (count($error)=="0")	{
		// If Sold...watermark the main thumb
		$upload_dir = "../uploads/images/";				
		$upload_dir_thumbs = "../uploads/thumbs/";
		if (empty($mls)) {$mls=$listing_id;}
    	mquery("UPDATE `listings` SET `icon`='$image',`uri`='$uri',`cat_id`='$cat_id',`country_id`='$country_id',`location_id`='$location_id',`state_id`='$state_id',`price`='$price',`currency`='$currency',`stars`='$stars',`types`='$types',`special`='$special',`gmap_location`='$gmap_location',`active`='$active',`start_date`='$start_date',`end_date`='$end_date',`include_sitemap`='$include_sitemap',`allow_reservation`='$allow_reservation',`allow_payment`='$allow_payment',`require_payment`='$require_payment' WHERE `listing_id`='$listing_id'");
	// Check if there is edit_lang in the DB
	$chkdb=mquery("SELECT * from `listings_text` WHERE `listing_id`='$listing_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
		mquery("UPDATE `listings_text` SET `title`='$title',`short_description`='$short_description',`description`='$description',`price_desc`='$price_desc',`contact_details`='$contact_details',`city`='$city' WHERE `listing_id`='$listing_id' AND `lang`='$edit_lang'");
	}else {
		mysql_query("INSERT into `listings_text` SET `listing_id`='$listing_id',`lang`='$edit_lang',`title`='$title',`short_description`='$short_description',`description`='$description',`price_desc`='$price_desc',`contact_details`='$contact_details',`city`='$city'") or die(mysql_error());
	}
		set_msg("Listing ID <b>$listing_id</b> updated successfuly!");		
		header("Location: $config[base_url]/admin/listings.php?edit=$listing_id&edit_lang=$edit_lang");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$listing=@mysql_fetch_array($getlistinginfo);
	   	$t->assign('listing',$listing);
		$listing_types=explode("|",$listing[types]);
		$t->assign('listing_types',$listing_types);																		
	   	$t->assign('categories',fetch_categories('1'));
   		$t->assign('countries',fetch_countries('1'));
		$t->assign('types_c', fetch_types());
		$t->assign('locations', fetch_locations('1'));
		$t->assign('states', fetch_states());
	   	$t->assign('currencies',fetch_currencies());		
		$t->display("admin/edit_listing.tpl");		
	}	
}
// EOF "OFFER EDIT SUBMIT FORM"

// START "OFFER DELETE"
elseif (isset($_GET['delete'])) {
  $listing_id=trim($_GET['delete']);
    // fetch listing details
    $listing=fetch_listing($listing_id);
  mquery("DELETE from `listings` WHERE `listing_id`='$listing_id'");
  mquery("DELETE from `listings_text` WHERE `listing_id`='$listing_id'");
  mquery("DELETE from `packages` WHERE `listing_id`='$listing_id'");  
  // If there are images, delete them
  $getimages=mquery("SELECT * from `images` WHERE `listing_id`='$listing_id'");
  while($image=@mysql_fetch_array($getimages)) {
  	@del_file("../uploads/images/$image[file]");
  	if (file_exists("../uploads/thumbs/$image[file]")) {
	  	@del_file("../uploads/thumbs/$image[file]");
  	}  
  	mquery("DELETE from `images` WHERE `image_id`='$image[image_id]'");
  	mquery("DELETE from `images_text` WHERE `image_id`='$image[image_id]'");
  }	
		
set_msg("Listing ID <b>$listing_id</b> deleted successfuly!");		
  header("Location: $config[base_url]/admin/listings.php");
}
// EOF "OFFER DELETE"

// START "SHOW OFFERS/HOTELS"
else {
	// FILTER
	if (isset($_REQUEST['filter'])) {
		$filter_cat_id=sqlx($_REQUEST['filter_category']);
		$filter_country_id=sqlx($_REQUEST['filter_country']);
		$filter_city=sqlx($_REQUEST['filter_city']);
		$filter_location_id=sqlx($_REQUEST['filter_location']);
		$filter_state_id=sqlx($_REQUEST['filter_state']);
		if (is_numeric($filter_cat_id)) { 
			// if category is main fetch subcategories
			$getsubcats=mquery("SELECT * from `categories` WHERE `parent`='$filter_cat_id'");
			if (@mysql_num_rows($getsubcats)>0) {
				$query .= "AND (`cat_id`='$filter_cat_id' ";
				while($subcat=@mysql_fetch_array($getsubcats)) {
					$query .= "OR `cat_id`='$subcat[cat_id]' ";
				}
				$query .= ") ";
			}else {
			  $query .= "AND `cat_id`='$filter_cat_id' ";
			}
		}
		if (is_numeric($filter_country_id)) { $query .= "AND `country_id`='$filter_country_id' "; }		
		if (!empty($filter_city)) { $query .= "AND `city`='$filter_city' "; }				
		if (is_numeric($filter_location_id)) { $query .= "AND `location_id`='$filter_location_id' "; }
		if (is_numeric($filter_state_id)) { $query .= "AND `state_id`='$filter_state_id' "; }		
	}
	$getlistings=mquery("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE (`lang`='$default_lang' OR `lang`='$edit_lang') $query ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`added_date` DESC");
	$listings=array();
	while($listing=@mysql_fetch_array($getlistings)) {
		if (multiarray_search($listings, 'listing_id', $listing[listing_id]) == "-1") {
			if ($listing['price_set']=='package') {
				$now=time();
				$package=fetch_package($listing['listing_id'],$now,$now);
				$listing['price']=$package['base_price'];
				$listing['price_desc']=$package['price_period'];
				if ($package['price_period']=="1") {$listing['price_desc']=$lang_globals['day'];}
				if ($package['price_period']=="7") {$listing['price_desc']=$lang_globals['week'];}
				if ($package['price_period']=="30") {$listing['price_desc']=$lang_globals['month'];}
				if ($package['price_period']=="365") {$listing['price_desc']=$lang_globals['year'];}			  
				$listing['pack_price_until']=$package['to_date'];
			}
			if (isset($_GET['expired'])) {
				if (listing_active($listing[start_date],$listing[end_date])==false) {
					array_push($listings, $listing);
				}
			}else{
				array_push($listings, $listing);
			}
		}
	}
	if (count($listings)) { $t->assign('dynamic_table',true); }
   	$t->assign('listings',$listings);
// assign filter variables
$t->assign('categories',fetch_categories());
$t->assign('countries',fetch_countries());
$t->assign('cities',fetch_cities());
$t->assign('locations',fetch_locations());
$t->assign('states',fetch_states());

	if (isset($_GET['expired'])) {
		$t->display("admin/listings_expired.tpl");
	}else {
		$t->display("admin/listings.tpl");
	}
}
// EOF "SHOW OFFERS/HOTELS"


?>
