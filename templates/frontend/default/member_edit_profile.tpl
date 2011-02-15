{include file="frontend/$template/header.tpl" title=$page_title}
		<div id="wrapper">
			<div id="content">
					{include file="frontend/$template/search_box.tpl"}
					<center>{parse_banner position="center"}</center>
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
<h2>{$page_title}</h2>
{include file="errors.tpl" errors=$error error_count=$error_count}			
{if $request.2 == "success" AND $request.1 == "edit"}
<br/><font color="#99CC00"><b>
{#profile_update_success#}
</b></font><br/>
{/if}
<form method="post" action="{$baseurl}/profile" ENCTYPE="multipart/form-data">
<img src="{$BASE_URL}/uploads/avatars/{$member.avatar}" border="0" style="float: right;margin-top:-25px;">
<div id="frm">
<label>{#email#}:</label><input type="text" name="email" class="required" value="{$member.email}" onMouseOver="showhint('{#hint_member_email#}', this, event, '150px')" maxlength="255" /><br/>
<label>{#fullname#}:</label><input type="text" name="fullname" class="required" value="{$member.fullname}" onMouseOver="showhint('{#hint_member_fullname#}', this, event, '150px')" maxlength="255" /><br/>
<label>{#photo#}:</label><input type="file" name="picture" onMouseOver="showhint('{#hint_member_photo#}', this, event, '150px')"/><br/>
</div>
<input type="hidden" name="edit_profile" value="true">
<input type="submit" name="submit" value="{#update_profile#}" />
</form>
				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
{if $request.2 == "success" AND $request.1 == "passwd"}
<br/><font color="#99CC00"><b>
{#password_update_success#}
</b></font><br/>
{/if}
<form method="post" action="{$baseurl}/profile">
<div id="frm">
<label>{#current_password#}:</label><input type="password" name="current_password" class="required" onMouseOver="showhint('{#hint_member_current_password#}', this, event, '150px')"/><br/>
<label>{#new_password#}:</label><input type="password" name="new_password" class="required" onMouseOver="showhint('{#hint_member_new_password#}', this, event, '150px')" maxlength="50" /><br/>
<label>{#new_password_repeat#}:</label><input type="password" name="new_password_repeat" class="required" onMouseOver="showhint('{#hint_member_new_password_repeat#}', this, event, '150px')" maxlength="50" /><br/>
</div>
<input type="hidden" name="passwd" value="true">
<input type="submit" name="submit" value="{#update_password#}" />
</form>

				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
			</div>
		</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}
