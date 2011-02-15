{include file="frontend/$template/header.tpl" title=$title}
		<div id="wrapper">
			<div id="content">
					{include file="frontend/$template/search_box.tpl"}
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
<h2>{#payment_completed#}</h2>
<br/>
<fieldset><legend>{#order_details#}</legend>
{#order_id#}: {$order.order_id}<br/>
{#username#}: {$member.username}<br/>
{#email#}: {$member.email}<br/>
{#order_amount#}: {$order.price} {$order.currency}<br/>
{#credit_plan#}: {$credit_plan.title|stripslashes}<br/>
</fieldset>
<br/>
{if $order.approved eq "0"}
{#payment_not_approved#}
{/if}
<br/>
<b>{#plan_features_msg#}</b><br/> 
<ul>
{if $credit_plan.access_require_approval}{#plan_require_approval#}<br/>{/if}
{if $credit_plan.access_limit_listings}{#plan_limit_listings#} {$credit_plan.access_listings_count}<br/>{/if}
{if $credit_plan.access_editor}{#plan_editor#}<br/>{/if}
{if $credit_plan.access_limit_images}{#plan_limit_images#} {$credit_plan.access_images_count}<br/>{/if}
{if $credit_plan.access_special}{#plan_special#} {$credit_plan.access_special_count}<br/>{/if}
{if $credit_plan.access_gmap}{#plan_gmap#}<br/>{/if}
{if $credit_plan.access_period}{#plan_period#}<br/>{/if}
{if $credit_plan.access_contacts}{#plan_contacts#}<br/>{/if}
{if $credit_plan.access_langs}{#plan_langs#} {$credit_plan.access_langs}<br/>{/if}
</ul>
				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
			</div>
		</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}
