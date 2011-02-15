<?php
include("../../config.php");
include("../../includes/functions.php");	
@session_start();
$admin_id=$_SESSION['admin_id'];
if (!is_numeric($admin_id)) {
	include("../../agents/checklogin.php");
}
if ($gd=="yes") {include("../../includes/thumb.class.php");}
$upload_dir = "../../uploads/editor/";
$upload_dir_thumbs = "../../uploads/editor/thumbs/";
if (isset($_GET['delete'])) {
	$file=sqlx($_GET['delete']);
	$file=after_last('/',$file);
	@unlink("$upload_dir/$file");
	@unlink("$upload_dir_thumbs/$file");
}
?>
<html><head>
<title>Upload/Select Image</title>
<script language="javascript" type="text/javascript" src="tiny_mce_popup.js"></script>
<script language="javascript" type="text/javascript">
var FileBrowserDialogue = {
    init : function () {
        // Here goes your code for setting your custom things onLoad.
    },
    mySubmit : function (URL) {
//        var URL = document.my_form.my_field.value;
        var win = tinyMCEPopup.getWindowArg("window");

        // insert information now
        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = URL;

        // are we an image browser
        if (typeof(win.ImageDialog) != "undefined")
        {
            // we are, so update image dimensions and preview if necessary
            if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();
            if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(URL);
        }

        // close popup window
        tinyMCEPopup.close();
    }
}

tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);
</script>
<style>
#img img {
float:left;
margin-top:4px;
/* border:0px; */
border:solid 2px #A4AFBD;
}
#img img:hover {border:solid 2px #FFFFFF;}
#imgbox {
border:solid 1px #A4AFBD;
float:left;
}
</style>
</head>
<body>
<?php
	if ($_FILES['image']['size']) {
		$now=time();
		$uploaded = do_upload($upload_dir,"image","$now");
		$uploaded=substr($uploaded, 1);
		$image=after_last('/',$uploaded);
		// Resize Image
		if ($gd=="yes") {
			$tm = new dThumbMaker; 
			$load = $tm->loadFile($upload_dir.$image);
			if($load === true){ // Note three '='      
			    $tm->resizeMaxSize($config[img_resize_h], $config[img_resize_w]); 
//				$tm->addWaterMark('images/watermark.gif', 64, 64, true);
			    $tm->build($upload_dir.$image); 
			}
		}
		//EOF Resize Image
		// Create Thumbnail
		if ($gd=="yes") {
			$tm = new dThumbMaker; 
			$load = $tm->loadFile($upload_dir.$image);
			if($load === true){ // Note three '='      
			    $tm->resizeMaxSize($config[thumb_resize_h], $config[thumb_resize_w]); 
//				$tm->addWaterMark('images/watermark.gif', 64, 64, true);
		    	$tm->build($upload_dir_thumbs.$image);
			}
		}
		//EOF THUMB creation	
?><a href="#" title="Click to insert this image" onClick="FileBrowserDialogue.mySubmit('<?php=$config[base_url]?>/uploads/editor/<?php=$image?>')"><img src="<?php=$config[base_url]?>/uploads/editor/thumbs/<?php=$image?>" border="0" width="130" height="92" /></a><br/>
<?php
}
?>
<form action="upload_image.php" method="post" ENCTYPE="multipart/form-data">
<input type="file" name="image" /><br/>
<input type="submit" name="submit" value="Upload New" /></form>
<hr/>
<b>Files</b><br/>
<div id="img">
<?php
if ($handle = opendir("$upload_dir")) {
   while (false !== ($file = readdir($handle))) {
       if ($file != "." && $file != ".." && !is_dir("$upload_dir/$file")) {
// check if image
if (after_last('.',$file)=="jpg" OR after_last('.',$file)=="jpeg" OR after_last('.',$file)=="gif" OR after_last('.',$file)=="bmp") {
	?><span id="imgbox"><a href="#" title="Click to insert this image" onClick="FileBrowserDialogue.mySubmit('<?php=$config[base_url]?>/uploads/editor/<?php=$file?>')"><img src="<?php=$config[base_url]?>/uploads/editor/thumbs/<?php=$file?>" border="0" width="100" height="72" /></a>
	<?if ($admin_id > "0") {?>
	<br/><a href="?delete=<?php=$file?>">Delete</a>
	<?php } ?>
	</span><?php
}
       }
   }
   closedir($handle);
}
?></div></hr>