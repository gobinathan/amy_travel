{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#currencies#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
			<input class="submit" name="Button" type="button" onClick="window.location='currency.php?add'" value="{#add_currency#}" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="submit" name="Button" type="button" onClick="window.location='currency.php?update_rates'" value="{#update_rates#}" /><br/><br/>
			{#currency_update_hint#}
			<br/>{#last_update#}: <b>{$conf.last_currency_update}</b> / {if $today == $conf.last_currency_update}Today{/if}<br/>
<table border="0" class="sortable">
<caption>{#currencies#}</caption>
<thead>
<tr><th>{#title#}</th><th>{#currency_code#}</th><th>{#rate#}</th><th>{#active#}</th><th>{#default#}</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$currencies item=currency}
<tr class="{cycle values="oddrow,none"}"><td>{$currency.title}</td><td><b>{$currency.code}</b></td><td>{$currency.rate}</td>
<td>
{if $currency.active eq "1"}
{if $currency.default eq "0"}
<a href="currency.php?deactivate={$currency.c_id}">{#deactivate#}</a>
{else}
{#deactivate#}
{/if}
{else}
<a href="currency.php?activate={$currency.c_id}">{#activate#}</a>
{/if}
</td>
<td>{if $currency.default eq "1"}{#default#}{else}<a href="currency.php?default={$currency.c_id}">{#make_default#}</a>{/if}</td>
<td><a href="currency.php?edit={$currency.c_id}"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('currency.php?delete={$currency.c_id}')" title="Delete Currency"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_currency#}" border="0"></a></td></tr>
{/foreach}

</tbody></table>
{include file="admin/footer.tpl"}