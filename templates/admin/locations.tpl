{include file="admin/header.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("title");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#title#}");
</script>
		<div class="left">
			<h3>{#locations#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<label>{#title#}:</label><input type="text" name="title" size="50" /> <input type="submit" name="add_location" value="{#add_new_location#}" />
</form>
{if count($locations)}
<table border="0" class="sortable">
<caption>{#locations#}</caption>
<thead>
<tr><th>{#id#}</th><th>{#title#}</th><th>{#position#}</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$locations item=location}
<tr class="{cycle values="oddrow,none"}"><td>{$location.location_id}</td><td><b>{$location.title}</b></td>
<td>
{if $location.position != $min_position}
	<a href="locations.php?move_up={$location.location_id}" title="{#move_up#}"><img src="images/up_arrow.gif" border=0 alt="{#move_up#}" align="left" /></a>
{/if}
{if $location.position != $max_position}
	<a href="locations.php?move_down={$location.location_id}" title="{#move_down#}"><img src="images/down_arrow.gif" border=0 alt="{#move_down#}" align="right" /></a>
{/if}
</td>
<td><a href="locations.php?edit={$location.location_id}&edit_lang={$language}" onMouseOver="showhint('{#edit_location#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('locations.php?delete={$location.location_id}')" onMouseOver="showhint('{#delete_location#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_location#}" border="0"></a></td></tr>
{/foreach}
</tbody></table>
{else}
<br/>{#no_locations#}
{/if}
{include file="admin/footer.tpl"}