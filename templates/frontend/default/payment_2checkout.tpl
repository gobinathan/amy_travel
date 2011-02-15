<html><head><title>{#payment_redirect_2checkout#}</title>
<SCRIPT LANGUAGE="JavaScript">
{literal}
function fnSubmit() {
  window.document.redirect_2checkout.submit();
  return;
}
{/literal}
</SCRIPT>
</head>
<body onload="return fnSubmit()">
{#payment_redirect_2checkout#}<br/><br/>
<form action='https://www.2checkout.com/2co/buyer/purchase' method='post' name="redirect_2checkout">
<input type="hidden" name="sid" value="{$payment_gw.2checkout_id}">
<input type="hidden" name="total" value="{$order.price}">
<input type="hidden" name="cart_order_id" value="{$order.order_id}">
<input type='hidden' name='id_type' value='1' >
<input type='hidden' name='c_prod' value='{$credit_plan.plan_id}' >
<input type='hidden' name='c_name' value='{$credit_plan.title|stripslashes}' >
<input type='hidden' name='c_description' value='{$credit_plan.description|stripslashes}' >
<input type='hidden' name='c_price' value='{$order.price}' >
<input type="hidden" name="pay_method" value="CC">
<input type='hidden' name='fixed' value='Y' >
</form>
