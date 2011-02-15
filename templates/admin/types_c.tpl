{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#type_fields#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
			<input class="submit" name="Button" type="button" onClick="window.location='types_c.php?add'" value="{#add_new_field#}" /><br/>
{if count($types_c)}
<table border="0" class="sortable">
<caption>{#type_fields#}</caption>
<thead>
<tr><th>{#id#}</th><th>{#title#}</th><th>{#features#}</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$types_c item=type_c}
<tr class="{cycle values="oddrow,none"}"><td>{$type_c.type_c_id}</td><td><b>{$type_c.title}</b></td><td align="center"><a href="types.php?manage={$type_c.type_c_id}" title="{#edit_features#}">{count_features type_c_id=$type_c.type_c_id}</td><td><a href="types_c.php?edit={$type_c.type_c_id}&edit_lang={$language}"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('types_c.php?delete={$type_c.type_c_id}')" title="{#delete_type#}"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_type#}" border="0"></a></td></tr>
{/foreach}
</tbody></table>
{else}
{#no_types_c#}
{/if}
{include file="admin/footer.tpl"}