<?php
$t->assign('title',$lang_globals['booking_title']);
// START "BOOKING CHANGE"
if ($request[1]=="change") {
	$booking=$_SESSION['res'];
	$booking['listing']=fetch_listing($booking['listing_id']);
	$t->assign('booking',$booking);	    
	$t->display("frontend/$template/booking_change.tpl");			
}
// EOF "BOOKING CHANGE"
// START "BOOKING REVIEW"
if (isset($_POST['listing_id'])) {
		$listing_id=sqlx($_POST['listing_id']);
		$first_name=sqlx($_POST['first_name']);
		$last_name=sqlx($_POST['last_name']);
		$city=sqlx($_POST['city']);
		$email=sqlx($_POST['email']);
		$reemail=sqlx($_POST['reemail']);
		$contact_method=sqlx($_POST['contact_method']);
		if (isset($_POST['tcodearea'])) {
			$phone="".sqlx($_POST['tcodearea'])."".sqlx($_POST['tnumberphone'])."";
		}else{
			$phone=sqlx($_POST['tnumberphone']);		  
		}
		$from_date=sqlx($_POST['from_date']);
		if (empty($from_date)) { $from_date = "0"; }
		else {
			$from_date_str=@explode('/',$from_date);
			if (!is_numeric($from_date_str[0]) OR !is_numeric($from_date_str[1]) OR !is_numeric($from_date_str[2])) { $error[]='Invalid Date of Arrival'; }
		  	$from_date=@mktime(0, 0, 0, $from_date_str[1], $from_date_str[0], $from_date_str[2]);
		}
	  $to_date=sqlx($_POST['to_date']);
	  if (empty($to_date)) { $to_date = "0"; }
	  else {
		  $to_date_str=@explode('/',$to_date);
			if (!is_numeric($to_date_str[0]) OR !is_numeric($to_date_str[1]) OR !is_numeric($to_date_str[2])) { $error[]='Invalid Date of Departure'; }
		  $to_date=@mktime(0, 0, 0, $to_date_str[1], $to_date_str[0], $to_date_str[2]);
	  }  
		$count_rooms=sqlx($_POST['numrooms']);
		$count_people=sqlx($_POST['numpeople']);
		$comments=sqlx($_POST['comments']);
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

		// travel insurance
		$insurance_id=sqlx($_POST['travelinsurance']);
		$existingcust=sqlx($_POST['existingcust']);
		
		// Check for errors
		if (empty($first_name)) { $error[]=$lang_errors['empty_fullname']; }
		if (empty($last_name)) { $error[]=$lang_errors['empty_fullname']; }
		if (empty($email)) { $error[]=$lang_errors['empty_email']; }
		if (empty($reemail)) { $error[]=$lang_errors['empty_email']; }		
		if ($email!==$reemail) { $error[]=$lang_errors['invalid_email']; }
		if (!is_email($email)) { $error[]=$lang_errors['invalid_email']; }
		if ($from_date>$to_date) { $error[]="Invalid date selected. Please check your reservation dates"; }
		// If no errors...continue
		if (count($error)=="0")	{
			$count_adults=$room1adults;
			$count_kids1=$room1kids1;
			$count_kids2=$room1kids2;
			$now=time();
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
			$res['insurance_id']=$insurance_id;
			$res['existingcust']=$existingcust;
			$count_kids=$count_kids1+$count_kids2;
			$res['count_adults']=$count_adults;			
			$res['count_kids1']=$count_kids1;			
			$res['count_kids2']=$count_kids2;			
						
			// calculate total price
			$listing=fetch_listing($listing_id);
			$t->assign('blisting',$listing);
			$days=round(($to_date-$from_date)/84600); // enter -1 at the end if the date of departure is not calculated as night at hotel
			$res['days']=$days;
			$res['currency']=$listing[currency];
			
if ($listing['price_set'] == 'static') {
	$base_price=$listing['price'];	
	$total_price=$base_price*$count_people;
	$res['total_price']=round($total_price);	
}
// START PACKAGE PRICE SET
if ($listing['price_set'] == 'package') {
			$package=fetch_package($listing_id,$from_date,$to_date);
			$t->assign('package',$package);			
			$base_price=$package['base_price'];
			$price_period=$package['price_period'];
			if ($price_period=="1") {
				$price_per_day=$base_price;
			}
			if ($price_period=="7") {
				$price_per_day=round($base_price/7,2);
			}
			if ($price_period=="30") {
				$price_per_day=round($base_price/30,2);
			}
			if ($price_period=="365") {
				$price_per_day=round($base_price/365,2);
			}			
			$res['price_base_per_day']=$price_per_day;
			// calculate if any people discounts
			if ($package['people_count'] > '0' AND $package['people_discount'] > '0' AND $count_people >= $package['people_count'])  {
				$price_per_day=$price_per_day-percent($package['people_discount'],$price_per_day);  
				$res['price_per_day_people_discount']=percent($package['people_discount'],$price_per_day);
			}
			// calculate if any kids discounts
			if ($package['kids_count'] > '0' AND $package['kids_discount'] > '0' AND $count_kids >= $package['kids_count'])  {
				$kids_price_per_day=$price_per_day-percent($package['kids_discount'],$price_per_day);  
				$res['price_per_day_kids_discount']=percent($package['kids_discount'],$price_per_day);
			}
			// calculate if any rooms discounts
			if ($package['room_count'] > '0' AND $package['room_discount'] > '0' AND $count_rooms >= $package['room_count'])  {
				$price_per_day=$price_per_day-percent($package['room_discount'],$price_per_day);  
				$res['price_per_day_room_discount']=percent($package['room_discount'],$price_per_day);
			}
			if (empty($kids_price_per_day)) { $kids_price_per_day=$price_per_day; }
			if ($count_kids>'0' AND !empty($kids_price_per_day)) {
				$total_price_kids=$count_kids*$kids_price_per_day*$days;
				$res['price_total_kids']=$total_price_kids;
			}
			$res['total_discount']=($res['price_per_day_people_discount']+$res['price_per_day_kids_discount']+$res['price_per_day_room_discount'])*$days;

			$total_price_adults=$count_adults*$price_per_day*$days;
			$res['price_total_adults']=$total_price_adults;
			$total_price=$total_price_adults+$total_price_kids;
			$res['total_price']=round($total_price);
}
// EOF OF PACKAGE PRICE SET
			// ADD MEMBER IN DATABASE AND SEND WELCOME/CONFIRM EMAIL
			$new_user_id=add_member($res[email]);
			if (empty($new_user_id)) {
				$t->assign('existing_customer',$res[email]);
				$fusr=fetch_member($res[email]);
				$new_user_id=$fusr['user_id'];
			}else{
				$member=fetch_member($res[email]);
				if (is_numeric($member[user_id])) {
					$t->assign('member',$member);
					$_SESSION['member']=$member;
				}

			}
			$res['existing_customer']=$res[email];
			$res['user_id']=$new_user_id;
			$_SESSION['res']=$res;
			$t->assign('res',$res);
			$getgw=mysql_query("SELECT * from `payment_gw`");
			$payment_gw=@mysql_fetch_array($getgw);
		   	$t->assign('payment_gw',$payment_gw);
			$t->display("frontend/$template/booking_review.tpl");			
		}else{
		   	$t->assign('error',$error);
			$t->assign('error_count',count($error));
			$t->assign('listing',fetch_listing($listing_id));
			$t->display("frontend/$template/listing.tpl");
		}
	}
