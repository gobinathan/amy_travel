<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty money format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     money_format<br>
 * Purpose:  convert numeric string to money formated string
 * @link http://smarty.php.net/manual/en/language.modifier.money_format.php
 * @author   Simeon Lyubenov <shake at vip dot bg>
 * @param string
 * @return string
 */
function smarty_modifier_money_format($string)
{
    return number_format($string, 0, '.', ' ');
}

?>
