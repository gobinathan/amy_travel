{include file="frontend/$template/header.tpl" title=$page_title}
		<div id="wrapper">
			<div id="content">
					{include file="frontend/$template/search_box.tpl"}
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
{parse_banner position="center"}
{include file="errors.tpl" errors=$error error_count=$error_count}
<h2>{#member_panel#}</h2>
<div id="frm">
	<form method="post" action="{$BASE_URL}/login">
	<label>{#email#}:</label><input type="text" name="username" value="{$smarty.post.username}" /><br/>
	<label>{#password#}:</label><input type="password" name="password" /><br/>
{if $conf.require_captcha}<label><img src="{$BASE_URL}/includes/random_image.php" border="1" alt="{#captcha_code#}" /></label><input type="text" name="txtNumber" onMouseOver="showhint('{#hint_captcha_code#}', this, event, '150px')" size="8" /><br/>{/if}
	<label></label><input type="submit" name="submit" value="{#login#}"/>
	</form>
<br/><br/>
</div>
				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
			</div>
		</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}
