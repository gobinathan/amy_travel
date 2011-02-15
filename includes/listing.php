<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// START "FETCH SELECTED OFFER"
$t->assign('send_status',"0");
$getlisting=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `uri`='$request[1]' AND `active`='1' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
$listing=@mysql_fetch_array($getlisting);

// Convert price
if ($conf[auto_convert_currency]=="1" AND $listing[currency]!==$conf[currency]) {
	$listing[price]=@convert_currency($listing[currency],$conf[currency],$listing[price]);
	$listing[currency]=$conf[currency];
}
$t->assign('meta_description',$listing['short_description']);
mysql_query("UPDATE `listings` SET `views`=views+1 WHERE `listing_id`='$listing[listing_id]'");
// EOF "FETCH SELECTED OFFER"

if (listing_active($listing[start_date],$listing[end_date]) == false) {
//	$error[]=$lang_errors['listing_not_active'];
	$error[]="This listing is not available";
   	$t->assign('errors',$error);
	$t->display("frontend/$template/errors.tpl");
	exit;
}
// START "FETCH LISTING PACKAGES"
$now=time();
if ($listing['price_set'] == 'package') {
$package=fetch_package($listing['listing_id'],$now,$now);
$t->assign('base_price',$package['base_price']);
$t->assign('base_price_period',$package['price_period']);
$listing['price']=$package['base_price'];
$listing['price_desc']=$package['price_period'];
if ($package['price_period']=="1") {$listing['price_desc']=$lang_globals['day'];}
if ($package['price_period']=="7") {$listing['price_desc']=$lang_globals['week'];}
if ($package['price_period']=="30") {$listing['price_desc']=$lang_globals['month'];}
if ($package['price_period']=="365") {$listing['price_desc']=$lang_globals['year'];}

$getpackages=mysql_query("SELECT * from `packages` WHERE `listing_id`='$listing[listing_id]' AND `to_date`>'$now' ORDER BY `from_date` ASC");
$default_listing_currency=@mysql_result(mysql_query("SELECT `currency` from `listings` WHERE `listing_id`='$listing[listing_id]'"),0);
$packages=array();
while($pack=@mysql_fetch_array($getpackages)) {
	// Convert price
	if ($conf['auto_convert_currency']=="1" AND $default_listing_currency!==$conf['currency']) {
		$pack['base_price']=round(@convert_currency($default_listing_currency,$conf['currency'],$pack['base_price']));
	}
	array_push($packages, $pack);
}
$t->assign('packages',$packages);
// EOF "FETCH LISTING PACKAGES"
}
if ($listing['price_set'] == 'static') {
	$package=fetch_package($listing['listing_id'],$now,$now);
	$t->assign('base_price',$package['base_price']);
	$t->assign('base_price_period',$package['price_period']);
}
$t->assign('listing',$listing);

// START "SEND OFFER"
if ($request[2]=="send") {
	if (isset($_POST['send'])) {
		$fullname=sqlx($_POST['fullname']);
		$from_email=sqlx($_POST['from_email']);
		$to_email=sqlx($_POST['to_email']);
		$comment=sqlx($_POST['comment']);
		$number = sqlx($_POST['txtNumber']);
				
		// Check for errors
		if (empty($fullname)) { $error[]=$lang_errors['empty_fullname']; }
		if (empty($from_email)) { $error[]=$lang_errors['empty_from_email']; }
		if (empty($to_email)) { $error[]=$lang_errors['empty_to_email']; }		
		if (!is_email($from_email)) { $error[]=$lang_errors['invalid_from_email']; }
		if (!is_email($to_email)) { $error[]=$lang_errors['invalid_to_email']; }
		if (md5($number) !== $_SESSION['image_random_value'] AND $conf['require_captcha'] == "1") { $error[]=$lang_errors['wrong_captcha']; }
		// If no errors...continue
		if (count($error)=="0")	{
			// Now send the email
			require_once('mail/htmlMimeMail.php');
			$mail = new htmlMimeMail();
		  // Get the images
			$tm="0";
			$getimages=mysql_query("SELECT * from `images` WHERE `listing_id`='$listing[listing_id]'");
			while($image=@mysql_fetch_array($getimages)) {
				if (file_exists("uploads/images/$image[file]")) {
					$tm++;
					$attachment = $mail->getFile("uploads/images/$image[file]");
					$mail->addAttachment($attachment, "image$tm", 'image/jpeg');	  	
		  		}  
  			}	
			// Parse Email Template
			$tpl_email = & new_smarty();
			$t->register_resource("email", array("email_get_template",
                                       "email_get_timestamp",
                                       "email_get_secure",
                                       "email_get_trusted"));
			$t->register_resource("email_subject", array("email_subject_get_template",
                                       "email_subject_get_timestamp",
                                       "email_subject_get_secure",
                                       "email_subject_get_trusted"));

			$tpl_email->assign('name', $fullname);
			$tpl_email->assign('from_email', $from_email);
			$tpl_email->assign('to_email', $to_email);
			$tpl_email->assign('comment', $comment);
			$tpl_email->assign('count_images', $tm);
			$tpl_email->assign('listing', $listing);									   			
			$tpl_email->config_load("$language/globals.lng");
			$tpl_email->config_load("$language/frontend.lng");
			$tpl_email->config_load("$language/members.lng");
			$tpl_email->config_load("$language/hints.lng");
			$subject=$tpl_email->fetch("email_subject:send_listing");
			$mail->setSubject($subject);
			$message = $tpl_email->fetch("email:send_listing");
			$mail->setText($message);
			$mail->setFrom("$fullname <$from_email>");
			$mail->setHeadCharset($language_encoding);
			$mail->setTextCharset($language_encoding);		
			if ($conf[use_smtp_mail] == "1") {
				$mail->setSMTPParams($conf['smtp_host'], $conf['smtp_port'], $conf['smtp_host'], $conf['smtp_auth_type'], $conf['smtp_user'], $conf['smtp_pass']);			
			}
			$result = $mail->send(array('"Webmaster" <'.$to_email.'>'));	
			$t->assign('send_status',"1");
		}else{
		   	$t->assign('error',$error);
			$t->assign('error_count',count($error));
		}
	}
	$t->display("frontend/$template/send_listing.tpl");	
	exit;
}
// EOF "SEND OFFER"

