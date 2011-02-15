{include file="admin/header.tpl"}
{include file="admin/html_editor.tpl"}
<div class="left">
			<h3>{#menu_pages#} {#edit_page#}: {$page.uri}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("title", "uri", "where");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#title#}", "{#uri#}", "{#where#}");
</script>
<ul id="countrytabs" class="shadetabs">
{foreach from=$languages_array item=lang}
<li><a href="pages.php?edit={$page.page_id}&edit_lang={$lang.lang_name}" {if $edit_lang == $lang.lang_name}class="selected"{/if}><img src="{$BASE_URL}/uploads/flags/{$lang.lang_name}.gif" border="0" />&nbsp;{$lang.lang_title}</a></li>
{/foreach}
</ul>
<div id="countrydivcontainer" style="border:1px solid gray; width:850px; margin-bottom: 1em; padding: 10px">
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<input type="hidden" name="edit_page" value="{$page.page_id}" />
<table border="0" class="tbl">
<tr><td>{#title#}:</td><td><input type="text" name="title" id="title" class="required" value="{$page.title}" onMouseOver="showhint('{#hint_page_title#}', this, event, '150px')" />
{if $edit_lang != $language}
<a onClick="submitChange('title');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
</td></tr>
<tr><td>{#where#}:<a href="#" class="hintanchor" onMouseover="showhint('{#hint_page_where#}', this, event, '150px')">[?]</a></td><td>{#up_menu#}<input type="radio" name="where" value="Up" {if $page.where == "Up"}checked{/if}/>&nbsp;&nbsp;&nbsp;&nbsp;{#down_menu#}<input type="radio" name="where" value="Down" {if $page.where == "Down"}checked{/if}/></td></tr>
</table>
{#page_content#}<a href="#" class="hintanchor" onMouseover="showhint('{#hint_page_content#}', this, event, '150px')">[?]</a><textarea name="page_content" id="page_content" cols="70" rows="17">{$page.content|stripslashes}</textarea><img src="../images/required.gif" border="0"/><br/>
{if $edit_lang != $language}
<a onClick="toggleEditor('page_content');submitChange('page_content');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
<a href="javascript:toggleEditor('page_content');">{#show_hide_editor#}</a><br/>
<table border="0" class="tbl">
<tr><td>{#meta_keywords#}:</td><td><input type="text" name="meta_keywords" value="{$page.meta_keywords}" onMouseOver="showhint('{#hint_meta_keywords#}', this, event, '150px')" /></td></tr>
<tr><td>{#meta_description#}:</td><td><input type="text" name="meta_description" value="{$page.meta_description}" onMouseOver="showhint('{#hint_meta_description#}', this, event, '150px')" /></td></tr>
<tr><td>{#uri#}:</td><td><input type="text" name="uri" class="required" value="{$page.uri}" onMouseOver="showhint('{#hint_page_uri#}', this, event, '150px')" /></td></tr>
</table>
<input type="hidden" name="edit_lang" value="{$edit_lang}" />
<input type="submit" value="{#save_changes#}" /> &nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='pages.php'" value="{#back_pages#}" /></form>
<br/>Editing language: <b>{$edit_lang}</b>
</div>
</div>
</div>
{include file="admin/footer.tpl"}