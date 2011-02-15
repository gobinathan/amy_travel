{include file="frontend/$template/header.tpl" title=$page_title}
		<div id="wrapper">
			<div id="content">
					{include file="frontend/$template/search_box.tpl"}
					<center>{parse_banner position="center"}</center>
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
<h2>{$page_title}</h2>
			{include file="errors.tpl" errors=$error error_count=$error_count}
<br/>			
{if count($orders)}
<table cellpadding="0" cellspacing="0" border="0" id="table" class="sortable">
<caption>{#menu_orders#}</caption>
<thead>
<tr><th>{#order_id#}</th><th>{#credit_plan#}</th><th>{#order_amount#}</th><th>{#payment_gateway#}</th><th>{#status#}</th><th>{#date_added#}</th></tr>
</thead>
<tbody>
{foreach from=$orders item=order name=count_items}
<tr>
<td>{$order.order_id}</td>
<td><span onMouseOver="showhint('', this, event, '150px')">{$order.plan.title|stripslashes}</span></td>
<td>{$order.price} {$order.currency}</td>
<td>{$order.payment_gw}</td>
<td><span onMouseOver="showhint('{$order.status}', this, event, '150px')">{if $order.approved eq "1"}{#approved#}<br/>{else}{#not_approved#}<br/>{/if}{if $order.confirmed eq "1"}{#confirmed#}{else}{#not_confirmed#}{/if}</span></td>
<td>{$order.date_added|date_format:"%d/%b/%Y %H:%M:%S"}</td>
</tr>
{/foreach}
</tbody></table>
{include file="frontend/$template/sortable.tpl"}
{else}
{#no_listings#}
{/if}
				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
			</div>
		</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}
