{include file="admin/header.tpl"}
<div class="left">
			<h3>{#edit_type#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("title");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#title#}");
</script>
<ul id="countrytabs" class="shadetabs">
{foreach from=$languages_array item=lang}
<li><a href="types_c.php?edit={$type_c.type_c_id}&edit_lang={$lang.lang_name}" {if $edit_lang == $lang.lang_name}class="selected"{/if}><img src="{$BASE_URL}/uploads/flags/{$lang.lang_name}.gif" border="0" />&nbsp;{$lang.lang_title}</a></li>
{/foreach}
</ul>
<div id="countrydivcontainer" style="border:1px solid gray; width:850px; margin-bottom: 1em; padding: 10px">
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<div id="frm">
<input type="hidden" name="edit_lang" value="{$edit_lang}" />
<input type="hidden" name="edit_type_c" value="{$type_c.type_c_id}" />
<label for="title">{#title#}:</label><input type="text" name="title" id="title" class="required" value="{$type_c.title}" />
{if $edit_lang != $language}
<a onClick="submitChange('title');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
<br/>
<input type="submit" value="{#edit_type#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='types_c.php'" value="{#back_types#}" />
</form>
<br/>Editing language: <b>{$edit_lang}</b>
</div>
</div>
</div>
{include file="admin/footer.tpl"}