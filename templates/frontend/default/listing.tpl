{include file="frontend/$template/header.tpl" title=$title}
<div id="page_body">
{* LOAD IE CSS TABS FIX *}
<!--[if lte IE 6]>
<style>
{literal}
.tabcontentstyle{ /*style of tab content oontainer*/
	 	border-top:1px solid #24618E;
		margin-top:-10px;
		width: 515px;
}
{/literal}
</style>
<![EndIf]-->

{* EOF IE CSS TABS FIX *}
		<div id="wrapper">
			<div id="content">
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
{parse_banner position="center"}
<h2>{if $listing.special}<img src="{$BASE_URL}/images/hot.gif" border="0" style="float:left;"/>&nbsp;&nbsp;{/if}{$listing.title|stripslashes}
{if $listing.stars}
<img src="{$BASE_URL}/images/stars-{$listing.stars}.gif" border="0" style="float:right;"/>
{/if}		

</h2>
<ul id="maintab" class="shadetabs">
<li class="selected"><a href="" rel="details"><span>{#listing_details#}</span></a></li>
{if count_images($listing.listing_id) > "1"}<li><a href="" rel="photos"><span>{#listing_gallery#}</span></a></li>{/if}
{if count_videos($listing.listing_id)}<li><a href="" rel="videos"><span>{#listing_video#}</span></a></li>{/if}
<li><a href="" rel="member"><span>{#listing_contact#}</span></a></li>
{if $listing.allow_reservation}<li><a href="" rel="reservation"><span>{#hint_listing_reserve#}</span></a></li>{/if}
<li><a href="" rel="map"><span>{#listing_map#}</span></a></li>
</ul>
<div class="tabcontentstyle">
{include file="frontend/$template/listing_details.tpl"}
{if count_images($listing.listing_id)}{include file="frontend/$template/listing_gallery.tpl"}{/if}
{if count_videos($listing.listing_id)}{include file="frontend/$template/listing_video.tpl"}{/if}
{include file="frontend/$template/listing_contact.tpl"}
{if $listing.allow_reservation}{include file="frontend/$template/listing_reservation.tpl"}{/if}
{include file="frontend/$template/listing_map.tpl"}
</div>
<script type="text/javascript">
//Start Tab Content script for UL with id="maintab" Separate multiple ids each with a comma.
initializetabcontent("maintab")
</script>

				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>

			</div>
		</div>
</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}