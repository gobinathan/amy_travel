{include file="admin/header.tpl"}
<div class="left">
			<h3>{#add_currency#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script type="text/javascript">
{literal}
function select_currency (code) {
	if (code !== 'custom') {
		document.getElementById('c_code').value = code; 
	}else {
		var elmnt = document.getElementById('currency'); 
		var newelmnt=document.createElement('input');
		newelmnt.setAttribute('type','text');
		newelmnt.setAttribute('name',elmnt.getAttribute('name'));
		elmnt.parentNode.replaceChild(newelmnt,elmnt);
		var celmnt = document.getElementById('c_code'); 
		var cnewelmnt=document.createElement('input');
		cnewelmnt.setAttribute('type','text');
		cnewelmnt.setAttribute('name',celmnt.getAttribute('name'));
		cnewelmnt.setAttribute('size','3');
		celmnt.parentNode.replaceChild(cnewelmnt,celmnt);
		var manu = document.getElementById('manual_update'); 
		manu.checked=true;
		newelmnt.focus();
	}
}
{/literal}
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}">
<div id="frm">
<label>{#currency#}:</label><select id="currency" name="currency" onChange="select_currency(this.options[this.selectedIndex].value);" onMouseOver="showhint('{#hint_currency_title#}', this, event, '150px')" >
<option value="">{#please_select_currency#}</option><option value="custom">Create Custom</option><option value="">--------------------</option>
{foreach from=$currency_list item=currency}
<option value="{$currency.code}">{$currency.title}</option>
{/foreach}
</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="c_code" name="c_code" size="3" value="" disabled/><br/>
<label>{#rate#}:</label><input type="text" name="rate" size="8" onMouseOver="showhint('{#hint_currency_rate#}', this, event, '150px')"/><br/>
<label>{#manual_update#}:</label><input type="checkbox" id="manual_update" name="manual_update" onMouseOver="showhint('{#hint_currency_manual_update#}', this, event, '150px')"/><br/>
<label>{#active#}:</label><input type="checkbox" name="active" checked /><br/>
<input type="submit" name="add_currency" value="{#add_currency#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='currency.php'" value="{#currencies#}" />
</form>
</div>
</div>
{include file="admin/footer.tpl"}