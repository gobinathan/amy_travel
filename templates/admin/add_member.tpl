{include file="admin/header.tpl"}
<div class="left">
			<h3>{#add_new_member#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("username", "password", "email", "fullname");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#username#}", "{#password#}", "{#email#}", "{#fullname#}");
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);" ENCTYPE="multipart/form-data">
<div id="frm">
<label>{#email#}:</label><input type="text" name="email" class="required" onMouseOver="showhint('{#hint_member_email#}', this, event, '150px')"/><br/>
<label for="password">{#password#}:</label><input type="text" name="password" class="required" onMouseOver="showhint('{#hint_member_password#}', this, event, '150px')"/><br/>
<label>{#fullname#}:</label><input type="text" name="fullname" class="required" onMouseOver="showhint('{#hint_member_fullname#}', this, event, '150px')"/><br/>
<label>{#photo#}:</label><input type="file" name="picture" onMouseOver="showhint('{#hint_member_photo#}', this, event, '150px')"/><br/>
</div>
<br/>
<input type="submit" name="add_member" value="{#add_member#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='members.php'" value="{#back_members#}" />
</form>
</div>
{include file="admin/footer.tpl"}