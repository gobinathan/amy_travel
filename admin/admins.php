<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");

if (isset($_GET['edit'])) {
	$user_id=sqlx($_GET['edit']);
	$sql=mquery("SELECT * from `admins` WHERE `admin_id`='$user_id'");
	if (@mysql_num_rows($sql)=="0") { $error[]=$lang_errors['invalid_admin']; }
	if (count($error)=="0") {
		$user=@mysql_fetch_array($sql);
	   	$t->assign('admin',$user);
		$t->assign('role',$roll);
		
		$t->display("admin/edit_admin.tpl");
		$t->display("role/edit_admin.tpl");
	} else{
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}	
}

elseif (isset($_POST['edit_admin'])) {
	$user_id=sqlx($_POST['edit_admin']);
	$username=sqlx($_POST['username']);
	$password=sqlx($_POST['password']);
	$role    =sqlx($_POST['role']);
	// Check for errors
	if (empty($username)) { $error[]="No Username!"; }
	if(empty($role)){$error[]="No Roll";}
//  	if (empty($password)) { $error[]="No Password!"; }
	$check_user_exists=mquery("SELECT * from `admins` WHERE `admin_id`='$user_id'");
	if (@mysql_num_rows($check_user_exists)==0) { $error[]=$lang_errors['invalid_admin']; }
	// If no errors...continue
	if (count($error)=="0")	{
   		mquery("UPDATE `admins` SET `username`='$username' WHERE `admin_id`='$user_id'");
		$passwd=cryptPass($password,$username);
		if (!empty($password)) { mquery("UPDATE `admins` SET `password`='$passwd'  WHERE `admin_id`='$user_id'"); }
		
		set_msg("Admin <b>$username</b> updated successfuly.");
		header("Location: $config[base_url]/admin/admins.php");
  	}else{ // Else Show errors
   		$t->assign('error',$error);
		$t->assign('error_count',count($error));
	}
}
elseif (isset($_GET['add'])) {
	
	$sql=mquery("SELECT * from `roles`");
	$roles=array();
	$r_id=array();
	while($user_role=mysql_fetch_array($sql)){
		
		$roles[]=$user_role['name'];
		$r_id[]=$user_role['r_id'];
		
	}
	
	
	$t->assign('user_role',$roles);
	$t->assign('user_id',$r_id);
	$t->display("admin/add_admin.tpl");
}
elseif (isset($_POST['add_admin'])) {
  $username=sqlx($_POST['username']);
  $password=sqlx($_POST['password']);
  $role    =sqlx($_POST['id']);



  
	// Check for errors
	if (empty($username)) { $error[]="No Username!"; }
  	if (empty($password)) { $error[]="No Password!"; }
	if (empty($role)) { $error[]="No roll!"; }
	$check_user_exists=mquery("SELECT * from `admins` WHERE `username`='$username'");
	if (@mysql_num_rows($check_user_exists)>0) { $error[]=$lang_errors['admin_username_exists']; }

	// If no errors...continue
	if (count($error)=="0")	{
		$passwd=cryptPass($password,$username);
    	mquery("INSERT into `admins` values ('','$username','$passwd','$role','never')");
		$new_user_id=@mysql_insert_id();
		set_msg("Admin <b>$username</b> added successfuly.");
		header("Location: $config[base_url]/admin/admins.php");
  	}else{ // Else Show errors
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/add_admin.tpl");		
	}
}
elseif (isset($_GET['delete'])) {
	$user_id=sqlx($_GET['delete']);
	$check_user_exists=mquery("SELECT * from `admins` WHERE `admin_id`='$user_id'");
	if (@mysql_num_rows($check_user_exists)==0) { $error[]=$lang_errors['invalid_admin']; }
	if ($user_id==$admin_id) { $error[]="Cannot delete yourself"; }
	// If no errors...continue
	if (count($error)=="0")	{
		if (isset($_GET['username'])) { $adm_username=sqlx($_GET['username']); }
    	mquery("DELETE from `admins` WHERE `admin_id`='$user_id'");
		set_msg("Admin <b>$adm_username</b> deleted successfuly.");
		header("Location: $config[base_url]/admin/admins.php");
  	}else{ // Else Show errors
		$sql=mquery("SELECT * from `admins`");
		$admins=array();
		while($user=@mysql_fetch_array($sql)) {
			array_push($admins, $user);
		}
   		$t->assign('admins',$admins);  	
    	$t->assign('error',$error);
		$t->assign('error_count',count($error));
		$t->display("admin/admins.tpl");
	}
}
else {
	
	
	$sql=mquery("SELECT * from `admins` t1, roles t2 where t1.role=t2.r_id ");
	$no_row=mysql_num_rows($sql);
	$admins=array();
	$r_id=array();
	$j=1;
	while($user=@mysql_fetch_array($sql)) {
		
		$r_id[$j]=$user['role'];
		$j++;
		array_push($admins, $user);
		
	}
	
	$u_roles=array();
	
		$sql1=mquery("SELECT * from roles ");
		
		while($name=@mysql_fetch_array($sql1)){
		
		$u_roles[$i]=$name['name'];
		
		}
		
		
	
	$t->assign('roles',$u_roles);
   	$t->assign('admins',$admins);
	$t->display("admin/admins.tpl");
}
?>