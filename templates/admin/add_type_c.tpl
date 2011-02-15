{include file="admin/header.tpl"}
<div class="left">
			<h3>{#add_new_field#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("title");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#type_title#}");
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<div id="frm">
<label for="title">{#title#}:</label><input type="text" name="title" class="required" /><br/>
<input type="submit" name="add_type_c" value="{#add_type#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='types_c.php'" value="{#back_types#}" />
</form>
</div>
</div>
{include file="admin/footer.tpl"}