<div id="details" class="tabcontent">
{if $config.default_icon != $listing.icon}
<a href="{$BASE_URL}/uploads/images/{$listing.icon}" class="highslide" rel="lightbox-gallery" title="{$image.title|stripslashes}"><img src="{$BASE_URL}/uploads/thumbs/{$listing.icon}" border="0" style="float:right; border:solid 2px #A4AFBD;" /></a>
{else}
<img src="{$BASE_URL}/uploads/thumbs/{$listing.icon}" border="0" style="width:92px;height:73px;float:right; border:solid 2px #A4AFBD;" />
{/if}
<br/>
<div style="width:101px; height:33px; float:right; position:relative; top:54px; left:101px; background-color:#993300; background:url({$BASE_URL}/images/glass.gif) no-repeat">
<div style="margin:9px 0 0 17px; font-size:12px; line-height:16px">
<img style="cursor:pointer;" oncontextmenu="return false;" src="{$BASE_URL}/images/gl1.gif" onclick="increaseFontSize();" onmouseover="this.className='thON';" onmouseout="this.className='';" />&nbsp;&nbsp;
<img style="cursor:pointer;" src="{$BASE_URL}/images/gl2.gif" onclick="decreaseFontSize();" onmouseover="this.className='thON';" onmouseout="this.className='';" />&nbsp;&nbsp;
<img style="cursor:pointer;" src="{$BASE_URL}/images/gl3.gif" onclick="normalFontSize();" onmouseover="this.className='thON';" onmouseout="this.className='';" />
</div>
</div>
		<p style="float:right;position:relative;top:90px;left:190px;">
		<a style="cursor:pointer;" onClick="popUp('{$baseurl}/listing/{$listing.uri}/send')" title="{#hint_listing_send#}"><img src="{$BASE_URL}/images/send_small.gif" border="0" alt="{#hint_listing_send#}" /></a> 
		<a style="cursor:pointer;" onClick="javascript:window.open('{$baseurl}/listing/{$listing.uri}/print', 'print_page', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=600,height=700,left=250,top=60');" title="{#hint_listing_print#}"><img src="{$BASE_URL}/images/print_small.gif" border="0" alt="{#hint_listing_print#}" /></a>
	{if in_favourites($listing.listing_id)}
		<a style="cursor:pointer;" onClick="window.location='{$baseurl}/favourites/remove/{$listing.listing_id}'" title="{#fav_remove#}"><img src="{$BASE_URL}/images/remove_from_favourites.gif" border="0" alt="{#fav_remove#}" /></a> 
	{else}
		<a style="cursor:pointer;" onClick="window.location='{$baseurl}/favourites/add/{$listing.listing_id}'" title="{#fav_add#}"><img src="{$BASE_URL}/images/add_to_favourites.gif" border="0" alt="{#fav_add#}"/></a> 
	{/if}
		</p>				
<div id="pdetails">
{if $listing.price > "0"}<label for="price">{#price#}:</label>&nbsp;&nbsp;&nbsp; <p class="price">{$listing.price|money_format} {$listing.currency} {if $listing.price_desc}/ {$listing.price_desc}{/if}</p><br />{/if}
</div>
<br/><div id="fulldescription">
{$listing.description|stripslashes}
</div>
<br /><br />
<div id="adescription">
<h3>{#additional_information#}</h3>
{foreach from=$types_c item=type_c}
	{if count($type_c.types)}
	<ul>
	<b>{$type_c.title}</b>	
	{foreach from=$type_c.types item=type}
		<li>{$type.title}</li>
	{/foreach}
	</ul>
	{/if}
{/foreach}
<br/>{#listing_views#}: {$listing.views} {#times#}<br/>
</div>

</div>
