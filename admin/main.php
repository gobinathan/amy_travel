<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");

@clearstatcache();
if (file_exists("../install/")) {
	$error[]=$lang_errors['install_dir_exists'];
}
$install_dir_perms=substr(sprintf('%o', fileperms('../')), -4);
if ($install_dir_perms == "0777") {
	$error[]=$lang_errors['main_dir_writable'];
}
$admin_id=$_SESSION['admin_id'];
$getadminpass=mysql_query("SELECT `password` from `admins` WHERE `username`='admin' AND `password`='VdFaHdFbsdnUs5kdPZFZVV2V0dVVB1TP'");
if (@mysql_num_rows($getadminpass) > "0") {
	$error[]="You should <a href=admins.php?edit=1>change</a> your admin password!";
}
if (count($error)) {
	$t->assign('error',$error);
	$t->assign('error_count',count($error));
}

// FETCH LATEST OFFERS
$getlistings=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `lang`='$default_lang' OR `lang`='$edit_lang' ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`listings`.`added_date` DESC LIMIT 10");

$listings=array();
while($listing=@mysql_fetch_array($getlistings)) {
	if (multiarray_search($listings, 'listing_id', $listing[listing_id]) == "-1") {
		array_push($listings, $listing);
	}
}
//$listings=sortArrayByField($listings,"added_date",true);
$t->assign('listings',$listings);

// FETCH memberS WAITING FOR APPROVAL
if ($conf[member_allow_register] == "1") {
$getmembers=mysql_query("SELECT * from `members` WHERE `approved`='0'");
$members=array();
while($member=@mysql_fetch_array($getmembers)) {
	array_push($members, $member);
}
//$listings=sortArrayByField($listings,"added_date",true);
$t->assign('nonapproved_members',$members);
}
// EOF FETCH memberS WAITING FOR APPROVAL

// FETCH member OFFERS WAITING FOR APPROVAL
$getalistings=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `member_id`!='0' AND `active`='0' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
$nonapproved_member_listings=array();
while($listing=@mysql_fetch_array($getalistings)) {
	if (multiarray_search($nonapproved_member_listings, 'listing_id', $listing[listing_id]) == "-1") {
		$getmember=mysql_query("SELECT * from `members` WHERE `member_id`='$listing[member_id]'");
		$listing[member]=@mysql_fetch_array($getmember);
		array_push($nonapproved_member_listings, $listing);
	}
}
$t->assign('nonapproved_member_listings',$nonapproved_member_listings);
// EOF FETCH member OFFERS WAITING FOR APPROVAL

// FETCH member OFFERS WAITING FOR DELETION
$getdlistings=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `member_id`!='0' AND `delete_approval`='1' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
$listings_waiting_for_deletion=array();
while($listing=@mysql_fetch_array($getdlistings)) {
	if (multiarray_search($listings_waiting_for_deletion, 'listing_id', $listing[listing_id]) == "-1") {
		$getmember=mysql_query("SELECT * from `members` WHERE `member_id`='$listing[member_id]'");
		$listing[member]=@mysql_fetch_array($getmember);
		array_push($listings_waiting_for_deletion, $listing);
	}
}
$t->assign('listings_waiting_for_deletion',$listings_waiting_for_deletion);
// EOF FETCH member OFFERS WAITING FOR DELETION

// FETCH LATEST ORDERS
$getorders=mysql_query("SELECT * from `orders` ORDER BY `order_id` DESC LIMIT 10");
$orders=array();
while($order=@mysql_fetch_array($getorders)) {
	// get member details
	$member=@mysql_fetch_array(mysql_query("SELECT * from `members` WHERE `member_id`='$order[member_id]'"));
	$member['access_video_size']=ByteSize($member[access_video_size]).ByteSize($member[access_video_size],true);	
	$order['member']=$member;
	// get credit plan details
	$credit_plan=@mysql_fetch_array(mysql_query("SELECT * from `credit_packs` WHERE `plan_id`='$order[plan_id]'"));
	$order['plan']=$credit_plan;
	array_push($orders, $order);
}
$t->assign('orders',$orders);
// EOF FETCH LATEST ORDERS
$t->display("admin/main.tpl");
?>
