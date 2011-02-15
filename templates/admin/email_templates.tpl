{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#menu_email_templates#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
<table border="0" class="sortable">
<caption>{#menu_email_templates#}</caption>
<thead>
<tr><th>name</th><th>description</th><th>last modified</th><th>edit</th></tr>
</thead>
<tbody>
{foreach from=$email_templates item=tpl}
<tr class="{cycle values="oddrow,none"}">
<td><b>{$tpl.tpl_name}</b></td>
<td>{$tpl.description|stripslashes}</td>
<td>{$tpl.last_update|date_format:"%d/%b/%Y %H:%M:%S"}</td>
<td align="center"><a href="email_templates.php?edit={$tpl.tpl_name}"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a></td></tr>
{/foreach}

</tbody></table>
{include file="admin/footer.tpl"}