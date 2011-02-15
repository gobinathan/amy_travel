{include file="admin/header.tpl"}
<div class="left">
			<h3>{#add_new_country#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("title", "country_code");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#country_title#}", "{#country_code#}");
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<div id="frm">
<label for="title">{#title#}:</label><input type="text" name="title" class="required" /><br/>
<label for="code">{#country_code#}:</label><input type="text" name="country_code" class="required" /><br/>
<input type="submit" name="add_country" value="{#add_country#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='countries.php'" value="{#back_countries#}" />
</form>
</div>
</div>
{include file="admin/footer.tpl"}