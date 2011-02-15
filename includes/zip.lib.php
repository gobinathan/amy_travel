<?php
function zip_dir ($src,$dst) {
	global $config;
	chdir($config[root_dir]);
	// create object
	$zip = new ZipArchive() or die("Cannot create new ZIP archive");  
	// open archive 
	$res = $zip->open("$dst.zip", ZipArchive::CREATE);
	if ($res !== TRUE) {
		die ("Could not open archive");
	}
	// set archive comment
	$now=date("H:i:s");
	$zip->setArchiveComment("Backup from $now");

	// initialize an iterator
	// pass it the directory to be processed
	$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src));
	// iterate over the directory
	// add each file found to the archive
	foreach ($iterator as $key=>$value) {
			if (!strpos("$key","backups")) {
    			$zip->addFile(realpath($key), $key) or die ("ERROR: Could not add file: $key");            
    		}
	}
	// close and save archive
	$zip->close();
//	echo "Archive created successfully.";    
}
?>