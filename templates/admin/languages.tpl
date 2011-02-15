{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#languages#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
			<input class="submit" name="Button" type="button" onClick="window.location='languages.php?add'" value="{#add_new_language#}" /><br/>
<table border="0" class="sortable">
<caption>{#languages#}</caption>
<thead>
<tr><th>{#id#}</th><th>{#title#}</th><th>{#language_encoding#}</th><th>{#active#}</th><th>{#default#}</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$languages item=lang}
<tr class="{cycle values="oddrow,none"}">
<td>{$lang.lang_name}&nbsp;&nbsp;<img src="{$BASE_URL}/uploads/flags/{$lang.lang_name}.gif" name="flag" border="0" /></td>
<td><b>{$lang.lang_title}</b></td>
<td>{$lang.encoding}</td>
<td>{if $lang.active eq "1"}
{if $lang.default eq "0"}
<a href="languages.php?deactivate={$lang.lang_name}">{#deactivate#}</a>
{else}
{#deactivate#}
{/if}
{else}
<a href="languages.php?activate={$lang.lang_name}">{#activate#}</a>
{/if}</td>
<td>{if $lang.default eq "1"}{#default#}{else}<a href="#" onClick="DeleteItem('languages.php?default={$lang.lang_name}')">{#make_default#}</a>{/if}</td><td><a href="languages.php?edit={$lang.lang_name}"><img src="{$BASE_URL}/admin/images/edit.png" border="0" onMouseOver="showhint('{#hint_edit_language#}', this, event, '150px')"/></a> | <a href="#" onClick="DeleteItem('languages.php?delete={$lang.lang_name}')" title="{#delete_language#}"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_language#}" border="0"></a></td></tr>
{/foreach}

</tbody></table>
{include file="admin/footer.tpl"}