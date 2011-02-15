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
{include file="frontend/$template/round_corner_top.tpl"}
				<div class="padding">
					<div id="search">
<form method="post" action="{$baseurl}/search" name="frmSearch">
<center>
<label for="category"><select name="cat_id" style="width:140px;">
<option value="0">{#category#}</option>
<option value="0">-</option>
{foreach from=$categories item=category}
<option value="{$category.cat_id}" {if $smarty.session.search_cat_id eq $category.cat_id}selected{/if} style="background-color: black;color:white;">{$category.title}</option>
	{foreach from=$category.subcats item=subcat}
	<option value="{$subcat.cat_id}" {if $smarty.session.search_cat_id eq $subcat.cat_id}selected{/if}>&nbsp;&nbsp;{$subcat.title}</option>
	{/foreach}
{/foreach}
</select>
</label>
&nbsp;&nbsp;<label for="country"><select name="country_id" id="country_id" style="width:140px;" onchange="getCityList(this)">
<option value="0">{#country#}</option>
<option value="0">-</option>
{foreach from=$countries item=country}
<option value="{$country.country_id}">{$country.title|stripslashes}</option>
{/foreach}
</select><select id="city" name="city" style="width:100px;"><option value="0">{#city#}</option></select>
</label><br/>
<label for="price_from">{#price#} {#from#}: <input type="text" name="price_from" size="5" value="{$smarty.session.search_price_from}" /></label>
&nbsp;<label for="price_to">{#price#} {#to#}:  <input type="text" name="price_to" size="5" value="{$smarty.session.search_price_to}" /></label><br/>
<br/><label for="keyword"><input type="text" name="keyword" value="{#search_keyword#}" onFocus="javascript:if(this.value=='{#search_keyword#}'){literal}{{/literal}this.value='';{literal}}{/literal}" onBlur="javascript:if(this.value==''){literal}{{/literal}this.value='{#search_keyword#}';{literal}}{/literal};" size="18" /></label><br/><br/>
<input type="hidden" name="search" value="simple" />
<input type="submit" value="{#search_submit#}" class="submitbg" />
</form>
</center>
	 				</div>
				</div>
{include file="frontend/$template/round_corner_bottom.tpl"}
				
