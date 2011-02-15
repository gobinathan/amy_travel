{literal}
<!-- TinyMCE -->
<script type="text/javascript" src="../js/editor/tiny_mce_gzip.js"></script>
<script type="text/javascript">
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
		template_external_list_url : "../uploads/editor/template_list.js",
		external_link_list_url : "../uploads/editor/link_list.js",
		external_image_list_url : "../uploads/editor/image_list.js",
		media_external_list_url : "../uploads/editor/media_list.js",

		// Customs
		skin : "o2k7",
		skin_variant : "silver",

	// Replace values for the template plugin
	template_replace_values : {
		username : "shake",
		staffid : "777"
	}
});
</script>
<script type="text/javascript">
tinyMCE.init({
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
		template_external_list_url : "../uploads/editor/template_list.js",
		external_link_list_url : "../uploads/editor/link_list.js",
		external_image_list_url : "../uploads/editor/image_list.js",
		media_external_list_url : "../uploads/editor/media_list.js",

		// Customs
		skin : "o2k7",
		skin_variant : "silver",

	// Replace values for the template plugin
	template_replace_values : {
		username : "Some User",
		staffid : "991234"
	}
});
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
