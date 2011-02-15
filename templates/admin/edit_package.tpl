{include file="admin/header.tpl"}
<div class="left">
			<h3>Add New Package</h3>
			<div class="left_box">
{* LISTING MENU ICONS *}
<table border="0" cellspacing="5" cellpadding="5">
<tr align="center">
<td><a href="listings.php?edit={$listing.listing_id}"><img src="{$BASE_URL}/admin/images/listings.png" title="{#edit_listing#} {$item.title|stripslashes}" class="imgfade"  border="0" /></a><br/>{#edit_listing#}</td> 
<td><a href="packages.php?listing_id={$listing.listing_id}"><img src="{$BASE_URL}/admin/images/packages_prices.png" title="{#reservations#}" class="imgfade"  border="0" /></a><br/>{#packages_prices#} ({count_packages listing_id=$listing.listing_id})</td>
<td><a href="images.php?id={$listing.listing_id}"><img src="{$BASE_URL}/admin/images/listing_images.png" {if count_images($listing.listing_id)}onMouseOver="showhint('{count_images_size listing_id=$listing.listing_id}', this, event, '70px')"{/if} title="{#manage_images#} ({count_images listing_id=$listing.listing_id})" class="imgfade"  border="0" /></a><br/>{#manage_images#} ({count_images listing_id=$listing.listing_id})</td>
<td><a href="videos.php?id={$listing.listing_id}"><img src="{$BASE_URL}/admin/images/listing_videos.png" {if count_videos($listing.listing_id)}onMouseOver="showhint('{count_videos_size listing_id=$listing.listing_id}', this, event, '70px')"{/if} title="{#manage_videos#} ({count_videos listing_id=$listing.listing_id})" class="imgfade"  border="0" /></a><br/>{#manage_videos#} ({count_videos listing_id=$listing.listing_id})</td>
<td><img style="cursor:pointer;" src="{$BASE_URL}/admin/images/listing_delete.png" onClick="DeleteItem('listings.php?delete={$listing.listing_id}')" title="{#delete_listing#}" class="imgfade"  border="0" /><br/>{#delete_listing#}</td>
<td><a href="{$BASE_URL}/preview/{$listing.uri}" target="_blank"><img src="{$BASE_URL}/admin/images/listing_preview.jpg" title="{#preview_listing#}" class="imgfade"  border="0" /></a><br/>{#preview_listing#}</td>
</tr>
</table><br/>
{* EOF LISTING MENU ICONS *}
			
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<form method="post" action="{$smarty.server.PHP_SELF|xss}">
<div id="frm">
<label>From Date:</label><input type="text" value="{if $pack.from_date != "0"}{$pack.from_date|date_format:"%d/%m/%y"}{/if}" name="start_date" id="start_date" size="8" onMouseOver="showhint('{#hint_package_start_date#}', this, event, '150px')" /><img src="{$BASE_URL}/images/calendar.gif" id="f_trigger_s" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" /><br/>
{literal}
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "start_date",     // id of the input field
        ifFormat       :    "%d/%m/%y",      // format of the input field
        button         :    "f_trigger_s",  // trigger for the calendar (button ID)
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true
    });
</script>
{/literal}

<label>To Date:</label><input type="text" value="{if $pack.to_date != "0"}{$pack.to_date|date_format:"%d/%m/%y"}{/if}" name="end_date" id="end_date" size="8" onMouseOver="showhint('{#hint_package_end_date#}', this, event, '150px')" /><img src="{$BASE_URL}/images/calendar.gif" id="f_trigger_e" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" /><br/>
{literal}
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "end_date",     // id of the input field
        ifFormat       :    "%d/%m/%y",      // format of the input field
        button         :    "f_trigger_e",  // trigger for the calendar (button ID)
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true
    });
</script>
{/literal}

<label>Price (<b>{$listing.currency}</b>):</label><input type="text" name="base_price" size="4" value="{$pack.base_price}">&nbsp; (<b>{$listing.currency}</b>)<br/><label>{#per#} </label><select name="price_period" style="width:70px;">
<option value="1" {if $pack.price_period eq "1"}selected{/if}>{#day#}</option><option value="7" {if $pack.price_period eq "7"}selected{/if}>{#week#}</option><option value="30" {if $pack.price_period eq "30"}selected{/if}>{#month#}</option><option value="365" {if $pack.price_period eq "365"}selected{/if}>{#year#}</option>
</select><br/>
<fieldset style="width:600px;text-align:center;"><legend>Discounts</legend>
<fieldset style="float:left;"><legend>People</legend>
<label>Count:</label><input type="text" name="people_count" size="2" value="{$pack.people_count}"/><br/>
<label>Discount:</label><input type="text" name="people_discount" size="2"  value="{$pack.people_discount}"/> <b>%</b><br/>
</fieldset>
<fieldset style="float:left;"><legend>Rooms</legend>
<label>Count:</label><input type="text" name="rooms_count" size="2"  value="{$pack.room_count}"/><br/>
<label>Discount:</label><input type="text" name="rooms_discount" size="2"  value="{$pack.room_discount}"/> <b>%</b><br/>
</fieldset>
<fieldset style="float:left;"><legend>Kids</legend>
<label>Count:</label><input type="text" name="kids_count" size="2"  value="{$pack.kids_count}"/><br/>
<label>Discount:</label><input type="text" name="kids_discount" size="2"  value="{$pack.kids_discount}"/> <b>%</b><br/>
</fieldset>
</fieldset>
<input type="hidden" name="pack_id" value="{$pack.pack_id}">
<input type="hidden" name="listing_id" value="{$listing.listing_id}">
<input type="submit" name="edit_package" value="Edit Package" />
</form>
</div>
</div>
{include file="admin/footer.tpl"}