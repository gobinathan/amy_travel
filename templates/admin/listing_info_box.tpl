<div style="margin-left:10px;float:right;margin-top:-120px;">
<fieldset style="padding-left:5px;width:210px;"><legend><b>{#listing#}</b></legend>
<i><b>{$listing.title|stripslashes}</b></i><a href="{$BASE_URL}/listing/{$listing.uri}" target="_blank" title="{#preview_listing#}"><img src={$BASE_URL}/uploads/thumbs/{$listing.icon} border=0 height=50 width=50 style="float:left;padding:5px;" /></a>
<br/>{$listing.short_description|stripslashes}<p style="clear:left;"><br/>
{#category#}: {category2name id=$listing.cat_id lang=$language}<br/>
{#location_type#}: {location2name id=$listing.location_id lang=$language}<br/>
{#country#}: {country2name id=$listing.country_id lang=$language}<br/>
{#city#}: {$listing.city}<br/>
{#date_added#}: {$listing.added_date|date_format:"%b %d, %Y"}<br/>
{#added_by#}: {admin2name id=$listing.added_by}<br/>
</fieldset>
</div>
