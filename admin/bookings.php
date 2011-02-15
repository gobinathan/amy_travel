<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php"); // Check if user is logged in as admin
include("common.php");

// START "CONFIRM BOOKING"
if (isset($_GET['confirm'])) {
	$r_id=sqlx($_GET['confirm']);
	mysql_query("UPDATE `bookings` SET `confirmed`='1' WHERE `r_id`='$r_id'");
	$booking=fetch_booking($r_id);
	$member=fetch_member($booking['member_id']);
	$listing=fetch_listing($booking['listing_id']);
		// ---------- Send email to Member -------------
		// Parse Email Template
		$tpl_email = & new_smarty();
	    $tpl_email->force_compile = true;
		$t->register_resource("email", array("email_get_template",
                                       "email_get_timestamp",
                                       "email_get_secure",
                                       "email_get_trusted"));
		$t->register_resource("email_subject", array("email_subject_get_template",
                                       "email_subject_get_timestamp",
                                       "email_subject_get_secure",
                                       "email_subject_get_trusted"));  
		// assign additional template variables
		$tpl_email->assign('member', $member);
		$tpl_email->assign('listing', $listing);
		$tpl_email->assign('booking', $booking);
		$subject = $tpl_email->fetch("email_subject:member_booking_confirmed");
		$email_message = $tpl_email->fetch("email:member_booking_confirmed");
		// Get member_register from email
		$gettpl=mysql_query("SELECT `from_email` from `email_templates` WHERE `tpl_name`='member_booking_confirmed'");		  

		$from_email=@mysql_result($gettpl,0,'from_email');
		// Send E-Mail
		send_mail($email,"$conf[system_name] <$from_email>",$subject,$email_message);
		// EOF ---------- Send email to Member -------------
	set_msg("Reservation <b>$r_id</b> confirmed.<br/>Notification e-mail sent to <b>$member[email]</b>");		
}
// EOF "CONFIRM BOOKING"

// START "SHOW BOOKING DETAILS"
if (isset($_GET['id'])) {
	$r_id=sqlx($_GET['id']);
	$booking=fetch_booking($r_id);
//
	$package=fetch_package($booking['listing_id'],$booking['from_date'],$booking['to_date']);
//			$t->assign('package',$package);			
	$count_adults=$booking['room1adults'];
	$count_kids1=$booking['room1kids1'];
	$count_kids2=$booking['room1kids2'];
	if ($booking['count_rooms'] >= "2") {
		$count_adults=$count_adults+$room2adults;
		$count_kids1=$count_kids1+$room2kids1;
		$count_kids2=$count_kids2+$room2kids2;
	}
	if ($booking['count_rooms'] >= "3") {
		$count_adults=$count_adults+$room3adults;
		$count_kids1=$count_kids1+$room3kids1;
		$count_kids2=$count_kids2+$room3kids2;
	}
	if ($booking['count_rooms'] == "4") {
		$count_adults=$count_adults+$room4adults;
		$count_kids1=$count_kids1+$room4kids1;
		$count_kids2=$count_kids2+$room4kids2;
	}

	$base_price=$package['base_price'];
	$price_period=$package['price_period'];
	if ($price_period=="1") {
		$price_per_day=$base_price;
	}
	if ($price_period=="7") {
		$price_per_day=round($base_price/7);
	}
	if ($price_period=="30") {
		$price_per_day=round($base_price/30);
	}
	if ($price_period=="365") {
		$price_per_day=round($base_price/365);
	}			
	$booking['price_base_per_day']=$price_per_day;
	// calculate if any people discounts
	if ($package['people_count'] > '0' AND $package['people_discount'] > '0' AND $count_people >= $package['people_count'])  {
		$price_per_day=$price_per_day-percent($package['people_discount'],$price_per_day);  
		$booking['price_per_day_people_discount']=percent($package['people_discount'],$price_per_day);
	}
	// calculate if any kids discounts
	if ($package['kids_count'] > '0' AND $package['kids_discount'] > '0' AND $count_kids >= $package['kids_count'])  {
		$kids_price_per_day=$price_per_day-percent($package['kids_discount'],$price_per_day);  
		$booking['price_per_day_kids_discount']=percent($package['kids_discount'],$price_per_day);
	}
	// calculate if any rooms discounts
	if ($package['room_count'] > '0' AND $package['room_discount'] > '0' AND $count_rooms >= $package['room_count'])  {
		$price_per_day=$price_per_day-percent($package['room_discount'],$price_per_day);  
		$booking['price_per_day_room_discount']=percent($package['room_discount'],$price_per_day);
	}
	$days=round(($booking['to_date']-$booking['from_date'])/84600);
	$booking['days']=$days;
	if ($count_kids>'0' AND !empty($kids_price_per_day)) {
		$total_price_kids=$count_kids*$kids_price_per_day*$days;
		$booking['price_total_kids']=$total_price_kids;
	}
	$booking['total_discount']=($booking['price_per_day_people_discount']+$booking['price_per_day_kids_discount']+$booking['price_per_day_room_discount'])*$days;

	$total_price_adults=$count_adults*$price_per_day*$days;
	$booking['price_total_adults']=$total_price_adults;
//

   	$t->assign('booking',$booking);
	$t->display("admin/booking_details.tpl");
}
// EOF "SHOW BOOKING DETAILS"

