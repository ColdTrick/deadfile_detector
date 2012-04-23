<?php

	
	function deadfile_detector_directory_listing($directory, $recursive = true) {
		$array_items = array();
		
		if ($handle = opendir($directory)) {
			while (false !== ($file = readdir($handle))) {
				if (!in_array($file, array('.', '..'))) {
					if (is_dir($directory. '/' . $file))  {
						if($recursive) {
							$array_items = array_merge($array_items, deadfile_detector_directory_listing($directory. "/" . $file, $recursive));
						}
					} else {
						$file = $directory . '/' . $file;
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					}
				}
		    }
		}

		return $array_items;
	}
	
	function deadfile_detector_check_file_owner($file) {
		$dataroot = elgg_get_config('dataroot');
		
		$file_path = str_replace($dataroot, '', $file);
		
		list($year, $month, $day, $owner_guid) = explode('/', $file_path);
		
		if(is_numeric($year) && (strlen($year) == 4)) {			
			if(is_numeric($month) && (strlen($month) == 2)) {
				if(is_numeric($day) && (strlen($day) == 2)) {
					return $owner_guid;
				}
			}
		}
		
		return false;
	}
	
	function deadfile_detector_check_file_entities() {
		$dead_files = array();
		
		$file_options = array(
			'type' => 'object',
			'subtype' => 'file'
		);
		
		if($file_entities = elgg_get_entities($file_options)) {
			foreach($file_entities as $file) {
				if(!$file->exists()) {
					$dead_files[] = array(
						'reason' => elgg_echo('deadfile_detector:reason:no_file'),
						'filename' => $file->getFilenameOnFilestore(),
						'object' => $file
					);
				}
			}
		}
		
		return $dead_files;
	}
	
	function deadfile_detector_check_files_on_disk() {
		$dataroot = elgg_get_config('dataroot');
		
		$dead_files = array();
		
		$files = deadfile_detector_directory_listing($dataroot);
		
		foreach($files as $file) {
			if($owner_guid = deadfile_detector_check_file_owner($file)) {
				if(!($owner = get_entity($owner_guid))) {					
					$dead_files[] = array(
						'reason' => elgg_echo('deadfile_detector:reason:no_owner'),
						'filename' => $file
					);
				}
			}
		}
		
		return $dead_files;
	}
	
	function deadfile_detector_get_deadfiles() {
		$files = array(
			'no_file' => deadfile_detector_check_file_entities(), 
			'no_owner' => deadfile_detector_check_files_on_disk()
		);
		
		return $files;
	}