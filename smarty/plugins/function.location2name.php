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
 * Name:     location2name<br>
 * Date:     Feb 26, 2003
 * Purpose:  return location title from location_id
 * Example:  {location2name id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_location2name ($params, &$smarty) {
  if (!empty($params['id'])) {
	if (!empty($params['lang'])) {
  		return location2name($params['id'],$params['lang']);
  	}else{
	    return location2name($params['id']);
	}
  }
}
?>
