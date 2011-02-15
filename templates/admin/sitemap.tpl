{include file="admin/header.tpl"}
<div class="left">
			<h3>Google Sitemap Generator</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
	<form method="post" action="{$smarty.server.PHP_SELF|xss}" onSubmit="disableForm(this,'{#updating#}')">
	<input type="hidden" name="save_sitemap" value="true"/>
	<table width="700" class="sortable" border="0">
	<caption>Sitemap</caption>
	<thead><tr><th>Location</th><th>Priority</th></tr></thead>
	<tr><td></td><td><input type="submit" value="{#save_changes#}" /></td></tr>
	<tbody>
	{foreach from=$links item=link}
	<tr class="{cycle values="oddrow,none"}"><td><b>{$link.url}</b></td><td><input type="text" name="links[{$link.url}]" value="{$link.priority}" size="2" class="text" /></td></tr>
	{/foreach}
	</tbody>
	<tr><td></td><td><input type="submit" value="{#save_changes#}" /></form></td></tr>
	</table>
	<hr/>
</div>
{include file="admin/footer.tpl"}