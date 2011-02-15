<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
if ($gd=="yes") {include("../includes/thumb.class.php");}
include("common.php");
$_SESSION["file_info"] = array();

$max_upload_size=@ini_get("upload_max_filesize");
$max_upload_size=before_last('M',$max_upload_size);
$t->assign('max_upload_size',$max_upload_size);

// START "SHOW ITEM VIDEOS"
if (isset($_GET['id'])) {
	$id=sqlx($_GET['id']);
	$_SESSION['tmp_listing_id']=$id;
	$sql=mquery("SELECT * from `videos` WHERE `listing_id`='$id'"); 
	$getitem=mquery("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE listings.listing_id='$id' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
	$item=@mysql_fetch_array($getitem);
   	$t->assign('listing',$item);
	$videos=array();
	while($vid=@mysql_fetch_array($sql)) {
		unset($video_size);
		if (multiarray_search($videos, 'video_id', $vid[video_id]) == "-1") {
			$video_size=@filesize("$config[root_dir]/uploads/videos/$vid[video_id].flv");
			$vid[size]=ByteSize($video_size).ByteSize($video_size,true);
			array_push($videos, $vid);
		}
	}
   	$t->assign('videos',$videos);
	$t->display("admin/videos.tpl");
}
// END "SHOW ITEM VIDEOS"

// START "DELETE"
elseif (isset($_GET['delete'])) {
  $video_id=sqlx($_GET['delete']);
  $listing_id=sqlx($_GET['listing']);
  $sql=mquery("SELECT `file` from `videos` WHERE `video_id`='$video_id'");
  $ufile=@mysql_result($sql,0);
  @del_file("../uploads/videos/$ufile");
  if (file_exists("../uploads/videos/thumbs/$ufile")) {
  	@del_file("../uploads/videos/thumbs/$ufile");
  }  
  mquery("DELETE from `videos` WHERE `video_id`='$video_id'");
  mquery("DELETE from `videos_text` WHERE `video_id`='$video_id'");  
set_msg("Video <b>$ufile</b> deleted successfuly.");		
  header("Location: videos.php?id=$listing_id");
}
// EOF "DELETE"

// START "FORM SUBMIT VIDEO"
if (isset($_POST['listing_id'])) {
	$listing_id=sqlx($_POST['listing_id']);
	if ($demo == "0") {
	@set_time_limit(0);
	foreach($_FILES as $file_name => $file) {
		$p=$_FILES[$file_name]['name'];
		$pos=strrpos($p,".");
		$ph=strtolower(substr($p,$pos+1,strlen($p)-$pos));
		if(($ph!="mpg" && $ph!="avi" && $ph!="mpeg" && $ph!="wmv" && $ph!="rm" && $ph!="dat")) {
			$error[]="Invalid Video Format.";
		}

		mquery("INSERT into `videos` values ('','$listing_id','')");
		$video_id=@mysql_insert_id();
		$ff = "$config[root_dir]/uploads/videos/$video_id.$ph";
//		print_r($_FILES);
		$tmp_name=$file['tmp_name'];
//		echo "FILENAME: $file_name<br/>COPY TO: $ff<br/>TMP NAME: $tmp_name<br/>";
		if (move_uploaded_file($_FILES["$file_name"]['tmp_name'], $ff)){	
			chmod($ff,0666);
//			$mov = new ffmpeg_movie($ff);
//			video_to_frame($ff,$video_id,$mov);
//			$duration=$mov->getDuration();
//			mquery("UPDATE `videos` SET `duration`='$duration' WHERE `video_id`='$video_id'");
//			$command="$config[ffmpeg_path] -i $ff -acodec lame -ar 22050 -ab 64 -f flv $config[root_dir]/uploads/videos/".$video_id.".flv";
			$command="$config[ffmpeg_path] -i $ff -f flv $config[root_dir]/uploads/videos/".$video_id.".flv";
			$output=exec($command,$myout);
		}
	}  
}
	set_msg("New Video <b>$video_id.$ph</b> uploaded successfuly.");		
	header("Location: videos.php?id=$listing_id");
}
// END "FORM SUBMIT VIDEO"
if (isset($_GET['upload_status'])) {
  $t->display("admin/upload_status.tpl");
}
if (isset($_GET['play'])) {
	$video_id=sqlx($_GET['play']);
	$video=array();
	$video['video_id']=$video_id;
   	$t->assign('video',$video);	
	$t->display("frontend/$template/flvplayer.tpl");
}
?>