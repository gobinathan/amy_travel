{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#menu_pages#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
			<input class="submit" name="Button" type="button" onClick="window.location='pages.php?add'" value="{#add_new_page#}" /><br/>
<div style="float: left; display:inline;padding-right: 60px;">
{if count($pages_up)}
<table border="0" class="sortable">
<caption>{#pages_menu_up#}</caption>
<thead><tr><th>{#id#}</th><th>{#title#}</th><th>{#position#}</th><th>{#action#}</th></tr></thead>
<tbody>
{foreach from=$pages_up item=page}
<tr class="{cycle values="oddrow,none"}">
<td><b>{$page.page_id}</b></td>
<td>{$page.title}</td>
<td>
{if $page.position != $min_position_up}
	<a href="pages.php?move_up={$page.page_id}" title="{#move_up#}"><img src="images/up_arrow.gif" border=0 alt="{#move_up#}" align="left" /></a>
{/if}
{if $page.position != $max_position_up}
	<a href="pages.php?move_down={$page.page_id}" title="{#move_down#}"><img src="images/down_arrow.gif" border=0 alt="{#move_down#}" align="right" /></a>
{/if}
</td>
<td><a href="pages.php?edit={$page.page_id}&edit_lang={$language}"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('pages.php?delete={$page.page_id}')"><img src="{$BASE_URL}/admin/images/delete.png" border="0" /></a></td></tr>
{/foreach}
</tbody></table>
{else}
{#no_pages_up#}
{/if}
</div>


<div style="float: left; display:inline;">
{if count($pages_down)}
<table border="0" class="sortable">
<caption>{#pages_menu_down#}</caption>
<thead><tr><th>{#id#}</th><th>{#title#}</th><th>{#position#}</th><th>{#action#}</th></tr></thead>
<tbody>
{foreach from=$pages_down item=page}
<tr class="{cycle values="oddrow,none"}">
<td><b>{$page.page_id}</b></td>
<td>{$page.title}</td>
<td>
{if $page.position != $min_position_down}
	<a href="pages.php?move_up={$page.page_id}" title="{#move_up#}"><img src="images/up_arrow.gif" border=0 alt="{#move_up#}" align="left" /></a>
{/if}
{if $page.position != $max_position_down}
	<a href="pages.php?move_down={$page.page_id}" title="{#move_down#}"><img src="images/down_arrow.gif" border=0 alt="{#move_down#}" align="right" /></a>
{/if}
</td>
<td><a href="pages.php?edit={$page.page_id}&edit_lang={$language}"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('pages.php?delete={$page.page_id}')"><img src="{$BASE_URL}/admin/images/delete.png" border="0" /></a></td></tr>
{/foreach}
</tbody></table>
{else}
{#no_pages_down#}
{/if}
</div>
{include file="admin/footer.tpl"}