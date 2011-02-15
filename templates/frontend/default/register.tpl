{include file="frontend/$template/header.tpl" title=$title}
		<div id="wrapper">
			<div id="content">
					{include file="frontend/$template/search_box.tpl"}
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
{parse_banner position="center"}
{include file="errors.tpl" errors=$error error_count=$error_count}
<h2>{#new_member_register#}</h2>
{if $status eq ""}
	<div id="frm">
	<form method="post" action="{$baseurl}/register">
	<label>{#email#}:</label><input type="text" name="email" value="{$smarty.post.email}" /><br/>	
	<label>{#password#}:</label><input type="password" name="password" /><br/>
	<label>{#password_repeat#}:</label><input type="password" name="repeat_password" /><br/>	
	<label>{#fullname#}:</label><input type="text" name="fullname" value="{$smarty.post.fullname}" /><br/>
{if $conf.require_captcha}<label><img src="{$BASE_URL}/includes/random_image.php" border="1" alt="{#captcha_code#}" /></label><input type="text" name="txtNumber" class="required" onMouseOver="showhint('{#hint_captcha_code#}', this, event, '150px')" size="8" /><br/>{/if}
<input type="hidden" name="redirect_to" value="{$req_uri}" />
	<label></label><input type="submit" name="submit" value="{#register_submit#}"/>
	</form></div>
{* MESSAGES AFTER REGISTER *}
{elseif $status eq "register_success"}
	{#status_register_success#}
<br/><br/>
<div id="frm">
	<form method="post" action="{$BASE_URL}/login">
	<label>{#email#}:</label><input type="text" name="email" value="{$smarty.post.email}" /><br/>
	<label>{#password#}:</label><input type="password" name="password" /><br/>
{if $conf.require_captcha}<label><img src="{$BASE_URL}/includes/random_image.php" border="1" alt="{#captcha_code#}" /></label><input type="text" name="txtNumber" onMouseOver="showhint('{#hint_captcha_code#}', this, event, '150px')" size="8" /><br/>{/if}
<input type="hidden" name="redirect_to" value="{$req_uri}" />
	<label></label><input type="submit" name="submit" class="button" value="{#login#}"/>
	</form>
</div>
<br/><br/>
{elseif $status eq "register_confirm" }
	{#status_register_confirm#}
{elseif $status eq "register_approve"}
	{#status_register_approve#}
{/if}

				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
			</div>
		</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}