// START "DELETE BOOKING"
elseif (isset($_GET['delete'])) {
	$r_id=sqlx($_GET['delete']);
	mysql_query("DELETE from `bookings` WHERE `r_id`='$r_id'");
	set_msg("Booking <b>$r_id</b> deleted.");		
	echo "<html><head></head><body onload=\"";
	echo "opener.document.location.href='bookings.php';window.close();";
	echo "\"></body></html>";
}
// EOF "DEACTIVATE BOOKING"
// START "EDIT BOOKING"
elseif (isset($_GET['edit'])) {
	$r_id=sqlx($_GET['edit']);
	$booking=fetch_booking($r_id);
   	$t->assign('booking',$booking);
	$t->display("admin/edit_booking_details.tpl");
}
// EOF "EDIT BOOKING"

// START "SUBMIT EDIT BOOKING"
elseif (isset($_POST['booking_id'])) {
	$booking_id=sqlx($_POST['booking_id']);
	$listing_id=sqlx($_POST['listing_id']);
	$confirmed_by_client=sqlx($_POST['confirmed_by_client']);
	$confirmed_by_admin=sqlx($_POST['confirmed_by_admin']);
	$confirmed_by_hotel=sqlx($_POST['confirmed_by_hotel']);
	$admin_notes=sqlx($_POST['admin_notes']);
	$paid_price=sqlx($_POST['paid_price']);
	$total_price=sqlx($_POST['total_price']);
	// update the booking details
		$first_name=sqlx($_POST['first_name']);
		$last_name=sqlx($_POST['last_name']);
		$city=sqlx($_POST['city']);
		$email=sqlx($_POST['email']);
		$phone=sqlx($_POST['tnumberphone']);
		$from_date=sqlx($_POST['from_date']);
		if (empty($from_date)) { $from_date = "0"; }
		else {
			$from_date_str=explode('/',$from_date);
		  	$from_date=mktime(0, 0, 0, $from_date_str[1], $from_date_str[0], $from_date_str[2]);
		}
	  $to_date=sqlx($_POST['to_date']);
	  if (empty($to_date)) { $to_date = "0"; }
	  else {
		  $to_date_str=explode('/',$to_date);
		  $to_date=mktime(0, 0, 0, $to_date_str[1], $to_date_str[0], $to_date_str[2]);
	  }  
		$count_rooms=sqlx($_POST['numrooms']);
		$count_people=sqlx($_POST['numpeople']);
		// Room Request variables
		// Room 1
		$room1adults=sqlx($_POST['room1adults']);
		$room1kids1=sqlx($_POST['room1child1age']);
		$room1kids2=sqlx($_POST['room1child2age']);
		// Room 2
		$room2adults=sqlx($_POST['room2adults']);
		$room2kids1=sqlx($_POST['room2child1age']);
		$room2kids2=sqlx($_POST['room2child2age']);
		// Room 3
		$room3adults=sqlx($_POST['room3adults']);
		$room3kids1=sqlx($_POST['room3child1age']);
		$room3kids2=sqlx($_POST['room3child2age']);
		// Room 4
		$room4adults=sqlx($_POST['room4adults']);
		$room4kids1=sqlx($_POST['room4child1age']);
		$room4kids2=sqlx($_POST['room4child2age']);
		
		// Check for errors
		if (empty($first_name)) { $error[]=$lang_errors['empty_fullname']; }
		if (empty($last_name)) { $error[]=$lang_errors['empty_fullname']; }
		if (empty($email)) { $error[]=$lang_errors['empty_email']; }
		if (!is_email($email)) { $error[]=$lang_errors['invalid_email']; }
//		if ($from_date>$to_date) { $error[]="Invalid date selected. Please check your reservation dates"; }
		// If no errors...continue
		if (count($error)=="0")	{
			$count_adults=$room1adults;
			$count_kids1=$room1kids1;
			$count_kids2=$room1kids2;
			$res['listing_id']=$listing_id;
			$res['first_name']=$first_name;
			$res['last_name']=$last_name;
			$res['city']=$city;
			$res['email']=$email;
			$res['contact_method']=$contact_method;
			$res['phone']=$phone;
			$res['from_date']=$from_date;
			$res['to_date']=$to_date;
			$res['count_rooms']=$count_rooms;
			$res['count_people']=$count_people;
			$res['comments']=$comments;
			$res['room1adults']=$room1adults;
			$res['room1kids1']=$room1kids1;
			$res['room1kids2']=$room1kids2;
			if ($count_rooms >= "2") {
				$res['room2adults']=$room2adults;
				$res['room2kids1']=$room2kids1;
				$res['room2kids2']=$room2kids2;
				$count_adults=$count_adults+$room2adults;
				$count_kids1=$count_kids1+$room2kids1;
				$count_kids2=$count_kids2+$room2kids2;
			}
			if ($count_rooms >= "3") {
				$res['room3adults']=$room3adults;
				$res['room3kids1']=$room3kids1;
				$res['room3kids2']=$room3kids2;
				$count_adults=$count_adults+$room3adults;
				$count_kids1=$count_kids1+$room3kids1;
				$count_kids2=$count_kids2+$room3kids2;
			}
			if ($count_rooms == "4") {
				$res['room4adults']=$room4adults;
				$res['room4kids1']=$room4kids1;
				$res['room4kids2']=$room4kids2;
				$count_adults=$count_adults+$room4adults;
				$count_kids1=$count_kids1+$room4kids1;
				$count_kids2=$count_kids2+$room4kids2;
			}
			$count_kids=$count_kids1+$count_kids2;
			$res['count_adults']=$count_adults;			
			$res['count_kids1']=$count_kids1;			
			$res['count_kids2']=$count_kids2;			
						
			mysql_query("UPDATE `bookings` SET 
			`email`='$res[email]',
			`first_name`='$res[first_name]',
			`last_name`='$res[last_name]',
			`city`='$res[city]',
			`phone`='$res[phone]',
			`admin_notes`='$admin_notes',
			`total_price`='$total_price',
			`paid_price`='$paid_price',
			`from_date`='$res[from_date]',
			`to_date`='$res[to_date]',
			`count_rooms`='$res[count_rooms]',
			`room1_adults`='$res[room1adults]',
			`room1_kids1`='$res[room1kids1]',
			`room1_kids2`='$res[room1kids2]',
			`room2_adults`='$res[room2adults]',
			`room2_kids1`='$res[room2kids1]',
			`room2_kids2`='$res[room2kids2]',
			`room3_adults`='$res[room3adults]',
			`room3_kids1`='$res[room3kids1]',
			`room3_kids2`='$res[room3kids2]',
			`room4_adults`='$res[room4adults]',
			`room4_kids1`='$res[room4kids1]',
			`room4_kids2`='$res[room4kids2]',
			`confirmed_by_admin`='$confirmed_by_admin',
			`confirmed_by_client`='$confirmed_by_client',
			`confirmed_by_hotel`='$confirmed_by_hotel' 
			WHERE `r_id`='$booking_id' AND `listing_id`='$listing_id'") or die(mysql_error());

	// eof update booking details	
	set_msg("Booking <b>$booking_id</b> was changed successful.<script>opener.document.location.href='bookings.php';</script>");
	$booking=fetch_booking($booking_id);
   	$t->assign('booking',$booking);
	$t->display("admin/edit_booking_details.tpl");	
} else {
   	$t->assign('error',$error);
	$t->assign('error_count',count($error));
	$booking=fetch_booking($booking_id);
   	$t->assign('booking',$booking);
	$t->display("admin/edit_booking_details.tpl");	  
}
}
// EOF "SUBMIT EDIT BOOKING"

// START "SHOW BOOKINGS"
else {
	$getbookings=mysql_query("SELECT * from `bookings` ORDER BY `date_added` DESC");
	$bookings=array();
	while($res=@mysql_fetch_array($getbookings)) {
			$res['listing']=fetch_listing($res['listing_id']);
			$last_order=fetch_order($r_id);
			if (valid_currency($tmp_order['currency'])) {
				$res['currency']=$tmp_order['currency'];
			}else {
				$res['currency']=$res['listing']['currency'];
			}

			array_push($bookings, $res);
	}
   	$t->assign('bookings',$bookings);
	$t->display("admin/bookings.tpl");	
}
// EOF "SHOW BOOKINGS"

?>