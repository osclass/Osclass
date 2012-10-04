// ================================================
// PHP image browser - iBrowser 
// ================================================
// iBrowser - SPAW editor interface (IE & Gecko)
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: GPL - see license.txt
// (c)2005 All rights reserved.
// File: editor_plugin.js
// ================================================
// Revision: 1.0                   Date: 08/03/2005
// ================================================

	//-------------------------------------------------------------------------
	// SPAW editor - init iBrowser
	function SPAW_ibrowser_click(editor) {
		ib.isMSIE = (navigator.appName == 'Microsoft Internet Explorer');
		ib.isGecko = navigator.userAgent.indexOf('Gecko') != -1;
		ib.oEditor = document.getElementById(editor + '_rEdit');  // set editor object
		ib.editor = editor;		
		ib.selectedElement = ib.getSelectedElement();
		ib.baseURL = '<?php echo $spaw_dir; ?>/plugins/ibrowser/ibrowser.php'; // plugin URL
		iBrowser_open(); // starting iBrowser
	}
	//-------------------------------------------------------------------------
	// include common interface code
	var js  = document.createElement('script');
	js.type	= 'text/javascript';
	js.src  = '<?php echo $spaw_dir; ?>/plugins/ibrowser/interface/common.js';
	// Add the new object to the HEAD element.
	document.getElementsByTagName('head')[0].appendChild(js) ; 
	//-------------------------------------------------------------------------