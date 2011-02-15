<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php"); // Check if user is logged in as admin
include("common.php");

// START "EDIT CATEGORY"
if (isset($_GET['edit'])) {
	$cat_id=sqlx($_GET['edit']);
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
	$chkdb=mquery("SELECT * from `categories_text` WHERE `cat_id`='$cat_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
		$getcatinfo=mquery("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE categories.cat_id='$cat_id' AND `lang`='$edit_lang'");		
	}else {
		// Load default
		$getcatinfo=mquery("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE categories.cat_id='$cat_id' AND `lang`='$default_lang'");				
	}
	
	$category=@mysql_fetch_array($getcatinfo);
	$getmaincats=mquery("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='0' AND (categories_text.lang='$default_lang' OR categories_text.lang='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`position`");
	$main_categories=array();
	while($cat=@mysql_fetch_array($getmaincats)) {
		if (multiarray_search($main_categories, 'cat_id', $cat[cat_id]) == "-1") {
			array_push($main_categories, $cat);
		}
	} 
   	$t->assign('main_categories',$main_categories);
	$t->assign('category', $category);
	if (!empty($edit_lang)) {
		$t->assign('edit_lang', $edit_lang);
	}else {
		$t->assign('edit_lang', $default_lang);	  
	}
	$t->assign('load_google_api',true);
	$t->display("admin/edit_category.tpl");
}
// EOF "EDIT PRODUCT CATEGORY"

// START "EDIT PRODUCT CATEGORY FORM SUBMIT"
elseif (isset($_POST['edit_category'])) {
  $cat_id=sqlx($_POST['edit_category']);
  $title=sqlx($_POST['title']);
  $uri=sqlx($_POST['uri']);
  $description=sqlx($_POST['description']);
  $parent_cat=sqlx($_POST['category']);
  $edit_lang=sqlx($_POST['edit_lang']);
  
	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['cat_title_empty']; }
	if (empty($uri)) { $error[]=$lang_errors['cat_uri_empty']; }
	$check_uri_exists=mquery("SELECT * from `categories` WHERE `uri`='$uri' AND `parent`='$parent_cat' AND `cat_id`!='$cat_id'");
	if (@mysql_num_rows($check_uri_exists)>0) { $error[]=$lang_errors['cat_uri_exists']; }

	// If no errors...continue
	if (count($error)=="0")	{
		mquery("UPDATE `categories` SET `uri`='$uri',`parent`='$parent_cat' WHERE `cat_id`='$cat_id'");

	// Check if there is edit_lang in the DB
	$chkdb=mquery("SELECT * from `categories_text` WHERE `cat_id`='$cat_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) { // If exists...update record
		mquery("UPDATE `categories_text` SET `title`='$title',`description`='$description' WHERE `cat_id`='$cat_id' AND `lang`='$edit_lang'");
	}else { // If not...insert new
		mquery("INSERT into `categories_text` (`cat_id`,`lang`,`title`,`description`) values ('$cat_id','$edit_lang','$title','$description')");
	}
		set_msg("Category <b>$title</b> updated successfuly!");
		if ($parent_cat=="0") {
			header("Location: $config[base_url]/admin/categories.php?edit=$cat_id&edit_lang=$edit_lang");
		} else {
			header("Location: $config[base_url]/admin/categories.php?edit=$cat_id&edit_lang=$edit_lang");
		}	
		
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/edit_category.tpl");		
	}	
}
// EOF "EDIT CATEGORY FORM SUBMIT"

// START "ADD CATEGORY"
elseif (isset($_GET['add'])) {
	$getmaincats=mquery("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='0' AND (categories_text.lang='$default_lang' OR categories_text.lang='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`position`");
	$main_categories=array();
	while($cat=@mysql_fetch_array($getmaincats)) {
		if (multiarray_search($main_categories, 'cat_id', $cat[cat_id]) == "-1") {
			array_push($main_categories, $cat);
		}
	} 
   	$t->assign('main_categories',$main_categories);
	$t->display("admin/add_category.tpl");
}
// EOF "ADD CATEGORY"