// START "CONTACT member"
if ($request[2]=="contact") {
	if (isset($_POST['interested_in'])) {
		$name=sqlx($_POST['name']);
		$from_email=sqlx($_POST['email']);
		$phone=sqlx($_POST['phone']);
		$interested_in=sqlx($_POST['interested_in']);
		$message=sqlx($_POST['message']);
		$number = sqlx($_POST['txtNumber']);
				
		// Check for errors
		if (empty($name)) { $error[]=$lang_errors['empty_fullname']; }
		if (empty($from_email)) { $error[]=$lang_errors['empty_from_email']; }
		if (empty($phone)) { $error[]=$lang_errors['empty_phone']; }		
		if (!is_email($from_email)) { $error[]=$lang_errors['invalid_from_email']; }
		if (md5($number) !== $_SESSION['image_random_value'] AND $conf['require_captcha'] == "1") { $error[]=$lang_errors['wrong_captcha']; }
		// If no errors...continue
		if (count($error)=="0")	{
		// ---------- Send email to Subscriber -------------
		// Parse Email Template
		$tpl_email = & new_smarty();
	    $tpl_email->force_compile = true;
		$t->register_resource("email", array("email_get_template",
                                       "email_get_timestamp",
                                       "email_get_secure",
                                       "email_get_trusted"));
		$t->register_resource("email_subject", array("email_subject_get_template",
                                       "email_subject_get_timestamp",
                                       "email_subject_get_secure",
                                       "email_subject_get_trusted"));  
		// assign additional template variables
		$tpl_email->assign('name', $name);
		$tpl_email->assign('email', $from_email);
		$tpl_email->assign('phone', $phone);
		$tpl_email->assign('interested_in', $interested_in);
		$tpl_email->assign('message', $message);		
		$tpl_email->assign('listing', $listing);
		$subject = $tpl_email->fetch("email_subject:contact_member");
		$email_message = $tpl_email->fetch("email:contact_member");
		
		// Send E-Mail
		send_mail($conf[system_email], "$name <$from_email>", $subject, $email_message);	
		// EOF ---------- Send email to Subscriber -------------				
			$t->assign('send_status',"1");
		}else{
		   	$t->assign('error',$error);
			$t->assign('error_count',count($error));
		}
	}
}
// EOF "CONTACT member"

// START "FETCH SUBCATEGORIES"
// get selected category info
$sgetcatinfo=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE categories.cat_id='$listing[cat_id]' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
if (@mysql_num_rows($sgetcatinfo) > "0") {
	$selected_category=@mysql_fetch_array($sgetcatinfo);
	$t->assign('selected_category',$selected_category); // Assign smarty array for the selected category
}else {
	$error[]=$lang_errors['invalid_category'];
}

