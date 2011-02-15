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
 * Name:     listing2uri<br>
 * Date:     Feb 26, 2003
 * Purpose:  return listing uri from id
 * Example:  {listing2uri id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_listing2uri ($params, &$smarty) {
  if (!empty($params['id'])) {
	return listing2uri($params['id']);
  }
}
?>
