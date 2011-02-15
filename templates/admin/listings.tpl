{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#listings#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
			<input class="submit" name="Button" type="button" onClick="window.location='listings.php?add'" value="{#add_listing#}" />&nbsp;&nbsp;&nbsp;
			<input class="submit" name="Button" type="button" onClick="window.location='listings.php?expired=show'" value="{#show_expired_listings#}" /><br/>			
<fieldset><legend><b>{#filter#}</b></legend>
<form method="post" action="">
<select name="filter_category"><option>{#category#}</option><option>-------</option>
{foreach from=$categories item=category}
<option value="{$category.cat_id}" {if $smarty.request.filter_category eq $category.cat_id}selected style="background-color:#99CC00;"{/if}>{$category.title}</option>
	{foreach from=$category.subcats item=subcat}
	<option value="{$subcat.cat_id}" {if $smarty.request.filter_category eq $subcat.cat_id}selected style="background-color:#99CC00;"{/if}>{$category.title} -> {$subcat.title}</option>
	{/foreach}
{/foreach}
</select>
<select name="filter_country"><option>{#country#}</option><option>-------</option>
{foreach from=$countries item=country}
	<option value="{$country.country_id}" {if $smarty.request.filter_country eq $country.country_id}selected style="background-color:#99CC00;"{/if}>{$country.title}</option>
{/foreach}
</select>
<select name="filter_city"><option value="0">{#city#}</option><option value="0">-------</option>
{foreach from=$cities item=city}
	<option value="{$city.city}" {if $smarty.request.filter_city eq $city.city}selected style="background-color:#99CC00;"{/if}>{$city.city} {city2country id=$city.city}</option>
{/foreach}
</select>
<select name="filter_location"><option>{#location_type#}</option><option>-------</option>
{foreach from=$locations item=location}
<option value="{$location.location_id}" {if $smarty.request.filter_location eq $location.location_id}selected style="background-color:#99CC00;"{/if}>{$location.title}</option>
{/foreach}
</select>
<select name="filter_state"><option>{#state#}</option><option>-------</option>
{foreach from=$states item=state}
<option value="{$state.state_id}" {if $smarty.request.filter_state eq $state.state_id}selected style="background-color:#99CC00;"{/if}>{$state.title}</option>
{/foreach}
</select>
<input type="submit" name="filter" value="{#filter#}" />&nbsp;&nbsp;&nbsp;<input type="reset" value="Reset" />
</form>
</fieldset>
{if count($listings)}
<table cellpadding="0" cellspacing="0" border="0" id="table" class="sortable" width="930">
<caption>{#listings#}</caption>
<thead>
<tr><th class="desc">{#id#}</th><th>{#title#}</th><th>{#category#}</th><th>{#price#}</th><th>{#country#}</th><th>{#location_type#}</th><th>{#date_added#}</th><th>{#added_by#}</th><th class="nosort">{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$listings item=listing name=count_items}
<tr class="{cycle values="oddrow,none"}">
<td>{$listing.listing_id}</td>
<td><span onMouseOver="showhint('{* START OF OFFER INFO BALOON *}<i><b>{$listing.title}</b></i><img src={$BASE_URL}/uploads/thumbs/{$listing.icon} border=0 height=50 width=50 style=float:left; /><br/>{$listing.short_description}<br/>{if $listing.special}<img src={$BASE_URL}/images/hot.gif border=0 />{/if}<br/>{#listing_views#}: {$listing.views} {#times#}{* END OF OFFER INFO BALOON *}', this, event, '150px')">{$listing.title|stripslashes}</span></td>
<td>{category2name id=$listing.cat_id lang=$language}</td>
{if $listing.price > '0'}
<td onMouseOver="showhint('Price type: <b>{$listing.price_set}</b><br/>{if $listing.price_set eq "package"}Period:{elseif $listing.price_desc}Description:{/if} <b>{$listing.price_desc}</b>{if $listing.price_set eq "package"}<br/>Valid Until: <b>{$listing.pack_price_until|date_format:"%b %d, %Y"}</b>{/if}', this, event, '150px')"><a class="info_link" href="packages.php?listing_id={$listing.listing_id}">{$listing.price} {$listing.currency}</a></td>
{else}
<td onMouseOver="showhint('Click to set new price or add package(s)', this, event, '150px')"><a class="info_link" href="packages.php?listing_id={$listing.listing_id}">Not Set</a></td>
{/if}
<td>{country2name id=$listing.country_id lang=$language}</td>
<td>{location2name id=$listing.location_id lang=$language}</td>
<td>{$listing.added_date|date_format:"%b %d, %Y"}</td>
<td>{admin2name id=$listing.added_by}</td>
<td><a href="{$BASE_URL}/preview/{$listing.uri}" target="_blank" onMouseOver="showhint('{#preview_listing#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/preview.gif" border="0" /></a> | <a href="images.php?id={$listing.listing_id}" onMouseOver="showhint('{#show_images#} ({count_images listing_id=$listing.listing_id})', this, event, '150px')"><img src="{$BASE_URL}/admin/images/manage_images.gif" border="0" /></a> | <a href="videos.php?id={$listing.listing_id}" onMouseOver="showhint('{#manage_videos#} ({count_videos listing_id=$listing.listing_id})', this, event, '150px')"><img src="{$BASE_URL}/admin/images/manage_videos.png" border="0" /></a> | <a href="listings.php?edit={$listing.listing_id}&edit_lang={$language}" onMouseOver="showhint('{#edit_listing#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('listings.php?delete={$listing.listing_id}')" title="{#delete_listing#}" onMouseOver="showhint('{#delete_listing#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_listing#}" border="0"></a></td></tr>
{/foreach}
</tbody></table>
{include file="admin/sortable.tpl"}
{else}
{#no_listings#}
{/if}
{include file="admin/footer.tpl"}