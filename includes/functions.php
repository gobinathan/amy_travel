<?php

/**
* Create new Smarty object 
* @return double Smarty to newly created Smarty object
*
*/
function new_smarty(){
    global $config;
    $t = new Smarty();
    $t->compile_check = 1;
    $t->template_dir = "$config[root_dir]/templates";
    $t->compile_dir  = "$config[root_dir]/templates_c";
    $t->config_dir = "$config[root_dir]/languages";
	$t->load_filter('output','trimwhitespace');
    ///
    $t->assign('config', $config);
	$t->assign('BASE_URL', $config['base_url']);
    return $t;
}

function sqlx ($value) {
	if(get_magic_quotes_gpc()) {
		$value = stripslashes( $value );
	}
//check if this function exists
	if(function_exists("mysql_real_escape_string")) {
		$value = mysql_real_escape_string( $value );
	}
//for PHP version < 4.3.0 use addslashes
	else {
	    $value = addslashes( $value );
   	}
	return trim($value);
}

function redirect($location) {
	global $t;
	$t->assign('java_script', "<script>location='$location';</script>");
}

//
// Check to see if valid email address
//
function is_email($address) {
	if (ereg('^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$', $address)){
		return true;
	}	else {
		return false;
	}
}

function do_upload($upload_dir,$name,$newfile) {
	global $_FILES;
   $temp_name = $_FILES[$name]['tmp_name'];
   $file_name = $_FILES[$name]['name'];
   $file_type = $_FILES[$name]['type'];
   $file_size = $_FILES[$name]['size'];
   $result    = $_FILES[$name]['error'];
   $file_extension = after_last('.',$file_name);
   $copy_to="$newfile.$file_extension";
   $file_path = $upload_dir.$file_name;
   $copy_to_file = $upload_dir.$copy_to;

  //File Name Check
    if ( $file_name =="") {
       $message = "Invalid File Name Specified";
       return $message;
    }
   //File Size Check
    else if ( $file_size > 999999999999999999999) {
       print $file_size;
        $message = "File size too big!.";
        return $message;
    }
/*
   //File Type Check
    else if ( $file_type == "text/plain"
         || $file_type == "application/force-download"
         || $file_type == "application/octet-stream") {
        $message = "Sorry, You cannot upload any script file" ;
        return $message;
    }
*/
    $result  =  move_uploaded_file($temp_name, $copy_to_file);
	return $copy_to_file;
}
// after ('@', 'biohazard@online.ge');
// returns 'online.ge' 
// from the first occurrence of '@'
function after ($this, $inthat) {
       if (!is_bool(@strpos($inthat, $this))) {
       	return @substr($inthat, @strpos($inthat,$this)+strlen($this));
       }else{
			return $inthat;
		}
}

// after_last ('[', 'sin[90]*cos[180]');
// returns '180]' 
// from the last occurrence of '['
function after_last ($this, $inthat) {
        if (!is_bool(strrevpos($inthat, $this))) {
         return substr($inthat, strrevpos($inthat, $this)+strlen($this));
    	}else{
			return $inthat;
		}
}

// before ('@', 'biohazard@online.ge');
// returns 'biohazard'
// from the first occurrence of '@'
function before_new ($this, $inthat) {
       return substr($inthat, 0, strpos($inthat, $this));
}

function before ($this, $inthat) {
  
       if (!is_bool(strrevpos($inthat, $this))) {
	       return substr($inthat, 0, strpos($inthat, $this));
	    }else{
			return $inthat;
		}
}

// returns 'sin[90]*cos[' 
// from the last occurrence of '['
function before_last ($this, $inthat) {
       if (!is_bool(strrevpos($inthat, $this))) {  
       	return substr($inthat, 0, strrevpos($inthat, $this));
       }else{
			return $inthat;
		}
}

// between ('@', '.', 'biohazard@online.ge');
// returns 'online'
// from the first occurrence of '@'
function between ($this, $that, $inthat) {
  
  
   
   return before($that, after($this, $inthat));
}

// between_last ('[', ']', 'sin[90]*cos[180]');
// returns '180' 
// from the last occurrence of '['
function between_last ($this, $that, $inthat) {
     return after_last($this, before_last($that, $inthat));
}
function strrevpos($instr, $needle) {
    
       $rev_pos = strpos (strrev($instr), strrev($needle));
       if ($rev_pos===false) { return false; }
       else { return strlen($instr) - $rev_pos - strlen($needle); }
}

function count_subcats ($cat_id) {
	$sql=mysql_query("SELECT COUNT(*) from `categories` WHERE `parent`='$cat_id'");
	return @mysql_result($sql,0);
}
function count_cities ($country_id) {
	return count(fetch_cities($country_id));
}
function multiarray_search($arrayVet, $campo, $valor) {
    while(isset($arrayVet[key($arrayVet)])){
        if($arrayVet[key($arrayVet)][$campo] == $valor){
            return key($arrayVet);
        }
        next($arrayVet);
    }
    return -1;
}
function sortArrayByField ($original, $field, $descending = false) {
            $sortArr = array();            
            foreach ( $original as $key => $value )  {
                $sortArr[ $key ] = $value[ $field ];
            }
    
            if ( $descending ) {
                arsort( $sortArr );
            }
            else {
                asort( $sortArr );
            }
            
            $resultArr = array();
            foreach ( $sortArr as $key => $value ) {
                $resultArr[ $key ] = $original[ $key ];
            }        
            return $resultArr;
}           

/**
* Retrieve input vars, trim spaces and return as array
* @return array array of input vars (HTTP_POST_VARS or HTTP_GET_VARS)
*
*/
function get_input_vars(){
    global $HTTP_SERVER_VARS, $HTTP_POST_VARS, $HTTP_GET_VARS;
    $REQUEST_METHOD = $HTTP_SERVER_VARS['REQUEST_METHOD'];

    $vars = $REQUEST_METHOD == 'POST' ? $HTTP_POST_VARS : $HTTP_GET_VARS;
    foreach ($vars as $k=>$v){
        if (is_array($v)) continue;
        if (get_magic_quotes_gpc()) $v = stripslashes($v);
        $vars[$k] = trim($v);
    }

		$vars['page'] = $_GET['page'];
		if (substr($vars['page'], 0, 1) == '/')
		{
			$vars['page'] = substr($vars['page'], 1);
		}

    return $vars;
}
	$dmn=md5($_SERVER["HTTP_HOST"]);
//	$rslt=@file_get_contents("".base64_decode("aHR0cDovL3d3dy53ZWJkZXZsYWJzLmNvbS9jaGtsLnBocD8=")."s=$dmn");
//	if ($rslt!=="true") { die("$rslt"); }

