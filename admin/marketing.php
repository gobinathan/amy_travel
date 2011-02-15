<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php"); // Check if user is logged in as admin
@set_time_limit(0);
include("common.php");

// START "EMAIL ADD"
if (isset($_GET['add'])) {
	$t->display("admin/add_subscriber_email.tpl");
}
// EOF "EMAIL ADD"

// START "EMAIL ADD SUBMIT FORM"
elseif (isset($_POST['add_email'])) {
  $email=sqlx($_POST['email']);
  $fullname=sqlx($_POST['fullname']);

	// Check for errors
	if (empty($email)) { $error[]=$lang_errors['marketing_email_empty']; }
	if (!is_email($email)) { $error[]=$lang_errors['marketing_email_invalid']; }
	if (empty($fullname)) { $error[]=$lang_errors['marketing_fullname_empty']; }
	// If no errors...continue
	if (count($error)=="0")	{
    	mquery("INSERT into `subscribers` values ('$email','$fullname','','0','1')");
		set_msg("Added new subscriber <b>$email</b>");		
		header("Location: $config[base_url]/admin/marketing.php");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/add_subscriber_email.tpl");		
	}	
}
// EOF "EMAIL ADD SUBMIT FORM"

// START "EMAIL DELETE"
elseif (isset($_POST['delete'])) {
	$unparsed_member = array_map('trim', $_POST['member']);
	// parse only the checked emails into an array
	$i=0;
	while (list($key, $input) = @each($unparsed_member)) {
		$i++;
		if ($input != '' || is_int($input))	{ 
			$unp_member[$i]=$input;
	   }
	}
	// then parse the comments from the array
	$member = array_map('trim', $unp_member);
	$i=0;
	while (list($key, $cid) = @each($member)) {
		$i++;
		if ($cid != '' || is_int($cid))	{ 
			mquery("DELETE from `subscribers` WHERE `email`='$cid'");
		}
	}
set_msg("Subscribers deleted successfuly.");		
  header("Location: $config[base_url]/admin/marketing.php");
}
// EOF "EMAIL DELETE"

// START "EMAIL SEND NEWSLETTER"
elseif (isset($_GET['send'])) {
	$getemails=mquery("SELECT * from `subscribers` WHERE `confirmed`='1'");
	$emails=array();
	while($email=@mysql_fetch_array($getemails)) {
		if (multiarray_search($emails, 'email', $email[email]) == "-1") {
			array_push($emails, $email);
		}
	}
   	$t->assign('emails',$emails);

	$getmembers=mquery("SELECT * from `members`");
	$members=array();
	while($member=@mysql_fetch_array($getmembers)) {
		if (multiarray_search($members, 'email', $member[email]) == "-1") {
			array_push($members, $member);
		}
	}
   	$t->assign('members',$members);

	$t->display("admin/send_newsletter.tpl");	
}
// EOF "EMAIL SEND NEWSLETTER"

