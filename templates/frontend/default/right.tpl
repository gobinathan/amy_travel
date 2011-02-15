	<div id="sidebar-right">
{parse_banner position="right"}
{* START LANGUAGES BOX *}
{include file="frontend/$template/round_corner_top.tpl"}
{if count($languages_array) > "1"}
<div id="langmenu">
<ul id="item1">
<li class="top"><img src="{$BASE_URL}/uploads/flags/{$language}.gif" alt="" border="0" /> {#choose_language#}</li>
{foreach from=$languages_array item=lang}
	{if $lang.lang_name != $language}
<li class="item"><a href="{$BASE_URL}{if $lang.lang_name != $default_lang}/{$lang.lang_name}{/if}{$ref_url}" title="{$lang.lang_title}"><img class="img-flag" src="{$BASE_URL}/uploads/flags/{$lang.lang_name}.gif" alt="{$lang.lang_title}" border="0" />&nbsp;&nbsp;{$lang.lang_title}</a></li>
	{/if}	
{/foreach}
</ul><p class="clear">
</div>
{/if}	
<br/><br/>
{* EOF LANGUAGES BOX *}
{* START CURRENCY BOX *}
<br/>{#currency#}
<select name="currency" onChange='window.location = "{$baseurl}/currency/" + this.value;'>
{foreach from=$currencies item=currency}
<option value="{$currency.code}" {if $conf.currency == $currency.code}selected{/if}>{$currency.title}</option>
{/foreach}
</select>
{include file="frontend/$template/round_corner_bottom.tpl"}
{* EOF CURRENCY BOX *}

{* START FAVOURITES BOX *}
{include file="frontend/$template/favourites_box.tpl"}
{* EOF FAVOURITES BOX *}
{* START MEMBER BOX *}
{include file="frontend/$template/member_box.tpl"}
{* EOF MEMBER BOX *}
{* START FEATURED LISTINGS BOX *}
		<div class="hr"><hr /></div>
<span class="title"><center>{#special_listings#}</center></span>
<MARQUEE onmouseover=this.stop() onmouseout=this.start() scrollAmount="1" scrollDelay="05" direction="down" height="350">
		{foreach from=$top_listings item=featured_listing}
	<div id="shortd">
		<a href="{$baseurl}/listing/{$featured_listing.uri}"><b>{$featured_listing.title|stripslashes}</b><br/>
		<img src="{$BASE_URL}/uploads/thumbs/{$featured_listing.icon}" border="0" /><img src="{$BASE_URL}/images/hot.gif" border="0" style="float:right;"/>
		</a>
		<br />	
		{$featured_listing.short_description|stripslashes}<br />
		{if $featured_listing.price > "0"}<i>{#price#}: <font color="red">{$featured_listing.price|money_format} {$featured_listing.currency}</font> {if $featured_listing.price_desc}/ {$featured_listing.price_desc}{/if}</i><br />{/if}<br />
	</div>
      {/foreach}
</MARQUEE>
{* EOF FEATURED LISTINGS BOX *}				
	</div>
