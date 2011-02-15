<html><head><title>{#payment_redirect_paypal#}</title>
<SCRIPT LANGUAGE="JavaScript">
{literal}
function fnSubmit() {
  window.document.redirect_paypal.submit();
  return;
}
{/literal}
</SCRIPT>
</head>
<body onload="return fnSubmit()">
<center>
<h2>{#payment_redirect_paypal#}</h2><br/><br/>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="redirect_paypal">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="{$payment_gw.paypal_id}">
<input type="hidden" name="item_name" value="{$listing.title|stripslashes} - {#passengers#}: {$res.count_people} / {#rooms#}: {$res.count_rooms} / {$res.days} {#days#}">
<input type="hidden" name="item_number" value="1">
<input type="hidden" name="amount" value="{$res.total_price}"><br />
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="return" value="{$BASE_URL}/payment_success">
<input type="hidden" name="notify_url" value="{$BASE_URL}/ipn/paypal">
<input type="hidden" name="cancel_return" value="{$BASE_URL}/payment_error/{$order_id}">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="tax" value="0">
<input type="hidden" name="currency_code" value="{$res.currency}">
<input type="hidden" name="bn" value="PP-BuyNowBF">
<input type="hidden" name="on0" value="ORDERID">
<input type="hidden" name="os0" maxlength="200" value="{$order_id}">
<br />
</form>  
