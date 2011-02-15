{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#news#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
			<input class="submit" name="Button" type="button" onClick="window.location='news.php?add'" value="{#add_new_news#}" /><br/>
{if count($news)}
<table border="0" class="sortable">
<caption>{#news#}</caption>
<thead><tr><th>{#id#}</th><th>{#title#}</th><th>{#position#}</th><th>{#added_by#}</th><th>{#date_added#}</th><th>Visible</th><th>{#action#}</th></tr></thead>
<tbody>
{foreach from=$news item=nw}
<tr class="{cycle values="oddrow,none"}">
<td><b>{$nw.news_id}</b></td>
<td>{$nw.title|stripslashes}</td>
<td>
{if $nw.position != $min_position}
	<a href="news.php?move_up={$nw.news_id}" title="{#move_up#}"><img src="images/up_arrow.gif" border=0 alt="{#move_up#}" align="left" /></a>
{/if}
{if $nw.position != $max_position}
	<a href="news.php?move_down={$nw.news_id}" title="{#move_down#}"><img src="images/down_arrow.gif" border=0 alt="{#move_down#}" align="right" /></a>
{/if}
</td>
<td>{admin2name id=$nw.admin_id}</td>
<td>{$nw.date_added|date_format:"%d/%b/%Y %H:%M:%S"}</td>
<td>{if $nw.visible eq "1"}{#answer_yes#}{else}{#answer_no#}{/if}</td>
<td><a href="news.php?edit={$nw.news_id}&edit_lang={$language}"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('news.php?delete={$nw.news_id}')"><img src="{$BASE_URL}/admin/images/delete.png" border="0" /></a></td></tr>
{/foreach}
</tbody></table>
{else}
{#no_news#}
{/if}
{include file="admin/footer.tpl"}