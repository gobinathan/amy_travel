{include file="admin/header.tpl"}
{include file="admin/html_editor.tpl"}
<div class="left">
			<h3>{#add_new_category#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("title");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#title#}");
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" ENCTYPE="multipart/form-data" onsubmit="return formCheck(this);">
<table border="0" class="tbl">
<tr><td>{#title#}:</td><td><input type="text" name="title" value="{$smarty.post.title}" class="required" onMouseOver="showhint('{#hint_category_title#}', this, event, '150px')" /></td></tr>
</table>
{#description#}:<a href="#" class="hintanchor" onMouseover="showhint('{#hint_category_description#}', this, event, '150px')">[?]</a><textarea name="description" cols="70" rows="17">{$smarty.post.description}</textarea><br/>
<a href="javascript:toggleEditor('description');">{#show_hide_editor#}</a>
<table border="0" class="tbl">
<tr><td>{#parent_category#}:</td><td>
<select name="category" onMouseOver="showhint('{#hint_parent_category#}', this, event, '150px')">
<option value="0">{#main_category#}</option>
<option value="0">------------------</option>
{foreach from=$main_categories item=category}
<option value="{$category.cat_id}" {if $smarty.get.parent == $category.cat_id OR $smarty.post.category == $category.cat_id}selected{/if}>{$category.title}</option>
{/foreach}
</select>
</td></tr>
<tr><td>{#uri#}:</td><td><input type="text" name="uri" value="{$smarty.post.uri}" onMouseOver="showhint('{#hint_category_uri#}', this, event, '150px')" /></td></tr>
</table>
<input type="submit" name="add_category" value="{#add_category#}" /> &nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='categories.php'" value="{#back_categories#}" /></form>
</div>
</div>
{include file="admin/footer.tpl"}