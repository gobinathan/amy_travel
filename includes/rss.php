<?php
	require("xmlwriterclass.php");
	require("rss_writer_class.php");
	$now=time();

	if (!empty($request[1])) {
		$show_lang=$request[1];
		$checklang=@mysql_query("SELECT * from `languages` WHERE `lang_name`='$show_lang' AND `active`='1'");
		if (@mysql_num_rows($checklang)>0) {
			$lang_encoding=@mysql_result($sql,0,"encoding");
		}else {
			$show_lang=$default_lang;
			$lang_encoding=$language_encoding;
		}
	}else{
		$show_lang=$default_lang;
		$lang_encoding=$language_encoding;
	}
	// START "FETCH ALL OFFERS"
	$getlistings=mysql_query("SELECT * from `listings` LEFT JOIN `listings_text` ON (listings.listing_id=listings_text.listing_id) WHERE `active`='1' AND (`lang`='$default_lang' OR `lang`='$show_lang') ORDER BY FIELD(lang,'$show_lang','$default_lang') LIMIT $conf[rss_max_items]");

	$listings=array();
	while($listing=@mysql_fetch_array($getlistings)) {
		if (multiarray_search($listings, 'listing_id', $listing[listing_id]) == "-1") {
			if (listing_active($listing[start_date],$listing[end_date]) == true) {
				// Convert price
				if ($conf[auto_convert_currency]=="1" AND $listing[currency]!==$conf[currency]) {
					$listing[price]=convert_currency($listing[currency],$conf[currency],$listing[price]);
					$listing[currency]=$conf[currency];
				}
				array_push($listings, $listing);
			}
		}
	}
	$listings=sortArrayByField($listings,"added_date",true); 
	// EOF "FETCH ALL OFFERS"
	
	$rss_writer_object=new rss_writer_class;
	$rss_writer_object->specification="1.0";
	$rss_writer_object->about=$baseurl;
//	$rss_writer_object->stylesheet="http://www.phpclasses.org/rss2html.xsl";
	$rss_writer_object->rssnamespaces["dc"]="http://purl.org/dc/elements/1.1/";

	/*
	 *  Define the properties of the channel.
	 */
	$rss_items=array();
	$rss_items["description"]=$conf[site_title];
	$rss_items["link"]=$baseurl;
	$rss_items["title"]=$conf[site_title];
	$rss_items["dc:date"]=date("d/M/y H:i");
	$rss_writer_object->addchannel($rss_items);

	/*
	 *  If your channel has a logo, before adding any channel items, specify the logo details this way.
	 *

	$rss_items=array();
	$rss_items["url"]=$feed[image];
	$rss_items["link"]="http://www.webdevlabs.com/";
	$rss_items["title"]="Web Development Labs";
	$rss_items["description"]="Professional web development";
	$rss_writer_object->addimage($rss_items);
*/
	/*
	 *  Then add your channel items one by one.
	 */
	$rss_items=array();
    $count_items=0;
	foreach ($listings as $rssdata) {
		if ($count_items<=$conf[rss_max_items]) {
			$rss_items["description"]=stripslashes($rssdata[short_description]);
			$rss_items["link"]="$baseurl/listing/$rssdata[uri]";
			$rss_items["title"]=stripslashes($rssdata[title]);
			$rss_items["dc:date"]=date("d-M-Y H:i",$rssdate[added_date]);
			$rss_writer_object->additem($rss_items);
			$count_items++;
		}
	}


	/*
	 *  When you are done with the definition of the channel items, generate RSS document.
	 */
	if($rss_writer_object->writerss($output))
	{
		
		/*
		 *  If the document was generated successfully, you may not output it.
		 */

//		Header("Content-Type: text/xml; charset=\"".$rss_writer_object->outputencoding."\"");
		Header("Content-Type: text/xml; charset=\"$lang_encoding\"");
		Header("Content-Length: ".strval(strlen($output)));
		echo $output;
	}
	else
	{

		/*
		 *  If there was an error, output it as well.
		 */
		Header("Content-Type: text/plain");
		echo ("Error: ".$rss_writer_object->error);
	}
?>
