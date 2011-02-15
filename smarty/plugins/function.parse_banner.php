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
 * Name:     parse_banner<br>
 * Date:     Feb 26, 2003
 * Purpose:  return ad code with position
 * Example:  {parse_banner position=top}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_parse_banner ($params, &$smarty) {
  if (!empty($params['position'])) {
  		return parse_banner($params['position']);
  }
}
?>
