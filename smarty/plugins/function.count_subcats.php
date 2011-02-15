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
 * Name:     count_subcats<br>
 * Date:     Feb 26, 2003
 * Purpose:  return number of subcategories in a given category
 * Example:  {count_subcats cat_id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_count_subcats ($params, &$smarty) {
  if (!empty($params['cat_id'])) {
  	$sql=mysql_query("SELECT COUNT(*) from `categories` WHERE `parent`='$params[cat_id]'");
  	return @mysql_result($sql,0);
  }
}
?>
