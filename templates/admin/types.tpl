{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#type_fields#}: {$manage}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("title");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#title#}");
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<input type="hidden" name="manage" value="{$smarty.get.manage}" />
<label for="username">{#title#}:</label><input type="text" name="title" size="50" /> <input type="submit" name="add_new_type" value="{#add_new_type#}: {$manage}" />
</form>
<br/>
{if count($types)}
<ul id="countrytabs" class="shadetabs">
{foreach from=$languages_array item=lang}
<li><a href="types.php?manage={$smarty.get.manage}&edit_lang={$lang.lang_name}" {if $edit_lang == $lang.lang_name}class="selected"{/if}><img src="{$BASE_URL}/uploads/flags/{$lang.lang_name}.gif" border="0" />&nbsp;{$lang.lang_title}</a></li>
{/foreach}
</ul>
<div id="countrydivcontainer" style="border:1px solid gray; width:850px; margin-bottom: 1em; padding: 10px">
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onSubmit="disableForm(this,'{#updating#}')">
<input type="hidden" name="edit_lang" value="{$edit_lang}" />
<input type="hidden" name="manage" value="{$smarty.get.manage}" />
<input type="hidden" name="edit_lang_values" value="go" />
<input type="submit" name="edit" value="{#save_changes#}" />
<table border="0" class="sortable">
<caption><b>{$manage}</b></caption>
<thead>
<tr><th>{#id#}</th><th>{#title#}</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$types item=type}
<tr class="{cycle values="oddrow,none"}"><td>{$type.type_id}</td><td><input type="text" name="type[{$type.type_id}]" id="type[{$type.type_id}]" value="{$type.title}" size="100" class="text" /></td><td>
{if $edit_lang != $language}
<a onClick="submitChange('type[{$type.type_id}]');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="cursor:pointer;" /></a> | {/if}
<a href="#" onClick="DeleteItem('types.php?manage={$smarty.get.manage}&delete={$type.type_id}')" title="{#delete_type#}"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_type#}" border="0"></a></td></tr>
{/foreach}
</tbody></table>
<input type="submit" name="edit" value="{#save_changes#}" />
</form>
</div>
</div>
{else}
{#no_types#}
{/if}
{include file="admin/footer.tpl"}