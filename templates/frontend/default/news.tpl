{include file="frontend/$template/header.tpl" title=$title}
		<div id="wrapper">
			<div id="content">
					{include file="frontend/$template/search_box.tpl"}
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
{parse_banner position="center"}
<h3>{$nw.title|stripslashes}</h3><span style="float:right;"><i>{$nw.date_added|date_format:"%d/%b/%Y"}</i></span><br/>
<i>{$nw.brief_description|stripslashes}</i> <br/>
<p>{$nw.full_article|stripslashes}</p>
<br/><br/>
				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
			</div>
		</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}
