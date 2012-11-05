<?php
	// ================================================
	// PHP image browser - iBrowser 
	// ================================================
	// iBrowser dialog - popup
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: GPL - see license.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.0                   Date: 07/15/2006
	// ================================================

	if ( get_magic_quotes_gpc() ) {
		$_GET['url'] = stripslashes($_GET['url']);
	}
	$src   = @$_REQUEST['url'];
	$clTxt = (isset($_REQUEST['clTxt']) ? $_REQUEST['clTxt'] : $cfg['clTxt']);		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="imagetoolbar" content="no" />
<title>iBrowser Popup</title>
<script language="JavaScript" type="text/JavaScript">
	function getRefToDivMod( divID, oDoc ) {
		if( !oDoc ) { oDoc = document; }
		if( document.layers ) {
			if( oDoc.layers[divID] ) { return oDoc.layers[divID]; } else {
				for( var x = 0, y; !y && x < oDoc.layers.length; x++ ) {
					y = getRefToDivNest(divID,oDoc.layers[x].document); }
				return y; } }
		if( document.getElementById ) { return oDoc.getElementById(divID); }
		if( document.all ) { return oDoc.all[divID]; }
		return document[divID];
	}

	function resizeWinTo(idOfDiv) {
		window.focus();
		var oH = getRefToDivMod(idOfDiv);
		if (!oH) {
			return false;
		}
		var oW = oH.clip ? oH.clip.width : oH.offsetWidth;
		var oH = oH.clip ? oH.clip.height : oH.offsetHeight;
		if(!oH) {
			return false;
		}
		var x = window; x.resizeTo( oW + 200, oH + 200 );
		var myW = 0, myH = 0, d = x.document.documentElement, b = x.document.body;
		if( x.innerWidth ) { myW = x.innerWidth; myH = x.innerHeight; }
		else if( d && d.clientWidth ) { myW = d.clientWidth; myH = d.clientHeight; }
		else if( b && b.clientWidth ) { myW = b.clientWidth; myH = b.clientHeight; }
		if( window.opera && !document.childNodes ) { myW += 16; }
		x.resizeTo( oW + ( ( oW + 200 ) - myW ), oH + ( (oH + 200 ) - myH ) );
	}
// ============================================================
// = load/hide message, date: 02/08/2005                      =
// ============================================================
	function hideloadmessage() {
		document.getElementById('dialogLoadMessage').style.display = 'none';
	}
</script>
<style type="text/css">
<!--
body {
	margin: 0px;
	padding: 0px;
	background-color: #efefef;
}
#dialogLoadMessage {
	position:absolute; 
	z-index:1000; 
	display:block;
	width:100%; 
	height:100%;
}
#loadMessage {	
	border:1px solid #cccccc; 
	padding: 10px; 
	width: 150px; 
	color:#666666;
	background-color:#efefef; 
	font-family: verdana,arial,helvetica,sans-serif; 
	font-size:12px; 
	font-weight:bold
}
-->
</style>
</head>
<body onload="resizeWinTo('iDiv'); hideloadmessage();">
<?php 
	include dirname(__FILE__) . '/loadmsg.php';
?>
<div id="iDiv" style="position:absolute; left:0px; top:0px;">
	<img onclick="window.close();" src="<?php echo $src; ?>" border="0" alt="<?php echo $clTxt; ?>" title="<?php echo $clTxt; ?>" style="cursor: pointer;"/></div>
</body>
</html>