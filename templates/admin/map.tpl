{include file="admin/header.tpl"}
		<div class="left">
			<h3>{#admin_panel#}</h3>
			<div class="left_box">
			<div style="float: left;">
{#last_login#}: <b>{$last_login}</b><br/>
<br/>
<div id="message"></div>
    <table>
      <tr>
        <td>{$google_map}</td>
        <td>{$google_map_sidebar}</td>
      </tr>
    </table>
</div>
{include file="admin/footer.tpl"}