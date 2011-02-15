<?php 

// setup our function for fetching stock data 
function fetch_listing($id) 
{ 
  global $default_lang;
	$sql=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE listings.listing_id='$id' AND `lang`='$default_lang'");		
	$listing=mysql_fetch_array($sql);
	return $listing;
} 

function smarty_function_fetch_listing($params, &$smarty) 
{ 
   // call the function 
   $listing_info = fetch_listing($params['id']); 
    
   // assign template variable 
   $smarty->assign($params['assign'], $listing_info); 
} 
?> 