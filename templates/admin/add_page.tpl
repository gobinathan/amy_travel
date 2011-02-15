{include file="admin/header.tpl"}
{include file="admin/html_editor.tpl"}
<div class="left">
			<h3>{#add_new_page#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("title", "where");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#title#}", "{#where#}");
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<table border="0" class="tbl">
<tr><td>{#title#}:</td><td><input type="text" name="title" value="{$smarty.post.title}" class="required" onMouseOver="showhint('{#hint_page_title#}', this, event, '150px')" /></td></tr>
<tr><td>{#where#}:<a href="#" class="hintanchor" onMouseover="showhint('{#hint_page_where#}', this, event, '150px')">[?]</a></td><td>{#up_menu#}<input type="radio" name="where" value="Up" {if $smarty.post.where == "Up"}checked{/if}/>&nbsp;&nbsp;&nbsp;&nbsp;{#down_menu#}<input type="radio" name="where" value="Down" {if $smarty.post.where == "Down"}checked{/if}/></td></tr>
</table>
{#page_content#}<a href="#" class="hintanchor" onMouseover="showhint('{#hint_page_content#}', this, event, '150px')">[?]</a><textarea name="page_content" cols="70" rows="17">{$smarty.post.page_content}</textarea><img src="../images/required.gif" border="0"/><br/>
<a href="javascript:toggleEditor('page_content');">{#show_hide_editor#}</a><br/>
<table border="0" class="tbl">
<tr><td>{#meta_keywords#}:</td><td><input type="text" name="meta_keywords" value="{$smarty.post.meta_keywords}" onMouseOver="showhint('{#hint_meta_keywords#}', this, event, '150px')" /></td></tr>
<tr><td>{#meta_description#}:</td><td><input type="text" name="meta_description" value="{$smarty.post.meta_description}" onMouseOver="showhint('{#hint_meta_description#}', this, event, '150px')" /></td></tr>
<tr><td>{#uri#}:</td><td><input type="text" name="uri" value="{$smarty.post.uri}" onMouseOver="showhint('{#hint_page_uri#}', this, event, '150px')" /></td></tr>
</table>
<input type="submit" name="add_page" value="{#add_page#}" /> &nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='pages.php'" value="{#back_pages#}" /></form>
</div>
</div>
{include file="admin/footer.tpl"}