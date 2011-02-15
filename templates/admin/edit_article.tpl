{include file="admin/header.tpl"}
{include file="admin/html_editor.tpl"}
<div class="left">
			<h3>{#edit_article#}: {$article.title|stripslashes}</h3>
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
<li><a href="articles.php?edit={$article.article_id}&edit_lang={$lang.lang_name}" href="#" {if $edit_lang == $lang.lang_name}class="selected"{/if}><img src="{$BASE_URL}/uploads/flags/{$lang.lang_name}.gif" border="0" />&nbsp;{$lang.lang_title}</a></li>
{/foreach}
</ul>
<div id="countrydivcontainer" style="border:1px solid gray; width:850px; margin-bottom: 1em; padding: 10px">
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<input type="hidden" name="edit_article" value="{$article.article_id}" />
<table border="0" class="tbl">
<tr><td>{#title#}:</td><td><input type="text" name="title" id="title" class="required" value="{$article.title|stripslashes}" onMouseOver="showhint('{#hint_news_title#}', this, event, '150px')" />
{if $edit_lang != $language}
<a onClick="submitChange('title');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
</td></tr>
<tr><td>{#category#}:</td><td><select name="cat_id" onMouseOver="showhint('{#hint_article_category#}', this, event, '150px')">
<option value="0">------------------</option>
{foreach from=$categories item=category}
<option value="{$category.cat_id}" {if $category.cat_id == $article.cat_id}selected{/if}>{$category.title}</option>
	{foreach from=$category.subcats item=subcat}
	<option value="{$subcat.cat_id}" {if $subcat.cat_id == $article.cat_id}selected{/if}>{$category.title} -> {$subcat.title}</option>
	{/foreach}
{/foreach}
</select></td></tr>
</table>
{#full_article#}<a href="#" class="hintanchor" onMouseover="showhint('{#hint_news_full_article#}', this, event, '150px')">[?]</a><textarea name="article" id="full_article" cols="70" rows="17">{$article.article|stripslashes}</textarea><img src="../images/required.gif" border="0"/><br/>
{if $edit_lang != $language}
<a onClick="toggleEditor('article');submitChange('article');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
<a href="javascript:toggleEditor('article');">{#show_hide_editor#}</a><br/>
<input type="hidden" name="edit_lang" value="{$edit_lang}" />
<input type="submit" value="{#save_changes#}" /> &nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='articles.php'" value="{#back_articles#}" /></form>
<br/>Editing language: <b>{$edit_lang}</b>
</div>
</div>
</div>
{include file="admin/footer.tpl"}