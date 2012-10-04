<?php
	// ================================================
	// PHP image browser - iBrowser 
	// ================================================
	// iBrowser dialog
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: GPL - see license.txt
	// (c)2005 All rights reserved.
	// File: ibrowser.php
	// ================================================
	// Revision: 1.3.1                 Date: 10/09/2009
	// ================================================
	
	//-------------------------------------------------------------------------
	// unset $cfg['ilibs_incl'] - dynamic image library
	if (isset($cfg['ilibs_inc'])) {
		$cfg['ilibs_inc'] = '';
	}
	//-------------------------------------------------------------------------
	// include configuration settings
	include dirname(__FILE__) . '/config/config.inc.php';
	include dirname(__FILE__) . '/langs/lang.class.php';
	//-------------------------------------------------------------------------
	// language settings	
	$l = (isset($_REQUEST['lang']) ? new PLUG_Lang($_REQUEST['lang']) : new PLUG_Lang($cfg['lang']));
	$l->setBlock('ibrowser');	
	//-------------------------------------------------------------------------
	// if set, include file specified in $cfg['ilibs_incl']; hardcoded libraries will be ignored!	
	if (!empty($cfg['ilibs_inc'])) {
		include $cfg['ilibs_inc'];
	}	
	//-------------------------------------------------------------------------		
	// set current image library	
	$clib = (isset($_REQUEST['clib']) ? $_REQUEST['clib'] : '');
	//-------------------------------------------------------------------------	
	$value_found = false;
	// callback function for preventing listing of non-library directory
	function is_array_value($value, $key, $tlib) {
		global $value_found;
		if (is_array($value)) {
			array_walk($value, 'is_array_value', $tlib);
		}
		if ($value == $tlib) {
			$value_found = true;
		}
	}	
	//-------------------------------------------------------------------------	
	array_walk($cfg['ilibs'], 'is_array_value', $clib);	
	if (!$value_found || empty($clib)) {
		$clib = $cfg['ilibs'][0]['value'];
	}		
	//-------------------------------------------------------------------------
	// create library dropdown
	$lib_options = liboptions($cfg['ilibs'], '', $clib,'');
?>
<!-- do not delete this line - it's need for proper working of the resizeDialogToContent() function -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<head>
<title><?php echo $l->m('im_002'); ?></title>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $l->getCharset(); ?>">
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
<style type="text/css">
<!--
	@import url("css/style.css");
-->
</style>
<script language="javascript" type="text/javascript" src="scripts/resizeDialog.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/validateForm.js"></script>
<script language="JavaScript" type="text/JavaScript">
<!--
// ============================================================
// = global instance V 1.0, date: 04/07/2005                  =
// ============================================================
	function iBrowser() {
		// browser check
		this.isMSIE  = (navigator.appName == 'Microsoft Internet Explorer');
		this.isGecko = navigator.userAgent.indexOf('Gecko') != -1;		
	};
	var iBrowser = new iBrowser();

// ============================================================
// = iBrowser init V 1.0, date: 12/03/2004                    =
// ============================================================
	function init() {			
		var formObj = document.forms[0];		
		btnInit();	 // init menu buttons		
		document.getElementById('mainDivHeader').innerHTML = setTitle('imDiv'); 		
		//-------------------------------------------------------------------------			
		// hide library selection if there is only one library available!
		if (formObj.ilibs.options.length > 1) {
			changeClass(0,'ilibsDiv','showit');
		}
		//-------------------------------------------------------------------------
		// window arguments			
		var args = window.dialogArguments;		
		if (args) {										// if dialog argument are available
			if (args.src) { 							// source is image and maybe also link				
				initImageArgs(); 						// init and set image attributes					
			} else if (args.a) { 						// source is popup image only
				setImagePopup(args.popSrc);				// update popup preview				
				formObj.popSrc.value = args.popSrc;		// popup image url
				formObj.popTitle.value = args.popTitle;	// link title
				for (var i = 0; i < formObj.popClassName.options.length; i++) {	// CLASS value
					if (formObj.popClassName.options[i].value == args.popClassName) {
						formObj.popClassName.options.selectedIndex = i;				
					}
				}
				if (args.rsrc) { 						// random image with popup link
					setRandom(0);
				}		
			} else if (args.rsrc) { 					// random image
				setRandom(0);
			}
		}
		
		// adjust size of upload field for gecko
		if (iBrowser.isGecko) {
			var fldFile = document.getElementById('nfile[]');
			fldFile.setAttribute('size', 45);
		}
				 
		//-------------------------------------------------------------------------
		preloadImages('images/firefox.gif','images/explorer.gif','images/img_in.gif','images/img_at.gif','images/img_po.gif','images/help.gif','images/help_off.gif','images/about.gif','images/about_off.gif','images/im.gif','images/dir_off.gif','images/dir.gif','images/prev_off.gif','images/prev.gif','images/symbols_off.gif','images/symbols.gif','images/alert_off.gif','images/alert.gif','images/dirview_off.gif','images/dirview.gif'); // preload images				
		btnStage();
		resizeDialogToContent();		
		window.focus();		
	}
// ============================================================
// = image buttons init V 1.0, date: 05/27/2005               =
// ============================================================
	function btnInit() {		
		var x = document.getElementById('menuBarDiv').getElementsByTagName('li');
		for (var i = 0; i < x.length; i++) {
			if (x[i].className   == 'btnUp') {
				x[i].onmouseover = btn_over;
				x[i].onmouseout  = btn_out;
				x[i].onmousedown = btn_down;
				x[i].onclick     = btn_click;
			}
		}		
	}
// ============================================================
// = menu buttons V 1.0, date: 06/03/2005                     =
// ============================================================	
	function btn_over() {	// menu button hover
		if (this.className != 'btnDown') {
			this.className  = 'btnOver';
		}
	}	
	function btn_out() {	// menu button out
		if (this.className != 'btnDown') {
			this.className  = 'btnUp';
		}
	}
	function btn_down() {	// menu button down
		if (this.className != 'btnDown') {
			this.className  = 'btnDown';
		}
	}
	function btn_click() {	// menu button click		
		var formObj = document.forms[0];
		var args = btn_click.arguments;
		if(document.getElementById(args[0]) != null) { 				
			this.id = document.getElementById(args[0]).id;				
		}
		var x = document.getElementById('menuBarDiv').getElementsByTagName('li');
		for (var i = 0; i < x.length; i++) {
			if (x[i].className == 'btnDown') {				
				if (x[i].id != this.id) {					
					x[i].className = 'btnUp';
				}
			}
		}	
				
		// check whether image has been selected or not
		if (this.id == 'mbtn_at') { // properties functions
			if(!btnStage()) {
				var msg = escapeHTML('<?php echo $l->m('er_001') . ': ' . $l->m('er_002'); ?>');
				alert(msg);
				this.className = 'btnUp';
				return;
			}
		}
		
		// reset all classes to "hideit"
		changeClass(0,'imDiv','hideit','inDiv','hideit','atDiv','hideit','hideit','raDiv','hideit');		
		// get element, set title			
		elm = this.id.substring(this.id.length-2, this.id.length);			
		elm = elm + 'Div';			
		document.getElementById('mainDivHeader').innerHTML = setTitle(elm);
			
		if (this.id == 'mbtn_po') {			
			var iProps = window.dialogArguments;			
			if (iProps && iProps.a) { // show remove link only if link			 
				changeClass(1,'fileDivWrap','hideit','fileDiv','hideit','img_ren','hideit','img_del','hideit','inDiv','showit','poDiv','showit','poDelDiv','showit','raDiv','hideit');
			} else {
				changeClass(1,'fileDivWrap','hideit','fileDiv','hideit','img_ren','hideit','img_del','hideit','inDiv','showit','poDiv','showit','poDelDiv','hideit','raDiv','hideit');
			}
		} else {						
			<?php if (($cfg['create'] && isset($cfg['ilibs_inc'])) || $cfg['upload'] || $cfg['rename'] || $cfg['delete']) { ?>
				changeClass(1,'poDiv','hideit','fileDivWrap','showit','img_ren','showit','img_del','showit',elm,'showit','raDiv','showit');
			<?php } else { ?>
				changeClass(1,'poDiv','hideit','fileDivWrap','hideit','img_ren','showit','img_del','showit',elm,'showit','raDiv','showit');
			<?php }; ?>
		}
	}
// ============================================================
// = set title - V 1.0, date: 06/03/2005                      =
// ============================================================
	function setTitle(elm) {
		var retstr;
		switch(elm) {
			case 'imDiv':
				retstr = '<?php echo $l->m('im_004'); ?>';
				break;		
			case 'inDiv':
				retstr = '<?php echo $l->m('im_008'); ?>';
				break;
			case 'atDiv':
				retstr = '<?php echo $l->m('im_010'); ?>';
				break;
			case 'poDiv':
				retstr = '<?php echo $l->m('im_014'); ?>';
				break;
			default:
				retstr = '<?php echo $l->m('im_016'); ?>'; 		
		}
		return retstr;	
	}
