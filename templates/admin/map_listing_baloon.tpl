<font size="2"><i><b>{$listing.title|stripslashes}</b></i></font><img src="{$BASE_URL}/uploads/thumbs/{$listing.icon}" border=0 height=60 width=70 style="float: left;"/><br/>
{$listing.short_description|stripslashes}<br/><br/><br/>
{#category#}: {category2name id=$listing.cat_id lang=$language}<br/>
{#price#}: {$listing.price}<br/>
{#city#}: {$listing.city}<br/>
{#country#}: {country2name id=$listing.country_id lang=$language}<br/>
{#location_type#}: {location2name id=$listing.location_id lang=$language}<br/>
{#date_added#}: {$listing.added_date|date_format:"%d/%b/%Y %H:%M:%S"}<br/>
{#added_by#}: {if $listing.member_id == "0"}
{admin2name id=$listing.added_by}
{else}
<a href="members.php?all_listings={$listing.member_id}" target="_main">{member2name id=$listing.member_id}</a>
{/if}
<br/>
{if $listing.special}<img src={$BASE_URL}/images/hot.gif border=0 /><br/>{/if}
{#listing_views#}: {$listing.views} {#times#}
<br/><br/>
<a href="{$BASE_URL}/listing/{$listing.uri}" target="_blank" title="{#preview_listing#}"><img src="{$BASE_URL}/admin/images/preview.gif" border="0" /></a> | 
<a href="listings.php?edit={$listing.listing_id}&edit_lang={$language}" title="{#edit_listing#}" target="_main"><img src="{$BASE_URL}/admin/images/edit.png" border=0 /></a> | 
<a href="#" onClick="DeleteItem('listings.php?delete={$listing.listing_id}')" title="{#delete_listing#}" target="_main"><img src="{$BASE_URL}/admin/images/delete.png" border=0></a> | 
<a href="images.php?id={$listing.listing_id}" title="{#show_images#} ({count_images listing_id=$listing.listing_id})" target="_main"><img src="{$BASE_URL}/admin/images/manage_images.gif" border=0 /></a>
