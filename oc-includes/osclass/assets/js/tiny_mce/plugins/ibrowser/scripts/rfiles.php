<?php
	// ================================================
	// PHP image browser - iBrowser 
	// ================================================
	// iBrowser dialog - file functions
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: GPL - see license.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.0                   Date: 09/11/2006
	// ================================================
	
	//-------------------------------------------------------------------------
	// include configuration settings
	include dirname(__FILE__) . '/../config/config.inc.php';
	include dirname(__FILE__) . '/../langs/lang.class.php';	
	//-------------------------------------------------------------------------
		
	// language settings	
	$l = (isset($_REQUEST['lang']) ? new PLUG_Lang($_REQUEST['lang']) : new PLUG_Lang($cfg['lang']));
	$l->setBlock('ibrowser');
	//-------------------------------------------------------------------------	
	// parameters
	$param  = (isset($_REQUEST['param']) ? $_REQUEST['param'] : '');
	if (isset($param)) {
		$param  = explode('|', $param);
	}	
	// set action	
	$action = (isset($_REQUEST['action']) ? $_REQUEST['action'] : '');			
	// set image library		
	$clib = (isset($_REQUEST['clib']) ? $_REQUEST['clib'] : (isset($_REQUEST['ilibs']) ? $_REQUEST['ilibs'] : $cfg['ilibs'][0]['value']));	
	// set current file
	$cfile = (isset($_REQUEST['cfile']) ? $_REQUEST['cfile'] : '');	
	// set new file
	$nfile = (isset($_REQUEST['nfile']) ? $_REQUEST['nfile'] : '');
	// set list view	
	$list = (isset($_REQUEST['flist']) ? $_REQUEST['flist'] : $cfg['list']);	
	
	//-------------------------------------------------------------------------
	// file/directory actions			
	if ($param[0] == 'delete') {			// ACTION: delete image
		$action = $param[0];				
		$cfile  = $param[1]; 				// current filename			
		if(!deleteImg($clib, $cfile)) {
			echo $l->m('er_001') . ': ' . $l->m('er_030');			
		};	
	} else if ($param[0] == 'rename') {		// ACTION: rename image			
		$action = $param[0];	
		$cfile  = $param[1]; 				// current filename
		$nfile  = $param[2]; 				// new filename
		if(!$nfile = renameImg($clib, $cfile, $nfile)) {
			echo $l->m('er_001') . ': ' . $l->m('er_033');	
		};
	} else if ($param[0] == 'upload') {		// ACTION: upload image		
		$action = $param[0];		
		$chkT   = (isset($_REQUEST['chkThumbSize']) ? $_REQUEST['chkThumbSize'] : Array() ); 	// thumb-sizes in Array								
		$selR   = (isset($_REQUEST['selRotate']) ? $_REQUEST['selRotate'] : '');	// auto rotate	
		if (isset($_FILES['nfile']['name'][0])) {			
			if (!$nfile = uploadImg($clib, $chkT, $selR)) {	
				echo $l->m('er_001') . ': ' . $l->m('er_028');	
			}	
		};	
	} else if ($param[0] == 'create') {		// ACTION: create directory			
		$action = $param[0];	
		$nfile  = $param[1]; 				// new filename
		if(!createDir($clib, $nfile)) {
			echo $l->m('er_001') . ': ' . $l->m('er_034');	
		};
	} else if ($param[0] == 'update') {		// ACTION: update image list and select current image			
		$action = $param[0];				
		$cfile  = $param[1];				// current filename
	} else if ($param[0] == 'switch') {		// ACTION: switch image list view (list or thumbnails)			
		$action = $param[0];				
		$cfile  = $param[1];				// current filename	
	}		
?>
<!-- do not delete this line - it's need for proper working of the resizeDialogToContent() function -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $l->m('im_002'); ?></title>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $l->getCharset(); ?>">
<style type="text/css">
<!--
@import url("../css/style.css");

html, body {
	margin: 0px;
	padding: 0px;
}

-->
</style>
<?php if ($list == false) { // thumbnail view ?> 
	<style type="text/css">
	<!--
		div#iselDiv li {
			margin: 5px;
			padding: 5px;
			display: block;
			border: 1px solid #cccccc;	
			float: left;	
		}
	-->
	</style>
