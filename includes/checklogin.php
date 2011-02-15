<?php
$member=$_SESSION['member'];
if (!is_numeric($member['user_id'])) {
	header("Location: $config[base_url]/login");
}
//command
?>