<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");

// START "LISTING PACKAGES"
if (isset($_GET['listing_id'])) {
	$listing_id=sqlx($_GET['listing_id']);
	$t->assign('listing',fetch_listing($listing_id));	
	$t->assign('currencies',fetch_currencies());
	$t->assign('today_package',fetch_package($listing_id,time(),time()));
	// start "delete package"
	if(isset($_GET['delete'])) {
		$pack_id=sqlx($_GET['delete']);
		mysql_query("DELETE from `packages` WHERE `pack_id`='$pack_id' AND `listing_id`='$listing_id'");
		set_msg("Package ID <b>$pack_id</b> deleted successfuly!");
		header("Location: $config[base_url]/admin/packages.php?listing_id=$listing_id");
	}
	// start "set default package"
	if (isset($_GET['default'])) {
		$package_id=sqlx($_GET['default']);
		mquery("UPDATE `packages` SET `default`='$package_id' WHERE `listing_id`='$listing_id'");
	}
	if (isset($_GET['price_set'])) {
		$price_set=sqlx($_GET['price_set']);
		mysql_query("UPDATE `listings` SET `price_set`='$price_set' WHERE `listing_id`='$listing_id'") or die(mysql_error()); 
		header("Location: $config[base_url]/admin/packages.php?listing_id=$listing_id");
	}
	if (isset($_POST['static_price'])) {
		$price=sqlx($_POST['price']);
		$currency=sqlx($_POST['currency']);
		mquery("UPDATE `listings` SET `price`='$price',`currency`='$currency' WHERE `listing_id`='$listing_id'");		
		header("Location: $config[base_url]/admin/packages.php?listing_id=$listing_id");
	}
// start "show add package"
elseif(isset($_GET['add'])) {
	$t->display("admin/add_package.tpl");		
}
// start "show listing packages"
else {
	$sql=mquery("SELECT * from `packages` WHERE `listing_id`='$listing_id' ORDER BY `from_date` ASC"); 
	$packages=array();
	while($pack=@mysql_fetch_array($sql)) {
		array_push($packages, $pack);
	}
   	$t->assign('packages',$packages);
	$t->display("admin/packages.tpl");
}
}
// EOF "LISTING PACKAGES"
// start "add package submit"
if (isset($_POST['add_package'])) {
	$listing_id=sqlx($_POST['listing_id']);
	$t->assign('listing',fetch_listing($listing_id));		
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
	$price=sqlx($_POST['base_price']);
	$people_count=sqlx($_POST['people_count']);
	$people_discount=sqlx($_POST['people_discount']);
	$rooms_count=sqlx($_POST['rooms_count']);
	$rooms_discount=sqlx($_POST['rooms_discount']);
	$kids_count=sqlx($_POST['kids_count']);
	$kids_discount=sqlx($_POST['kids_discount']);
	$price_period=sqlx($_POST['price_period']);
	// Check for errors
	if (empty($listing_id)) { $error[]="Please select listing first"; }
	if (empty($start_date)) { $error[]="Empty start date"; }
	if (empty($end_date)) { $error[]="Empty end date"; }	
	if ($start_date>$end_date) { $error[]="Invalid date selected. Please check your dates"; }
	if (empty($price)) { $error[]="Empty Price"; }
	if (!empty($people_count) AND !is_numeric($people_count)) { $error[]="People Count must be numeric!"; }
	if (!empty($rooms_count) AND !is_numeric($rooms_count)) { $error[]="Rooms Count must be numeric!"; }	
	if (!empty($kids_count) AND !is_numeric($kids_count)) { $error[]="Kids Count must be numeric!"; }
	if ($people_discount > '100') { $error[]="Invalid People Discount! Cannot be greater than 100%"; }
	if ($rooms_discount > '100') { $error[]="Invalid Rooms Discount! Cannot be greater than 100%"; }
	if ($kids_discount > '100') { $error[]="Invalid Kids Discount! Cannot be greater than 100%"; }	
	if (!empty($people_discount) AND !is_numeric($people_discount)) { $error[]="People Discount must be numeric!"; }
	if (!empty($rooms_discount) AND !is_numeric($rooms_discount)) { $error[]="Rooms Discount must be numeric!"; }	
	if (!empty($kids_discount) AND !is_numeric($kids_discount)) { $error[]="Kids Discount must be numeric!"; }
	$now=time();
//	if ($start_date+86400 <= $now) { $error[]="Invalid From Date. Cannot be in the past!"; }
//	if ($end_date <= $now) { $error[]="Invalid To Date. Cannot be in the past!"; }
	// check if there is another package in this period
	$checkdups=mysql_query("SELECT * from `packages` WHERE `listing_id`='$listing_id' AND ((`from_date`<=$start_date AND `to_date`>=$start_date) OR (`from_date`<=$end_date AND `to_date`>=$end_date))");
	if (@mysql_num_rows($checkdups)>"0") { $error[]="There is another package in this date period!"; }
	// If no errors...continue
	if (count($error)=="0")	{
		mquery("INSERT into `packages` values ('','$listing_id','$start_date','$end_date','$price','$people_count','$people_discount','$rooms_count','$rooms_discount','$kids_count','$kids_discount','$price_period')");
		$ins_id=@mysql_insert_id();
		set_msg("Package ID <b>$ins_id</b> added successfuly!");
		header("Location: $config[base_url]/admin/packages.php?listing_id=$listing_id");
	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->assign('listing',fetch_listing($listing_id));	
		$t->display("admin/add_package.tpl");		
	}	
}
// start "show edit package"
elseif(isset($_GET['edit'])) {
	$pack_id=sqlx($_GET['edit']);
	$package=@mysql_fetch_array(mysql_query("SELECT * from `packages` WHERE `pack_id`='$pack_id'"));
	$t->assign('listing',fetch_listing($package['listing_id']));	
	$t->assign('pack',$package);
	$t->display("admin/edit_package.tpl");		
}
// start "add package submit"
if (isset($_POST['edit_package'])) {
	$pack_id=sqlx($_POST['pack_id']);
	$listing_id=sqlx($_POST['listing_id']);
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
	$price=sqlx($_POST['base_price']);
	$people_count=sqlx($_POST['people_count']);
	$people_discount=sqlx($_POST['people_discount']);
	$rooms_count=sqlx($_POST['rooms_count']);
	$rooms_discount=sqlx($_POST['rooms_discount']);
	$kids_count=sqlx($_POST['kids_count']);
	$kids_discount=sqlx($_POST['kids_discount']);
	$price_period=sqlx($_POST['price_period']);
	// Check for errors
	if (empty($listing_id)) { $error[]="Please select listing first"; }
	if (empty($start_date)) { $error[]="Empty start date"; }
	if (empty($end_date)) { $error[]="Empty end date"; }	
	if ($start_date>$end_date) { $error[]="Invalid date selected. Please check your dates"; }
	if (empty($price)) { $error[]="Empty Price"; }
	if (!empty($people_count) AND !is_numeric($people_count)) { $error[]="People Count must be numeric!"; }
	if (!empty($rooms_count) AND !is_numeric($rooms_count)) { $error[]="Rooms Count must be numeric!"; }	
	if (!empty($kids_count) AND !is_numeric($kids_count)) { $error[]="Kids Count must be numeric!"; }
	if ($people_discount > '100') { $error[]="Invalid People Discount! Cannot be greater than 100%"; }
	if ($rooms_discount > '100') { $error[]="Invalid Rooms Discount! Cannot be greater than 100%"; }
	if ($kids_discount > '100') { $error[]="Invalid Kids Discount! Cannot be greater than 100%"; }	
	if (!empty($people_discount) AND !is_numeric($people_discount)) { $error[]="People Discount must be numeric!"; }
	if (!empty($rooms_discount) AND !is_numeric($rooms_discount)) { $error[]="Rooms Discount must be numeric!"; }	
	if (!empty($kids_discount) AND !is_numeric($kids_discount)) { $error[]="Kids Discount must be numeric!"; }
	$now=time();
//	if ($start_date <= $now) { $error[]="Invalid From Date. Cannot be in the past!"; }
//	if ($end_date <= $now) { $error[]="Invalid To Date. Cannot be in the past!"; }
	// check if there is another package in this period
	$checkdups=mysql_query("SELECT * from `packages` WHERE `listing_id`='$listing_id' AND `pack_id`!='$pack_id' AND ((`from_date`<=$start_date AND `to_date`>=$start_date) OR (`from_date`<=$end_date AND `to_date`>=$end_date))");
	if (@mysql_num_rows($checkdups)>"0") { $error[]="There is another package in this date period!"; }
	// If no errors...continue
	if (count($error)=="0")	{
		mquery("UPDATE `packages` SET `from_date`='$start_date',`to_date`='$end_date',`base_price`='$price',`people_count`='$people_count',`people_discount`='$people_discount',`room_count`='$rooms_count',`room_discount`='$rooms_discount',`kids_count`='$kids_count',`kids_discount`='$kids_discount',`price_period`='$price_period' WHERE `pack_id`='$pack_id'") or die(mysql_error());
		set_msg("Package ID <b>$pack_id</b> updated successfuly!");
		header("Location: $config[base_url]/admin/packages.php?listing_id=$listing_id");
	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$package=@mysql_fetch_array(mysql_query("SELECT * from `packages` WHERE `pack_id`='$pack_id'"));
		$t->assign('listing',fetch_listing($package['listing_id']));	
		$t->assign('pack',$package);
		$t->display("admin/edit_package.tpl");		
	}	
}
?>