{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#menu_countries#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
			<input class="submit" name="Button" type="button" onClick="window.location='countries.php?add'" value="{#add_new_country#}" /><br/><br/><br/>
{if count($countries)}
<div style="float:left;">
<table cellpadding="0" cellspacing="0" border="0" id="table" class="sortable" width="530">
<caption>{#countries#}</caption>
<thead>
<tr><th>{#id#}</th><th>{#country_code#}</th><th>{#title#}</th><th>{#cities#}</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$countries item=country name=count_items}
<tr><td>{$country.country_id}</td><td>{$country.country_code}</td><td><b>{$country.title}</b></td>
<td align="center" {if count($country.cities)}onMouseOver="showhint('{foreach from=$country.cities item=city}{$city.city|stripslashes}<br/>{/foreach}', this, event, '150px')"{/if}>{count_cities country_id=$country.country_id}</td>
<td><a href="countries.php?edit={$country.country_id}&edit_lang={$language}" title="{#edit_country#}"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('countries.php?delete={$country.country_id}')" title="{#delete_country#}"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_country#}" border="0"></a></td></tr>
{/foreach}
</tbody></table>
{include file="admin/sortable.tpl"}
</div>
{else}
<br/>{#no_countries#}
{/if}
{include file="admin/footer.tpl"}