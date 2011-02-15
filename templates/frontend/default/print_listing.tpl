<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$language_encoding}" />
  <link rel="stylesheet" type="text/css" href="{$BASE_URL}/templates/frontend/{$template}/style.css" />
  <script src="{$BASE_URL}/js/main.js" type="text/javascript"></script>
<title>{$title|stripslashes} - {$conf.site_title|stripslashes}</title>
<meta name="keywords" content="{$conf[meta_keywords]}" />
<meta name="description" content="{$conf[meta_description]}" />
</head>
<body class="print" onload='javascript: window.print();'> 
{if $listing.special}<img src="{$BASE_URL}/images/hot.gif" border="0" style="float:left;"/>{/if}
<h2>{$listing.title|stripslashes}</h2>&nbsp;&nbsp;<br/>
<div id="indexcontent">
<div id="pdetails">
<label for="mls">{#mls#}:</label>&nbsp;&nbsp;&nbsp; {$listing.mls}<br />
<label for="price">{#price#}:</label> {$listing.price|money_format} {$listing.currency}<br />
<label for="type">{#category#}:</label> {category2name id=$listing.cat_id}<br/>
<label for="country">{#country#}:</label>&nbsp;&nbsp;&nbsp; {country2name id=$listing.country_id}<br/>
{if $listing.state_id}<label for="state">{#state#}:</label> {state2name id=$listing.state_id}<br/>{/if}
<label for="location">{#location#}:</label> {location2name id=$listing.location_id}<br/>
<label for="city">{#city#}:</label>&nbsp;&nbsp;&nbsp; {$listing.city}<br/>
{if $listing.address}<label for="address">{#address#}:</label> {$listing.address}<br />{/if}
{if $listing.zip}<label for="zip">{#zip#}:</label>&nbsp;&nbsp;&nbsp; {$listing.zip}<br />{/if}
{if $listing.internal_area}<label for="livingarea">{#internal_area#}:</label> {$listing.internal_area} m2<br />{/if}
{if $listing.external_area}<label for="plotsize">{#external_area#}:</label> {$listing.external_area} m2<br />{/if}
{if $listing.bedrooms}<label for="bedrooms">{#bedrooms#}:</label> {$listing.bedrooms}<br />{/if}
{if $listing.bathrooms}<label for="bathrooms">{#bathrooms#}:</label> {$listing.bathrooms}<br />{/if}
{if $listing.garage}<label for="garage">{#garages#}:</label> {$listing.garage}<br />{/if}
{if $listing.built}<label>{#year_built#}:</label> {$listing.built}<br/>{/if}
{if $listing.floors}<label>{#floors#}:</label> {$listing.floors}<br/>{/if}
{if $listing.rooms}<label>{#rooms#}:</label> {$listing.rooms}<br/>{/if}
</div>

<div id="fulldescription">
{$listing.description|stripslashes}
</div>
<br /><br />
<div id="adescription">
{* START "SHOW IMAGES" *}
{if count($images)}
{foreach from=$images item=image}
<img src="{$BASE_URL}/uploads/thumbs/{$image.file}" border="0" title="{#image_title#}"/>
{/foreach}
{/if}	  
{* EOF "SHOW IMAGES" *}
<br/>
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
</div>

</div>