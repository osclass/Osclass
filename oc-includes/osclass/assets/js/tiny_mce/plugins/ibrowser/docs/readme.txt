// ================================================
// PHP image browser - iBrowser 
// ================================================
// iBrowser - readme.txt
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: GPL - see readme.txt
// (c)2005 All rights reserved.
// ================================================
// Revision: 1.0                   Date: 10/07/2005
// ================================================

---------------------------------------------------
 - Thank you
---------------------------------------------------

Let me take this opportunity to thank everbody who has contributed
to iBrowser - I could not have realized this project without the 
patient help of James, Alan, Johan(Spoke), and Slava.


---------------------------------------------------
 - Introduction
---------------------------------------------------

iBrowser allows you to manage your image files on your webserver.
You can create directories, upload file, rename and delete them. It also
helps you to change the image properties.


iBrowser currently works with tinyMCE, SPAW, FCKeditor, Xinha, and HTMLarea.

If you're using the random picture function, please see the phpThumb readme for the parameter settings.

If you need more features like image processing, please have a look at the
net4visions.com iManager.
 

---------------------------------------------------
 - Installation
---------------------------------------------------

iBrowser has been confirmed to work with the latest version of
Microsoft Internet Explorer, Safari and Firefox. In case you've a previous version of iBrowser installed,
please delete it first!


1. Prerequisites
---------------------------------------------------
   You will need to compile PHP with the GD library of image functions for iBrowser to work.
   If you use CSS styles for images and/or image caption, please make sure that the used css styles also exist in 
   your site's stylesheet AND the wysiwyg editor content area stylesheet.

   
2. Permission settings
---------------------------------------------------
   Make sure the following directories have writing
   permission (chmod to 0755):

	ibrowser/scripts/phpThumb/cache - should there be any files already, plese delete those!!!
	ibrowser/temp

	all the image libraries you set up in the iBrowser config file!


3. Configuration
---------------------------------------------------
   Check configuration settings
   The configuration of iBrowser is fairly easy - it depends a little
   on what wysiwyg editor you're using

   Setting up image libraries:
   ---------------------------

   You can set up your image libraries in two ways (static or dynamically):

   - static: set your libraries like:
  	$cfg['ilibs'] = array (	
		array (
			'value'   	=> '/dev/im/assets/images/', 				
			'text'    	=> 'Site Pictures',
		),
		array (
			'value'   	=> '/dev/im/assets/gallery/', 				
			'text'    	=> 'Gallery',
		),
	);
	


   - dynamically: set your libraries like:
	uncomment the following line in your config file - the following settings will
	automatically override the static libary settings

	$cfg['ilibs_dir'] 	= array('/dev/pictures/','/dev/images/');
	

	The aforementioned main directories will be scanned for sub-directories and
	all directories found will be listed as directories.


4. WYSIWYG editor interfaces
---------------------------------------------------

   You'll find some predefined files in the ibrowser/interfaces directory.
   As per now, interfaces for tinyMCE, SPAW, FCKeditor, Xinha, and HTMLarea
   are provided.


   tinyMCE interface
   -----------------

   copy the provided interface (editor_plugin_src.js and editor_plugin.js) file from the ibrowser/interface/tinyMCE directory to the ibrowser main directory.

   adding plugin to tinyMCE:

	tinyMCE.init({ 
		... 
		plugins : "ibrowser", 
		theme_advanced_buttons3_add : "ibrowser",
	
	}); 

   For further information on how to use a plugin with tinyMCE be it iBrowser or any other plugin,
   please see the tinyMCE instructions manual!


   FCKeditor interface
   -------------------

   copy the provided interface file: FCKeditor.editor_plugin.js file into your FCKeditor iBrowser plugin
   directory and rename it to "fckplugin.js".

   In the fckconfig.js file, add 'ibrowser' to the FCKConfig.ToolbarSets. Register the iBrowser plugin with
   the following statement: FCKConfig.Plugins.Add( 'ibrowser') ; 

   For further information on how to use a plugin with FCKeditor, be it iBrowser or any other plugin,
   please see the FCKeditor instructions manual!


   Xinha interface
   -------------------

   copy the provided interface file: xinha.editor_plugin.js file into your Xinha iBrowser plugin
   directory and rename it to "ibrowser.js".

   add iBrowser to the following array: xinha_plugins = xinha_plugins ? xinha_plugins :
      [
       'CharacterMap',
       'ContextMenu',       
       'ListType',       
       'Stylist',      
       'TableOperations',
       'ibrowser'
      ];


   For further information on how to use a plugin with Xinha, be it iBrowser or any other plugin,
   please see the Xinha instructions manual!

   HTMLarea interface
   -------------------

   copy the provided interface file: HTMLarea.editor_plugin.js file into your HTMLarea iBrowser plugin
   directory and rename it to "ibrowser.js".

   load the iBrowser plugin as follows:
   	HTMLArea.loadPlugin("ibrowser");

   register the iBrowser plugin as follows:
	editor.registerPlugin(ibrowser);


   For further information on how to use a plugin with HTMLarea, be it iBrowser or any other plugin,
   please see the HTMLarea instructions manual!


   SPAW interface
   --------------

   unfortunately, the plugin integration into SPAW isn't as easy as with tinyMCE or other editors. However, if you follow the next
   steps, it shouldn't be a problem to get iBrowser to work with SPAW either.

	1. in the spaw directory, create a directory called "plugins" with a sub-directory called "ibrowser".
	   unzip all the ibrowser files into the "ibrowser" directory
	
	2. edit the following two files in the spaw/class directory and add the iBrowser include just before the
           SPAW_showColorPicker(editor,curcolor) line:

		IE: scripts.js.php
			<?php include $spaw_root . 'plugins/ibrowser/interface/SPAW.editor_plugin.js'; ?>

		Firefox: scripts+gecko.js.php		
			<?php include $spaw_root . 'plugins/ibrowser/interface/SPAW.editor_plugin.js'; ?>


	3. edit the following two file in the spaw/lib/toolbars/default directory
	   (if you don't use the default toolbar, use the one you use)
		- default_toolbar_data_inc.php
		- default_toolbar_data.gecko.inc.php

		array(
              		'name' => 'ibrowser',
              		'type' => SPAW_TBI_BUTTON
            	),

		if you like to not longer use the regular SPAW image function, just comment those lines.

	4. copy the four button images in the ibrowser/interface/images/spaw directory into the spaw/libs/themes/img directory

	5. in the spaw/lib/lang/en directory, edit the "en_lang_data.inc.php" file and add the following:

		'ibrowser' => array(
  		   'title' => 'iBrowser'
  		),

		This will create the title for the toolbar image button. 	

  

		
