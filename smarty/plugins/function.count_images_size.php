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
 * Name:     count_images_size<br>
 * Date:     Feb 26, 2003
 * Purpose:  return number of bytes in images in a given listing
 * Example:  {count_images_size listing_id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_count_images_size ($params, &$smarty) {
  if (!empty($params['listing_id'])) {
		$images_size=count_images_size($params[listing_id]);
		$images_size=ByteSize($images_size).ByteSize($images_size,true);
		return $images_size;
  }
}
?>
