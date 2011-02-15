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

$p=$_FILES['Filedata']['name'];
$pos=strrpos($p,".");
$ph=strtolower(substr($p,$pos+1,strlen($p)-$pos));
if(($ph!="mpg" && $ph!="avi" && $ph!="mpeg" && $ph!="wmv" && $ph!="rm" && $ph!="dat")) {
	$error[]="Invalid Video Format.";
}
$listing_id=$_SESSION['tmp_listing_id'];
mquery("INSERT into `videos` values ('','$listing_id','')");
$video_id=@mysql_insert_id();
$ff = "$config[root_dir]/uploads/videos/$video_id.$ph";
if ($demo == "0") {
if (move_uploaded_file($_FILES['Filedata']['tmp_name'], $ff)){	
	chmod($ff,0666);
//	$mov = new ffmpeg_movie($ff);
//	video_to_frame($ff,$video_id,$mov);
//	$duration=$mov->getDuration();
//	$thumb_command="$config[ffmpeg_path] -itsoffset -4  -i $ff -vcodec mjpeg -an -f rawvideo -s 320x240 $config[root_dir]/uploads/videos/thumbs/".$video_id.".jpg";
//	$thumb_output=exec($thumb_command);
//	ffmpeg.exe -y -i [входящ видео файл] -f image2 -ss 3 -vframes 1 -s 120x90 -an thumb.png
//	mquery("UPDATE `videos` SET `duration`='$duration' WHERE `video_id`='$video_id'");
	$command="$config[ffmpeg_path] -i $ff -ar 22050 -f flv $config[root_dir]/uploads/videos/".$video_id.".flv";
	$output=exec($command);
//	$output=exec("$config[ffmpeg_path] -i $ff -acodec mp3 -ar 22050 -ab 64 -f flv $config[root_dir]/uploads/videos/".$video_id.".flv",$myout);
}
}
//echo $video_id;
$video_size=@filesize("$config[root_dir]/uploads/videos/$video_id.flv");
$vidsize=ByteSize($video_size).ByteSize($video_size,true);
echo "<a href=videos.php?play=$video_id target=_blank><b>Play Video $p</b></a> | Size: $vidsize | ID: $video_id<br/>";
@unlink("$config[root_dir]/uploads/videos/$video_id.$ph");
?>