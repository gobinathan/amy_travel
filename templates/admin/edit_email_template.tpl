{include file="admin/header.tpl"}
<div class="left">
			<h3>Edit E-Mail Template: {$tpl.tpl_name}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("title", "cat_id");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#title#}", "{#category#}");
</script>
<ul id="countrytabs" class="shadetabs">
{foreach from=$languages_array item=lang}
<li><a href="email_templates.php?edit={$tpl.tpl_name}&edit_lang={$lang.lang_name}" href="#" {if $edit_lang == $lang.lang_name}class="selected"{/if}><img src="{$BASE_URL}/uploads/flags/{$lang.lang_name}.gif" border="0" />&nbsp;{$lang.lang_title}</a></li>
{/foreach}
</ul>
<div id="countrydivcontainer" style="border:1px solid gray; width:850px; margin-bottom: 1em; padding: 10px">
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<input type="hidden" name="edit_template" value="{$tpl.tpl_name}" />
<table border="0" class="tbl">
<tr><td>{#from_email#}:</td><td><input type="text" name="from_email" size="40" class="required" value="{$tpl.from_email}" />
</td></tr>
<tr><td>{#mail_subject#}:</td><td><input type="text" name="subject" id="subject" class="required" size="80" value="{$tpl.tpl_subject}" />
{if $edit_lang != $language}
<a onClick="submitChange('subject');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
</td></tr>
<tr><td>{#message_body#}</td><td><textarea name="tpl_source" id="tpl_source" cols="90" rows="20">{$tpl.tpl_source|stripslashes}</textarea><img src="../images/required.gif" border="0"/><br/>
{if $edit_lang != $language}
<a onClick="submitChange('tpl_source');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
</td></tr>
<tr><td>{#description#}:</td><td><input type="text" name="description" id="description" class="required" size="50" value="{$tpl.description}" onMouseOver="showhint('{#hint_email_tpl_description#}', this, event, '150px')" />
{if $edit_lang != $language}
<a onClick="submitChange('description');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
</td></tr>
<tr><td>Variables:</td><td><i>You can use the following variables in this e-mail template</i><br/>{$tpl.variables}</td></tr>
</table>
<input type="hidden" name="edit_lang" value="{$edit_lang}" />
<input type="submit" value="{#save_changes#}" /> &nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='email_templates.php'" value="{#back_email_templates#}" /></form>
<br/>Editing language: <b>{$edit_lang}</b>
</div>
</div>
</div>
{include file="admin/footer.tpl"}