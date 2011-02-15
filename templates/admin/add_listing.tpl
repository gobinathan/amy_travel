{include file="admin/header.tpl"}
{include file="admin/html_editor.tpl"}
<div class="left">
			<h3>{#add_listing#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("title", "cat_id", "country_id", "location_id", "city");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#title#}", "{#category#}", "{#country#}", "{#location_type#}", "{#city#}");
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);" name="editfrm" ENCTYPE="multipart/form-data">
<fieldset><legend><b>{#required_settings#}</b></legend>
  <fieldset style="float: right;"><legend><b>{#listing_icon#}</b></legend>
  <img src="{$BASE_URL}/uploads/thumbs/{$config.default_icon}" name="listing_icon" alt="Default Icon" border="0" width="69" height="59" /><br/>
{#upload_icon#} <br/>
<input type="file" name="icon" size="1"/>
  </fieldset>
    <fieldset style="clear: right; display:block; float: right;"><legend>{#stars#}</legend>
<input type="radio" name="stars" value="5" {if $smarty.post.stars == "5"}checked{/if}> <img src="../images/stars-5.gif" border="0" alt="5 Stars" title="5 Stars"><br/>
<input type="radio" name="stars" value="4" {if $smarty.post.stars == "4"}checked{/if}> <img src="../images/stars-4.gif" border="0" alt="4 Stars" title="4 Stars"><br/>
<input type="radio" name="stars" value="3" {if $smarty.post.stars == "3"}checked{/if}> <img src="../images/stars-3.gif" border="0" alt="3 Stars" title="3 Stars"><br/>
<input type="radio" name="stars" value="2" {if $smarty.post.stars == "2"}checked{/if}> <img src="../images/stars-2.gif" border="0" alt="2 Stars" title="2 Stars"><br/>
<input type="radio" name="stars" value="1" {if $smarty.post.stars == "1"}checked{/if}> <img src="../images/stars-1.gif" border="0" alt="1 Stars" title="1 Stars"><br/>
<input type="radio" name="stars" value="0" {if !$smarty.post.stars}checked{/if}>none<br/>
  </fieldset>  

<table border="0" class="tbl">
<tr><td>{#title#}:</td><td><input type="text" name="title" value="{$smarty.post.title}" class="required" size="60" onMouseOver="showhint('{#hint_listing_title#}', this, event, '150px')" /></td></tr>
<tr><td>{#category#}:</td><td><select name="cat_id" onMouseOver="showhint('{#hint_listing_category#}', this, event, '150px')">
<option value="0">------------------</option>
{foreach from=$categories item=category}
<option value="{$category.cat_id}" {if $category.cat_id == $smarty.post.cat_id OR $sublisting.cat_id == $category.cat_id}selected{/if}>{$category.title}</option>
	{foreach from=$category.subcats item=subcat}
	<option value="{$subcat.cat_id}" {if $subcat.cat_id == $smarty.post.cat_id OR $sublisting.cat_id == $subcat.cat_id}selected{/if}>{$category.title} -> {$subcat.title}</option>
	{/foreach}
{/foreach}
</select></td></tr>
<tr><td>{#country#}:</td><td><select name="country_id" onMouseOver="showhint('{#hint_listing_country#}', this, event, '150px')">
<option value="0">------------------</option>
{foreach from=$countries item=country}
	<option value="{$country.country_id}" {if $country.country_id == $smarty.post.country_id OR $sublisting.country_id == $country.country_id}selected{/if}>{$country.title}</option>
{/foreach}
</select></td></tr>
<tr><td>{#city#}:</td><td><input type="text" name="city" value="{if $sublisting.city}{$sublisting.city}{else}{$smarty.post.city}{/if}" class="required" onMouseOver="showhint('{#hint_listing_city#}', this, event, '150px')" /></td></tr>
<tr><td>{#state#}:</td><td><select name="state_id" onMouseOver="showhint('{#hint_listing_state#}', this, event, '150px')">
<option value="0">{#outside_us#}</option>
<option value="0">------------------</option>
{foreach from=$states item=state}
<option value="{$state.state_id}" {if $state.state_id == $smarty.post.state_id OR $sublisting.state_id == $state.state_id}selected{/if}>{$state.title}</option>
{/foreach}
</select></td></tr>
<tr><td>{#location_type#}:</td><td><select name="location_id" onMouseOver="showhint('{#hint_listing_location#}', this, event, '150px')">
<option value="0">------------------</option>
{foreach from=$locations item=location}
<option value="{$location.location_id}" {if $location.location_id == $smarty.post.location_id OR $sublisting.location_id == $location.location_id}selected{/if}>{$location.title}</option>
{/foreach}
</select></td></tr>
<tr><td>{#short_description#}</td><td><input type="text" name="short_description"  class="required" size="80" onMouseOver="showhint('{#hint_listing_short_description#}', this, event, '150px')" value="{$smarty.post.short_description}"/></td></tr>
</table>
{#description#}<a href="#" class="hintanchor" onMouseover="showhint('{#hint_listing_description#}', this, event, '150px')">[?]</a><textarea name="description" cols="70" rows="17">{$smarty.post.description}</textarea><img src="../images/required.gif" border="0"/><br/>
<a href="javascript:toggleEditor('description');">{#show_hide_editor#}</a>
</fieldset>
<fieldset><legend><b>{#listing_system_settings#}</b></legend>
<table border="0" class="tbl">
<tr><td>
{#price#}:</td><td><input type="text" name="price" value="{$smarty.post.price}" size="8" onMouseOver="showhint('{#hint_listing_price#}', this, event, '150px')" />
<select name="currency">
{foreach from=$currencies item=currency}
<option value="{$currency.code}">{$currency.code}</option>
{/foreach}
</select>
&nbsp;&nbsp;&nbsp;<input type="text" name="price_desc" value="{$smarty.post.price_desc}" size="10" onMouseOver="showhint('{#hint_listing_price_desc#}', this, event, '150px')" />
</td></tr>
<tr><td>{#allow_reservation#}:</td><td><input type="checkbox" name="allow_reservation" onMouseOver="showhint('{#hint_listing_allow_reservation#}', this, event, '150px')" {if $smarty.post.allow_reservation eq "on"}checked{/if} /></td></tr>
<tr><td>{#allow_payment#}:</td><td><input type="checkbox" name="allow_payment" onMouseOver="showhint('{#hint_listing_allow_payment#}', this, event, '150px')" {if $smarty.post.allow_payment eq "on"}checked{/if} /></td></tr>
<tr><td>{#require_payment#}:</td><td><input type="checkbox" name="require_payment" onMouseOver="showhint('{#hint_listing_require_payment#}', this, event, '150px')" {if $smarty.post.require_payment eq "on"}checked{/if} /></td></tr>
<tr><td>{#listing_active#}:</td><td><input type="checkbox" name="active" onMouseOver="showhint('{#hint_listing_active#}', this, event, '150px')" checked /></td></tr>
<tr><td>{#special_listing#}?:</td><td><input type="checkbox" name="special" onMouseOver="showhint('{#hint_special_listing#}<br/><img src={$BASE_URL}/images/hot.gif border=0 />', this, event, '150px')" {if $smarty.post.special eq "on"}checked{/if}/></td></tr>
<tr><td>{#include_sitemap#}?:</td><td><input type="checkbox" name="include_sitemap" onMouseOver="showhint('{#hint_listing_sitemap#}', this, event, '150px')" checked/></td></tr>
<tr><td>{#google_map_location#}:</td><td><input type="text" name="gmap_location" value="{if $sublisting.gmap_location}{$sublisting.gmap_location}{else}{$smarty.post.gmap_location}{/if}" onMouseOver="showhint('{#hint_listing_google_map_location#}', this, event, '150px')" size="30" /><a style="cursor:pointer;" onClick="javascript:window.open('{$BASE_URL}/admin/map.php?listing={$listing.listing_id}', 'interactive_map', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=700,left=120,top=60');" onMouseOver="showhint('{#hint_listing_map_choose#}', this, event, '150px')"><img src="{$BASE_URL}/images/map.gif" border="0" height="30" width="40"/></a></td></tr>
<tr><td>{#uri#}:</td><td><input type="text" value="{$smarty.post.uri}" name="uri" onMouseOver="showhint('{#hint_listing_uri#}', this, event, '150px')" /></td></tr>
<tr><td>{#start_date#}:</td><td><input type="text" value="{$smarty.post.start_date}" id="start_date" name="start_date" size="8" onMouseOver="showhint('{#hint_listing_start_date#}', this, event, '150px')" /><img src="{$BASE_URL}/images/calendar.gif" id="f_trigger_s" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" /></td></tr>
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
<tr><td>{#end_date#}:</td><td><input type="text" value="{$smarty.post.end_date}" id="end_date" name="end_date" size="8" onMouseOver="showhint('{#hint_listing_end_date#}', this, event, '150px')" /><img src="{$BASE_URL}/images/calendar.gif" id="f_trigger_e" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" /></td></tr>
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
		<label class="label_types"><input type="checkbox" name="types[]" value="{$type.type_id}" title="{$type.title}" class="types_checkbox" 
		{if $smarty.post.types AND in_array($type.type_id,$smarty.post.types)}checked{/if}
		/>{$type.title}</label>
	{/foreach}
	</td></tr>
{/foreach}
</table>
</fieldset>
{#contact_details#}:<a href="#" class="hintanchor" onMouseover="showhint('{#hint_listing_contact_details#}', this, event, '150px')">[?]</a></td><td><textarea name="contact_details" cols="70" rows="17">{if $sublisting.contact_details}{$sublisting.contact_details}{else}{$smarty.post.contact_details}{/if}</textarea><br/>
<a href="javascript:toggleEditor('contact_details');">{#show_hide_editor#}</a><br/><br/>
<input type="submit" name="add_listing" value="{#add_listing#}" /> &nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='listings.php'" value="{#back_listings#}" /></form>
</div>
</div>
{include file="admin/footer.tpl"}