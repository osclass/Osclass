// ================================================
// PHP image browser - iBrowser 
// ================================================
// iBrowser - Xinha editor interface (IE & Gecko)
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: GPL - see license.txt
// (c)2005 All rights reserved.
// File: ibrowser.js
// ================================================
// Revision: 1.0                   Date: 08/03/2005
// ================================================

	//-------------------------------------------------------------------------
	// Xinha editor - open iBrowser
	function ibrowser(editor) {
		this.editor = editor;
		var cfg = editor.config;
		var self = this;	
		// register the toolbar buttons provided by this plugin
		cfg.registerButton({
		id       : "ibrowser",
		tooltip  : 'iBrowser',
		image    : _editor_url + '/plugins/ibrowser/images/ibrowser.gif',
		textMode : false,
		action   : function(editor) {
				   iBrowser_click(editor);
			}
		})
		cfg.addToolbarElement("ibrowser", "inserthorizontalrule", 1);
	};
	
	ibrowser._pluginInfo = {
	  name          : "ibrowser",
	  version       : "1.2",
	  developer     : "Marco M. Jaeger",
	  developer_url : "http://net4visions.com/",  
	  license       : "GPL"
	};
	//-------------------------------------------------------------------------
	// Xinha editor - init iBrowser
	function iBrowser_click(editor) {
		ib.isMSIE = (navigator.appName == 'Microsoft Internet Explorer');
		ib.isGecko = navigator.userAgent.indexOf('Gecko') != -1;
		ib.oEditor = editor._iframe;
		ib.editor = editor;		
		ib.selectedElement = ib.getSelectedElement();
		ib.baseURL = _editor_url  + '/plugins/ibrowser/ibrowser.php';
		iBrowser_open(); // starting iBrowser
	}
	//-------------------------------------------------------------------------
	// include common interface code
	var js  = document.createElement('script');
	js.type	= 'text/javascript';
	js.src  = _editor_url  + '/plugins/ibrowser/interface/common.js';
	// Add the new object to the HEAD element.
	document.getElementsByTagName('head')[0].appendChild(js) ; 
	//-------------------------------------------------------------------------