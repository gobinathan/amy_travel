{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#menu_members#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
			<input class="submit" name="Button" type="button" onClick="window.location='members.php?add'" value="{#add_new_member#}" /><br/>
<table border="0" class="sortable" width="800" style="float:left;">
<caption>{#menu_members#}</caption>
<thead>
<tr><th>{#email#}</th><th>{#fullname#}</th><th>{#orders#}</th><th>{#last_login#}</th><th>Registered</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$members item=member}
<tr class="{cycle values="oddrow,none"}" onMouseOver="showhint('<img src={$BASE_URL}/uploads/avatars/{$member.avatar} border=0>', this, event, '100px')">
<td>{$member.email}</td>
<td>{$member.fullname}</td>
<td align="center"><a href="transactions.php?member={$member.member_id}"><b>{$member.count_orders}</b></a></td>
<td>{$member.last_login}</td>
<td align="center">{$member.date_register|date_format:"%d/%b/%Y"}</td>
<td><a href="members.php?edit={$member.email}"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('members.php?delete={$member.email}')" title="{#delete_member#}"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_member#}" border="0"></a></td></tr>
{/foreach}

</tbody></table>
{include file="admin/footer.tpl"}