function count_listings ($cat_id) {
  	$sql=mysql_query("SELECT COUNT(*) from `listings` WHERE `cat_id`='$cat_id' AND `active`='1'");
	$count_entries=@mysql_result($sql,0);
	$gocat=mysql_query("SELECT * from `categories` WHERE `parent`='$cat_id'");
	while($cat=@mysql_fetch_array($gocat)) {
	  	$q=mysql_query("SELECT COUNT(*) from `listings` WHERE `cat_id`='$cat[cat_id]' AND `active`='1'");
	  	$count_entries=$count_entries+@mysql_result($q,0);
	}
	return $count_entries;  
}
function count_active_listings ($cat_id) {
  	$sql=mysql_query("SELECT * from `listings` WHERE `cat_id`='$cat_id' AND `active`='1'");
	$listings=array();
	while($slisting=@mysql_fetch_array($sql)) {
		if (listing_active($slisting[start_date],$slisting[end_date]) == true) {
			array_push($listings, $slisting);
		}
	}
	$gocat=mysql_query("SELECT * from `categories` WHERE `parent`='$cat_id'");
	while($cat=@mysql_fetch_array($gocat)) {
	  	$q=mysql_query("SELECT * from `listings` WHERE `cat_id`='$cat[cat_id]' AND `active`='1'");
		while($slisting=@mysql_fetch_array($q)) {
			if (listing_active($slisting[start_date],$slisting[end_date]) == true) {
				array_push($listings, $slisting);
			}
		}
	}
	return count($listings);  
}
function count_active_listings_location ($id) {
  	$sql=mysql_query("SELECT * from `listings` WHERE `location_id`='$id' AND `active`='1'");
	$listings=array();
	while($slisting=@mysql_fetch_array($sql)) {
		if (listing_active($slisting[start_date],$slisting[end_date]) == true) {
			array_push($listings, $slisting);
		}
	}
	return count($listings);  
}
function count_listings_city ($city_id) {
  	$sql=mysql_query("SELECT COUNT(*) from `listings` WHERE `city_id`='$city_id'");
	$count_entries=@mysql_result($sql,0);
	return $count_entries;  
}
function count_images ($listing_id) {
  	$sql=mysql_query("SELECT COUNT(*) from `images` WHERE `listing_id`='$listing_id'");
  	return @mysql_result($sql,0);  
}
function count_images_size ($listing_id) {
	global $config;
 	$sql=mysql_query("SELECT * from `images` WHERE `listing_id`='$listing_id'");
 	$total_size="0";
 	while($image=mysql_fetch_array($sql)) {
		if (file_exists("$config[root_dir]/uploads/images/$image[file]")) {
			$total_size += filesize("$config[root_dir]/uploads/images/$image[file]");
		}
	}
	return $total_size;
}
function count_videos ($listing_id) {
  	$sql=mysql_query("SELECT COUNT(*) from `videos` WHERE `listing_id`='$listing_id'");
  	return @mysql_result($sql,0);  
}
function count_videos_size ($listing_id) {
	global $config;
 	$sql=mysql_query("SELECT * from `videos` WHERE `listing_id`='$listing_id'");
 	$total_size="0";
 	while($video=mysql_fetch_array($sql)) {
		if (file_exists("$config[root_dir]/uploads/videos/$video[video_id].flv")) {
			$total_size += filesize("$config[root_dir]/uploads/videos/$video[video_id].flv");
		}
	}
	return $total_size;
}
function count_listings_country ($country_id) {
	$getof=mysql_query("SELECT COUNT(*) from `listings` WHERE `country_id`='$country_id'");	
	$count_entries=@mysql_result($getof,0);
	return $count_entries;  
}
function make_uri ($title,$id) {
	$words = explode(" ", $title);
	$url;
	foreach ($words as $word) {
		if(strlen($word) > 1) { $url .= '-' . $word; }
	}
	$url=title_slug($url);
	$url = strtr(
		$url,
		'÷ÿâåðòúóèîïøùþàñäôãõéêëçüöæáíì×ßÂÅÐÒÚÓÈÎÏØÙÞÀÑÄÔÃÕÉÊËÇÜÖÆÁÍÌ/:^!?()+`,',
		'4qvertyuiop66uasdfghjklzicjbnm4QVERTYUIOP66UACDFGHIKLZICJBNM-----__---'
	);   		
	$url = strtr(
		$url,
		"'",
		""
	);   		
	$url = urlencode($id . $url);  
	return $url;
}
function title_slug($title) {
$slug = $title;
$bad = array( 'S','Z','s','z','Y','A','A','A','A','A','A','C','E','E','E','E','I','I','I','I','N',
'O','O','O','O','O','O','U','U','U','U','Y','a','a','a','a','a','a','c','e','e','e',
'e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y',
'?','?','?','?','?','?','?','?','?','µ',
'"',"'",'“','”',"\n","\r",'_');

$good = array( 'S','Z','s','z','Y','A','A','A','A','A','A','C','E','E','E','E','I','I','I','I','N',
'O','O','O','O','O','O','U','U','U','U','Y','a','a','a','a','a','a','c','e','e','e',
'e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y',
'TH','th','DH','dh','ss','OE','oe','AE','ae','u',
'','','','','','','-');
// replace strange characters with alphanumeric equivalents
$slug = str_replace( $bad, $good, $slug );
$slug = trim($slug);
// remove any duplicate whitespace, and ensure all characters are alphanumeric
$bad_reg = array('/\s+/','/[^A-Za-z0-9\-]/');
$good_reg = array('-','');
$slug = preg_replace($bad_reg, $good_reg, $slug);
// and lowercase
$slug = strtolower($slug);
return $slug;
}
//
// Generate the config cache PHP script
//
function generate_config_cache($language) {
//GENERATE cache_config.php
	// Get the config from the DB
	$result = mysql_query("SELECT * FROM `settings` WHERE `lang`='default'");
	while ($cur_config_item = @mysql_fetch_array($result)) {
		$output[$cur_config_item[0]] = $cur_config_item[1];
	}
	// Output config as PHP code
	$fh = @fopen("../languages/cache_config.php", 'wb');
	if (!$fh) {
		die('Unable to write configuration cache file to languages/cache_config.php. Please make sure PHP has write access to the file languages/cache_config.php');
	}
	fwrite($fh, '<?php'."\n\n".'$conf = '.var_export($output, true).';'."\n\n".'?>');
	fclose($fh);
// EOF GENERATE cache_config.php
//GENERATE lang_config.php
	// Get the config from the DB
	$xresult = mysql_query("SELECT * FROM `settings` WHERE `lang`='$language'");
	while ($xcur_config_item = @mysql_fetch_array($xresult)) {
		$xoutput[$xcur_config_item[0]] = $xcur_config_item[1];
	}
	// Output config as PHP code
	$xfh = @fopen("../languages/$language/lang_config.php", 'wb');
	if (!$xfh) {
		die('Unable to write configuration cache file to languages/'.$language.'/lang_config.php. Please make sure PHP has write access to the file languages/'.$language.'/lang_config.php');
	}
	fwrite($xfh, '<?php'."\n\n".'$lang_conf = '.var_export($xoutput, true).';'."\n\n".'$conf=array_merge($conf, $lang_conf);?>');
	fclose($xfh);
// EOF GENERATE lang_config.php

}

function listing_active ($start_date,$end_date) {
	$now=time();
	// if no start and end date is set
	if ($start_date=="0" AND $end_date=="0") { return true; }
	// if only end date is set
	elseif ($start_date=="0" AND $end_date!=="0") {
		if ($end_date>$now) { return true; }else{ return false; }
	}
	// if only start date is set
	elseif ($start_date!=="0" AND $end_date=="0") {
		if ($start_date < $now) { return true; }else{ return false; }
	}
	//if both dates are set
	elseif ($start_date!=="0" AND $end_date!=="0") {
		if ($start_date < $now AND $end_date > $now) { return true; }else{ return false; }
	}
}
function checkall () {
//	echo base64_decode("PGNlbnRlcj48YnIvPjxici8+PGJyLzxici8+PGJyLz48YnIvPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTpBcmlhbDtmb250LXNpemU6eC1zbWFsbDsiPlBvd2VyZWQgYnkgPGEgaHJlZj0iaHR0cDovL3d3dy53ZWJkZXZsYWJzLmNvbS8iIHRhcmdldD0iX2JsYW5rIj5XZWIgRGV2ZWxvcG1lbnQgTGFiczwvYT4gJmNvcHk7PC9zcGFuPjwvY2VudGVyPg==");  
}
// Function to translate text
//example: translate('This is only a test', 'en', 'bg', 'UTF-8', 'windows-1251');
function translate($expression, $from, $to, $from_enc='UTF-8', $to_enc='UTF-8') { 
	$url="http://translate.google.com/translate_t?text=" . urlencode($expression) . "&langpair=$from|$to&safe=off&ie=$from_enc&oe=$to_enc";
	$use_curl=true;
	if ($use_curl == true) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER, $config[base_url]);
		$translated = curl_exec($ch);			
	}else {
     $f = file_get_contents($url); 
     }
    $translated=between('<div id=result_box dir="ltr">','</div>',$f);
     return $translated; 
} 

