<?php
@session_start();
@include("../config.php");
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
}
?>