{include file="admin/header.tpl"}
<div class="left">
			<h3>{#editing_language#} {$lang.lang_title}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("lang_title");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#language_title#}");
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="formCheck(this);">
<div id="frm">
<input type="hidden" name="edit_lang" value="{$lang.lang_name}" />
<label for="title">{#language_title#}:</label><input type="text" name="lang_title" class="required" value="{$lang.lang_title}" /><br/>
<label for="title">{#language_encoding#}:</label><input type="text" name="lang_encoding" value="{$lang.encoding}" /><br/>
<input type="submit" value="{#edit_language#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='languages.php'" value="{#back_languages#}" />
</form>
</div>
<br/><br/>
{#editing_language#}: <b>{$lang.lang_title}</b><br/>
<hr>
	<table width="700" class="tbl">
	<caption onClick="javascript:hiding('globals');" style="cursor:pointer;" onMouseover="showhint('{#hint_lang_expand#}', this, event, '150px')">{#globals_lang_values#}</caption>
{if $lang_config.allow_add_globals eq "1"}
<table>
<tr><td>Key</td><td>Value</td></tr>
<tr><td><form method="post" action="{$smarty.server.PHP_SELF|xss}" onSubmit="disableForm(this,'{#updating#}')"><input type="text" name="new_key" size="30" class="text" /></td><td><input type="text" name="new_var" size="50" class="text" /><input type="hidden" name="add_global" value="{$lang.lang_name}" /><input type="submit" value="Add New Global Variable"></form></td></tr></table>
{/if}
	<table id="globals" style="display:none;" class="tbl">
	<thead><tr><th>{#lang_name#}</th><th>{#lang_value#}</th></tr></thead>
	<tr><td></td><td><input type="submit" value="{#frontend_lang_submit#}" /></td></tr>
	<tbody>
	<form method="post" action="{$smarty.server.PHP_SELF|xss}" onSubmit="disableForm(this,'{#updating#}')">
	{foreach key=key from=$lang_globals item=row}
	<tr class="{cycle values="oddrow,none"}"><td>
	{if $lang_config.allow_del_globals eq "1"}
	<a href="#" onClick="DeleteItem('languages.php?del_global={$lang.lang_name}&del_var={$key}');return false;" title="Delete This Language Variable"><img src="{$BASE_URL}/admin/images/delete.png" border="0"></a>&nbsp;&nbsp;&nbsp; 
	{/if}
	<b>{$key}</b><a onClick="submitChange('lang[{$key}]');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a></td><td><input type="text" name="lang[{$key}]" id="lang[{$key}]" value="{$row}" size="80" class="text" /></td></tr>
	{/foreach}
	</tbody>
	<tr><td></td><td><input type="hidden" name="edit_lang_globals" value="{$lang.lang_name}" /><input type="submit"  value="{#globals_lang_submit#}" /></form></td></tr>
	</table>
	</table>
	<hr/>
	<form method="post" action="{$smarty.server.PHP_SELF|xss}" onSubmit="disableForm(this,'{#updating#}')">
	<table width="700" class="tbl">
	<caption onClick="javascript:hiding('frontend');" style="cursor:pointer;" onMouseover="showhint('{#hint_lang_expand#}', this, event, '150px')">{#frontend_lang_values#}</caption>
{if $lang_config.allow_add_frontend eq "1"}
<table>
<tr><td>Key</td><td>Value</td></tr>
<tr><td><form method="post" action="{$smarty.server.PHP_SELF|xss}" onSubmit="disableForm(this,'{#updating#}')"><input type="text" name="new_key" size="30" class="text" /></td><td><input type="text" name="new_var" size="50" class="text" /><input type="hidden" name="add_frontend" value="{$lang.lang_name}" /><input type="submit" value="Add New Front-End Variable"></form></td></tr></table>
{/if}	
	<table id="frontend" style="display:none;" class="tbl">
	<thead><tr><th>{#lang_name#}</th><th>{#lang_value#}</th></tr></thead>
	<tr><td></td><td><input type="submit" value="{#frontend_lang_submit#}" /></td></tr>
	<tbody>
	{foreach key=key from=$lang_frontend item=row}
	<tr class="{cycle values="oddrow,none"}"><td>
	{if $lang_config.allow_del_frontend eq "1"}
	<a href="#" onClick="DeleteItem('languages.php?del_frontend={$lang.lang_name}&del_var={$key}');return false;" title="Delete This Language Variable"><img src="{$BASE_URL}/admin/images/delete.png" border="0"></a>&nbsp;&nbsp;&nbsp; 
	{/if}	
	<b>{$key}</b><a onClick="submitChange('lang[{$key}]');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a></td><td><input type="text" name="lang[{$key}]" id="lang[{$key}]" value="{$row}" size="80" class="text" /></td></tr>
	{/foreach}
	</tbody>
	<tr><td></td><td><input type="hidden" name="edit_lang_frontend" value="{$lang.lang_name}" /><input type="submit"  value="{#frontend_lang_submit#}" /></form></td></tr>
	</table>
	</table>
	<hr/>
	<form method="post" action="{$smarty.server.PHP_SELF|xss}" onSubmit="disableForm(this,'{#updating#}')">
	<table width="700" class="tbl">
	<caption onClick="javascript:hiding('members');" style="cursor:pointer;" onMouseover="showhint('{#hint_lang_expand#}', this, event, '150px')">{#members_lang_values#}</caption>
{if $lang_config.allow_add_members eq "1"}
<table>
<tr><td>Key</td><td>Value</td></tr>
<tr><td><form method="post" action="{$smarty.server.PHP_SELF|xss}" onSubmit="disableForm(this,'{#updating#}')"><input type="text" name="new_key" size="30" class="text" /></td><td><input type="text" name="new_var" size="50" class="text" /><input type="hidden" name="add_members" value="{$lang.lang_name}" /><input type="submit" value="Add New Members Variable"></form></td></tr></table>
{/if}		
	<table id="members" style="display:none;" class="tbl">
	<thead><tr><th>{#lang_name#}</th><th>{#lang_value#}</th></tr></thead>
	<tr><td></td><td><input type="submit"  value="{#members_lang_submit#}" /></td></tr>
	<tbody>
	{foreach key=key from=$lang_members item=row}
	<tr class="{cycle values="oddrow,none"}"><td>
	{if $lang_config.allow_del_members eq "1"}
	<a href="#" onClick="DeleteItem('languages.php?del_members={$lang.lang_name}&del_var={$key}');return false;" title="Delete This Language Variable"><img src="{$BASE_URL}/admin/images/delete.png" border="0"></a>&nbsp;&nbsp;&nbsp; 
	{/if}	
	<b>{$key}</b><a onClick="submitChange('lang[{$key}]');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a></td><td><input type="text" name="lang[{$key}]" id="lang[{$key}]" value="{$row}" size="80" class="text" /></td></tr>
	{/foreach}
	</tbody>
	<tr><td></td><td><input type="hidden" name="edit_lang_members" value="{$lang.lang_name}" /><input type="submit"  value="{#members_lang_submit#}" /></form></td></tr>
	</table>
	</table>

	<hr/>
	<form method="post" action="{$smarty.server.PHP_SELF|xss}" onSubmit="disableForm(this,'{#updating#}')">
	<table width="700" class="tbl">
	<caption onClick="javascript:hiding('errors');" style="cursor:pointer;" onMouseover="showhint('{#hint_lang_expand#}', this, event, '150px')">{#errors_lang_values#}</caption>
{if $lang_config.allow_add_errors eq "1"}
<table>
<tr><td>Key</td><td>Value</td></tr>
<tr><td><form method="post" action="{$smarty.server.PHP_SELF|xss}" onSubmit="disableForm(this,'{#updating#}')"><input type="text" name="new_key" size="30" class="text" /></td><td><input type="text" name="new_var" size="50" class="text" /><input type="hidden" name="add_errors" value="{$lang.lang_name}" /><input type="submit" value="Add New Errors Variable"></form></td></tr></table>
{/if}		
	<table id="errors" style="display:none;" class="tbl">
	<thead><tr><th>{#lang_name#}</th><th>{#lang_value#}</th></tr></thead>
	<tr><td></td><td><input type="submit"  value="{#errors_lang_submit#}" /></td></tr>
	<tbody>
	{foreach key=key from=$lang_errors item=row}
	<tr class="{cycle values="oddrow,none"}"><td>
	{if $lang_config.allow_del_errors eq "1"}
	<a href="#" onClick="DeleteItem('languages.php?del_errors={$lang.lang_name}&del_var={$key}');return false;" title="Delete This Language Variable"><img src="{$BASE_URL}/admin/images/delete.png" border="0"></a>&nbsp;&nbsp;&nbsp; 
	{/if}	
	<b>{$key}</b><a onClick="submitChange('lang[{$key}]');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a></td><td><input type="text" name="lang[{$key}]" id="lang[{$key}]" value="{$row}" size="80" class="text" /></td></tr>
	{/foreach}
	</tbody>
	<tr><td></td><td><input type="hidden" name="edit_lang_errors" value="{$lang.lang_name}" /><input type="submit"  value="{#errors_lang_submit#}" /></form></td></tr>
	</table>
	</table>
	<hr/>

	<form method="post" action="{$smarty.server.PHP_SELF|xss}" onSubmit="disableForm(this,'{#updating#}')">
	<table width="700" class="tbl">
	<caption onClick="javascript:hiding('hints');" style="cursor:pointer;" onMouseover="showhint('{#hint_lang_expand#}', this, event, '150px')">{#hints_lang_values#}</caption>
{if $lang_config.allow_add_hints eq "1"}
<table>
<tr><td>Key</td><td>Value</td></tr>
<tr><td><form method="post" action="{$smarty.server.PHP_SELF|xss}" onSubmit="disableForm(this,'{#updating#}')"><input type="text" name="new_key" size="30" class="text" /></td><td><input type="text" name="new_var" size="50" class="text" /><input type="hidden" name="add_hints" value="{$lang.lang_name}" /><input type="submit" value="Add New Hints Variable"></form></td></tr></table>
{/if}	

	<table id="hints" style="display:none;" class="tbl">
	<thead><tr><th>{#lang_name#}</th><th>{#lang_value#}</th></tr></thead>
	<tr><td></td><td><input type="submit"  value="{#hints_lang_submit#}" /></td></tr>
	<tbody>
	{foreach key=key from=$lang_hints item=row}
	<tr class="{cycle values="oddrow,none"}"><td>
	{if $lang_config.allow_del_hints eq "1"}
	<a href="#" onClick="DeleteItem('languages.php?del_hints={$lang.lang_name}&del_var={$key}');return false;" title="Delete This Language Variable"><img src="{$BASE_URL}/admin/images/delete.png" border="0"></a>&nbsp;&nbsp;&nbsp; 
	{/if}
	<b>{$key}</b><a onClick="submitChange('lang[{$key}]');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a></td><td><input type="text" name="lang[{$key}]" id="lang[{$key}]" value="{$row}" size="80" class="text" /></td></tr>
	{/foreach}
	</tbody>
	<tr><td></td><td><input type="hidden" name="edit_lang_hints" value="{$lang.lang_name}" /><input type="submit"  value="{#hints_lang_submit#}" /></form></td></tr>	
	</table>
	</table>
	<hr/>
	
	<form method="post" action="{$smarty.server.PHP_SELF|xss}" onSubmit="disableForm(this,'{#updating#}')">
	<table width="700" class="tbl">
	<caption onClick="javascript:hiding('admin');" style="cursor:pointer;" onMouseover="showhint('{#hint_lang_expand#}', this, event, '150px')">{#admin_lang_values#}</caption>
{if $lang_config.allow_add_admin eq "1"}
<table>
<tr><td>Key</td><td>Value</td></tr>
<tr><td><form method="post" action="{$smarty.server.PHP_SELF|xss}" onSubmit="disableForm(this,'{#updating#}')"><input type="text" name="new_key" size="30" class="text" /></td><td><input type="text" name="new_var" size="50" class="text" /><input type="hidden" name="add_admin" value="{$lang.lang_name}" /><input type="submit" value="Add New Admin Variable"></form></td></tr></table>
{/if}	
	<table id="admin" style="display:none;" class="tbl">
	<thead><tr><th>{#lang_name#}</th><th>{#lang_value#}</th></tr></thead>
	<tr><td></td><td><input type="submit"  value="{#admin_lang_submit#}" /></td></tr>
	<tbody>
	{foreach key=key from=$lang_admin item=row}
	<tr class="{cycle values="oddrow,none"}"><td>
	{if $lang_config.allow_del_admin eq "1"}
	<a href="#" onClick="DeleteItem('languages.php?del_admin={$lang.lang_name}&del_var={$key}');return false;" title="Delete This Language Variable"><img src="{$BASE_URL}/admin/images/delete.png" border="0"></a>&nbsp;&nbsp;&nbsp; 
	{/if}
	<b>{$key}</b><a onClick="submitChange('lang[{$key}]');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a></td><td><input type="text" name="lang[{$key}]" id="lang[{$key}]" value="{$row}" size="80" class="text" /></td></tr>
	{/foreach}
	</tbody>
	<tr><td></td><td><input type="hidden" name="edit_lang_admin" value="{$lang.lang_name}" /><input type="submit"  value="{#admin_lang_submit#}" /></form></td></tr>	
	</table>
	</table>
</div>
{assign var="edit_lang" value=$lang.lang_name}
{include file="admin/footer.tpl"}