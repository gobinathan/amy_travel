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
 * Name:     state2name<br>
 * Date:     Feb 26, 2003
 * Purpose:  return state title from state_id
 * Example:  {state2name id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_state2name ($params, &$smarty) {
  if (!empty($params['id'])) {
	if (!empty($params['lang'])) {
  		return state2name($params['id'],$params['lang']);
  	}else{
	    return state2name($params['id']);
	}
  }
}
?>
