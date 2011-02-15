{include file="admin/header.tpl"}
<div class="left">
			<h3>{#add_email#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("email");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#subscriber_email#}");
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<div id="frm">
<label for="subscriber_email">{#subscriber_email#}:</label><input type="text" name="email" class="required" /><br/>
<input type="submit" name="add_email" value="{#add_email#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='marketing.php'" value="{#back_emails#}" />
</form>
</div>
</div>
{include file="admin/footer.tpl"}