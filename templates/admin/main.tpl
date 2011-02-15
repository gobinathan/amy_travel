{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#admin_panel#}</h3>
{#last_login#}: <b>{$last_login}</b><br/><br/>
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<br/>
<fieldset style="border:1px solid #b3b3b3;padding: 5px 5px 5px 5px;"><legend>Fast actions</legend>
<input class="submit" name="Button" type="button" onClick="window.location='listings.php?add'" value="{#add_listing#}" />&nbsp;
<input class="submit" name="Button" type="button" onClick="window.location='categories.php?add'" value="{#add_new_category#}" />&nbsp;
<input class="submit" name="Button" type="button" onClick="window.location='sitemap_generator.php'" value="Generate Sitemap" />&nbsp;
<input class="submit" name="Button" type="button" onClick="window.location='settings.php'" value="{#menu_site_settings#}" />&nbsp;
<input class="submit" name="Button" type="button" onClick="window.location='news.php?add'" value="{#add_news#}" />&nbsp;
</fieldset>
<br/><br/>
			<div class="left_box">
{if count($orders)}
<table border="0" class="sortable">
<caption>{#orders#}</caption>
<thead>
<tr><th>{#order_id#}</th><th>{#price#}</th><th>{#member#}</th><th>{#credit_plan#}</th><th>{#order_type#}</th><th>{#date_added#}</th><th>Status</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$orders item=order}
<tr class="{cycle values="oddrow,none"}">
<td>{$order.order_id}</td>
<td>{if $order.price != "0"}{$order.price} {$order.currency}{else}{#free#}{/if}</td>
<td onMouseOver="showhint('{#email#}:{$order.member.email}<br/>{#fullname#}:{$order.member.fullname}<br/>{#last_login#}:{$order.member.last_login}<br/>{if $order.member.email_confirmed}E-Mail is confirmed{else}E-Mail NOT confirmed{/if}<br/>{if $order.member.approved}Account is approved{else}Account is NOT approved{/if}', this, event, '250px')"><a href="members.php?edit={$order.member_id}">{$order.member.username}</a></td>
<td onMouseOver="showhint('<b>{#access_rights#}</b><br/>{if $order.member.access_require_approval}{#access_require_approval#}<br/>{/if}{if $order.member.access_limit_listings}{#access_limit_listings#}: {$order.member.access_listings_count}<br/>{/if}{if $order.member.access_editor}{#access_editor#}<br/>{/if}{if $order.member.access_limit_images}{#access_limit_images#}: {$order.member.access_images_count}<br/>{/if}{if $order.member.access_limit_video}{#access_limit_video#}{if $order.member.access_video_count}: {$order.member.access_video_count}{/if}<br/>{if $order.member.access_video_size}{#access_video_size#}: {$order.member.access_video_size}<br/>{/if}{/if}{if $order.member.access_special}{#access_special_listings#}: {$order.member.access_special_count}<br/>{/if}{if $order.member.access_gmap}{#access_gmap#}<br/>{/if}{if $order.member.access_period}{#access_period#}<br/>{/if}{if $order.member.access_contacts}{#access_contacts#}<br/>{/if}{if $order.member.access_langs}{#access_langs#}: {$order.member.access_langs}<br/>{/if}{if $order.member.access_sublistings}{#access_sublistings#}<br/>{/if}{if $order.member.access_sitemap}{#access_sitemap#}<br/>{/if}', this, event, '150px')">{$order.plan.title|stripslashes}</td>
<td>{$order.payment_gw}</td>
<td>{$order.date_added|date_format:"%d/%b/%Y %H:%M:%S"}</td>
<td>{$order.status}</td>
<td>{if $order.approved eq "1"}<a href="orders.php?unapprove={$order.order_id}">{#unapprove#}</a>{else}<a href="orders.php?approve={$order.order_id}">{#approve#}</a>{/if} | {if $order.confirmed eq "1"}<a href="orders.php?unconfirm={$order.order_id}">{#unconfirm#}</a>{else}<a href="orders.php?confirm={$order.order_id}">{#confirm#}</a>{/if} | <a href="#" onClick="DeleteItem('orders.php?delete={$order.order_id}')" title="{#delete_order#}">{#delete#}</a></td></tr>
{/foreach}
</tbody></table>
<br/>
{/if}
{if count($nonapproved_members)}
<table border="0" width="700" class="sortable">
<caption>{#members_waiting_approval#}</caption>
<thead>
<tr><th>{#approve#}</th><th>{#username#}</th><th>{#email#}</th><th>{#fullname#}</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$nonapproved_members item=member}
<tr class="{cycle values="oddrow,none"}">
<td><a href="members.php?member_approve={$member.member_id}">{#activate#}</a></td>
<td><b>{$member.username}</b></td>
<td>{$member.email}</td>
<td>{$member.fullname}</td>
<td><a href="members.php?edit={$member.member_id}"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('members.php?delete={$member.member_id}')" title="{#delete_member#}"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_member#}" border="0"></a></td></tr>
{/foreach}
</tbody></table>
{/if}
<br/>
{if count($nonapproved_member_listings)}
<table border="0" width="700" class="sortable">
<caption>{#member_listings_waiting#}</caption>
<thead>
<tr><th>{#approve#}</th><th>{#title#}</th><th>{#added_by#}</th><th>{#category#}</th><th>{#date_added#}</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$nonapproved_member_listings item=listing}
<tr class="{cycle values="oddrow,none"}">
<td><a href="members.php?listing_approve={$listing.listing_id}">{#activate#}</a></td>
<td><span onMouseOver="showhint('{include file="admin/listing_tip.tpl"}', this, event, '150px')">{$listing.title|stripslashes}</span></td>
<td><a href="members.php?waiting={$listing.member.member_id}">{$listing.member.username}</a></td>
<td>{category2name id=$listing.cat_id lang=$language}</td>
<td>{$listing.added_date|date_format:"%d/%b/%Y %H:%M:%S"}</td>
<td><a href="{$BASE_URL}/preview/{$listing.uri}" target="_blank" onMouseOver="showhint('{#preview_listing#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/preview.gif" border="0" /></a> | <a href="images.php?id={$listing.listing_id}" onMouseOver="showhint('{#show_images#} ({count_images listing_id=$listing.listing_id})', this, event, '150px')"><img src="{$BASE_URL}/admin/images/manage_images.gif" border="0" /></a> | <a href="videos.php?id={$listing.listing_id}" onMouseOver="showhint('{#manage_videos#} ({count_videos listing_id=$listing.listing_id})', this, event, '150px')"><img src="{$BASE_URL}/admin/images/manage_videos.png" border="0" /></a> | <a href="listings.php?edit={$listing.listing_id}&edit_lang={$language}" onMouseOver="showhint('{#edit_listing#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('listings.php?delete={$listing.listing_id}')" title="{#delete_listing#}" onMouseOver="showhint('{#delete_listing#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_listing#}" border="0"></a></td></tr>
{/foreach}
</tbody></table>
{/if}
<br/>
{if count($listings_waiting_for_deletion)}
<table border="0" width="700" class="sortable">
<caption>{#member_listings_deletion#}</caption>
<thead>
<tr><th>{#delete#}</th><th>{#title#}</th><th>{#added_by#}</th><th>{#category#}</th><th>{#date_added#}</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$listings_waiting_for_deletion item=listing}
<tr class="{cycle values="oddrow,none"}">
<td onMouseOver="showhint('{#delete_listing#}', this, event, '150px')"><a href="#" onClick="DeleteItem('members.php?listing_delete={$listing.listing_id}')">{#delete#}</a></td>
<td><span onMouseOver="showhint('{include file="admin/listing_tip.tpl"}', this, event, '150px')">{$listing.title|stripslashes}</span></td>
<td><a href="members.php?delete_requests={$listing.member.member_id}">{$listing.member.username}</a></td>
<td>{category2name id=$listing.cat_id lang=$language}</td>
<td>{$listing.added_date|date_format:"%d/%b/%Y %H:%M:%S"}</td>
<td><a href="{$BASE_URL}/preview/{$listing.uri}" target="_blank" onMouseOver="showhint('{#preview_listing#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/preview.gif" border="0" /></a> | <a href="images.php?id={$listing.listing_id}" onMouseOver="showhint('{#show_images#} ({count_images listing_id=$listing.listing_id})', this, event, '150px')"><img src="{$BASE_URL}/admin/images/manage_images.gif" border="0" /></a> | <a href="videos.php?id={$listing.listing_id}" onMouseOver="showhint('{#manage_videos#} ({count_videos listing_id=$listing.listing_id})', this, event, '150px')"><img src="{$BASE_URL}/admin/images/manage_videos.png" border="0" /></a> | <a href="listings.php?edit={$listing.listing_id}&edit_lang={$language}" onMouseOver="showhint('{#edit_listing#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a></td></tr>
{/foreach}
</tbody></table>
{/if}
<br/><br/>
{if count($listings)}
<table border="0" width="700" class="sortable">
<caption>{#last_10_listings#}</caption>
<thead>
<tr><th>{#title#}</th><th>{#category#}</th><th>{#date_added#}</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$listings item=listing}
<tr class="{cycle values="oddrow,none"}">
<td><span onMouseOver="showhint('{include file="admin/listing_tip.tpl"}', this, event, '150px')">{$listing.title|stripslashes}</span></td>
<td>{category2name id=$listing.cat_id lang=$language}</td>
<td>{$listing.added_date|date_format:"%d/%b/%Y %H:%M:%S"}</td>
<td>{if !$listing.sublisting}<a href="listings.php?add&sublisting={$listing.listing_id}" onMouseOver="showhint('{#add_sublisting#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/add.gif" border="0" alt="{#add_sublisting#}" /></a> | {/if}<a href="{$BASE_URL}/preview/{$listing.uri}" target="_blank" onMouseOver="showhint('{#preview_listing#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/preview.gif" border="0" /></a> | <a href="images.php?id={$listing.listing_id}" onMouseOver="showhint('{#show_images#} ({count_images listing_id=$listing.listing_id})', this, event, '150px')"><img src="{$BASE_URL}/admin/images/manage_images.gif" border="0" /></a> | <a href="videos.php?id={$listing.listing_id}" onMouseOver="showhint('{#manage_videos#} ({count_videos listing_id=$listing.listing_id})', this, event, '150px')"><img src="{$BASE_URL}/admin/images/manage_videos.png" border="0" /></a> | <a href="listings.php?edit={$listing.listing_id}&edit_lang={$language}" onMouseOver="showhint('{#edit_listing#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('listings.php?delete={$listing.listing_id}')" title="{#delete_listing#}" onMouseOver="showhint('{#delete_listing#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_listing#}" border="0"></a></td></tr>
{/foreach}
</tbody></table>
{/if}
{include file="admin/footer.tpl"}