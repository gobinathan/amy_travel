<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty XSS protection modifier
 *
 * Type:     modifier<br>
 * Name:     xss<br>
 * Purpose:  prevents URL from XSS html injection
 * Examples:
 * <pre>
 * &lt;form method="post" action="{$smarty.server.PHP_SELF|xss}"&gt;
 * </pre>
 * @author Michal "Techi" Vrchota <michal.vrchota@seznam.cz>
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 * @param string
 * @return string
 */
function smarty_modifier_xss($string)
{
	// those characters are removed from URL
    return str_replace(array('<', '>', '\'', '"', ' '), '', $string);
}

?>