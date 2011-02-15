<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
@set_time_limit(720); #720sec
@session_cache_expire(720);   #720 min expire
include("checklogin.php");
include("common.php");
$now=date("mdY_His");

if (phpversion() > "5") {
	$ziplib="1";
	$t->assign('ziplib','1');
	include("../includes/zip.lib.php");
}else {
	$ziplib="0";
	$t->assign('ziplib','0');
}

if (isset($_GET['download_backup']) AND $demo=="0") {
	$backupfile=sqlx($_GET['download_backup']);
	sendDownloadFile("$config[root_dir]/uploads/backups/","$backupfile");	
}
elseif (isset($_GET['create_backup_languages']) AND $demo=="0") {
	zip_dir("languages", "uploads/backups/languages.$now.backup");
	set_msg("New Languages Backup created successfuly!");
	header("Location: $config[base_url]/admin/backup.php");
}
elseif (isset($_GET['create_backup_uploads']) AND $demo=="0") {
	zip_dir("uploads", "uploads/backups/uploads.$now.backup");
	set_msg("New Uploads Backup created successfuly!");
	header("Location: $config[base_url]/admin/backup.php");
}
elseif (isset($_GET['create_backup_images']) AND $demo=="0") {
	zip_dir("uploads/images", "uploads/backups/images.$now.backup");
	set_msg("New Images Backup created successfuly!");
	header("Location: $config[base_url]/admin/backup.php");
}
elseif (isset($_GET['unzip'])) {
if ($demo == "0") {
	$file=sqlx($_GET['unzip']);
	$file=after_last('/',$file);	
	if (file_exists("../uploads/backups/$file")) {
		set_msg("Backup <b>$file</b> unzipped successfuly!");
		unzip_dir ($file,"$config[root_dir]/");
		header("Location: $config[base_url]/admin/backup.php");
	}
}
}
elseif (isset($_POST['create_backup'])) {
if ($demo == "0") {
	$nodata   = false;      #!DO NOT DUMP TABLES DATA
	$nostruct = false;      #!DO NOT DUMP TABLES STRUCTURE
	$gzip     = sqlx($_POST['gzip']);      #!DO GZIP OUTPUT
	$download     = sqlx($_POST['download']);
	$send_email = sqlx($_POST['send_to_email']);
	if ($send_email=="on") {
		$emailto=sqlx($_POST['emailto']);
	}else{$emailto=false;}
	if ($gzip=="on") {$gzip=true;}else{$gzip=false;}
	if ($download=="on") {$download=true;}else{$download=false;}	
	require_once("../includes/mysqlbackup.class.php");
	$dump = new MySQLDump();
	$dbdata =  $dump->dumpDatabase($db_name,$nodata,$nostruct);
	$now=date("His");
	if($gzip == false) {
		$dump->sendAttachFile($dbdata,'text/html',"_$now.backup.sql",$download,$emailto);
	}
	else {
		$dump->sendAttachFileGzip($dbdata,"_$now.backup.sql.gz",$download,$emailto);
	}	
}
	set_msg("New DB Backup created successfuly!");
}
elseif (isset($_POST['restore_backup']) AND $_FILES['dump']['size']) {
if ($demo == "0") {
	$upload_dir = "../uploads/backups/";
	$now=time();
   $temp_name = $_FILES['dump']['tmp_name'];
   $file_name = $_FILES['dump']['name'];
   $file_type = $_FILES['dump']['type'];
   $file_size = $_FILES['dump']['size'];
   $result    = $_FILES['dump']['error'];
   $copy_to_file = $upload_dir.$file_name;
    move_uploaded_file($temp_name, $copy_to_file);
	set_msg("DB Backup <b>$copy_to_file</b> Uploaded and Restored successfuly!");
	header("Location: $config[base_url]/includes/mysqlbackup_restore.php?start=1&fn=$copy_to_file&foffset=0&totalqueries=0");	
}
}
elseif (isset($_GET['delete'])) {
if ($demo=="0") {
	$file=sqlx($_GET['delete']);
	$file=after_last('/',$file);
	if (file_exists("../uploads/backups/$file")) {
		@del_file("../uploads/backups/$file");
	}
	set_msg("DB Backup <b>$file</b> deleted successfuly!");
	header("Location: backup.php");
}
}
elseif (isset($_GET['restore_db'])) {
if ($demo == "0") {
	$file=sqlx($_GET['restore_db']);
	$file=after_last('/',$file);	
	if (file_exists("../uploads/backups/$file")) {
		set_msg("DB Backup <b>$file</b> Restored successfuly!");
		header("Location: $config[base_url]/includes/mysqlbackup_restore.php?start=1&fn=../uploads/backups/$file&foffset=0&totalqueries=0");		
	}
}
}
else {
	$new_b_db="0";
	$new_b_uploads="0";
	$new_b_images="0";
	$new_b_lang="0";
	$sqlbackups=array();
	$filebackups=array();
	if ($handle = opendir('../uploads/backups')) {
   		while (false !== ($file = readdir($handle))) {
       		if ($file != "." && $file != ".." && $file != ".htaccess") {
           		$backup['filename']=$file;
           		$backup['date']=date("F d Y H:i:s", filectime("../uploads/backups/$file"));
           		$backup['timestamp']=filectime("../uploads/backups/$file");           		
           		$backup['size']=round(filesize("../uploads/backups/$file")/1024,2)."KB";
				if (strpos($file,"sql") !== false) {
           			array_push($sqlbackups,$backup);
				}else{
					array_push($filebackups,$backup);
				}
				// Write the newest backups dates
				if (strpos($file,"sql") AND filectime("../uploads/backups/$file") > $new_b_db) { 
					$new_b_db=filectime("../uploads/backups/$file");
				}
				elseif (strpos($file,"ploads") AND filectime("../uploads/backups/$file") > $new_b_uploads) { 
					$new_b_uploads=filectime("../uploads/backups/$file");
				}
				elseif (strpos($file,"mages") AND filectime("../uploads/backups/$file") > $new_b_images) { 
					$new_b_images=filectime("../uploads/backups/$file");
				}
				elseif (strpos($file,"anguages") AND filectime("../uploads/backups/$file") > $new_b_lang) { 
					$new_b_lang=filectime("../uploads/backups/$file");
				}
			}
   		}
   		closedir($handle);
	}
	$sqlbackups=sortArrayByField($sqlbackups,"timestamp",true);
	$t->assign('sqlbackups',$sqlbackups);
	$filebackups=sortArrayByField($filebackups,"timestamp",true);
	$t->assign('filebackups',$filebackups);
	$t->assign('newest_backup_db',$new_b_db);
	$t->assign('newest_backup_uploads',$new_b_uploads);
	$t->assign('newest_backup_images',$new_b_images);
	$t->assign('newest_backup_lang',$new_b_lang);
	
	$t->display("admin/backup.tpl");
}
?>