if ($selected_category[parent]!=="0") {
// get main category info
$getcatinfo=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE categories.cat_id='$selected_category[parent]' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
	if (@mysql_num_rows($getcatinfo) > "0") {
		$main_category=@mysql_fetch_array($getcatinfo);
		$t->assign('main_category',$main_category); // Assign smarty array for the selected category
		// get main subcategories
		$getcats=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='$main_category[cat_id]' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang'),`position`");
		$subcategories=array();
		while($cat=@mysql_fetch_array($getcats)) {
			if ($conf['show_empty_categories']=="0") {
			if (count_active_listings($cat[cat_id])) {
				if (multiarray_search($subcategories, 'cat_id', $cat[cat_id]) == "-1") {
					array_push($subcategories, $cat);
				}
			}}else {
				if (multiarray_search($subcategories, 'cat_id', $cat[cat_id]) == "-1") {
					array_push($subcategories, $cat);
				}			  
			}
		}
		$t->assign('subcategories',$subcategories);
		$t->assign('main_category',$main_category);
	}else {
		$error[]=$lang_errors['invalid_category'];
	}
	$t->assign('title', "$listing[title] - $selected_category[title] $main_category[title]"); // Assign Page Title	
}else{
		// get selected subcategories
		$getcats=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='$selected_category[cat_id]' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang'),`position`");
		$subcategories=array();
		while($cat=@mysql_fetch_array($getcats)) {
			if ($conf['show_empty_categories']=="0") {
			if (count_active_listings($cat[cat_id])) {
				if (multiarray_search($subcategories, 'cat_id', $cat[cat_id]) == "-1") {
					array_push($subcategories, $cat);
				}
			}}else {
				if (multiarray_search($subcategories, 'cat_id', $cat[cat_id]) == "-1") {
					array_push($subcategories, $cat);
				}			  
			}
		}
		$t->assign('subcategories',$subcategories);
		$t->assign('title', "$listing[title] - $selected_category[title]"); // Assign Page Title	
		$t->assign('main_category',$selected_category);
}
// EOF "FETCH SUBCATEGORIES"

// START "FETCH OFFER IMAGES"
$getimages=mysql_query("SELECT * from `images` LEFT JOIN `images_text` ON (images.image_id=images_text.image_id) WHERE `listing_id`='$listing[listing_id]' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')"); 
$images=array();
while($img=@mysql_fetch_array($getimages)) {
	if (multiarray_search($images, 'image_id', $img[image_id]) == "-1") {
		array_push($images, $img);
	}
}
$t->assign('images',$images);
// EOF "FETCH OFFER IMAGES"

// START "FETCH OFFER VIDEOS"
$getvideos=mysql_query("SELECT * from `videos` WHERE `listing_id`='$listing[listing_id]'"); 
$videos=array();
while($vid=@mysql_fetch_array($getvideos)) {
	if (multiarray_search($videos, 'video_id', $vid[image_id]) == "-1") {
		array_push($videos, $vid);
	}
}
$t->assign('videos',$videos);
// EOF "FETCH OFFER VIDEOS"


// START "FETCH OFFER TYPES"
//////////////////////
$listing_types=explode("|",$listing[types]);
$sql=mysql_query("SELECT * from `types_c` LEFT JOIN `types_c_text` ON (types_c.type_c_id=types_c_text.type_c_id) WHERE `lang`='$default_lang' OR `lang`='$language' ORDER BY FIELD(lang,'$language','$default_lang')");
$types_c=array();
while($type_c=@mysql_fetch_array($sql)) {
	if (multiarray_search($types_c, 'type_c_id', $type_c[type_c_id]) == "-1") {	  
		array_push($types_c, $type_c);
	}
}
$types_final=array();	
foreach($types_c as $key => $row) { 
	$get_types=mysql_query("SELECT * from `types` LEFT JOIN `types_text` ON (types.type_id=types_text.type_id) WHERE `type_c_id`='$row[type_c_id]' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
	$types=array();
	while($tps=@mysql_fetch_array($get_types)) {
		if (multiarray_search($types, 'type_id', $tps[type_id]) == "-1" AND in_array($tps[type_id],$listing_types)) {
			array_push($types, $tps);
		}
	}
   	 $row['types'] = $types;		
	array_push($types_final, $row);
} 

// Assign a template variable 

$t->assign('listing_types',$listing_types);
$t->assign('types_c', $types_final);
// EOF "FETCH OFFER TYPES"

// START "FETCH ARTICLES IN SELECTED CATEGORY"
$articles=array();
// fetch from parent category
$getarticles=mysql_query("SELECT * from `articles` LEFT JOIN `articles_text` ON (articles.article_id=articles_text.article_id) WHERE `cat_id`='$selected_category[parent]' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
while($article=@mysql_fetch_array($getarticles)) {
	if (multiarray_search($articles, 'article_id', $article[article_id]) == "-1") {
			array_push($articles, $article);
	}
}
$getarticles=mysql_query("SELECT * from `articles` LEFT JOIN `articles_text` ON (articles.article_id=articles_text.article_id) WHERE `cat_id`='$selected_category[cat_id]' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
while($article=@mysql_fetch_array($getarticles)) {
	if (multiarray_search($articles, 'article_id', $article[article_id]) == "-1") {
			array_push($articles, $article);
	}
}
$t->assign('articles',$articles);
// EOF "FETCH ARTICLES IN SELECTED CATEGORY"


// START "PRINT"
if ($request[2]=="print") {
	$t->display("frontend/$template/print_listing.tpl");
	exit;
}
// EOF "PRINT"

if (count($error)) {
   	$t->assign('error',$error);
	$t->assign('error_count',count($error));  
}

$t->display("frontend/$template/listing.tpl");
?>