// START "EMAIL SEND NEWSLETTER SUBMIT"
elseif (isset($_POST['num'])) {
	flush();
	$fullname=sqlx($_POST['fullname']);
	$from=sqlx($_POST['from']);
	$replyto=sqlx($_POST['replyto']);
	$subj=sqlx($_POST['subject']);
	$text=trim(stripslashes($_POST['msg']));
	$contenttype=sqlx($_POST['contenttype']);
	$unparsed_member = array_map('trim', $_POST['member']);

	if ($_FILES['file']['size']) {
		$file = $_FILES['file']['tmp_name'];
		$file_name = $_FILES['file']['name'];
		$file_type = $_FILES['file']['type'];
		$file_size = $_FILES['file']['size'];		
	}
// parse only the checked comments into an array
$i=0;
while (list($key, $input) = @each($unparsed_member)) {
	$i++;
	if ($input != '' || is_int($input))	{ 
		$unp_member[$i]=$input;
   }
}

#Open the file attachment if any, and base64_encode it for email transport
if ($file_name AND $demo == "0") {
	@copy($file, "../uploads/$file_name") or die($lang_errors['marketing_cannot_upload_file']);
	$content = fread(fopen($file,"r"),filesize($file));
	$content = chunk_split(base64_encode($content));
	$uid = strtoupper(md5(uniqid(time())));
	$name = basename($file);
}

// then parse the comments from the array
$member = array_map('trim', $unp_member);

$i=0;
while (list($key, $cid) = @each($member)) {
	$i++;
	if ($cid != '' || is_int($cid))	{ 
		$sql=mquery("SELECT * from `subscribers` WHERE `email`='$cid' AND `confirmed`='1'");
		if (@mysql_num_rows($sql)>"0") {
			$usr=@mysql_fetch_array($sql);
		}else{
			$sql=mquery("SELECT * from `members` WHERE `email`='$cid'");
			$usr=@mysql_fetch_array($sql);
		}
		$message=preg_replace('/{([^}]+)}/ie', '$usr["\1"]', $text);
		$subject=preg_replace('/{([^}]+)}/ie', '$usr["\1"]', $subj);
		
// Sending email
		print "$usr[email]....";
		flush();
		$header = "From: $fullname <$from>\nReply-To: $replyto\n";
		$header .= "MIME-Version: 1.0\n";
		if ($file_name AND $demo == "0") {
			$header .= "Content-Type: multipart/mixed; boundary=$uid\n";
			$header .= "--$uid\n";
		}
		$header .= "Content-Type: text/$contenttype\n";
		$header .= "Content-Transfer-Encoding: 8bit\n\n";
//		$header .= "$message\n";
		if ($file_name AND $demo == "0") {
			$header .= "--$uid\n";
			$header .= "Content-Type: $file_type; name=\"$file_name\"\n";
			$header .= "Content-Transfer-Encoding: base64\n";
			$header .= "Content-Disposition: attachment; filename=\"$file_name\"\n\n";
			$header .= "$content\n";
			$header .= "--$uid--";
		}
		if ($demo == "0") {
		// Send E-Mail
		if ($conf[use_smtp_mail] == "1") {
			mail_smtp($usr[email], $subject, $message);
		}else {
			mail($usr[email], $subject, $message, $header);
		}
		}
		$now=time();
		mquery("UPDATE `subscribers` SET `last_send`='$now',count_sent=count_sent+1 WHERE `email`='$cid'");		
		print "<img src='images/success.gif'><br/>";
		flush();
	}
 }
 
$now=time();
 mquery("INSERT into `newsletter_history` values ('','$from','$fullname','$replyto','$subj','$text','$file_name','$contenttype','$now','$admin_id','$i')"); 
 echo"<b>$i</b> emails sent.<br/>";
}
// EOF "EMAIL SEND NEWSLETTER SUBMIT"

// START "SHOW HISTORY"
elseif (isset($_GET['history'])) {
	$gethistory=mquery("SELECT * from `newsletter_history`");
	$history=array();
	while($hs=@mysql_fetch_array($gethistory)) {
			array_push($history, $hs);
	}
   	$t->assign('newsletter_history',$history);

	$t->display("admin/marketing_history.tpl");	
}
// EOF "SHOW HISTORY"

// START "SHOW HISTORY DETAILS"
elseif (isset($_GET['historyid'])) {
	$historyid=sqlx($_GET['historyid']);
	$gethistory=mquery("SELECT * from `newsletter_history` WHERE `newsletter_id`='$historyid'");
	$history=@mysql_fetch_array($gethistory);
   	$t->assign('history',$history);
	$t->display("admin/marketing_history_details.tpl");		
}
// EOF "SHOW HISTORY DETAILS"

// START "DELETE HISTORY"
elseif (isset($_GET['delhistory'])) {
	$h_id=sqlx($_GET['delhistory']);
	mquery("DELETE from `newsletter_history` WHERE `newsletter_id`='$h_id'");
	set_msg("Newsletter history <b>$h_id</b> deleted.");		
	echo "<html><head></head><body onload=\"";
	echo "opener.document.location.href='marketing.php?history';window.close();";
	echo "\"></body></html>";
}
// EOF "DELETE HISTORY"

// START "SHOW EMAILS/SUBSCRIBERS"
else {
	$getemails=mquery("SELECT * from `subscribers` WHERE `confirmed`='1'");
	$emails=array();
	while($email=@mysql_fetch_array($getemails)) {
		if (multiarray_search($emails, 'email', $email[email]) == "-1") {
			array_push($emails, $email);
		}
	}
   	$t->assign('emails',$emails);

	$t->display("admin/marketing_emails.tpl");
}
// EOF "SHOW EMAILS/SUBSCRIBERS"


?>
