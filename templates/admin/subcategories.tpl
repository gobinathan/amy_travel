{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#subcategories#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
			<input class="submit" name="Button" type="button" onClick="window.location='categories.php?add&parent={$smarty.get.browse}'" value="{#add_new_subcategory#}" />
			&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='categories.php'" value="{#back_categories#}" /><br/>
<table border="0" class="sortable">
<caption><b>{$category_title}</b> {#subcategories#}</caption>
<thead><tr><th>{#id#}</th><th>{#title#}</th><th>{#uri#}</th><th>{#listings#}</th><th>{#position#}</th><th>{#action#}</th></tr></thead>
<tbody>
{foreach from=$subcategories item=category}
<tr class="{cycle values="oddrow,none"}">
<td><b>{$category.cat_id}</b></td>
<td>{$category.title}</td>
<td>{$category.uri}</td>
<td align="center">{count_listings cat_id=$category.cat_id}</td>
<td>
{if $category.position != $min_position}
	<a href="categories.php?move_up={$category.cat_id}&parent={$category.parent}" onMouseOver="showhint('{#move_up#}', this, event, '150px')"><img src="images/up_arrow.gif" border=0 alt="{#move_up#}" align="left" /></a>
{/if}
{if $category.position != $max_position}
	<a href="categories.php?move_down={$category.cat_id}&parent={$category.parent}" onMouseOver="showhint('{#move_down#}', this, event, '150px')"><img src="images/down_arrow.gif" border=0 alt="{#move_down#}" align="right" /></a>
{/if}
</td>
<td><a href="categories.php?edit={$category.cat_id}" onMouseOver="showhint('{#edit_category#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('categories.php?delete={$category.cat_id}')" onMouseOver="showhint('{#delete_category#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/delete.png" border="0" /></a></td></tr>
{/foreach}
</tbody></table>
</div>
</div>
{include file="admin/footer.tpl"}