<?php

        require_once LIB_PATH . 'osclass/helpers/hTranslations.php';
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
		'im_001' => osc_esc_js( __('Image browser')),
		'im_002' => osc_esc_js( __('iBrowser')),
		'im_003' => osc_esc_js( __('Menu')),
		'im_004' => osc_esc_js( __('Welcome')),
		'im_005' => osc_esc_js( __('Insert')),
		'im_006' => osc_esc_js( __('Cancel')),
		'im_007' => osc_esc_js( __('Insert')),
		'im_008' => osc_esc_js( __('Insert/change')),
		'im_009' => osc_esc_js( __('Properties')),
		'im_010' => osc_esc_js( __('Image properties')),
		'im_013' => osc_esc_js( __('Popup')),
		'im_014' => osc_esc_js( __('Image popup')),
		'im_015' => osc_esc_js( __('About iBrowser')),
		'im_016' => osc_esc_js( __('Section')),
		'im_097' => osc_esc_js( __('Please wait while loading...')),
		'im_098' => osc_esc_js( __('Open section')),
		'im_099' => osc_esc_js( __('Close section')),
		//-------------------------------------------------------------------------
		// insert/change screen - in
		'in_001' => osc_esc_js( __('Insert/Change image')),
		'in_002' => osc_esc_js( __('Library')),
		'in_003' => osc_esc_js( __('Select an image library')),
		'in_004' => osc_esc_js( __('Images')),
		'in_005' => osc_esc_js( __('Preview')),
		'in_006' => osc_esc_js( __('Delete image')),
		'in_007' => osc_esc_js( __('Click for a larger view of picture')),
		'in_008' => osc_esc_js( __('Open the image upload, rename, or delete section')),
		'in_009' => osc_esc_js( __('Information')),
		'in_010' => osc_esc_js( __('Popup')),
		'in_013' => osc_esc_js( __('Create a link to an image to be opened in a new window.')),
		'in_014' => osc_esc_js( __('remove popup link')),
		'in_015' => osc_esc_js( __('File')),
		'in_016' => osc_esc_js( __('Rename')),
		'in_017' => osc_esc_js( __('Rename image')),
		'in_018' => osc_esc_js( __('Upload')),
		'in_019' => osc_esc_js( __('Upload image')),
		'in_020' => osc_esc_js( __('Size(s)')),
		'in_021' => osc_esc_js( __('Check the desired size(s) to be created while uploading image(s)')),
		'in_022' => osc_esc_js( __('Original')),
		'in_023' => osc_esc_js( __('Image will be cropped')),
		'in_024' => osc_esc_js( __('Delete')),
		'in_025' => osc_esc_js( __('Directory')),
		'in_026' => osc_esc_js( __('Click to create a directory')),
		'in_027' => osc_esc_js( __('Create a directory')),
		'in_028' => osc_esc_js( __('Width')),
		'in_029' => osc_esc_js( __('Height')),
		'in_030' => osc_esc_js( __('Type')),
		'in_031' => osc_esc_js( __('Size')),
		'in_032' => osc_esc_js( __('Name')),
		'in_033' => osc_esc_js( __('Created')),
		'in_034' => osc_esc_js( __('Modified')),
		'in_035' => osc_esc_js( __('Image info')),
		'in_036' => osc_esc_js( __('Click on image to close window')),
		'in_037' => osc_esc_js( __('Rotate')),
		'in_038' => osc_esc_js( __('Auto rotate: set to exif info, to use EXIF orientation stored by camera. Can also be set to +180&deg; or -180&deg; for landscape, or +90&deg; or -90&deg; for portrait. Positive values for clockwise and negative values for counterclockwise.')),
		'in_041' => '',
		'in_042' => osc_esc_js( __('none')),
		'in_043' => osc_esc_js( __('portrait')),
		'in_044' => osc_esc_js( __('+ 90&deg;')),
		'in_045' => osc_esc_js( __('- 90&deg;')),
		'in_046' => osc_esc_js( __('landscape')),
		'in_047' => osc_esc_js( __('+ 180&deg;')),
		'in_048' => osc_esc_js( __('- 180&deg;')),
		'in_049' => osc_esc_js( __('camera')),
		'in_050' => osc_esc_js( __('exif info')),
		'in_051' => osc_esc_js( __('WARNING: Current image is a dynamic thumbnail created by iManager - parameters will be lost on image change.')),
		'in_052' => osc_esc_js( __('Click to switch image selection view')),
		'in_053' => osc_esc_js( __('Random')),
		'in_054' => osc_esc_js( __('If checked, random image will be inserted')),
		'in_055' => osc_esc_js( __('Check to insert random image')),
		'in_056' => osc_esc_js( __('Parameters')),
		'in_057' => osc_esc_js( __('click to reset parameters to default values')),
		'in_099' => osc_esc_js( __('default')),
		//-------------------------------------------------------------------------
		// properties, attributes - at
		'at_001' => osc_esc_js( __('Image attributes')),
		'at_002' => osc_esc_js( __('Source')),
		'at_003' => osc_esc_js( __('Title')),
		'at_004' => osc_esc_js( __('TITLE - displays image description onmouseover')),
		'at_005' => osc_esc_js( __('Description')),
		'at_006' => osc_esc_js( __('ALT -  textual replacement for the image, to be displayed or otherwise used in place of the image')),
		'at_007' => osc_esc_js( __('Style')),
		'at_008' => osc_esc_js( __('Please make sure that the selected style exists in your stylesheet!')),
		'at_009' => osc_esc_js( __('CSS-style')),
		'at_010' => osc_esc_js( __('Attributes')),
		'at_011' => osc_esc_js( __('The \'align\', \'border\', \'hspace\', and \'vspace\' attributes of the image element are not supported in XHTML 1.0 Strict DTD. Please use the CSS-style instead.')),
		'at_012' => osc_esc_js( __('Align')),
		'at_013' => osc_esc_js( __('default')),
		'at_014' => osc_esc_js( __('left')),
		'at_015' => osc_esc_js( __('right')),
		'at_016' => osc_esc_js( __('top')),
		'at_017' => osc_esc_js( __('middle')),
		'at_018' => osc_esc_js( __('bottom')),
		'at_019' => osc_esc_js( __('absmiddle')),
		'at_020' => osc_esc_js( __('texttop')),
		'at_021' => osc_esc_js( __('baseline')),
		'at_022' => osc_esc_js( __('Size')),
		'at_023' => osc_esc_js( __('Width')),
		'at_024' => osc_esc_js( __('Height')),
		'at_025' => osc_esc_js( __('Border')),
		'at_026' => osc_esc_js( __('V-space')),
		'at_027' => osc_esc_js( __('H-space')),
		'at_028' => osc_esc_js( __('Preview')),
		'at_029' => osc_esc_js( __('Click to enter special character into title field')),
		'at_030' => osc_esc_js( __('Click to enter special character into description field')),
		'at_031' => osc_esc_js( __('Reset image dimensions to default values')),
		'at_032' => osc_esc_js( __('Caption')),
		'at_033' => osc_esc_js( __('checked: set image caption / unchecked: no image caption set or remove image caption')),
		'at_034' => osc_esc_js( __('set image caption')),
		'at_099' => osc_esc_js( __('default')),
		//-------------------------------------------------------------------------
		// error messages - er
		'er_001' => osc_esc_js( __('Error')),
		'er_002' => osc_esc_js( __('No image is selected!')),
		'er_003' => osc_esc_js( __('Width is not a number')),
		'er_004' => osc_esc_js( __('Height is not a number')),
		'er_005' => osc_esc_js( __('Border is not a number')),
		'er_006' => osc_esc_js( __('Horizontal space is not a number')),
		'er_007' => osc_esc_js( __('Vertical space is not a number')),
		'er_008' => osc_esc_js( __('Click OK to delete the image')),
		'er_009' => osc_esc_js( __('Renaming of thumbnails is not allowed! Please rename the main image if you like to rename the thumbnail image.')),
		'er_010' => osc_esc_js( __('Click OK to rename image to')),
		'er_011' => osc_esc_js( __('New name is either empty or has not changed!')),
		'er_014' => osc_esc_js( __('Please enter new file name!')),
		'er_015' => osc_esc_js( __('Please enter valid file name!')),
		'er_016' => osc_esc_js( __('Thumbnailing not available! Please set thumbnail size in config file in order to enable thumbnailing.')),
		'er_021' => osc_esc_js( __('Click OK to upload image(s).')),
		'er_022' => osc_esc_js( __('Uploading image - please wait...')),
		'er_023' => osc_esc_js( __('No image has been selected or no file size has been checked.')),
		'er_024' => osc_esc_js( __('File')),
		'er_025' => osc_esc_js( __('already exists! Please click OK to overwrite file...')),
		'er_026' => osc_esc_js( __('Please enter new file name!')),
		'er_027' => osc_esc_js( __('Directory doesn\'t physically exist')),
		'er_028' => osc_esc_js( __('An error occured while handling file upload. Please try again.')),
		'er_029' => osc_esc_js( __('Wrong image file type')),
		'er_030' => osc_esc_js( __('Delete failed! Please try again.')),
		'er_031' => osc_esc_js( __('Overwrite')),
		'er_032' => osc_esc_js( __('Full size preview only works for pictures larger than the preview size')),
		'er_033' => osc_esc_js( __('Renaming file failed! Please try again.')),
		'er_034' => osc_esc_js( __('Creating directory failed! Please try again.')),
		'er_035' => osc_esc_js( __('Enlarging is not allowed!')),
		'er_036' => osc_esc_js( __('Error building image list!'))
	  ),
	  //-------------------------------------------------------------------------
	  // symbols
            'symbols'		=> array (
		'title' 		=> osc_esc_js( __('Symbols')),
		'ok' 			=> osc_esc_js( __('OK')),
		'cancel' 		=> osc_esc_js( __('Cancel')),
	  ),
	)
?>