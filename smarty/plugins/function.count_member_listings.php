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

function smarty_function_count_member_listings ($params, &$smarty) {  
  if (!empty($params['member_id'])) {
	if ($params['type']=="all") {
		return count_member_listings($params[member_id]);
	}
	if ($params['type']=="active") {
		return count_member_listings_active($params[member_id]);
	}
	if ($params['type']=="special") {
		return count_member_listings_special($params[member_id]);
	}
	if ($params['type']=="waiting") {
		return count_member_listings_waiting($params[member_id]);
	}
	if ($params['type']=="deletion") {
		return count_member_listings_deletion($params[member_id]);
	}
  }
}
?>
