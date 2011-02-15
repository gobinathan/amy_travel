{include file="admin/header.tpl"}
{* LOAD IE CSS TABS FIX *}
<style>
{literal}
#status_frame { 
	float:right;
}
{/literal}
</style>
<!--[if IE]>
<style>
{literal}
#status_frame { 
	margin-left:640px;
	margin-top:-900px;
	width:300px;
}
{/literal}
</style>
<![EndIf]-->
{* EOF IE CSS TABS FIX *}
{literal}
<!-- TinyMCE -->
<script type="text/javascript" src="../js/editor/tiny_mce_gzip.js"></script>
<script type="text/javascript">
function setup_editor () {
tinyMCE_GZ.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		disk_cache : true,
		width : "500",
		plugins : "safari,pagebreak,style,table,save,advhr,advimage,advlink,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,inlinepopups",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,undo,redo,|,search,replace,|,bullist,numlist,|,outdent,indent,|,link,unlink,image,media,anchor,|,insertdate,inserttime,|,forecolor,backcolor,styleprops",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,charmap,advhr,blockquote,cleanup,|,print,|,ltr,rtl,|,code,preview,fullscreen",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		{/literal}content_css : "{$BASE_URL}/js/editor/content.css",{literal}

		// Drop lists for link/image/media/template dialogs
//		template_external_list_url : "../uploads/editor/template_list.js",
//		external_link_list_url : "../uploads/editor/link_list.js",
//		external_image_list_url : "../uploads/editor/image_list.js",
//		media_external_list_url : "../uploads/editor/media_list.js",

		// Customs
		skin : "o2k7",
		skin_variant : "silver",
		file_browser_callback : "myFileBrowser",

	// Replace values for the template plugin
	template_replace_values : {
		username : "shake",
		staffid : "777"
	}
}, function () {
tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		disk_cache : true,
		ask: true,
		width : "500",
		plugins : "safari,pagebreak,style,table,save,advhr,advimage,advlink,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,inlinepopups",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,undo,redo,|,search,replace,|,bullist,numlist,|,outdent,indent,|,link,unlink,image,media,anchor,|,insertdate,inserttime,|,forecolor,backcolor,styleprops",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,charmap,advhr,blockquote,cleanup,|,print,|,ltr,rtl,|,code,preview,fullscreen",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		{/literal}content_css : "{$BASE_URL}/js/editor/content.css",{literal}

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "../uploads/editor/template_list.js",
		external_link_list_url : "../uploads/editor/link_list.js",
		external_image_list_url : "../uploads/editor/image_list.js",
		media_external_list_url : "../uploads/editor/media_list.js",

		// Customs
		skin : "o2k7",
		skin_variant : "silver",
		file_browser_callback : "myFileBrowser",

	// Replace values for the template plugin
	template_replace_values : {
		username : "Some User",
		staffid : "991234"
	}
		});
	});
}
function myFileBrowser (field_name, url, type, win) {

    // alert("Field_Name: " + field_name + "\nURL: " + url + "\nType: " + type + "\nWin: " + win); // debug/testing

    /* If you work with sessions in PHP and your client doesn't accept cookies you might need to carry
       the session name and session ID in the request string (can look like this: "?PHPSESSID=88p0n70s9dsknra96qhuk6etm5").
       These lines of code extract the necessary parameters and add them back to the filebrowser URL again. */

//    var cmsURL = window.location.toString();    // script URL - use an absolute path!
{/literal}	  var cmsURL = "{$BASE_URL}/js/editor/upload_image.php"; {literal}
    if (cmsURL.indexOf("?") < 0) {
        //add the type as the only query parameter
        cmsURL = cmsURL + "?type=" + type;
    }
    else {
        //add the type as an additional query parameter
        // (PHP session ID is now included if there is one at all)
        cmsURL = cmsURL + "&type=" + type;
    }

    tinyMCE.activeEditor.windowManager.open({
        file : cmsURL,
        title : 'Media Browser',
        width : 620,  // Your dimensions may differ - toy around with them!
        height : 400,
        resizable : "yes",
        inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
        close_previous : "no"
    }, {
        window : win,
        input : field_name
    });
    return false;
  }

function toggleEditor(id) {
	var elm = document.getElementById(id);

	if (tinyMCE.getInstanceById(id) == null)
		tinyMCE.execCommand('mceAddControl', false, id);
	else
		tinyMCE.execCommand('mceRemoveControl', false, id);
}