// ============================================================
// = get image path and update ilist V 1.0, date: 04/25/2005  =
// ============================================================
	function initImageArgs() {
		var formObj = document.forms[0];		
		var args = window.dialogArguments;
		
		// in case of full url, remove 'http://
		var pos = args.src.indexOf('://');
		if (pos != -1) {			
   			pos = args.src.indexOf('/', pos + 3 ); // + length of '://'   			
			args.src = args.src.substring(pos);			
		}	
		
		// set current image file, and library
		var pos   = args.src.lastIndexOf('/');
		var cfile = args.src.slice(pos+1,args.src.length);		
		var clib  = absPath(args.src.slice(0,pos+1)); // relative path to library		
		// set current directory/library & update image list
		for (var i = 0; i < formObj.ilibs.options.length; i++) {
			if (formObj.ilibs.options[i].value == clib) {
				formObj.ilibs.options.selectedIndex = i;	
				formObj.param.value = 'update' + '|' + cfile;			
				formObj.submit();						
			}
		}		
	}
// ============================================================
// = set image properties V 1.0, date: 04/25/2005             =
// ============================================================		
	function setImageArgs() {
		var formObj = document.forms[0];		
		var args = window.dialogArguments;					
		
		if (args.tsrc) { 											// dynamic thumbnail
			formObj.pr_src.value = args.tsrc;
			changeClass(0,'alertImg','showit');						// show warning		
		}
		if (args.rset) {
			formObj.pr_src.value = args.rsrc;
		}
		
		formObj.pr_width.value 	= args.width  ? args.width  : '';	// WIDTH value		
		formObj.pr_height.value	= args.height ? args.height : '';	// HEIGHT value
		formObj.pr_alt.value 	= args.alt;							// ALT text		
		formObj.pr_title.value 	= args.title;						// DESCR text
		formObj.pr_border.value = args.border ? args.border : '';	// BORDER value	
		formObj.pr_vspace.value = args.vspace ? args.vspace : '';	// VSPACE value				
		formObj.pr_hspace.value = args.hspace ? args.hspace : '';	// HSPACE value
		
		if (args.caption == 1) { // if image caption
			formObj.pr_chkCaption.checked = true;
			for (var i = 0; i < formObj.pr_captionClass.options.length; i++) {	// CLASS value
				if (formObj.pr_captionClass.options[i].value == args.captionClass) {
					formObj.pr_captionClass.options.selectedIndex = i;				
				}
			}
		}
		
		for (var i = 0; i < formObj.pr_align.options.length; i++) {	// ALIGN value 
			if (formObj.pr_align.options[i].value == args.align) {
				formObj.pr_align.options.selectedIndex = i;				
			}
		}
		
		for (var i = 0; i < formObj.pr_class.options.length; i++) {	// CLASS value
			if (formObj.pr_class.options[i].value == args.className) {
				formObj.pr_class.options.selectedIndex = i;				
			}
		}
		// set popup preview in case it's a popup
		if (args.popSrc) {					
			setImagePopup(args.popSrc);				// update popup preview			
			formObj.popTitle.value = args.popTitle;	// link title
			for (var i = 0; i < formObj.popClassName.options.length; i++) {	// CLASS value
				if (formObj.popClassName.options[i].value == args.popClassName) {
					formObj.popClassName.options.selectedIndex = i;				
				}
			}		
		}
		formObj.param.value = ''; // resetting param value		
	}
// ============================================================
// = set popup image src preview V 1.0, date: 05/13/2005      =
// ============================================================	
	function setImagePopup(popSrc) {		
		var formObj = document.forms[0];
		var src = '<?php echo $cfg['scripts']; ?>' + 'phpThumb/phpThumb.php'; // command			
		src     = src + '?src=' + popSrc; 					// popup source image				
		src     = src + '&w=80'; 							// image width
		src     = src + '&h=60'; 							// image height
		src     = src + '&zc=1'; 							// zoom crop			
		document.getElementById('poPrevFrame').src = src; 	// update preview	
		formObj.popSrc.value = popSrc;
	}
// ============================================================
// = insertImage, date: 08/03/2005                            =
// ============================================================
	function insertImage() {
		var formObj = document.forms[0];
		var args = {};
		// get active menu button
		var x = document.getElementById('menuBarDiv').getElementsByTagName('li');
		for (var i = 0; i < x.length; i++) {
			if (x[i].className == 'btnDown') {
				if (x[i].id == 'mbtn_po') { // popup mode
					if(formObj.chkP.checked) {								
						args.action    = 2; // delete popup link
					} else { // create / edit link to popup image
						args.action    = 1; 
						args.popUrl    = '<?php echo $cfg['pop_url']; ?>'; // link to popup.php						
						args.popSrc    = (formObj.popSrc.value)   ? (formObj.popSrc.value)   : '';						
						args.popTitle  = (formObj.popTitle.value) ? (formObj.popTitle.value) : '';
						args.popTxt    = '<?php echo $l->m('in_036'); ?>';
						if (formObj.popClassName.selectedIndex > 0) { // if class style is selected
							args.popClassName = (formObj.popClassName.options[formObj.popClassName.selectedIndex].value) ? (formObj.popClassName.options[formObj.popClassName.selectedIndex].value) : '';
						}
						// caption parameters
						args.caption      = formObj.pr_chkCaption.checked ? formObj.pr_chkCaption.value : '';
						args.captionClass = (formObj.pr_captionClass.options[formObj.pr_captionClass.selectedIndex].value) ? (formObj.pr_captionClass.options[formObj.pr_captionClass.selectedIndex].value) : '';
					}							
				}
			}
		}
		//-------------------------------------------------------------------------
		// check if valid image is selected		
		if (!args.action) { // if not popup	mode, check whether there is a valid image selected		
			if (formObj.pr_src.value == '') { // no valid picture has been selected				
				var msg = escapeHTML('<?php echo $l->m('er_001') . ': ' . $l->m('er_002'); ?>');
				alert(msg);
				return;
			}
			
			args.src = (formObj.pr_src.value) ? (formObj.pr_src.value) : '';									
			if ('<?php echo $cfg['furl']; ?>' == true) { // create full url incl. e.g. http://localhost....
				args.src = '<?php echo $cfg['base_url']; ?>' + args.src;				
			}
						
			args.width  = (formObj.pr_width.value)  ? (formObj.pr_width.value)  : '';
			args.height = (formObj.pr_height.value) ? (formObj.pr_height.value) : '';				
			args.align 	= (formObj.pr_align.value)  ? (formObj.pr_align.value)  : '';
			args.border = (formObj.pr_border.value) ? (formObj.pr_border.value) : '';				
			args.alt 	= (formObj.pr_alt.value)    ? (formObj.pr_alt.value)    : '';
			args.title 	= (formObj.pr_title.value)  ? (formObj.pr_title.value)  : '';
			args.hspace = (formObj.pr_hspace.value) ? (formObj.pr_hspace.value) : '';
			args.vspace = (formObj.pr_vspace.value) ? (formObj.pr_vspace.value) : ''; 
			if (formObj.pr_class.selectedIndex > 0) { // if class style is selected
				args.className = (formObj.pr_class.options[formObj.pr_class.selectedIndex].value) ? (formObj.pr_class.options[formObj.pr_class.selectedIndex].value) : '';
			}
			// caption parameters
			args.caption = formObj.pr_chkCaption.checked ? formObj.pr_chkCaption.value : '';
			args.captionClass = (formObj.pr_captionClass.options[formObj.pr_captionClass.selectedIndex].value) ? (formObj.pr_captionClass.options[formObj.pr_captionClass.selectedIndex].value) : '';
		} else { // check whether there is valid popup image
			if (formObj.popSrc.value == '') { // no valid picture has been selected				
				var msg = escapeHTML('<?php echo $l->m('er_001') . ': ' . $l->m('er_002'); ?>');
				alert(msg);
				return;
			}
		}		
							
		//-------------------------------------------------------------------------	
		// save image to wysiwyg editor and close window		
		window.returnValue = args;
		window.close();				
		
		if (iBrowser.isGecko) { // Gecko				
			<?php					
				if (!empty($_REQUEST['callback'])) {          				
					echo "opener." . @$_REQUEST['callback'] . "('" . @$_REQUEST['editor'] . "',this);\n";
				};
			?>	
		}	
	}
