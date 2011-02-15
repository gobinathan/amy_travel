{include file="frontend/$template/header.tpl" title=$title}
		<div id="wrapper">
			<div id="content">
					{include file="frontend/$template/search_box.tpl"}
				<div class="box">

				<div class="padding" style="background:url({$BASE_URL}/templates/frontend/{$template}/img/booking_preview_bg.png) no-repeat;">
<h2>{#contact#}</h2>
{parse_banner position="center"}
{include file="errors.tpl" errors=$error error_count=$error_count}
	<br/>
{if $send_status eq "0"}	
	<h3>{#contact#}</h3><br />	
	<div id="frm">		
	<form action="{$baseurl}/contact" method="post" name="contact"><br />
	<label for="yourname">{#fullname#}:</label><input type="text" name="name" size="30" value="{$smarty.post.name}" /><br />
	<label for="youremail">{#email#}:</label><input type="text" name="email" size="30" value="{$smarty.post.email}" /><br />
	<label for="yourname">{#phone#}:</label><input type="text" name="phone" size="30" value="{$smarty.post.phone}" /><br />
	<label for="interested">{#interested_in#}:</label><input type="text" name="interested_in" size="40" /><br />
	<label for="message">{#message#}:</label><textarea cols="30" rows="7" wrap="OFF" name="message">{$smarty.post.message|stripslashes}</textarea><br />
	<input type="hidden" name="contact_form" value="go"/>
	{if $conf.require_captcha}<label for="captcha"><img src="{$BASE_URL}/includes/random_image.php" border="1" alt="{#captcha_code#}" /></label>
	<input type="text" name="txtNumber" onMouseOver="showhint('{#hint_captcha_code#}', this, event, '150px')" size="8" /><br/>{/if}
	<div id="button"><a href="javascript:document.contact.submit();" title="{#listing_contact#}">{#contact_submit#}</a></div>
</form>
	</div>
{else}
<b>{#contact_success_msg#}</b><br/>
{/if}
				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
			</div>
		</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}

