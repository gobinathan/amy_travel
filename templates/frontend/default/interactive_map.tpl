<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$language_encoding}" />
<meta name="keywords" content="{$conf[meta_keywords]}" />
<meta name="description" content="{$conf[meta_description]}" />
<script src="{$BASE_URL}/js/main.js" type="text/javascript"></script>
<title>{#interactive_map#}</title>
{if $multi_listing}
{literal}
<style type="text/css">
body { 
	font: 0.8em Tahoma, sans-serif; 
	line-height: 1.5em;
	background: #fff; 
	color: #454545; 
}

a {	color: #E0691A;	background: inherit;}
a:hover { color: #6C757A; background: inherit; }
.curlycontainer{
border: 1px solid #b8b8b8;
margin-bottom: 1em;
width: 1050px;
}
.curlycontainer .innerdiv{
background: transparent url(../images/brcorner.gif) bottom right no-repeat;
position: relative;
left: 2px;
top: 2px;
padding: 1px 4px 15px 5px;
}
#sidebar_map { 
float:left; 
height:270px; 
text-align:left; 
padding:05px; 
overflow:auto; 
}
/* Search */
#search {

	margin: 0;
	color: #000;
}

#search select {
	margin: 0 2em 1em .5em;
}

#search input {
	margin: 0 1em 0 .5em;
}
#button a {
font-family:Verdana;
font-size:13px;
font-weight: bold;
text-decoration:none;
color:#000000;
border-bottom:solid 2px #990000;
border-left:solid 2px #990000;
}
#button a:hover {
color:#990000;
text-decoration:none;
border-bottom:solid 2px #FFFFFF;
border-left:solid 2px #FFFFFF;
}
#two fieldset {display:block; padding:5px; font-family:verdana, sans-serif; line-height:1.5em; border:1px solid #000;color:#000; margin:15px 0 0 5px;}
#two legend { border:1px solid #666; font-family: "Courier New", Courier, mono; color:#555; font-size:1em; font-weight:normal; font-style:normal; margin-bottom:14px; padding:3px; width:190px; background:none;}

</style>
{/literal}
{/if}
{$google_map_header}
{$google_map_js}
   <!-- necessary for google maps polyline drawing in IE -->
    <style type="text/css">
      v\:* {ldelim}
        behavior:url(#default#VML);
     {rdelim} 
    </style>
<script type="text/javascript" src="{$relative_url}js/ajax.js"></script>
{literal}
<script type="text/javascript">
var ajax = new Array();

function getCityList(sel)
{
	var countryCode = sel.options[sel.selectedIndex].value;
	document.getElementById('city').options[0] = new Option('Loading...','0');	// 
	if(countryCode.length>0){
		var index = ajax.length;
		ajax[index] = new sack();
		
		ajax[index].requestFile = '{/literal}{$BASE_URL}/{literal}getCities/'+countryCode;	// Specifying which file to get
		ajax[index].onCompletion = function(){ createCities(index) };	// Specify function that will be executed after file has been found
		ajax[index].runAJAX();		// Execute AJAX function
	}
}

function createCities(index)
{
	var obj = document.getElementById('city');
	eval(ajax[index].response);	// Executing the response from Ajax as Javascript code	
}

</script>
{/literal}    
</head>
<body onload="onLoad()">
<div class="curlycontainer">
<div class="innerdiv">
    <table>
      <tr>
        <td valign="top">{$google_map}</td>
        <td valign="top">
{if $multi_listing}
<fieldset id="two"><legend>{#search_title#}</legend>     
<div id="search">
<form method="post" action="{$baseurl}/interactive_map/search" name="frmSearch">
<center><label for="category"><select name="cat_id" style="width:140px;">
<option value="0">{#category#}</option>
<option value="0">-</option>
{foreach from=$categories item=category}
<option value="{$category.cat_id}" {if $smarty.session.search_cat_id eq $category.cat_id}selected style="background-color:#99CC00;"{/if} style="background-color: black;color:white;">{$category.title}</option>
	{foreach from=$category.subcats item=subcat}
	<option value="{$subcat.cat_id}" {if $smarty.session.search_cat_id eq $subcat.cat_id}selected style="background-color:#99CC00;"{/if}>&nbsp;&nbsp;{$subcat.title}</option>
	{/foreach}
{/foreach}
</select>
</label><br/>
<label for="country"><select name="country_id" style="width:140px;" id="country_id" onchange="getCityList(this)">
<option value="0">{#country#}</option>
<option value="0">-</option>
{foreach from=$countries item=country}
<option value="{$country.country_id}" {if $smarty.session.search_country_id eq $country.country_id}selected style="background-color:#99CC00;"{/if}>{$country.title|stripslashes}</option>
{/foreach}
</select>
</label><br/>
<label for="city">
<select name="city" id="city">
<option value="0">{#city#}</option></select></label> 
<br/>
<label for="location">
<select name="location_id">
<option value="0">{#location#}</option>
<option value="0">-</option>
{foreach from=$locations item=dlocation}
	<option value="{$dlocation.location_id}" {if $smarty.session.search_location_id eq $dlocation.location_id}selected style="background-color:#99CC00;"{/if}>{$dlocation.title|stripslashes}</option>
{/foreach}
</select></label>
<br/>
<label for="keyword"><input type="text" name="keyword" value="{#search_keyword#}" onFocus="javascript:if(this.value=='{#search_keyword#}'){literal}{{/literal}this.value='';{literal}}{/literal}" onBlur="javascript:if(this.value==''){literal}{{/literal}this.value='{#search_keyword#}';{literal}}{/literal};" size="18" /></label><br/>
<label for="price_from">{#price#} {#from#}: <input type="text" name="price_from" size="5" value="{$smarty.session.search_price_from}" /></label><br/>
<label for="price_to">{#price#} {#to#}:  <input type="text" name="price_to" size="5" value="{$smarty.session.search_price_to}" /></label>
<input type="hidden" name="search" value="simple" />
</form>
<div id="button"><a href="javascript:document.frmSearch.submit();">&nbsp;&nbsp;{#search_submit#}</a></div><br/>
</center></form>
</fieldset>
</div>   
{/if}
<fieldset id="two"><legend>{#listings#}</legend>     
	{if count($listings)}
		{$google_map_sidebar}		
	{else}
		{#no_listings#}
	{/if}
		</fieldset>
		</td>
      </tr>
    </table>
{if $multi_listing}
<br/><center><input name="Button" type="button" onClick="window.close();" value="{#close#}" /></center>
{/if}
</div></div>
</body></html>