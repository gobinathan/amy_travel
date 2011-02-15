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
 * Name:     count_videos_size<br>
 * Date:     Feb 26, 2003
 * Purpose:  return number of bytes in videos in a given listing
 * Example:  {count_videos_size listing_id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_count_videos_size ($params, &$smarty) {
  if (!empty($params['listing_id'])) {
		$video_size=count_videos_size($params[listing_id]);
		$video_size=ByteSize($video_size).ByteSize($video_size,true);
		return $video_size;
  }
}
?>
