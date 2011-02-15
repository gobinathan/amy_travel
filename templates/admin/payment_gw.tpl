{include file="admin/header.tpl"}
		<div class="left">
			<h3>Payment Settings</h3>
{include file="admin/msg.tpl"}
			<div class="left_box">
<form method="post" action="">
<fieldset><legend>PayPal</legend>
Enable PayPal: <input type="checkbox" name="paypal_enabled" {if $payment_gw.paypal_enabled}checked{/if}/><br/>
PayPal Email: <input type="text" name="paypal_id" value="{$payment_gw.paypal_id}" size="40" class="text" /><br /><br/>
Manual Approve: <a href="#" class="hintanchor" onMouseover="showhint('When this is checked, the Order will have to be manually approved from the Administrator.', this, event, '150px')">[?]</a> <input type="checkbox" name="paypal_approve" {if $payment_gw.paypal_approve}checked{/if} /><br/>
</fieldset>
<br/>
<fieldset><legend>2CheckOut</legend>
Enable 2CheckOut: <input type="checkbox" name="2checkout_enabled" {if $payment_gw.2checkout_enabled}checked{/if} /><br/>
2CO Vendor ID: <input type="text" name="2checkout_id" value="{$payment_gw.2checkout_id}" size="20" class="text" /><br />
Secret Word: <input type="text" name="2checkout_secret" value="{$payment_gw.2checkout_secret}" size="20" class="text" /><br /><br/>
Manual Approve: <a href="#" class="hintanchor" onMouseover="showhint('When this is checked, the Order will have to be manually approved from the Administrator.', this, event, '150px')">[?]</a> <input type="checkbox" name="2checkout_approve" {if $payment_gw.2checkout_approve}checked{/if} /><br/>
</fieldset>
<br/>
<fieldset><legend>Authorize.net</legend>
Enable Authorize.net: <input type="checkbox" name="authorize_enabled" {if $payment_gw.authorize_enabled}checked{/if} /><br/>
Authorize ID: &nbsp;&nbsp;<input type="text" name="authorize_id" value="{$payment_gw.authorize_id}" size="20" class="text" /><br />
Authorize Key: <input type="text" name="authorize_key" value="{$payment_gw.authorize_key}" size="50" class="text" /><br /><br/>
Manual Approve: <a href="#" class="hintanchor" onMouseover="showhint('When this is checked, the Order will have to be manually approved from the Administrator.', this, event, '150px')">[?]</a> <input type="checkbox" name="authorize_approve" {if $payment_gw.authorize_approve}checked{/if} /><br/>
</fieldset>
<fieldset><legend>Wire Transfer</legend>
Enable Wire transfers: <input type="checkbox" name="bw_enabled" {if $payment_gw.bw_enabled}checked{/if} onClick="hiding('frm')" /><br/>
<br/><div id="frm" {if !$payment_gw.bw_enabled}style="display:none;"{/if}>
<label>{#bw_recipient#}:</label><input type="text" name="bw_recipient" value="{$payment_gw.bw_recipient}" size="50"/><br/>
<label>{#currency#}:</label><input type="text" name="bw_currency" value="{$payment_gw.bw_currency}" size="3" /><br/>
<label>{#bw_bank_name#}:</label><input type="text" name="bw_bank_name" value="{$payment_gw.bw_bank_name}" size="40"/><br/>
<label>{#bw_bank_phone#}:</label><input type="text" name="bw_bank_phone" value="{$payment_gw.bw_bank_phone}" /><br/>
<label>{#bw_bank_address1#}:</label><input type="text" name="bw_bank_address1" value="{$payment_gw.bw_bank_address1}" size="60"/><br/>
<label>{#bw_bank_address2#}:</label><input type="text" name="bw_bank_address2" value="{$payment_gw.bw_bank_address2}" size="30"/><br/>
<label>{#bw_bank_city#}:</label><input type="text" name="bw_bank_city" value="{$payment_gw.bw_bank_city}" /><br/>
<label>{#bw_bank_state#}:</label><input type="text" name="bw_bank_state" value="{$payment_gw.bw_bank_state}" /><br/>
<label>{#bw_bank_zip#}:</label><input type="text" name="bw_bank_zip" value="{$payment_gw.bw_bank_zip}" size="5" /><br/>
<label>{#bw_bank_country#}:</label><input type="text" name="bw_bank_country" value="{$payment_gw.bw_bank_country}" /><br/>
<label>{#bw_account_number#}:</label><input type="text" name="bw_account_number" value="{$payment_gw.bw_account_number}" size="30"/><br/>
<label>{#bw_swift_code#}:</label><input type="text" name="bw_swift_code" value="{$payment_gw.bw_swift_code}" size="10"/><br/>
<label>{#bw_iban#}:</label><input type="text" name="bw_iban" value="{$payment_gw.bw_iban}" size="30" /><br/>
</div>
</fieldset>
<input class="submit" type="submit" name="submit" value="Save" /></form><br />
</div>
</div>
{include file="admin/footer.tpl"}