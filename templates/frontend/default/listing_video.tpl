<div id="videos" class="tabcontent">
	<h3>{#listings_video#}</h3><br />
	<div id="other_pic">
{foreach from=$videos item=video}
{include file="frontend/$template/flvplayer.tpl"}
{/foreach}		
	</div>
</div>