// ============================================================
// = image change - set attributes V 1.0, date: 12/03/2004    =
// ============================================================
	function imageChange() {		
		var formObj = document.forms[0];
		var args 	= imageChange.arguments;  												// image change arguments - set by rfiles.php						
		var clib    = absPath(formObj.ilibs.options[formObj.ilibs.selectedIndex].value);	// current library - absolute path		
		var cfile   = document.getElementById('cimg').attributes['cfile'].value;			// get current image
		var cwidth  = document.getElementById('cimg').attributes['cwidth'].value;			// get current width	
		var cheight = document.getElementById('cimg').attributes['cheight'].value;			// get current height		
		var csize   = document.getElementById('cimg').attributes['csize'].value.split('|');	// get current size (array)
		var ctype   = document.getElementById('cimg').attributes['ctype'].value.split('|');	// get current type (array)	
			
		//-------------------------------------------------------------------------
		// set default image attributes
		formObj.pr_src.value    = clib + cfile;		
		formObj.pr_width.value  = cwidth;
		formObj.pr_height.value = cheight;		
		formObj.pr_size.value   = csize[0];		
		formObj.pr_align.options.selectedIndex = 0;
		formObj.pr_class.options.selectedIndex = 0;
		document.getElementById('pr_sizeUnit').innerHTML = csize[1]; // e.g. kb		
		formObj.pr_alt.value 	= cfile.substr(0, cfile.length-4);
		formObj.pr_title.value 	= cfile.substr(0, cfile.length-4);		
		changeClass(0,'alertImg','hideit');
		//-------------------------------------------------------------------------
		// update preview window	
		var sizes = resizePreview(cwidth, cheight, 150, 150);		
		var src = '<?php echo $cfg['scripts']; ?>' + 'phpThumb/phpThumb.php'; // command
		src = src + '?src=' + clib + cfile; // source file
		src = src + '&w=' + sizes['w']; // width		
		document.getElementById('inPrevFrame').src = src; // update regular preview
		
		//-------------------------------------------------------------------------
		// reset rename and delete info
		if ('<?php echo $cfg['rename']; ?>' == true) {
			formObj.in_srcnew.value  = cfile.substr(0, cfile.length-4); // default rename value			
		}
		if ('<?php echo $cfg['delete']; ?>' == true) {
			formObj.in_delinfo.value = cfile; 							// default delete value
		}
		
		//-------------------------------------------------------------------------
		// change image attributes in case it's an existing image		
		if (args[0] == 'update') { 	// if argument from rfiles.php received				
			setImageArgs(); 		// update image attributes
		} else if (args[0] == 'delete') { // image was deleted
			document.getElementById('cimg').attributes['cfile'].value = '';			
			document.getElementById('in_srcnew').value  = '';
			document.getElementById('in_delinfo').value = '';			
			document.getElementById('inPrevFrame').src = 'images/noImg.gif'; // update preview
		}
			
		//-------------------------------------------------------------------------
		// update popup preview and set popup default attributes
		if (document.getElementById('mbtn_po').className == 'btnDown') {
			var popSrc = clib + cfile; 
			setImagePopup(popSrc);			
			formObj.popTitle.value = cfile.substr(0, cfile.length-4);			
		}
		//-------------------------------------------------------------------------
		// random image
		formObj.chkRandom.checked = false; // uncheck random on image change
		changeClass(1,'raParamDiv','hideit');
		//-------------------------------------------------------------------------
		updateStyle();		
		btnStage();	
	}
// ============================================================
// = update style frame V 1.0, date: 12/13/2004               =
// ============================================================	
	function updateStyle() {
		var formObj = document.forms[0];			
		document.getElementById('atPrevImg').align 	 	= formObj.pr_align.options[formObj.pr_align.selectedIndex].value;			
		document.getElementById('atPrevImg').vspace 	= formObj.pr_vspace.value;
		document.getElementById('atPrevImg').hspace 	= formObj.pr_hspace.value;
		document.getElementById('atPrevImg').border 	= formObj.pr_border.value;
		document.getElementById('atPrevImg').alt 		= formObj.pr_alt.value;
		document.getElementById('atPrevImg').title 	 	= formObj.pr_title.value;
		document.getElementById('atPrevImg').className 	= formObj.pr_class.options[formObj.pr_class.selectedIndex].value;	
	}
// ============================================================
// = enable/disable menu buttons, date: 03/21/2005            =
// ============================================================ 
	function btnStage() {
		var formObj = document.forms[0];					
		var cfile   = document.getElementById('cimg').attributes['cfile'].value; // current image	
		if (cfile  == '') {			
			formObj.img_at.src = 'images/img_at_off.gif';			
			if (formObj.img_cr) {
				formObj.img_cr.src = 'images/img_cr_off.gif';	
			}		
			return false;
		}
		formObj.img_at.src = 'images/img_at.gif';
		if (formObj.img_cr) {
			formObj.img_cr.src = 'images/img_cr.gif';
		}
		return true;		
	}
// ============================================================
// = resize image to fit preview V 1.0, date: 12/19/2004      =
// ============================================================	
	function resizePreview(w,h,mw,mh) { // width, height, max width, max height				
		var sizes = new Array();		
		if (w > mw || h > mh) { // thumbnailing required
			f = w / h; // proportions of image: (f > 1) = landscape; (f < 1) = portrait; (f = 1) = square			
			if (f > 1) { // landscape and square
				w = mw;
				h = Math.round(w / f);			
			} else if (f <= 1) {	// portrait
				h = mh;				
				w = Math.round(h * f);			
			}	
		}				
		sizes['w'] = w;
		sizes['h'] = h;
		return sizes;
	}
// ============================================================
// = insert special characters V 1.0, date: 03/31/2005        =
// ============================================================		
	function selSymbol(elm) {				
		var wArgs = {};
		wArgs.iBrowser = iBrowser;
		wArgs.elm = elm;	// passing calling element to function
		if ((iBrowser.isMSIE)) { 
			var rArgs = showModalDialog('<?php echo $cfg['scripts']; ?>symbols.php?lang=<?php echo $l->lang; ?>', wArgs, 
			'dialogHeight:300px; dialogWidth:400px; scrollbars: no; menubar: no; toolbar: no; resizable: no; status: no;');													
			if (rArgs) {				
				setSymbol(null, null, rArgs);
			}			
		} else if (iBrowser.isGecko) {
			var wnd = window.open('<?php echo $cfg['scripts']; ?>symbols.php?lang=<?php echo $l->lang; ?>&callback=setSymbol', 'symbols', 'status=no, modal=yes, width=400, height=300');				
			wnd.dialogArguments = wArgs;
		}		
	}
	// set symbol callback
	function setSymbol(editor, sender, rArgs) {		
		if (!rArgs) { // Gecko		
			var rArgs = sender.returnValue;				
		}
		if (rArgs.chr != null) {
			var chr = rArgs.chr;
			var elm = rArgs.elm;				
			chr = String.fromCharCode(chr.substring(2, chr.length -1)); // e.g. returns &#220;		
			document.getElementById(elm).value = document.getElementById(elm).value + ' ' + chr;
		}			
  }		 
// ============================================================
// = preload Images, date: 11/13/2004                         =
// ============================================================		
	function preloadImages() {
  		var d=document;
		if(d.images) {
			if(!d.MM_p)
				d.MM_p = new Array();
    			var i,j=d.MM_p.length,a = preloadImages.arguments;
				for(i= 0; i < a.length; i++)
    				if (a[i].indexOf("#") != 0) {
						d.MM_p[j] = new Image;
						d.MM_p[j++].src = a[i];
			}
		}
	}
// ============================================================
// = change image library V 1.0, date: 04/22/2005             =
// ============================================================
	function ilibsClick() {		
		var formObj = document.forms[0];		
		formObj.param.value = ''; // clear param values;		
		formObj.submit();	
		// reset values 
		document.getElementById('inPrevFrame').src = 'images/noImg.gif'; // update preview
		document.getElementById('cimg').attributes['cfile'].value = '';
		btnStage();			
	}
// ============================================================
// = upload image, date: 05/24/2005                           =
// ============================================================
	function uploadClick() {
		var formObj = document.forms[0];		
		if (!checkUpload()) {
			var msg = escapeHTML('<?php echo $l->m('er_001') . ': ' . $l->m('er_023'); ?>');
			alert(msg);
			return;
		}
		if (confirm('<?php echo $l->m('er_021'); ?>')) {			
			formObj.param.value = 'upload'; // parameter: <action>			
			showloadmessage();
			formObj.submit();						
		}
	}
	// check whether image file is selected for uploading
	function checkUpload() {		
		var formObj = document.forms[0];	
		var upload = false;
		var x = document.getElementById('fiUplDiv').getElementsByTagName('input');
		for (var i = 0; i < x.length; i++) {
			if (x[i].type == 'file') {
				if (x[i].value != '') { // check whether files has been selected for upload					
					
					for (z=0; document.getElementById('chkThumbSize['+ z +']'); z++) {						
						if(document.getElementById('chkThumbSize['+ z +']').checked) {
							upload = true;							
						}						
					}
				}
			}			
		}
		return upload;	
	}
