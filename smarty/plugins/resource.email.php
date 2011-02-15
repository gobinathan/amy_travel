<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     resource.email.php
 * Type:     resource
 * Name:     email
 * Purpose:  Fetches E-Mail templates from a database
 * -------------------------------------------------------------
 */
function smarty_resource_email_source($tpl_name, &$tpl_source, &$smarty)
{
	global $language;
    // do database call here to fetch your template,
    // populating $tpl_source
    $sql=mysql_query("select `tpl_source`
                   from `email_templates`
                  where `tpl_name`='$tpl_name' AND `lang`='$language'");
    if (@mysql_num_rows($sql)) {
        $tpl_source = @mysql_result($sql,0,'tpl_source');
        return true;
    } else {
        return false;
    }
}

function smarty_resource_email_timestamp($tpl_name, &$tpl_timestamp, &$smarty)
{
  	global $language;
    // do database call here to populate $tpl_timestamp.
    $sql=mysql_query("select `last_update`
                   from `email_templates`
                  where `tpl_name`='$tpl_name' AND `lang`='$language'");
    if (@mysql_num_rows($sql)) {
        $tpl_timestamp = @mysql_result($sql,0,'last_update');
        return true;
    } else {
        return false;
    }
}

function smarty_resource_email_secure($tpl_name, &$smarty)
{
    // assume all templates are secure
    return true;
}

function smarty_resource_email_trusted($tpl_name, &$smarty)
{
    // not used for templates
}
?>