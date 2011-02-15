<?php
//error_reporting(E_ALL | E_STRICT);
@session_start();

if ($request[0]=="currency") {
	update_total();
}
elseif ($conf['auto_convert_currency'] == "1") {
	update_total();
}

// FUNCTIONS
function add_item ($itemid) {
	global $conf, $t;
	$listing=fetch_listing($itemid);
	if (count($listing)) {
		$favourites=array();
		$favourites=$_SESSION['favourites'];
		$favourites[$itemid]=$listing;
		$_SESSION['favourites']=$favourites; // Update Session Variables
	}
	update_total();
//	$t->display("frontend/$template/favourites_box.tpl");
}
function del_item ($itemid) {
  global $t;
	$favourites=array();
	$favourites=$_SESSION['favourites'];
	unset($favourites[$itemid]);
	$_SESSION['favourites']=$favourites; // Update Session Variables
	update_total();	
//	$t->display("frontend/$template/favourites_box.tpl");
}
function edit_item ($itemid,$price,$currency,$price_desc) {
	$favourites=array();
	$favourites=$_SESSION['favourites'];
	if (count($favourites[$itemid]) > 0) {
		$favourites[$itemid]['price']=$price;
		$favourites[$itemid]['currency']=$currency;
		$favourites[$itemid]['price_desc']=$price_desc;		
	}
	$_SESSION['favourites']=$favourites; // Update Session Variables
	update_total();	
}

function empty_favourites () {
	unset($_SESSION['favourites']);
	$_SESSION['favourites_total']=0;
}
function update_total () {
	global $conf;
	$favourites=array();
	$favourites=$_SESSION['favourites'];
	if (count($favourites) > 0) {
		foreach($favourites as $item) {
			if ($conf['currency']!==$item[currency]) {
				$item[price]=convert_currency ($item[currency], $conf['currency'], $item[price]);
				$item[currency]=$conf['currency'];
				edit_item($item[listing_id],$item[price],$item[currency],$item[price_desc]);
			}
		}
		$_SESSION['favourites_count']=count($favourites);
	}  
}

function in_favourites ($itemid) {
	$favourites=array();
	$favourites=$_SESSION['favourites'];
	if (count($favourites[$itemid]) > 0) {
		return true;
	}else{ return false; }
}
// EOF FUNCTIONS
if ($request[0]=="favourites") {
	if ($request[1]=="add" AND is_numeric($request[2])) {
		add_item($request[2]);
	}
	if ($request[1]=="remove" AND is_numeric($request[2])) {
		del_item($request[2]);
	}

	// Show favourites
	$t->assign('title',"Favourites");
	$t->assign('favourites',$_SESSION['favourites']);
	$t->assign('favourites_total',$_SESSION['favourites_total']);
	$t->display("frontend/$template/favourites.tpl");
}
?>
