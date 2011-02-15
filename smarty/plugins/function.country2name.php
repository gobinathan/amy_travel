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
 * Name:     country2name<br>
 * Date:     Feb 26, 2003
 * Purpose:  return country title from country_id
 * Example:  {country2name id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_country2name ($params, &$smarty) {
  if (!empty($params['id'])) {
	if (!empty($params['lang'])) {
  		return country2name($params['id'],$params['lang']);
  	}else{
	    return country2name($params['id']);
	}
  }
}
?>
