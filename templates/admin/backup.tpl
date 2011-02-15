{include file="admin/header.tpl"}
		<div class="left">
{include file="admin/msg.tpl"}
<div id="loading" style="display:none;"><br/><b>Please wait while the backup is created...</b><br/><img src="{$BASE_URL}/images/loading.gif" border="0" /><br/></div>

<br/>
{if $newest_backup_db > "0"}
Last <b>Database</b> Backup is from {$newest_backup_db|date_format:"%d/%b/%Y %H:%M"}<br/>
{/if}
{if $newest_backup_uploads > "0"}
Last <b>Uploads</b> Backup is from {$newest_backup_uploads|date_format:"%d/%b/%Y %H:%M"}<br/>
{/if}
{if $newest_backup_images > "0"}
Last <b>Images</b> Backup is from {$newest_backup_images|date_format:"%d/%b/%Y %H:%M"}<br/>
{/if}
{if $newest_backup_lang > "0"}
Last <b>Languages</b> Backup is from {$newest_backup_lang|date_format:"%d/%b/%Y %H:%M"}<br/>
{/if}
<br/>
			<fieldset style="border: 1px solid #A9C0CE"><legend><h3>File Backup</h3></legend>
<br/>
{if $ziplib eq "1"}
<input class="submit" name="Button" type="button" onClick="window.location='backup.php?create_backup_languages=true';document.getElementById('loading').style.display = 'block';document.getElementById('proceed').style.display = 'none';" value="Backup Languages" />&nbsp;
<input class="submit" name="Button" type="button" onClick="window.location='backup.php?create_backup_uploads=true';document.getElementById('loading').style.display = 'block';document.getElementById('proceed').style.display = 'none';" value="Backup All Uploads" />&nbsp;
<input class="submit" name="Button" type="button" onClick="window.location='backup.php?create_backup_images=true';document.getElementById('loading').style.display = 'block';document.getElementById('proceed').style.display = 'none';" value="Backup Images Uploads" />&nbsp;
{else}
<font color="red"><b>PHP 5+ is required for the Files Backup.</b></font>
<br/>PHP 5 includes the PclZip library that offers compression and extraction functions for Zip formatted archives (WinZip, PKZIP).
{/if}
<br/><br/>
{if count($filebackups)}
<table border="0" class="sortable">
<caption>{#backups_server#}</caption>
<thead><tr><th>{#b_filename#}</th><th>{#b_created#}</th><th>{#b_size#}</th><th>{#action#}</th></tr></thead>
<tbody>
{foreach from=$filebackups item=backup}
<tr class="{cycle values="oddrow,none"}"><td><a class="tbl_link" href="backup.php?download_backup={$backup.filename}" target="_blank" title="Download">{$backup.filename}</a></td><td>{$backup.date}</td><td>{$backup.size}</td><td><a href="#" onMouseOver="showhint('{#hint_delete_backup#}', this, event, '150px')" onClick="DeleteItem('backup.php?delete={$backup.filename}')" ><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_backup#}" border="0"></a> | <a href="#" onMouseOver="showhint('{#hint_restore_backup1#} <b>{$backup.date}</b><br/>This will replace all your existing files and <br/><b>CANNOT BE UNDONE!</b>', this, event, '150px')" onClick="DeleteItem('backup.php?unzip={$backup.filename}')"><img src="{$BASE_URL}/admin/images/backup_restore.gif" alt="{#restore_backup#}" border="0"></a></td></tr>
{/foreach}
</tbody>
</table>
{/if}
</fieldset>
<br/><br/>
			<fieldset style="border: 1px solid #A9C0CE"><legend><h3>{#database_backup#}</h3></legend>
<br/>
<fieldset style="width:500px;height:120px;margin-left:20px;"><legend>{#create_backup#}</legend>
<form method="post" action="{$smarty.server.PHP_SELF|xss}">
<div id="frm">
<label>{#gzip_compression#}:</label> <input type="checkbox" name="gzip" checked onMouseOver="showhint('{#hint_gzip_compression#}', this, event, '150px')" /><br/>
<label>{#save_backup_as#}:</label> <input type="checkbox" name="download" checked onMouseOver="showhint('{#hint_save_backup_as#}', this, event, '150px')" /><br/>
<label>{#email_backup#}:</label> <input type="checkbox" name="send_to_email" onClick="hiding('email_details')" onMouseOver="showhint('{#hint_email_backup#}', this, event, '150px')" />
<div id="email_details" style="display:none;">
<label>{#email_backup_address#}:</label> <input type="text" name="emailto" value="{$conf.system_email}" size="30" />
</div>
<br/>
<input type="submit" name="create_backup" value="{#create_backup#}" />
</div>
</form>
</fieldset>
<br/><br/>
<fieldset style="width:200px;height:120px;margin-left:20px;"><legend>{#restore_backup#}</legend>
<form action="{$smarty.server.PHP_SELF|xss}" method="post" ENCTYPE="multipart/form-data">
{#mysql_backup_file#}: <input type="file" name="dump" accept="MySQL Database Backup files" onMouseOver="showhint('{#hint_select_backup#}', this, event, '150px')" /><br/><br/>
<input type="submit" name="restore_backup" value="{#restore_backup#}" />
</form>
</fieldset>
<br/><br/>
{if count($sqlbackups)}
<table border="0" class="sortable">
<caption>{#backups_server#}</caption>
<thead><tr><th>{#b_filename#}</th><th>{#b_created#}</th><th>{#b_size#}</th><th>{#action#}</th></tr></thead>
<tbody>
{foreach from=$sqlbackups item=backup}
<tr class="{cycle values="oddrow,none"}"><td><a class="tbl_link" href="backup.php?download_backup={$backup.filename}" target="_blank" title="Download">{$backup.filename}</a></td><td>{$backup.date}</td><td>{$backup.size}</td><td><a href="#" onMouseOver="showhint('{#hint_delete_backup#}', this, event, '150px')" onClick="DeleteItem('backup.php?delete={$backup.filename}')" ><img src="{$BASE_URL}/admin/images/delete.png" alt="{#delete_backup#}" border="0"></a> | <a href="#" onMouseOver="showhint('{#hint_restore_backup1#} <b>{$backup.date}</b><br/>{#hint_restore_backup2#}', this, event, '150px')" onClick="DeleteItem('backup.php?restore_db={$backup.filename}')"><img src="{$BASE_URL}/admin/images/backup_restore.gif" alt="{#restore_backup#}" border="0"></a></td></tr>
{/foreach}
</tbody>
</table>
{/if}
{include file="admin/footer.tpl"}