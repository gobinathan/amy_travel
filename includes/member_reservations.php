<?php
$t->assign("page_title",$lang_globals[reservations]);
$sql=mysql_query("SELECT * from `bookings` WHERE `user_id`='$member[user_id]' ORDER BY `r_id` DESC");
$reservations=array();
while($res=@mysql_fetch_array($sql)) {
	// get listing details
	$listing=fetch_listing($res[listing_id]);
	$res['listing']=$listing;
	array_push($reservations, $res);
}
$t->assign('reservations',$reservations);
$t->display("frontend/$template/member_reservations.tpl");	

?>
