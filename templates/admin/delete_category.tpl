{include file="admin/header.tpl"}
{include file="admin/html_editor.tpl"}
<div class="left">
			<h3>{$title}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<h1>{#category_delete_confirm_msg#} <b>{$category.title|stripslashes}</b>?</h1><br/>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" ENCTYPE="multipart/form-data">
<input type="hidden" name="delete_category" value="{$category.cat_id}" /><input type="submit" name="delete" value="{#delete_category#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='categories.php'" value="{#back_categories#}" /><br/><br/>
</form>
<h2>{#category_delete_confirm_msg_info#}</h2>
{if count_subcats($category.cat_id)>0}
<form method=post action="{$smarty.server.PHP_SELF|xss}" enctype="multipart/form-data">
<fieldset><legend>{#subcategories_in_cat#} <b>{$category.title}</b> ({count_subcats cat_id=$category.cat_id})</legend>
<table border="0" class="sortable">
<caption>{#categories#}</caption>
<thead>
<tr><th>{#title#}</th><th>{#action#}</th><th>{#move#}</th></tr>
</thead>
<tbody>
{foreach from=$subcategories item=subcategory name=move_categories}
<tr class="{cycle values="oddrow,none"}">
<td><span onMouseOver="showhint('{#hint_listings_in_this_cat#}: {count_listings cat_id=$subcategory.cat_id}', this, event, '150px')">{$subcategory.title|stripslashes}</span></td><td><a href="categories.php?edit={$category.cat_id}" onMouseOver="showhint('{#edit_category#}', this, event, '150px')" target="_blank"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a></td><td><input type="checkbox" name="subcategory[{$smarty.foreach.move_categories.iteration}]" value="{$subcategory.cat_id}"/></td>
</tr>
{/foreach}
</tbody></table>
<input type="hidden" name="main_cat" value="{$category.cat_id}" />
<input type="hidden" name="move_categories" value="{$smarty.foreach.move_categories.total}" />
<input type="submit" name="move_selected" value="{#move_selected_categories_to#}" /><select name="cat_id" onMouseOver="showhint('{#hint_listing_category#}', this, event, '150px')">
<option value="0">------------------</option>
{foreach from=$categories item=mcategory}
<option value="{$mcategory.cat_id}">{$mcategory.title}</option>
	{foreach from=$mcategory.subcats item=subcat}
	<option value="{$subcat.cat_id}">{$mcategory.title} -> {$subcat.title}</option>
	{/foreach}
{/foreach}
</select>
</form>
</fieldset>
{/if}
{if count_listings($category.cat_id)>0}
<form method=post action="{$smarty.server.PHP_SELF|xss}" enctype="multipart/form-data">
<fieldset><legend>{#listings_in_cat#} <b>{$category.title}</b> ({count_listings cat_id=$category.cat_id})</legend>
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
<input type="hidden" name="main_cat" value="{$category.cat_id}" />
<input type="hidden" name="move_listings" value="{$smarty.foreach.move_listings.total}" />
<input type="submit" name="move_selected" value="{#move_selected_listings_to#}" /><select name="cat_id" onMouseOver="showhint('{#hint_listing_category#}', this, event, '150px')">
<option value="0">------------------</option>
{foreach from=$categories item=mcategory}
<option value="{$mcategory.cat_id}">{$mcategory.title}</option>
	{foreach from=$mcategory.subcats item=subcat}
	<option value="{$subcat.cat_id}">{$mcategory.title} -> {$subcat.title}</option>
	{/foreach}
{/foreach}
</select>
</form>
</fieldset>
{/if}
<br/><br/><br/>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" ENCTYPE="multipart/form-data">
<input type="hidden" name="delete_category" value="{$category.cat_id}" /><input type="submit" name="delete" value="{#delete_category#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='categories.php'" value="{#back_categories#}" /><br/><br/>
</form>
</div>
{include file="admin/footer.tpl"}