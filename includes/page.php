<?php
// START "FETCH PAGE DETAILS"
$getpage=mysql_query("SELECT * from `pages` LEFT JOIN `pages_text` ON (pages.page_id=pages_text.page_id) WHERE `uri`='$request[1]' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang'),`position`");
$page_array=@mysql_fetch_array($getpage);
$t->assign('page',$page_array);
$t->assign('title', $page_array[title]);

// EOF "FETCH PAGE DETAILS"

$t->display("frontend/$template/page.tpl");
?>