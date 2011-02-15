{include file="frontend/$template/header.tpl" title=$title}
		<div id="wrapper">
			<div id="content">
					{include file="frontend/$template/search_box.tpl"}
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
<h2>{$article.title|stripslashes}</h2>
{parse_banner position="center"}
<p>{$article.article|stripslashes}</p>
<br/><center><input class="submit" name="Button" type="button" onClick="javascript:window.location='{if $smarty.server.HTTP_REFERER}{$smarty.server.HTTP_REFERER}{else}{$baseurl}{/if}'" value="{#continue#}" /></center><br/>

				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
			</div>
		</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}