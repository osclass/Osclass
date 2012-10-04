<?php
	// ================================================
	// PHP image browser - iBrowser
	// ================================================
	// iBrowser - configuration
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: GPL - see license.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.4.5                 Date: 12/28/2011
	// ================================================

	//-------------------------------------------------------------------------
	// INSTRUCTIONS:
	//
	// 	Please make sure that all of the following directories have writing permissions:
	// 	1. image libraries (chmod 0755 or 0777)
	// 	2. ibrowser/scripts/phpThumb/cache  (chmod 0755 or 0777)
	//	3. ibrowser/temp (chmod 0755 or 0777)
        date_default_timezone_set('Europe/Madrid');
	//  see readme.txt for further instructions
	include dirname(__FILE__) . '/../../../../../../../../config.php';
	//-------------------------------------------------------------------------
	$cfg['lang']   		= 'en';														// default language; e.g. 'en'
	$cfg['valid']  		= array('gif', 'jpg', 'jpeg', 'png');						// valid extentions for image files
	$cfg['upload'] 		= true; 													// allow uploading of image: 'true' or 'false'
	$cfg['umax'] 		= 1;														// max. number of image files to be uploaded; default: 1; value > 1
	$cfg['create'] 		= true; 													// allow to create directory: 'true' or 'false'
	$cfg['delete'] 		= true; 													// allow deletion of image: 'true' or 'false'
	$cfg['rename'] 		= true; 													// allow renaming of image: 'true' or 'false'
	$cfg['attrib'] 		= false; 													// allow changing image attributes: 'true' or 'false'; default = false;
	$cfg['furl'] 		= true;														// default: true; if set to true, full url incl. domain will be added to image src
	$cfg['random']      = '&w=150&h=150&zc=1';										// random image parameters (see phpThumb readme for more information)
	$cfg['style'] = array ( 														// css styles for images ('class' => 'descr'); - please make sure that the classes exist in your css file
			'left' 				=> 'align left',									// image: float left
			'right' 			=> 'align right',									// image: float right
			'capDivRightBrd' 	=> 'align right, border',							// caption: float right with border
			'capDivRight' 		=> 'align right',									// caption: float right
			'capDivLeftBrd' 	=> 'align left, border',							// caption: float left with border
			'capDivLeft' 		=> 'align left',									// caption: float left
	);
	$cfg['list']		= true;														// default: true; if set to true, image selection will be shown as list; if set to false, image selection will show thumbnails
	//-------------------------------------------------------------------------
	// set image formats
	$cfg['thumbs'] = array (
		/* array (																	//				settings
			'size'   	=> '*',														//				'size' = if set to '*' or '0', no image resizing will be done, otherwise set to desired width or height, e.g. 640
			'ext'    	=> '*',														//				'ext'  = if set to '*' width or height will be set as identifier. If set to '', no identifier will be set.
			'crop'    	=> false,													//				'crop' = if set to true, image will be zoom cropped resulting in a square image
		), */
		/*	array (
			'size'   	=> 1280,
			'ext'    	=> '*',
			'crop'    	=> false,
		),
		array (
			'size'   	=> 1024,
			'ext'    	=> '*',
			'crop'    	=> false,
		),
		array (
			'size'   	=> 640,
			'ext'    	=> '*',
			'crop'    	=> false,
		),
		array (
			'size'   	=> 512,
			'ext'    	=> '*',
			'crop'    	=> false,
		), */
		array (
			'size'   	=> 400,
			'ext'    	=> '*',
			'crop'    	=> false,
		),
		array (
			'size'   	=> 400,
			'ext'    	=> '*',
			'crop'    	=> true,
		),
		array (
			'size'   	=> 120,
			'ext'    	=> '*',
			'crop'    	=> false,
		),
/*		array (
			'size'   	=> 75,
			'ext'    	=> '*',
			'crop'    	=> false,
		), */
	);
	//-------------------------------------------------------------------------
	// use static image libraries
	$cfg['ilibs'] = array (																// image library path with slashes; absolute to root directory - please make sure that the directories have write permissions
		array (
			'value'   	=> REL_WEB_URL.'oc-content/uploads/',
			'text'    	=> 'Site Pictures',
		)
//                ,array (
//			'value'   	=> '/projects/iBrowser/pictures/',
//			'text'    	=> 'Gallery',
//		),
	);
	//-------------------------------------------------------------------------
	// use dynamic image libraries - if $cfg['ilibs_inc'] is set, static image libraries above are ignored
	// image directories to be scanned
	$cfg['ilibs_dir'] 	   = array('/projects/iBrowser/pictures/');						   	// image library path with slashes; absolute to root directory - please make sure that the directories have write permissions
	$cfg['ilibs_dir_show'] = true;														// show main library (true) or only sub-dirs (false)
	//$cfg['ilibs_inc']      = realpath(dirname(__FILE__) . '/../scripts/rdirs.php'); 	// file to include in ibrowser.php (useful for setting $cfg['ilibs] dynamically
	//-------------------------------------------------------------------------
	// you shouldn't need to make any changes to the config variable beyond this line!
	//-------------------------------------------------------------------------
	$osslash = ((strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? '\\' : '/');
	$cfg['ver'] 		= '1.4.5 - build 12282011';										// iBrowser version
	//$cfg['root_dir']	= realpath((getenv('DOCUMENT_ROOT') && ereg('^'.preg_quote(realpath(getenv('DOCUMENT_ROOT'))), realpath(__FILE__))) ? getenv('DOCUMENT_ROOT') : str_replace(dirname(@$_SERVER['PHP_SELF']), '', str_replace($osslash, '/', dirname(__FILE__))));
	$cfg['root_dir']    = ((@$_SERVER['DOCUMENT_ROOT'] && file_exists(@$_SERVER['DOCUMENT_ROOT'] . $_SERVER['PHP_SELF'])) ? $_SERVER['DOCUMENT_ROOT'] : str_replace(dirname(@$_SERVER['PHP_SELF']), '', str_replace('\\', '/', realpath('.'))));
	$cfg['base_url'] 	= 'http://' . $_SERVER['SERVER_NAME']; 							// base url; e.g. 'http://localhost/'
	//$cfg['main_dir'] 	= dirname($_SERVER['PHP_SELF']); 								// iBrowser main dir; e.g. '/home/domain/public_html/ibrowser/'
	$cfg['main_dir']    = str_replace("//", "/", dirname($_SERVER['PHP_SELF']));
	//echo 'dir is: ' . $cfg['main_dir'];
	$cfg['scripts']  	= $cfg['main_dir'] . '/scripts/'; 								// scripts dir; e.g. '/home/domain/public_html/ibrowser/scripts/'
	$cfg['pop_url']    	= $cfg['scripts'] . 'popup.php'; 								// popup dir; relative to 'script' dir
	$cfg['temp']     	= realpath(dirname(__FILE__) . '/../temp'); 					// temp dir; e.g. 'D:/www/temp'
?>