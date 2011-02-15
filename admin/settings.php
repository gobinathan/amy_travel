<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");
//update `settings` set `lang`='default' WHERE `name`!='site_title' AND `name`!='slogan' AND `name`!='template' AND `name`!='meta_keywords' AND `name`!='meta_description'
include("../languages/$edit_lang/lang_config.php");
$t->assign('conf',$conf);
$t->assign('watermark_image_file',$conf[watermark_image_file]);
function is_valid_template ($template_name) {
	$required_files=array('index.tpl',
'errors.tpl',
'listing.tpl',
'list_listings.tpl',
'news.tpl',
'newsletter.tpl',
'page.tpl',
'advanced_search.tpl',
'article.tpl',
'contact.tpl',
'favourites_box.tpl',
'favourites.tpl',
'interactive_map.tpl',
'map_listing_baloon.tpl',
'send_listing.tpl',
'print_listing.tpl',
'register.tpl',
'forgot_pass.tpl',
'member_login.tpl',
'member_index.tpl',
'member_orders.tpl',
'member_edit_profile.tpl',
'member_edit_password.tpl',
'payment_paypal.tpl',
'payment_2checkout.tpl',
'payment_2checkout.tpl',
'payment_error.tpl',
'payment_completed.tpl');
foreach ($required_files as $file) {
	if (!file_exists("../templates/frontend/$template_name/$file")) { $error[]=$file; }
}
if (count($error)=="0") { return true; }else {   return false;   }
}
// get valid frontend templates
if ($handle = opendir('../templates/frontend/')) {
	$valid_templates=array();
	$d = dir("../templates/frontend/");
	while (false !== ($entry = $d->read())) {
		if ($entry !== "." AND $entry !== "..") {
			// check if all required template files are included
//			if (!is_valid_template($entry)) { $entry="$entry (invalid)"; }
			array_push($valid_templates,$entry);
		}
	}
	$d->close();
	$t->assign('frontend_templates',$valid_templates);
}

/// Get Currencies
$getcurrencies=mquery("SELECT * from `currency` WHERE `active`='1' ORDER BY `default` DESC");
$currencies=array();
while($currency=@mysql_fetch_array($getcurrencies)) {
	array_push($currencies, $currency);
}
$t->assign('currencies',$currencies);
if (empty($edit_lang)) { $edit_lang=$default_lang; }
// Check if this language exists, and if exists, change the page encoding
if ($edit_lang !== $default_lang) {
	$checklang=mquery("SELECT * from `languages` WHERE `lang_name`='$edit_lang'");
	if (@mysql_num_rows($checklang)=="0") { $error[]=$lang_errors['invalid_language']; }
	if (count($error)=="0") {
		$lang=@mysql_fetch_array($checklang);
		$language_encoding=$lang[encoding];
		$t->assign('language_encoding',$language_encoding);
		$t->assign('load_google_api',true);
	}
}	

if (isset($_POST['submit'])) {
	$form = array_map('trim', $_POST['form']);  

	if ($form[show_empty_categories]=="on") { $form[show_empty_categories]="1"; }else { $form[show_empty_categories]="0"; }
	if ($form[show_empty_locations]=="on") { $form[show_empty_locations]="1"; }else { $form[show_empty_locations]="0"; }
	if ($form[show_empty_countries]=="on") { $form[show_empty_countries]="1"; }else { $form[show_empty_countries]="0"; }
	if ($form[img_resize]=="on") { $form[img_resize]="1"; }else { $form[img_resize]="0"; }
	if ($form[create_thumbs]=="on") { $form[create_thumbs]="1"; }else { $form[create_thumbs]="0"; }
	if ($form[resize_member_photos]=="on") { $form[resize_member_photos]="1"; }else { $form[resize_member_photos]="0"; }
	if ($form[accept_browser_language]=="on") { $form[accept_browser_language]="1"; }else { $form[accept_browser_language]="0"; }	
	if ($form[auto_convert_currency]=="on") { $form[auto_convert_currency]="1"; }else { $form[auto_convert_currency]="0"; }	
	if ($form[watermark_images]=="on") { $form[watermark_images]="1"; }else { $form[watermark_images]="0"; }
	if ($form[require_captcha]=="on") { $form[require_captcha]="1"; }else { $form[require_captcha]="0"; }	
	if ($form[member_allow_register]=="on") { $form[member_allow_register]="1"; }else { $form[member_allow_register]="0"; }	
	if ($form[member_approve]=="on") { $form[member_approve]="1"; }else { $form[member_approve]="0"; }		
	if ($form[member_confirm_email]=="on") { $form[member_confirm_email]="1"; }else { $form[member_confirm_email]="0"; }		
	if ($form[use_smtp_mail]=="on") { $form[use_smtp_mail]="1"; }else { $form[use_smtp_mail]="0"; }			
	if (!empty($form[smtp_pass])) { $form[smtp_pass]=cryptPass("$form[smtp_pass]","$form[smtp_user]"); }
	if (empty($form[smtp_pass])) { unset($form[smtp_pass]); }
	if ($_FILES['watermark_image_file']['size']>"0") {
		$upload_dir = "../uploads/";
		$form[watermark_image_file]=after_last('/',do_upload($upload_dir,"watermark_image_file","watermark"));
	}
	while (list($key, $input) = @each($form))
	{
		// Only update values that have changed
		if (array_key_exists($key, $conf) && $conf[$key] != $input) {
			if ($input != '' || is_int($input))	{ $value = $input; }
//			else { $value = 'NULL'; }
			if ($key=='site_title' OR $key=='slogan' OR $key=='meta_keywords' OR $key=='meta_description' OR $key=='template' OR $key=='auto_convert_currency' OR $key=='accept_browser_language' OR $key=='currency') {
				mquery("UPDATE `settings` SET `value`='$value' WHERE `name`='$key' AND `lang`='$edit_lang'") or die($lang_errors['unable_update_config']);
			}else {
				mquery("UPDATE `settings` SET `value`='$value' WHERE `name`='$key' AND `lang`='default'") or die($lang_errors['unable_update_config']);				
			}
		}
	}
	if ($demo == "0") {
		generate_config_cache($edit_lang);	
	}
	set_msg("Settings saved successfuly.");
	header("Location: $config[base_url]/admin/settings.php?edit_lang=$edit_lang");
}
else{
	$t->display("admin/settings.tpl");
}
?>