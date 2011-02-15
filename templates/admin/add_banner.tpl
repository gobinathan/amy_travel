{include file="admin/header.tpl"}
<div class="left">
			<h3>{#add_banner#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<form method="post" action="{$smarty.server.PHP_SELF|xss}">
<table border="0" class="tbl">
<tr><td>{#position#}:</td><td>
<label>{#position_top#} <input type="radio" name="position" value="top" /></label>&nbsp;&nbsp;&nbsp;
<label>{#position_left#} <input type="radio" name="position" value="left" /></label>&nbsp;&nbsp;&nbsp;
<label>{#position_center#} <input type="radio" name="position" value="center" /></label>&nbsp;&nbsp;&nbsp;
<label>{#position_right#} <input type="radio" name="position" value="right" /></label>&nbsp;&nbsp;&nbsp;
<label>{#position_bottom#} <input type="radio" name="position" value="bottom" /></label>&nbsp;&nbsp;&nbsp;
</td></tr>
<tr><td>{#rotate#}:</td><td><input type="checkbox" name="rotate" onMouseover="showhint('{#hint_banner_rotate#}', this, event, '150px')"></td></tr>
</table>
{#banner_code#}:<a href="#" class="hintanchor" onMouseover="showhint('{#hint_banner_code#}', this, event, '150px')">[?]</a><textarea name="code" cols="70" rows="17"></textarea><img src="../images/required.gif" border="0"/><br/>
<input type="submit" name="add_banner" value="{#add_banner_submit#}" /> &nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='ads.php'" value="{#back_ads#}" /></form>
</div>
</div>
{include file="admin/footer.tpl"}