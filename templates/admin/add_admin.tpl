{include file="admin/header.tpl"}
<div class="left">
			<h3>{#add_new_admin#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("username", "password" ,"role");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#admin_username#}", "{#admin_password#}","{#admin_roll#}");
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<div id="frm">
<label for="username">{#admin_username#}:</label><input type="text" name="username" class="required" /><br/>
<label for="password">{#admin_password#}:</label><input type="text" name="password" class="required" /><br/>
<label for="role">{#admin_roll#}:</label>{html_radios name='id' values=$user_id output=$user_role  }<br/> 
			
<input type="submit" name="add_admin" value="{#add_admin#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='admins.php'" value="{#back_admins#}" />
</form>
</div>
</div>
{include file="admin/footer.tpl"}