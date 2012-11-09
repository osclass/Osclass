// ================================================
// PHP image browser - iBrowser 
// ================================================
// iBrowser - fckeditor editor interface (IE & Gecko)
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: GPL - see license.txt
// (c)2005 All rights reserved.
// File: fckplugin.js
// ================================================
// Revision: 1.0                   Date: 08/03/2005
// ================================================

	//-------------------------------------------------------------------------
	// FCKeditor editor - open iBrowser
	var FCKibrowser = function(name) {  
		this.Name = name;		
	}  
	// manage the plugins' button behavior  
	FCKibrowser.prototype.GetState = function() {  
		return FCK_TRISTATE_OFF;  
	}  
	 
	FCKCommands.RegisterCommand('ibrowser', new FCKibrowser('ibrowser')) ;  
	 
	// Create the toolbar button. 
	var oibrowserItem = new FCKToolbarButton( 'ibrowser', "ibrowser", null, null, false, true ) ; 
	oibrowserItem.IconPath = FCKConfig.PluginsPath + 'ibrowser/images/ibrowser.gif' ;
	FCKToolbarItems.RegisterItem( 'ibrowser', oibrowserItem ) ;   
	FCKibrowser.prototype.Execute = function() {  
		iBrowser_click(FCK,null)	
	}
	//-------------------------------------------------------------------------
	// fckeditor editor - init iBrowser
	function iBrowser_click(editor) {
		ib.isMSIE = (navigator.appName == 'Microsoft Internet Explorer');
		ib.isGecko = navigator.userAgent.indexOf('Gecko') != -1;		
		//ib.oEditor = document.getElementById('eEditorArea'); // for FCKeditor up to V 2.2
		ib.oEditor = document.getElementById('xEditingArea').firstChild; // for FCKeditor V 2.3
		ib.editor = editor;
		ib.selectedElement = ib.getSelectedElement();
		ib.baseURL = FCKConfig.PluginsPath + 'ibrowser/ibrowser.php';
		iBrowser_open(); // starting iBrowser
	}
	//-------------------------------------------------------------------------
	// include common interface code
	var js  = document.createElement('script');
	js.type	= 'text/javascript';
	js.src  = FCKConfig.PluginsPath + 'ibrowser/interface/common.js';
	// Add the new object to the HEAD element.
	document.getElementsByTagName('head')[0].appendChild(js) ; 
	//-------------------------------------------------------------------------