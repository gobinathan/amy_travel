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
 * Name:     count_active_listings<br>
 * Date:     Feb 26, 2003
 * Purpose:  return number of active listing entries in a given category
 * Example:  {count_active_listings cat_id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_count_active_listings ($params, &$smarty) {
  if (!empty($params['cat_id'])) {
	return count_active_listings($params['cat_id']);
  }
}
?>
