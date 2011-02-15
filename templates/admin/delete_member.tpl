{include file="admin/header.tpl"}
{include file="admin/html_editor.tpl"}
<div class="left">
			<h3>{$title}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<h1>{#member_delete_confirm_msg#} <b>{$member.username}</b>?</h1><br/>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" ENCTYPE="multipart/form-data">
<input type="hidden" name="delete_member" value="{$member.member_id}" /><input type="submit" name="delete" value="{#delete_member#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='members.php'" value="{#back_members#}" /><br/><br/>
</form>
<h2>{#member_delete_confirm_msg_info#}</h2>
<br/>
{#email#}:{$member.email}<br/>
{#fullname#}:{$member.fullname}<br/>
{#last_login#}:{$member.last_login}<br/>
{if $member.email_confirmed}E-Mail is confirmed{else}E-Mail NOT confirmed{/if}<br/>
{if $member.approved}Account is approved{else}Account is NOT approved{/if}<br/>
<br/><br/>
{if count($listings)>0}
<form method=post action="{$smarty.server.PHP_SELF|xss}" enctype="multipart/form-data">
<fieldset><legend>{#listings#}</legend>
<table border="0" class="sortable">
<caption>{#listings#}</caption>
<thead>
<tr><th>{#title#}</th><th>{#action#}</th><th>{#move#}</th></tr>
</thead>
<tbody>
{foreach from=$listings item=listing name=move_listings}
<tr class="{cycle values="oddrow,none"}">
<td><span onMouseOver="showhint('<i><b>{$listing.title|stripslashes}</b></i><img src={$BASE_URL}/uploads/thumbs/{$listing.icon} border=0 height=50 width=50 style=float:left; /><br/>{$listing.short_description|stripslashes}<br/><br/>{#price#}: {$listing.price}<br/>{#country#}: {country2name id=$listing.country_id lang=$language}<br/>{#city#}: {$listing.city}<br/><br/>{#category#}: {category2name id=$listing.cat_id lang=$language}<br/>', this, event, '150px')">{$listing.title|stripslashes}</span></td>
<td><a href="{$BASE_URL}/preview/{$listing.uri}" target="_blank" onMouseOver="showhint('{#preview_listing#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/preview.gif" border="0" /></a> | <a href="images.php?id={$listing.listing_id}" onMouseOver="showhint('{#show_images#}', this, event, '150px')" target="_blank"><img src="{$BASE_URL}/admin/images/manage_images.gif" border="0" /></a> | <a href="listings.php?edit={$listing.listing_id}&edit_lang={$language}" onMouseOver="showhint('{#edit_listing#}', this, event, '150px')" target="_blank"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a></td><td><input type="checkbox" name="listing[{$smarty.foreach.move_listings.iteration}]" value="{$listing.listing_id}"/></td></tr>
{/foreach}
</tbody></table>
<input type="hidden" name="current_member" value="{$member.member_id}" />
<input type="hidden" name="move_listings" value="{$smarty.foreach.move_listings.total}" />
<input type="submit" name="move_selected" value="{#assign_selected_listings_to#}" /><select name="member_id">
<option value="0">------------------</option>
{foreach from=$members item=mmember}
<option value="{$mmember.member_id}">{$mmember.username}</option>
{/foreach}
</select>
</form>
</fieldset>
{/if}
<br/><br/>
{if count($orders)}
<table border="0" class="sortable">
<caption>{#orders#}</caption>
<thead>
<tr><th>{#order_id#}</th><th>{#price#}</th><th>{#credit_plan#}</th><th>{#order_type#}</th><th>{#date_added#}</th><th>Status</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$orders item=order}
<tr class="{cycle values="oddrow,none"}">
<td>{$order.order_id}</td>
<td>{if $order.price != "0"}{$order.price} {$order.currency}{else}{#free#}{/if}</td>
<td onMouseOver="showhint('<b>{#access_rights#}</b><br/>{if $member.access_require_approval}{#access_require_approval#}<br/>{/if}{if $member.access_limit_listings}{#access_limit_listings#}: {$member.access_listings_count}<br/>{/if}{if $member.access_editor}{#access_editor#}<br/>{/if}{if $member.access_limit_images}{#access_limit_images#}: {$member.access_images_count}<br/>{/if}{if $member.access_special}{#access_special_listings#}: {$member.access_special_count}<br/>{/if}{if $member.access_gmap}{#access_gmap#}<br/>{/if}{if $member.access_period}{#access_period#}<br/>{/if}{if $member.access_contacts}{#access_contacts#}<br/>{/if}{if $member.access_langs}{#access_langs#}: {$member.access_langs}<br/>{/if}', this, event, '150px')">{$order.plan.title|stripslashes}</td>
<td>{$order.payment_gw}</td>
<td>{$order.date_added|date_format:"%d/%b/%Y %H:%M:%S"}</td>
<td>{$order.status}</td>
<td>{if $order.approved eq "1"}<a href="orders.php?unapprove={$order.order_id}">{#unapprove#}</a>{else}<a href="orders.php?approve={$order.order_id}">{#approve#}</a>{/if} | {if $order.confirmed eq "1"}<a href="orders.php?unconfirm={$order.order_id}">{#unconfirm#}</a>{else}<a href="orders.php?confirm={$order.order_id}">{#confirm#}</a>{/if} | <a href="#" onClick="DeleteItem('orders.php?delete={$order.order_id}')" title="{#delete_order#}">{#delete#}</a></td></tr>
{/foreach}
</tbody></table>
{else}
{#no_orders#}
{/if}

<br/><br/><br/>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" ENCTYPE="multipart/form-data">
<input type="hidden" name="delete_member" value="{$member.member_id}" /><input type="submit" name="delete" value="{#delete_member#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='members.php'" value="{#back_members#}" /><br/><br/>
</form>
</div>
{include file="admin/footer.tpl"}