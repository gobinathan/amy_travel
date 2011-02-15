{include file="errors.tpl" errors=$error error_count=$error_count}
<a style="float:right;" href="#" onclick="javascript:toggle_lightbox('no_url', 'progress_bar_lightbox');return false;"><img src="{$BASE_URL}/images/small_close.gif" border="0" alt="{#close#}" /></a>
<h2>{#member_panel#}</h2><br/>
	<form method="post" action="{$BASE_URL}/login">
	<b>{#email#}:</b> <br/><input type="text" name="username" value="{if $request.1}{$request.1}{else}{$smarty.post.username}{/if}" /><br/><br/>
	<b>{#password#}:</b><br/> <input type="password" name="password" /><br/><br/>
{if $conf.require_captcha}<label><img src="{$BASE_URL}/includes/random_image.php" border="1" alt="{#captcha_code#}" /></label><input type="text" name="txtNumber" onMouseOver="showhint('{#hint_captcha_code#}', this, event, '150px')" size="8" /><br/>{/if}
	<input type="hidden" name="redirect_to" value="{$smarty.session.ref_url}" />
	<input type="submit" name="submit" value="{#login#}" class="submitbg"/>
<br/><br/><br/>
<a href="{$baseurl}/forgot_pass" style="padding-right:30px;">{#forgot_pass#}</a>
<a href="{$baseurl}/register" style="padding-left:30px;">{#register#}</a>
<br/><br/>

