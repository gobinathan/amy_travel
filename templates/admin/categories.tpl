{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#categories#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
			<input class="submit" name="Button" type="button" onClick="window.location='categories.php?add'" value="{#add_new_category#}" /><br/>
{if count($categories)}
<table border="0" class="sortable">
<caption>{#categories#}</caption>
<thead><tr><th>{#id#}</th><th>{#title#}</th><th>{#subcategories#}</th><th>{#listings#}</th><th>{#position#}</th><th>{#action#}</th></tr></thead>
<tbody>
{foreach from=$categories item=category}
<tr class="{cycle values="oddrow,none"}">
<td><b>{$category.cat_id}</b></td>
<td>{$category.title}</td>
<td align="center">
{if count_subcats($category.cat_id) > 0}
	<a href="categories.php?browse={$category.cat_id}" title="{#browse_subcategories#} {$category.title}"><b>{count_subcats cat_id=$category.cat_id}</b></a>
{else}
{count_subcats cat_id=$category.cat_id} 
{/if}
</td>
<td align="center" title="Show Listings in this category"><a href="listings.php?filter=true&filter_category={$category.cat_id}" title="Show Listings in this category"><b>{count_listings cat_id=$category.cat_id}</b></a></td>
<td>
{if $category.position != $min_position}
	<a href="categories.php?move_up={$category.cat_id}&parent=0" title="{#move_up#}"><img src="images/up_arrow.gif" border=0 alt="{#move_up#}" align="left" /></a>
{/if}
{if $category.position != $max_position}
	<a href="categories.php?move_down={$category.cat_id}&parent=0" title="{#move_down#}"><img src="images/down_arrow.gif" border=0 alt="{#move_down#}" align="right" /></a>
{/if}
</td>
<td><a href="categories.php?add&parent={$category.cat_id}" onMouseOver="showhint('{#add_subcategory#} <b>{$category.title}</b>', this, event, '150px')"><img src="{$BASE_URL}/admin/images/add.gif" border="0" alt="{#add_subcategory#}" /></a> | <a href="categories.php?edit={$category.cat_id}&edit_lang={$language}" onMouseOver="showhint('{#edit_category#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('categories.php?delete={$category.cat_id}')"><img src="{$BASE_URL}/admin/images/delete.png" border="0" onMouseOver="showhint('{#delete_category#}', this, event, '150px')"/></a></td></tr>
{/foreach}
</tbody></table>
{else}
{#no_categories#}
{/if}
</div>
</div>
{include file="admin/footer.tpl"}