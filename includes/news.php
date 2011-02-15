<?php
// START "FETCH NEWS DETAILS"
$getnews=mysql_query("SELECT * from `news` LEFT JOIN `news_text` ON (news.news_id=news_text.news_id) WHERE news.news_id='$request[1]' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
$news_array=@mysql_fetch_array($getnews);
$t->assign('nw',$news_array);
$t->assign('title', $news_array[title]);
$t->assign('meta_description',$news_array['brief_description']);
// EOF "FETCH NEWS DETAILS"

// START "FETCH NEWS"
$getnews=mysql_query("SELECT * from `news` LEFT JOIN `news_text` ON (news.news_id=news_text.news_id) WHERE `lang`='$default_lang' OR `lang`='$language' ORDER BY FIELD(lang,'$language','$default_lang'),`position`");
$news=array();
while($nws=@mysql_fetch_array($getnews)) {
	if (multiarray_search($news, 'news_id', $nws[news_id]) == "-1" AND $nws[news_id]!==$news_array[news_id]) {
		array_push($news, $nws);
	}
}
$t->assign('news',$news);
// EOF "FETCH NEWS"

$t->display("frontend/$template/news.tpl");
?>