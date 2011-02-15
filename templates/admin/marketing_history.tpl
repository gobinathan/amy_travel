{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#newsletter_history#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
{if count($newsletter_history)}
<table border="0" class="sortable">
<caption>{#newsletter_history#}</caption>
<thead>
<tr><th>{#date_sent#}</th><th>{#from_name#}</th><th>{#from_email#}</th><th>{#mail_subject#}</th><th>{#sent_by#}</th><th>{#count_sent#}</th></tr>
</thead>
<tbody>
{foreach from=$newsletter_history item=history}
<tr onClick="popUp('marketing.php?historyid={$history.newsletter_id}')" style="cursor:pointer;">
<td onMouseOver="showhint('{#hint_show_newsletter_details#}', this, event, '150px')">{$history.date_sent|date_format:"%d/%b/%Y %H:%M:%S"}</td>
<td>{$history.from_name}</td>
<td>{$history.from_email}</td>
<td>{$history.subject|stripslashes}</td>
<td>{admin2name id=$history.admin_id}</td>
<td>{$history.count_sent}</td>
</tr>
{/foreach}
</tbody></table>
{else}
{#no_newsletter_history#}
{/if}
{include file="admin/footer.tpl"}