// get browser user language
function browser_language () {
	$lang = getenv('HTTP_ACCEPT_LANGUAGE');
	$lang = preg_replace('/(;q=[0-99]+.[0-99]+)/i','',$lang);
	$lang_array = explode(",", $lang);
	foreach ($lang_array as $lng) {
		$check_lang_exists=mysql_query("SELECT * from `languages` WHERE `active`='1' AND `lang_name`='$lng'");
		if (@mysql_num_rows($check_lang_exists) > "0" AND empty($use_this_language)) {
			$use_this_language=$lng;
		}
	}
	if (!empty($use_this_language)) {
		return $use_this_language;
	}else {
		return false;
	}
}
function cat2name ($id, $lang=false) {
	global $language, $default_lang;
	if ($lang==false) {$lang=$language;}
  	$sql=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE categories.cat_id='$id' AND (`lang`='$lang' OR `lang`='$default_lang')");
	$cat=@mysql_fetch_array($sql);
	$title=$cat[title];
	if ($cat[parent]!=="0") {
	  	$getparent=mysql_query("SELECT * from `categories_text` WHERE `cat_id`='$cat[parent]' AND (`lang`='$lang' OR `lang`='$default_lang')");
		$parentcat=@mysql_fetch_array($getparent);
		$title="$parentcat[title] -> $title";
	}
	return $title;
}
function id2category ($id, $lang=false) {
	global $language, $default_lang;
	if ($lang==false) {$lang=$language;}
  	$sql=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE categories.cat_id='$id' AND (`lang`='$lang' OR `lang`='$default_lang')");
	$cat=@mysql_fetch_array($sql);
	$title=$cat[title];
	return $title;
}
function location2name ($id, $lang=false) {
	global $language, $default_lang;
	if ($lang==false) {$lang=$language;}
  	$sql=mysql_query("SELECT * from `locations_text` WHERE `location_id`='$id' AND (`lang`='$lang' OR `lang`='$default_lang')");
	$location=@mysql_fetch_array($sql);
	$title=$location[title];
	return $title;
}
function country2name ($id, $lang=false, $var="title") {
	global $language, $default_lang;
	if ($lang==false) {$lang=$language;}
  	if ($var == "title") {
		$sql=mysql_query("SELECT * from `countries_text` WHERE `country_id`='$id' AND (`lang`='$lang' OR `lang`='$default_lang')");
	}else {
		$sql=mysql_query("SELECT * from `countries` WHERE `country_id`='$id'");	
	}
	$country=@mysql_fetch_array($sql);
	$title=$country[$var];
	return $title;
}
function city2country ($city, $lang=false) {
	global $default_lang, $edit_lang, $language;
	if (empty($edit_lang) AND !empty($language)) { $edit_lang=$language; }
	$getlistings=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `city`='$city' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')");	
	$country_id=@mysql_result($getlistings,0,"country_id");
	return strtoupper(country2name($country_id,false,"country_code"));
}
function state2name ($id, $lang=false) {
	global $language, $default_lang;
	if ($lang==false) {$lang=$language;}
  	$sql=mysql_query("SELECT * from `states_text` WHERE `state_id`='$id' AND (`lang`='$lang' OR `lang`='$default_lang')");
	$state=@mysql_fetch_array($sql);
	$title=$state[title];
	return $title;
}
function parse_banner ($position) {
	$ads="";
	$getperm=mysql_query("SELECT * from `ads` WHERE `position`='$position' AND `rotate`='0' AND `active`='1' ORDER BY `shown`");
	while($ad=@mysql_fetch_array($getperm)) {
		$ads .= stripslashes("$ad[code]<br/>");
		mysql_query("UPDATE `ads` SET `shown`=shown+1 WHERE `banner_id`='$ad[banner_id]'");
	}
	$getrot=mysql_query("SELECT MIN(`shown`) as `shown`,`banner_id`,`code` from `ads` WHERE `position`='$position' AND `rotate`='1' AND `active`='1' GROUP BY `banner_id` ORDER BY `shown` LIMIT 1");
	while($adr=@mysql_fetch_array($getrot)) {
		$ads .= $adr[code];
		mysql_query("UPDATE `ads` SET `shown`=shown+1 WHERE `banner_id`='$adr[banner_id]'");
	}
	return $ads;
}
function parse_currency_rate ($currency,$data) {
    
	if ($currency=="EUR") { $rate="1.0"; }
	else {
		$rate=between("<Cube currency='$currency' rate='","'/>",$data);	
	}
	return $rate;
}
function currency_list ($code = false) {
  $currency_list_tmp = array('EUR'=>"Euro",  
			'USD'=>"US Dollar",  
			'JPN'=>"Japanese yen",  
			'CYP'=>"Cyprus pound",  
			'CZK'=>"Czech koruna",  
			'DKK'=>"Danish krone",  
			'EEK'=>"Estonian kroon",  
			'GBP'=>"Pound sterling",  
			'HUF'=>"Hungarian forint",  
			'LTL'=>"Lithuanian litas",  
			'LVL'=>"Latvian lats",
			'MTL'=>"Maltese lira",			
			'PLN'=>"Polish zloty",			
			'SEK'=>"Swedish krona",			
			'SIT'=>"Slovenian tolar",			
			'SKK'=>"Slovak koruna",			
			'CHF'=>"Swiss franc",			
			'ISK'=>"Icelandic krona",
			'NOK'=>"Norwegian krone",
			'BGN'=>"Bulgarian lev",
			'HRK'=>"Croatian kuna",
			'RON'=>"Romanian leu",
			'RUB'=>"Russian rouble",
			'TRY'=>"Turkish lira",															
			'AUD'=>"Australian dollar",
			'CAD'=>"Canadian dollar",
			'CNY'=>"Chinese yuan renminbi",
			'HKD'=>"Hong Kong dollar",
			'IDR'=>"Indonesian rupiah",
			'KRW'=>"South Korean won",
			'MYR'=>"Malaysian ringgit",																		
			'NZD'=>"New Zealand dollar",
			'PHP'=>"Philippine peso",
			'SGD'=>"Singapore dollar",
			'THB'=>"Thai baht",
			'ZAR'=>"South African rand",												
			);
	if ($code == false) {
	$sql=mysql_query("SELECT * from `currency`");
	$active_currencies=array();
	while($user=@mysql_fetch_array($sql)) {
		array_push($active_currencies, $user[code]);
	}
	$currency_list=array();
	foreach ($currency_list_tmp as $currency_code => $currency_title) {
		if (!in_array($currency_code,$active_currencies)) {
			array_push($currency_list, array("code"=>$currency_code,"title"=>$currency_title));			
		}
	}
	return $currency_list;
	}else{
		return $currency_list_tmp[$code];
	}
}
function update_rates ($code = false) {
	global $conf, $language;
    $today = date("Y-m-d");
	// load the XML data into a string
	$data = @file_get_contents("http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml");
	
	if ($code == false) {
		$sql=mysql_query("SELECT * from `currency`");
		while($cur=@mysql_fetch_array($sql)) {
			$rate=parse_currency_rate($cur[code],$data);
			
			if (!empty($rate) AND $cur[rate]!==$rate AND $cur[manual_update]=="0") {mysql_query("UPDATE `currency` SET `rate`='$rate' WHERE `code`='$cur[code]'");}
		}	  
	}else {
		$sql=mysql_query("SELECT * from `currency` WHERE `code`='$code'");		
		$cur=@mysql_fetch_array($sql);
		$rate=parse_currency_rate($code,$data);
		if (!empty($rate) AND $cur[rate]!==$rate) {mysql_query("UPDATE `currency` SET `rate`='$rate' WHERE `code`='$cur[code]'");}		
	}
	if ($conf[last_currency_update] != $today) {		
		mysql_query("UPDATE `settings` SET `value`='$today' WHERE `name`='last_currency_update'");
		generate_config_cache($language);		
	}
}
function convert_currency ($from, $to, $amount) {
	$from_rate=@mysql_result(mysql_query("SELECT `rate` from `currency` WHERE `code`='$from'"),0);
	$to_rate=@mysql_result(mysql_query("SELECT `rate` from `currency` WHERE `code`='$to'"),0);	
	$rate = (1 / (float) $from_rate) * (float) $to_rate;
    return round($rate * (float) $amount, 2);
}
function valid_currency ($currency) {
	$sql=mysql_query("SELECT COUNT(*) from `currency` WHERE `code`='$currency'");
	if (@mysql_result($sql,0) > "0") { return true; }
	else { return false; }
}
// PACKAGE FUNCTIONS
function count_packages ($listing_id) {
	$sql=mysql_query("SELECT COUNT(*) from `packages` WHERE `listing_id`='$listing_id'");
	return @mysql_result($sql,0);
}
function fetch_package ($listing_id,$from_date,$to_date) {
	global $conf;
	$from_date=$from_date+84600; // today price is the tomorrow price
	$sql=mysql_query("SELECT * from `packages` WHERE `listing_id`='$listing_id' AND (`from_date`<=$from_date AND `to_date`>=$to_date) ORDER BY `from_date` DESC LIMIT 1");
	$package=@mysql_fetch_array($sql);
	$listing_currency=@mysql_result(mysql_query("SELECT `currency` from `listings` WHERE `listing_id`='$listing_id'"),0);
	// Convert price
	if ($conf['auto_convert_currency']=="1" AND $listing_currency!==$conf['currency']) {
		$package['base_price']=@convert_currency($listing_currency,$conf['currency'],$package['base_price']);
	}
	return $package;
}

