{include file="admin/header.tpl"}
<div class="left">
			<h3>{#edit_admin#}: {$admin.admin_id} </h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("username", "password");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#admin_username#}", "{#admin_password#}");
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<div id="frm">
<input type="hidden" name="edit_admin" value="{$admin.admin_id}" />
<label for="username">{#admin_username#}:</label><input type="text" name="username" class="required" value="{$admin.username}" /><br/>
<label for="password">{#admin_password#}:</label><input type="password" name="password" onMouseOver="showhint('{#hint_change_password#}', this, event, '150px')"/><br/>
<label for="roll">{#admin_roll#}:        </label><input type="text" name="roll" class="required"  value="{$admin.role}" /><br/>
<input type="submit" name="submit" value="{#edit_admin#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='admins.php'" value="{#back_admins#}" />
</form>
</div>
</div>
{include file="admin/footer.tpl"}