// ============================================================
// = delete image V 1.0, date: 04/22/2005                     =
// ============================================================
	function deleteClick() {
		var formObj = document.forms[0];		
		var cfile = document.getElementById('cimg').attributes['cfile'].value;
		if (cfile == '') { // check if image is selected	
			var msg = escapeHTML('<?php echo $l->m('er_001') . ': ' . $l->m('er_002'); ?>');
			alert(msg);
			return;
		}
				
		if (confirm('<?php echo $l->m('er_008'); ?> ' + cfile + '!')) {				
			formObj.param.value = 'delete' + '|' + cfile; // parameter: <action>|<file>				
			formObj.submit();	
		}	  	
	} 
// ============================================================
// = rename image V 1.0, date: 04/22/2005                     =
// ============================================================
	function renameClick() {
		var formObj = document.forms[0];
		var clib =  formObj.ilibs.options[formObj.ilibs.selectedIndex].value; // current library
		var cfile = document.getElementById('cimg').attributes['cfile'].value;		
		var ctype = document.getElementById('cimg').attributes['ctype'].value.split('|');		
				
		if (cfile == '') { // check if image is selected
			var msg = escapeHTML('<?php echo $l->m('er_001') . ': ' . $l->m('er_002'); ?>');
			alert(msg);
			return;
		}		
		
		var ctype = '.' + imageType(ctype[0]);		
		if (formObj.in_srcnew.value == '' || formObj.in_srcnew.value + ctype == cfile) { // new name is either empty or hasn't changed
			var msg = escapeHTML('<?php echo $l->m('er_011'); ?>');
			alert(msg);
			return;
		}
				
		if (confirm('<?php echo $l->m('er_010'); ?>: ' + formObj.in_srcnew.value + ctype)) { // do rename					
			var nfile = formObj.in_srcnew.value + ctype;				 
			formObj.param.value = 'rename' + '|' + cfile + '|' + nfile; // parameter: <action>|<filename>|<newname>		
			formObj.submit();				
		}		  	
	}
// ============================================================
// = switch list view V 1.0, date: 07/06/2005                 =
// ============================================================
	function switchList() {
		var formObj = document.forms[0];			
		if (formObj.flist.value == 1) { // check if image is selected	
			formObj.flist.value = 0;
		} else {
			formObj.flist.value = 1;
		}		
		// refresh list view		
		var cfile = document.getElementById('cimg').attributes['cfile'].value;
		if (cfile.length > 0) {
			formObj.param.value = 'switch' + '|' + cfile;	
		}
		formObj.submit();	
	} 
// ============================================================
// = create directory V 1.0, date: 04/22/2005                 =
// ============================================================
	function createClick() {
		var formObj = document.forms[0];
		var clib    = formObj.ilibs.options[formObj.ilibs.selectedIndex].value; 		// current library
		
		if (clib == '') { // check if library is selected
			var msg = escapeHTML('<?php echo $l->m('er_001') . ': ' . $l->m('er_002'); ?>');
			alert(msg);
			return;
		}			
		if (formObj.in_dirnew.value == '') { // check if user has entered a new directory name
			var msg = escapeHTML('<?php echo $l->m('er_011'); ?>');
			alert(msg);
			return;
		}
				
		if (confirm('<?php echo $l->m('in_026'); ?>: ' + clib + formObj.in_dirnew.value)) {					
			var nfile = formObj.in_dirnew.value;						 
			formObj.param.value = 'create' + '|' + nfile; // parameter: <action>|<newdir>		
			formObj.submit();				
		}				
	}
// ============================================================
// = set random image, date: 07/17/2005                       =
// ============================================================
	function setRandom() {
		var formObj = document.forms[0];
		var action = setRandom.arguments;
		if (action[0] == 0) { // set arguments on init
			var args = window.dialogArguments;	
			var formObj = document.forms[0];
			
			for (var i = 0; i < formObj.ilibs.options.length; i++) { // random library
				var pos = args.rlib.indexOf(formObj.ilibs.options[i].value);
				if (pos != -1) {
					formObj.ilibs.options.selectedIndex = i;	
				}
			}
			
			ilibsClick();
			formObj.chkRandom.checked = true;
			formObj.randomParam.value = args.rset;
			var src = 'images/randomImg.gif';
			document.getElementById('inPrevFrame').src = src;
			document.getElementById('cimg').attributes['cfile'].value = src;
			changeClass(1,'raParamDiv','showit');
			setImageArgs();
			btnStage();
		} else if (action[0] == 1) {
			if (formObj.chkRandom.checked) {
				document.getElementById('inPrevFrame').src = 'images/randomImg.gif'; // update preview
				var clib= formObj.ilibs.options[formObj.ilibs.selectedIndex].value; 
				var src = '<?php echo $cfg['scripts']; ?>' + 'random.php'; // command					
				src     = src + unescape('?dir=<?php echo str_replace('\\','/', $cfg['root_dir']); ?>' + clib);
				src     = src + formObj.randomParam.value;				
				formObj.pr_src.value    = src;
				formObj.pr_alt.value    = '<?php echo $l->m('in_053'); ?>';
				formObj.pr_title.value  = '<?php echo $l->m('in_053'); ?>';
				formObj.pr_width.value  = '';
				formObj.pr_height.value = '';
				changeClass(1,'raParamDiv','showit');
				document.getElementById('cimg').attributes['cfile'].value = src;				
				btnStage();
				formObj.param.value = '';
				formObj.submit();	
			} else {
				document.getElementById('inPrevFrame').src = 'images/noImg.gif'; // update preview
				formObj.param.value = '';
				formObj.pr_src.value = '';
				changeClass('raParamDiv','hideit');
				document.getElementById('cimg').attributes['cfile'].value = '';
				btnStage();
				formObj.submit();		
			}
		} else if (action[0] == 2) { // resetting parameter values to config value
			formObj.randomParam.value = '<?php echo $cfg['random']; ?>';
		}
	}

// ============================================================
// = full size preview V 1.0, date: 12/18/2004                =
// ============================================================	
	function fullSizeView() {
		var formObj = document.forms[0];		
		var clib    = formObj.ilibs.options[formObj.ilibs.selectedIndex].value; 	// current library
		var cfile   = document.getElementById('cimg').attributes['cfile'].value; 	// current image			
		var cwidth  = document.getElementById('cimg').attributes['cwidth'].value;	// current width
		var cheight = document.getElementById('cimg').attributes['cheight'].value;	// current height
		if (cfile != '') {	
			var sizes;		
			sizes = resizePreview(cwidth,cheight,512,512);			
			if (sizes['w'] > 150 || sizes['h'] > 150) { // open external window if size &gt; 150 which is the size of the preview window			
			} else {
				var msg = escapeHTML('<?php echo $l->m('er_001') . ': ' . $l->m('er_032'); ?>');
				alert(msg);
				return;
			}
			var src;						
			src = '<?php echo $cfg['scripts']; ?>' + 'phpThumb/phpThumb.php'; // command							
			src = src + '?src=' + absPath(clib) + cfile; // source image
			src = src + '&w='+sizes['w']; //image width						
		
			var windowName = 'fullView';							
			var features =
			'width='        + sizes['w'] +
			',height='      + sizes['h'] +        		
			',top='         + '10'  +
			',left='        + '10'  +
			',location='    + 'no'  +
			',menubar='     + 'no'  +
			',scrollbars='  + 'no'  +
			',status='      + 'no'  +
			',toolbar='     + 'no'  +
			',resizable='   + 'no';			
			// open full view popup window
			window.open('<?php echo $cfg['pop_url']; ?>?url=' + escape(src) + '&clTxt=' + '<?php echo $l->m('in_036'); ?>', windowName, features);				
		}
	}
// ============================================================
// = change class, date: 12/01/2004                           =
// ============================================================
	function changeClass() { 		
		var args = changeClass.arguments; 		
		if (args[0] == 0 || args[0] == 1) { // 0 = no resizeDialogToContent; 1 = resizeDialogToContent
			var start = 1;
		} else {
			var start = 0;
		}
		
		for(var i = start; i < args.length; i += 2) {
			if(document.getElementById(args[i]) != null) {				
				document.getElementById(args[i]).className = args[i+1];
			}
		}
		// resize dialog to content
		if (args[0] == 1) {					
			resizeDialogToContent();
		}		
	}	
// ============================================================
// = image dimension change, date: 05/08/2005                 =
// ============================================================		
	function changeDim(sel) {		
		var formObj = document.forms[0];
		var cwidth  = document.getElementById('cimg').attributes['cwidth'].value;			// get current width	
		var cheight = document.getElementById('cimg').attributes['cheight'].value;			// get current height	
		
		if (eval(formObj.pr_width.value) > cwidth || eval(formObj.pr_height.value) > cheight) { 		// check for enlarging			
			var msg = escapeHTML('<?php echo $l->m('er_001') . ': ' . $l->m('er_035'); ?>');
			alert(msg);
			resetDim();
			return;
		}		
		
		f = cheight/cwidth; // factor		
		if (sel == 1) { 																	// height changed				
			formObj.pr_width.value  = Math.round(formObj.pr_height.value / f);
		} else if (sel == 0) { 																// width changed			
			formObj.pr_height.value = Math.round(formObj.pr_width.value * f);			
		}		
	}
	
	function resetDim() { // reset dimensions
 		var formObj = document.forms[0];
		var cwidth  = document.getElementById('cimg').attributes['cwidth'].value;			// get current width	
		var cheight = document.getElementById('cimg').attributes['cheight'].value;			// get current height	
		formObj.pr_width.value  = cwidth;
		formObj.pr_height.value = cheight;
	}			
