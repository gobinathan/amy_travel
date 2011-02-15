<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{* SET DYNAMIC LANGUAGE ENCODING *}<meta http-equiv="Content-Type" content="text/html; charset={$language_encoding}" />
{* SET DYNAMIC TITLE *}<title>{if $request.0 OR $member_logged_in}{$title|stripslashes} - {/if}{$conf.site_title|stripslashes}</title>
{* DYNAMIC META KEYWORDS *}<meta name="keywords" content="{if $meta_keywords eq ""}{$conf.meta_keywords}{else}{$meta_keywords}{/if}" />
{* DYNAMIC META DESCRIPTION *}<meta name="description" content="{if $meta_description eq ""}{$conf.meta_description}{else}{$meta_description}{/if}" />
{* LOAD MAIN STYLESHEET *}<link rel="stylesheet" type="text/css" href="{$BASE_URL}/templates/frontend/{$template}/css/main.css" />
{* LOAD MAIN JS LIBRARY *}<script src="{$BASE_URL}/js/main.js" type="text/javascript"></script>
{* LOAD TABCONTENT STYLESHEET *}<link rel="stylesheet" type="text/css" href="{$BASE_URL}/templates/frontend/{$template}/css/tabcontent.css" />
{* LOAD TABCONTENT JS LIBRARY *}<script src="{$BASE_URL}/js/tabcontent.js" type="text/javascript"></script>
{* RSS CONTENT LINK *}<link rel="alternate" type="application/rss+xml" title="RSS" href="{$BASE_URL}/rss/{$language}">
{* START WHEN MEMBER IS LOGGED IN *}
  <link rel="stylesheet" type="text/css" media="all" href="{$BASE_URL}/js/calendar-style.css" title="win2k-cold-1" />
  <script type="text/javascript" src="{$BASE_URL}/js/calendar.js"></script>
  <script type="text/javascript" src="{$BASE_URL}/js/calendar-en.js"></script>
  <script type="text/javascript" src="{$BASE_URL}/js/calendar-setup.js"></script>
	{if $load_google_api}
    	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
		<style type="text/css">
		{literal}
		v\:* {
		behavior:url(#default#VML);
		} 
		{/literal}
		</style>    	
	{/if}
{* EOF WHEN MEMBER IS LOGGED IN *}
<link rel="stylesheet" type="text/css" href="{$BASE_URL}/js/menu/ddsmoothmenu.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="{$BASE_URL}/js/menu/ddsmoothmenu.js"></script>
{literal}
<script type="text/javascript">
ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})
</script>
{/literal}
</head>
<body>
<div id="container">
<center>
<a href="#" onClick="javascript:window.open('{$baseurl}/interactive_map', 'interactive_map', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=1100,height=700,left=120,top=60');return false;" title="{#interactive_map#}"><img src="{$BASE_URL}/templates/frontend/{$template}/img/google_maps_icon_small.png" border="0" class="imgtrans"></a>
<img src="{$BASE_URL}/templates/frontend/{$template}/img/header_top.png" border="0" style="margin-bottom:-3px;">
<a href="{$baseurl}/contact" title="{#contact#}"><img src="{$BASE_URL}/templates/frontend/{$template}/img/contact_icon_small.png" border="0" class="imgtrans"></a>
<br/>
<img src="{$BASE_URL}/templates/frontend/{$template}/img/bg_header.gif" border="0" style="margin-bottom:-10px;"></center>
{* START HEADER CONTENT *}
	<div id="header">
		<div id="logo">
			<h2><a href="{$BASE_URL}">{$conf.slogan}</a></h2>
		</div>		
<br/>
<center>{parse_banner position="top"}</center>
{* EOF HEADER CONTENT *}
	</div>
{* START HEADER MENU *}
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="{$BASE_URL}">{#index_page#}</a></li>
{foreach from=$categories item=category}
<li><a href="{$baseurl}/category/{$category.uri}">{$category.title|stripslashes} {if count($category.subcats)}<img src="{$BASE_URL}/js/menu/down.gif">{/if}</a>
  <ul>
{foreach from=$category.subcats item=subcat}
<li><a href="{$baseurl}/category/{$category.uri}/{$subcat.uri}">{$subcat.title|stripslashes}  {if count($subcat.subcats)}<img src="{$BASE_URL}/js/menu/right.gif">{/if}</a></li>
{/foreach}
  </ul>
</li>
{/foreach}
{* START PAGES TOP MENU *}
{foreach from=$pages_up item=page}
   	<li><a href="{$baseurl}/page/{$page.uri}">{$page.title|stripslashes}</a></li>
{/foreach}
{* EOF PAGES TOP MENU *}
</ul>
<br style="clear:left;"/>
</div>
{* EOF HEADER MENU *}
{* START HEADER MENU - ALTERNATIVE! // DISABLED //
<div id="menu">
<ul>
		{foreach from=$pages_up item=page}
   	    	<li><a href="{$baseurl}/page/{$page.uri}">{$page.title|stripslashes}</a></li>
		{/foreach}
		{foreach from=$categories item=category}
   	    	<li><a href="{$baseurl}/category/{$category.uri}">{$category.title|stripslashes} ({count_active_listings cat_id=$category.cat_id})</a>
			<ul>
		{foreach from=$category.subcats item=subcategory}
        <li><a href="{$baseurl}/category/{$category.uri}/{$subcategory.uri}"><i>{$subcategory.title|stripslashes}</i> ({count_active_listings cat_id=$subcategory.cat_id})</a></li>
	    {/foreach}
			</ul>
			</li>
		{/foreach}
<br/></ul>
</div>
 EOF HEADER MENU - ALTERNATIVE! // DISABLED // *}	