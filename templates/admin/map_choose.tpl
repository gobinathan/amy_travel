<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>{$listing.title}</title>
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
width: 950px;
}
.curlycontainer .innerdiv{
background: transparent url(images/brcorner.gif) bottom right no-repeat;
position: relative;
left: 2px;
top: 2px;
padding: 1px 4px 15px 5px;
}
</style>
{/literal}
{$google_map_header}
{$google_map_js}
   <!-- necessary for google maps polyline drawing in IE -->
    <style type="text/css">
      v\:* {ldelim}
        behavior:url(#default#VML);
     {rdelim} 
    </style>
<SCRIPT LANGUAGE="JavaScript">
{literal}
function updateParent() {
    opener.document.editfrm.gmap_location.value = document.childForm.geo_coords.value;
    self.close();
    return false;
}
{/literal}
</SCRIPT>    
</head>
<body onload="onLoad();initialize();">
<div class="curlycontainer">
<div class="innerdiv">
    <form action="#" onsubmit="showAddress(this.address.value); return false">
      <p>
        <input type="text" size="60" name="address" value="{$gmap_location}" />
        <input type="submit" value="{#map_search_by_address#}" />
      </p>
</form>
<FORM NAME="childForm" onSubmit="return updateParent();"><input type="text" name="geo_coords" id="geo_coords" size="50" /><input type="submit" value="{#save_geo_coords#}" /></form>
{#map_choose_hint#}
    <table>
      <tr>
        <td>{$google_map}</td>
      </tr>
    </table>
</div></div>
<input class="submit" name="Button" type="button" onClick="window.close();" value="Close" />
</body></html>