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
 * Name:     city2country<br>
 * Date:     Feb 26, 2003
 * Purpose:  return city title from city_id
 * Example:  {city2country id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_city2country ($params, &$smarty) {
  if (!empty($params['id'])) {
	if (!empty($params['lang'])) {
  		return city2country($params['id'],$params['lang']);
  	}else{
	    return city2country($params['id']);
	}
  }
}
?>
