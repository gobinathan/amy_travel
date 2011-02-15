{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#reservations#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
{if count($bookings)}
<table border="0" class="sortable">
<caption>{#reservations#}</caption>
<thead>
<tr><th>{#listing#}</th><th>{#fullname#}</th><th>{#email#}</th><th>Total Price</th><th>Paid Price</th><th>{#from_date#}</th><th>{#to_date#}</th><th>Payment Way</th><th>{#date_added#}</th></tr>
</thead>
<tbody>
{foreach from=$bookings item=reserve}
<tr class="{cycle values="oddrow,none"}" onClick="popUp('bookings.php?id={$reserve.r_id}');" style="cursor:pointer;" title="Click to Review/Change this booking">
<td onMouseOver="showhint('<i><b>{$reserve.listing.title|stripslashes}</b></i><img src={$BASE_URL}/uploads/thumbs/{$reserve.listing.icon} border=0 height=50 width=50 style=float:left; /><br/><img src=../images/stars-{$reserve.listing.stars}.gif border=0><br/>{$reserve.listing.short_description|stripslashes}<br/><br/>{#price#}: {$reserve.listing.price}<br/>{if $reserve.listing.special}<img src={$BASE_URL}/images/hot.gif border=0 /><br/>{/if}{#viewed#}: {$reserve.listing.views} {#times#}', this, event, '150px')">
{$reserve.listing.title|stripslashes}
</td>
<td>{$reserve.first_name} {$reserve.last_name}</td>
<td>{$reserve.email}</td>
<td>{$reserve.total_price} {$reserve.currency}</td>
<td>{$reserve.paid_price} {$reserve.currency}</td>
<td>{$reserve.from_date|date_format:"%d/%b/%Y"}</td>
<td>{$reserve.to_date|date_format:"%d/%b/%Y"}</td>
<td>
{if $reserve.payment_gw eq "cash"}{#gw_cash#}{/if}
{if $reserve.payment_gw eq "bw"}{#gw_bank_wire#}{/if}
{if $reserve.payment_gw eq "paypal"}{#gw_paypal#}{/if}
{if $reserve.payment_gw eq "2checkout"}{#gw_2checkout#}{/if}
{if $reserve.payment_gw eq "authorize"}{#gw_authorize#}{/if}
</td>
<td>{$reserve.date_added|date_format:"%d/%b/%Y %H:%M:%S"}</td>
</tr>
{/foreach}
</tbody></table>
{else}
{#no_reservations#}
{/if}
{include file="admin/footer.tpl"}