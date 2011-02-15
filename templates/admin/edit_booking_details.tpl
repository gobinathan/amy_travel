<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
	<meta http-equiv="Content-Type" content="text/html; charset={$language_encoding}" />
	<link rel="stylesheet" href="{$BASE_URL}/templates/admin/style.css" type="text/css" />
	<link rel="stylesheet" href="{$BASE_URL}/templates/admin/tabcontent.css" type="text/css" />	
	<script src="{$BASE_URL}/js/main.js" type="text/javascript"></script>
  <!-- calendar stylesheet -->
  <link rel="stylesheet" type="text/css" media="all" href="{$BASE_URL}/js/calendar-style.css" title="win2k-cold-1" />
  <!-- main calendar program -->
  <script type="text/javascript" src="{$BASE_URL}/js/calendar.js"></script>
  <!-- language for the calendar -->
  <script type="text/javascript" src="{$BASE_URL}/js/calendar-en.js"></script>
  <!-- the following script defines the Calendar.setup helper function, which makes
       adding a calendar a matter of 1 or 2 lines of code. -->
  <script type="text/javascript" src="{$BASE_URL}/js/calendar-setup.js"></script>
<style>
{literal}
/* BOOKING FORM */
.quoteheader {font-size: 140%; font-weight: bold; padding-top: 15px; padding-bottom: 10px;}
.quoteheaderthanks {margin-bottom: 10px;}
.toursummary {border-top: 1px solid #000000; margin-bottom: 10px; width: 100%;}
.operatordeparture {width: 48%; float: left; padding: 3px;}
.toursummary div.tourname {padding:5px; width:100%;}
.questioncontainer {border:1px solid #000000; padding: 5px; width: 98%; margin-bottom: 10px;}
.questioncontainer h1 {font-size: 115%; font-weight: bold;} 
.quotelblselect {width:98%; margin-bottom: 5px;}
.quotelblselect div {margin: 4px 5px; float: left;}
.quotelblselect div label {font-size: 100%; font-weight: bold; display: block;}
.quotelblselect div select {font-size: 100%;}

.numpeople {
	border-width: 0px;
	border-style: none;
	background-color: #f2f6fb;
	font-weight: bold;
}

.formsection {
     border: 1px dotted #cdced0;
     padding: 5px;
     background-color: #fbfcfe;
     font-family: Arial, Halvetica, Verdana;
     clear: both;
}

.formsection .nevershare {
     width: 100%;
     text-align: right;
     color: #28537e;
     font-size: 13px;
}

.formsection fieldset {
	background-color: #f2f6fb;
	border: none !important;
	border-top: 3px solid #b3c8ed !important;
	padding: 0px 50px 20px 50px;
	margin-bottom: 20px;
}

.formsection legend {
	font-size: 105%;
	font-weight: bold;
	color: #003365;
}

.quotebttn {
	width: 213px;
	height: 44px;
	padding: 0;
	margin: 0;
	border: 0;
	cursor: pointer;
	color: #fff;
	font-family: Arial, Halvetica, Verdana;
	font-size: 20px;
	font-weight: bold;
	vertical-align: baseline;
}

.quotebttn:hover {
	width: 213px;
	height: 44px;
	padding: 0;
	margin: 0;
	border: 0;
	cursor: pointer;
	color: #fff;
	font-family: Arial, Halvetica, Verdana;
	font-size: 20px;
	font-weight: bold;
	vertical-align: baseline;
}

p.para {margin: 10px 0px; padding-left: 15px;}

.generalitinfowquote {margin-top: 5px; clear: both; width: 100%;}
.generalitinfowquote .generalitinfo {width: 475px;} 
.generalitinfowquote .generalitinfo {margin-top: 5px; clear: both; color: #1d7509; float: left;}
.generalitinfowquote .generalitinfo div {padding-bottom: 5px; width: 95%;}
.generalitinfowquote .generalitinfo div div.label {font-weight: bold; width: 100px; float: left;}
.generalitinfowquote .generalitinfo div div.info {padding-left: 10px; width: 325px; float: left;}
.generalitinfowquote .qaccolades {float: left; width: 225px; margin-left: 15px; background-color: #e6e6da; padding: 10px; font-size: 120%;}
.generalitinfowquote .qaccolades span {font-size: 150%; font-weight: bold;}
.date {
	padding-right: 20px;
	background:url(../templates/frontend/{/literal}{$template}{literal}/img/calendar.gif);
	background-repeat: no-repeat;
	background-position: right center;
}
{/literal}
</style>
</head>
<body>
<div class="curlycontainer">
<div class="innerdiv">
{include file="admin/msg.tpl"}

<fieldset><legend>{#listing#}</legend>
<i><b>{$booking.listing.title|stripslashes}</b></i><img src={$BASE_URL}/uploads/thumbs/{$booking.listing.icon} border=0 height=50 width=50 style=float:left; /><br/><img src=../images/stars-{$booking.listing.stars}.gif border=0><br/>{$booking.listing.short_description|stripslashes}<br/>
</fieldset>
<script type="text/javascript">
{literal}
function validate_email(field){
	 if (field.length < 6) return false;
	 var filter=/^.+@.+\..{2,3}$/
	 if (filter.test(field))
	     return true;
	 return false;
}
  function isValidNumber(str){
	regex =  /[^0-9]/i;
	if (regex.test(str))
		return true;
	else
		return false;
}
function getRoomChildCount(roomnum){
    var roomchildcount = 0;
    var room1childelemname = "room" + roomnum + "child1age";
    var room2childelemname = "room" + roomnum + "child2age";
    var roomchild1age = document.forms["requestquote"].elements[room1childelemname].selectedIndex;
    var roomchild2age = document.forms["requestquote"].elements[room2childelemname].selectedIndex;
	roomchildcount = roomchild1age + roomchild2age;
// Uncomment the lines below if you want to count all childrens in room as 1 person
//    if (roomchild1age > 0) {
//		roomchildcount++;
//	}
//  if (roomchild2age > 0) {
//		roomchildcount++;
//	}
    return roomchildcount;
}
function validatetourquoteform(){
	var alertMsg = '{/literal}{#empty_fields#}{literal}:\n';
	var l_Msg = alertMsg.length;
	 var room1adults = document.forms["requestquote"].elements["room1adults"].selectedIndex + 1;
	 var room2adults = document.forms["requestquote"].elements["room2adults"].selectedIndex + 1;
	 var room3adults = document.forms["requestquote"].elements["room3adults"].selectedIndex + 1;
	 var room4adults = document.forms["requestquote"].elements["room4adults"].selectedIndex + 1;
	 var room1children = getRoomChildCount(1);
	 var room2children = getRoomChildCount(2);
	 var room3children = getRoomChildCount(3);
	 var room4children = getRoomChildCount(4);
	 var myroomcount = document.forms["requestquote"].elements["numrooms"].selectedIndex;
	 myroomcount = myroomcount + 1;
	 var mycontacttype = document.forms["requestquote"].elements["contact_method"].selectedIndex;
	 var myfirstname = document.forms["requestquote"].elements["first_name"].value;
	 var mylastname = document.forms["requestquote"].elements["last_name"].value;
	 var mypostalcode = document.forms["requestquote"].elements["city"].value;
	 var myemail = document.forms["requestquote"].elements["email"].value;
	 var myreemail = document.forms["requestquote"].elements["reemail"].value;
	 var myphoneareacode = document.forms["requestquote"].elements["tcodearea"].value;
	 var myphonenumber = document.forms["requestquote"].elements["tnumberphone"].value;
{/literal}
	 if (myfirstname.length < 3)
	     alertMsg += '- {#first_name#}\n';
	 if (mylastname.length < 3)
	     alertMsg += '- {#last_name#}\n';
	 if (mypostalcode.length < 4)
	     alertMsg += '- {#city#}\n';
	 if (!validate_email(myemail) || myemail != myreemail)
	     alertMsg += '- {#email_address#}\n';
	 if (mycontacttype == 2 && (myphoneareacode.length < 3 || myphonenumber.length < 7 || isValidNumber(myphoneareacode) || isValidNumber(myphonenumber)))
	     alertMsg += '- {#contact_number#}\n';
{literal}	     
	 var room1count = 0;
	 var room2count = 0;
	 var room3count = 0;
	 var room4count = 0;
	 switch (myroomcount){
		 case 1: room1count = room1adults + room1children;  break;
		 case 2: room1count = room1adults + room1children; room2count = room2adults + room2children; break;
		 case 3: room1count = room1adults + room1children; room2count = room2adults + room2children; room3count = room3adults + room3children; break;
		 case 4: room1count = room1adults + room1children; room2count = room2adults + room2children; room3count = room3adults + room3children; room4count = room4adults + room4children; break;
	 }
	{/literal}
	 if (room1count > 3) alertMsg += '- {#room#} 1 {#room_max_passengers#} 3 {#passengers#}\n';
	 if (room2count > 3) alertMsg += '- {#room#} 2 {#room_max_passengers#} 3 {#passengers#}\n';
	 if (room3count > 3) alertMsg += '- {#room#} 3 {#room_max_passengers#} 3 {#passengers#}\n';
	 if (room4count > 3) alertMsg += '- {#room#} 4 {#room_max_passengers#} 3 {#passengers#}\n';
	 {literal}
	 if (alertMsg.length == l_Msg){
		requestquote.mysubmitbttn.disabled = true;
		return true;
	}
	alert(alertMsg);
	return false;

}
function onchangepeople1(){
	 var adults = 0;
	 var children = 0;
	 var total = 0;
	 var room1adults = document.forms["requestquote"].elements["room1adults"].selectedIndex + 1;
	 var room2adults = document.forms["requestquote"].elements["room2adults"].selectedIndex + 1;
	 var room3adults = document.forms["requestquote"].elements["room3adults"].selectedIndex + 1;
	 var room4adults = document.forms["requestquote"].elements["room4adults"].selectedIndex + 1;
	 var room1children = getRoomChildCount(1);
	 var room2children = getRoomChildCount(2);
	 var room3children = getRoomChildCount(3);
	 var room4children = getRoomChildCount(4);

	 var myroomcount = document.forms["requestquote"].elements["numrooms"].selectedIndex;
	 myroomcount = myroomcount + 1;
	 switch (myroomcount){
		 case 1: adults = room1adults; children = room1children; break;
		 case 2: adults = room1adults + room2adults; children = room1children + room2children; break;
		 case 3: adults = room1adults + room2adults + room3adults; children = room1children + room2children + room3children; break;
		 case 4: adults = room1adults + room2adults + room3adults + room4adults; children = room1children + room2children + room3children + room4children; break;
	 }
	 total = adults + children;
	 document.forms["requestquote"].elements["numpeople"].value = total;
}

function disabletextbox1(){
	document.forms["requestquote"].elements["numpeople"].disabled = true;
	onchangepeople1();
}
function changecontacttype(){
	 var mycontacttype = document.getElementById("contact_method").selectedIndex;
	 if (mycontacttype == 2)
	     document.getElementById("scontacttype").style.display = '';
	 else
	     document.getElementById("scontacttype").style.display = 'none';
}
function changenumrooms(){
	 var myroomcount = document.getElementById("numrooms").selectedIndex;
	 switch (myroomcount){
		 case 0: document.getElementById("sroom2").style.display = 'none'; document.getElementById("sroom3").style.display = 'none'; document.getElementById("sroom4").style.display = 'none'; break;
		 case 1: document.getElementById("sroom2").style.display = ''; document.getElementById("sroom3").style.display = 'none'; document.getElementById("sroom4").style.display = 'none'; break;
		 case 2: document.getElementById("sroom2").style.display = ''; document.getElementById("sroom3").style.display = ''; document.getElementById("sroom4").style.display = 'none'; break;
		 case 3: document.getElementById("sroom2").style.display = ''; document.getElementById("sroom3").style.display = ''; document.getElementById("sroom4").style.display = ''; break;
	 }
}
</script>
{/literal}
{include file="errors.tpl" errors=$error error_count=$error_count}
<form name='requestquote' action='bookings.php' method='POST' onsubmit='return validatetourquoteform();'>
<div align="center">
<input name="submit" type="submit" value="{#save_changes#}" />&nbsp;&nbsp;
<input name="Button" type="button" onClick="window.location='bookings.php?id={$booking.r_id}';" value="Cancel" />&nbsp;&nbsp;
<input name="Button" type="button" onClick="DeleteItem('bookings.php?delete={$booking.r_id}');" value="{#delete#}" />&nbsp;&nbsp;
<input name="Button" type="button" onClick="window.close();" value="{#close#}" />
</div>

                   <input type='hidden' name='booking_id' value='{$booking.r_id}'>
                   <input type='hidden' name='listing_id' value='{$booking.listing.listing_id}'>
<div class="formsection clearfix">
                    <!-- System Information -->
                    <fieldset>
                        <legend>System</legend>
					<table cellspacing=1 cellpadding=1 border=0 class='mediumtxt'>
                    <tr><td><font class='mediumtxt'><b>{#confirmed_by_client#}:</b>&nbsp;&nbsp;</font></td><td><select name='confirmed_by_client'><option value='0' {if $booking.confirmed_by_client eq "0"}selected{/if}>{#answer_no#}</option><option value='1' {if $booking.confirmed_by_client eq "1"}selected{/if}>{#answer_yes#}</option></select></td></tr>
                    <tr><td><font class='mediumtxt'><b>{#confirmed_by_admin#}:</b></font></td><td><select name='confirmed_by_admin'><option value='0' {if $booking.confirmed_by_admin eq "0"}selected{/if}>{#answer_no#}</option><option value='1' {if $booking.confirmed_by_admin eq "1"}selected{/if}>{#answer_yes#}</option></select></td></tr>
                    <tr><td><font class='mediumtxt'><b>{#confirmed_by_hotel#}:</b></font></td><td><select name='confirmed_by_hotel'><option value='0' {if $booking.confirmed_by_hotel eq "0"}selected{/if}>{#answer_no#}</option><option value='1' {if $booking.confirmed_by_hotel eq "1"}selected{/if}>{#answer_yes#}</option></select></td></tr>
                    <tr><td></td><td><br/></td></tr>
                    <tr><td><b>Total Amount</b><br><input style="text-align:center" type=text name='total_price' size="4" value="{$booking.total_price}">&nbsp;{$booking.currency}</td><td><b>Paid Amount</b><br><input style="text-align:center" type=text name='paid_price' size="4" value="{$booking.paid_price}">&nbsp;{$booking.currency}</td></tr>
					</table>
                    </fieldset>

                    <!-- Contact Information -->
                    <fieldset>
                        <legend>{#contact_information#}</legend>
                        <br><table cellspacing=1 cellpadding=1 border=0 class='mediumtxt'>
                        <tr><td><b>{#first_name#}</b><br><input type=text name='first_name' maxlength=50 value="{$booking.first_name}"></td><td width=10>&nbsp;</td><td><b>{#last_name#}</b><br><input type=text name='last_name' maxlength=50 value="{$booking.last_name}"></td><td width=10>&nbsp;</td><td><b>{#city#}</b><br><input type=text name='city' size=8 maxlength=15 value="{$booking.city}"></td></tr>
                        </table>
                        <br>
                        <table cellspacing=1 cellpadding=1 border=0 class='mediumtxt'>
                        <tr><td><b>{#email_address#}</b><br><input type=text name='email' size=30 value="{$booking.email}"></td><td width=10>&nbsp;</td><td><b>{#contact_number#}</b><br/><input type=text name='tnumberphone' size=15 maxlength=17 value="{$booking.phone}"></td></tr>
                        </table>
                    </fieldset>
                    
                    <!-- Room Request -->
                    <fieldset>
                        <legend>{#room_request#}</legend>
                        <br><table cellspacing=1 cellpadding=1 border=0 class='mediumtxt' width='100%'>
						<tr><td><b>{#arrival_date#}</b><br/><input type="text" name="from_date" id="from_date" size="8" class="date" value="{$booking.from_date|date_format:"%d/%m/%y"}" /><br/>
{literal}
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "from_date",     // id of the input field
        ifFormat       :    "%d/%m/%y",      // format of the input field
        button         :    "from_date",  // trigger for the calendar (button ID)
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true
    });
</script>
{/literal}<br/><br/></td>
<td align="left">
<b>{#departure_date#}</b><br/> <input type="text" name="to_date" id="to_date" size="8" class="date" onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this)" value="{$booking.to_date|date_format:"%d/%m/%y"}"/><br/>
{literal}
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "to_date",     // id of the input field
        ifFormat       :    "%d/%m/%y",      // format of the input field
        button         :    "to_date",  // trigger for the calendar (button ID)
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true
    });
</script>
{/literal}
<br/><br/>
</td></tr>
                        <tr><td><b>{#count_rooms#}: </b><select name='numrooms' id='numrooms' onchange="onchangepeople1();changenumrooms();"><option value='1' {if $booking.count_rooms eq "1"}selected{/if}> 1 </option><option value='2' {if $booking.count_rooms eq "2"}selected{/if}> 2 </option><option value='3' {if $booking.count_rooms eq "3"}selected{/if}> 3 </option><option value='4' {if $booking.count_rooms eq "4"}selected{/if}> 4 </option></select></td><td align=right><b>{#count_people#}:</b> <input type=text name='numpeople' class='numpeople' size=1 value="{$booking.count_people}"></td></tr>
                        </table>
                        <br><table cellspacing=1 cellpadding=1 border=0 class='mediumtxt'>
                        <tr><td>&nbsp;</td><td><b>{#adults#}</b></td><td align=center width=100><b>{#kids1#}</b></td><td align=center width=100><b>{#kids2#}</b></td></tr>
                        <tr><td width=100><b>{#room#} 1:</b></td><td width=50><select name='room1adults' id='room1adults' onchange="onchangepeople1();"><option value='1' {if $booking.room1_adults eq "1"}selected{/if}> 1 </option><option value='2' {if $booking.room1_adults eq "2"}selected{/if}> 2 </option><option value='3' {if $booking.room1_adults eq "3"}selected{/if}> 3 </option></select></td>
                        <td width=100 align=center><select name='room1child1age' id='room1child1age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1' {if $booking.room1_kids1 eq "1"}selected{/if}>1</option><option value='2' {if $booking.room1_kids1 eq "2"}selected{/if}>2</option><option value='3' {if $booking.room1_kids1 eq "3"}selected{/if}>3</option><option value='4' {if $booking.room1_kids1 eq "4"}selected{/if}>4</option><option value='5' {if $booking.room1_kids1 eq "5"}selected{/if}>5</option></select></td>
                        <td width=100 align=center><select name='room1child2age' id='room1child2age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1' {if $booking.room1_kids2 eq "1"}selected{/if}>1</option><option value='2' {if $booking.room1_kids2 eq "2"}selected{/if}>2</option><option value='3' {if $booking.room1_kids2 eq "3"}selected{/if}>3</option><option value='4' {if $booking.room1_kids2 eq "4"}selected{/if}>4</option><option value='5' {if $booking.room1_kids2 eq "5"}selected{/if}>5</option></select></td>
                        </tr>
                        </table>
                        <div id='sroom2' style='{if $booking.count_rooms >= "2"}display:block;{else}display:none;{/if}'>
                        <br><table cellspacing=1 cellpadding=1 border=0 class='mediumtxt'>
                        <tr><td width=100><b>{#room#} 2:</b></td><td width=50><select name='room2adults' id='room2adults' onchange="onchangepeople1();"><option value='1' {if $booking.room2_adults eq "1"}selected{/if}> 1 </option><option value='2' {if $booking.room2_adults eq "2"}selected{/if}> 2 </option><option value='3' {if $booking.room2_adults eq "3"}selected{/if}> 3 </option></select></td>
                        <td width=100 align=center><select name='room2child1age' id='room2child1age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1' {if $booking.room2_kids1 eq "1"}selected{/if}>1</option><option value='2' {if $booking.room2_kids1 eq "2"}selected{/if}>2</option><option value='3' {if $booking.room2_kids1 eq "3"}selected{/if}>3</option><option value='4' {if $booking.room2_kids1 eq "4"}selected{/if}>4</option><option value='5' {if $booking.room2_kids1 eq "5"}selected{/if}>5</option></select></td>
                        <td width=100 align=center><select name='room2child2age' id='room2child2age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1' {if $booking.room2_kids2 eq "1"}selected{/if}>1</option><option value='2' {if $booking.room2_kids2 eq "2"}selected{/if}>2</option><option value='3' {if $booking.room2_kids2 eq "3"}selected{/if}>3</option><option value='4' {if $booking.room2_kids2 eq "4"}selected{/if}>4</option><option value='5' {if $booking.room2_kids2 eq "5"}selected{/if}>5</option></select></td>
                        </tr>
                        </table>
                        </div>
                        <div id='sroom3' style='{if $booking.count_rooms >= "3"}display:block;{else}display:none;{/if}'>
                        <br><table cellspacing=1 cellpadding=1 border=0 class='mediumtxt'>
                        <tr><td width=100><b>{#room#} 3:</b></td><td width=50><select name='room3adults' id='room3adults' onchange="onchangepeople1();"><option value='1' {if $booking.room3_adults eq "1"}selected{/if}> 1 </option><option value='2' {if $booking.room3_adults eq "2"}selected{/if}> 2 </option><option value='3' {if $booking.room3_adults eq "3"}selected{/if}> 3 </option></select></td>
                        <td width=100 align=center><select name='room3child1age' id='room3child1age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1' {if $booking.room3_kids1 eq "1"}selected{/if}>1</option><option value='2' {if $booking.room3_kids1 eq "2"}selected{/if}>2</option><option value='3' {if $booking.room3_kids1 eq "3"}selected{/if}>3</option><option value='4' {if $booking.room3_kids1 eq "4"}selected{/if}>4</option><option value='5' {if $booking.room3_kids1 eq "5"}selected{/if}>5</option></select></td>                        
                        <td width=100 align=center><select name='room3child2age' id='room3child2age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1' {if $booking.room3_kids2 eq "1"}selected{/if}>1</option><option value='2' {if $booking.room3_kids2 eq "2"}selected{/if}>2</option><option value='3' {if $booking.room3_kids2 eq "3"}selected{/if}>3</option><option value='4' {if $booking.room3_kids2 eq "4"}selected{/if}>4</option><option value='5' {if $booking.room3_kids2 eq "5"}selected{/if}>5</option></select></td>
                        </tr>
                        </table>
                        </div>
                        <div id='sroom4' style='{if $booking.count_rooms >= "4"}display:block;{else}display:none;{/if}'>
                        <br><table cellspacing=1 cellpadding=1 border=0 class='mediumtxt'>
                        <tr><td width=100><b>{#room#} 4:</b></td><td width=50><select name='room4adults' id='room4adults' onchange="onchangepeople1();"><option value='1' {if $booking.room4_adults eq "1"}selected{/if}> 1 </option><option value='2' {if $booking.room4_adults eq "2"}selected{/if}> 2 </option><option value='3' {if $booking.room4_adults eq "3"}selected{/if}> 3 </option></select></td>                        
                        <td width=100 align=center><select name='room4child1age' id='room4child1age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1' {if $booking.room4_kids1 eq "1"}selected{/if}>1</option><option value='2' {if $booking.room4_kids1 eq "2"}selected{/if}>2</option><option value='3' {if $booking.room4_kids1 eq "3"}selected{/if}>3</option><option value='4' {if $booking.room4_kids1 eq "4"}selected{/if}>4</option><option value='5' {if $booking.room4_kids1 eq "5"}selected{/if}>5</option></select></td>
                        <td width=100 align=center><select name='room4child2age' id='room4child2age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1' {if $booking.room4_kids2 eq "1"}selected{/if}>1</option><option value='2' {if $booking.room4_kids2 eq "2"}selected{/if}>2</option><option value='3' {if $booking.room4_kids2 eq "3"}selected{/if}>3</option><option value='4' {if $booking.room4_kids2 eq "4"}selected{/if}>4</option><option value='5' {if $booking.room4_kids2 eq "5"}selected{/if}>5</option></select></td>
                        </tr>
                        </table>
                        </div>
                    </fieldset>

                    <!-- Extras -->
{if count($listing.insurance_types)}
                    <fieldset>
                        <legend>{#booking_extras#}</legend><br>
<font class='mediumtxt'><b>{#travel_insurance#}</b></font> &nbsp; <select name='travelinsurance'><option value='No'>{#travel_insurance_no#}</option>
{foreach from=$listing.insurance_types item=ins}
<option value='{$ins.i_id}'>{$ins.title|stripslashes} ({$ins.price} {$listing.currency} {#per_person#})</option>
{/foreach}
</select><br>
</fieldset>
{/if}
                    <br>                    
                    <fieldset>
                        <legend>Admin Notes</legend>
                    <textarea name='admin_notes' rows=6 cols=45>{$booking.admin_notes|stripslashes}</textarea><br>
                    <center><input type=submit value='{#save_changes#}' class='quotebttn' name='mysubmitbttn' id='mysubmitbttn'></center>
                    </fieldset>
                
                </div>
                </form>
</body></html>