<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
$t = & new_smarty();
$t->assign('title', "Admin Panel");
$admin_id=$_SESSION['admin_id'];
if (!is_numeric($admin_id)) {
	echo '<HEAD>
<SCRIPT language="JavaScript">
<!--
top.location="'.$config[base_url].'/admin/login.php";
//-->
</SCRIPT>
</HEAD>';	  

//	header("Location: $config[base_url]/admin/login.php");
}else {
	$t->display("admin/index.tpl");
}
?>

