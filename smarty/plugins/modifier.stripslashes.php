<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plugin
 *
 * Type:     modifier<br>
 * Name:     stripslashes<br>
 * Date:     Feb 26, 2003
 * Purpose:  strip slashes
 * Example:  {$text|stripslashes}
 * @version  1.0
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */
function smarty_modifier_stripslashes($string)
{
    return stripslashes($string);
}

/* vim: set expandtab: */

?>
