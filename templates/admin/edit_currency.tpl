{include file="admin/header.tpl"}
<div class="left">
			<h3>{#edit_currency#}: {$currency.title}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<form method="post" action="{$smarty.server.PHP_SELF|xss}">
<div id="frm">
<label>{#currency#}:</label><select name="currency" onChange="document.getElementById('c_code').value = this.options[this.selectedIndex].value;" disabled>
{foreach from=$currency_list item=currencyy}
<option value="{$currencyy.code}" {if $currencyy.code == $currency.code}selected{/if}>{$currencyy.title}</option>
{/foreach}
</select>&nbsp;&nbsp;<input type="text" id="c_code" size="3" value="{$currency.code}" disabled/><br/>
<label>{#rate#}:</label><input type="text" name="rate" size="8" value="{$currency.rate}" onMouseOver="showhint('{#hint_currency_rate#}', this, event, '150px')"/><br/>
<label>{#manual_update#}:</label><input type="checkbox" name="manual_update" {if $currency.manual_update}checked{/if} onMouseOver="showhint('{#hint_currency_manual_update#}', this, event, '150px')"/><br/>
<label>{#active#}:</label><input type="checkbox" name="active" {if $currency.active}checked{/if}/><br/>
<input type="hidden" name="edit_currency" value="{$currency.c_id}" />
<input type="submit" name="submit" value="{#edit_currency#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='currency.php'" value="{#currencies#}" />
</form>
</div>
</div>
{include file="admin/footer.tpl"}