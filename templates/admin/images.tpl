{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#show_images#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("image");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#empty_image_file#}");
</script>
{* FILE UPLOAD *}
<script type="text/javascript" src="{$BASE_URL}/js/swfupload/swfupload.js"></script>
<script type="text/javascript" src="{$BASE_URL}/js/swfupload/handlers.js"></script>
<script type="text/javascript">
{literal}
		var swfu;
		window.onload = function () {
			swfu = new SWFUpload({
				// Backend Settings
{/literal}
				upload_url: "{$BASE_URL}/admin/upload.php",	// Relative to the SWF file
				post_params: {literal}{"PHPSESSID":{/literal} "{php} echo session_id(); {/php}{literal}"},

				// File Upload Settings
				file_size_limit : "10 MB",	// 10MB
				file_types : "*.jpg",
				file_types_description : "JPG Images",
				file_upload_limit : "0",

				// Event Handler Settings - these functions as defined in Handlers.js
				//  The handlers are not part of SWFUpload but are part of my website and control how
				//  my website reacts to the SWFUpload events.
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
{/literal}				button_image_url : "{$BASE_URL}/images/page_white_add.png",	// Relative to the SWF file {literal}
				button_placeholder_id : "spanButtonPlaceholder",
				button_width: 180,
				button_height: 18,
{/literal}				button_text : '<span class="button">{#select_images#} <span class="buttonSmall">(10 MB Max)</span></span>', {literal}
				button_text_style : '.button { font-family: Helvetica, Arial, sans-serif; font-size: 12pt; } .buttonSmall { font-size: 10pt; }',
				button_text_top_padding: 0,
				button_text_left_padding: 18,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				
				// Flash Settings
				flash_url : "../js/swfupload/swfupload.swf",

				custom_settings : {
					upload_target : "divFileProgressContainer"
				},
				
				// Debug Settings
				debug: false
			});
		};
{/literal}
</script>
	<div id="page_body" class="page_body">
			<div class="left_box">
{* LISTING IMAGES MENU ICONS *}
<table border="0" cellspacing="5" cellpadding="5">
<tr align="center">
<td><a href="listings.php?edit={$listing.listing_id}"><img src="{$BASE_URL}/admin/images/listings.png" title="{#edit_listing#} {$listing.title|stripslashes}" class="imgfade"  border="0" /></a><br/>{#edit_listing#}</td> 
<td><a href="packages.php?listing_id={$listing.listing_id}"><img src="{$BASE_URL}/admin/images/packages_prices.png" title="{#reservations#}" class="imgfade"  border="0" /></a><br/>{#packages_prices#} ({count_packages listing_id=$listing.listing_id})</td>
<td><a href="images.php?id={$listing.listing_id}"><img src="{$BASE_URL}/admin/images/listing_images.png" {if count_images($listing.listing_id)}onMouseOver="showhint('{count_images_size listing_id=$listing.listing_id}', this, event, '70px')"{/if} title="{#manage_images#} ({count_images listing_id=$listing.listing_id})" class="imgfade"  border="0" /></a><br/>{#manage_images#} ({count_images listing_id=$listing.listing_id})</td>
<td><a href="videos.php?id={$listing.listing_id}"><img src="{$BASE_URL}/admin/images/listing_videos.png" {if count_videos($listing.listing_id)}onMouseOver="showhint('{count_videos_size listing_id=$listing.listing_id}', this, event, '70px')"{/if} title="{#manage_videos#} ({count_videos listing_id=$listing.listing_id})" class="imgfade"  border="0" /></a><br/>{#manage_videos#} ({count_videos listing_id=$listing.listing_id})</td>
<td><img style="cursor:pointer;" src="{$BASE_URL}/admin/images/listing_delete.png" onClick="DeleteItem('listings.php?delete={$listing.listing_id}')" title="{#delete_listing#}" class="imgfade"  border="0" /><br/>{#delete_listing#}</td>
<td><a href="{$BASE_URL}/preview/{$listing.uri}" target="_blank"><img src="{$BASE_URL}/admin/images/listing_preview.jpg" title="{#preview_listing#}" class="imgfade"  border="0" /></a><br/>{#preview_listing#}</td>
</tr>
</table><br/><br/>
{* EOF LISTING IMAGES MENU ICONS *}
{include file="admin/listing_info_box.tpl"}
<div style="float:left">
<link rel="stylesheet" type="text/css" href="{$BASE_URL}/templates/frontend/tabcontent.css" />
<script src="{$BASE_URL}/js/tabcontent.js" type="text/javascript"></script>
<ul id="maintab" class="shadetabs">
<li class="selected"><a href="" rel="extended">{#images_upload_multi#}</a></li>
<li><a href="" rel="simple">{#images_upload_single#}</a></li>
</ul>
<div class="tabcontentstyle" style="border:1px solid gray; width:350px; margin-bottom: 1em; padding: 10px">
<div id="extended" class="tabcontent">
	<form>
		<div style="padding: 5px; display: inline; border: solid 1px #7FAAFF; background-color: #C5D9FF;" onMouseover="showhint('{#hint_swfupload#}', this, event, '150px')">
			<span id="spanButtonPlaceholder"></span>
		</div>
	</form>
	<div id="divFileProgressContainer" style="height: 50px;"></div>
</div>
<div id="simple" class="tabcontent">
<form enctype="multipart/form-data" id="upload_form" action="{$smarty.server.PHP_SELF|xss}" method="post">
	<input type="hidden" name="listing_id" value="{$smarty.get.id}" />
	<input type="file" name="file_1" />
	<input type="button" value="{#upload_image#}" onclick="javascript:toggle_lightbox('images.php?upload_status', 'progress_bar_lightbox'); javascript:document.forms['upload_form'].submit();" />
</form>
</div>
</div>
<script type="text/javascript">
//Start Tab Content script for UL with id="maintab" Separate multiple ids each with a comma.
initializetabcontent("maintab")
</script>
	<div id="thumbnails"></div>
{* EOF FILE UPLOAD *}	
{if count($images)}
<table border="0" class="sortable">
<caption><a href="listings.php?edit={$listing.listing_id}&edit_lang={$language}"><b>{$listing.title|stripslashes}</b></a> {#images#}<br/>{#total_size#}: {count_images_size listing_id=$smarty.get.id}</caption>
<thead>
<tr><th>{#image#}</th><th>{#title#}</th><th>{#listing_icon#}</th><th>{#filename#}</th><th>{#size#}</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$images item=image}
<tr class="{cycle values="oddrow,none"}">
<td><a href="{$BASE_URL}/uploads/images/{$image.file}" class="highslide" target="_blank"><img src="{$BASE_URL}/uploads/thumbs/{$image.file}" border="0" title="Click to enlarge"/></a></td>
<td>{$image.title|stripslashes}</td>
<td>{if $listing.icon eq $image.file}{#default#}{else}<a href="images.php?id={$listing.listing_id}&default={$image.image_id}">{#make_default#}</a>{/if}</td>
<td><b>{$image.file}</b></td>
<td>{$image.size}</td>
<td><a href="images.php?edit={$image.image_id}&edit_lang={$language}"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('images.php?delete={$image.image_id}&listing={$listing.listing_id}')" title="{#delete_image#}"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_image#}" border="0"></a></td></tr>
{/foreach}
</tbody></table>
{/if}
</div>
</div>
{include file="admin/footer.tpl"}