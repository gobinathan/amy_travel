{include file="frontend/$template/header.tpl" title=$title}
{literal}
<script language="javascript">

var gAutoPrint = true;

function processPrint(){

if (document.getElementById != null){

var html = '<HTML>\n<HEAD>\n';

if (document.getElementsByTagName != null){

var headTags = document.getElementsByTagName("head");

if (headTags.length > 0) html += headTags[0].innerHTML;

}

html += '\n</HE' + 'AD>\n<BODY>\n';

var printReadyElem = document.getElementById("wire_details");

if (printReadyElem != null) html += printReadyElem.innerHTML;

else{

alert("Error, no contents.");

return;

}

html += '\n</BO' + 'DY>\n</HT' + 'ML>';

var printWin = window.open("","processPrint");

printWin.document.open();

printWin.document.write(html);

printWin.document.close();

if (gAutoPrint) printWin.print();

} else alert("Browser not supported.");

}
</script>
{/literal}
		<div id="wrapper">
			<div id="content">
					<center>{parse_banner position="center"}</center>
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
<h1 align="center">{#gw_bank_wire#}</h1><br/>
<b>{#bw_payment_tip#}</b><br/><br/>
<div id="wire_details" style="font-size:14px;">
<table border="0">
<tr><td>{#bw_recipient#}:</td><td> <b>{$payment_gw.bw_recipient}</b></td></tr>
<tr><td>{#total_price#}:</td><td> <b>{$res.total_price}</b> {$listing.currency}</td></tr>
<tr><td>{#currency#}: </td><td><b>{$payment_gw.bw_currency}</b></td></tr>
<tr><td>{#bw_bank_name#}: </td><td><b>{$payment_gw.bw_bank_name}</b></td></tr>
<tr><td>{#bw_bank_phone#}: </td><td><b>{$payment_gw.bw_bank_phone}</b></td></tr>
<tr><td>{#bw_bank_address1#}: </td><td><b>{$payment_gw.bw_bank_address1}</b></td></tr>
<tr><td>{#bw_bank_address2#}: </td><td><b>{$payment_gw.bw_bank_address2}</b></td></tr>
<tr><td>{#bw_bank_city#}: </td><td><b>{$payment_gw.bw_bank_city}</b></td></tr>
<tr><td>{#bw_bank_state#}: </td><td><b>{$payment_gw.bw_bank_state}</b></td></tr>
<tr><td>{#bw_bank_zip#}: </td><td><b>{$payment_gw.bw_bank_zip}</b></td></tr>
<tr><td>{#bw_bank_country#}: </td><td><b>{$payment_gw.bw_bank_country}</b></td></tr>
<tr><td>{#bw_account_number#}: </td><td><b>{$payment_gw.bw_account_number}</b></td></tr>
<tr><td>{#bw_swift_code#}: </td><td><b>{$payment_gw.bw_swift_code}</b></td></tr>
<tr><td>{#bw_iban#}: </td><td><b>{$payment_gw.bw_iban}</b></td></tr>
</table>
</div>
<br/><br/>
<h2 align="center">
<input type="button" name="print" value="{#hint_listing_print#}" class='quotebttn' onClick="javascript:void(processPrint());">
</h2>
				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
			</div>
		</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}

