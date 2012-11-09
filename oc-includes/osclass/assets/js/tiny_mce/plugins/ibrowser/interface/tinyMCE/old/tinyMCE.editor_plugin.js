// ================================================
// PHP image browser - iBrowser 
// ================================================
// iBrowser - tinyMCE editor interface (IE & Gecko)
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: GPL - see license.txt
// (c)2005 All rights reserved.
// File: editor_plugin.js
// ================================================
// Revision: 1.0                   Date: 12/31/2005
// ================================================

	//-------------------------------------------------------------------------
	var ib = null;
	// tinyMCE editor - ibrowser info
	function TinyMCE_ibrowser_getInfo() {
		return {
			longname  : 'iBrowser',
			author    : 'net4visions.com',
			authorurl : 'http://net4visions.com',
			infourl   : 'http://net4visions.com',
			version   : '1.3.9'
		};
	};

	// tinyMCE editor - open iBrowser
	function TinyMCE_ibrowser_getControlHTML(control_name) {
		switch (control_name) {
			case 'ibrowser':
				return '<img id="{$editor_id}_ibrowser" src="{$pluginurl}/images/ibrowser.gif" title="iBrowser" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="(iBrowser_click(\'{$editor_id}\'));">';
			}	
		return '';
	}
	//-------------------------------------------------------------------------
	// tinyMCE editor - init iBrowser
	function iBrowser_click(editor) {
		ib.isMSIE = (navigator.appName == 'Microsoft Internet Explorer');
		ib.isGecko = navigator.userAgent.indexOf('Gecko') != -1;
		ib.oEditor = tinyMCE.getInstanceById(editor);
		ib.editor = editor;		
		ib.selectedElement = ib.getSelectedElement();
		ib.baseURL = tinyMCE.baseURL + '/plugins/ibrowser/ibrowser.php';
		iBrowser_open(); // starting iBrowser
	}
	//-------------------------------------------------------------------------
	// include common interface code
	var js  = document.createElement('script');
	js.type	= 'text/javascript';
	js.src  = tinyMCE.baseURL + '/plugins/ibrowser/interface/common.js';
	// Add the new object to the HEAD element.
	document.getElementsByTagName('head')[0].appendChild(js) ; 
	//-------------------------------------------------------------------------	