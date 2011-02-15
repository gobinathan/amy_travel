{include file="frontend/$template/header.tpl" title=$title}
<div id="page_body" class="page_body">
		<div id="wrapper">
			<div id="content">
					<center>{parse_banner position="center"}</center>
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
<h1 align="center">{#review_confirm#}</h1>
<br/>
<fieldset><legend>&nbsp;<a href="{$baseurl}/listing/{$blisting.uri}" target="_blank">{$blisting.title|stripslashes}</a>&nbsp;</legend>
&nbsp;&nbsp;&nbsp;<a href="{$baseurl}/listing/{$blisting.uri}" target="_blank"><img src={$BASE_URL}/uploads/thumbs/{$blisting.icon} border=0 height=50 width=50 style="float:left"/></a>&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;{$blisting.short_description|stripslashes}<br/>

</fieldset><br/>
<fieldset><legend>{#booking_details#}</legend>
<form method="post" action="{$baseurl}/booking/checkout">
{if $res.existing_customer AND $member.email != $res.email}
	<b>{$res.email}</b> {#existing_login#} <a href="#" onClick="javascript:toggle_lightbox('{$baseurl}/login_box/{$res.email}', 'progress_bar_lightbox'); return false;">{#proceed2login#}</a> <br/>
{else}
	{#email_address#}: {$res.email} 
{/if}
<br/>
{#first_name#}: {$res.first_name}<br/>
{#last_name#}: {$res.last_name}<br/>
{#city#}: {$res.city}<br/>
{if $res.phone}{#contact_number#}: {$res.phone}<br/>{/if}
{#arrival_date#}: {$res.from_date|date_format:"%d/%b/%Y"}<br/>
{#departure_date#}: {$res.to_date|date_format:"%d/%b/%Y"}<br/>
{#count_rooms#}: {$res.count_rooms}<br/>
{#count_people#}: {$res.count_people}<br/>
{#adults#}: {$res.count_adults}<br/>
{#kids1#}: {$res.count_kids1}<br/>
{#kids2#}: {$res.count_kids2}<br/>
</fieldset>
<fieldset><legend>{#payment_details#}</legend>
{#duration#}: {$res.days} {#days#}<br/>
{if $res.total_price > "0"}
{if $blisting.price_set eq "package" AND $res.price_base_per_day > "0"}
{#base_price_tip#}: {$res.price_base_per_day} {$res.currency}<br/>
{if $res.total_discount > 0}
<fieldset><legend>{#discounts#}</legend>
{if $res.price_per_day_people_discount}{#people_discount_tip#}: {$res.price_per_day_people_discount} {$res.currency}<br/>{/if}
{if $res.price_per_day_kids_discount}{#kids_discount_tip#}: {$res.price_per_day_kids_discount} {$res.currency}<br/>{/if}
{if $res.price_per_day_room_discount}{#rooms_discount_tip#}: {$res.price_per_day_room_discount} {$res.currency}<br/>{/if}
<b>{#total_discounts#}: {$res.total_discount} {$res.currency}<br/></b> 
</fieldset>
{/if}
<br/><br/>
{/if}
<div align="center">
<h1>{#total_price#}: {$res.total_price} {$res.currency}</h1><br/>
{* PAYMENT *}
{if $blisting.allow_payment OR $blisting.require_payment}
{#payment_gateway#}:<br/><select name="payment_gw" style="text-align:center;font-size:15px;">
{if !$blisting.require_payment}<option value="cash">{#gw_cash#}</option>{/if}
{if $payment_gw.paypal_enabled}<option value="paypal">{#gw_paypal#}</option>{/if}
{if $payment_gw.paypal_subscription_enabled}<option value="paypal">{#gw_paypal_subscription#}</option>{/if}
{if $payment_gw.2checkout_enabled}<option value="2checkout">{#gw_2checkout#}</option>{/if}
{if $payment_gw.authorize_enabled}<option value="authorize">{#gw_authorize#}</option>{/if}
{if $payment_gw.bw_enabled}<option value="bw">{#gw_bank_wire#}</option>{/if}
</select><br/>
</div>
{/if}
<br/>
{else}
<br/>
<div align="center">
{#no_price_notice#}
</div>
{/if}
</fieldset><br/>
<center>
{if $res.existing_customer AND $member.email != $res.email}
<input type="button" class='quotebttn' onClick="javascript:toggle_lightbox('{$baseurl}/login_box/{$res.email}', 'progress_bar_lightbox'); return false;" value="{#proceed2login#}">
{else}
<input type="submit" name="confirm" value="{if $res.total_price > "0" AND $blisting.allow_payment OR $blisting.require_payment}{#confirm_checkout#}{else}{#confirm#}{/if}" class='quotebttn'>
{/if}<br/><br/><br/><br/><br/>
</form>
<a href="{$baseurl}/booking/change">{#back_edit#}</a>
</center>
				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
			</div>
		</div>
</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}

