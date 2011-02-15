{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#listings#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
			<input class="submit" name="Button" type="button" onClick="window.location='listings.php?add'" value="{#add_listing#}" />&nbsp;&nbsp;&nbsp;
			<input class="submit" name="Button" type="button" onClick="window.location='listings.php'" value="{#show_all_listings#}" /><br/>			
			<br/>			
{if count($listings)}
<table cellpadding="0" cellspacing="0" border="0" id="table" class="sortable" width="930">
<caption>{#expired_listings#}</caption>
<thead>
<tr><th class="desc">{#id#}</th><th>{#title#}</th><th>{#category#}</th><th>{#start_date#}</th><th>{#end_date#}</th><th>{#date_added#}</th><th>{#added_by#}</th><th class="nosort">{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$listings item=listing name=count_items}
<tr class="{cycle values="oddrow,none"}">
<td>{$listing.listing_id}</td>
<td><span onMouseOver="showhint('{* START OF OFFER INFO BALOON *}<i><b>{$listing.title|stripslashes}</b></i><img src={$BASE_URL}/uploads/thumbs/{$listing.icon} border=0 height=50 width=50 style=float:left; /><br/>{$listing.short_description|stripslashes}<br/>{if $listing.special}<img src={$BASE_URL}/images/hot.gif border=0 />{/if}<br/>{#listing_views#}: {$listing.views} {#times#}{* END OF OFFER INFO BALOON *}', this, event, '150px')">{$listing.title|stripslashes}</span></td>
<td>{category2name id=$listing.cat_id lang=$language}</td>
<td>{if $listing.start_date !== "0"}{$listing.start_date|date_format:"%b %d, %Y"}{/if}</td>
<td>{if $listing.end_date !== "0"}{$listing.end_date|date_format:"%b %d, %Y"}{/if}</td>
<td>{$listing.added_date|date_format:"%b %d, %Y"}</td>
<td>
{if $listing.member_id == "0"}
{admin2name id=$listing.added_by}
{else}
<a href="members.php?all_listings={$listing.member_id}">{member2name id=$listing.member_id}</a>
{/if}
</td>
<td><a href="{$BASE_URL}/preview/{$listing.uri}" target="_blank" onMouseOver="showhint('{#preview_listing#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/preview.gif" border="0" /></a> | <a href="images.php?id={$listing.listing_id}" onMouseOver="showhint('{#show_images#} ({count_images listing_id=$listing.listing_id})', this, event, '150px')"><img src="{$BASE_URL}/admin/images/manage_images.gif" border="0" /></a> | <a href="videos.php?id={$listing.listing_id}" onMouseOver="showhint('{#manage_videos#} ({count_videos listing_id=$listing.listing_id})', this, event, '150px')"><img src="{$BASE_URL}/admin/images/manage_videos.png" border="0" /></a> | <a href="listings.php?edit={$listing.listing_id}&edit_lang={$language}" onMouseOver="showhint('{#edit_listing#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('listings.php?delete={$listing.listing_id}')" title="{#delete_listing#}" onMouseOver="showhint('{#delete_listing#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_listing#}" border="0"></a></td></tr>
{/foreach}
</tbody></table>
{include file="admin/sortable.tpl"}
{else}
{#no_expired_listings#}
{/if}
{include file="admin/footer.tpl"}