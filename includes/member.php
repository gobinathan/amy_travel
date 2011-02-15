<?php
$member=$_SESSION['member'];
if (is_numeric($member[user_id])) {
	$t->assign('member',$member);
}
$t->config_load("$language/members.lng");
$lang_members=parse_ini_file($config['root_dir']."/languages/$language/members.lng");
?>