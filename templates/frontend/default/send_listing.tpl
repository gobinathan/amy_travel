<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$language_encoding}" />
<link rel="stylesheet" type="text/css" href="{$BASE_URL}/templates/frontend/{$template}/style.css" />
<script src="{$BASE_URL}/js/main.js" type="text/javascript"></script>
<title>{$listing.title|stripslashes} - {#send_listing#}</title>
{literal}
<style type="text/css">
body { 
	font: 0.8em Tahoma, sans-serif; 
	line-height: 1.5em;
	background: #fff; 
	color: #454545; 
}

a {	color: #E0691A;	background: inherit;}
a:hover { color: #6C757A; background: inherit; }
.curlycontainer{
border: 1px solid #b8b8b8;
margin-bottom: 1em;
width: 400px;
}
.curlycontainer .innerdiv{
background: transparent url(../../images/brcorner.gif) bottom right no-repeat;
position: relative;
left: 2px;
top: 2px;
padding: 1px 4px 15px 5px;
}
input.required {
	border:1px solid #b3b3b3;
	padding-right: 20px;
	background: url(../../images/required.gif);
	background-repeat: no-repeat;
	background-position: right center;
}
input.required:hover {
	border:1px solid #313131;
	background: none;
}
</style>
{/literal}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("fullname","from_email", "to_email", "txtNumber");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#send_from_fullname#}","{#send_from_email#}","{#send_to_email#}","{#captcha_code#}");
</script>
</head>
<body>
<div class="curlycontainer">
<div class="innerdiv">
{include file="errors.tpl" errors=$error error_count=$error_count}
{if $send_status eq "0"}
<form method="post" action="{$baseurl}/listing/{$listing.uri}/send" onsubmit="return formCheck(this);">
<fieldset><legend>{#send_listing#}</legend>
<i><b>{$listing.title|stripslashes}</b></i><img src={$BASE_URL}/uploads/thumbs/{$listing.icon} border=0 height=50 width=50 style=float:left; />
<br/>{#mls#}: {$listing.mls}<br/>
{#price#}: {$listing.price|money_format} {$listing.currency} {if $listing.price_desc}/ {$listing.price_desc}{/if}<br/>
</fieldset>
<div id="frm">
<label>{#send_from_fullname#}</label><input type="text" name="fullname" class="required" value="{$smarty.post.fullname|stripslashes}" /><br/>
<label>{#send_from_email#}</label><input type="text" name="from_email" class="required" value="{$smarty.post.from_email|stripslashes}" /><br/>
<label>{#send_to_email#}</label><input type="text" name="to_email" class="required" value="{$smarty.post.to_email|stripslashes}" /><br/>
<label>{#send_comment#}</label><textarea name="comment" cols="30" rows="8">{$smarty.post.comment|stripslashes}</textarea><br/>
{if $conf.require_captcha}<label><img src="{$BASE_URL}/includes/random_image.php" border="1" alt="{#captcha_code#}" /></label><input type="text" name="txtNumber" onMouseOver="showhint('{#hint_captcha_code#}', this, event, '150px')" class="required" size="8" /><br/>{/if}
<input type="submit" name="send" value="{#send_listing_submit#}" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
{else}
{#send_listing_success_msg#}<br/>
{/if}
<input name="Button" type="button" onClick="window.close();" value="{#close#}" />
</div>
</form>

</div></div>
</body></html>