// START "ADD CATEGORY FORM SUBMIT"
elseif (isset($_POST['add_category'])) {
  $title=sqlx($_POST['title']);
  $uri=sqlx($_POST['uri']);
  $description=sqlx($_POST['description']);
  $get_max_position=mquery("SELECT MAX(`position`) from `categories`");
  $max_position=@mysql_result($get_max_position,0);  
  $position=$max_position+1;  
  $parent_cat=sqlx($_POST['category']);
	
	// Check for errors
	if (empty($title)) { $error[]=$lang_errors['cat_title_empty']; }
	if (!empty($uri)) {
		$check_uri_exists=mquery("SELECT * from `categories` WHERE `uri`='$uri' AND `parent`='$parent_cat'");
		if (@mysql_num_rows($check_uri_exists)>0) { $error[]=$lang_errors['cat_uri_exists']; }
	}
	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("INSERT into `categories` values ('','$uri','$parent_cat','$position')");
    	$ins_id=@mysql_insert_id();
    	if (empty($uri)) {
			$uri=make_uri("$title",$ins_id);
			mquery("UPDATE `categories` SET `uri`='$uri' WHERE `cat_id`='$ins_id'");
		}

		mquery("INSERT into `categories_text` values ('$ins_id','$language','$title','$description')");    	
		if ($parent_cat=="0") {
			set_msg("Category <b>$title</b> added successfuly!");
			header("Location: $config[base_url]/admin/categories.php?edit=$ins_id");
		} else {
			set_msg("Category <b>$title</b> added successfuly as subcategory of <b>$parent_cat</b>!");
			header("Location: $config[base_url]/admin/categories.php?browse=$parent_cat");
		}	
		
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/add_category.tpl");		
	}	
}
// EOF "ADD CATEGORY FORM SUBMIT"

// START "CATEGORY POSITION"
elseif (isset($_GET['move_up'])) {
	$cat_id=sqlx($_GET['move_up']);
	$parent=sqlx($_GET['parent']);
	$get_pos=mquery("SELECT `position` from `categories` WHERE `cat_id`='$cat_id' AND `parent`='$parent'");
	$cur_pos=@mysql_result($get_pos,0,"position");
	$new_pos=$cur_pos-1;
	$get_up_pos=mquery("UPDATE `categories` SET `position`='$cur_pos' WHERE `position`='$new_pos' AND `parent`='$parent'");
	$set_up_pos=mquery("UPDATE `categories` SET `position`='$new_pos' WHERE `cat_id`='$cat_id' AND `parent`='$parent'");	
	if ($parent=="0") {
		header("Location: $config[base_url]/admin/categories.php");
	} else {
		header("Location: $config[base_url]/admin/categories.php?browse=$parent");
		}
}
elseif (isset($_GET['move_down'])) {
	$cat_id=sqlx($_GET['move_down']);
	$parent=sqlx($_GET['parent']);	
	$get_pos=mquery("SELECT `position` from `categories` WHERE `cat_id`='$cat_id' AND `parent`='$parent'");
	$cur_pos=@mysql_result($get_pos,0,"position");
	$new_pos=$cur_pos+1;
	$get_up_pos=mquery("UPDATE `categories` SET `position`='$cur_pos' WHERE `position`='$new_pos' AND `parent`='$parent'");
	$set_up_pos=mquery("UPDATE `categories` SET `position`='$new_pos' WHERE `cat_id`='$cat_id' AND `parent`='$parent'");
	if ($parent=="0") {
		header("Location: $config[base_url]/admin/categories.php");
	} else {
		header("Location: $config[base_url]/admin/categories.php?browse=$parent");
		}
}
// EOF "CATEGORY POSITION"

// START "SHOW SUBCATEGORIES"
elseif (isset($_GET['browse'])) {
	$cat_id=sqlx($_GET['browse']);
	$getsubcatinfo=mquery("SELECT `title` from `categories_text` WHERE `cat_id`='$cat_id' AND (`lang`='$default_lang' OR `lang`='$edit_lang')");
	$category_title=@mysql_result($getsubcatinfo,0);
	$getcats=mquery("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='$cat_id' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`position`");
	$subcategories=array();
	while($cat=@mysql_fetch_array($getcats)) {
  		if (multiarray_search($subcategories, 'cat_id', $cat[cat_id]) == "-1") {
			array_push($subcategories, $cat);
		}
	}
	$get_min_position=mquery("SELECT MIN(`position`) from `categories` WHERE `parent`='$cat_id'");
	$min_position=@mysql_result($get_min_position,0);
	$get_max_position=mquery("SELECT MAX(`position`) from `categories` WHERE `parent`='$cat_id'");
	$max_position=@mysql_result($get_max_position,0);
	$t->assign('min_position',$min_position);
	$t->assign('max_position',$max_position);
	$t->assign('category_title',$category_title);
   	$t->assign('subcategories',$subcategories);

	$t->display("admin/subcategories.tpl");
}
// EOF "SHOW SUBCATEGORIES"

