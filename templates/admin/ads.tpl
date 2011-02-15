{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#banner_ads#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<input class="submit" name="Button" type="button" onClick="window.location='ads.php?add'" value="{#add_banner#}" /><br/>			
			<div class="left_box">
{if count($ads)}
<table border="0" class="sortable">
<caption>{#banner_ads#}</caption>
<thead><tr><th>{#id#}</th><th>{#position#}</th><th>{#rotate#}</th><th>{#shown#}</th><th>{#action#}</th></tr></thead>
<tbody>
{foreach from=$ads item=ad}
<tr class="{cycle values="oddrow,none"}">
<td><b>{$ad.banner_id}</b></td>
<td><b>{$ad.position}</b></td>
<td>{if $ad.rotate}Yes{else}No{/if}</td>
<td>{$ad.shown} {#times#}</td>
<td>{if $ad.active}
<a href="ads.php?deactivate={$ad.banner_id}">{#deactivate#}</a>
{else}
<a href="ads.php?activate={$ad.banner_id}">{#activate#}</a>
{/if}
 | <a href="ads.php?edit={$ad.banner_id}"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('ads.php?delete={$ad.banner_id}')"><img src="{$BASE_URL}/admin/images/delete.png" border="0" /></a>
</td></tr>
{/foreach}
</tbody></table>
{else}
{#no_ads_found#}
{/if}
{include file="admin/footer.tpl"}