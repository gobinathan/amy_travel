	{* START CATEGORIES BOX *}
		{include file="frontend/$template/round_corner_top.tpl"}
		<div id="navigation">
		<span class="title"><center>{#category#}</center></span>
		    <ul>
		{foreach from=$categories item=category}
   	    	<li><a href="{$baseurl}/category/{$category.uri}" {if $request.1 eq $category.uri AND ($request.2 eq "" OR $request.2 eq "page" OR $request.2 eq "sortby") OR $listing.cat_id eq $category.cat_id}class="selected"{/if}>{$category.title|stripslashes} ({count_active_listings cat_id=$category.cat_id})</a></li><br/>
		{* SHOW SUBCATEGORIES IF NOT EMPTY *}
   	    {if $request.1 eq $category.uri OR $listing.cat_id eq $category.cat_id OR $main_category.cat_id eq $category.cat_id}
		{foreach from=$category.subcats item=subcategory}
        <li class="sub"><a href="{$baseurl}/category/{$category.uri}/{$subcategory.uri}" {if $request.2 eq $subcategory.uri OR $listing.cat_id eq $subcategory.cat_id}class="selected"{/if}>&nbsp;&nbsp;&nbsp;&nbsp;<i>{$subcategory.title|stripslashes}</i> ({count_active_listings cat_id=$subcategory.cat_id})</a></li><br/>
	    {/foreach}
	    <br/>
		{/if}	   	    	
		{* EOF SHOW SUBCATEGORIES IF NOT EMPTY *}
		{/foreach}
<center><a href="{$baseurl}/search/all" style="float:top;">{#show_all_listings#}</a></center>
		    </ul>
		</div>
		{include file="frontend/$template/round_corner_bottom.tpl"}
	{* EOF CATEGORIES BOX *}
