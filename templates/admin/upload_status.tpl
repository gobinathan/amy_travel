<script type="text/javascript">
{literal}
if (document.images) {
	image = new Image(32, 32);
	image.src = "images/progress_bar.gif";
}
{/literal}
</script>
<center>		<div class="left"><h3>{#uploading#}</h3></div>
		{#upload_in_progress#}
		<br /><br />
		<img src="images/progress_bar.gif" alt="{#upload_in_progress#}" title="{#upload_in_progress#}" />
		<br /><br />
		{#upload_in_progress_desc#}<br/><br/>
		<div class="left"><a onclick="javascript:toggle_lightbox('no_url', 'progress_bar_lightbox');"><b>{#close#}</b></a></div>

