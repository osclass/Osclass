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
		'im_001' => "'".osc_esc_js( _('Image browser'))."'",
		'im_002' => "'".osc_esc_js( _('iBrowser'))."'",
		'im_003' => "'".osc_esc_js( _('Menu'))."'",
		'im_004' => "'".osc_esc_js( _('Welcome'))."'",
		'im_005' => "'".osc_esc_js( _('Insert'))."'",
		'im_006' => "'".osc_esc_js( _('Cancel'))."'",
		'im_007' => "'".osc_esc_js( _('Insert'))."'",
		'im_008' => "'".osc_esc_js( _('Insert/change'))."'",
		'im_009' => "'".osc_esc_js( _('Properties'))."'",
		'im_010' => "'".osc_esc_js( _('Image properties'))."'",
		'im_013' => "'".osc_esc_js( _('Popup'))."'",
		'im_014' => "'".osc_esc_js( _('Image popup'))."'",
		'im_015' => "'".osc_esc_js( _('About iBrowser'))."'",
		'im_016' => "'".osc_esc_js( _('Section'))."'",
		'im_097' => "'".osc_esc_js( _('Please wait while loading...'))."'",
		'im_098' => "'".osc_esc_js( _('Open section'))."'",
		'im_099' => "'".osc_esc_js( _('Close section'))."'",
		//-------------------------------------------------------------------------
		// insert/change screen - in
		'in_001' => "'".osc_esc_js( _('Insert/Change image'))."'",
		'in_002' => "'".osc_esc_js( _('Library'))."'",
		'in_003' => "'".osc_esc_js( _('Select an image library'))."'",
		'in_004' => "'".osc_esc_js( _('Images'))."'",
		'in_005' => "'".osc_esc_js( _('Preview'))."'",
		'in_006' => "'".osc_esc_js( _('Delete image'))."'",
		'in_007' => "'".osc_esc_js( _('Click for a larger view of picture'))."'",
		'in_008' => "'".osc_esc_js( _('Open the image upload, rename, or delete section'))."'",
		'in_009' => "'".osc_esc_js( _('Information'))."'",
		'in_010' => "'".osc_esc_js( _('Popup'))."'",
		'in_013' => "'".osc_esc_js( _('Create a link to an image to be opened in a new window.'))."'",
		'in_014' => "'".osc_esc_js( _('remove popup link'))."'",
		'in_015' => "'".osc_esc_js( _('File'))."'",
		'in_016' => "'".osc_esc_js( _('Rename'))."'",
		'in_017' => "'".osc_esc_js( _('Rename image'))."'",
		'in_018' => "'".osc_esc_js( _('Upload'))."'",
		'in_019' => "'".osc_esc_js( _('Upload image'))."'",
		'in_020' => "'".osc_esc_js( _('Size(s)'))."'",
		'in_021' => "'".osc_esc_js( _('Check the desired size(s) to be created while uploading image(s)'))."'",
		'in_022' => "'".osc_esc_js( _('Original'))."'",
		'in_023' => "'".osc_esc_js( _('Image will be cropped'))."'",
		'in_024' => "'".osc_esc_js( _('Delete'))."'",
		'in_025' => "'".osc_esc_js( _('Directory'))."'",
		'in_026' => "'".osc_esc_js( _('Click to create a directory'))."'",
		'in_027' => "'".osc_esc_js( _('Create a directory'))."'",
		'in_028' => "'".osc_esc_js( _('Width'))."'",
		'in_029' => "'".osc_esc_js( _('Height'))."'",
		'in_030' => "'".osc_esc_js( _('Type'))."'",
		'in_031' => "'".osc_esc_js( _('Size'))."'",
		'in_032' => "'".osc_esc_js( _('Name'))."'",
		'in_033' => "'".osc_esc_js( _('Created'))."'",
		'in_034' => "'".osc_esc_js( _('Modified'))."'",
		'in_035' => "'".osc_esc_js( _('Image info'))."'",
		'in_036' => "'".osc_esc_js( _('Click on image to close window'))."'",
		'in_037' => "'".osc_esc_js( _('Rotate'))."'",
		'in_038' => "'".osc_esc_js( _('Auto rotate: set to exif info, to use EXIF orientation stored by camera. Can also be set to +180&deg; or -180&deg; for landscape, or +90&deg; or -90&deg; for portrait. Positive values for clockwise and negative values for counterclockwise.'))."'",
		'in_041' => '',
		'in_042' => "'".osc_esc_js( _('none'))."'",
		'in_043' => "'".osc_esc_js( _('portrait'))."'",
		'in_044' => "'".osc_esc_js( _('+ 90&deg;'))."'",
		'in_045' => "'".osc_esc_js( _('- 90&deg;'))."'",
		'in_046' => "'".osc_esc_js( _('landscape'))."'",
		'in_047' => "'".osc_esc_js( _('+ 180&deg;'))."'",
		'in_048' => "'".osc_esc_js( _('- 180&deg;'))."'",
		'in_049' => "'".osc_esc_js( _('camera'))."'",
		'in_050' => "'".osc_esc_js( _('exif info'))."'",
		'in_051' => "'".osc_esc_js( _('WARNING: Current image is a dynamic thumbnail created by iManager - parameters will be lost on image change.'))."'",
		'in_052' => "'".osc_esc_js( _('Click to switch image selection view'))."'",
		'in_053' => "'".osc_esc_js( _('Random'))."'",
		'in_054' => "'".osc_esc_js( _('If checked, random image will be inserted'))."'",
		'in_055' => "'".osc_esc_js( _('Check to insert random image'))."'",
		'in_056' => "'".osc_esc_js( _('Parameters'))."'",
		'in_057' => "'".osc_esc_js( _('click to reset parameters to default values'))."'",
		'in_099' => "'".osc_esc_js( _('default'))."'",
		//-------------------------------------------------------------------------
		// properties, attributes - at
		'at_001' => "'".osc_esc_js( _('Image attributes'))."'",
		'at_002' => "'".osc_esc_js( _('Source'))."'",
		'at_003' => "'".osc_esc_js( _('Title'))."'",
		'at_004' => "'".osc_esc_js( _('TITLE - displays image description onmouseover'))."'",
		'at_005' => "'".osc_esc_js( _('Description'))."'",
		'at_006' => "'".osc_esc_js( _('ALT -  textual replacement for the image, to be displayed or otherwise used in place of the image'))."'",
		'at_007' => "'".osc_esc_js( _('Style'))."'",
		'at_008' => "'".osc_esc_js( _('Please make sure that the selected style exists in your stylesheet!'))."'",
		'at_009' => "'".osc_esc_js( _('CSS-style'))."'",
		'at_010' => "'".osc_esc_js( _('Attributes'))."'",
		'at_011' => "'".osc_esc_js( _('The \'align\', \'border\', \'hspace\', and \'vspace\' attributes of the image element are not supported in XHTML 1.0 Strict DTD. Please use the CSS-style instead.'))."'",
		'at_012' => "'".osc_esc_js( _('Align'))."'",
		'at_013' => "'".osc_esc_js( _('default'))."'",
		'at_014' => "'".osc_esc_js( _('left'))."'",
		'at_015' => "'".osc_esc_js( _('right'))."'",
		'at_016' => "'".osc_esc_js( _('top'))."'",
		'at_017' => "'".osc_esc_js( _('middle'))."'",
		'at_018' => "'".osc_esc_js( _('bottom'))."'",
		'at_019' => "'".osc_esc_js( _('absmiddle'))."'",
		'at_020' => "'".osc_esc_js( _('texttop'))."'",
		'at_021' => "'".osc_esc_js( _('baseline'))."'",
		'at_022' => "'".osc_esc_js( _('Size'))."'",
		'at_023' => "'".osc_esc_js( _('Width'))."'",
		'at_024' => "'".osc_esc_js( _('Height'))."'",
		'at_025' => "'".osc_esc_js( _('Border'))."'",
		'at_026' => "'".osc_esc_js( _('V-space'))."'",
		'at_027' => "'".osc_esc_js( _('H-space'))."'",
		'at_028' => "'".osc_esc_js( _('Preview'))."'",
		'at_029' => "'".osc_esc_js( _('Click to enter special character into title field'))."'",
		'at_030' => "'".osc_esc_js( _('Click to enter special character into description field'))."'",
		'at_031' => "'".osc_esc_js( _('Reset image dimensions to default values'))."'",
		'at_032' => "'".osc_esc_js( _('Caption'))."'",
		'at_033' => "'".osc_esc_js( _('checked: set image caption / unchecked: no image caption set or remove image caption'))."'",
		'at_034' => "'".osc_esc_js( _('set image caption'))."'",
		'at_099' => "'".osc_esc_js( _('default'))."'",
		//-------------------------------------------------------------------------
		// error messages - er
		'er_001' => "'".osc_esc_js( _('Error'))."'",
		'er_002' => "'".osc_esc_js( _('No image is selected!'))."'",
		'er_003' => "'".osc_esc_js( _('Width is not a number'))."'",
		'er_004' => "'".osc_esc_js( _('Height is not a number'))."'",
		'er_005' => "'".osc_esc_js( _('Border is not a number'))."'",
		'er_006' => "'".osc_esc_js( _('Horizontal space is not a number'))."'",
		'er_007' => "'".osc_esc_js( _('Vertical space is not a number'))."'",
		'er_008' => "'".osc_esc_js( _('Click OK to delete the image'))."'",
		'er_009' => "'".osc_esc_js( _('Renaming of thumbnails is not allowed! Please rename the main image if you like to rename the thumbnail image.'))."'",
		'er_010' => "'".osc_esc_js( _('Click OK to rename image to'))."'",
		'er_011' => "'".osc_esc_js( _('New name is either empty or has not changed!'))."'",
		'er_014' => "'".osc_esc_js( _('Please enter new file name!'))."'",
		'er_015' => "'".osc_esc_js( _('Please enter valid file name!'))."'",
		'er_016' => "'".osc_esc_js( _('Thumbnailing not available! Please set thumbnail size in config file in order to enable thumbnailing.'))."'",
		'er_021' => "'".osc_esc_js( _('Click OK to upload image(s).'))."'",
		'er_022' => "'".osc_esc_js( _('Uploading image - please wait...'))."'",
		'er_023' => "'".osc_esc_js( _('No image has been selected or no file size has been checked.'))."'",
		'er_024' => "'".osc_esc_js( _('File'))."'",
		'er_025' => "'".osc_esc_js( _('already exists! Please click OK to overwrite file...'))."'",
		'er_026' => "'".osc_esc_js( _('Please enter new file name!'))."'",
		'er_027' => "'".osc_esc_js( _('Directory doesn\'t physically exist'))."'",
		'er_028' => "'".osc_esc_js( _('An error occured while handling file upload. Please try again.'))."'",
		'er_029' => "'".osc_esc_js( _('Wrong image file type'))."'",
		'er_030' => "'".osc_esc_js( _('Delete failed! Please try again.'))."'",
		'er_031' => "'".osc_esc_js( _('Overwrite'))."'",
		'er_032' => "'".osc_esc_js( _('Full size preview only works for pictures larger than the preview size'))."'",
		'er_033' => "'".osc_esc_js( _('Renaming file failed! Please try again.'))."'",
		'er_034' => "'".osc_esc_js( _('Creating directory failed! Please try again.'))."'",
		'er_035' => "'".osc_esc_js( _('Enlarging is not allowed!'))."'",
		'er_036' => "'".osc_esc_js( _('Error building image list!'))."'"
	  ),
	  //-------------------------------------------------------------------------
	  // symbols
            'symbols'		=> array (
		'title' 		=> "'".osc_esc_js( _('Symbols'))."'",
		'ok' 			=> "'".osc_esc_js( _('OK'))."'",
		'cancel' 		=> "'".osc_esc_js( _('Cancel'))."'",
	  ),
	)
?>