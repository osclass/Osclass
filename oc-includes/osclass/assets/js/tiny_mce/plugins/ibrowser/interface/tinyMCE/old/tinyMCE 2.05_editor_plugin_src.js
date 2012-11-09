// ================================================
// PHP image browser - iBrowser 
// ================================================
// iBrowser - tinyMCE editor interface (IE & Gecko)
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// File: editor_plugin.js
// License: GPL - see license.txt
// (c)2005 All rights reserved.
// ================================================
// Revision: 1.0                   Date: 05/03/2006
// ================================================

	/* Import plugin specific language pack */
	tinyMCE.importPluginLanguagePack('ibrowser', 'en,de');
	
	//-------------------------------------------------------------------------
	var TinyMCE_ibrowserPlugin = {
		getInfo: function() {			
			return {
				longname  : 'iBrowser',
				author    : 'net4visions.com',
				authorurl : 'http://net4visions.com',
				infourl   : 'http://net4visions.com',
				version   : '1.3.3'
			};
		},
		
		getControlHTML: function(cn) {
			switch (cn) {
				case 'ibrowser':
					return tinyMCE.getButtonHTML(cn, 'lang_ibrowser_desc', '{$pluginurl}/images/ibrowser.gif', 'mceIbrowser');
			}	
			return '';
		},
		
		execCommand: function(editor_id, element, command, user_interface, value) {
			switch (command) {
				case 'mceIbrowser':
					ib.isMSIE  = (navigator.appName == 'Microsoft Internet Explorer');
					ib.isGecko = navigator.userAgent.indexOf('Gecko') != -1;
					ib.oEditor = tinyMCE.getInstanceById(editor_id);
					ib.editor  = ib.oEditor;
					ib.selectedElement = ib.oEditor.getFocusElement();					
					ib.baseURL = tinyMCE.baseURL + '/plugins/ibrowser/ibrowser.php';	
					
					iBrowser_open(); // starting iBrowser
					return true;
			}
			return false;
		},
		
		handleNodeChange: function(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
			if (node == null)
				return;
	
			do {
				if (node.nodeName == "IMG" && tinyMCE.getAttrib(node, 'class').indexOf('mceItem') == -1) {
					tinyMCE.switchClass(editor_id + '_ibrowser', 'mceButtonSelected');
					return true;
				}
			} while ((node = node.parentNode));
	
			tinyMCE.switchClass(editor_id + '_ibrowser', 'mceButtonNormal');
	
			return true;
		}
	};
	
	//-------------------------------------------------------------------------
	// include common interface code
	var js  = document.createElement('script');
	js.type	= 'text/javascript';
	js.src  = tinyMCE.baseURL + '/plugins/ibrowser/interface/common.js';	
	// Add the new object to the HEAD element.
	document.getElementsByTagName('head')[0].appendChild(js);	
	//-------------------------------------------------------------------------	
	
	tinyMCE.addPlugin('ibrowser', TinyMCE_ibrowserPlugin);