</script>
<!-- /TinyMCE -->
{/literal}
		<div class="left">
			<h3>{#menu_marketing_send_newsletter#}</h3>
			{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
			<div class="left_box">
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("from", "fullname", "replyto", "subject");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#from_email#}", "{#from_name#}", "{#reply_to_email#}", "{#mail_subject#}");
</script>

<form method=post action="{$smarty.server.PHP_SELF|xss}" name="frm" enctype="multipart/form-data" onsubmit="return formCheck(this);" target="frameBe">
<div id="frm">
<input type="submit" name="send_newsletter" value="{#send_newsletter_submit#}" /><br/>
<label>{#from_email#}:</label> <input type="text" name="from" size="30" value="{$conf.system_email}" class="required" onMouseover="showhint('{#hint_marketing_from_email#}', this, event, '150px')"/><br />
<label>{#from_name#}:</label> <input type="text" name="fullname" size="30" value="{$conf.system_name}" class="required" onMouseover="showhint('{#hint_marketing_from_name#}', this, event, '150px')"/><br />
<label>{#reply_to_email#}:</label> <input type="text" name="replyto" size="30" value="{$conf.system_email}" class="required" onMouseover="showhint('{#hint_marketing_reply_to#}', this, event, '150px')"/><br />
</div>
{#mail_subject#}:<input type="text" name="subject" size="60" class="required" onMouseover="showhint('{#hint_marketing_email_subject#}', this, event, '150px')"/><br /><br/>
{#message_body#}:<br />
<i>{#tip_mail_variables#}</i>:<b>{literal}{email} {fullname}{/literal}</b><br/>
<textarea cols="70" rows="17" name="msg"></textarea><img src="../images/required.gif" border="0"/><br/><a href="javascript:document.getElementById('content_html').checked=true;setup_editor();toggleEditor('msg');">{#show_hide_editor#}</a>
<br/>
<input type="radio" name="contenttype" value="plain" checked>{#content_plain#} 
<input type="radio" id="content_html" name="contenttype" value="html">{#content_html#} 
<br/>
{#attach_file#}: <input type="file" name="file" size="30"><br/>
<table border="0" class="sortable" width="600">
<caption>{#subscribers#}</caption>
<thead>
<tr><th>{#email#}</th><th>{#last_send#}</th><th>{#count_sent#}<th width="50"><a href="javascript:SelectAllCheckbox('subscribers',true);" title="{#select_all_emails#}" style="font-weight:bold;text-decoration:none;"><img src="{$BASE_URL}/admin/images/plus.gif" border="0"/></a>&nbsp;<a href="javascript:SelectAllCheckbox('subscribers',false);" title="{#deselect_all_emails#}" style="font-weight:bold;text-decoration:none;"><img src="{$BASE_URL}/admin/images/minus.gif" border="0"/></a></th></tr>
</thead>
<tbody>
{foreach from=$emails item=email name=email_list}
<tr class="{cycle values="odd,none"}"><td><b>{$email.email}</b></td><td>&nbsp;{$email.last_send|date_format:"%d/%b/%Y %H:%M:%S"}</td><td>&nbsp;{$email.count_sent}</td><td align="center"><input type="checkbox" name="member[{$smarty.foreach.email_list.iteration
}]" value="{$email.email}" checked id="subscribers" /></td></tr>
{/foreach}
</tbody>
</table>
<table border="0" class="sortable" width="600">
{* memberS EMAILS *}
<caption>{#menu_members#}</caption>
<thead>
<tr><th>{#email#}</th><th>{#fullname#}</th><th>{#last_send#}</th><th>{#count_sent#}<th width="50"><a href="javascript:SelectAllCheckbox('members',true);" title="{#select_all_emails#}" style="font-weight:bold;text-decoration:none;"><img src="{$BASE_URL}/admin/images/plus.gif" border="0"/></a>&nbsp;<a href="javascript:SelectAllCheckbox('members',false);" title="{#deselect_all_emails#}" style="font-weight:bold;text-decoration:none;"><img src="{$BASE_URL}/admin/images/minus.gif" border="0"/></a>&nbsp;</th></tr>
</thead>
<tbody>
{foreach from=$members item=member name=member_email_list}
<tr class="{cycle values="odd,none"}"><td><b>{$member.email}</b></td><td>{$member.fullname}</td><td>&nbsp;{$email.last_send|date_format:"%d/%b/%Y %H:%M:%S"}</td><td>&nbsp;{$email.count_sent}</td><td align="center"><input type="checkbox" name="member[{$smarty.foreach.member_email_list.iteration+$smarty.foreach.email_list.total}]" value="{$member.email}" checked id="members" /></td></tr>
{/foreach}
</tbody>
</table>
<input type="hidden" name="num" value="{$smarty.foreach.email_list.total+$smarty.foreach.member_email_list.total}" />
<input type="submit" name="send_newsletter" value="{#send_newsletter_submit#}" />
</form>
<p id="status_frame"><iframe name="frameBe" id="frameBe" style="float: right;" width="300"></iframe><span style="float:right;clear:left;padding-left:5px;">Status:</span></p>
{include file="admin/footer.tpl"}