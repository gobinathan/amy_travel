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
 * Name:     count_packages<br>
 * Date:     Feb 26, 2003
 * Purpose:  return number of packages in a given listing
 * Example:  {count_packages listing_id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_count_packages ($params, &$smarty) {
  if (!empty($params['listing_id'])) {
	return count_packages($params['listing_id']);
  }
}
?>
