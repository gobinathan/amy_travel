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
 * Name:     count_images<br>
 * Date:     Feb 26, 2003
 * Purpose:  return number of images in a given listing
 * Example:  {count_images listing_id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_count_images ($params, &$smarty) {
  if (!empty($params['listing_id'])) {
	return count_images($params[listing_id]);
  }
}
?>
