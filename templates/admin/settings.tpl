{include file="admin/header.tpl"}
</head>
<div class="left">
			<h3>{#menu_settings#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("site_title", "items_per_page", "rss_max_items", "system_email", "system_name");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#site_title#}", "{#items_per_page#}", "{#max_rss_items#}", "{#system_email#}", "{#system_fullname#}");
</script>
<div id="frm">
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);" name="settings_form" ENCTYPE="multipart/form-data">
<input type="submit" name="submit" value="{#save_settings#}" /><br/>
<fieldset><legend><b>{#site_settings#}</b></legend>
<ul id="countrytabs" class="shadetabs">
{foreach from=$languages_array item=lang}
<li><a href="settings.php?edit_lang={$lang.lang_name}" {if $edit_lang == $lang.lang_name}class="selected"{/if}><img src="{$BASE_URL}/uploads/flags/{$lang.lang_name}.gif" border="0" />&nbsp;{$lang.lang_title}</a></li>
{/foreach}
</ul>
<div id="countrydivcontainer" style="padding-top:20px;border:1px solid gray; width:550px; margin-bottom: 1em; padding: 10px">
<label>{#site_title#}:</label><input type="text" id="site_title" name="form[site_title]" class="required" value="{$conf.site_title}" size="40" onMouseOver="showhint('{#hint_site_title#}', this, event, '150px')" />
{if $edit_lang != $language}
<a onClick="submitChange('site_title');" title="{#auto_translate#} {#site_title#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
<br/>
<label>{#site_slogan#}:</label><input type="text" id="slogan" name="form[slogan]" value="{$conf.slogan}" size="60" onMouseOver="showhint('{#hint_site_slogan#}', this, event, '150px')" />
{if $edit_lang != $language}
<a onClick="submitChange('slogan');" title="{#auto_translate#} {#site_slogan#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
<br/>
<label>{#accept_browser_language#}:</label><input type="checkbox" name="form[accept_browser_language]" {if $conf.accept_browser_language eq "1"}checked{/if} onMouseOver="showhint('{#hint_accept_browser_language#}', this, event, '150px')" /><br />
<label>{#auto_convert_currency#}:</label><input type="checkbox" name="form[auto_convert_currency]" {if $conf.auto_convert_currency eq "1"}checked{/if} onMouseOver="showhint('{#hint_auto_convert_currency#}', this, event, '150px')" />
<select name="form[currency]" style="width:55px;">
{foreach from=$currencies item=currency}
<option value="{$currency.code}" {if $conf.currency == $currency.code}selected{/if}>{$currency.code}</option>
{/foreach}
</select>
<br />
<label>{#template#}:</label><select name="form[template]" style="width:100px;">
{foreach from=$frontend_templates item=front_tpl}
<option value="{$front_tpl}" {if $conf.template == $front_tpl}selected{/if}>{$front_tpl}</option>
{/foreach}
</select>
<br />
<label>{#meta_keywords#}:</label><input type="text" id="meta_keywords" name="form[meta_keywords]" value="{$conf.meta_keywords}" size="60" onMouseOver="showhint('{#hint_site_meta_keywords#}', this, event, '150px')" />
{if $edit_lang != $language}
<a onClick="submitChange('meta_keywords');" title="{#auto_translate#} {#meta_keywords#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
<br/>
<label>{#meta_description#}:</label><input type="text" id="meta_description" name="form[meta_description]" value="{$conf.meta_description}" size="60" onMouseOver="showhint('{#hint_site_meta_description#}', this, event, '150px')" />
{if $edit_lang != $language}
<a onClick="submitChange('meta_description');" title="{#auto_translate#} {#meta_description#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
<br/>
</div>
<label>{#gmap_api_key#}:</label><input type="text" name="form[gmap_api_key]" value="{$conf.gmap_api_key}" size="60" onMouseOver="showhint('{#hint_gmap_api_key#}', this, event, '150px')" />&nbsp;&nbsp;<a href="http://www.google.com/apis/maps/signup.html" target="_blank">{#register_new_gmap_key#}</a><br/>
<label>{#items_per_page#}:</label><input type="text" name="form[items_per_page]" class="required" value="{$conf.items_per_page}" size="2" onMouseOver="showhint('{#hint_items_per_page#}', this, event, '150px')" /><br/>
<label>{#max_rss_items#}:</label><input type="text" name="form[rss_max_items]" class="required" value="{$conf.rss_max_items}" size="2" onMouseOver="showhint('{#hint_max_rss_items#}', this, event, '150px')" /><br/>
<label>Show Empty Categories:</label><input type="checkbox" name="form[show_empty_categories]" {if $conf.show_empty_categories eq "1"}checked{/if} onMouseOver="showhint('Show categories that doesnt have any listings in it', this, event, '150px')" /><br />
<label>Show Empty Locations:</label><input type="checkbox" name="form[show_empty_locations]" {if $conf.show_empty_locations eq "1"}checked{/if} onMouseOver="showhint('Show locations that doesnt have any listings in it', this, event, '150px')" /><br />
<label>Show Empty Countries:</label><input type="checkbox" name="form[show_empty_countries]" {if $conf.show_empty_countries eq "1"}checked{/if} onMouseOver="showhint('Show countries that doesnt have any listings in it', this, event, '150px')" /><br />
</fieldset>
<br/>
<fieldset><legend><b>{#image_settings#}</b></legend>
<label>{#resize_images#}:</label><input type="checkbox" name="form[img_resize]" {if $conf.img_resize eq "1"}checked{/if} onMouseOver="showhint('{#hint_resize_images#}', this, event, '150px')" onClick="hiding('image_dimensions')"/><br/>
<div id="image_dimensions" style="display: {if $conf.img_resize eq "1"}block{else}none{/if};">
<label>{#image_height#}:</label> <input type="text" name="form[img_resize_h]" value="{$conf.img_resize_h}" size="3" />px<br />
<label>{#image_width#}:</label> <input type="text" name="form[img_resize_w]" value="{$conf.img_resize_w}" size="3" />px<br /><br/>
</div>
<label>{#create_thumbs#}:</label> <input type="checkbox" name="form[create_thumbs]" {if $conf.create_thumbs eq "1"}checked{/if} onMouseOver="showhint('{#hint_create_thumbs#}', this, event, '150px')" onClick="hiding('thumb_dimensions')"/><br/>
<div id="thumb_dimensions" style="display: {if $conf.create_thumbs eq "1"}block{else}none{/if};">
<label>{#thumb_height#}:</label> <input type="text" name="form[thumb_resize_h]" value="{$conf.thumb_resize_h}" size="3" />px<br />
<label>{#thumb_width#}:</label> <input type="text" name="form[thumb_resize_w]" value="{$conf.thumb_resize_w}" size="3" />px<br /><br/>
</div>
<label>{#resize_member_photos#}:</label> <input type="checkbox" name="form[resize_member_photos]" {if $conf.resize_member_photos eq "1"}checked{/if} onMouseOver="showhint('{#hint_resize_member_photos#}', this, event, '150px')" onClick="hiding('member_dimensions')"/><br/>
<div id="member_dimensions" style="display: {if $conf.resize_member_photos eq "1"}block{else}none{/if};">
<label>{#memberp_height#}:</label> <input type="text" name="form[member_resize_h]" value="{$conf.member_resize_h}" size="3" />px<br />
<label>{#memberp_width#}:</label> <input type="text" name="form[member_resize_w]" value="{$conf.member_resize_w}" size="3" />px<br /><br/>
</div>
<label>Watermark Images:</label> <input type="checkbox" name="form[watermark_images]" {if $conf.watermark_images eq "1"}checked{/if} onMouseOver="showhint('Watermark the uploaded images.<br/><b>Note:</b> watermarks will be made only to the full images size, NO watermark will be made on the thumbnails', this, event, '150px')" onClick="hiding('watermark_images')"/><br/>
<div id="watermark_images" style="display: {if $conf.watermark_images eq "1"}block{else}none{/if};">
<label>Position X:</label> <input type="text" name="form[watermark_position_x]" value="{$conf.watermark_position_x}" size="3" />px<br />
<label>Position Y:</label> <input type="text" name="form[watermark_position_y]" value="{$conf.watermark_position_y}" size="3" />px<br />
<label>Upload New Watermark Image:</label> <input type="file" name="watermark_image_file" />{if file_exists("../uploads/$watermark_image_file")}<a href="{$BASE_URL}/uploads/{$watermark_image_file}?{php}echo time();{/php}" target="_blank"><img src="{$BASE_URL}/uploads/{$watermark_image_file}?{php}echo time();{/php}" border="1" alt="Watermark Image" height="30" width="64" onMouseOver="showhint('The images will be watermarked with the following image<br/><b>Note:</b> this is not the actual size of the watermark image. Click to open with the real size', this, event, '150px')"></a>{/if}<br/><br/>
</div>
<label>{#require_captcha#}:</label><input type="checkbox" name="form[require_captcha]" {if $conf.require_captcha eq "1"}checked{/if} onMouseOver="showhint('{#hint_require_captcha#}', this, event, '150px')" /><br/>
</fieldset>
<br/>
<fieldset><legend><b>{#member_settings#}</b></legend>
<label>{#member_allow_register#}:</label> <input type="checkbox" name="form[member_allow_register]" {if $conf.member_allow_register eq "1"}checked{/if} onMouseOver="showhint('{#hint_member_allow_register#}', this, event, '150px')" onClick="hiding('member_settings')"/><br/>
<div id="member_settings" style="display: {if $conf.member_allow_register eq "1"}block{else}none{/if};">
<label>{#member_approve#}:</label> <input type="checkbox" name="form[member_approve]" {if $conf.member_approve eq "1"}checked{/if} onMouseOver="showhint('{#hint_member_approve#}', this, event, '150px')" /><br/>
<label>{#member_confirm_email#}:</label> <input type="checkbox" name="form[member_confirm_email]" {if $conf.member_confirm_email eq "1"}checked{/if} onMouseOver="showhint('{#hint_member_confirm_email#}', this, event, '150px')" /><br/>
</div>
</fieldset>
<br/>
<fieldset><legend><b>{#email_settings#}</b></legend>
<label>{#system_email#}:</label> <input type="text" name="form[system_email]" class="required" value="{$conf.system_email}" size="35" onMouseOver="showhint('{#hint_system_email#}', this, event, '150px')" /><br/>
<label>{#system_fullname#}:</label> <input type="text" name="form[system_name]" class="required" value="{$conf.system_name}" size="35" onMouseOver="showhint('{#hint_system_fullname#}', this, event, '150px')" /><br/>
<label>{#use_smtp_mail#}:</label> <input type="checkbox" name="form[use_smtp_mail]" {if $conf.use_smtp_mail eq "1"}checked{/if} onMouseOver="showhint('{#hint_use_smtp_mail#}', this, event, '150px')" onClick="hiding('smtp_details')"/><br/>
<div id="smtp_details" style="display: {if $conf.use_smtp_mail eq "1"}block{else}none{/if};">
<label>{#smtp_host#}:</label> <input type="text" name="form[smtp_host]" class="required" value="{$conf.smtp_host}" size="25" onMouseOver="showhint('{#hint_smtp_host#}', this, event, '150px')" /><br/>
<label>{#smtp_port#}:</label> <input type="text" name="form[smtp_port]" class="required" value="{$conf.smtp_port}" size="3" onMouseOver="showhint('{#hint_smtp_port#}', this, event, '150px')" /><br/>
<label>{#smtp_auth_type#}:</label> <select name="form[smtp_auth_type]" onMouseOver="showhint('{#hint_smtp_auth_type#}', this, event, '150px')"><option value="AUTH LOGIN" {if $conf.smtp_auth_type == "AUTH LOGIN"}selected{/if}>AUTH LOGIN</option><option value="AUTH PLAIN" {if $conf.smtp_auth_type == "AUTH PLAIN"}selected{/if}>AUTH PLAIN</option><option value="AUTO" {if $conf.smtp_auth_type == "AUTO"}selected{/if}>AUTO</option><option value="AUTH CRAM-MD5" {if $conf.smtp_auth_type == "AUTH CRAM-MD5"}selected{/if}>AUTH CRAM-MD5</option></select><br/>
<label>{#smtp_user#}:</label> <input type="text" name="form[smtp_user]" class="required" value="{$conf.smtp_user}" size="35" onMouseOver="showhint('{#hint_smtp_user#}', this, event, '150px')" /><br/>
<label>{#smtp_pass#}:</label> <input type="text" name="form[smtp_pass]" size="25" onMouseOver="showhint('{#hint_smtp_pass#}', this, event, '150px')" /><br/>
</div>
</fieldset>
<br/>
<input type="hidden" name="edit_lang" value="{$edit_lang}" />
<input type="submit" name="submit" value="{#save_settings#}" />
</form>
</div>
</div>
{include file="admin/footer.tpl"}