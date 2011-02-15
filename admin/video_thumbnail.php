<?php
	@set_time_limit(0);
	// This script accepts an ID and looks in the user's session for stored thumbnail data.
	// It then streams the data to the browser as an image
	
	// Work around the Flash Player Cookie Bug
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
if ($gd=="yes") {include("../includes/thumb.class.php");}
include("common.php");
	
//	session_start();
	
	$video_id = isset($_GET["id"]) ? $_GET["id"] : false;

	if ($video_id === false) {
		header("HTTP/1.1 500 Internal Server Error");
		echo "No ID";
		exit(0);
	}

//	if (!is_array($_SESSION["file_info"]) || !isset($_SESSION["file_info"][$image_id])) {
//		header("HTTP/1.1 404 Not found");
//		exit(0);
//	}
if (is_numeric($video_id)) {
	$img_file=file_get_contents("../uploads/videos/thumbs/$video_id.jpg");	
}else{
	$img_file=file_get_contents("$video_id");	  
}
	header("Content-type: image/jpeg") ;
	header("Content-Length: ".strlen($img_file));
//	header("Content-Length: ".strlen($_SESSION["file_info"][$image_id]));
//	echo $_SESSION["file_info"][$image_id];
	echo $img_file;
	exit(0);
?>