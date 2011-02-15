{include file="frontend/$template/header.tpl" title=$title}
{* LOAD IE CSS TABS FIX *}
<!--[if lte IE 6]>
<style>
{literal}
.tabcontentstyle{ /*style of tab content oontainer*/
	 	border-top:1px solid #24618E;
		margin-top:-10px;
		width: 515px;
}
{/literal}
</style>
<![EndIf]-->

{* EOF IE CSS TABS FIX *}
		<div id="wrapper">
			<div id="content">
					{include file="frontend/$template/search_box.tpl"}
					<center>{parse_banner position="center"}</center>
{* START NEWS *}
{if count($news)}
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
{foreach from=$news item=nw}			
					<div class="new-offer">
							<h2>{$nw.title|stripslashes}</h2>
							<p>{$nw.full_article|stripslashes}</p>
					</div>
					<div class="hr"><hr /></div>
{/foreach}
				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
{/if}
{* EOF NEWS *}
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
{* START LISTING TAB BOXES *}
<ul id="maintab" class="shadetabs">
<li class="selected"><a href="" rel="special_listings"><span>{#special_listings#}</span></a></li>
<li><a href="" rel="latest_listings"><span>{#latest_listings#}</span></a></li>
<li><a href="" rel="popular_listings"><span>{#popular_listings#}</span></a></li>
</ul>
<div class="tabcontentstyle">
{* START SHOW SPECIAL OFFERS *}
{if count($top_listings)}
<div id="special_listings" class="tabcontent">
{foreach from=$top_listings item=top_listing}
					<div class="new-offer">
						<div class="house"><a href="{$baseurl}/listing/{$top_listing.uri}"><img src="{$BASE_URL}/uploads/thumbs/{$top_listing.icon}" alt="" title="" style="width:92px;height:73px;" /></a></div>
						<div class="description">
							<h2><a href="{$baseurl}/listing/{$top_listing.uri}">{$top_listing.title|stripslashes}</a> {if $top_listing.special}&nbsp;&nbsp;<img src="{$BASE_URL}/images/hot.gif" border="0" />{/if}</h2>
<i>
{if $top_listing.location_id}<label for="location">{#location#}:</label> {location2name id=$top_listing.location_id}{/if}
{if $top_listing.city}<label for="city"> | {#city#}:</label> {$top_listing.city}{/if}
{if $top_listing.internal_area}<label for="livingarea"> | {#internal_area#}:</label> {$top_listing.internal_area} m2{/if}
{if $top_listing.rooms}<label> | {#rooms#}:</label> {$top_listing.rooms}{/if}
</i><br/><br/>
							<p>{$top_listing.short_description|stripslashes}</p>
							{if $top_listing.price > "0"}<p class="price"><a href="{$baseurl}/listing/{$top_listing.uri}">{$top_listing.price|money_format} {$top_listing.currency} {if $top_listing.price_desc}/ {$top_listing.price_desc}{/if}</a></p>{/if}
<br/><i>{#date_added#}: {$top_listing.added_date|date_format:"%d/%b/%Y"}</i>
						</div>
					</div>
					<div class="hr"><hr /></div>
{/foreach}
</div>
{/if}
{* START SHOW LATEST OFFERS *}
{if count($latest_listings)}
<div id="latest_listings" class="tabcontent">
{foreach from=$latest_listings item=new_listing}
					<div class="new-offer">
						<div class="house"><a href="{$baseurl}/listing/{$new_listing.uri}"><img src="{$BASE_URL}/uploads/thumbs/{$new_listing.icon}" alt="" title="" style="width:92px;height:73px;" /></a></div>
						<div class="description">
							<h2><a href="{$baseurl}/listing/{$new_listing.uri}">{$new_listing.title|stripslashes}</a> {if $new_listing.special}&nbsp;&nbsp;<img src="{$BASE_URL}/images/hot.gif" border="0" />{/if}</h2>
<i>
{if $new_listing.location_id}<label for="location">{#location#}:</label> {location2name id=$new_listing.location_id}{/if}
{if $new_listing.city}<label for="city"> | {#city#}:</label> {$new_listing.city}{/if}
{if $new_listing.internal_area}<label for="livingarea"> | {#internal_area#}:</label> {$new_listing.internal_area} m2{/if}
{if $new_listing.rooms}<label> | {#rooms#}:</label> {$new_listing.rooms}{/if}
</i><br/><br/>
							<p>{$new_listing.short_description|stripslashes}</p>
							{if $new_listing.price > "0"}<p class="price"><a href="{$baseurl}/listing/{$new_listing.uri}">{$new_listing.price|money_format} {$new_listing.currency} {if $new_listing.price_desc}/ {$new_listing.price_desc}{/if}</a></p>{/if}
<br/><i>{#date_added#}: {$new_listing.added_date|date_format:"%d/%b/%Y"}</i>
						</div>
					</div>
					<div class="hr"><hr /></div>
{/foreach}
</div>
{/if}
{* START SHOW POPULAR OFFERS *}
{if count($popular_listings)}
<div id="popular_listings" class="tabcontent">
{foreach from=$popular_listings item=p_listing}
					<div class="new-offer">
						<div class="house"><a href="{$baseurl}/listing/{$p_listing.uri}"><img src="{$BASE_URL}/uploads/thumbs/{$p_listing.icon}" alt="" title="" style="width:92px;height:73px;" /></a></div>
						<div class="description">
							<h2><a href="{$baseurl}/listing/{$p_listing.uri}">{$p_listing.title|stripslashes}</a> {if $p_listing.special}&nbsp;&nbsp;<img src="{$BASE_URL}/images/hot.gif" border="0" />{/if}</h2>
<i>
{if $p_listing.location_id}<label for="location">{#location#}:</label> {location2name id=$p_listing.location_id}{/if}
{if $p_listing.city}<label for="city"> | {#city#}:</label> {$p_listing.city}{/if}
{if $p_listing.internal_area}<label for="livingarea"> | {#internal_area#}:</label> {$p_listing.internal_area} m2{/if}
{if $p_listing.rooms}<label> | {#rooms#}:</label> {$p_listing.rooms}{/if}
</i><br/><br/>
							<p>{$p_listing.short_description|stripslashes}</p>
							{if $p_listing.price > "0"}<p class="price"><a href="{$baseurl}/listing/{$p_listing.uri}">{$p_listing.price|money_format} {$p_listing.currency} {if $p_listing.price_desc}/ {$p_listing.price_desc}{/if}</a></p>{/if}
							<br/><i>{#listing_views#}: {$p_listing.views} {#times#}</i>
						</div>
					</div>
					<div class="hr"><hr /></div>
{/foreach}
</div>
{/if}
</div>
<script type="text/javascript">
//Start Tab Content script for UL with id="maintab" Separate multiple ids each with a comma.
initializetabcontent("maintab")
</script>
{* EOF LISTING TAB BOXES *}

				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
			</div>
		</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}
