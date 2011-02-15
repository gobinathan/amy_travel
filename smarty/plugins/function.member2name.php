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
 * Name:     member2name<br>
 * Date:     Feb 26, 2003
 * Purpose:  return member username from member_id
 * Example:  {member2name id=1}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */

function smarty_function_member2name ($params, &$smarty) {
  if (!empty($params['id'])) {
  	$sql=mysql_query("SELECT `username` from `members` WHERE `member_id`='$params[id]'");
  	return @mysql_result($sql,0);
  }
}
?>
