{include file="admin/header.tpl"}
{include file="admin/html_editor.tpl"}
<div class="left">
			<h3>{#add_article#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("title", "cat_id");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#title#}", "{#category#}");
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<table border="0" class="tbl">
<tr><td>{#title#}:</td><td><input type="text" name="title" value="{$smarty.post.title}" class="required" onMouseOver="showhint('{#hint_news_title#}', this, event, '150px')" /></td></tr>
<tr><td>{#category#}:</td><td><select name="cat_id" onMouseOver="showhint('{#hint_article_category#}', this, event, '150px')">
<option value="0">------------------</option>
{foreach from=$categories item=category}
<option value="{$category.cat_id}" {if $category.cat_id == $smarty.post.cat_id}selected{/if}>{$category.title}</option>
	{foreach from=$category.subcats item=subcat}
	<option value="{$subcat.cat_id}" {if $subcat.cat_id == $smarty.post.cat_id}selected{/if}>{$category.title} -> {$subcat.title}</option>
	{/foreach}
{/foreach}
</select></td></tr>
</table>
{#full_article#}<a href="#" class="hintanchor" onMouseover="showhint('{#hint_news_full_article#}', this, event, '150px')">[?]</a><textarea name="article" cols="70" rows="17">{$smarty.post.article}</textarea><img src="../images/required.gif" border="0"/><br/>
<a href="javascript:toggleEditor('article');">{#show_hide_editor#}</a><br/>
<input type="submit" name="add_article" value="{#add_article#}" /> &nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='articles.php'" value="{#back_articles#}" /></form>
</div>
</div>
{include file="admin/footer.tpl"}