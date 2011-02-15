{include file="frontend/$template/header.tpl" title=$title}
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

		<div id="wrapper">
			<div id="content">
			{parse_banner position="center"}
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
		<h2>{#advanced_search#}</h2><br/>
<form id="two" method="post" action="{$BASE_URL}/search/advanced" name="frmSearch">
<div id="frm">
<label for="category">
{#category#}:</label> <select name="cat_id">
<option value="0">-</option>
{foreach from=$categories item=scategory}
<option value="{$scategory.cat_id}" {if $smarty.session.search_cat_id eq $scategory.cat_id}selected{/if} style="background-color: black;color:white;">{$scategory.title}</option>
	{foreach from=$scategory.subcats item=dsubcat}
	<option value="{$dsubcat.cat_id}" {if $smarty.session.search_cat_id eq $dsubcat.cat_id}selected{/if}>&nbsp;&nbsp;{$dsubcat.title}</option>
	{/foreach}
{/foreach}
</select>
<br/>
<fieldset id="personal"><legend><b>{#search_location_group#}</b></legend>
<label for="country">
{#country#}:</label> <select name="country_id" id="country_id" style="width:140px;" onchange="getCityList(this)">
<option value="0">-</option>
{foreach from=$countries item=country}
<option value="{$country.country_id}">{$country.title|stripslashes}</option>
{/foreach}
</select>
</label><br/>
<label for="city">
{#city#}:</label> 
<select id="city" name="city" style="width:100px;"><option value="0">-</option></select>
<br/>
<label for="state">
{#state#}:</label> <select name="state_id">
<option value="0">-</option>
{foreach from=$states item=state}
	<option value="{$state.state_id}">{$state.title|stripslashes}</option>
{/foreach}
</select>
<br/>
<label for="location">
{#location#}:</label> <select name="location_id">
<option value="0">-</option>
{foreach from=$locations item=dlocation}
	<option value="{$dlocation.location_id}">{$dlocation.title|stripslashes}</option>
{/foreach}
</select>
<br/></fieldset>
<fieldset id="personal"><legend><b>{#price#}</b></legend>
<label>{#from#}: </label><input type="text" name="price_from" size="5" value="{$smarty.session.search_price_from}" /><br/>
<label>{#to#}:</label> <input type="text" name="price_to" size="5" value="{$smarty.session.search_price_to}" /><br/>
<label>{#currency#}:</label><select name="price_currency">
{foreach from=$currencies item=currency}
<option value="{$currency.code}" {if $conf.currency == $currency.code}selected{/if}>{$currency.code}</option>
{/foreach}
</select>
</fieldset><br/>
<label for="keyword"><input type="text" name="keyword" value="{#search_keyword#}" onFocus="javascript:if(this.value=='{#search_keyword#}'){literal}{{/literal}this.value='';{literal}}{/literal}" onBlur="javascript:if(this.value==''){literal}{{/literal}this.value='{#search_keyword#}';{literal}}{/literal};" size="18" /></label><br/>
<fieldset id="personal"><legend><div id="additional_info_bt_minus" style="float:left;display:none;">-</div><div id="additional_info_bt_plus" style="float:left;">+</div><a href="javascript:hiding('additional_info');hiding('additional_info_bt_minus');hiding('additional_info_bt_plus');" style="text-decoration:none;" onMouseOver="showhint('{#search_hint_additional_information#}', this, event, '150px')"><b>{#additional_information#}</b></a></legend><div id="additional_info" style="display:none;">
<fieldset style="float:left;"><legend>{#rooms#}</legend>
<label for="rooms_from">{#min#}:</label> <input type="text" name="rooms_from" size="5" /><br/>
<label for="rooms_to">{#max#}:</label>  <input type="text" name="rooms_to" size="5" />
</fieldset>
<fieldset style="float:left;"><legend>{#floors#}</legend>
<label for="floors_from">{#min#}:</label> <input type="text" name="floors_from" size="5" /><br/>
<label for="floors_to">{#max#}:</label>  <input type="text" name="floors_to" size="5" />
</fieldset><br/>
<fieldset style="float:left;"><legend>{#bathrooms#}</legend>
<label for="bathrooms_from">{#min#}:</label> <input type="text" name="bathrooms_from" size="5" /><br/>
<label for="bathrooms_to">{#max#}:</label>  <input type="text" name="bahtrooms_to" size="5" />
</fieldset>
<fieldset style="float:left;"><legend>{#year_built#}</legend>
<label for="yb_from">{#min#}:</label> <input type="text" name="yb_from" size="5" /><br/>
<label for="yb_to">{#max#}:</label>  <input type="text" name="yb_to" size="5" />
</fieldset><br/>
<fieldset style="float:left;"><legend>{#bedrooms#}</legend>
<label for="bedrooms_from">{#min#}:</label> <input type="text" name="bedrooms_from" size="5" /><br/>
<label for="bedrooms_to">{#max#}:</label>  <input type="text" name="bedrooms_to" size="5" />
</fieldset>
<br/>
</div>
</div>
{foreach from=$types_c item=type_c}
<fieldset id="personal"><legend><div id="type_{$type_c.type_c_id}_bt_minus" style="float:left;display:none;">-</div><div id="type_{$type_c.type_c_id}_bt_plus" style="float:left;">+</div><a href="javascript:hiding('{$type_c.type_c_id}');hiding('type_{$type_c.type_c_id}_bt_minus');hiding('type_{$type_c.type_c_id}_bt_plus');" style="text-decoration:none;" onMouseOver="showhint('{#hint_search_features#} <b>{$type_c.title}</b>', this, event, '150px')"><b>{$type_c.title}</b></a></legend>
<div id="{$type_c.type_c_id}" style="display:none;">
	{foreach from=$type_c.types item=type}
	<label class="label_types"><input type="checkbox" name="types[]" value="{$type.type_id}" title="{$type.title}" class="types_checkbox" />{$type.title}</label><br/>
	{/foreach}
</div>
	</fieldset>
{/foreach}

<div id="frm">
<br/>
<label>{#search_order_by#}:</label> <select name="order_by">
<option value="date">{#date_added#}</option>
<option value="price">{#price#}</option>
<option value="zip">{#zip#}</option>
<option value="category">{#category#}</option>
<option value="location">{#location#}</option>
<option value="country">{#country#}</option>
<option value="state">{#state#}</option>
<option value="member_id">{#search_member#}</option>
<option value="year_built">{#year_built#}</option>
<option value="floors">{#floors#}</option>
<option value="rooms">{#rooms#}</option>
</select><br/>
<label>{#listings_per_page#}:</label> <select name="per_page">
<option value="{$conf.items_per_page}">{$conf.items_per_page} ({#search_order_default#})</option>
<option value="{$conf.items_per_page}">-</option>
<option value="5">5</option>
<option value="10">10</option>
<option value="20">20</option>
<option value="30">30</option>
<option value="50">50</option>
<option value="100">100</option>
</select>
<br/><br/>
<input type="hidden" name="search" value="advanced" />
<div id="button"><a href="javascript:document.frmSearch.submit();">{#search_submit#}</a></div>
</form>
</div>

				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>

			</div>
		</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}