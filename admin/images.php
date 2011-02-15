<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
if ($gd=="yes") {include("../includes/thumb.class.php");}
include("common.php");
$_SESSION["file_info"] = array();

// START "SHOW ITEM IMAGES"
if (isset($_GET['id'])) {
	$id=sqlx($_GET['id']);
	$_SESSION['tmp_listing_id']=$id;
	if (isset($_GET['default'])) {
		$default=sqlx($_GET['default']);
		$getimgfile=mquery("SELECT `file` from `images` WHERE `image_id`='$default'");
		$newfile=@mysql_result($getimgfile,0);
		mquery("UPDATE `listings` SET `icon`='$newfile' WHERE `listing_id`='$id'");
	}

	$sql=mquery("SELECT * from `images` LEFT JOIN `images_text` ON (images.image_id=images_text.image_id) WHERE `listing_id`='$id' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')"); 
	$getitem=mquery("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE listings.listing_id='$id' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
	$item=@mysql_fetch_array($getitem);
   	$t->assign('listing',$item);
	$images=array();
	while($img=@mysql_fetch_array($sql)) {
		unset($img_size);
		if (multiarray_search($images, 'image_id', $img[image_id]) == "-1") {
			$img_size=@filesize("$config[root_dir]/uploads/images/$img[file]");
			$img[size]=ByteSize($img_size).ByteSize($img_size,true);
			array_push($images, $img);
		}
	}
   	$t->assign('images',$images);
	$t->display("admin/images.tpl");
}
// END "SHOW ITEM IMAGES"

// START "DELETE"
elseif (isset($_GET['delete'])) {
  $image_id=sqlx($_GET['delete']);
  $listing_id=sqlx($_GET['listing']);
  $sql=mquery("SELECT `file` from `images` WHERE `image_id`='$image_id'");
  $ufile=@mysql_result($sql,0);
  @del_file("../uploads/images/$ufile");
  if (file_exists("../uploads/thumbs/$ufile")) {
  	@del_file("../uploads/thumbs/$ufile");
  }  
  mquery("DELETE from `images` WHERE `image_id`='$image_id'");
  mquery("DELETE from `images_text` WHERE `image_id`='$image_id'");
	$getlisting=mquery("SELECT * from `listings` WHERE `listing_id`='$listing_id'");
	$listing=mysql_fetch_array($getlisting);
	if (count_images($listing_id) == "0" OR $ufile == $listing[icon]) {
		mquery("UPDATE `listings` SET `icon`='$config[default_icon]' WHERE `listing_id`='$listing_id'");
	}
set_msg("Image ID <b>$image_id</b> deleted successfuly");  
  header("Location: images.php?id=$listing_id");
}
// EOF "DELETE"

// START "EDIT TITLE"
elseif (isset($_GET['edit'])) {
	$image_id=sqlx($_GET['edit']);
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
	$chkdb=mquery("SELECT * from `images_text` WHERE `image_id`='$image_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
		$getimginfo=mquery("SELECT * from `images` LEFT JOIN `images_text` ON (images.image_id=images_text.image_id) WHERE images.image_id='$image_id' AND `lang`='$edit_lang'");		
	}else {
		// Load default
		$getimginfo=mquery("SELECT * from `images` LEFT JOIN `images_text` ON (images.image_id=images_text.image_id) WHERE images.image_id='$image_id' AND `lang`='$default_lang'");		
	}
	if (@mysql_num_rows($getimginfo)=="0") { $error[]=$lang_errors['invalid_image']; }
	if (count($error)=="0") {
		$image=@mysql_fetch_array($getimginfo);
	   	$t->assign('image',$image);
		if (!empty($edit_lang)) {
			$t->assign('edit_lang', $edit_lang);
		}else {
			$t->assign('edit_lang', $default_lang);	  
		}
		$t->display("admin/edit_image.tpl");
	} else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}	
}
elseif (isset($_POST['edit_image'])) {
	$image_id=sqlx($_POST['edit_image']);
	$title=sqlx($_POST['title']);
	$edit_lang=sqlx($_POST['edit_lang']);

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

	// Check for errors
	$check_image_exists=mquery("SELECT * from `images` WHERE `image_id`='$image_id'");
	if (@mysql_num_rows($check_image_exists)==0) { $error[]=$lang_errors['invalid_image']; }
	// If no errors...continue
	if (count($error)=="0")	{
	// Check if there is edit_lang in the DB
	$chkdb=mquery("SELECT * from `images_text` WHERE `image_id`='$image_id' AND `lang`='$edit_lang'");
	if (@mysql_num_rows($chkdb)>0) {
   		mquery("UPDATE `images_text` SET `title`='$title' WHERE `image_id`='$image_id' AND `lang`='$edit_lang'");
	}else {
   		mquery("INSERT into `images_text` values ('$image_id','$edit_lang','$title')");
	}
	set_msg("Image description updated successfuly");
	header("Location: images.php?edit=$image_id&edit_lang=$edit_lang");
  	}else{ // Else Show errors
   		$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
}
// EOF "EDIT TITLE"
// START "FORM SUBMIT IMAGE"
if (isset($_POST['listing_id'])) {
	$listing_id=sqlx($_POST['listing_id']);
if ($demo == "0") {
	@set_time_limit(0);
	foreach($_FILES as $file_name => $file) {
//	
	// Get the thumbnail
	$upload_dir = "../uploads/images/";
	$upload_dir_thumbs = "../uploads/thumbs/";
	if ($file['size']) {
		$now=time();
		$uploaded = do_upload($upload_dir,$file_name,"$listing_id.$now");
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
		if (count_images($listing_id) == "0") {
			mquery("UPDATE `listings` SET `icon`='$image' WHERE `listing_id`='$listing_id'");
		}
		mquery("INSERT into `images` values ('','$image','$listing_id')");
		$new_image_id=@mysql_insert_id();
		mquery("INSERT into `images_text` values ('$new_image_id','$default_lang','$title')");
	}
//		
	}  
}	
	set_msg("New Image <b>$listing_id.$now</b> uploaded successfuly");
header("Location: images.php?id=$listing_id");
}
// END "FORM SUBMIT IMAGE"
if (isset($_GET['upload_status'])) {
  $t->display("admin/upload_status.tpl");
}
?>