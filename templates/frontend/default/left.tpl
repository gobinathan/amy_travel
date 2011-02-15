	<div id="sidebar-left">
{parse_banner position="left"}
	{* START SUBCATEGORIES BOX *}
{if $main_category}
		{include file="frontend/$template/round_corner_top.tpl"}
		<div id="navigation">
		<span class="title"><center>{$main_category.title|stripslashes}</center></span>
		    <ul>
		{foreach from=$subcategories item=category}
   	    	<li><a href="{$baseurl}/category/{$main_category.uri}/{$category.uri}" {if $request.2 eq $category.uri OR $listing.cat_id eq $category.cat_id}class="selected"{/if}>{$category.title|stripslashes} ({count_active_listings cat_id=$category.cat_id})</a></li><br/>
		{/foreach}

		    </ul>
		</div>
		{include file="frontend/$template/round_corner_bottom.tpl"}
{/if}
	{* EOF SUBCATEGORIES BOX *}

	{* START ARTICLES BOX *}
	{if count($articles)}
		{include file="frontend/$template/round_corner_top.tpl"}
		<div id="navigation">
		<span class="title"><center>{#articles#}</center></span>
		<ul>
		{foreach from=$articles item=article}
   	    	<li><a href="{$baseurl}/article/{$article.article_id}">{$article.title|stripslashes}</a></li><br/>
		{/foreach}
		</ul>
		</div>
		{include file="frontend/$template/round_corner_bottom.tpl"}
	{/if}
	{* EOF ARTICLES BOX *}
	{* START LOCATIONS BOX *}
		{include file="frontend/$template/round_corner_top.tpl"}
		<div id="navigation">
		<span class="title"><center>{#location#}</center></span>
		<ul>
		{foreach from=$locations item=location}
   	    	<li><a href="{$baseurl}/location/{$location.uri}" {if $request.1 eq $location.uri OR $listing.location_id eq $location.location_id}class="selected"{/if}>{$location.title|stripslashes}</a></li><br/>
		{/foreach}
		</ul>				
		</div>
		{include file="frontend/$template/round_corner_bottom.tpl"}
	{* EOF LOCATIONS BOX *}
	{* START NEWSLETTER BOX *}
		{include file="frontend/$template/round_corner_top.tpl"}
		<span class="title"><center>{#newsletter_title#}</center></span>
		{include file="frontend/$template/newsletter_box.tpl"}
		{include file="frontend/$template/round_corner_bottom.tpl"}
	{* EOF NEWSLETTER BOX *}				

	</div>
