<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={$language_encoding}" />
	<link rel="stylesheet" href="{$BASE_URL}/templates/admin/style.css" type="text/css" />
	<link rel="stylesheet" href="{$BASE_URL}/templates/admin/tabcontent.css" type="text/css" />	
	<script src="{$BASE_URL}/js/main.js" type="text/javascript"></script>
  <!-- calendar stylesheet -->
  <link rel="stylesheet" type="text/css" media="all" href="{$BASE_URL}/js/calendar-style.css" title="win2k-cold-1" />
  <!-- main calendar program -->
  <script type="text/javascript" src="{$BASE_URL}/js/calendar.js"></script>
  <!-- language for the calendar -->
  <script type="text/javascript" src="{$BASE_URL}/js/calendar-en.js"></script>
  <!-- the following script defines the Calendar.setup helper function, which makes
       adding a calendar a matter of 1 or 2 lines of code. -->
  <script type="text/javascript" src="{$BASE_URL}/js/calendar-setup.js"></script>
{if $load_google_api}
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
{/if}
{$google_map_header}
{$google_map_js}
   <!-- necessary for google maps polyline drawing in IE -->
    <style type="text/css">
      v\:* {ldelim}
        behavior:url(#default#VML);
     {rdelim} 
    </style>
</head>
<body {$body_onload}>
	<div class="content">
		<div class="header">
			<div class="top_info">
				<div class="top_info_right">
					<p>{#last_login#}: {$last_login}</p>
					<a href="login.php?logout" target="_top">EXIT (Logout)</a>
				</div>		
				<div class="top_info_left">
					{#admin_panel#}
<br/>
{if count($languages_array) > "1"}
{#prefered_language#}: <select name="select" onChange="window.location=this.value;" class="icon-menu">
{foreach from=$languages_array item=lang}
<option style="background-image:url({$BASE_URL}/uploads/flags/{$lang.lang_name}.gif);" value="?{$get_query}lang={$lang.lang_name}" {if $lang.lang_name eq "$language"}selected{/if}>{$lang.lang_title}</option>
{/foreach}
</select>
{/if}
				</div>

			</div>
			<div class="logo">
				<h1><a href="http://raysolutions.net/" target="_blank" title="Raysolutions"><span class="dark">Ray</span>solutions</a></h1>
Server time: {$time|date_format:"%d/%b/%Y %H:%M"}
			</div>
		</div>
{if $demo_mode}<font color="red">DEMO MODE<br/><b>ADD, UPDATE, DELETE</b> queries and <b>File Operations</b> are <b>DISABLED</b>!</font>{/if}