// ============================================================
// = show about, date: 06/04/2005                             =
// ============================================================	
	function about() {		
		var formObj = document.forms[0];		
		if (document.getElementById('imDiv').className == 'hideit') {
			var x = document.getElementById('menuBarDiv').getElementsByTagName('li');
			for (var i = 0; i < x.length; i++) {
				if (x[i].className == 'btnDown') {				
					formObj.param.value = (x[i].id);
					elm = x[i].id.substring(x[i].id.length-2, x[i].id.length);			
					if (elm == 'po') { // popup windows - uses inDiv
						elm = 'in'
					}
					elm = elm + 'Div';
					document.getElementById('mainDivHeader').innerHTML = setTitle('imDiv'); 		
					changeClass(1,elm,'hideit','imDiv','showit');											
				}
			}
		} else if (document.getElementById('imDiv').className == 'showit' && formObj.param.value != '') {
			elm = formObj.param.value;			
			btn_click(elm);			
		}
	}
// ============================================================
// = image file type extension V 1.0, date: 11/27/2004        =
// ============================================================	
	function imageType(type) {		
		var ext;		
		switch(parseInt(type)) {
			case 1 : ext = 'gif'; break;
   			case 2 : ext = 'jpg'; break;
			case 3 : ext = 'png'; break;
			case 6 : ext = 'bmp'; break;
   			default: ext = 'unknown';		
		}		
		return ext;
	}
// ============================================================
// = show/hide load message, date: 07/07/2005                 =
// ============================================================
	function showloadmessage() {
		document.getElementById('dialogLoadMessage').style.display = 'block';
	}	
	function hideloadmessage() {
		document.getElementById('dialogLoadMessage').style.display = 'none';
	}	
// ============================================================
// = show image info layer, date: 04/22/2005                  =
// ============================================================
	function showInfo() {
		var formObj = document.forms[0];
		if (formObj.chkRandom.checked) { // random image
			return false;
		}		
		if (document.getElementById('cimg').attributes['cfile'].value != '') {			
			var obj  = document.getElementById('inPrevDiv');
			var oDiv = document.getElementById('infoDiv');				
			
			if (oDiv.className == 'showit') {
				changeClass(0,oDiv.id,'hideit');
			} else {
				document.getElementById('inf_cwidth').innerHTML  = document.getElementById('cimg').attributes['cwidth'].value  + ' px';
				document.getElementById('inf_cheight').innerHTML = document.getElementById('cimg').attributes['cheight'].value + ' px';
				ctype = document.getElementById('cimg').attributes['ctype'].value.split('|'); 
				document.getElementById('inf_ctype').innerHTML   = ctype[1];		
				csize = document.getElementById('cimg').attributes['csize'].value.split('|');
				document.getElementById('inf_csize').innerHTML   = csize[0] + ' ' + csize[1];				
				document.getElementById('inf_ccdate').innerHTML  = document.getElementById('cimg').attributes['ccdate'].value; 
				document.getElementById('inf_cmdate').innerHTML  = document.getElementById('cimg').attributes['cmdate'].value;		
				if (iBrowser.isMSIE) {
					moveInfoTo(obj, oDiv, 0, 0); // object to move to (destination), object being moved, x offset, y offset		
				} else if (iBrowser.isGecko) {
					moveInfoTo(obj, oDiv, 0, 0); // object to move to (destination), object being moved, x offset, y offset
				}						
				changeClass(0, oDiv.id, 'showit');
			}
		}	
	}
// ============================================================
// = move layer/div to object, date: 04/22/2005               =
// ============================================================
	function moveInfoTo(obj, oDiv, ox, oy) {			
			var newX = getPosX(obj) + ox;
			var newY = getPosY(obj) + oy;			
			document.getElementById(oDiv.id).style.left = newX + 'px';
			document.getElementById(oDiv.id).style.top  = newY + 'px';					
	}
// ============================================================
// = get object's position, date: 04/22/2005                  =
// ============================================================
	function getPosX(obj) { // get X position
		var cleft = 0;
		if (obj.offsetParent) {
			while (obj.offsetParent) {
				cleft += obj.offsetLeft
				obj    = obj.offsetParent;
			}
		} else if (obj.x) {
			cleft += obj.x;
		}
		return cleft;		
	}

	function getPosY(obj) { // get Y position
		var ctop = 0;
		if (obj.offsetParent) {
			while (obj.offsetParent) {
				ctop += obj.offsetTop
				obj   = obj.offsetParent;
			}
		} else if (obj.y) {
			ctop += obj.y;
		}
		return ctop;
	}
// ============================================================
// = returns absolute path, date: 04/22/2005                  =
// ============================================================
	function absPath(path) {
		if (path.charAt(0) != '/') {
			path = '/' + path;			
		}
		return path;
	}
// ============================================================
// = escapeHTML, date: 08/12/2005                             =
// ============================================================
	function escapeHTML(str) {		
		var divElm = document.createElement('div');
		divElm.innerHTML = str; 
		str = divElm.innerHTML;
		return str;
  	}
//-->
</script>
</head>
<body onLoad="init(); hideloadmessage();" dir="<?php echo $l->getDir(); ?>">
<?php include dirname(__FILE__) . '/scripts/loadmsg.php'; ?>
<!- image info layer (cimg) -->
<div id="infoDiv" class="hideit">
  <div>
    <label><?php echo $l->m('in_028'); ?>:</label>
    <span id="inf_cwidth"> </span>
  </div>
  <div>
    <label><?php echo $l->m('in_029'); ?>:</label>
    <span id="inf_cheight"> </span>
  </div>
  <div>
    <label><?php echo $l->m('in_030'); ?>:</label>
    <span id="inf_ctype"> </span>
  </div>
  <div>
    <label><?php echo $l->m('in_031'); ?>:</label>
    <span id="inf_csize"> </span>
  </div>
  <div>
    <label><?php echo $l->m('in_033'); ?>:</label>
    <span id="inf_ccdate"> </span>
  </div>
  <div>
    <label><?php echo $l->m('in_034'); ?>:</label>
    <span id="inf_cmdate"> </span>
  </div>
