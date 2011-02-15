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
 * Name:     count_member_listings<br>
 * Date:     Feb 26, 2003
 * Purpose:  return number of listings for a given member
 * Example:  {count_member_listings member_id=1 type=active}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_count_member_images ($params, &$smarty) {
  if (!empty($params['member_id'])) {
	return count_member_images($params['member_id']);
  }
}
?>
