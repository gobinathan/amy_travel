{include file="admin/header.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("title");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#title#}");
</script>
		<div class="left">
			<h3>{#states#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<label>{#title#}:</label><input type="text" name="title" size="50" /> <input type="submit" name="add_state" value="{#add_new_state#}" /><br/><br/>
</form>
{if count($states)}
<div style="float:left;">
<table cellpadding="0" cellspacing="0" border="0" id="table" class="sortable" width="530">
<caption>{#states#}</caption>
<thead>
<tr><th>{#id#}</th><th>{#state_code#}</th><th>{#title#}</th><th>{#action#}</th></tr>
</thead>
<tbody>
{foreach from=$states item=state name=count_items}
<tr><td>{$state.state_id}</td><td>{$state.state_code}</td><td><b>{$state.title}</b></td>
<td><a href="states.php?edit={$state.state_id}&edit_lang={$language}" onMouseOver="showhint('{#edit_state#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/edit.png" border="0" /></a> | <a href="#" onClick="DeleteItem('states.php?delete={$state.state_id}')" onMouseOver="showhint('{#delete_state#}', this, event, '150px')"><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_state#}" border="0"></a></td></tr>
{/foreach}
</tbody></table>
{include file="admin/sortable.tpl"}
</div>
{else}
<br/>{#no_states#}
{/if}
{include file="admin/footer.tpl"}