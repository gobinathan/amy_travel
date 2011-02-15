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
 * Name:     count_features<br>
 * Date:     Feb 26, 2003
 * Purpose:  return number of features in a given feature category
 * Example:  {count_features type_c_id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_count_features ($params, &$smarty) {
  if (!empty($params['type_c_id'])) {
  	$sql=mysql_query("SELECT COUNT(*) from `types` WHERE `type_c_id`='$params[type_c_id]'");
  	return @mysql_result($sql,0);
  }
}
?>
