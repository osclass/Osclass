// ================================================
// PHP image browser - iBrowser 
// ================================================
// iBrowser - resize dialog to content
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: GPL - see license.txt
// (c)2005 All rights reserved.
// ================================================
// Revision: 1.1                   Date: 18/09/2009
// ================================================
	
	function resizeDialogToContent() {	
		if (window.sizeToContent) { // gecko
			window.sizeToContent();			
			window.scrollTo(0,0);
			window.moveTo(0,0);		
		} else if (!iBrowser.isMSIE) { // safari 
	      	window.resizeTo(600, 500);
	      	var w = 600 - document.body.clientWidth;
	      	var h = 500 - document.body.clientHeight;
	    	window.resizeTo(50, 40);
	    	window.resizeTo(document.body.scrollWidth + w, document.body.scrollHeight + h);
		} else { // IE
			var dw = parseInt(window.dialogWidth);
			var dh = parseInt(window.dialogHeight);				
			if(dw) {				
				difw = dw - this.document.body.clientWidth;
				window.dialogWidth = this.document.body.scrollWidth + difw + 'px';	
				difh = dh - this.document.body.clientHeight;
				window.dialogHeight = this.document.body.scrollHeight + difh + 'px';				
			}
		}
	}
	
//-------------------------------------------------------------------------