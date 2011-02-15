{include file="frontend/$template/header.tpl" title=$page_title}
		<div id="wrapper">
			<div id="content">
					{include file="frontend/$template/search_box.tpl"}
					<center>{parse_banner position="center"}</center>
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
<h2>{#member_panel#}</h2>
<br/>
{include file="errors.tpl" errors=$error error_count=$error_count}
				<br />
				<img src="{$BASE_URL}/uploads/avatars/{$member.avatar}" border="1" style="float: right;margin-top:-50px;">
				E-Mail: <b>{$member.email}</b><br/>
				Name: <b>{$member.fullname}</b><br/>
				Last Login: <b>{$member.last_login}</b><br/><br/>
<input class="submit" name="Button" type="button" onClick="window.location='{$baseurl}/profile/edit'" value="{#edit_profile#}" />
<br/><br/>
				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
			</div>
		</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}
