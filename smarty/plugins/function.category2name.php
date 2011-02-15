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
 * Name:     category2name<br>
 * Date:     Feb 26, 2003
 * Purpose:  return category title from cat_id
 * Example:  {category2name id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_category2name ($params, &$smarty) {
  if (!empty($params['id'])) {
	if (!empty($params['lang'])) {
  		return cat2name($params['id'],$params['lang']);
  	}else{
	    return cat2name($params['id']);
	}
  }
}
?>