</div>
<form id="iBrowser" name="iBrowser" method="post" action="scripts/rfiles.php" enctype="multipart/form-data" target="inSelFrame">
  <input type="hidden" name="lang" value="<?php echo $l->lang; ?>" />
  <input type="hidden" id="param" name="param" value="" />
  <input type="hidden" id="flist" name="flist" value="<?php echo $cfg['list']; ?>" />
  <input type="hidden" id="cimg" name="cimg" value="" cfile="" cwidth="" cheight="" csize="" ctype="" ccdate="" cmdate="" />
  <div id="outerDivWrap">
    <div class="headerDiv">
      <div class="btnRight">
        <img src="images/about_off.gif" alt="<?php echo $l->m('im_015'); ?>" width="16" height="16" border="0" align="middle" title="<?php echo $l->m('im_015'); ?>" onClick="about();" onMouseOver="this.src='images/about.gif';" onMouseOut="this.src='images/about_off.gif';" />
      </div>
      <?php echo $l->m('im_002'); ?>
    </div>
    <div class="brdPad">
      <!- MAIN MENU --------------------------------------------------------- -->
      <div id="menuDivWrap">
        <div class="headerDiv">
          <?php echo $l->m('im_003'); ?>
        </div>
        <div class="brdPad">
          <div id="menuDiv">
            <div id="menuBarDiv" >
              <ul>
                <li id="mbtn_in" class="btnUp"><img id="img_in" src="images/img_in.gif" width="40" height="40" />
                  <div>
                    <?php echo $l->m('im_007'); ?>
                  </div>
                </li>
                <li id="mbtn_at" class="btnUp"><img id="img_at" src="images/img_at.gif" width="40" height="40" />
                  <div>
                    <?php echo $l->m('im_009'); ?>
                  </div>
                </li>
                <li id="mbtn_po" class="btnUp"><img id="img_po" src="images/img_po.gif" width="40" height="40" />
                  <div>
                    <?php echo $l->m('im_013'); ?>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!- // menuDivWrap -->
      <!- MAIN CONTENT ------------------------------------------------------ -->
      <div id="mainDivWrap">
        <div id="mainDivHeader" class="headerDiv">
          <?php echo $l->m('im_016'); ?>
        </div>
        <div class="brdPad">
          <div id="mainDiv">
            <!- WELCOME ----------------------------------------------------------- -->
            <div id="imDiv" class="showit">
              <p><img class="right" src="images/ib.gif" alt="<?php echo $l->m('im_001'); ?>" title="<?php echo $l->m('im_001'); ?>" width="48" height="48" border="0" /><strong>net<span class="hilight">4</span>visions.com</strong> - the image browser plugin for WYSIWYG editors like FCKeditor, SPAW, tinyMCE, Xinha, and HTMLarea!</p>
              <p> <strong> <span class="hilight">i</span>Browser</strong> does upload images and supply file management functions. Images can be resized on the fly. If you need even more advanced features, have a look at <strong> <span class="hilight">i</span>Manager</strong>, another <strong>net<span class="hilight">4</span>visions.com</strong> plugin - it adds truecolor image editing functions like: resize, flip, crop, add text, gamma correct, merge into other image, and many others.</p>
              <p><strong> <span class="hilight">i</span>Browser</strong> is written and distributed under the GNU General Public License which means that its source code is freely-distributed and available to the general public.</p>
              <p>&nbsp;</p>
              <p>
              <div class="btnRight">
                <img src="images/firefox.gif" alt="" title="" width="80" height="15" align="absmiddle" /><img src="images/explorer.gif" alt="" title="" width="80" height="15" align="absmiddle" />
              </div>
              <span class="ver"> Version: <?php echo $cfg['ver']; ?> </span>
              </p>
            </div>
            <!- // imDiv -->
            <!- INSERT/CHANGE ----------------------------------------------------- -->
            <div id="inDiv" class="hideit">
              <fieldset>
              <!- select library ---------------------------------------------------- -->
              <div id="ilibsDiv" class="showit">
                <div class="rowDiv">
                  <div class="btnRight">
                    <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('in_003'); ?>" alt="<?php echo $l->m('in_003'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="ilibs"> <span class="title"> <?php echo $l->m('in_002'); ?> </span> </label>
                  <select class="fldlg" id="ilibs" name="ilibs" size="1" onChange="ilibsClick(this);">
                    <?php echo $lib_options; ?>
                  </select>
                </div>
              </div>
              </fieldset>
              <div class="floatWrap">
                <!- left column ------------------------------------------------------- -->
                <div class="colLeft">
                  <div style="float: left;">
                    <!- select image ------------------------------------------------------ -->
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img id="alertImg" class="hideit" src="images/alert_off.gif" onClick="alert(this.alt);" onMouseOver="this.src='images/alert.gif';" onMouseOut="this.src='images/alert_off.gif';" alt="<?php echo $l->m('in_051'); ?>" title="<?php echo $l->m('in_051'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label> <span class="title"> <?php echo $l->m('in_004'); ?> </span> </label>
                    </div>
                    <div class="rowDiv">
                      <div class="btnRight">
                        <span><img src="images/info_off.gif" onMouseOver="this.src='images/info.gif'; showInfo();" onMouseOut="this.src='images/info_off.gif'; showInfo();" alt="" title="" width="16" height="16" border="0" /><br />
                        <img src="images/dirview_off.gif" onClick="switchList();" onMouseOver="this.src='images/dirview.gif';" onMouseOut="this.src='images/dirview_off.gif';" alt="<?php echo $l->m('in_052'); ?>" title="<?php echo $l->m('in_052'); ?>" width="16" height="16" border="0" /></span>
                      </div>
                      <div id="inSelDiv">
                        <iframe name="inSelFrame" id="inSelFrame" src="scripts/rfiles.php?clib=<?php echo $clib; ?>" style="width: 100%; height: 100%;" scrolling="no" marginheight="0" marginwidth="0" frameborder="0"></iframe>
                      </div>
                    </div>
                  </div>
                </div>
                <!- // colLeft -->
                <!- right column ----------------------------------------------------- -->
                <div class="colRight">
                  <div style="float: left;">
                    <!- preview image ---------------------------------------------------- -->
                    <div class="rowDiv">
                      <label> <span class="title"> <?php echo $l->m('in_005'); ?> </span> </label>
                    </div>
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img onClick="fullSizeView('in'); return false;" src="images/prev_off.gif" onMouseOver="this.src='images/prev.gif';" onMouseOut="this.src='images/prev_off.gif';" alt="<?php echo $l->m('in_007'); ?>" title="<?php echo $l->m('in_007'); ?>" width="16" height="16" border="0" />
                      </div>
                      <div id="inPrevDiv">
                        <iframe name="inPrevFrame" id="inPrevFrame" src="images/noImg.gif" style="width: 100%; height: 100%;" scrolling="no" marginheight="0" marginwidth="0" frameborder="0"></iframe>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!- // colRight -->
              <!- random section --------------------------------------------------- -->
              <div id="raDiv" class="showit">
                <div class="mbottom5">
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('in_054'); ?>" title="<?php echo $l->m('in_054'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label> <span class="title"> <?php echo $l->m('in_053'); ?> </span> </label>
                    <input name="chkRandom" id="chkRandom" type="checkbox" value="" class="chkBox" onClick="setRandom(1);" />
                    <span class="frmText"> <?php echo $l->m('in_055'); ?> </span>
                  </div>
                  <div class="rowDiv">
                    <div id="raParamDiv" class="hideit">
                      <div class="btnRight">
                        <img src="images/img_size_off.gif" onMouseOver="this.src='images/img_size.gif';" onMouseOut="this.src='images/img_size_off.gif';" onClick="setRandom(2);" alt="<?php echo $l->m('in_057'); ?>" title="<?php echo $l->m('in_057'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label for="randomParam"> <span class="pad10"> <?php echo $l->m('in_056'); ?> </span> </label>
                      <input class="fldlg" id="randomParam" name="randomParam" type="text" value="<?php echo $cfg['random']; ?>" />
                    </div>
                  </div>
                </div>
              </div>
              <!- // raDiv -->
              <!- popup section ---------------------------------------------------- -->
              <div id="poDiv" class="hideit">
                <div class="btnRight">
                  <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('in_013'); ?>" title="<?php echo $l->m('in_013'); ?>" width="16" height="16" border="0" />
                </div>
                <div class="poPrevDiv">
                  <iframe id="poPrevFrame" name="poPrevFrame" src="images/noPop.gif" style="width: 100%; height: 100%;" scrolling="no" marginheight="0" marginwidth="0" frameborder="0"></iframe>
                </div>
                <div class="rowDiv">
                  <label> <span class="title"> <?php echo $l->m('in_010'); ?> </span> </label>
                </div>
                <div id="poDelDiv" class="hideit">
                  <div class="rowDiv">
                    <label for="chkP"> <span class="pad10"> <?php echo $l->m('in_024'); ?> </span> </label>
                    <input type="checkbox" id="chkP" name="chkP" value="" class="chkBox"/>
                    <span class="frmText"> (<?php echo $l->m('in_014'); ?>) </span>
                  </div>
                </div>
                <div class="rowDiv">
                  <label for="popClassName"> <span class="pad10"> <?php echo $l->m('at_009'); ?> </span> </label>
                  <select class="fldm" id="popClassName" name="popClassName" />
                  
                  <option value="default" selected="selected"><?php echo $l->m('at_099'); ?></option>
                  <?php echo getStyles(false); ?>
                  </select>
                </div>
                <!- clear floats ------------------------------------------------------ -->
                <div class="clrFloatRight">
                </div>
                <div class="rowDiv">
                  <label for="popTitle"> <span class="pad10"> <?php echo $l->m('at_002'); ?> </span> </label>
                  <input class="fldlg" id="popSrc" name="popSrc" type="text" value="" disabled="true" readonly="true"/>
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img onClick="selSymbol('popTitle');" src="images/symbols_off.gif" onMouseOver="this.src='images/symbols.gif';" onMouseOut="this.src='images/symbols_off.gif';" title="<?php echo $l->m('at_029'); ?>" alt="<?php echo $l->m('at_029'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('at_004'); ?>" title="<?php echo $l->m('at_004'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="popTitle"> <span class="pad10"> <?php echo $l->m('at_003'); ?> </span> </label>
                  <input class="fldmlg" id="popTitle" name="popTitle" type="text" value="" />
                </div>
              </div>
              <!- // poDiv -->
              <!- file section ----------------------------------------------------- -->
              <div id="fileDivWrap" class="showit">
                <div class="rowDiv">
                  <div class="btnRight">
				    <?php if ($cfg['create'] && isset($cfg['ilibs_inc'])) {; ?>
                    <img src="images/dir_off.gif" onClick="changeClass(0,'fileDiv','showit','fiDirDiv','showit','fiUplDiv','hideit','fiRenDiv','hideit','fiDelDiv','hideit');" onMouseOver="this.src='images/dir.gif';" onMouseOut="this.src='images/dir_off.gif';" alt="<?php echo $l->m('in_027'); ?>" title="<?php echo $l->m('in_027'); ?>" width="16" height="16" />
                    <?php }; ?>
                    <?php if ($cfg['upload']) {; ?>
                    <img src="images/upimg_off.gif" onClick="changeClass(1,'fileDiv','showit','fiDirDiv','hideit','fiUplDiv','showit','fiRenDiv','hideit','fiDelDiv','hideit');" onMouseOver="this.src='images/upimg.gif';" onMouseOut="this.src='images/upimg_off.gif';" alt="<?php echo $l->m('in_019'); ?>" title="<?php echo $l->m('in_019'); ?>" width="16" height="16" />
                    <?php }; ?>
                    <?php if ($cfg['rename']) {; ?>
                    <img class="isecbtn"src="images/renimg_off.gif" onClick="changeClass(0,'fileDiv','showit','fiDirDiv','hideit','fiRenDiv','showit','fiUplDiv','hideit','fiDelDiv','hideit');" onMouseOver="this.src='images/renimg.gif';" onMouseOut="this.src='images/renimg_off.gif';" alt="<?php echo $l->m('in_017'); ?>" title="<?php echo $l->m('in_017'); ?>" width="16" height="16" border="0" />
                    <?php }; ?>
                    <?php if ($cfg['delete']) {; ?>
                    <img src="images/delimg_off.gif" onClick="changeClass(0,'fileDiv','showit','fiDirDiv','hideit','fiDelDiv','showit','fiRenDiv','hideit','fiUplDiv','hideit');" onMouseOver="this.src='images/delimg.gif';" onMouseOut="this.src='images/delimg_off.gif';" alt="<?php echo $l->m('in_006'); ?>" title="<?php echo $l->m('in_006'); ?>" width="16" height="16" border="0" />
                    <?php }; ?>
                    <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('in_008'); ?>" title="<?php echo $l->m('in_008'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label> <span class="title"> <?php echo $l->m('in_015'); ?> </span> </label>
                </div>
                <!- clear floats ------------------------------------------------------ -->
                <div class="clrFloatRight">
                </div>
                <div id="fileDiv" class="showit">
                  <?php if ($cfg['delete']) { ?>
                  <div id="fiDelDiv" class="hideit">
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img onClick="deleteClick();" src="images/okclick_off.gif" onMouseOver="this.src='images/okclick.gif';" onMouseOut="this.src='images/okclick_off.gif';" alt="<?php echo $l->m('in_006'); ?>" title="<?php echo $l->m('in_006'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label for="in_srcnew"> <span class="pad10"> <?php echo $l->m('in_024'); ?> </span> </label>
                      <input class="fldlg readonly" id="in_delinfo" name="in_delinfo" type="text" value="" disabled="true" readonly="true" />
                    </div>
                  </div>
                  <?php }; ?>
                  <?php if ($cfg['rename']) { ?>
                  <div id="fiRenDiv" class="hideit">
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img onClick="renameClick();" src="images/okclick_off.gif" onMouseOver="this.src='images/okclick.gif';" onMouseOut="this.src='images/okclick_off.gif';" alt="<?php echo $l->m('in_017'); ?>" title="<?php echo $l->m('in_017'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label for="in_srcnew"> <span class="pad10"> <?php echo $l->m('in_016'); ?> </span> </label>
                      <input class="fldlg" id="in_srcnew" name="in_srcnew" type="text" value="" onKeyUp="RemoveInvalidChars(this, '[^A-Za-z0-9 \_-]'); ForceLowercase(this); CharacterReplace(this, ' ', '_'); return false;"  />
                    </div>
                  </div>
                  <?php }; ?>
                  <?php if ($cfg['create']) { ?>
                  <div id="fiDirDiv" class="hideit">
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img onClick="createClick();" src="images/okclick_off.gif" onMouseOver="this.src='images/okclick.gif';" onMouseOut="this.src='images/okclick_off.gif';" alt="<?php echo $l->m('in_026'); ?>" title="<?php echo $l->m('in_026'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label for="in_srcnew"> <span class="pad10"> <?php echo $l->m('in_025'); ?> </span> </label>
                      <input class="fldlg" id="in_dirnew" name="in_dirnew" type="text" value="" onKeyUp="RemoveInvalidChars(this, '[^A-Za-z0-9 \_-]'); ForceLowercase(this); CharacterReplace(this, ' ', '_'); return false;" />
                    </div>
                  </div>
                  <?php }; ?>
                  <?php if ($cfg['upload']) {; ?>
                  <div id="fiUplDiv" class="hideit">
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img onClick="uploadClick();" src="images/okclick_off.gif" onMouseOver="this.src='images/okclick.gif';" onMouseOut="this.src='images/okclick_off.gif';" alt="<?php echo $l->m('in_019'); ?>" title="<?php echo $l->m('in_019'); ?>" width="16" height="16" />
                      </div>
                      <?php 
							$max = isset($cfg['umax']) && $cfg['umax'] >= 1 ? $cfg['umax'] : 1;					
							for($i=1; $i <= $max; $i++) {; ?>
                      <label for="nfile"> <span class="pad10"> <?php echo $l->m('in_018'); if ($max > 1){ echo ' (' . $i . ')';} ?> </span> </label>
                      <input name="nfile[]" type="file" class="fldlg" id="nfile[]" size="53" accept="image/*" />
                      <?php }; ?>
                    </div>
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('in_021'); ?>" alt="<?php echo $l->m('in_021'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label for="chkThumbSize[]"> <span class="pad20"> <?php echo $l->m('in_020'); ?> </span> </label>
                      <div id="fmtDiv">
                        <?php echo thumbSizes($cfg['thumbs']); ?>
                      </div>
                    </div>
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('in_038'); ?>" alt="<?php echo $l->m('in_038'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label for="rotateDiv"> <span class="pad20"> <?php echo $l->m('in_037'); ?> </span> </label>
                      <select class="fldm" id="selRotate" name="selRotate">
                        <option value="" selected="selected"><?php echo $l->m('in_041'); ?></option>
                        <optgroup label="<?php echo $l->m('in_043'); ?>">
                        <option value="P" ><?php echo $l->m('in_044'); ?></option>
                        <option value="p" ><?php echo $l->m('in_045'); ?></option>
                        </optgroup>
                        <optgroup label="<?php echo $l->m('in_046'); ?>">
                        <option value="l" ><?php echo $l->m('in_047'); ?></option>
                        <option value="L"><?php echo $l->m('in_048'); ?></option>
                        </optgroup>
                        <optgroup label="<?php echo $l->m('in_049'); ?>">
                        <option value="x"><?php echo $l->m('in_050'); ?></option>
                        </optgroup>
                      </select>
                      <span class="frmText">(<?php echo $l->m('in_099'); ?>: <?php echo $l->m('in_042'); ?>)</span>
                    </div>
                  </div>
                  <?php }; ?>
                </div>
              </div>
              <!- // fiDiv -->
            </div>
            <!- // inDiv -->
            <!- ATTRIBUTES -------------------------------------------------------- -->
            <div id="atDiv" class="hideit">
              <fieldset>
              <div class="rowDiv">
                <label for="pr_src"> <span class="title"> <?php echo $l->m('at_002'); ?> </span> </label>
                <input class="fldlg readonly" id="pr_src" name="pr_src" type="text" value="" disabled="true" readonly="true" />
              </div>
              <div class="rowDiv">
                <div class="btnRight">
                  <img onClick="selSymbol('pr_title');" src="images/symbols_off.gif" onMouseOver="this.src='images/symbols.gif';" onMouseOut="this.src='images/symbols_off.gif';" title="<?php echo $l->m('at_029'); ?>" alt="<?php echo $l->m('at_029'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('at_004'); ?>" title="<?php echo $l->m('at_004'); ?>" width="16" height="16" border="0" />
                </div>
                <label for="pr_title"> <span class="title"> <?php echo $l->m('at_003'); ?> </span> </label>
                <input class="fldmlg" id="pr_title" name="pr_title" type="text" value="" onChange="updateStyle()" />
              </div>
              <div class="rowDiv">
                <div class="btnRight">
                  <img onClick="selSymbol('pr_alt');" src="images/symbols_off.gif" onMouseOver="this.src='images/symbols.gif';" onMouseOut="this.src='images/symbols_off.gif';" title="<?php echo $l->m('at_030'); ?>" alt="<?php echo $l->m('at_030'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('at_006'); ?>" title="<?php echo $l->m('at_006'); ?>" width="16" height="16" border="0" />
                </div>
                <label for="pr_alt"> <span class="title"> <?php echo $l->m('at_005'); ?> </span> </label>
                <input class="fldmlg" id="pr_alt" name="pr_alt" type="text" value="" onChange="updateStyle()" />
              </div>
              </fieldset>
              <div class="floatWrap">
                <!- left column ------------------------------------------------------ -->
                <div class="colLeft">
                  <div class="rowDiv">
                    <label> <span class="title"> <?php echo $l->m('at_007'); ?> </span> </label>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('at_008'); ?>" title="<?php echo $l->m('at_008'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="pr_class"> <span class="pad10"> <?php echo $l->m('at_009'); ?> </span> </label>
                    <select class="fldm" id="pr_class" name="pr_class" onChange="updateStyle()">
                      <option value="default" selected="selected"><?php echo $l->m('at_099'); ?></option>
                      <?php echo getStyles(false); ?>
                    </select>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('at_011'); ?>" alt="<?php echo $l->m('at_011'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label> <span class="title"> <?php echo $l->m('at_010'); ?> </span> </label>
                  </div>
                  <div class="rowDiv">
                    <label for="pr_align"> <span class="pad10"> <?php echo $l->m('at_012'); ?> </span> </label>
                    <select class="fldm" id="pr_align" name="pr_align" onChange="updateStyle()">
                      <option value=""><?php echo $l->m('at_013'); ?></option>
                      <option value="left"><?php echo $l->m('at_014'); ?></option>
                      <option value="right"><?php echo $l->m('at_015'); ?></option>
                      <option value="top"><?php echo $l->m('at_016'); ?></option>
                      <option value="middle"><?php echo $l->m('at_017'); ?></option>
                      <option value="bottom"><?php echo $l->m('at_018'); ?></option>
                    </select>
                  </div>
                  <div class="rowDiv">
                    <label for="pr_size"> <span class="pad10"> <?php echo $l->m('at_022'); ?> </span> </label>
                    <input class="fldsm readonly" id="pr_size" name="pr_size" type="text"value="" maxlength="8" disabled="true" readonly="true" />
                    <span class="frmText">(<span id="pr_sizeUnit"></span>)</span>
                  </div>
                  <div class="rowDiv">
                    <?php if ($cfg['attrib'] == true) {; ?>
                    <div class="btnRight">
                      <img src="images/img_size_off.gif" onMouseOver="this.src='images/img_size.gif';" onMouseOut="this.src='images/img_size_off.gif';" onClick="resetDim();" alt="<?php echo $l->m('at_031'); ?>" title="<?php echo $l->m('at_031'); ?>" width="16" height="16" border="0" />
                    </div>
                    <?php }; ?>
                    <label for="pr_width"> <span class="pad10"> <?php echo $l->m('at_023'); ?> </span> </label>
                    <input id="pr_width" name="pr_width" type="text"value="" maxlength="4" <?php if ($cfg['attrib'] != true) {; ?> class="fldsm readonly" disabled="true" readonly="true" <?php } else {; ?> class="fldsm" onchange="changeDim(0);" onkeyup="RemoveInvalidChars(this, '[^0-9]');" <?php }; ?> />
                    <span class="frmText"> (px) </span>
                  </div>
                  <div class="rowDiv">
                    <label for="pr_height"> <span class="pad10"> <?php echo $l->m('at_024'); ?> </span> </label>
                    <input id="pr_height" name="pr_height" type="text"value="" maxlength="4" <?php if ($cfg['attrib'] != true) {; ?> class="fldsm readonly" disabled="true" readonly="true" <?php } else {; ?> class="fldsm" onchange="changeDim(1);" onkeyup="RemoveInvalidChars(this, '[^0-9]');" <?php }; ?> />
                    <span class="frmText"> (px) </span>
                  </div>
                  <div class="rowDiv">
                    <label for="pr_border"> <span class="pad10"> <?php echo $l->m('at_025'); ?> </span> </label>
                    <input class="fldsm" id="pr_border" name="pr_border" type="text"value="" maxlength="2" onChange="updateStyle();" onKeyUp="RemoveInvalidChars(this, '[^0-9]');"  />
                    <span class="frmText"> (px) </span>
                  </div>
                  <div class="rowDiv">
                    <label for="pr_vspace"> <span class="pad10"> <?php echo $l->m('at_026'); ?> </span> </label>
                    <input class="fldsm" id="pr_vspace" name="pr_vspace" type="text" value="" maxlength="2" onChange="updateStyle();" onKeyUp="RemoveInvalidChars(this, '[^0-9]');" />
                    <span class="frmText"> (px) </span>
                  </div>
                  <div class="rowDiv">
                    <label for="pr_hspace"> <span class="pad10"> <?php echo $l->m('at_027'); ?> </span> </label>
                    <input class="fldsm" id="pr_hspace" name="pr_hspace" type="text" value="" maxlength="2" onChange="updateStyle();" onKeyUp="RemoveInvalidChars(this, '[^0-9]');" />
                    <span class="frmText"> (px) </span>
                  </div>
                </div>
                <!- // colLeft -->
                <!- right column ----------------------------------------------------- -->
                <div class="colRight">
                  <div style="float: left;">
                    <div class="rowDiv">
                      <label> <span class="title"> <?php echo $l->m('at_028'); ?> </span> </label>
                    </div>
                    <div class="rowDiv">
                      <div id="atPrevDiv">
                        <p><img id="atPrevImg" src="images/textflow.gif" width="45" height="45" alt="" title="" hspace="" vspace="" border="" class="" />Lorem ipsum, Dolor sit amet, consectetuer adipiscing loreum ipsum edipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.Loreum ipsum edipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exercitation ullamcorper suscipit. Lorem ipsum, Dolor sit amet, consectetuer adipiscing loreum ipsum edipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <!- // colRight -->
                <div class="rowDiv">
                  <div class="btnRight">
                    <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('at_033'); ?>" title="<?php echo $l->m('at_033'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="pr_chkCaption"> <span class="title"> <?php echo $l->m('at_032'); ?> </span> </label>
                  <input name="pr_chkCaption" type="checkbox" class="chkBox" id="pr_chkCaption" onChange="updateStyle()" value="1" />
                  <span class="frmText">(<?php echo $l->m('at_034'); ?>)</span>
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('at_008'); ?>" title="<?php echo $l->m('at_008'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="pr_captionClass"> <span class="pad10"> <?php echo $l->m('at_009'); ?> </span> </label>
                  <select class="fldm" id="pr_captionClass" name="pr_captionClass" onChange="updateStyle()">
                    <option value="default" selected="selected"><?php echo $l->m('at_099'); ?></option>
                    <?php echo getStyles(true); ?>
                  </select>
                </div>
              </div>
            </div>
            <!- // atDiv -->
          </div>
        </div>
      </div>
      <!- // mainDivWrap -->
      <!- footer ----------------------------------------------------------- -->
      <div id="ftDivWrap">
        <div id="ftDiv">
          <input type="button" value="<?php echo $l->m('im_005'); ?>" class="btn" onClick="insertImage();" />
          <span class="pad5">
          <input type="button" value="<?php echo $l->m('im_006'); ?>" class="btn" onClick="top.window.close();" />
          </span>
        </div>
      </div>
      <!- // ftDivWrap -->
    </div>
  </div>
  <!- // outerDivWrap -->
</form>
</body>
</html><?php
// ============================================================
// = create library list V 1.0, date: 05/10/2005              =
// ============================================================
	function liboptions($arr, $prefix = '', $sel = '') {
  		$retval = '';
  		foreach($arr as $lib) {			
    		$retval .= '<option value="' . absPath($lib['value']) . '"' . (($lib['value'] == $sel) ? ' selected="selected"' : '') . '>' . $prefix . $lib['text'] . '</option>' . "\n";
  		}
  		return $retval;
	}
// ============================================================
// = create thumb sizes V 1.0, date: 05/23/2005               =
// ============================================================
	function thumbSizes($arr, $sel = '') {
  		global $l;
		$retval = '';
  		foreach($arr as $key => $thumb) {			
			$retval .= '<div>' . '<input id="chkThumbSize[' . $key . ']" name="chkThumbSize[' . $key . ']" class="chkBox" type="checkbox" value="' . $key . '"' . (($key == 0) ? ' checked="checked"' : '') . ' />' . '<span class="frmText">' . (($thumb['size'] == '*') ? $l->m('in_022') . '&nbsp;'  : $thumb['size'] . ' px' ) . '</span>' . (($thumb['crop'] == true) ? '<img src="images/thbCrop.gif" align="absmiddle" width="10px" height="10px" alt="' . $l->m('in_023') . '" title="' . $l->m('in_023') . '" />' : '') . '</div>' . "\n";
		}
  		return $retval;
	}
// ============================================================
// = abs path - add slashes V 1.0, date: 05/10/2005           =
// ============================================================
	function absPath($path) {		
		if (substr($path,-1)  != '/') $path .= '/';
		if (substr($path,0,1) != '/') $path  = '/' . $path;
		return $path;
	}
// ============================================================
// = css styles V 1.0, date: 08/03/2005                       =
// ============================================================
	function getStyles($cap) {
		$styles = '';
		global $cfg;
		foreach ($cfg['style'] as $key => $value) {
			$pos = strrpos($key,'capDiv'); // is caption style
			if ($cap == false && $pos === false) {
					$styles .= '<option value="'. $key . '">' . $value . '</option>';
			} elseif ($cap == true && $pos !== false) {
					$styles .= '<option value="'. $key . '">' . $value . '</option>';
			}
		}
		return $styles;
	}
//-------------------------------------------------------------------------
?>