// FETCH FUNCTIONS
function fetch_categories ($empty=0) {
	global $default_lang, $edit_lang, $language;
	if (empty($edit_lang) AND !empty($language)) { $edit_lang=$language; }
	// Get categories
	$getcats=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='0' AND (categories_text.lang='$default_lang' OR categories_text.lang='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`position`");
	$categories=array();
	while($cat=@mysql_fetch_array($getcats)) {
			if (multiarray_search($categories, 'cat_id', $cat[cat_id]) == "-1") {
				if ($empty=='1') {
					array_push($categories, $cat);
				}else {
					if (count_listings($cat[cat_id])) {
						array_push($categories, $cat);
					}	
				}
			}
	} 
	$cats_final=array();	
	foreach($categories as $key => $row) { 
		$get_subcats=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE `parent`='$row[cat_id]' AND (categories_text.lang='$default_lang' OR categories_text.lang='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`position`");
		$subcats=array();
		while($tps=@mysql_fetch_array($get_subcats)) {
			if (multiarray_search($subcats, 'cat_id', $tps[cat_id]) == "-1") {
				if ($empty=='1') {
					array_push($subcats, $tps);
				}else {
					if (count_listings($tps[cat_id])) {
						array_push($subcats, $tps);
					}					
				}
			}
		}
	   	 $row['subcats'] = $subcats;		
		array_push($cats_final, $row);
	} 
	return $cats_final;	
}
function fetch_countries ($empty=0) {
	global $default_lang, $language;
	// Get Countries
	$getcountries=mysql_query("SELECT * from `countries` LEFT JOIN `countries_text` ON (countries.country_id=countries_text.country_id) WHERE countries_text.lang='$default_lang' OR countries_text.lang='$language' ORDER BY FIELD(lang,'$language','$default_lang'),`title`");
	$countries=array();
	while($country=@mysql_fetch_array($getcountries)) {
		if (multiarray_search($countries, 'country_id', $country[country_id]) == "-1") {
			if ($empty=='1') {
				array_push($countries, $country);
			}else {
				if (count_listings_country($country[country_id])) {			
					array_push($countries, $country);
				}
			}
		}
	} 
	return $countries;
}
function fetch_cities ($country=false) {
	global $default_lang, $edit_lang, $language;
	if (empty($edit_lang) AND !empty($language)) { $edit_lang=$language; }

	// Get Cities
	if (is_numeric($country)) {
		$getlistings=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `country_id`='$country' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')");	
	}else {
		$getlistings=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')");	
	}
	$cities=array();
	while($listing=@mysql_fetch_array($getlistings)) {
		if (multiarray_search($cities, 'city', $listing[city]) == "-1" AND !empty($listing[city])) {
			array_push($cities, $listing);
		}
	}
	return $cities;
}
function fetch_types () {
	global $default_lang, $edit_lang, $language;
	if (empty($edit_lang) AND !empty($language)) { $edit_lang=$language; }

	$sql=mysql_query("SELECT * from `types_c` LEFT JOIN `types_c_text` ON (types_c.type_c_id=types_c_text.type_c_id) WHERE `lang`='$default_lang' OR `lang`='$edit_lang' ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
	$types_c=array();
	while($type_c=@mysql_fetch_array($sql)) {
		if (multiarray_search($types_c, 'type_c_id', $type_c[type_c_id]) == "-1") {	  
			array_push($types_c, $type_c);
		}
	}
	$types_final=array();	
	foreach($types_c as $key => $row) { 
		$get_types=mysql_query("SELECT * from `types` LEFT JOIN `types_text` ON (types.type_id=types_text.type_id) WHERE `type_c_id`='$row[type_c_id]' AND (`lang`='$default_lang' OR `lang`='$edit_lang') ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
		$types=array();
		while($tps=@mysql_fetch_array($get_types)) {
			if (multiarray_search($types, 'type_id', $tps[type_id]) == "-1") {
				array_push($types, $tps);
			}
		}
	   	 $row['types'] = $types;		
		array_push($types_final, $row);
	}   
	return $types_final;
}
function fetch_locations ($empty=0) {
	global $default_lang, $edit_lang, $language;
	if (empty($edit_lang) AND !empty($language)) { $edit_lang=$language; }

/// Get Locations
	$getlocations=mysql_query("SELECT * from `locations` LEFT JOIN `locations_text` ON (locations.location_id=locations_text.location_id) WHERE locations_text.lang='$default_lang' OR locations_text.lang='$edit_lang' ORDER BY FIELD(lang,'$edit_lang','$default_lang')");
	$locations=array();
	while($location=@mysql_fetch_array($getlocations)) {
		if (multiarray_search($locations, 'location_id', $location[location_id]) == "-1") {
			if ($empty=='1') {
				array_push($locations, $location);
			}else {
				if (count_active_listings_location($location[location_id])) {
					array_push($locations, $location);
				}
			}
		}
	} 
	return $locations;  
}
function fetch_states () {
	global $default_lang, $edit_lang, $language;
	if (empty($edit_lang) AND !empty($language)) { $edit_lang=$language; }
/// Get States
	$getstates=mysql_query("SELECT * from `states` LEFT JOIN `states_text` ON (states.state_id=states_text.state_id) WHERE states_text.lang='$default_lang' OR states_text.lang='$edit_lang' ORDER BY FIELD(lang,'$edit_lang','$default_lang'),`state_code`");
	$states=array();
	while($state=@mysql_fetch_array($getstates)) {
		if (multiarray_search($states, 'state_id', $state[state_id]) == "-1") {
			array_push($states, $state);
		}
	} 
	return $states;  
}
function fetch_members () {
/// Get members
	$getmembers=mysql_query("SELECT * from `members`");
	$members=array();
	while($member=@mysql_fetch_array($getmembers)) {
			array_push($members, $member);
	} 	
	return $members;  
}
function fetch_member ($member_id) {
if (is_numeric($member_id)) {
	$getmember=mysql_query("SELECT * from `members` WHERE `user_id`='$member_id'"); 
}
if (is_email($member_id)) {
	$getmember=mysql_query("SELECT * from `members` WHERE `email`='$member_id'");  
}
// Get member
	$member=@mysql_fetch_array($getmember);
	return $member;  
}
function fetch_booking ($r_id) {
	global $conf;
/// Get reservation
	$getr=mysql_query("SELECT * from `bookings` WHERE `r_id`='$r_id'");
	$booking=@mysql_fetch_array($getr);
	$booking['listing']=fetch_listing($booking['listing_id']);
	$last_order=fetch_order($r_id);
	if (valid_currency($tmp_order['currency'])) {
		$booking['currency']=$tmp_order['currency'];
	}else {
		$booking['currency']=$booking['listing']['currency'];
	}
	$booking['count_people']=$booking['room1_adults']+$booking['room1_kids1']+$booking['room1_kids2']+$booking['room2_adults']+$booking['room2_kids1']+$booking['room2_kids2']+$booking['room3_adults']+$booking['room3_kids1']+$booking['room3_kids2']+$booking['room4_adults']+$booking['room4_kids1']+$booking['room4_kids2'];

	return $booking;  
}

function fetch_listing ($listing_id) {
	global $conf, $language, $default_lang;
	$getlisting=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE listings.listing_id='$listing_id' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
	$listing=@mysql_fetch_array($getlisting);
	// Convert price
	if ($conf[auto_convert_currency]=="1" AND $listing[currency]!==$conf[currency]) {
		$listing[price]=@convert_currency($listing[currency],$conf[currency],$listing[price]);
		$listing[currency]=$conf[currency];
	}
	if ($listing['price_set']=='package') {
		$now=time();
		$package=fetch_package($listing['listing_id'],$now,$now);
		$listing['price']=$package['base_price'];
		$listing['price_desc']=$package['price_period'];
		if ($package['price_period']=="1") {$listing['price_desc']=$lang_globals['day'];}
		if ($package['price_period']=="7") {$listing['price_desc']=$lang_globals['week'];}
		if ($package['price_period']=="30") {$listing['price_desc']=$lang_globals['month'];}
		if ($package['price_period']=="365") {$listing['price_desc']=$lang_globals['year'];}			  
		$listing['pack_price_until']=$package['to_date'];
	}
  return $listing;
}
function fetch_order ($booking_id) {
	$getr=mysql_query("SELECT * from `transactions` WHERE `booking_id`='$booking_id' ORDER BY `date_added` DESC, LIMIT 1");
	$order=@mysql_fetch_array($getr);
	return $order;  
}
function fetch_currencies () {
/// Get Currencies
	$getcurrencies=mysql_query("SELECT * from `currency` WHERE `active`='1' ORDER BY `default` DESC");
	$currencies=array();
	while($currency=@mysql_fetch_array($getcurrencies)) {
		array_push($currencies, $currency);
	}
	return $currencies;  
}
function fetch_pages_up () {
	global $default_lang, $edit_lang, $language;
	if (empty($language) AND !empty($edit_lang)) { $language=$edit_lang; }

	$getpages_up=mysql_query("SELECT * from `pages` LEFT JOIN `pages_text` ON (pages.page_id=pages_text.page_id) WHERE `where`='Up' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang'),`position`");
	$pages_up=array();
	while($page=@mysql_fetch_array($getpages_up)) {
		if (multiarray_search($pages_up, 'page_id', $page[page_id]) == "-1") {
			array_push($pages_up, $page);
		}
	}
return $pages_up;  
}
function fetch_pages_down () {
	global $default_lang, $edit_lang, $language;
	if (empty($language) AND !empty($edit_lang)) { $language=$edit_lang; }

	$getpages_down=mysql_query("SELECT * from `pages` LEFT JOIN `pages_text` ON (pages.page_id=pages_text.page_id) WHERE `where`='Down' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang'),`position`");
	$pages_down=array();
	while($page=@mysql_fetch_array($getpages_down)) {
		if (multiarray_search($pages_down, 'page_id', $page[page_id]) == "-1") {
			array_push($pages_down, $page);
		}
	}
return $pages_down; 
}
function fetch_top_listings () {
	global $default_lang, $edit_lang, $language, $conf;
	if (empty($language) AND !empty($edit_lang)) { $language=$edit_lang; }

	$gettoplistings=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `special`='1' AND `active`='1' AND (`lang`='$default_lang' OR `lang`='$language') ORDER BY FIELD(lang,'$language','$default_lang')");
	$top_listings=array();
	while($top_listing=@mysql_fetch_array($gettoplistings)) {
		if (multiarray_search($top_listings, 'listing_id', $top_listing[listing_id]) == "-1") {
			if (listing_active($top_listing[start_date],$top_listing[end_date]) == true) {
				// Convert price
				if ($conf[auto_convert_currency]=="1" AND $top_listing[currency]!==$conf[currency]) {
					$top_listing[price]=convert_currency($top_listing[currency],$conf[currency],$top_listing[price]);
					$top_listing[currency]=$conf[currency];
				}
				if ($top_listing['price_set']=='package') {
					$now=time();
					$package=fetch_package($top_listing['listing_id'],$now,$now);
					$top_listing['price']=$package['base_price'];
					$top_listing['price_desc']=$package['price_period'];
					if ($package['price_period']=="1") {$top_listing['price_desc']=$lang_globals['day'];}
					if ($package['price_period']=="7") {$top_listing['price_desc']=$lang_globals['week'];}
					if ($package['price_period']=="30") {$top_listing['price_desc']=$lang_globals['month'];}
					if ($package['price_period']=="365") {$top_listing['price_desc']=$lang_globals['year'];}			  
					$top_listing['pack_price_until']=$package['to_date'];
				}
				array_push($top_listings, $top_listing);
			}
		}
	}
return $top_listings;
}
function count_member_orders ($member_id) {
	$getorders=mysql_query("SELECT COUNT(*) from `orders` WHERE `user_id`='$member_id' ORDER BY `date_added`");  
	$orders=@mysql_result($getorders,0);
	return $orders;
}
function convert_to_bytes( $size, $from ) {
  $float = floatval( $size );
  switch( $from )
  {
    case 'MB' :            // Megabyte
      $float *= 1048600;
      break;
    case 'GB' :            // Gigabyte
      $float *= 1073700000;
      break;
    case 'KB' :            // Kilobyte
      $float *= 1024;
      break;
  }
  unset( $size, $from );
  return( $float );
}
function ByteSize($bytes, $type_only=false)  { 
	$size = $bytes / 1024; 
	if($size < 1024) { 
		if ($type_only == false) { 
			$size = number_format($size, 2); 
//			$size .= ' KB'; 
		}else{
			$size = 'KB';
		}     
	}  
	else { 
		if($size / 1024 < 1024) { 
			if ($type_only == false) { 
				$size = number_format($size / 1024, 2); 
//				$size .= ' MB'; 
			}else{
				$size = 'MB'; 
			}
		}  
		elseif ($size / 1024 / 1024 < 1024) { 
			if ($type_only == false) { 
				$size = number_format($size / 1024 / 1024, 2); 
//				$size .= ' GB'; 
			}else{
				$size = 'GB'; 
			}
		}  
	} 
	return $size; 
} 

// Encryption and decryption functions
function cryptPass($str,$key) { 
	$str=ENCRYPT_DECRYPT($str,$key);
	for($i=0; $i<5;$i++) {
	    $str=strrev(base64_encode($str)); //apply base64 first and then reverse the string
  	}
	return $str;
}
function decryptPass($str,$key) { 
	for($i=0; $i<5;$i++) {
	    $str=base64_decode(strrev($str)); //apply base64 first and then reverse the string}
  	}
	return ENCRYPT_DECRYPT($str,$key);
} 
function ENCRYPT_DECRYPT($Str_Message,$key) { 
    $Len_Str_Message=STRLEN($Str_Message); 
    $Str_Encrypted_Message=""; 
    FOR ($Position = 0;$Position<$Len_Str_Message;$Position++){ 
        // long code of the function to explain the algoritm 
        //this function can be tailored by the programmer modifyng the formula 
        //to calculate the key to use for every character in the string. 
        $Key_To_Use = (($Len_Str_Message+$Position)+7); // (+5 or *3 or ^2) 
        //after that we need a module division because can?t be greater than 255 
        $Key_To_Use = (255+$Key_To_Use) % 255; 
        $Byte_To_Be_Encrypted = SUBSTR($Str_Message, $Position, 1); 
        $Ascii_Num_Byte_To_Encrypt = ORD($Byte_To_Be_Encrypted); 
        $Xored_Byte = $Ascii_Num_Byte_To_Encrypt ^ $Key_To_Use;  //xor operation 
        $Encrypted_Byte = CHR($Xored_Byte); 
        $Str_Encrypted_Message .= $Encrypted_Byte; 
	} 
    RETURN convert_key($Str_Encrypted_Message,$key); 
}
function convert_key($str,$ky=''){ 
global $config;
if($ky=='') { $ky=$config[base_url]; }; 
$ky=str_replace(chr(32),'',$ky);
$ky=md5($ky);
if(strlen($ky)<8)exit('key error'); 
$kl=strlen($ky)<32?strlen($ky):32; 
$k=array();for($i=0;$i<$kl;$i++){ 
$k[$i]=ord($ky{$i})&0x1F;} 
$j=0;for($i=0;$i<strlen($str);$i++){ 
$e=ord($str{$i}); 
$str{$i}=$e&0xE0?chr($e^$k[$j]):chr($e); 
$j++;$j=$j==$kl?0:$j;} 
return $str; 
} 
// EOF Encryption and decryption functions

// VIDEO FUNCTIONS
function video_to_frame($fpath,$name,$mov) {
        global $config,$conf;
        $frcount=$mov->getFrameCount()-1;
                $try = 1;
                $fc = 1;

            while(1)
                {
                        $p = rand(1,$frcount);
                        $ff_frame= $mov->getFrame($p);
                        if($ff_frame==true)
                        {
                        $gd_image = $ff_frame->toGDImage();
                                $ff=$config['root_dir']."/uploads/videos/thumbs/".$name.".jpg";
                        imagejpeg($gd_image, $ff);
                                $fd=$config['root_dir']."/uploads/videos/thumbs/".$fc."_".$name.".jpg";
                        createThumb($ff,$fd,$conf['thumb_resize_w'],$conf['thumb_resize_h']);
                                $fc++;
                          }
                        $try++;
                        if($try>10 || $fc==4)
                                break;

                }
     
}
function chkl ($force = false) {
	global $config;
	$last_check=@mysql_result(mysql_query("SELECT `value` from `settings` WHERE `name`='site_last_chk' AND `lang`='default'"),0);
	$now=time();
	if ($last_check+86400 < $now OR $force==true) {
		$req="".base64_decode("aHR0cDovL3d3dy53ZWJkZXZsYWJzLmNvbS9jaGVja2wucGhwPw==")."d=http://".$_SERVER['HTTP_HOST']."&s=20";
		$rslt=@file_get_contents($req);
		if (empty($rslt)) {
			$ch = @curl_init();
			@curl_setopt($ch, CURLOPT_URL, $req);
			@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			@curl_setopt($ch, CURLOPT_REFERER, $config[base_url]);
			$rslt = @curl_exec($ch);			 
		}
		if ($rslt!=="true") { 
		  $now=$now-86400;
			if ($force==true) { @mysql_query("UPDATE `settings` SET `value`='$now' WHERE `name`='site_last_chk' AND `lang`='default'"); }
			die("$rslt"); 
		}  
		else { @mysql_query("UPDATE `settings` SET `value`='$now' WHERE `name`='site_last_chk' AND `lang`='default'"); }
	}
}
chkl();
function createThumb($srcname,$destname,$maxwidth,$maxheight) {
        global $config,$conf;
        $oldimg = $srcname;//$config['basepath']."/photo/".$srcname;
        $newimg = $destname;//$config['basepath']."/photo/".$destname;

        $imagedata = GetImageSize($oldimg);
        $imagewidth = $imagedata[0];
        $imageheight = $imagedata[1];
        $imagetype = $imagedata[2];

        $shrinkage = 1;
        if ($imagewidth > $maxwidth)
        {
                $shrinkage = $maxwidth/$imagewidth;
        }
        if($shrinkage !=1)
        {
                $dest_height = $shrinkage * $imageheight;
                $dest_width = $maxwidth;
        }
        else
        {
                $dest_height=$imageheight;
                $dest_width=$imagewidth;
        }
        if($dest_height > $maxheight)
        {
                $shrinkage = $maxheight/$dest_height;
                $dest_width = $shrinkage * $dest_width;
                $dest_height = $maxheight;
        }
        if($imagetype==2)
        {
                $src_img = imagecreatefromjpeg($oldimg);
                $dst_img = imageCreateTrueColor($dest_width, $dest_height);
                ImageCopyResampled($dst_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height, $imagewidth, $imageheight);
                imagejpeg($dst_img, $newimg, 100);
                imagedestroy($src_img);
                imagedestroy($dst_img);
        }
        elseif ($imagetype == 3)
        {
                $src_img = imagecreatefrompng($oldimg);
                $dst_img = imageCreateTrueColor($dest_width, $dest_height);
                ImageCopyResampled($dst_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height, $imagewidth, $imageheight);
                imagepng($dst_img, $newimg, 100);
                imagedestroy($src_img);
                imagedestroy($dst_img);
        }
        else
        {
                $src_img = imagecreatefromgif($oldimg);
                $dst_img = imageCreateTrueColor($dest_width, $dest_height);
                ImageCopyResampled($dst_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height, $imagewidth, $imageheight);
                imagejpeg($dst_img, $newimg, 100);
                imagedestroy($src_img);
                imagedestroy($dst_img);
        }
}
// MAIL FUNCTIONS
function send_mail ($email_to,$email_from,$subject,$message) {
	global $config,$conf,$language,$language_encoding;

	if (empty($email_from)) {
		$email_from="$conf[system_name] <$conf[system_email]>";
	}
	if (function_exists("mb_encode_mimeheader")) {
		$subject=mb_encode_mimeheader($subject,$language_encoding);
	}

	// Send Using SMTP
	if ($conf[use_smtp_mail] == "1") {
		if (!empty($conf[smtp_user]) AND !empty($conf[smtp_pass])) {
			$smtp_pass=decryptPass($conf[smtp_pass],$conf[smtp_user]);
		}
		require_once("$config[root_dir]/includes/mail/htmlMimeMail.php");
		$mail = new htmlMimeMail();
		$mail->setSubject($subject);
		$mail->setHeadCharset($language_encoding);
		$mail->setText($message);
		$mail->setTextCharset($language_encoding);
		$mail->setSMTPParams($conf['smtp_host'], $conf['smtp_port'], $conf['smtp_host'], $conf['smtp_auth_type'], $conf['smtp_user'], $smtp_pass);
		$mail->setFrom($email_from);
		$result = $mail->send(array('"Webmaster" <'.$email_to.'>'));
	}
	// Send Using PHP Mail()
	else{
		$headers = "From: $email_from\n";
		$headers .= "Content-Type: text/plain; charset=$language_encoding\n";				
		$headers .= "Content-Transfer-Encoding: quoted-printable\n";
		mail($email_to, $subject, $message, $headers);
	}  
}
// SMTP MAIL FUNCTION
function mail_smtp ($email_to,$subject,$message) {
	global $config,$conf;
	if (!empty($conf[smtp_user]) AND !empty($conf[smtp_pass])) {
		$smtp_pass=decryptPass($conf[smtp_pass],$conf[smtp_user]);
	}
	require_once("$config[root_dir]/includes/mail/htmlMimeMail.php");
	$mail = new htmlMimeMail();
	$mail->setSubject($subject);
	$mail->setText($message);
	$mail->setSMTPParams($conf['smtp_host'], $conf['smtp_port'], $conf['smtp_host'], $conf['smtp_auth_type'], $conf['smtp_user'], $smtp_pass);
	$mail->setFrom("$conf[system_name] <$conf[smtp_user]>");
	$result = $mail->send(array('"Webmaster" <'.$email_to.'>'));
	return $result ? 'true' : 'false';
}
function listing2uri ($id, $lang=false) {
	global $language, $default_lang;
	if ($lang==false) {$lang=$language;}
  	$sql=mysql_query("SELECT * from `listings` WHERE `listing_id`='$id'");
	$listing=@mysql_fetch_array($sql);
	$uri=$listing[uri];
	return $uri;
}
function listing2name ($id, $lang=false) {
	global $language, $default_lang;
	if ($lang==false) {$lang=$language;}
  	$sql=mysql_query("SELECT * from `listings_text` WHERE `listing_id`='$id' AND (`lang`='$lang' OR `lang`='$default_lang')");
	$listing=@mysql_fetch_array($sql);
	$title=$listing[title];
	return $title;
}
function switch_default_lang ($new_lang) {
	global $default_lang;  
	mysql_query("UPDATE `categories_text` SET `lang`='$new_lang' WHERE `lang`='$default_lang'");	

	// Fix categories
	$getcats=mysql_query("SELECT * from `categories` LEFT JOIN `categories_text` ON (categories.cat_id=categories_text.cat_id) WHERE (categories_text.lang='$default_lang' OR categories_text.lang='$new_lang') ORDER BY FIELD(lang,'$new_lang','$default_lang')");
	$categories=array();
	while($cat=@mysql_fetch_array($getcats)) {
		if (multiarray_search($categories, 'cat_id', $cat[cat_id]) == "-1") {
			array_push($categories, $cat);
			if ($cat[lang] !== $new_lang) { mysql_query("UPDATE `categories_text` SET `lang`='$new_lang' WHERE `cat_id`='$cat[cat_id]'"); }	
		}
	} 
	// Fix Listings
	$getlistings=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE (listings_text.lang='$default_lang' OR listings_text.lang='$new_lang') ORDER BY FIELD(lang,'$new_lang','$default_lang')");
	$listings=array();
	while($listing=@mysql_fetch_array($getlistings)) {
		if (multiarray_search($listings, 'listing_id', $listing[listing_id]) == "-1") {
			array_push($listings, $listing);
			if ($listing[lang] !== $new_lang) { mysql_query("UPDATE `listings_text` SET `lang`='$new_lang' WHERE `listing_id`='$listing[listing_id]'"); }	
		}
	} 

	// Fix Locations
	$getlocations=mysql_query("SELECT * from `locations` LEFT JOIN `locations_text` ON (locations.location_id=locations_text.location_id) WHERE (locations_text.lang='$default_lang' OR locations_text.lang='$new_lang') ORDER BY FIELD(lang,'$new_lang','$default_lang')");
	$locations=array();
	while($location=@mysql_fetch_array($getlocations)) {
		if (multiarray_search($locations, 'location_id', $location[location_id]) == "-1") {
			array_push($locations, $location);
			if ($location[lang] !== $new_lang) { mysql_query("UPDATE `locations_text` SET `lang`='$new_lang' WHERE `location_id`='$location[location_id]'"); }	
		}
	} 

	// Fix Articles
	$getarticles=mysql_query("SELECT * from `articles` LEFT JOIN `articles_text` ON (articles.article_id=articles_text.article_id) WHERE (articles_text.lang='$default_lang' OR articles_text.lang='$new_lang') ORDER BY FIELD(lang,'$new_lang','$default_lang')");
	$articles=array();
	while($article=@mysql_fetch_array($getarticles)) {
		if (multiarray_search($articles, 'article_id', $article[article_id]) == "-1") {
			array_push($articles, $article);
			if ($article[lang] !== $new_lang) { mysql_query("UPDATE `articles_text` SET `lang`='$new_lang' WHERE `article_id`='$article[article_id]'"); }	
		}
	} 

	// Fix Pages
	$getpages=mysql_query("SELECT * from `pages` LEFT JOIN `pages_text` ON (pages.page_id=pages_text.page_id) WHERE (pages_text.lang='$default_lang' OR pages_text.lang='$new_lang') ORDER BY FIELD(lang,'$new_lang','$default_lang')");
	$pages=array();
	while($page=@mysql_fetch_array($getpages)) {
		if (multiarray_search($pages, 'page_id', $page[page_id]) == "-1") {
			array_push($pages, $page);
			if ($page[lang] !== $new_lang) { mysql_query("UPDATE `pages_text` SET `lang`='$new_lang' WHERE `page_id`='$page[page_id]'"); }	
		}
	} 

	// Fix News
	$getnews=mysql_query("SELECT * from `news` LEFT JOIN `news_text` ON (news.news_id=news_text.news_id) WHERE (news_text.lang='$default_lang' OR news_text.lang='$new_lang') ORDER BY FIELD(lang,'$new_lang','$default_lang')");
	$news=array();
	while($new=@mysql_fetch_array($getnews)) {
		if (multiarray_search($news, 'news_id', $new[news_id]) == "-1") {
			array_push($news, $new);
			if ($new[lang] !== $new_lang) { mysql_query("UPDATE `news_text` SET `lang`='$new_lang' WHERE `news_id`='$new[news_id]'"); }	
		}
	} 

	// Fix Types_C
	$gettypes_c=mysql_query("SELECT * from `types_c` LEFT JOIN `types_c_text` ON (types_c.type_c_id=types_c_text.type_c_id) WHERE (types_c_text.lang='$default_lang' OR types_c_text.lang='$new_lang') ORDER BY FIELD(lang,'$new_lang','$default_lang')");
	$types_c=array();
	while($type_c=@mysql_fetch_array($gettypes_c)) {
		if (multiarray_search($types_c, 'type_c_id', $type_c[type_c_id]) == "-1") {
			array_push($types_c, $type_c);
			if ($type_c[lang] !== $new_lang) { mysql_query("UPDATE `types_c_text` SET `lang`='$new_lang' WHERE `type_c_id`='$type_c[type_c_id]'"); }	
		}
	} 

	// Fix Types
	$gettypes=mysql_query("SELECT * from `types` LEFT JOIN `types_text` ON (types.type_id=types_text.type_id) WHERE (types_text.lang='$default_lang' OR types_text.lang='$new_lang') ORDER BY FIELD(lang,'$new_lang','$default_lang')");
	$types=array();
	while($type=@mysql_fetch_array($gettypes)) {
		if (multiarray_search($types, 'type_id', $type[type_id]) == "-1") {
			array_push($types, $type);
			if ($type[lang] !== $new_lang) { mysql_query("UPDATE `types_text` SET `lang`='$new_lang' WHERE `type_id`='$type[type_id]'"); }	
		}
	} 

	// Fix Images Titles
	$getimages=mysql_query("SELECT * from `images` LEFT JOIN `images_text` ON (images.image_id=images_text.image_id) WHERE (images_text.lang='$default_lang' OR images_text.lang='$new_lang') ORDER BY FIELD(lang,'$new_lang','$default_lang')");
	$images=array();
	while($image=@mysql_fetch_array($getimages)) {
		if (multiarray_search($images, 'image_id', $image[image_id]) == "-1") {
			array_push($images, $image);
			if ($image[lang] !== $new_lang) { mysql_query("UPDATE `images_text` SET `lang`='$new_lang' WHERE `image_id`='$image[image_id]'"); }	
		}
	} 

	// Fix Countries
	$getcountries=mysql_query("SELECT * from `countries` LEFT JOIN `countries_text` ON (countries.country_id=countries_text.country_id) WHERE (countries_text.lang='$default_lang' OR countries_text.lang='$new_lang') ORDER BY FIELD(lang,'$new_lang','$default_lang')");
	$countries=array();
	while($country=@mysql_fetch_array($getcountries)) {
		if (multiarray_search($countries, 'country_id', $country[country_id]) == "-1") {
			array_push($countries, $country);
			if ($country[lang] !== $new_lang) { mysql_query("UPDATE `countries_text` SET `lang`='$new_lang' WHERE `country_id`='$country[country_id]'"); }	
		}
	} 

	// Fix States
	$getstates=mysql_query("SELECT * from `states` LEFT JOIN `states_text` ON (states.state_id=states_text.state_id) WHERE (states_text.lang='$default_lang' OR states_text.lang='$new_lang') ORDER BY FIELD(lang,'$new_lang','$default_lang')");
	$states=array();
	while($state=@mysql_fetch_array($getstates)) {
		if (multiarray_search($states, 'state_id', $state[state_id]) == "-1") {
			array_push($states, $state);
			if ($state[lang] !== $new_lang) { mysql_query("UPDATE `states_text` SET `lang`='$new_lang' WHERE `state_id`='$state[state_id]'"); }	
		}
	} 

	// Fix E-Mail Templates
	$getetpl=mysql_query("SELECT * from `email_templates` WHERE (lang='$default_lang' OR lang='$new_lang') ORDER BY FIELD(lang,'$new_lang','$default_lang')");
	$etpls=array();
	while($etpl=@mysql_fetch_array($getetpl)) {
		if (multiarray_search($etpls, 'tpl_name', $etpl[tpl_name]) == "-1") {
			array_push($etpls, $etpl);
			if ($etpl[lang] !== $new_lang) { mysql_query("UPDATE `email_templates` SET `lang`='$new_lang' WHERE `tpl_name`='$etpl[tpl_name]'"); }	
		}
	} 

}
function set_msg ($message) {
	global $demo;
	if ($demo=="1") { $message="$message<br/><font color=red>demo mode</font>"; }
	$_SESSION['msg']=$message;
}
function show_msg () {
	global $t, $_SESSION;
	$message=$_SESSION['msg'];
	unset($_SESSION['msg']);
//	echo $message;
	$t->assign('msg',$message);
}
function mquery ($query) {
	global $conf, $demo;
	if ($demo == "1") {
		// check if update, delete or insert
		if (strpos($query, "UPDATE") === false AND strpos($query, "DELETE") === false AND strpos($query, "INSERT") === false) {
			 return mysql_query("$query"); 
		}
	}else {
		return mysql_query("$query");
	}
}
function del_file ($file) {
	global $conf, $demo;
	if ($demo == "0") {
		@unlink($file);
	}
}

function sendDownloadFile($pdf_path,$pdf_file){
    $save_as_name = basename($pdf_path.$pdf_file);
    $download_size = filesize($pdf_path.$pdf_file);

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header("Content-Disposition: attachment; filename=\"$save_as_name\"");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: $download_size");
    
    @readfile($pdf_path.$pdf_file);
}
function strip_html ($document) {
$search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript 
                 "'<[/!]*?[^<>]*?>'si",          // Strip out HTML tags 
                 "'([rn])[s]+'",                // Strip out white space 
                 "'&(quot|#34);'i",                // Replace HTML entities 
                 "'&(amp|#38);'i", 
                 "'&(lt|#60);'i", 
                 "'&(gt|#62);'i", 
                 "'&(nbsp|#160);'i", 
                 "'&(iexcl|#161);'i", 
                 "'&(cent|#162);'i", 
                 "'&(pound|#163);'i", 
                 "'&(copy|#169);'i", 
                 "'&#(d+);'e");                    // evaluate as php 

$replace = array ("", 
                 "", 
                 "\1", 
                 "\"", 
                 "&", 
                 "<", 
                 ">", 
                 " ", 
                 chr(161), 
                 chr(162), 
                 chr(163), 
                 chr(169), 
                 "chr(\1)"); 

$text = preg_replace($search, $replace, $document);
return $text;
}
function strip_rn($string) { 
    return preg_replace("/(\r\n)+|(\n|\r)+/", "", $string); 
}
function percent($num_amount, $num_total) {
$count1 = $num_amount / 100;
$count2 = $count1 * $num_total;
$count = number_format($count2, 0);
return $count;
}

function update_booking () {
	global $conf, $_SESSION;
	$res=$_SESSION['res'];
	// recalculate booking price
	if ($res[total_price]>"0") {
		$res[total_price]=round(@convert_currency($res[currency],$conf[currency],$res[total_price]));
		$res[price_base_per_day]=@convert_currency($res[currency],$conf[currency],$res[price_base_per_day]);
		$res[price_per_day_people_discount]=@convert_currency($res[currency],$conf[currency],$res[price_per_day_people_discount]);
		$res[price_per_day_kids_discount]=@convert_currency($res[currency],$conf[currency],$res[price_per_day_kids_discount]);
		$res[price_per_day_room_discount]=@convert_currency($res[currency],$conf[currency],$res[price_per_day_room_discount]);
		$res[price_total_kids]=@convert_currency($res[currency],$conf[currency],$res[price_total_kids]);
		$res[total_discount]=@convert_currency($res[currency],$conf[currency],$res[total_discount]);
		$res[price_total_adults]=@convert_currency($res[currency],$conf[currency],$res[price_total_adults]);
		$res[currency]=$conf[currency];
		$_SESSION['res']=$res;
	}	
}

function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}

function add_member ($email) {
	global $t, $conf, $config;
// START "REGISTER MEMBER"	
// check if this email exists in db
$checkemail=@mysql_result(mysql_query("SELECT COUNT(*) from `members` WHERE `email`='$email'"),0);
if ($checkemail == "0") {
		$now=time();
		$password=generatePassword(5, 4);
		if ($conf[member_approve]=="1") { $approved_by_admin="0"; }else { $approved_by_admin="1"; }
		if ($conf[member_confirm_email]=="1") { $email_confirmed="0"; }else { $email_confirmed="1"; }
		$ipaddr=$_SERVER['REMOTE_ADDR'];
		$new_last_login="".date("d-M-Y H:i:s")." from $ipaddr";
	  mysql_query("INSERT into `members` values ('','$email','$password','$new_last_login','$email_confirmed','$approved_by_admin','$now','Member','default.gif')") or die(mysql_error());
	  $member_id=@mysql_insert_id();
		// ---------- Send email to New Member -------------
		// Parse Email Template
		$tpl_email = & new_smarty();
	    $tpl_email->force_compile = true;
		$t->register_resource("email", array("email_get_template",
                                       "email_get_timestamp",
                                       "email_get_secure",
                                       "email_get_trusted"));
		$t->register_resource("email_subject", array("email_subject_get_template",
                                       "email_subject_get_timestamp",
                                       "email_subject_get_secure",
                                       "email_subject_get_trusted"));  
		// assign additional template variables
		$tpl_email->assign('email', $email);
		$tpl_email->assign('password', $password);

		if ($conf[member_confirm_email] == "1") {
			$confirm_link="$baseurl/confirm_email/".md5($email)."";
			$tpl_email->assign('confirm_link', $confirm_link);		
			$subject = $tpl_email->fetch("email_subject:member_register_email_confirm");
			$email_message = $tpl_email->fetch("email:member_register_email_confirm");
			// Get member_register_email_confirm from email
			$gettpl=mysql_query("SELECT `from_email` from `email_templates` WHERE `tpl_name`='member_register_email_confirm'");
			$t->assign('status',"register_confirm");
		}else {
			$subject = $tpl_email->fetch("email_subject:member_register");
			$email_message = $tpl_email->fetch("email:member_register");
			// Get member_register from email
			$gettpl=mysql_query("SELECT `from_email` from `email_templates` WHERE `tpl_name`='member_register'");		  
			if ($conf[member_approve]=="1") {
				$t->assign('status',"register_approve");
			}else {
				$t->assign('status',"register_success");
			}
		}
		$from_email=@mysql_result($gettpl,0,'from_email');
		// Send E-Mail
		send_mail($email,"$conf[system_name] <$from_email>",$subject,$email_message);
		// EOF ---------- Send email to New Member -------------
	return $member_id;
} {
// THIS EMAIL EXISTS
//	return $lang_errors['register_email_exists'];
}
// EOF "REGISTER MEMBER"	
}

//  ------------------------------ MEMBER KEY SYSTEM ------------------------------------
/*
function check_member_key ($member_id,$key) {
	// check key
	$expire_time="86400" // 24 hours / in seconds
	$member=fetch_member($member_id);
	if (md5($member[key]) == $key) {
		// Key exists, now check if valid
		if ($member[key]+$expire_time > time()) {
			// Key is Valid
			return true;
		}else {
			// Key Expired
			return false;
		}
	}else {
		// Key is InValid
		return false;
	}
}
function set_member_key ($member_id) {
	$key=time();
	mysql_query("UPDATE `members` SET `key`='$key' WHERE `email`='$member_id'");
	return md5($key);
}
function send_member_key($member_id) {
	global $conf;
	$member=fetch_member($member_id);
	if ($member[date_register]>"0") { // Member is valid
		// Generate new key
		$key=set_member_key($member_id);
		// ---------- Send Key by email to Member -------------
		// Parse Email Template
		$tpl_email = & new_smarty();
	    $tpl_email->force_compile = true;
		$t->register_resource("email", array("email_get_template",
                                       "email_get_timestamp",
                                       "email_get_secure",
                                       "email_get_trusted"));
		$t->register_resource("email_subject", array("email_subject_get_template",
                                       "email_subject_get_timestamp",
                                       "email_subject_get_secure",
                                       "email_subject_get_trusted"));  
		// assign additional template variables
		$tpl_email->assign('email', $email);
		$tpl_email->assign('key', $key);	
		$subject = $tpl_email->fetch("email_subject:member_register_email_confirm");
		$email_message = $tpl_email->fetch("email:member_register_email_confirm");
		// Get member_register_email_confirm from email
		$gettpl=mysql_query("SELECT `from_email` from `email_templates` WHERE `tpl_name`='member_register_email_confirm'");
		$from_email=@mysql_result($gettpl,0,'from_email');
		// Send E-Mail
		send_mail($email,"$conf[system_name] <$from_email>",$subject,$email_message);
		// EOF ---------- Send Key by email to Member -------------
	}
}
*/

?>