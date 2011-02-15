{if count($smarty.session.favourites)}
<br/>	
{include file="frontend/$template/round_corner_top.tpl"}
<a href="{$baseurl}/favourites" title="Favourites"><img src="{$BASE_URL}/images/favourites_cart.gif" border="0" alt="Favourites" style="float:left;"/></a>
&nbsp;&nbsp;<a href="#" onClick="doSlide('favourite_items');return false;" title="View Favourites">{#favourites#} ({$smarty.session.favourites_count})</a>
<br/>
<div id="favourite_items" style="display:none;">
	{foreach from=$smarty.session.favourites item=fav}
<br/>
		<a title="{#fav_remove#}" href="{$baseurl}/favourites/remove/{$fav.listing_id}" /><img src="{$BASE_URL}/images/delete.gif" border="0" alt="{#fav_remove#}" style="float:left;"/></a>&nbsp;&nbsp;
		<b><a href="{$baseurl}/listing/{$fav.uri}" onMouseOver="showhint('<img src={$BASE_URL}/uploads/thumbs/{$fav.icon} border=0 height=80 width=80 style=float:left; />', this, event, '80px')">{$fav.title|stripslashes}</a></b><br/>
		{if $fav.price > "0"}{#price#}: <font color="green"><b>{$fav.price|money_format} {$fav.currency}</b> {if $fav.price_desc}/ {$fav.price_desc}{/if}</font>{/if}
<br/>
	{/foreach}
</div>
{include file="frontend/$template/round_corner_bottom.tpl"}
<br/>
{/if}
