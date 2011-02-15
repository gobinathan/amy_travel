{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#articles#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
			<input class="submit" name="Button" type="button" onClick="window.location='articles.php?add'" value="{#add_new_article#}" /><br/>
{if count($articles)}
<table border="0" class="sortable">
<caption>{#articles#}</caption>
<thead><tr><th>{#id#}</th><th>{#title#}</th><th>{#category#}</th><th>{#added_by#}</th><th>{#date_added#}</th><th>{#action#}</th></tr></thead>
<tbody>
{foreach from=$articles item=nw}
<tr class="{cycle values="oddrow,none"}">
<td><b>{$nw.article_id}</b></td>
<td>{$nw.title|stripslashes}</td>
<td>{category2name id=$nw.cat_id lang=$language}</td>
<td>{admin2name id=$nw.admin_id}</td>
<td>{$nw.date_added|date_format:"%d/%b/%Y %H:%M:%S"}</td>
<td><a href="articles.php?edit={$nw.article_id}&edit_lang={$language}"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('articles.php?delete={$nw.article_id}')"><img src="{$BASE_URL}/admin/images/delete.png" border="0" /></a></td></tr>
{/foreach}
</tbody></table>
{else}
{#no_articles#}
{/if}
{include file="admin/footer.tpl"}