{include file="admin/header.tpl"}
<div class="left">
			<h3>{#edit_banner#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<form method="post" action="{$smarty.server.PHP_SELF|xss}">
<table border="0" class="tbl">
<tr><td>{#position#}:</td><td>
<label>{#position_top#} <input type="radio" name="position" value="top" {if $ad.position eq "top"}checked{/if}/></label>&nbsp;&nbsp;&nbsp;
<label>{#position_left#} <input type="radio" name="position" value="left" {if $ad.position eq "left"}checked{/if}/></label>&nbsp;&nbsp;&nbsp;
<label>{#position_center#} <input type="radio" name="position" value="center" {if $ad.position eq "center"}checked{/if}/></label>&nbsp;&nbsp;&nbsp;
<label>{#position_right#} <input type="radio" name="position" value="right" {if $ad.position eq "right"}checked{/if}/></label>&nbsp;&nbsp;&nbsp;
<label>{#position_bottom#} <input type="radio" name="position" value="bottom" {if $ad.position eq "bottom"}checked{/if}/></label>&nbsp;&nbsp;&nbsp;
</td></tr>
<tr><td>{#rotate#}:</td><td><input type="checkbox" name="rotate" {if $ad.rotate eq "1"}checked{/if} onMouseover="showhint('{#hint_banner_rotate#}', this, event, '150px')"></td></tr>
<tr><td>{#banner_code#}:<a href="#" class="hintanchor" onMouseover="showhint('{#hint_banner_code#}', this, event, '150px')">[?]</a></td><td><textarea name="code" cols="70" rows="17">{$ad.code|stripslashes}</textarea><img src="../images/required.gif" border="0"/><br/>
</td></tr>
</table>
<input type="hidden" name="edit_banner" value="{$ad.banner_id}"/>
<input type="submit" name="submit" value="{#edit_banner_submit#}" /> &nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='ads.php'" value="{#back_ads#}" /></form>
</div>
</div>
{include file="admin/footer.tpl"}