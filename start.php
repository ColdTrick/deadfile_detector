<?php

	include(dirname(__FILE__) . '/lib/functions.php');
	
	function deadfile_detector_init() {
		elgg_extend_view('css/admin', 'deadfile_detector/css/admin');
		elgg_extend_view('js/admin', 'deadfile_detector/js/deadfile_detector');
		
		elgg_register_admin_menu_item('administer', 'deadfile_detector', 'administer_utilities');
	}
	
	elgg_register_event_handler('init', 'system', 'deadfile_detector_init');