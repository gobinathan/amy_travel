<?php
$t->assign("page_title",$lang_members[menu_orders]);
$sql=mysql_query("SELECT * from `orders` WHERE `user_id`='$member[user_id]' ORDER BY `order_id` DESC");
$orders=array();
while($order=@mysql_fetch_array($sql)) {
	// get listing details
	$listing=fetch_listing($order[listing_id]);
	$order['listing']=$listing;
	array_push($orders, $order);
}
$t->assign('orders',$orders);
$t->display("frontend/$template/member_orders.tpl");	

?>
