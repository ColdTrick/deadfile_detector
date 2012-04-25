<?php

$file_location = urldecode($_GET['path']);

if(file_exists($file_location)) {
	if($file_handle = fopen($file_location, 'r')) {
		header('Content-Type: image/jpeg');
		echo $file_content = fread($file_handle, filesize($file_location));
	}
}

