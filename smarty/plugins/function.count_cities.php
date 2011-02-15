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
 * Name:     count_cities<br>
 * Date:     Feb 26, 2003
 * Purpose:  return number of cities in a given country
 * Example:  {count_cities country_id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_count_cities ($params, &$smarty) {
  if (!empty($params['country_id'])) {
	return count_cities($params['country_id']);
  }
}
?>
