{include file="frontend/$template/header.tpl" title=$page_title}
		<div id="wrapper">
			<div id="content">
					{include file="frontend/$template/search_box.tpl"}
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
{parse_banner position="center"}
{include file="errors.tpl" errors=$error error_count=$error_count}
<h2>{#forgot_pass#}</h2>
<br/>
{if $status eq "sent"}
<br/>{#password_sent_to#} <b>{$sendto}</b><br/>
{else}
<div id="frm">
	<form method="post" action="{$baseurl}/forgot_pass">
	<label>{#email#}:</label><input type="text" name="email" value="{$smarty.post.email}" /><br/>
{if $conf.require_captcha}<label><img src="{$BASE_URL}/includes/random_image.php" border="1" alt="{#captcha_code#}" /></label><input type="text" name="txtNumber" onMouseOver="showhint('{#hint_captcha_code#}', this, event, '150px')" size="8" /><br/>{/if}
	<label></label><input type="submit" name="submit" value="{#send_password#}"/>
	</form>
<br/><br/>
</div>
{/if}
				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
			</div>
		</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}
