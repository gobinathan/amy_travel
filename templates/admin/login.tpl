<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$language_encoding}" />
<meta name="author" content="Web Development Labs" />
<title>{#admin_panel#}</title>
<link rel="stylesheet" type="text/css" href="{$BASE_URL}/templates/admin/style.css" media="screen" />
<script src="{$BASE_URL}/js/main.js" type="text/javascript"></script>
</head>
<body>
<center><div id="content">
<h2>{#admin_panel#}</h2><br/>
{include file="errors.tpl" errors=$error error_count=$error_count}
	<div style="width: 400px; float: center;">
	<div id="frm">
	<script language="JavaScript">
		// Enter name of mandatory fields
		var fieldRequired = Array("username", "password");
		// Enter field description to appear in the dialog box
		var fieldDescription = Array("{#username#}", "{#password#}");
	</script>
	<form method="post" action="{$smarty.server.PHP_SELF|xss}" onSubmit="return formCheck(this);">
	<label>{#username#}:</label><input type="text" name="username" value="{$smarty.post.username}" /><br/>
	<label>{#password#}:</label><input type="password" name="password" /><br/>
{if $conf.require_captcha}<label><img src="{$BASE_URL}/includes/random_image.php" border="1" alt="{#captcha_code#}" /></label><input type="text" name="txtNumber" onMouseOver="showhint('{#hint_captcha_code#}', this, event, '150px')" size="8" /><br/>{/if}

<label>{#prefered_language#}:</label> <select name="lang">
{foreach from=$languages_array item=lang}
<option style="background-image:url({$BASE_URL}/uploads/flags/{$lang.lang_name}.gif);" value="{$lang.lang_name}" {if $lang.lang_name eq "$language"}selected{/if}>{$lang.lang_title}</option>
{/foreach}
</select><br/>

	<label></label><input type="submit" name="submit" value="{#login#}"/>
	</form>
	</div>
	</div>
<br/><br/><hr/>
<br/>
</body></html>