	{include file="admin/header.tpl"}
{include file="admin/html_editor.tpl"}
<div class="left">
			<h3>{#edit_listing#} {$listing.uri}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("title", "uri", "cat_id", "country_id", "location_id");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#title#}", "{#uri#}", "{#category#}", "{#country#}", "{#location_id#}");
</script>

<ul id="countrytabs" class="shadetabs">
{foreach from=$languages_array item=lang}
<li><a href="listings.php?edit={$listing.listing_id}&edit_lang={$lang.lang_name}" {if $edit_lang == $lang.lang_name}class="selected"{/if}><img src="{$BASE_URL}/uploads/flags/{$lang.lang_name}.gif" border="0" />&nbsp;{$lang.lang_title}</a></li>
{/foreach}
</ul>
<div id="countrydivcontainer" style="border:1px solid gray; width:920px; margin-bottom: 1em; padding: 10px">
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);" name="editfrm" ENCTYPE="multipart/form-data">
{* LISTING MENU ICONS *}
<table border="0" cellspacing="5" cellpadding="5">
<tr align="center">
<td><input type="image" src="{$BASE_URL}/admin/images/listing_save.jpg" title="{#save_changes#}" class="imgfade" /><br/>{#save_changes#}</td>
<td><a href="packages.php?listing_id={$listing.listing_id}"><img src="{$BASE_URL}/admin/images/packages_prices.png" title="{#reservations#}" class="imgfade"  border="0" /></a><br/>{#packages_prices#} ({count_packages listing_id=$listing.listing_id})</td>
<td><a href="images.php?id={$listing.listing_id}"><img src="{$BASE_URL}/admin/images/listing_images.png" {if count_images($listing.listing_id)}onMouseOver="showhint('{count_images_size listing_id=$listing.listing_id}', this, event, '70px')"{/if} title="{#manage_images#} ({count_images listing_id=$listing.listing_id})" class="imgfade"  border="0" /></a><br/>{#manage_images#} ({count_images listing_id=$listing.listing_id})</td>
<td><a href="videos.php?id={$listing.listing_id}"><img src="{$BASE_URL}/admin/images/listing_videos.png" {if count_videos($listing.listing_id)}onMouseOver="showhint('{count_videos_size listing_id=$listing.listing_id}', this, event, '70px')"{/if} title="{#manage_videos#} ({count_videos listing_id=$listing.listing_id})" class="imgfade"  border="0" /></a><br/>{#manage_videos#} ({count_videos listing_id=$listing.listing_id})</td>
<td><img style="cursor:pointer;" src="{$BASE_URL}/admin/images/listing_delete.png" onClick="DeleteItem('listings.php?delete={$listing.listing_id}')" title="{#delete_listing#}" class="imgfade"  border="0" /><br/>{#delete_listing#}</td>
<td><a href="{$BASE_URL}/preview/{$listing.uri}" target="_blank"><img src="{$BASE_URL}/admin/images/listing_preview.jpg" title="{#preview_listing#}" class="imgfade"  border="0" /></a><br/>{#preview_listing#}</td>
<td><a href="listings.php"><img src="{$BASE_URL}/admin/images/listings.png" title="{#back_listings#}" class="imgfade"  border="0" /></a><br/>{#back_listings#}</td> 
</tr>
</table><br/>
{* EOF LISTING MENU ICONS *}
<fieldset><legend><b>{#required_settings#}</b></legend>
  <fieldset style="float: right;"><legend><b>{#listing_icon#}</b></legend>
  <img src="{$BASE_URL}/uploads/thumbs/{$listing.icon}?{php}echo time();{/php}" name="listing_icon" border="0" width="49" height="49" /><br/>
{#select_icon#}<br/><select name="listing_icon_select" onChange="document.listing_icon.src = this.options[this.selectedIndex].value;">
<option value="{$BASE_URL}/uploads/thumbs/{$config.default_icon}">------------------</option>
{foreach from=$images item=icon}
	<option value="{$BASE_URL}/uploads/thumbs/{$icon}" {if $icon == $listing.icon}selected{/if}>{$icon}</option>
{/foreach}
</select> 
<br/><br/>{#upload_icon#} <br/>
<input type="file" name="icon" size="1"/>
  </fieldset>
  <fieldset style="clear: right; display:block; float: right;"><legend>{#stars#}</legend>
<input type="radio" name="stars" value="5" {if $listing.stars == "5"}checked{/if}> <img src="../images/stars-5.gif" border="0" alt="5 Stars" title="5 Stars"><br/>
<input type="radio" name="stars" value="4" {if $listing.stars == "4"}checked{/if}> <img src="../images/stars-4.gif" border="0" alt="4 Stars" title="4 Stars"><br/>
<input type="radio" name="stars" value="3" {if $listing.stars == "3"}checked{/if}> <img src="../images/stars-3.gif" border="0" alt="3 Stars" title="3 Stars"><br/>
<input type="radio" name="stars" value="2" {if $listing.stars == "2"}checked{/if}> <img src="../images/stars-2.gif" border="0" alt="2 Stars" title="2 Stars"><br/>
<input type="radio" name="stars" value="1" {if $listing.stars == "1"}checked{/if}> <img src="../images/stars-1.gif" border="0" alt="1 Stars" title="1 Stars"><br/>
<input type="radio" name="stars" value="0" {if $listing.stars == "0"}checked{/if}>none<br/>
  </fieldset>

<table border="0" class="tbl">
<tr><td>{#title#}:</td><td><input type="text" name="title" id="title" value="{$listing.title|stripslashes}" class="required" size="60" onMouseOver="showhint('{#hint_listing_title#}', this, event, '150px')" />
{if $edit_lang != $language}
<a onClick="submitChange('title');" title="{#auto_translate#} {#title#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
</td></tr>
<tr><td>{#category#}:</td><td><select name="cat_id" onMouseOver="showhint('{#hint_listing_category#}', this, event, '150px')">
<option value="0">------------------</option>
{foreach from=$categories item=category}
<option value="{$category.cat_id}" {if $category.cat_id == $listing.cat_id}selected{/if}>{$category.title}</option>
	{foreach from=$category.subcats item=subcat}
	<option value="{$subcat.cat_id}" {if $subcat.cat_id == $listing.cat_id}selected{/if}>{$category.title} -> {$subcat.title}</option>
	{/foreach}
{/foreach}
</select></td></tr>
<tr><td>{#country#}:</td><td><select name="country_id" onMouseOver="showhint('{#hint_listing_country#}', this, event, '150px')">
<option value="0">------------------</option>
{foreach from=$countries item=country}
	<option value="{$country.country_id}" {if $country.country_id == $listing.country_id}selected{/if}>{$country.title}</option>
{/foreach}
</select></td></tr>
<tr><td>{#city#}:</td><td><input type="text" id="city" name="city" value="{$listing.city}" class="required" onMouseOver="showhint('{#hint_listing_city#}', this, event, '150px')" />
{if $edit_lang != $language}
<a onClick="submitChange('city');" title="{#auto_translate#} {#city#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
</td></tr>
<tr><td>{#state#}:</td><td><select name="state_id" onMouseOver="showhint('{#hint_listing_state#}', this, event, '150px')">
<option value="0">{#outside_us#}</option>
<option value="0">------------------</option>
{foreach from=$states item=state}
<option value="{$state.state_id}" {if $state.state_id == $listing.state_id}selected{/if}>{$state.title}</option>
{/foreach}
</select></td></tr>
<tr><td>{#location_type#}:</td><td><select name="location_id" onMouseOver="showhint('{#hint_listing_location#}', this, event, '150px')">
<option value="0">------------------</option>
{foreach from=$locations item=location}
<option value="{$location.location_id}" {if $location.location_id == $listing.location_id}selected{/if}>{$location.title}</option>
{/foreach}
</select></td></tr>
<tr><td>{#short_description#}</td><td><input type="text" name="short_description" id="short_description" class="required" size="80" onMouseOver="showhint('{#hint_listing_short_description#}', this, event, '150px')" value="{$listing.short_description|stripslashes}" />
{if $edit_lang != $language}
<a onClick="submitChange('short_description');" title="{#auto_translate#} {#short_description#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
</td></tr>
</table>
{#description#}<a href="#" class="hintanchor" onMouseover="showhint('{#hint_listing_description#}', this, event, '150px')">[?]</a><textarea name="description" id="description" cols="70" rows="17">{$listing.description|stripslashes}</textarea><img src="../images/required.gif" border="0"/><br/>
{if $edit_lang != $language}
<a onClick="toggleEditor('description');submitChange('description');" title="{#auto_translate#} {#description#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
<a href="javascript:toggleEditor('description');">{#show_hide_editor#}</a>
</fieldset>
<fieldset><legend><b>{#listing_system_settings#}</b></legend>
<table border="0" class="tbl">
<tr><td>
{#price#}:</td><td><input type="text" name="price" value="{$listing.price}" size="8" onMouseOver="showhint('{#hint_listing_price#}', this, event, '150px')" {if $listing.price_set eq "package"}disabled{/if}/>
<select name="currency">
{foreach from=$currencies item=currency}
<option value="{$currency.code}" {if $listing.currency == $currency.code}selected{/if}>{$currency.code}</option>
{/foreach}
</select>
&nbsp;&nbsp;&nbsp;<input type="text" name="price_desc" id="price_desc" value="{$listing.price_desc}" size="10" onMouseOver="showhint('{#hint_listing_price_desc#}', this, event, '150px')" />
{if $edit_lang != $language}
<a onClick="submitChange('price_desc');" title="{#auto_translate#} Price Description"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
&nbsp;&nbsp;&nbsp; <a href="packages.php?listing_id={$listing.listing_id}">{#packages_prices#}</a>
</td></tr>
<tr><td>{#allow_reservation#}:</td><td><input type="checkbox" name="allow_reservation" onMouseOver="showhint('{#hint_listing_allow_reservation#}', this, event, '150px')" {if $listing.allow_reservation eq "1"}checked{/if} /></td></tr>
<tr><td>{#allow_payment#}:</td><td><input type="checkbox" name="allow_payment" onMouseOver="showhint('{#hint_listing_allow_payment#}', this, event, '150px')" {if $listing.allow_payment eq "1"}checked{/if} /></td></tr>
<tr><td>{#require_payment#}:</td><td><input type="checkbox" name="require_payment" onMouseOver="showhint('{#hint_listing_require_payment#}', this, event, '150px')" {if $listing.require_payment eq "1"}checked{/if} /></td></tr>
<tr><td>{#listing_active#}:</td><td><input type="checkbox" name="active" onMouseOver="showhint('{#hint_listing_active#}', this, event, '150px')" {if $listing.active eq "1"}checked{/if} /></td></tr>
<tr><td>{#special_listing#}?:</td><td><input type="checkbox" name="special" onMouseOver="showhint('{#hint_special_listing#}<br/><img src={$BASE_URL}/images/hot.gif border=0 />', this, event, '150px')" {if $listing.special eq "1"}checked{/if} /></td></tr>
<tr><td>{#include_sitemap#}?:</td><td><input type="checkbox" name="include_sitemap" onMouseOver="showhint('{#hint_listing_sitemap#}', this, event, '150px')" {if $listing.include_sitemap eq "1"}checked{/if}/></td></tr>
<tr><td>{#google_map_location#}:</td><td><input type="text" name="gmap_location"  onMouseOver="showhint('{#hint_listing_google_map_location#}', this, event, '150px')" value="{$listing.gmap_location}" size="30" /><a style="cursor:pointer;" onClick="javascript:window.open('{$BASE_URL}/admin/map.php?listing={$listing.listing_id}', 'interactive_map', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=700,left=120,top=60');" onMouseOver="showhint('{#hint_listing_map_choose#}', this, event, '150px')"><img src="{$BASE_URL}/images/map.gif" border="0" height="30" width="40"/></a></td></tr>
<tr><td>{#uri#}:</td><td><input type="text" name="uri" value="{$listing.uri}" class="required" onMouseOver="showhint('{#hint_listing_uri#}', this, event, '150px')" /></td></tr>
<tr><td>{#start_date#}:</td><td><input type="text" value="{if $listing.start_date != "0"}{$listing.start_date|date_format:"%d/%m/%y"}{/if}" name="start_date" id="start_date" size="8" onMouseOver="showhint('{#hint_listing_start_date#}', this, event, '150px')" /><img src="{$BASE_URL}/images/calendar.gif" id="f_trigger_s" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" /></td></tr>
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
<tr><td>{#end_date#}:</td><td><input type="text" value="{if $listing.end_date != "0"}{$listing.end_date|date_format:"%d/%m/%y"}{/if}" name="end_date" id="end_date" size="8" onMouseOver="showhint('{#hint_listing_end_date#}', this, event, '150px')" /><img src="{$BASE_URL}/images/calendar.gif" id="f_trigger_e" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" /></td></tr>
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
</table>
</fieldset>
<fieldset><legend><b>{#additional_information#}</b></legend>
<table border="0" class="tbl">
{foreach from=$types_c item=type_c}
	<tr><td>{$type_c.title}:</td>
	<td>
	{foreach from=$type_c.types item=type}
		<label class="label_types"><input type="checkbox" name="types[]" value="{$type.type_id}" title="{$type.title}" class="types_checkbox" {if in_array($type.type_id,$listing_types)}checked{/if}/>{$type.title}</label>
	{/foreach}
	</td></tr>
{/foreach}
</table>
</fieldset>
{#contact_details#}:<a href="#" class="hintanchor" onMouseover="showhint('{#hint_listing_contact_details#}', this, event, '150px')">[?]</a></td><td><textarea name="contact_details" id="contact_details" cols="70" rows="17">{$listing.contact_details|stripslashes}</textarea><br/>
{if $edit_lang != $language}
<a onClick="toggleEditor('contact_details');submitChange('contact_details');" title="{#auto_translate#} {#contact_details#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
<br/>
<a href="javascript:toggleEditor('contact_details');">{#show_hide_editor#}</a><br/><br/>
<input type="hidden" name="edit_lang" value="{$edit_lang}" />
<input type="hidden" name="edit_listing" value="{$listing.listing_id}" />
<input type="submit" value="{#save_changes#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='listings.php'" value="{#back_listings#}" /> <br/>
</form>
Editing language: <b>{$edit_lang}</b>
</div>

</div>
</div>
{include file="admin/footer.tpl"}