{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#marketing_emails#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
			<input class="submit" name="Button" type="button" onClick="window.location='marketing.php?add'" value="{#add_new_email#}" /><br/><br/><br/>
{if count($emails)}
			<input type="submit" name="delete_selected" value="{#delete_selected#}" />
<form method=post action="" name="messages" enctype="multipart/form-data" onsubmit="return formCheck(this);">
<table border="0" class="sortable">
<caption>{#subscribers#}</caption>
<thead>
<tr><th>{#email#}</th><th>{#last_send#}</th><th>{#count_sent#}<th><a href="javascript:SelectAllMessages(true);" title="{#select_all_emails#}" style="font-weight:bold;text-decoration:none;"><img src="{$BASE_URL}/admin/images/plus.gif" border="0"/></a>&nbsp;<a href="javascript:SelectAllMessages(false);" title="{#deselect_all_emails#}" style="font-weight:bold;text-decoration:none;"><img src="{$BASE_URL}/admin/images/minus.gif" border="0"/></a></th></tr>
</thead>
<tbody>
{foreach from=$emails item=email name=email_list}
<tr class="{cycle values="oddrow,none"}"><td><b>{$email.email}</b></td><td>{$email.last_send|date_format:"%d/%b/%Y %H:%M:%S"}</td><td>{$email.count_sent}</td><td align="center"><input type="checkbox" name="member[{$smarty.foreach.email_list.iteration
}]" value="{$email.email}"/></td></tr>
{/foreach}
</tbody></table>
<input type="hidden" name="delete" value="{$smarty.foreach.email_list.total}" />
<input type="submit" name="delete_selected" value="{#delete_selected#}" />
</form>
{else}
{#no_subscribers#}
{/if}
{include file="admin/footer.tpl"}