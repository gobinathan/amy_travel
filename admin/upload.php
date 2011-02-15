<?php
@set_time_limit(0);
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
if ($gd=="yes") {include("../includes/thumb.class.php");}
include("common.php");

	/* Note: This thumbnail creation script requires the GD PHP Extension.  
		If GD is not installed correctly PHP does not render this page correctly
		and SWFUpload will get "stuck" never calling uploadSuccess or uploadError
	 */

	// Get the session Id passed from SWFUpload. We have to do this to work-around the Flash Player Cookie Bug
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}

	session_start();

	// Check the upload
	if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
		header("HTTP/1.1 500 Internal Server Error");
		echo "invalid upload";
		exit(0);
	}

	// Get the image and create a thumbnail
	$img = @imagecreatefromjpeg($_FILES["Filedata"]["tmp_name"]);
	if (!$img) {
		header("HTTP/1.1 500 Internal Server Error");
		echo "could not create image handle";
		exit(0);
	}

	$width = imageSX($img);
	$height = imageSY($img);

	if (!$width || !$height) {
		header("HTTP/1.1 500 Internal Server Error");
		echo "Invalid width or height";
		exit(0);
	}

	// Build the thumbnail
	$target_width = 100;
	$target_height = 100;
	$target_ratio = $target_width / $target_height;

	$img_ratio = $width / $height;

	if ($target_ratio > $img_ratio) {
		$new_height = $target_height;
		$new_width = $img_ratio * $target_height;
	} else {
		$new_height = $target_width / $img_ratio;
		$new_width = $target_width;
	}

	if ($new_height > $target_height) {
		$new_height = $target_height;
	}
	if ($new_width > $target_width) {
		$new_height = $target_width;
	}

	$new_img = ImageCreateTrueColor(100, 100);
	if (!@imagefilledrectangle($new_img, 0, 0, $target_width-1, $target_height-1, 0)) {	// Fill the image black
		header("HTTP/1.1 500 Internal Server Error");
		echo "Could not fill new image";
		exit(0);
	}

	if (!@imagecopyresampled($new_img, $img, ($target_width-$new_width)/2, ($target_height-$new_height)/2, 0, 0, $new_width, $new_height, $width, $height)) {
		header("HTTP/1.0 500 Internal Server Error");
		echo "Could not resize image";
		exit(0);
	}

	if (!isset($_SESSION["file_info"])) {
		$_SESSION["file_info"] = array();
	}

	// Use a output buffering to load the image into a variable
	ob_start();
	imagejpeg($new_img);
	$imagevariable = ob_get_contents();
	ob_end_clean();

	$file_id = md5($_FILES["Filedata"]["tmp_name"] + rand()*100000);
	
	$_SESSION["file_info"][$file_id] = $imagevariable;

// CREATE DATABASE ENTRY AND RECREATE IMAGE
$file_name="Filedata";
$listing_id=$_SESSION['tmp_listing_id'];
$upload_dir = "../uploads/images/";
$upload_dir_thumbs = "../uploads/thumbs/";
$now=time();
if ($demo == "0") {
$uploaded = do_upload($upload_dir,$file_name,"$listing_id.".rand());
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
}
if (count_images($listing_id) == "0") {
	mquery("UPDATE `listings` SET `icon`='$image' WHERE `listing_id`='$listing_id'");
}
mquery("INSERT into `images` values ('','$image','$listing_id')");
$new_image_id=@mysql_insert_id();
mquery("INSERT into `images_text` values ('$new_image_id','$default_lang','$title')");
///////////////////////////////////////////
echo $new_image_id;
//	echo $file_id;	// Return the file id to the script
	
?>