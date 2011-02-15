<font size="2"><i><b>{$listing.title|stripslashes|truncate:50:"...":true}</b></i></font><img src="{$BASE_URL}/uploads/thumbs/{$listing.icon}" border=0 height=60 width=70 style="float: left;"/><br/>
{$listing.short_description|stripslashes|truncate:50:"...":true}<br/>
{#mls#}: {$listing.mls}<br/>
{#country#}: {country2name id=$listing.country_id lang=$language}<br/>
{#city#}: {$listing.city}<br/>
{if $listing.price > "0"}{#price#}: {$listing.price|money_format} {$listing.currency} {if $listing.price_desc}/ {$listing.price_desc}{/if}<br/>{/if}
{if $listing.special}<img src={$BASE_URL}/images/hot.gif border=0 /><br/>{/if}
<br/>
<a href="{$baseurl}/listing/{$listing.uri}" target="_blank" title="{#listing_details#}"><b>{#listing_details#}</b></a>