// START "CATEGORY DELETE"
elseif (isset($_GET['delete'])) {
	$cat_id=trim($_GET['delete']);
	
	// If there are subcategories or listings in this category
	if (count_subcats($cat_id)>0 OR count_listings($cat_id)) {
		$getcatinfo=mquery("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE categories.cat_id='$cat_id' AND `lang`='$default_lang'");
		// Get main Categories and Subcategories
		$getcats=mquery("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='0' AND (categories_text.lang='$default_lang' OR categories_text.lang='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`position`");
		$categories=array();
		while($cat=@mysql_fetch_array($getcats)) {
			if (multiarray_search($categories, 'cat_id', $cat[cat_id]) == "-1") {
				array_push($categories, $cat);
			}
		} 
		$cats_final=array();
		foreach($categories as $key => $row) { 
			$get_subcats=mquery("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='$row[cat_id]' AND (categories_text.lang='$default_lang' OR categories_text.lang='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`position`");
			$subcats=array();
			while($tps=@mysql_fetch_array($get_subcats)) {
				if (multiarray_search($subcats, 'cat_id', $tps[cat_id]) == "-1") {
					array_push($subcats, $tps);
				}
			}
		   	 $row['subcats'] = $subcats;		
			array_push($cats_final, $row);
		} 
		$t->assign('categories',$cats_final);
		// EOF Get main Categories and Subcategories
		
		// if there are subcategories
		if (count_subcats($cat_id)>0)	{
			$getcats=mquery("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='$cat_id' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`position`");
		$subcategories=array();
		while($cat=@mysql_fetch_array($getcats)) {
  			if (multiarray_search($subcategories, 'cat_id', $cat[cat_id]) == "-1") {
				array_push($subcategories, $cat);
			}
		}
			$t->assign('subcategories',$subcategories);
		}
		// If there are listings
		if (count_listings($cat_id)>0) {
			$getlistings=mquery("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `cat_id`='$cat_id' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
			$listings=array();
			while($listing=@mysql_fetch_array($getlistings)) {
				if (multiarray_search($listings, 'listing_id', $listing[listing_id]) == "-1") {
					array_push($listings, $listing);
				}
			}			
			$gocat=mquery("SELECT * from `categories` WHERE `parent`='$cat_id'");
			while($cat=@mysql_fetch_array($gocat)) {
				$getsublistings=mquery("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `cat_id`='$cat[cat_id]' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
				while($listing=@mysql_fetch_array($getsublistings)) {
					if (multiarray_search($listings, 'listing_id', $listing[listing_id]) == "-1") {
						array_push($listings, $listing);
					}
				}			
			}
			$t->assign('listings',$listings);
		}
	   	$t->assign('category',@mysql_fetch_array($getcatinfo));
	   	
		$t->display("admin/delete_category.tpl");
	}else {
		mquery("DELETE from `categories` WHERE `parent`='$cat_id'");
		mquery("DELETE from `categories` WHERE `cat_id`='$cat_id'");
		set_msg("Category <b>$cat_id</b> deleted successfuly!");
		header("Location: $config[base_url]/admin/categories.php");
	}
}
// EOF "CATEGORY DELETE"

// START "CATEGORY DELETE SUBMIT"
elseif (isset($_POST['delete_category'])) {
	$cat_id=sqlx($_POST['delete_category']);

	// If there are OFFERS in category/subcategories, DELETE listings
	if (count_listings($cat_id)>0) {
		$getlistings=mquery("SELECT * from `listings` WHERE `cat_id`='$cat_id'");
		while($listing=@mysql_fetch_array($getlistings)) {
			// If there are images, delete them
		  $getimages=mquery("SELECT * from `images` WHERE `listing_id`='$listing[listing_id]'");
		  while($image=@mysql_fetch_array($getimages)) {
		  	@del_file("../uploads/images/$image[file]");
		  	if (file_exists("../uploads/thumbs/$image[file]")) {
			  	@del_file("../uploads/thumbs/$image[file]");
		  	}  
		  	mquery("DELETE from `images` WHERE `image_id`='$image[image_id]'");
		  	mquery("DELETE from `images_text` WHERE `image_id`='$image[image_id]'");
		  }	
		  // Delete listing
		  mquery("DELETE from `listings` WHERE `listing_id`='$listing[listing_id]'");
		  mquery("DELETE from `listings_text` WHERE `listing_id`='$listing[listing_id]'");		
		}			

		// Get subcategories
		$gocat=mquery("SELECT * from `categories` WHERE `parent`='$cat_id'");
		while($cat=@mysql_fetch_array($gocat)) {
		  	// Get listings in subcategories
			$getsublistings=mquery("SELECT * from `listings` WHERE `cat_id`='$cat[cat_id]'");
			while($listing=@mysql_fetch_array($getsublistings)) {
				// If there are images, delete them
			  $getimages=mquery("SELECT * from `images` WHERE `listing_id`='$listing[listing_id]'");
			  while($image=@mysql_fetch_array($getimages)) {
		  		@del_file("../uploads/images/$image[file]");
			  	if (file_exists("../uploads/thumbs/$image[file]")) {
				  	@del_file("../uploads/thumbs/$image[file]");
			  	}  
		  		mquery("DELETE from `images` WHERE `image_id`='$image[image_id]'");
			  	mquery("DELETE from `images_text` WHERE `image_id`='$image[image_id]'");
			  }	
			  // Delete listing
			  mquery("DELETE from `listings` WHERE `listing_id`='$listing[listing_id]'");
			  mquery("DELETE from `listings_text` WHERE `listing_id`='$listing[listing_id]'");			
			}			
		}	
	}
	// EOF DELETE listings in category/subcategories

	// If there are subcategories in this category, DELETE subcategory
	if (count_subcats($cat_id)>0)	{
		$getcats=mquery("SELECT * from `categories` WHERE `parent`='$cat_id'");
		while($cat=@mysql_fetch_array($getcats)) {
			mquery("DELETE from `categories` WHERE `parent`='$cat[cat_id]'");
			mquery("DELETE from `categories_text` WHERE `parent`='$cat[cat_id]'");			
		}
	}
	// EOF DELETE subcategories

	// Delete selected category
	mquery("DELETE from `categories` WHERE `cat_id`='$cat_id'");
	mquery("DELETE from `categories_text` WHERE `cat_id`='$cat_id'");				
	set_msg("Category <b>$cat_id</b> deleted successfuly!");
	header("Location: $config[base_url]/admin/categories.php");
}
// EOF "CATEGORY DELETE SUBMIT"

// START "CATEGORY DELETE -> MOVE SUBCATEGORIES"
elseif (isset($_POST['move_categories'])) {
	$main_cat=sqlx($_POST['main_cat']);
	$cat_id=sqlx($_POST['cat_id']); // where to be moved to
	$unparsed_subcategory = array_map('trim', $_POST['subcategory']);
	// parse only the checked subcategories into an array
	$i=0;
	while (list($key, $input) = @each($unparsed_subcategory)) {
		$i++;
		if ($input != '' || is_int($input))	{ 
			$unp_subcategory[$i]=$input;
	   }
	}
	// then parse the comments from the array
	$subcategories = array_map('trim', $unp_subcategory);
	$i=0;
	while (list($key, $cid) = @each($subcategories)) {
		$i++;
		if ($cid != '' || is_int($cid))	{ 
			mquery("UPDATE `categories` SET `parent`='$cat_id' WHERE `cat_id`='$cid'");
		}
	}	
	set_msg("Subcategories moved from <b>$main_cat</b> to <b>$cat_id</b> successfuly!");
	header("Location: $config[base_url]/admin/categories.php?delete=$main_cat");
}
// EOF "CATEGORY DELETE -> MOVE SUBCATEGORIES"

// START "CATEGORY DELETE -> MOVE OFFERS"
elseif (isset($_POST['move_listings'])) {
	$main_cat=sqlx($_POST['main_cat']);
	$cat_id=sqlx($_POST['cat_id']); // where to be moved to
	$unparsed_listing = array_map('trim', $_POST['listing']);
	// parse only the checked listings into an array
	$i=0;
	while (list($key, $input) = @each($unparsed_listing)) {
		$i++;
		if ($input != '' || is_int($input))	{ 
			$unp_listing[$i]=$input;
	   }
	}
	// then parse the comments from the array
	$listings = array_map('trim', $unp_listing);
	$i=0;
	while (list($key, $cid) = @each($listings)) {
		$i++;
		if ($cid != '' || is_int($cid))	{ 
			mquery("UPDATE `listings` SET `cat_id`='$cat_id' WHERE `listing_id`='$cid'");
		}
	}	
	set_msg("Listings moved from category <b>$main_cat</b> to <b>$cat_id</b> successfuly!");
	header("Location: $config[base_url]/admin/categories.php?delete=$main_cat");	
}
// EOF "CATEGORY DELETE -> MOVE OFFERS"

// START "SHOW CATEGORIES"
else {
	$getcats=mquery("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='0' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`position`");
	$categories=array();
	while($cat=@mysql_fetch_array($getcats)) {
		if (multiarray_search($categories, 'cat_id', $cat[cat_id]) == "-1") {
			array_push($categories, $cat);
		}
	}
	$get_min_position=mquery("SELECT MIN(`position`) from `categories` WHERE `parent`='0'");
	$min_position=@mysql_result($get_min_position,0);
	$get_max_position=mquery("SELECT MAX(`position`) from `categories` WHERE `parent`='0'");
	$max_position=@mysql_result($get_max_position,0);
	$t->assign('min_position',$min_position);
	$t->assign('max_position',$max_position);
   	$t->assign('categories',$categories);

	$t->display("admin/categories.tpl");
}
// EOF "SHOW CATEGORIES"

?>