// EOF "BOOKING REVIEW"	
// START "BOOKING CHECKOUT"
if ($request['1']=='checkout') {
	$res=$_SESSION['res'];
	$res['payment_gw']=sqlx($_POST['payment_gw']);
	$t->assign('res',$res);
	$listing=fetch_listing($res['listing_id']);
	$t->assign('listing',$listing);
	// check if this listing requires payment after booking
	if ($listing['require_payment']=='1' AND $_POST['payment_gw']=='cash') {
		die("You cannot pay in Cash. Please select Payment Gateway!");
	}
	$now=time();
	mysql_query("INSERT into `bookings` values ('','$res[listing_id]','$res[user_id]','$res[email]','$res[first_name]','$res[last_name]','$res[city]','$res[phone]','$res[contact_method]','$res[comments]','Admin Notes','$res[total_price]','0','$res[payment_gw]','$res[from_date]','$res[to_date]','0','0','0','$res[count_rooms]','$res[room1adults]','$res[room1kids1]','$res[room1kids2]','$res[room2adults]','$res[room2kids1]','$res[room2kids2]','$res[room3adults]','$res[room3kids1]','$res[room3kids2]','$res[room4adults]','$res[room4kids1]','$res[room4kids2]','$now')") or die(mysql_error());
	$booking_id=@mysql_insert_id();
// START "SEND BOOKING DETAILS BY EMAIL"
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
		$tpl_email->assign('member', $_SESSION['member']);
		$tpl_email->assign('listing', $listing);
		$tpl_email->assign('booking', fetch_booking($booking_id));
		$subject = $tpl_email->fetch("email_subject:member_booking_pending");
		$email_message = $tpl_email->fetch("email:member_booking_pending");
		// Get member_register from email
		$gettpl=mysql_query("SELECT `from_email` from `email_templates` WHERE `tpl_name`='member_booking_pending'");		  

		$from_email=@mysql_result($gettpl,0,'from_email');
		// Send E-Mail
		send_mail($email,"$conf[system_name] <$from_email>",$subject,$email_message);
		// EOF ---------- Send email to Member -------------

		// ---------- Send email to Admin -------------
		// Parse Email Template
		$tpl_aemail = & new_smarty();
	    $tpl_aemail->force_compile = true;
		$t->register_resource("email", array("email_get_template",
                                       "email_get_timestamp",
                                       "email_get_secure",
                                       "email_get_trusted"));
		$t->register_resource("email_subject", array("email_subject_get_template",
                                       "email_subject_get_timestamp",
                                       "email_subject_get_secure",
                                       "email_subject_get_trusted"));  
		// assign additional template variables
		$tpl_aemail->assign('member', $_SESSION['member']);
		$tpl_aemail->assign('listing', $listing);
		$tpl_aemail->assign('booking', fetch_booking($booking_id));
		$asubject = $tpl_aemail->fetch("email_subject:admin_booking_pending");
		$aemail_message = $tpl_aemail->fetch("email:admin_booking_pending");
		// Get member_register from email
		$gettpl=mysql_query("SELECT `from_email` from `email_templates` WHERE `tpl_name`='admin_booking_pending'");		  

		$afrom_email=@mysql_result($gettpl,0,'from_email');
		// Send E-Mail
		send_mail($conf[system_email],"$conf[system_name] <$afrom_email>",$asubject,$aemail_message);
		// EOF ---------- Send email to Admin -------------			
// EOF "SEND BOOKING DETAILS BY EMAIL"
	if ($_POST['payment_gw']!=='cash' AND $listing['allow_payment'] == '1' OR $listing['require_payment'] == '1') {
		if ($res['total_price'] > "0") {
		$res['payment_gw']=sqlx($_POST['payment_gw']);
		$getgw=mysql_query("SELECT * from `payment_gw`");
		$payment_gw_details=@mysql_fetch_array($getgw);
	   	$t->assign('payment_gw',$payment_gw_details);
		mysql_query("INSERT into `transactions` values('','$booking_id','$res[user_id]','$res[payment_gw]','recipient name','$res[first_name] $res[last_name]','$res[email]','$res[total_price]','$listing[currency]','$now','0','0','waiting for data...')") or die(mysql_error());
		$order_id=@mysql_insert_id();
		$t->assign('order_id',$order_id);
		// START "PAYPAL GATEWAY"
		if ($res['payment_gw']=="paypal")	{
			mysql_query("UPDATE `transactions` SET `recipient`='$payment_gw_details[paypal_id]',`approved_by_admin`='$payment_gw_details[paypal_approve]' WHERE `order_id`='$order_id'");
			$t->display("frontend/$template/payment_paypal.tpl");
		}
		// EOF "PAYPAL GATEWAY"		
		// START "2CHECKOUT GATEWAY"
		if ($res['payment_gw']=="2checkout")	{
			if ($payment_gw_details['2checkout']=="1") { $co_approve='1'; }else{ $co_approve='0'; }
			$payment_co_recipient=$payment_gw_details['2checkout'];
			mysql_query("UPDATE `transactions` SET `recipient`='$payment_co_recipient',`approved_by_admin`='$co_approve' WHERE `order_id`='$order_id'");
			$t->display("frontend/$template/payment_2checkout.tpl");
		}
		// EOF "2CHECKOUT GATEWAY"		
		// START "AUTHORIZE.NET GATEWAY"
		if ($res['payment_gw']=="authorize")	{
			mysql_query("UPDATE `transactions` SET `recipient`='$payment_gw_details[authorize_id]',`approved_by_admin`='$payment_gw_details[authorize_approve]' WHERE `order_id`='$order_id'");
			$t->display("frontend/$template/payment_authorize.tpl");
		}
		// EOF "AUTHORIZE.NET GATEWAY"					
		// START "BANK WIRE TRANSFER DETAILS"
		if ($res['payment_gw']=="bw")	{
			mysql_query("UPDATE `transactions` SET `recipient`='$payment_gw_details[bw_recipient]',`approved_by_admin`='0' WHERE `order_id`='$order_id'");
			$t->display("frontend/$template/payment_bank_wire.tpl");
		}
		// EOF "BANK WIRE TRANSFER DETAILS"		
	}else {
		unset($_SESSION['res']); // unset booking
		$t->display("frontend/$template/booking_confirmed.tpl");		
	}			
		} else {
		unset($_SESSION['res']); // unset booking
		$t->display("frontend/$template/booking_confirmed.tpl");		
	}

}
// EOF "BOOKING CHECKOUT"

elseif(is_array($_SESSION['res']) AND empty($_POST['listing_id']) AND $request['1']!=='checkout') {
	$res=$_SESSION['res'];
	$t->assign('res',$res);
	$listing=fetch_listing($res['listing_id']);
	$t->assign('blisting',$listing);
	$getgw=mysql_query("SELECT * from `payment_gw`");
	$payment_gw=@mysql_fetch_array($getgw);
   	$t->assign('payment_gw',$payment_gw);
	$t->display("frontend/$template/booking_review.tpl");			  
}

?>