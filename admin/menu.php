<?php
include("../config.php");
include("../includes/functions.php");
@session_start();
include("checklogin.php");
include("common.php");

if (isset($_GET['select'])) {
	$select=sqlx($_GET['select']);
	$t->assign('body_onload','onLoad="hiding(\''.$select.'\');"');
}
$sql=mysql_query("SELECT * from `types_c` LEFT JOIN `types_c_text` ON (types_c.type_c_id=types_c_text.type_c_id) WHERE `lang`='$default_lang'");
$types_c=array();
while($type_c=@mysql_fetch_array($sql)) {
	array_push($types_c, $type_c);
}
$t->assign('types_c',$types_c);


$t->display("admin/menu.tpl");
?>
