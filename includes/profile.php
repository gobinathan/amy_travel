<?php
include($config['root_dir']."/includes/checklogin.php");
if ($gd=="yes") {include($config['root_dir']."/includes/thumb.class.php");}

// START "EDIT PROFILE"
if ($request[1]=='edit' OR $request[1]=='passwd') {
	$sql=mysql_query("SELECT * from `members` WHERE `user_id`='$member[user_id]' AND `password`='$member[password]'");
	if (@mysql_num_rows($sql)=="0") { $error[]=$lang_errors['invalid_member']; }
	if (count($error)=="0") {
		$profile=@mysql_fetch_array($sql);
	   	$t->assign('profile',$profile);
		$t->assign('page_title',$lang_members[edit_profile]);
		$t->display("frontend/$template/member_edit_profile.tpl");
	} else{
		$t->assign('page_title',$lang_members[edit_profile]);
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}	
}
elseif (isset($_POST['edit_profile'])) {
  $email=sqlx($_POST['email']);
  $fullname=sqlx($_POST['fullname']);

  $added_time=time();
  if ($_FILES['picture']['size']) { // Add Image submit
	$upload_dir = "uploads/avatars/";
	$uploaded = do_upload($upload_dir,"picture","$added_time");
	$uploaded=substr($uploaded, 1);
	$image=after_last('/',$uploaded);
	// Resize Image
	if ($gd=="yes" AND $conf[resize_member_photos]=="1") {
		$tm = new dThumbMaker; 
		$load = $tm->loadFile($upload_dir.$image);
		if($load === true){ // Note three '='      
		    $tm->resizeMaxSize($conf[member_resize_h], $conf[member_resize_w]); 
//			$tm->addWaterMark('images/watermark.gif', 64, 64, true);
		    $tm->build($upload_dir.$image); 
		}
	  }
	//EOF Resize Image
	}

	// Check for errors
	if (empty($email)) { $error[]=$lang_errors['empty_email']; }
	if (!is_email($email)) { $error[]=$lang_errors['invalid_email']; }
	if (empty($fullname)) { $error[]=$lang_errors['empty_fullname']; }
	  	
	$check_user_exists=mysql_query("SELECT * from `members` WHERE `user_id`='$member[user_id]'");
	if (@mysql_num_rows($check_user_exists)==0) { $error[]=$lang_errors['invalid_member']; }
	// If no errors...continue
	if (count($error)=="0")	{
		if (!empty($image)) {
			$member_old_picture=@mysql_result($check_user_exists,0,"avatar");
			if ($member_old_picture !== "default.gif") {
				@unlink("$upload_dir/$member_old_picture");
			}
			mysql_query("UPDATE `members` SET `avatar`='$image' WHERE `user_id`='$member[user_id]'");
		}
		mysql_query("UPDATE `members` SET `email`='$email',`fullname`='$fullname' WHERE `user_id`='$member[user_id]'");
		// update session
		$_SESSION['member']=fetch_member($member[user_id]);
		header("Location: $config[base_url]/profile/edit/success");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("frontend/$template/member_edit_profile.tpl");		
	}
}
// EOF "EDIT PROFILE"

// START "CHANGE PASSWORD"
elseif (isset($_POST['passwd'])) {
  $old_password=sqlx($_POST['current_password']);
  $new_password=sqlx($_POST['new_password']);
  $new_password_repeat=sqlx($_POST['new_password_repeat']);

	// Check for errors
	if ($old_password!==$member[password]) { $error[]=$lang_errors['invalid_current_password']; }
	if (empty($old_password)) { $error[]=$lang_errors['empty_current_password']; }
	if (empty($new_password)) { $error[]=$lang_errors['empty_new_password']; }
	if (empty($new_password_repeat)) { $error[]=$lang_errors['empty_new_password_repeat']; }
	if ($new_password!==$new_password_repeat) { $error[]=$lang_errors['new_passwords_dont_match']; }
		
	$check_user_exists=mysql_query("SELECT * from `members` WHERE `user_id`='$member[user_id]'");
	if (@mysql_num_rows($check_user_exists)==0) { $error[]=$lang_errors['invalid_member']; }
	// If no errors...continue
	if (count($error)=="0")	{
		mysql_query("UPDATE `members` SET `password`='$new_password' WHERE `user_id`='$member[user_id]'");
		// update session
		$_SESSION['member']=fetch_member($member[user_id]);
		header("Location: $config[base_url]/profile/passwd/success");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->assign('page_title',$lang_members[change_password]);
		$t->display("frontend/$template/member_edit_password.tpl");		
	}
}
// EOF "CHANGE PASSWORD"
elseif (empty($request[1])) {
	$t->assign('page_title', $lang_globals['member_panel']);
	$member=$_SESSION['member'];
	$t->assign('page_title',$lang_members[menu_profile]);
	$t->display("frontend/$template/member_index.tpl");
}
?>