<?php } else { // list view ?>
	<style type="text/css">
	<!--
		div#iselDiv li {
			margin: 0px;
			padding: 0px;
			display: block;		
			padding-left: 5px;
			padding-right: 5px;			
		}
	-->
	</style>
<?php }; ?>
<script language="JavaScript" type="text/JavaScript">
<!--
// ============================================================
// = image list item V 1.0, date: 01/21/2005                  =
// ============================================================	
	// list item hover
	function li_over() {
		if (this.className != 'cimgdown') {
			this.className   = 'cimgover';
		}
	}
	// list item out
	function li_out() {
		if (this.className != 'cimgdown') {
			this.className   = 'cimgup';
		}
	}
	// list item down
	function li_down() {
		if (this.className != 'cimgdown') {
			this.className  = 'cimgdown';
		}
	}
	// list item click
	function li_click() {		
		x = document.getElementById('iselDiv').getElementsByTagName('li');
		for (var i = 0; i < x.length; i++) {
			if (x[i].className == 'cimgdown') {
				if (x[i] != this) {
					x[i].className = 'cimgup';
				}
			}
		}		
		imageChangeClick(this);		
	}	
// ============================================================
// = init filelist - set attributes V 1.0, date: 04/18/2005   =
// ============================================================
	function init() {		
		var formObj = document.forms[0];
		// init mouse events on image list <li>
		var x = document.getElementById('iselDiv').getElementsByTagName('li');
		for (var i = 0; i < x.length; i++) {
			if (x[i].className == 'cimgup') {
				x[i].onmouseover = li_over;
				x[i].onmouseout  = li_out;
				x[i].onmousedown = li_down;
				x[i].onclick     = li_click;
			}
		}		
		
		// actions
		var action = formObj.action.value;		
		if (action == 'upload') {
			var tfile = '<?php echo $nfile; ?>';			
			getObject(tfile);
			self.parent.hideloadmessage();				
		} else if (action == 'rename') {
			var tfile = '<?php echo $nfile; ?>';			
			getObject(tfile);
		} else if (action == 'update') {
			var tfile = '<?php echo $cfile; ?>';			
			getObject(tfile);
		} else if (action == 'switch') {
			var tfile = '<?php echo $cfile; ?>';			
			getObject(tfile);					
		} else if (action == 'delete') {			
			imageChangeClick();
		} else if (formObj.action.value == 'create') {	
			// parent needs to be refreshed if directory got created	
			parent.document.location.reload(); 	
		}
	}
// ============================================================
// = image change - set attributes V 1.0, date: 04/18/2005    =
// ============================================================	
	function imageChangeClick(obj) {
		var formObj = document.forms[0];
		var action  = formObj.action.value;	
		if (obj) {
			parent.document.getElementById('cimg').attributes['cfile'].value   = obj.attributes['ifile'].value;
			parent.document.getElementById('cimg').attributes['csize'].value   = obj.attributes['isize'].value;
			parent.document.getElementById('cimg').attributes['cheight'].value = obj.attributes['iheight'].value;
			parent.document.getElementById('cimg').attributes['cwidth'].value  = obj.attributes['iwidth'].value;
			parent.document.getElementById('cimg').attributes['ctype'].value   = obj.attributes['itype'].value;
			parent.document.getElementById('cimg').attributes['cmdate'].value  = obj.attributes['imdate'].value;
			parent.document.getElementById('cimg').attributes['ccdate'].value  = obj.attributes['icdate'].value;		
		}
		self.parent.imageChange(action);
		formObj.action.value = null; // resetting action status		
	}
// ============================================================
// = get current file - set attrib V 1.0, date: 04/18/2005    =
// ============================================================		
	function getObject(tfile) {	
		var x = document.getElementById('iselDiv').getElementsByTagName('li');
		for (var i = 0; i < x.length; i++) {
			if (x[i].attributes['ifile'].value == tfile) {					
				x[i].className = 'cimgdown';
				imageChangeClick(x[i]);
			}
		}	
	}
// ============================================================
// = load/hide message, date: 02/08/2005                      =
// ============================================================
	function hideloadmessage() {
		document.getElementById('dialogLoadMessage').style.display = 'none'
	}
