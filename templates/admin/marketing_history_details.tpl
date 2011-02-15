<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<script src="{$BASE_URL}/js/main.js" type="text/javascript"></script>
<title>{#marketing_history_details#}</title>
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
width: 420px;
}
.curlycontainer .innerdiv{
background: transparent url(../images/brcorner.gif) bottom right no-repeat;
position: relative;
left: 2px;
top: 2px;
padding: 1px 4px 15px 5px;
}
</style>
{/literal}
</head>
<body>
<div class="curlycontainer">
<div class="innerdiv">
{#date_sent#}: <b>{$history.date_sent|date_format:"%d/%b/%Y %H:%M:%S"}</b><br/>
{#from_name#}: <b>{$history.from_name}</b><br/>
{#from_email#}: <b>{$history.from_email}</b><br/>
{#sent_by#}: <b>{admin2name id=$history.admin_id}</b><br/>
{#count_sent#}: <b>{$history.count_sent}</b><br/>
{if $history.attachment}Attachment: <b>{$history.attachment}</b><br/>{/if}
Type: <b>{$history.type}</b><br/>
<hr/>
{#mail_subject#}: <b>{$history.subject|stripslashes}</b><br/><br/>
{$history.body|stripslashes|nl2br}
<br/><br/><br/><br/>
</div></div>
<input class="submit" name="Button" type="button" onClick="DeleteItem('marketing.php?delhistory={$history.newsletter_id}');" value="{#delete#}" />&nbsp;&nbsp;
<input class="submit" name="Button" type="button" onClick="window.close();" value="Close" />
</body></html>