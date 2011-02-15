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
 * Name:     count_member_videos_size<br>
 * Date:     Feb 26, 2003
 * Purpose:  return size of videos for a given member
 * Example:  {count_member_videos_size member_id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_count_member_videos_size ($params, &$smarty) {
  if (!empty($params['member_id'])) {
	return count_member_videos_size($params['member_id']);
  }
}
?>
