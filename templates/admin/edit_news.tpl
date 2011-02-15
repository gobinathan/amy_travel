{include file="admin/header.tpl"}
{include file="admin/html_editor.tpl"}
<div class="left">
			<h3>{#edit_news#}: {$news.title|stripslashes}</h3>
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
<li><a href="news.php?edit={$news.news_id}&edit_lang={$lang.lang_name}" href="#" {if $edit_lang == $lang.lang_name}class="selected"{/if}><img src="{$BASE_URL}/uploads/flags/{$lang.lang_name}.gif" border="0" />&nbsp;{$lang.lang_title}</a></li>
{/foreach}
</ul>
<div id="countrydivcontainer" style="border:1px solid gray; width:850px; margin-bottom: 1em; padding: 10px">
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<input type="hidden" name="edit_news" value="{$news.news_id}" />
<table border="0" class="tbl">
<tr><td>{#title#}:</td><td><input type="text" name="title" id="title" class="required" value="{$news.title|stripslashes}" onMouseOver="showhint('{#hint_news_title#}', this, event, '150px')" />
{if $edit_lang != $language}
<a onClick="submitChange('title');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
</td></tr>
<tr><td>Visible:</td><td><input type="checkbox" name="visible" onMouseOver="showhint('If not Checked, The news will be hidden', this, event, '150px')" {if $news.visible eq "1"}checked{/if} /></td></tr>
<tr><td>{#brief_description#}:</td><td><input type="text" name="brief_description" id="brief_description" value="{$news.brief_description|stripslashes}" onMouseOver="showhint('{#hint_news_brief_description#}', this, event, '150px')" size="100" />
{if $edit_lang != $language}
<a onClick="submitChange('brief_description');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
</td></tr>
</table>
{#full_article#}<a href="#" class="hintanchor" onMouseover="showhint('{#hint_news_full_article#}', this, event, '150px')">[?]</a><textarea name="full_article" id="full_article" cols="70" rows="17">{$news.full_article|stripslashes}</textarea><br/>
{if $edit_lang != $language}
<a onClick="toggleEditor('full_article');submitChange('full_article');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
<a href="javascript:toggleEditor('full_article');">{#show_hide_editor#}</a><br/>

<input type="hidden" name="edit_lang" value="{$edit_lang}" />
<input type="submit" value="{#save_changes#}" /> &nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='news.php'" value="{#back_news#}" /></form>
<br/>Editing language: <b>{$edit_lang}</b>
</div>
</div>
</div>
{include file="admin/footer.tpl"}