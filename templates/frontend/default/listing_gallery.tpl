	<script type="text/javascript" src="{$BASE_URL}/js/gallery/mootools.js"></script>
	<script type="text/javascript" src="{$BASE_URL}/js/gallery/slimbox.js"></script>
	<link rel="stylesheet" href="{$BASE_URL}/js/gallery/css/slimbox.css" type="text/css" media="screen" />
	<div id="photos" class="tabcontent">
	<h3>{#listing_gallery#}</h3><br />
	<div id="other_pic">
{if count($images) > "1"}
{foreach from=$images item=image}
<a href="{$BASE_URL}/uploads/images/{$image.file}" class="highslide" rel="lightbox-gallery" title="{$image.title|stripslashes}"><img src="{$BASE_URL}/uploads/thumbs/{$image.file}" border="0" /></a>
{/foreach}		
{/if}
	</div>
</div>