-->
</script>
<title>Image list</title>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $l->getCharset(); ?>">
</head>
<body onload="init(); hideloadmessage();" dir="<?php echo $l->getDir(); ?>">
<?php include 'loadmsg.php'; ?>
<form id="rfiles" name="rfiles" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" target="_self">
  <input type="hidden" name="lang" value="<?php echo $l->lang; ?>" />
  <input type="hidden" id= "action" name="action" value="<?php echo $action; ?>" />    
  <div id="iselDiv">
    <ul>
      <?php echo getItems($cfg['root_dir'] . $clib, $cfg['valid'], $list); ?>
    </ul>
  </div>
</form>
</body>
</html>
<?php
	// get images
	function getItems($path, $valid, $list) {			
		global $cfg;
		global $l;		
	    
		$path = str_replace('//','/', $path); // remove double slash in path if any		
		$retstr = ''; 			                           
		if ($handle = @opendir($path)) {			
			$files = array();
			$valids = implode('|', $valid);			
			while (($file = readdir($handle)) !== false) {                                                            
				if (is_file($path . $file) && preg_match('/\.(' . $valids . ')$/i', $file, $matches)) {                                                                                   
					$files[$path . $file] = $matches[0];
				}
			}
			closedir($handle);                                               
			ksort($files);							
			$dfmt = "m-d-Y";
			foreach ($files as $filename => $ext) {										
				$size     = @getimagesize($path . basename($filename));		
				if( $size === false ) {
					 continue;
				}
				$fsize    = filesize($path . basename($filename));						
				$modified = date($dfmt, filemtime($path . basename($filename)));
				$created  = date($dfmt, filectime($path . basename($filename)));								
				$ctype    = iType($size[2]);				
				if ($list == true || $list == 1) {
					$retstr .= '<li class="cimgup" ifile="' . basename($filename) . '" iwidth="' . htmlentities($size[0], ENT_QUOTES) . '" iheight="' . htmlentities($size[1], ENT_QUOTES) . '" itype="' . htmlentities($size[2] . '|' . $ctype, ENT_QUOTES) . '" imdate="' . htmlentities($modified, ENT_QUOTES) . '" icdate="' . htmlentities($created, ENT_QUOTES) . '" isize="' .filesize_h($fsize,2) . '">' . htmlentities(basename($filename), ENT_QUOTES,$l->getCharset()) . '</li>' . "\n";
				} else {
					$src     = 'phpThumb/phpThumb.php?src=' . absPath(str_replace($cfg['root_dir'],'', $path)) . basename($filename) . '&w=48&h=48&far=1&bg=ffffff&f=jpg'; 				
					$retstr .= '<li class="cimgup" ifile="' . basename($filename) . '" iwidth="' . htmlentities($size[0], ENT_QUOTES) . '" iheight="' . htmlentities($size[1], ENT_QUOTES) . '" itype="' . htmlentities($size[2] . '|' . $ctype, ENT_QUOTES) . '" imdate="' . htmlentities($modified, ENT_QUOTES) . '" icdate="' . htmlentities($created, ENT_QUOTES) . '" isize="' .filesize_h($fsize,2) . '">' . '<img src="' . $src . '" width="48" height="48" alt="' . basename($filename) . '; ' . htmlentities($size[0], ENT_QUOTES) . ' x ' . htmlentities($size[1], ENT_QUOTES) . 'px;' . '" title="' . basename($filename) . '; ' . htmlentities($size[0], ENT_QUOTES) . ' x ' . htmlentities($size[1], ENT_QUOTES) . 'px;' . '"/>' . '</li>' . "\n";
				}
			}			
			return $retstr;
		}
		echo $l->m('er_036');			
		return false;		
	}
	// get image types
	function iType($type) {		
		switch ($type) {
			case 1:
				$str = 'GIF'; break;
		   	case 2:
			   	$str = 'JPG'; break;
		   	case 3:
			   	$str = 'PNG'; break;
		   	case 4:
			   	$str = 'SWF'; break;
			case 5:
			   	$str = 'PSD'; break;
			case 6:
			   	$str = 'BMP'; break;
			case 7:
			   	$str = 'TIFF'; break;
			case 8:
			   	$str = 'TIFF'; break;
			case 15:
			   	$str = 'WBMP'; break;
			default:
			   	$str = 'n/a'; break;
		}
		return $str;
	}
	
	//-------------------------------------------------------------------------
	// Return the human readable size of a file
	// @param int $size a file size
	// @param int $dec a number of decimal places
	function filesize_h($size, $dec = 1) {
		$sizes = array('b', 'kb', 'mb', 'gb');
		$count = count($sizes);
		$i = 0;
		while ($size >= 1024 && ($i < $count - 1)) {
			$size /= 1024;
			$i++;
		}
		return round($size, $dec) . '|' . $sizes[$i];
	}
	
	//-------------------------------------------------------------------------
	// delete image
	function deleteImg($clib, $cfile) {		
  		global $cfg;  		
  		global $l;
		  
  		if (!$cfg['delete']) {
			return false;
		}		
		$path = str_replace('//', '/', $cfg['root_dir'] . $clib); 	// remove double slash in path		
		return @unlink($path . $cfile); 							// returns true or false 				
	}
	
	//-------------------------------------------------------------------------
	// rename image	
	function renameImg($clib, $cfile, $nfile) {		
		global $cfg;  		
  		global $l;
				
		if (!$cfg['rename']) {
			return false;
		}
		
		// check new file extension
		$ext = strtolower(substr($nfile,strrpos($nfile, '.')+1));
		if (!in_array($ext, $cfg['valid'])) { 						// invalid image / file extension			
			echo $l->m('er_029');			
			return false;
		}
		
		$path = str_replace('//', '/', $cfg['root_dir'] . $clib); 	// remove double slash in path					
		if (file_exists($path . $cfile)) {		
			$nfile = fixFileName($nfile); 							// remove invalid characters in file name
        	$nfile = chkFileName($path, $nfile); 					// rename if file already exists
			
			@rename($path . $cfile, $path . $nfile);				
         	return $nfile;			
		}
		return false;
	}
	
	//-------------------------------------------------------------------------
	// create directory
	function createDir($clib, $nfile) {		
  		global $cfg;  		
  		global $l; 
		 
  		if (!$cfg['create']) {
			return false;
		}
		
		$nfile = fixFileName($nfile);
		$tfile = $nfile;
		$path = str_replace('//', '/', $cfg['root_dir'] . $clib); 	// remove double slash in path		
		
		// renaming directory if it already exists
		// keep looping and incrementing _i filenumber until a non-existing one is found		
		$i = 1;
		while (file_exists($path . $nfile)) {			
			$nfile = $tfile . '_' . $i;				
			$i++;
		}		
		
		$perm = 0777;
		$oumask = umask(0);
		umask();
		if(@mkdir($path . $nfile, $perm)) {
			umask($oumask);
			return true;
		}
		umask($oumask);
		return false;	
	}	
	//-------------------------------------------------------------------------
	// upload image
	function uploadImg($clib, $chkT, $selR) { 		
		global $cfg;
  		global $l;
		  
  		if (!$cfg['upload']) {
			return false;
		}
		
		foreach ($_FILES['nfile']['size'] as $key => $size) {			
			if ($size > 0) {			
				// get file extension and check for validity 				
				$ext = pathinfo($_FILES['nfile']['name'][$key]);
				$ext = strtolower($ext['extension']);				
				if (!in_array($ext, $cfg['valid'])) { 						// invalid image			
					echo $l->m('er_029');			
					return false;
				}
				
				$path  = str_replace('//', '/', $cfg['root_dir'] . $clib); 	// remove double slash in path	
				$nfile = fixFileName($_FILES['nfile']['name'][$key]); 		// remove invalid characters in filename					
				
				// move file to temp directory for processing
				if (!move_uploaded_file($_FILES['nfile']['tmp_name'][$key], $cfg['temp'] . '/' . $nfile)) { // upload image to temp dir
					echo $l->m('er_028');				
					return false;
				}
				
				$size = getimagesize($cfg['temp'] . '/' . $nfile);	
				
				// process (thumbnail) images			
				$arr = $cfg['thumbs'];				
				foreach($arr as $key => $thumb) {
					if (in_array($key, $chkT)) {										
						// create new phpThumb() object
						require_once(dirname(__FILE__) . '/phpThumb/phpthumb.class.php');
						$phpThumb = new phpThumb();							// create object
						// parameters
						$phpThumb->config_cache_disable_warning = true;		// disable cache warning			
						$phpThumb->config_output_format = $ext;				// output format	
						$phpThumb->src = $cfg['temp'] . '/' . $nfile;		// destination
						$phpThumb->q = 95; 									// compression level for jpeg
						if ($selR != '') {									// set auto rotate
							$phpThumb->ar = $selR;
						};										
						//-------------------------------------------------------------------------
						if ($thumb['size'] > 0 && ($size[0] >= $thumb['size'] || $size[1] >= $thumb['size'])) {	// size value is set -> RESIZING and source image is larger than preset sizes
							// resize parameters
							if ($size[0] < $size[1]) {                      // portrait
								$phpThumb->h  = $thumb['size'];				// max. height
							} else {
								$phpThumb->w  = $thumb['size'];				// max. width
							}
							// crop parameters 
							if($thumb['crop'] == true) {
								$phpThumb->zc = 1;							// set zoom crop								
								$phpThumb->w  = $thumb['size'];				// width
								$phpThumb->h  = $thumb['size'];				// height
							}
							// create file suffix
							if ($thumb['ext'] == '*') {						// image size is used
								$dim = '_' . $thumb['size'];				// e.g. _1280
							} else if ($thumb['ext'] == '') {				// no suffix is created
								$dim = '';									
							} else {										// suffix is set to $thumb['ext']
								$dim = '_'. $thumb['ext'];
							}
						//-------------------------------------------------------------------------
						} elseif ($thumb['size'] == 0 || $thumb['size'] == '*') {					// size value is set to '0' -> NO RESIZING
							// crop parameters
							if ($thumb['crop'] == true) {
								$phpThumb->zc = 1;							// set zoom crop
								if($size[0] < $size[1]) { 					// portrait
									$phpThumb->w  = $size[0];				// getimagesize width value
									$phpThumb->h  = $size[0];				// getimagesize width value
								} else {									// landscape
									$phpThumb->w  = $size[1];				// getimagesize height value
									$phpThumb->h  = $size[1];				// getimagesize height value
								}
							}
							// create file suffix							
							if ($thumb['ext'] == '*') {						// image size is used
								$dim = '_' . (($size[0] <= $size[1]) ? $size[1] : $size[0]);	// source height or width - e.g. _1280
							} else if ($thumb['ext'] == '') {				// no suffix is created
								$dim = '';
							} else {										// suffix is set to $thumb['ext']
								$dim = '_'. $thumb['ext'];
							}
						//-------------------------------------------------------------------------
						} else {											// default setting - images smaller than predefined sizes
							$dim = '';										// no file suffix is used
						}
						//-------------------------------------------------------------------------
						$nthumb = fixFileName(basename($nfile, '.' . $ext) . $dim . '.' . $ext); 					
						$nthumb = chkFileName($path, $nthumb); 				// rename if file already exists								
						
						if ($phpThumb->GenerateThumbnail()) {
							$phpThumb->RenderToFile($path . $nthumb);
							@chmod($path . $nthumb, 0755) or die($l->m('er_028'));					
						} else { 											// error				
							echo $l->m('er_028');
							return false;
						}										
						unset($phpThumb); 					
					}					
				}
				@unlink($cfg['temp'] . '/' . $nfile);						// delete temporary file					
			}
		}		
		return $nthumb;			  		
	}
	//-------------------------------------------------------------------------
	// escape and clean up file name (only lowercase letters, numbers and underscores are allowed) 
	function fixFileName($file) {
		$file = ereg_replace("[^a-z0-9._-]", "", str_replace(" ", "_", str_replace("%20", "_", strtolower($file))));
		return $file;
	}
	//-------------------------------------------------------------------------
	// check whether file already exists; rename file if filename already exists
	// keep looping and incrementing _i filenumber until a non-existing filename is found
	function chkFileName($path, $nfile) {
		$tfile = $nfile;
		$i = 1;
		while (file_exists($path . $nfile)) {
			$nfile = ereg_replace('(.*)(\.[a-zA-Z]+)$', '\1_' . sprintf('%02d',$i) . '\2', $tfile);				
			$i++;
		}
		return $nfile;		
	}
	// ============================================================
	// = abs path - add slashes V 1.0, date: 05/10/2005           =
	// ============================================================
	function absPath($path) {		
		if(substr($path,-1)  != '/') $path .= '/';
		if(substr($path,0,1) != '/') $path = '/' . $path;
		return $path;
	}
?>