{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#orders#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
<fieldset><legend><b>{#filter#}</b></legend>
<form method="post" action="">
<select name="filter_gw"><option value="0">{#payment_gateway#}</option><option value="0">-------</option>
	<option value="paypal" {if $smarty.request.filter_gw eq "paypal"}selected style="background-color:#99CC00;"{/if}>{#gw_paypal#}</option>
	<option value="2co" {if $smarty.request.filter_gw eq "2co"}selected style="background-color:#99CC00;"{/if}>{#gw_2checkout#}</option>
	<option value="authorize" {if $smarty.request.filter_gw eq "authorize"}selected style="background-color:#99CC00;"{/if}>{#gw_authorize#}</option>
</select>
<select name="filter_status"><option value="0">{#status#}</option><option value="0">-------</option>
	<option value="confirmed" {if $smarty.request.filter_status eq "confirmed"}selected style="background-color:#99CC00;"{/if}>{#confirmed#} by gateway</option>
	<option value="approved" {if $smarty.request.filter_status eq "approved"}selected style="background-color:#99CC00;"{/if}>{#approved#} by admin</option>
	<option value="0">-------</option>	
	<option value="unconfirmed" {if $smarty.request.filter_status eq "unconfirmed"}selected style="background-color:#99CC00;"{/if}>{#unconfirmed#}</option>
	<option value="unapproved" {if $smarty.request.filter_status eq "unapproved"}selected style="background-color:#99CC00;"{/if}>{#unapproved#}</option>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="text" value="{if $smarty.request.start_date AND $smarty.request.start_date !== "From Date"}{$smarty.request.start_date}{else}{#from_date#}{/if}" id="start_date" name="start_date" size="8" onClick="this.value='';" /><img src="{$BASE_URL}/images/calendar.gif" id="f_trigger_s" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" /></td></tr>
{literal}
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "start_date",     // id of the input field
        ifFormat       :    "%d/%m/%y",      // format of the input field
        button         :    "f_trigger_s",  // trigger for the calendar (button ID)
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true
    });
</script>
{/literal}
&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" value="{if $smarty.request.end_date AND $smarty.request.end_date !== "To Date"}{$smarty.request.end_date}{else}{#to_date#}{/if}" id="end_date" name="end_date" size="8" onClick="this.value='';" /><img src="{$BASE_URL}/images/calendar.gif" id="f_trigger_e" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" /></td></tr>
{literal}
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "end_date",     // id of the input field
        ifFormat       :    "%d/%m/%y",      // format of the input field
        button         :    "f_trigger_e",  // trigger for the calendar (button ID)
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true
    });
</script>
{/literal}
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" name="filter" value="{#filter#}" />&nbsp;&nbsp;&nbsp;<input type="reset" value="{#reset#}" />
</form>
</fieldset>

{if count($orders)}
<table cellpadding="0" cellspacing="0" border="0" id="table" class="sortable" width="700">
<caption>
{#success_orders#}: <b>{$closed_sales|money_format} {$conf.currency}</b><br/>
{#total_orders#}: <b>{$total_sales|money_format} {$conf.currency}</b>
</caption>
<thead>
<tr><th class="desc">{#order_id#}</th><th>Booking ID</th><th>Payment Way</th><th>E-Mail</th><th>Total</th><th>{#date_added#}</th><th title="Is the Payment Completed? This field is auto updated from the Payment Gateway on successful order">Completed</th><th title="This field shows if the transaction is approved by Admin">Approved</th></tr>
</thead>
<tbody>
{foreach from=$orders item=order name=count_items}
<tr class="{cycle values="oddrow,none"}">
<td align="center"><a href="#" onClick="popUp('transactions.php?details={$order.order_id}');return false;" style="cursor:pointer;" title="Click to View Full Transaction Details">{$order.order_id}</a></td>
<td align="center"><a href="#" onClick="popUp('bookings.php?id={$order.booking_id}');return false;" title="Click here to change/view booking details">{$order.booking_id}</a></td>
<td>
{if $order.payment_gw eq "bw"}{#gw_bank_wire#}{/if}
{if $order.payment_gw eq "paypal"}{#gw_paypal#}{/if}
{if $order.payment_gw eq "2checkout"}{#gw_2checkout#}{/if}
{if $order.payment_gw eq "authorize"}{#gw_authorize#}{/if}
</td>
<td>{$order.customer_email}</td>
<td>{$order.total_amount} {$order.currency}</td>
<td>{$order.date_added|date_format:"%d/%b/%Y %H:%M:%S"}</td>
<td align="center"><a href="transactions.php?{if $order.confirmed_by_gw eq "1"}unconfirm={$order.order_id}" title="Change to No">{#answer_yes#}{else}confirm={$order.order_id}" title="Change to Yes">{#answer_no#}{/if}</a></td>
<td align="center"><a href="transactions.php?{if $order.approved_by_admin eq "1"}unapprove={$order.order_id}" title="Change to No">{#answer_yes#}{else}approve={$order.order_id}" title="Change to Yes">{#answer_no#}{/if}</a></td></tr>
{/foreach}
</tbody></table>
{include file="admin/sortable.tpl"}
{else}
{#no_orders#}
{/if}
{include file="admin/footer.tpl"}