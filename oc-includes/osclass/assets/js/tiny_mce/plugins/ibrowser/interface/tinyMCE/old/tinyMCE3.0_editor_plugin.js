/**
 * $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright  2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.IBrowserPlugin', {
		init : function(ed, url) {

			// load common script
			tinymce.ScriptLoader.load(url + '/interface/common.js');
			
			// Register commands
			ed.addCommand('mceIBrowser', function() {
				var e = ed.selection.getNode();

				// Internal image object like a flash placeholder
				if (ed.dom.getAttrib(e, 'class').indexOf('mceItem') != -1)
					return;

				ib.isMSIE  = tinymce.isIE;
				ib.isGecko = tinymce.isGecko;
				ib.oEditor = ed; 
				ib.editor  = ed;
				ib.selectedElement = e;					
				ib.baseURL = url + '/ibrowser.php';	
				iBrowser_open();
			});

			// Register buttons
			ed.addButton('ibrowser', {
				title : 'iBrowser',
				cmd : 	'mceIBrowser',
				image: 	url + '/interface/images/tinyMCE/ibrowser.gif'
			});
			
			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('ibrowser', n.nodeName == 'IMG');
			});
		},

		getInfo : function() {
			return {
				longname : 	'iBrowser',
				author : 	'net4visions.com',
				authorurl : 'http://net4visions.com',
				infourl : 	'http://net4visions.com',
				version : 	'1.3.9'
			};
		}
	});
	
	// Register plugin
	tinymce.PluginManager.add('ibrowser', tinymce.plugins.IBrowserPlugin);
})();	