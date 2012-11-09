<?php
	// ================================================
	// PHP image browser - iBrowser 
	// ================================================
	// iBrowser dialog - dir functions
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: GPL - see license.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.0                   Date: 12/12/2005
	// ================================================
	
	$files = array();	
	foreach ($cfg['ilibs_dir'] as $dir) {		
		if ($cfg['ilibs_dir_show'] == true) {
			$files[] = array('value' => absPath(str_replace($cfg['root_dir'],'',$dir)), 'text' => ucfirst(basename($dir)));				
		}		
		if(dirlist($files, str_replace('//','/',$cfg['root_dir'] . $dir))) { // get dirlist
			$cfg['ilibs'] = $files;			
		} else {
			echo 'directory error';
			return false;
		}	
	}	
	function dirlist(&$files,$dir) {		
		global $cfg;
		if ($handle = opendir($dir)) {			
			while ($file = readdir($handle)) {				
				if ($file == '.' || $file == '..') {
					continue;					
				}				
				$fullpath = str_replace('//','/',$dir . '/' . $file);	
				if (is_dir($fullpath)) {						
					$indent = str_repeat('&nbsp;', count(explode('/', trim(str_replace($cfg['root_dir'],'',$dir), '/')))*2);
					$files[] = array('value' => absPath(str_replace( $cfg['root_dir'],'',$fullpath ) . '/'), 'text' => $indent . ucfirst(basename($fullpath)));	
					dirlist($files,$fullpath);														   	
				}
		   	}
			closedir($handle);
			asort($files);	
		   	return true;		
		}
		return false; 		
	}
?>