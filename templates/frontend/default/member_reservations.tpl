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
{if count($reservations)}
<h2>{#reservations#}</h2>
{foreach from=$reservations item=reserve}
<fieldset><legend>{$reserve.booking_id}</legend>
<a href="{$baseurl}/listing/{$reserve.listing.uri}" target="_blank" onMouseOver="showhint('<i><b>{$reserve.listing.title|stripslashes}</b></i><img src={$BASE_URL}/uploads/thumbs/{$reserve.listing.icon} border=0 height=50 width=50 style=float:left; /><br/><img src={$BASE_URL}/images/stars-{$reserve.listing.stars}.gif border=0><br/>{$reserve.listing.short_description|stripslashes}<br/><br/>{#price#}: {$reserve.listing.price}<br/>{if $reserve.listing.special}<img src={$BASE_URL}/images/hot.gif border=0 /><br/>{/if}{#viewed#}: {$reserve.listing.views} {#times#}', this, event, '150px')">{$reserve.listing.title|stripslashes}</a>
<br/><br/>
{#fullname#}: {$reserve.first_name} {$reserve.last_name}<br/>
{#total_price#}: {$reserve.total_price} {$reserve.currency}<br/>
{#paid_price#}: {$reserve.paid_price} {$reserve.currency}<br/>
{#arrival_date#}: {$reserve.from_date|date_format:"%d/%b/%Y"}<br/>
{#departure_date#}: {$reserve.to_date|date_format:"%d/%b/%Y"}<br/>
{#payment_way#}: {if $reserve.payment_gw eq "cash"}{#gw_cash#}{/if}
{if $reserve.payment_gw eq "bw"}{#gw_bank_wire#}{/if}
{if $reserve.payment_gw eq "paypal"}{#gw_paypal#}{/if}
{if $reserve.payment_gw eq "2checkout"}{#gw_2checkout#}{/if}
{if $reserve.payment_gw eq "authorize"}{#gw_authorize#}{/if}
<br/>
{#date_added#}: {$reserve.date_added|date_format:"%d/%b/%Y %H:%M:%S"}<br/>
<b>
{#confirmed_by_client#}: {if $reserve.confirmed_by_client eq "0"}{#answer_no#}{else}{#answer_yes#}{/if}<br/>
{#confirmed_by_admin#}: {if $reserve.confirmed_by_admin eq "0"}{#answer_no#}{else}{#answer_yes#}{/if}<br/>
{#confirmed_by_hotel#}: {if $reserve.confirmed_by_hotel eq "0"}{#answer_no#}{else}{#answer_yes#}{/if}<br/>
</b>
<br/></fieldset><br/>
{/foreach}
{else}
{#no_reservations#}
{/if}
				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
			</div>
		</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}
