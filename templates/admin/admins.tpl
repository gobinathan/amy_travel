{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#site_admins#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
			{include file="admin/msg.tpl"}
			<div class="left_box">
			<input class="submit" name="Button" type="button" onClick="window.location='admins.php?add'" value="{#add_new_admin#}" /><br/>
<table border="0" class="sortable">
<caption>{#site_admins#}</caption>
<thead>
<tr><th>{#id#}</th><th>{#admin_username#}</th><th>{#admin_roll#}</th><th>{#last_login#}</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$admins item=admin name=user }

<tr class="{cycle values="oddrow,none"}"><td>{$smarty.foreach.user.iteration}</td><td><b>{$admin.username}</b></td><td>{$admin.name}</td><td>{$admin.last_login}</td><td><a href="admins.php?edit={$admin.admin_id}"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('admins.php?delete={$admin.admin_id}&username={$admin.username}')" title="{#delete_admin#}"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_admin#}" border="0"></a></td></tr>


{/foreach}
</tbody></table>
{include file="admin/footer.tpl"}