<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plugin
 *
 * Type:     function<br>
 * Name:     count_listings<br>
 * Date:     Feb 26, 2003
 * Purpose:  return number of listing entries in a given category
 * Example:  {count_listings cat_id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_count_listings ($params, &$smarty) {
  if (!empty($params['cat_id'])) {
  	$sql=mysql_query("SELECT COUNT(*) from `listings` WHERE `cat_id`='$params[cat_id]'");
	$count_entries=@mysql_result($sql,0);
	$gocat=mysql_query("SELECT * from `categories` WHERE `parent`='$params[cat_id]'");
	while($cat=@mysql_fetch_array($gocat)) {
	  	$q=mysql_query("SELECT COUNT(*) from `listings` WHERE `cat_id`='$cat[cat_id]'");
	  	$count_entries=$count_entries+@mysql_result($q,0);
	}
	return $count_entries;
  }
}
?>
