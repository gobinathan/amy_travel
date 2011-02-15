<html><head>
<script type="text/javascript">
{literal}
function hiding(what) { 
      var tmp = document.getElementById(what); 
      if (tmp.style.display == 'none') { 
        tmp.style.display = 'block'; 
      } else { 
        tmp.style.display = 'none'; 
      } 
}
</script>
</head>
<style>
body{
margin:0px;
font-family: arial, sans-serif;
color:#444;
background:#FFF;
}
#nav ul{
padding: 0;
margin: 0;
list-style-type: none;
list-style-image: none;
}

#nav ul li{
list-style-image: none;
}

#nav a{
padding: 5px 20px;
text-decoration:none;
font-weight:bold;
font-size:14px;
color: #E0691A;
border:1px solid #737373;
display:block;
width:160px;
max-width:160px;
margin:1px 0;
}


#nav a.expand{
background: url(images/expand_btn.gif) no-repeat left;
}

#nav ul.hiddenSlide li a{
font-size:12px;
margin-left:10px;
width:130px;
background:#FFF;
}
#nav ul.hiddenSlide li:hover a:hover {
font-size:12px;
margin-left:10px;
width:130px;
background:#000;
}
{/literal}
</style>
{* LOAD IE CSS TABS FIX *}
<!--[if IE]>
<style>
{literal}
#nav a{
	width:200px;
}
#nav ul.hiddenSlide li a {
	width:180px;
}
{/literal}
</style>
<![EndIf]-->
{* EOF IE CSS TABS FIX *}
<body {$body_onload}>
			<div id="nav">
				<a href="main.php" target="main">{#menu_main#}</a>
				<a href="{$BASE_URL}" target="_blank">{#menu_view_live_site#}</a>
				<a onClick="javascript:hiding('listings');" style="cursor:pointer;" title="Click to Expand" class="expand">{#menu_listings#}</a>
					<ul id="listings" class="hiddenSlide" style="display: none;">
						<li><a href="listings.php" title="List Edit Listings" target="main">Browse</a></li>
						<li><a href="listings.php?add" title="Add new Listing" target="main">Add listing</a></li>						
						<li><a href="categories.php" title="List Edit Categories" target="main">{#menu_categories#}</a></li>												
					</ul>												
					<a href="members.php" title="Browse/Edit members" target="main">{#menu_members#}</a>
				<a onClick="javascript:hiding('bookings');" style="cursor:pointer;" title="Click to Expand" class="expand">Bookings</a>
					<ul id="bookings" class="hiddenSlide" style="display: none;">
						<li><a href="bookings.php" title="Reservations" target="main">Reservations</a></li>
						<li><a href="transactions.php" title="Orders" target="main">Transactions</a></li>						
{*
						<li><a href="payment_options.php" title="Custom Payment Options that can be included in the booking" target="main">Additional Charges</a></li>
						<li><a href="promo_codes.php" title="Promo Codes" target="main">Promo Codes</a></li>																			
*}													
					</ul>
				<a onClick="javascript:hiding('locations');" style="cursor:pointer;" title="Click to Expand" class="expand">{#menu_locations#}</a>
					<ul id="locations" class="hiddenSlide" style="display: none;">
						<li><a href="locations.php" title="Locations" target="main">{#menu_location_types#}</a></li>					
						<li><a href="countries.php" title="Countries" target="main">{#menu_countries#}</a></li>
						<li><a href="states.php" title="States" target="main">{#menu_states#}</a></li>
					</ul>
				<a onClick="javascript:hiding('types');" style="cursor:pointer;" title="Click to Expand" class="expand">{#menu_types#}</a>
					<ul id="types" class="hiddenSlide" style="display: none;">
						<li><a href="types_c.php?add" title="Add" target="main">{#menu_types_add#}</a></li>
						<li><a href="types_c.php" title="List" target="main">{#menu_types_list#}</a></li>
						<li>&nbsp;&nbsp;</li>
						{foreach from=$types_c item=type_c}
							<li><a href="types.php?manage={$type_c.type_c_id}" target="main">{$type_c.title}</a></li>
						{/foreach}
					</ul>								
				<a href="news.php" title="News" target="main">{#menu_news#}</a>
				<a href="articles.php" title="Articles" target="main">{#menu_articles#}</a>				
				<a onClick="javascript:hiding('pages');" style="cursor:pointer;" title="Click to Expand" class="expand">{#menu_pages#}</a>
					<ul id="pages" class="hiddenSlide" style="display: none;">
						<li><a href="pages.php?add" title="Add Page" target="main">{#menu_pages_add#}</a></li>
						<li><a href="pages.php" title="List Edit Pages" target="main">{#menu_pages_browse_edit#}</a></li>
					</ul>								
				<a onClick="javascript:hiding('marketing');" style="cursor:pointer;" title="Click to Expand" class="expand">{#menu_marketing#}</a>
					<ul id="marketing" class="hiddenSlide" style="display: none;">
						<li><a href="marketing.php" title="E-Mail List" target="main">{#menu_marketing_list#}</a></li>
						<li><a href="marketing.php?add" title="Add E-Mail" target="main">{#menu_marketing_add_email#}</a></li>						
						<li><a href="marketing.php?send" title="Send Newsletter" target="main">{#menu_marketing_send_newsletter#}</a></li>									
						<li><a href="marketing.php?history" title="Newsletter History" target="main">{#menu_marketing_history#}</a></li>																		
					</ul>
				<a onClick="javascript:hiding('settings');" style="cursor:pointer;" title="Click to Expand" class="expand">{#menu_settings#}</a>
					<ul id="settings" class="hiddenSlide" style="display: none;">
						<li><a href="settings.php" title="Site Settings" target="main">{#menu_site_settings#}</a></li>
						<li><a href="languages.php" title="Languages" target="main">{#menu_languages#}</a></li>
						<li><a href="currency.php" title="Available Currencies" target="main">Currency</a></li>
						<li><a href="payment_gw.php" title="Payment Settings" target="main">Payment Gateways</a></li>
						<li><a href="ads.php" title="Banners/Ads" target="main">{#banner_ads#}</a></li>
						<li><a href="email_templates.php" title="E-Mail Templates" target="main">{#menu_email_templates#}</a></li>
						<li><a href="admins.php" title="Admins" target="main">{#menu_admins#}</a></li>						
						<li><a href="backup.php" target="main">{#menu_backup#}</a></li>
					</ul>				
				<a href="stats/" target="main">{#menu_stats#}</a>				
				<a href="map.php" target="main">{#menu_map#}</a>
				<a href="login.php?logout" target="_top">{#menu_logout#}</a>
			</div>
