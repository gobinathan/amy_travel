<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php"); // Check if user is logged in as admin
include("common.php");

// START "BANNER ADD"
if (isset($_GET['add'])) {
	$t->display("admin/add_banner.tpl");
}
// EOF "BANNER ADD"

// START "BANNER ADD SUBMIT FORM"
elseif (isset($_POST['add_banner'])) {
  $position=sqlx($_POST['position']);
  $rotate=sqlx($_POST['rotate']);  
  if ($rotate == "on") {$rotate="1";}else{$rotate="0";}
  $code=addslashes(sqlx($_POST['code']));
	// Check for errors
	if (empty($position)) { $error[]="Missing position"; }
	if (empty($code)) { $error[]="Missing banner code"; }
	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("INSERT into `ads` values ('','$position','$code','0','1','$rotate')");
		set_msg("Banner added successfuly.");
		header("Location: $config[base_url]/admin/ads.php");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/add_banner.tpl");		
	}	
}
// EOF "BANNER ADD SUBMIT FORM"

// START "BANNER EDIT"
elseif (isset($_GET['edit'])) {
	$banner_id=sqlx($_GET['edit']);
	$getad=mquery("SELECT * from `ads` WHERE `banner_id`='$banner_id'");
	if (@mysql_num_rows($getad)=="0") { $error[]="Invalid Ad"; }
	if (count($error)=="0") {
		$ad=@mysql_fetch_array($getad);
	   	$t->assign('ad',$ad);
		$t->display("admin/edit_banner.tpl");
	} else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}		
}
// EOF "BANNER EDIT"

// START "BANNER EDIT SUBMIT FORM"
elseif (isset($_POST['edit_banner'])) {
  $banner_id=sqlx($_POST['edit_banner']);
  $position=sqlx($_POST['position']);
  $rotate=sqlx($_POST['rotate']);  
  if ($rotate == "on") {$rotate="1";}else{$rotate="0";}  
  $code=sqlx($_POST['code']);
  // Check for errors
  if (empty($banner_id)) { $error[]="Missing Banner ID"; }
  if (empty($position)) { $error[]="Missing position"; }
  if (empty($code)) { $error[]="Missing banner code"; }

	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("UPDATE `ads` SET `position`='$position',`code`='$code',`rotate`='$rotate' WHERE `banner_id`='$banner_id'");
		set_msg("Banner <b>$banner_id</b> updated successfuly.");
		header("Location: $config[base_url]/admin/ads.php");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/edit_banner.tpl");		
	}	
}
// EOF "BANNER EDIT SUBMIT FORM"

// START "BANNER DELETE"
elseif (isset($_GET['delete'])) {
  $banner_id=trim($_GET['delete']);
  mquery("DELETE from `ads` WHERE `banner_id`='$banner_id'");
  set_msg("Banner <b>$banner_id</b> deleted successfuly.");
  header("Location: $config[base_url]/admin/ads.php");
}
// EOF "BANNER DELETE"

elseif (isset($_GET['activate'])) {
	$banner_id=sqlx($_GET['activate']);
	mquery("UPDATE `ads` SET `active`='1' WHERE `banner_id`='$banner_id'");
	set_msg("Banner <b>$banner_id</b> is now active!");
	header("Location: $config[base_url]/admin/ads.php");
}
elseif (isset($_GET['deactivate'])) {
	$banner_id=sqlx($_GET['deactivate']);
	$check_banner_exists=mquery("SELECT * from `ads` WHERE `banner_id`='$banner_id'");
	if (@mysql_num_rows($check_banner_exists)==0) { $error[]="Invalid Banner ID"; }
	// If no errors...continue
	if (count($error)=="0")	{
		mquery("UPDATE `ads` SET `active`='0' WHERE `banner_id`='$banner_id'");
		set_msg("Banner <b>$banner_id</b> is now inactive!");
		header("Location: $config[base_url]/admin/ads.php");
	}else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/ads.tpl");
	}	
}

// START "SHOW ADS/BANNERS"
else {
	$getads=mquery("SELECT * from `ads` ORDER BY `position`");
	$ads=array();
	while($banner=@mysql_fetch_array($getads)) {
		array_push($ads, $banner);
	}
   	$t->assign('ads',$ads);
	$t->display("admin/ads.tpl");
}
// EOF "SHOW ADS/BANNERS"

?>
