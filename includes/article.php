<?php
// START "FETCH ARTICLE DETAILS"
$getarticle=mysql_query("SELECT * from `articles` LEFT JOIN `articles_text` ON (articles.article_id=articles_text.article_id) WHERE articles.article_id='$request[1]' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
$article_array=@mysql_fetch_array($getarticle);
$t->assign('article',$article_array);
$t->assign('title', $article_array[title]);
// EOF "FETCH ARTICLE DETAILS"

// START "FETCH ALL ARTICLES"
$articles=array();
$getarticles=mysql_query("SELECT * from `articles` LEFT JOIN `articles_text` ON (articles.article_id=articles_text.article_id) WHERE (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
while($article=@mysql_fetch_array($getarticles)) {
	if (multiarray_search($articles, 'article_id', $article[article_id]) == "-1") {
			array_push($articles, $article);
	}
}
$t->assign('articles',$articles);
// EOF "FETCH ALL ARTICLES"

$t->display("frontend/$template/article.tpl");
?>