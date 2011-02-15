<div id="member" class="tabcontent">
{include file="errors.tpl" errors=$error error_count=$error_count}
	<br/>
	{$listing.contact_details|stripslashes}<br/>
{if $send_status eq "0"}	
	<h3>{#contact#}</h3><br />	
	<div id="frm">		
	<form action="{$baseurl}/listing/{$listing.uri}/contact" method="post" name="contact"><br />
	<label for="yourname">{#fullname#}:</label><input type="text" name="name" size="30" value="{$smarty.post.name}" /><br />
	<label for="yourname">{#phone#}:</label><input type="text" name="phone" size="30" value="{$smarty.post.phone}" /><br />
	<label for="youremail">{#email#}:</label><input type="text" name="email" size="30" value="{$smarty.post.email}" /><br />
	<label for="interested">{#interested_in#}:</label><input type="text" name="interested_in" size="40" value="{$baseurl}/listing/{$listing.uri}" /><br />
	<label for="message">{#message#}:</label><textarea cols="30" rows="7" wrap="OFF" name="message">{$smarty.post.message|stripslashes}</textarea><br />
	{if $conf.require_captcha}<label for="captcha"><img src="{$BASE_URL}/includes/random_image.php" border="1" alt="{#captcha_code#}" /></label>
	<input type="text" name="txtNumber" onMouseOver="showhint('{#hint_captcha_code#}', this, event, '150px')" size="8" /><br/>{/if}
	<div id="button"><a href="javascript:document.contact.submit();" title="{#listing_contact#}">{#contact_submit#}</a></div>
</form>
	</div>
{else}
<b>{#contact_success_msg#}</b><br/>
{/if}
</div>
