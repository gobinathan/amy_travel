<div id="reservation" class="tabcontent">
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
<script src="{$BASE_URL}/js/effects.js" type="text/javascript"></script>
<br/><br/>
{include file="errors.tpl" errors=$error error_count=$error_count}
{if $reserve_status eq "1"}
{#reservation_success_msg#}<br/>
{else}
<h1 class="pageheadline">{#booking_title#}</h1><form name='requestquote' action='{$baseurl}/booking' method='POST' onsubmit='return validatetourquoteform();'>
<div align="center" id="prices_and_packages">
{if $base_price > "0" AND $listing.price_set eq "package"}
<a href="#" onClick="doSlide('packages');return false;" title="{#packages_prices#}">
	{#price#} {$base_price} {$listing.currency} {#per#} {if $base_price_period == "1"}{#day#}{/if}
	{if $base_price_period == "7"}{#week#}{/if}
	{if $base_price_period == "30"}{#month#}{/if}
	{if $base_price_period == "365"}{#year#}{/if}
</a>
{else}
	{if $listing.price > "0" AND $listing.price_set eq "static"}
		{#package#} {#price#}: {$listing.price} {$listing.currency} {if $listing.price_desc}{$listing.price_desc|stripslashes}{/if}
	{else}
		{#no_price_set#}
	{/if}
{/if}
<div id="packages" style="display:none;background:white;overflow:auto;height:200px;width:300px;text-align:center;">
<h2>{#packages_prices#}</h2>
{foreach from=$packages item=pack}
{#from#} <b>{$pack.from_date|date_format:"%d/%b/%Y"}</b> {#to#} <b>{$pack.to_date|date_format:"%d/%b/%Y"}</b><br/>
{#price#} <font color="green">{$pack.base_price} {$listing.currency}</font> {#per#} {if $pack.price_period == "1"}{#day#}{/if}
{if $pack.price_period == "7"}{#week#}{/if}
{if $pack.price_period == "30"}{#month#}{/if}
{if $pack.price_period == "365"}{#year#}{/if}<br/>
<hr>
{/foreach}
</div>
</div>
<br/>
                   <input type='hidden' name='listing_id' value='{$listing.listing_id}'>
				   <div id="newquotebegin" style='margin-left: 15px; margin-top: 10px;'>{#booking_tip#}<br><br></div>
<div class="formsection clearfix">
                    <div class="nevershare">{#email_privacy#}</div>
                    <!-- Contact Information -->
                    <fieldset>
                        <legend>{#contact_information#}</legend>
                        <br><table cellspacing=1 cellpadding=1 border=0 class='mediumtxt' width='100%'>
                        <tr><td><b>{#first_name#}</b><br><input type=text name='first_name' maxlength=50></td><td width=10>&nbsp;</td><td><b>{#last_name#}</b><br><input type=text name='last_name' maxlength=50></td><td width=10>&nbsp;</td><td><b>{#city#}</b><br><input type=text name='city' size=8 maxlength=15></td></tr>
                        </table>
                        <br>
                        <table cellspacing=1 cellpadding=1 border=0 class='mediumtxt'>
                        <tr><td><b>{#email_address#}</b><br><input type=text name='email' size=30 value="{$member.email}"></td><td width=10>&nbsp;</td><td><b>{#email_address_repeat#}</b><br><input type=text name='reemail' size=30 value="{$member.email}"></td></tr>
                        <tr><td colspan=3><br><b>{#contact_method#}</b> <select name='contact_method' id='contact_method' onchange="javascript:changecontacttype();"><option value=''>{#select#}</option><option value='1'>{#email#}</option><option value='2'>{#phone#}</option></select></td></tr>
                        <tr><td colspan=3><div id='scontacttype' style='display: none'><br><b>{#contact_number#}:</b> ( <input type=text name='tcodearea' size=3 maxlength=3> ) &nbsp; <input type=text name='tnumberphone' size=10 maxlength=17> &nbsp; &nbsp; <font class='smalltxt'>(e.g. (XXX) XXXXXXX)</font></div></td></tr>
                        </table>
                    </fieldset>
                    
                    <!-- Room Request -->
                    <fieldset>
                        <legend>{#room_request#}</legend>
                        <br><table cellspacing=1 cellpadding=1 border=0 class='mediumtxt' width='100%'>
						<tr><td>{#arrival_date#}:<input type="text" value="dd/mm/yy" name="from_date" id="from_date" size="8" class="date" value="{$smarty.post.from_date}" /><br/>
{literal}
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "from_date",     // id of the input field
        ifFormat       :    "%d/%m/%y",      // format of the input field
        button         :    "from_date",  // trigger for the calendar (button ID)
        align          :    " ",           // alignment (defaults to "Bl")
        singleClick    :    true
    });
</script>
{/literal}<br/><br/></td>
<td align="left">
{#departure_date#}: <input type="text" value="dd/mm/yy" name="to_date" id="to_date" size="8" class="date" onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this)" value="{$smarty.post.to_date}"/><br/>
{literal}
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "to_date",     // id of the input field
        ifFormat       :    "%d/%m/%y",      // format of the input field
        button         :    "to_date",  // trigger for the calendar (button ID)
        align          :    " ",           // alignment (defaults to "Bl")
        singleClick    :    true
    });
</script>
{/literal}
<br/><br/>
</td></tr>
                        <tr><td><b>{#count_rooms#}: </b><select name='numrooms' id='numrooms' onchange="onchangepeople1();changenumrooms();"><option value='1'> 1 </option><option value='2'> 2 </option><option value='3'> 3 </option><option value='4'> 4 </option></select></td><td align=right><b>{#count_people#}:</b> <input type=text name='numpeople' class='numpeople' size=1 value="1"></td></tr>
                        </table>
                        <br><table cellspacing=1 cellpadding=1 border=0 class='mediumtxt'>
                        <tr><td>&nbsp;</td><td><b>{#adults#}</b></td><td align=center width=100><b>{#kids1#}</b></td><td align=center width=100><b>{#kids2#}</b></td></tr>
                        <tr><td width=100><b>{#room#} 1:</b></td><td width=50><select name='room1adults' id='room1adults' onchange="onchangepeople1();"><option value='1'> 1 </option><option value='2'> 2 </option><option value='3'> 3 </option></select></td>
                        <td width=100 align=center><select name='room1child1age' id='room1child1age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option></select></td>
                        <td width=100 align=center><select name='room1child2age' id='room1child2age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option></select></td>
                        </tr>
                        </table>
                        
                        <div id='sroom2' style='display: none'>
                        <br><table cellspacing=1 cellpadding=1 border=0 class='mediumtxt'>
                        <tr><td width=100><b>{#room#} 2:</b></td><td width=50><select name='room2adults' id='room2adults' onchange="onchangepeople1();"><option value='1'> 1 </option><option value='2'> 2 </option><option value='3'> 3 </option></select></td>
                        <td width=100 align=center><select name='room2child1age' id='room2child1age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option></select></td>
                        <td width=100 align=center><select name='room2child2age' id='room2child2age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option></select></td>
                        </tr>
                        </table>
                        </div>

                        <div id='sroom3' style='display: none'>
                        <br><table cellspacing=1 cellpadding=1 border=0 class='mediumtxt'>
                        <tr><td width=100><b>{#room#} 3:</b></td><td width=50><select name='room3adults' id='room3adults' onchange="onchangepeople1();"><option value='1'> 1 </option><option value='2'> 2 </option><option value='3'> 3 </option></select></td>
                        <td width=100 align=center><select name='room3child1age' id='room3child1age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option></select></td>
                        <td width=100 align=center><select name='room3child2age' id='room3child2age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option></select></td>
                        </tr>
                        </table>
                        </div>

                        <div id='sroom4' style='display: none'>
                        <br><table cellspacing=1 cellpadding=1 border=0 class='mediumtxt'>
                        <tr><td width=100><b>{#room#} 4:</b></td><td width=50><select name='room4adults' id='room4adults' onchange="onchangepeople1();"><option value='1'> 1 </option><option value='2'> 2 </option><option value='3'> 3 </option></select></td>
                        <td width=100 align=center><select name='room4child1age' id='room4child1age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option></select></td>
                        <td width=100 align=center><select name='room4child2age' id='room4child2age' onchange="onchangepeople1();"><option value=''>N/A</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option></select></td>
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
                        <legend>{#booking_comments#}</legend>
                        <br><font class='mediumtxt'>{#booking_comments_tip#}<br>
                    <textarea name='comments' rows=6 cols=45></textarea><br><br>
                    <font class='mediumtxt'><b>{#booking_before#} {$conf.site_title}?</b></font> &nbsp; <select name='existingcust'><option value='No'>{#answer_no#}</option><option value='Yes' {if $member}selected{/if}>{#answer_yes#}</option></select><br>
                    <br>
                    <center><input type=submit value='{#booking_step2#}' class='quotebttn' name='mysubmitbttn' id='mysubmitbttn'></center><br>
                    </fieldset>
                
                </div>
                </form>
                <br>
{/if}
</div>
