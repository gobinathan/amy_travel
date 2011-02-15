{include file="admin/header.tpl"}
<div class="left">
			<h3>{#edit_member#}: {$member.username}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("username", "password", "email", "fullname");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#username#}", "{#password#}", "{#email#}", "{#fullname#}");
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);" ENCTYPE="multipart/form-data">
<img src="{$BASE_URL}/uploads/avatars/{$member.avatar}" border="0" style="float: right;">
<div id="frm">
<label>{#email#}:</label><input type="text" name="email" class="required" value="{$member.email}" onMouseOver="showhint('{#hint_member_email#}', this, event, '150px')"/><br/>
<label for="password">{#password#}:</label><input type="password" name="password" onMouseOver="showhint('{#hint_change_password#}', this, event, '150px')"/><br/>
<label>{#fullname#}:</label><input type="text" name="fullname" class="required" value="{$member.fullname}" onMouseOver="showhint('{#hint_member_fullname#}', this, event, '150px')"/><br/>
<label>{#photo#}:</label><input type="file" name="avatar" onMouseOver="showhint('{#hint_member_photo#}', this, event, '150px')"/><br/>
<label>{#member_email_confirmed#}:</label><input type="checkbox" name="email_confirmed" {if $member.email_confirmed}checked{/if} onMouseOver="showhint('{#hint_member_email_confirm#}', this, event, '150px')"/><br/>
<label>{#member_approved#}:</label><input type="checkbox" name="approved" {if $member.approved}checked{/if} onMouseOver="showhint('{#hint_member_approved#}', this, event, '150px')"/><br/>
</div>
<br/>
<input type="hidden" name="edit_member" value="{$member.member_id}">
<input type="submit" name="submit" value="{#edit_member#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='members.php'" value="{#back_members#}" />
</form>
<br/>
{if count($orders)}
<table border="0" class="sortable">
<caption>
Successful Orders: <b>{$closed_sales|money_format} {$conf.currency}</b><br/>
Orders Total: <b>{$total_sales|money_format} {$conf.currency}</b>
</caption>
<thead>
<tr><th>{#order_id#}</th><th>{#price#}</th><th>{#order_type#}</th><th>{#date_added#}</th><th>Status</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$orders item=order}
<tr class="{cycle values="oddrow,none"}">
<td>{$order.order_id}</td>
<td>{if $order.price != "0"}{$order.price} {$order.currency}{else}{#free#}{/if}</td>
<td>{$order.payment_gw}</td>
<td>{$order.date_added|date_format:"%d/%b/%Y %H:%M:%S"}</td>
<td>{$order.status}</td>
<td>{if $order.approved eq "1"}<a href="orders.php?unapprove={$order.order_id}">{#unapprove#}</a>{else}<a href="orders.php?approve={$order.order_id}">{#approve#}</a>{/if} | {if $order.confirmed eq "1"}<a href="orders.php?unconfirm={$order.order_id}">{#unconfirm#}</a>{else}<a href="orders.php?confirm={$order.order_id}">{#confirm#}</a>{/if} | <a href="#" onClick="DeleteItem('orders.php?delete={$order.order_id}')" title="{#delete_order#}">{#delete#}</a></td></tr>
{/foreach}
</tbody></table>
{else}
{#no_orders#}
{/if}
<br/>
</div>
{include file="admin/footer.tpl"}