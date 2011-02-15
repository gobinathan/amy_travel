<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="{$BASE_URL}/js/main.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html; charset={$language_encoding}" />
<title>Transaction/Payment Details</title>
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
width: 470px;
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
{include file="admin/msg.tpl"}
<fieldset><legend>{#listing#}</legend>
<i><b>{$order.booking.listing.title|stripslashes}</b></i><img src={$BASE_URL}/uploads/thumbs/{$order.booking.listing.icon} border=0 height=50 width=50 style=float:left; /><br/><img src=../images/stars-{$order.booking.listing.stars}.gif border=0><br/>{$order.booking.listing.short_description|stripslashes}<br/>
</fieldset>
<span style="float:right;">{#date_added#}: {$order.booking.date_added|date_format:"%d/%b/%Y %H:%M:%S"}</span>
<br/>
<div align="center">
<input name="Button" type="button" onClick="DeleteItem('transactions.php?delete={$order.order_id}');" value="{#delete#}" />&nbsp;&nbsp;
<input name="Button" type="button" onClick="window.close();" value="{#close#}" />
</div>
<fieldset><legend>Customer Details</legend>
{#first_name#}: {$order.booking.first_name}<br/>
{#last_name#}: {$order.booking.last_name}<br/>
{#email_address#}: {$order.booking.email}<br/>
</fieldset><br/>
<fieldset><legend>Order Details</legend>
Total: {$order.total_amount} {$order.currency}<br/>
{#date_added#}: {$order.booking.to_date|date_format:"%d/%b/%Y"}<br/>
Payment Gateway: {if $order.payment_gw eq "bw"}{#gw_bank_wire#}{/if}
{if $order.payment_gw eq "paypal"}{#gw_paypal#}{/if}
{if $order.payment_gw eq "2checkout"}{#gw_2checkout#}{/if}
{if $order.payment_gw eq "authorize"}{#gw_authorize#}{/if}
<br/>
Recipient: {$order.recipient}<br/>

</fieldset><br/>
<fieldset style="clear:both;padding-top:20px;"><legend>Received Payment Data</legend>
{$order.payment_data|stripslashes}
</fieldset>
</div></div>
</body></html>