{include file="admin/header.tpl"}
{include file="admin/html_editor.tpl"}
<div class="left">
			<h3>{#add_news#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("title");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#title#}");
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<table border="0" class="tbl">
<tr><td>{#title#}:</td><td><input type="text" name="title" value="{$smarty.post.title}" class="required" onMouseOver="showhint('{#hint_news_title#}', this, event, '150px')" /></td></tr>
<tr><td>{#brief_description#}:</td><td><input type="text" name="brief_description" value="{$smarty.post.brief_description}" onMouseOver="showhint('{#hint_news_brief_description#}', this, event, '150px')" size="100" /></td></tr>
<tr><td>Visible:</td><td><input type="checkbox" name="visible" onMouseOver="showhint('If not Checked, The news will be hidden', this, event, '150px')" checked /></td></tr>
</table>
{#full_article#}<a href="#" class="hintanchor" onMouseover="showhint('{#hint_news_full_article#}', this, event, '150px')">[?]</a><textarea name="full_article" cols="70" rows="17">{$smarty.post.full_article}</textarea><br/>
<a href="javascript:toggleEditor('full_article');">{#show_hide_editor#}</a><br/>	
<input type="submit" name="add_news" value="{#add_news#}" /> &nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='news.php'" value="{#back_news#}" /></form>
</div>
</div>
{include file="admin/footer.tpl"}