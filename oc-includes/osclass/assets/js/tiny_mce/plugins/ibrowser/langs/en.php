<?php
	// ================================================
	// PHP image browser - iBrowser 
	// ================================================
	// iBrowser - language file: English
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: GPL - see license.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.1                   Date: 07/07/2005
	// ================================================
	
	//-------------------------------------------------------------------------
	// charset to be used in dialogs
	$lang_charset = 'iso-8859-1';
	// text direction for the current language to be used in dialogs
	$lang_direction = 'ltr';
	//-------------------------------------------------------------------------
	
	// language text data array
	// first dimension - block, second - exact phrase
	//-------------------------------------------------------------------------
	// iBrowser
	$lang_data = array (  
		'ibrowser' => array (
		//-------------------------------------------------------------------------
		// common - im
		'im_001' => 'Image browser',
		'im_002' => 'iBrowser',
		'im_003' => 'Menu',
		'im_004' => 'Welcome',
		'im_005' => 'Insert',
		'im_006' => 'Cancel',
		'im_007' => 'Insert',		
		'im_008' => 'Insert/change',
		'im_009' => 'Properties',
		'im_010' => 'Image properties',
		'im_013' => 'Popup',
		'im_014' => 'Image popup',
		'im_015' => 'About iBrowser',
		'im_016' => 'Section',
		'im_097' => 'Please wait while loading...',
		'im_098' =>	'Open section',
		'im_099' => 'Close section',
		//-------------------------------------------------------------------------
		// insert/change screen - in	
		'in_001' => 'Insert/Change image',
		'in_002' => 'Library',
		'in_003' => 'Select an image library',
		'in_004' => 'Images',
		'in_005' => 'Preview',
		'in_006' => 'Delete image',
		'in_007' => 'Click for a larger view of picture',
		'in_008' => 'Open the image upload, rename, or delete section',	
		'in_009' => 'Information',
		'in_010' => 'Popup',		
		'in_013' => 'Create a link to an image to be opened in a new window.',
		'in_014' => 'remove popup link',	
		'in_015' => 'File',	
		'in_016' => 'Rename',
		'in_017' => 'Rename image',
		'in_018' => 'Upload',
		'in_019' => 'Upload image',	
		'in_020' => 'Size(s)',
		'in_021' => 'Check the desired size(s) to be created while uploading image(s)',
		'in_022' => 'Original',
		'in_023' => 'Image will be cropped',
		'in_024' => 'Delete',
		'in_025' => 'Directory',
		'in_026' => 'Click to create a directory',
		'in_027' => 'Create a directory',
		'in_028' => 'Width',
		'in_029' => 'Height',
		'in_030' => 'Type',
		'in_031' => 'Size',
		'in_032' => 'Name',
		'in_033' => 'Created',
		'in_034' => 'Modified',
		'in_035' => 'Image info',
		'in_036' => 'Click on image to close window',
		'in_037' => 'Rotate',
		'in_038' => 'Auto rotate: set to exif info, to use EXIF orientation stored by camera. Can also be set to +180&deg; or -180&deg; for landscape, or +90&deg; or -90&deg; for portrait. Positive values for clockwise and negative values for counterclockwise.',
		'in_041' => '',
		'in_042' => 'none',		
		'in_043' => 'portrait',
		'in_044' => '+ 90&deg;',	
		'in_045' => '- 90&deg;',
		'in_046' => 'landscape',	
		'in_047' => '+ 180&deg;',	
		'in_048' => '- 180&deg;',
		'in_049' => 'camera',	
		'in_050' => 'exif info',
		'in_051' => 'WARNING: Current image is a dynamic thumbnail created by iManager - parameters will be lost on image change.',
		'in_052' => 'Click to switch image selection view',
		'in_053' => 'Random',
		'in_054' => 'If checked, random image will be inserted',
		'in_055' => 'Check to insert random image',
		'in_056' => 'Parameters',
		'in_057' => 'click to reset parameters to default values',
		'in_099' => 'default',	
		//-------------------------------------------------------------------------
		// properties, attributes - at
		'at_001' => 'Image attributes',
		'at_002' => 'Source',
		'at_003' => 'Title',
		'at_004' => 'TITLE - displays image description onmouseover',
		'at_005' => 'Description',
		'at_006' => 'ALT -  textual replacement for the image, to be displayed or otherwise used in place of the image',
		'at_007' => 'Style',
		'at_008' => 'Please make sure that the selected style exists in your stylesheet!',
		'at_009' => 'CSS-style',	
		'at_010' => 'Attributes',
		'at_011' => 'The \'align\', \'border\', \'hspace\', and \'vspace\' attributes of the image element are not supported in XHTML 1.0 Strict DTD. Please use the CSS-style instead.',
		'at_012' => 'Align',	
		'at_013' => 'default',
		'at_014' => 'left',
		'at_015' => 'right',
		'at_016' => 'top',
		'at_017' => 'middle',
		'at_018' => 'bottom',
		'at_019' => 'absmiddle',
		'at_020' => 'texttop',
		'at_021' => 'baseline',		
		'at_022' => 'Size',
		'at_023' => 'Width',
		'at_024' => 'Height',
		'at_025' => 'Border',
		'at_026' => 'V-space',
		'at_027' => 'H-space',
		'at_028' => 'Preview',	
		'at_029' => 'Click to enter special character into title field',
		'at_030' => 'Click to enter special character into description field',
		'at_031' => 'Reset image dimensions to default values',
		'at_032' => 'Caption',
		'at_033' => 'checked: set image caption / unchecked: no image caption set or remove image caption',
		'at_034' => 'set image caption',
		'at_099' => 'default',	
		//-------------------------------------------------------------------------		
		// error messages - er
		'er_001' => 'Error',
		'er_002' => 'No image is selected!',
		'er_003' => 'Width is not a number',
		'er_004' => 'Height is not a number',
		'er_005' => 'Border is not a number',
		'er_006' => 'Horizontal space is not a number',
		'er_007' => 'Vertical space is not a number',
		'er_008' => 'Click OK to delete the image',
		'er_009' => 'Renaming of thumbnails is not allowed! Please rename the main image if you like to rename the thumbnail image.',
		'er_010' => 'Click OK to rename image to',
		'er_011' => 'New name is either empty or has not changed!',
		'er_014' => 'Please enter new file name!',
		'er_015' => 'Please enter valid file name!',
		'er_016' => 'Thumbnailing not available! Please set thumbnail size in config file in order to enable thumbnailing.',
		'er_021' => 'Click OK to upload image(s).',
		'er_022' => 'Uploading image - please wait...',
		'er_023' => 'No image has been selected or no file size has been checked.',
		'er_024' => 'File',
		'er_025' => 'already exists! Please click OK to overwrite file...',
		'er_026' => 'Please enter new file name!',
		'er_027' => 'Directory doesn\'t physically exist',
		'er_028' => 'An error occured while handling file upload. Please try again.',
		'er_029' => 'Wrong image file type',
		'er_030' => 'Delete failed! Please try again.',
		'er_031' => 'Overwrite',
		'er_032' => 'Full size preview only works for pictures larger than the preview size',
		'er_033' => 'Renaming file failed! Please try again.',
		'er_034' => 'Creating directory failed! Please try again.',
		'er_035' => 'Enlarging is not allowed!',
		'er_036' => 'Error building image list!',
	  ),	  
	  //-------------------------------------------------------------------------
	  // symbols
		'symbols'		=> array (
		'title' 		=> 'Symbols',
		'ok' 			=> 'OK',
		'cancel' 		=> 'Cancel',
	  ),	  
	)
?>