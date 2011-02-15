{include file="admin/header.tpl"}
		<div class="left">
			<h3>Packages</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
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
<br/>
<div style="float:left">
<div class="listing_price_type">
<label onClick="window.location='packages.php?listing_id={$listing.listing_id}&price_set=static'" onMouseOver="showhint('When user is booking, he will pay the static price set to this listing for each person, and days of duration will NOT be used for any calculations', this, event, '300px')"><input type="radio" name="use_price" value="static" {if $listing.price_set eq "static"}checked="checked"{/if}>Static Price</label>
<label onClick="window.location='packages.php?listing_id={$listing.listing_id}&price_set=package'" onMouseOver="showhint('When user is booking, the price will be calculated from the package for the selected period if any. <br/><b>For example</b>: 10EUR / per day is selected for dates between 10Jun-10Jul. User selected Duration is 10 days. User have to pay 100EUR total. If 2 persons - 200EUR.', this, event, '350px')"><input type="radio" name="use_price" value="package" {if $listing.price_set eq "package"}checked="checked"{/if}>Package Price</label>
</div><br/>
{if $listing.price_set eq "static"}
<fieldset style="width:270px;"><legend>Static {#price#}</legend>
<form method="post" action="">
<input type="text" name="price" style="text-align:center" value="{$listing.price}" size="8" onMouseOver="showhint('{#hint_listing_price#}', this, event, '150px')" />
<select name="currency">
{foreach from=$currencies item=currency}
<option value="{$currency.code}" {if $listing.currency == $currency.code}selected{/if}>{$currency.code}</option>
{/foreach}
</select>
<input type="submit" name="static_price" value="Update Price">
</form></fieldset><br/>
{/if}
{if $listing.price_set eq "package"}
<fieldset style="width:470px;"><legend>Package {#price#}</legend>
<p align="center">
{if $today_package.base_price}
<b>Package Base Price: <font color="green">{$today_package.base_price} {$listing.currency}</font>
{#per#} {if $today_package.price_period == "1"}{#day#}{/if}
	{if $today_package.price_period == "7"}{#week#}{/if}
	{if $today_package.price_period == "30"}{#month#}{/if}
	{if $today_package.price_period == "365"}{#year#}{/if}
</b><br/>
Package ID: <a href="packages.php?edit={$today_package.pack_id}">{$today_package.pack_id}</a>
{else}
No price set for today.
{/if}
</p><br/>
			<input class="submit" name="Button" type="button" onClick="window.location='packages.php?add&listing_id={$listing.listing_id}'" value="Add New Package" /><br/>

{if count($packages)}
<table border="0" class="sortable">
<caption>{#packages_prices#}</caption>
<thead>
<tr><th>{#id#}</th><th>{#from_date#}</th><th>{#to_date#}</th><th>{#price#}</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$packages item=pack}
<tr class="{cycle values="oddrow,none"}"><td>{$pack.pack_id}</td>
<td>{$pack.from_date|date_format:"%d/%b/%Y"}</td>
<td>{$pack.to_date|date_format:"%d/%b/%Y"}</td>
<td>{$pack.base_price} {$listing.currency} {#per#} 
{if $pack.price_period == "1"}{#day#}{/if}
{if $pack.price_period == "7"}{#week#}{/if}
{if $pack.price_period == "30"}{#month#}{/if}
{if $pack.price_period == "365"}{#year#}{/if}</td>
<td><a href="packages.php?edit={$pack.pack_id}"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('packages.php?delete={$pack.pack_id}&listing_id={$listing.listing_id}')"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_location#}" border="0"></a></td></tr>
{/foreach}
</tbody></table>
{else}
<br/>No Packages
{/if}
</fieldset>
{/if}
</div>
{include file="admin/listing_info_box.tpl"}
{include file="admin/footer.tpl"}