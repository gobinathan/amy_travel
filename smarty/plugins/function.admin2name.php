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
 * Name:     admin2name<br>
 * Date:     Feb 26, 2003
 * Purpose:  return admin username from admin_id
 * Example:  {admin2name id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_admin2name ($params, &$smarty) {
  if (!empty($params['id'])) {
  	$sql=mysql_query("SELECT `username` from `admins` WHERE `admin_id`='$params[id]'");
  	return @mysql_result($sql,0);
  }
}
?>
