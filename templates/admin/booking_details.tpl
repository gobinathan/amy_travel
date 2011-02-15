<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="{$BASE_URL}/js/main.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html; charset={$language_encoding}" />
<title>{#reservation_details#}</title>
{literal}
<style type="text/css">
body { 
	font: 0.8em Tahoma, sans-serif; 
	line-height: 1.5em;
	background: #fff; 
	color: #454545; 
}

a {	color: #E0691A;	background: inherit;}
a:hover { color: #6C757A; background: inherit; }
.curlycontainer{
border: 1px solid #b8b8b8;
margin-bottom: 1em;
width: 470px;
}
.curlycontainer .innerdiv{
background: transparent url(../images/brcorner.gif) bottom right no-repeat;
position: relative;
left: 2px;
top: 2px;
padding: 1px 4px 15px 5px;
}
</style>
{/literal}
</head>
<body>
<div class="curlycontainer">
<div class="innerdiv">
{include file="admin/msg.tpl"}
<fieldset><legend>{#listing#}</legend>
<i><b>{$booking.listing.title|stripslashes}</b></i><img src={$BASE_URL}/uploads/thumbs/{$booking.listing.icon} border=0 height=50 width=50 style=float:left; /><br/><img src=../images/stars-{$booking.listing.stars}.gif border=0><br/>{$booking.listing.short_description|stripslashes}<br/>
</fieldset>
<span style="float:right;">{#date_added#}: {$booking.date_added|date_format:"%d/%b/%Y %H:%M:%S"}</span>
<br/>
<div align="center">
<input name="Button" type="button" onClick="window.location='bookings.php?edit={$booking.r_id}'" value="Edit" />&nbsp;&nbsp;
<input name="Button" type="button" onClick="DeleteItem('bookings.php?delete={$booking.r_id}');" value="{#delete#}" />&nbsp;&nbsp;
<input name="Button" type="button" onClick="window.close();" value="{#close#}" />
</div>
<fieldset><legend>{#booking_details#}</legend>
{#first_name#}: {$booking.first_name}<br/>
{#last_name#}: {$booking.last_name}<br/>
{#email_address#}: {$booking.email}<br/>
{#city#}: {$booking.city}<br/>
{if $booking.phone}{#contact_number#}: {$booking.phone}<br/>{/if}
{#arrival_date#}: {$booking.from_date|date_format:"%d/%b/%Y"}<br/>
{#departure_date#}: {$booking.to_date|date_format:"%d/%b/%Y"}<br/>
{#count_rooms#}: {$booking.count_rooms}<br/>
</fieldset>
<fieldset><legend>Rooms</legend>
<fieldset style="float:left;"><legend>{#room#} 1</legend>
{if $booking.room1_adults > "0"}{#adults#}: {$booking.room1_adults}<br/>{/if}
{if $booking.room1_kids1 > "0"}{#kids1#}: {$booking.room1_kids1}<br/>{/if}
{if $booking.room1_kids2 > "0"}{#kids2#}: {$booking.room1_kids2}<br/>{/if}
</fieldset>
{if $booking.count_rooms >= "2"}
<fieldset style="float:left;"><legend>{#room#} 2</legend>
{if $booking.room2_adults > "0"}{#adults#}: {$booking.room2_adults}<br/>{/if}
{if $booking.room2_kids1 > "0"}{#kids1#}: {$booking.room2_kids1}<br/>{/if}
{if $booking.room2_kids2 > "0"}{#kids2#}: {$booking.room2_kids2}<br/>{/if}
</fieldset>
{/if}
{if $booking.count_rooms >= "3"}
<fieldset style="float:left;"><legend>{#room#} 3</legend>
{if $booking.room3_adults > "0"}{#adults#}: {$booking.room3_adults}<br/>{/if}
{if $booking.room3_kids1 > "0"}{#kids1#}: {$booking.room3_kids1}<br/>{/if}
{if $booking.room3_kids2 > "0"}{#kids2#}: {$booking.room3_kids2}<br/>{/if}
</fieldset>
{/if}
{if $booking.count_rooms >= "4"}
<fieldset style="float:left;"><legend>{#room#} 4</legend>
{if $booking.room4_adults > "0"}{#adults#}: {$booking.room4_adults}<br/>{/if}
{if $booking.room4_kids1 > "0"}{#kids1#}: {$booking.room4_kids1}<br/>{/if}
{if $booking.room4_kids2 > "0"}{#kids2#}: {$booking.room4_kids2}<br/>{/if}
</fieldset>
{/if}
</fieldset><br/>
<fieldset style="clear:both;padding-top:20px;"><legend>{#payment_details#}</legend>
{#base_price_tip#}: {$booking.price_base_per_day} {$booking.currency}<br/>
{#duration#}: {$booking.days} {#days#}<br/>
{if $booking.total_discount > 0}
<fieldset><legend>{#discounts#}</legend>
{if $booking.price_per_day_people_discount}{#people_discount_tip#}: {$booking.price_per_day_people_discount} {$booking.currency}<br/>{/if}
{if $booking.price_per_day_kids_discount}{#kids_discount_tip#}: {$booking.price_per_day_kids_discount} {$booking.currency}<br/>{/if}
{if $booking.price_per_day_room_discount}{#rooms_discount_tip#}: {$booking.price_per_day_room_discount} {$booking.currency}<br/>{/if}
<b>{#total_discounts#}: {$booking.total_discount} {$booking.currency}<br/></b> 
</fieldset>
<br/><br/>
{/if}
<div align="center">
<b>{#total_price#}: {$booking.total_price} {$booking.currency}</b><br/>
</div>
</fieldset>
<hr/>
{if $booking.user_comment}User {#reserve_comment#}: <b>{$booking.user_comment}</b><br/><br/>{/if}
{if $booking.admin_notes}Note: <b>{$booking.admin_notes}</b><br/><br/>{/if}
</div></div>
</body></html>