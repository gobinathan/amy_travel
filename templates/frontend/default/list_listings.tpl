{include file="frontend/$template/header.tpl" title=$title}
		<div id="wrapper">
			<div id="content">
					{include file="frontend/$template/search_box.tpl"}
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
<h2>{$title}</h2>
<br/>
{parse_banner position="center"}
<br/>
{$selected_category.description|stripslashes}
<br/>
{* START SHOW OFFERS *}
{if count($listings)}
{* ---------------------- START SORT  ----------------------- *}
{#sort_by#}: 
{* SORT BY PRICE *}
{if $smarty.session.sortby eq "price"}
	{if $smarty.session.sortby_w eq "asc"}
		<a href="{$page_uri}/sortby/price/desc"><img src="{$BASE_URL}/images/up_arrow.jpg" border="0">
	{else}
		<a href="{$page_uri}/sortby/price/asc"><img src="{$BASE_URL}/images/down_arrow.jpg" border="0">
	{/if}
	<b>{#price#}</b></a> | 
{else}
	<a href="{$page_uri}/sortby/price">{#price#}</a> | 
{/if}
{* SORT BY STARS *}
{if $smarty.session.sortby eq "stars"}
	{if $smarty.session.sortby_w eq "asc"}
		<a href="{$page_uri}/sortby/stars/desc"><img src="{$BASE_URL}/images/up_arrow.jpg" border="0">
	{else}
		<a href="{$page_uri}/sortby/stars/asc"><img src="{$BASE_URL}/images/down_arrow.jpg" border="0">
	{/if}
	<b>{#stars#}</b></a> | 
{else}
	<a href="{$page_uri}/sortby/stars">{#stars#}</a> | 
{/if}
{* SORT BY DATE *}
{if $smarty.session.sortby eq "date"}
	{if $smarty.session.sortby_w eq "asc"}
		<a href="{$page_uri}/sortby/date/desc"><img src="{$BASE_URL}/images/up_arrow.jpg" border="0">
	{else}
		<a href="{$page_uri}/sortby/date/asc"><img src="{$BASE_URL}/images/down_arrow.jpg" border="0">
	{/if}
	<b>{#date_added#}</b></a> | 
{else}
	<a href="{$page_uri}/sortby/date">{#date_added#}</a> | 
{/if}
{* SORT BY LOCATION *}
{if $smarty.session.sortby eq "location"}
	{if $smarty.session.sortby_w eq "asc"}
		<a href="{$page_uri}/sortby/location/desc"><img src="{$BASE_URL}/images/up_arrow.jpg" border="0">
	{else}
		<a href="{$page_uri}/sortby/location/asc"><img src="{$BASE_URL}/images/down_arrow.jpg" border="0">
	{/if}
	<b>{#location#}</b></a> | 
{else}
	<a href="{$page_uri}/sortby/location">{#location#}</a> | 
{/if}
{* SORT BY CITY *}
{if $smarty.session.sortby eq "city"}
	{if $smarty.session.sortby_w eq "asc"}
		<a href="{$page_uri}/sortby/city/desc"><img src="{$BASE_URL}/images/up_arrow.jpg" border="0">
	{else}
		<a href="{$page_uri}/sortby/city/asc"><img src="{$BASE_URL}/images/down_arrow.jpg" border="0">
	{/if}
	<b>{#city#}</b></a>
{else}
	<a href="{$page_uri}/sortby/city">{#city#}</a>
{/if}

<br/>
{* ---------------------- END SORT  ----------------------- *}

<br/>
<div class="pagination"><ul>
<b>{pagination start=$page_index perpage=$page_limit total=$page_total output_c="<li class=\"currentpage\">%pag%</li>" output="<li><a href=\"$page_uri/page/%st%\">%pag%</a></li>"}</b>
</ul></div>
<span style="float:right;">{#results#} {$page_index+1} - {$count_page_listings+$page_index}</span>
<br/><br/>
{foreach from=$listings item=dlisting}
					<div class="new-offer" onMouseOver="hiding('listing_{$dlisting.listing_id}_menu')" onMouseOut="hiding('listing_{$dlisting.listing_id}_menu')">
						<div class="house"><a href="{$baseurl}/listing/{$dlisting.uri}"><img src="{$BASE_URL}/uploads/thumbs/{$dlisting.icon}" alt="" title="" style="width:92px;height:73px;"/>		
</a></div>
						<div class="description">
							<h2><a href="{$baseurl}/listing/{$dlisting.uri}">{$dlisting.title|stripslashes}</a> {if $dlisting.special}&nbsp;&nbsp;<img src="{$BASE_URL}/images/hot.gif" border="0" />{/if}
{if $dlisting.stars}<img src="{$BASE_URL}/images/stars-{$dlisting.stars}.gif" border="0" style="float:right"/>{/if}							
	{if in_favourites($dlisting.listing_id)}
							<div id="listing_{$dlisting.listing_id}_menu" style="display:none;float:right;"><a href="{$baseurl}/favourites/remove/{$dlisting.listing_id}" title="{#fav_remove#}"><img src="{$BASE_URL}/images/remove_from_favourites.gif" border="0" alt="{#fav_remove#}"></a></div>
	{else}							
							<div id="listing_{$dlisting.listing_id}_menu" style="display:none;float:right;"><a href="{$baseurl}/favourites/add/{$dlisting.listing_id}" title="{#fav_add#}"><img src="{$BASE_URL}/images/add_to_favourites.gif" border="0" alt="{#fav_add#}"></a></div>
	{/if}					
</h2>
<i>
{if $dlisting.location_id}<label for="location">{#location#}:</label> {location2name id=$dlisting.location_id}{/if}
{if $dlisting.city}<label for="city"> | {#city#}:</label> {$dlisting.city}{/if}
{if $dlisting.internal_area}<label for="livingarea"> | {#internal_area#}:</label> {$dlisting.internal_area} m2{/if}
{if $dlisting.rooms}<label> | {#rooms#}:</label> {$dlisting.rooms}{/if}
</i><br/><br/>
							<p>{$dlisting.short_description|stripslashes}</p>
							{if $dlisting.price > "0"}<p class="price"><a href="{$baseurl}/listing/{$dlisting.uri}">{$dlisting.price|money_format} {$dlisting.currency} {if $dlisting.price_desc}/ {$dlisting.price_desc}{/if}</a></p>{/if}
						</div>
					</div>
					<div class="hr"><hr /></div>
{/foreach}
<div class="pagination"><ul>
<b>{pagination start=$page_index perpage=$page_limit total=$page_total output_c="<li class=\"currentpage\">%pag%</li>" output="<li><a href=\"$page_uri/page/%st%\">%pag%</a></li>"}</b>
</ul></div>
<span style="float:right;">{#results#} {$page_index+1} - {$count_page_listings+$page_index}</span>
{* ELSE SHOW MESSAGE *}
{else}
{#no_listings_found#}
{/if}
<br/><br/>
				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
	</div>
</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}