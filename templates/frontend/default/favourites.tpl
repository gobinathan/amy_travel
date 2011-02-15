{include file="frontend/$template/header.tpl" title=$title}
		<div id="wrapper">
			<div id="content">
					{include file="frontend/$template/search_box.tpl"}

				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
<h2>{$title}</h2>
{parse_banner position="center"}
<br/>
<center><input class="submit" name="Button" type="button" onClick="javascript:window.location='{if $smarty.server.HTTP_REFERER}{$smarty.server.HTTP_REFERER}{else}{$baseurl}{/if}'" value="{#continue#}" /></center><br/>
{if count($favourites)}
{foreach from=$favourites item=fav}
					<div class="new-offer" onMouseOver="hiding('listing_{$fav.listing_id}_menu')" onMouseOut="hiding('listing_{$fav.listing_id}_menu')">
						<div class="house"><a href="{$baseurl}/listing/{$fav.uri}"><img src="{$BASE_URL}/uploads/thumbs/{$fav.icon}" alt="" title="" style="width:92px;height:73px;" /></a></div>
						<div class="description">
							<h2><a href="{$baseurl}/listing/{$fav.uri}">{$fav.title|stripslashes}</a>
{if $fav.stars}<img src="{$BASE_URL}/images/stars-{$fav.stars}.gif" border="0"/>{/if}
							<div id="listing_{$fav.listing_id}_menu" style="display:none;float:right;"><a href="{$baseurl}/favourites/remove/{$fav.listing_id}" title="{#fav_remove#}"><img src="{$BASE_URL}/images/remove_from_favourites.gif" border="0" alt="{#fav_remove#}"></a></div>
							</h2>
<i>
{if $fav.location_id}<label for="location">{#location#}:</label> {location2name id=$fav.location_id}{/if}
{if $fav.city}<label for="city"> | {#city#}:</label> {$fav.city}{/if}
{if $fav.internal_area}<label for="livingarea"> | {#internal_area#}:</label> {$fav.internal_area} m2{/if}
{if $fav.rooms}<label> | {#rooms#}:</label> {$fav.rooms}{/if}
</i><br/><br/>

							<p>{$fav.short_description|stripslashes}</p>
							{if $fav.price > "0"}<p class="price"><a href="{$baseurl}/listing/{$fav.uri}">{$fav.price|money_format} {$fav.currency} {if $fav.price_desc}/ {$fav.price_desc}{/if}</a></p>{/if}
						</div>
					</div>
					<div class="hr"><hr /></div>
{/foreach}
{else}
No favourites
<br/><br/>
{/if}
				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>

	</div>
</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}