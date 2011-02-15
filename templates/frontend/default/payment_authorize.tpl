<html><head><title>{#payment_redirect_authorize#}</title>
<SCRIPT LANGUAGE="JavaScript">
{literal}
function fnSubmit() {
  window.document.redirect_authorize.submit();
  return;
}
{/literal}
</SCRIPT>
</head>
<body onload="return fnSubmit()">
{#payment_redirect_authorize#}<br/><br/>
<FORM action="https://secure.authorize.net/gateway/transact.dll" method="POST" name="redirect_authorize">
{include_php file="includes/authorizenet_lib.php"}
{php}
if (substr($amount, 0,1) == "$") {
	$amount = substr($amount,1);
}
$ret = InsertFP ($payment_gw[authorize_id], $payment_gw[authorize_key] $order[price], $order[order_id]);
{/php}
<INPUT type="hidden" name="x_description" value="{$credit_plan.title|stripslashes}">
<INPUT type="hidden" name="x_login" value="{$payment_gw.authorize_id}">
<INPUT type="hidden" name="x_amount" value="{$order.price}">
<INPUT type="hidden" name="x_invoice_num" value="{$order.order_id}">
<INPUT type="hidden" name="x_cust_id" value="{$credit_plan.plan_id}">
<INPUT type="hidden" name="x_show_form" value="PAYMENT_FORM">
<INPUT type="hidden" name="x_relay_response" value="TRUE">
<INPUT type="hidden" name="x_relay_url" value="{$BASE_URL}/ipn/authorize">
</form>