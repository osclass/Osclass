// ================================================
// PHP image browser - iBrowser 
// ================================================
// iBrowser - wysiwyg editor interface (IE & Gecko)
// for tinyMCE, FCKeditor, SPAW, Xinha, and HTMLarea
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: GPL - see license.txt
// (c)2005 All rights reserved.
// File: common.js
// ================================================
// Revision: 1.0                   Date: 08/10/2006
// ================================================	
	//=========================================================================
	// common code for all wysiwyg editor interfaces like tinyMCE, FCKeditor,
	// SPAW, Xinha, and HTMLarea
	//=========================================================================
	// initiate iBrowser object	
	function iBrowser() {	
	}
	var ib = new iBrowser;
	//-------------------------------------------------------------------------
	// open iBrowser
	function iBrowser_open() {	
		ib.oEditor.contentWindow.focus();

		var wArgs = {};
		var elm = ib.selectedElement;		
		if (elm != null) { // if element		
			if (elm.nodeName.toLowerCase() == 'img') { // selected object is image				
				var oImageElement   = elm;				
				var oLinkElement    = oImageElement.parentNode.nodeName.toLowerCase() == 'a' ? oImageElement.parentNode : null;
				var oCaptionElement = ib.getNextSiblingByName(oImageElement, 'span');
				ib.getImgParam(oImageElement, wArgs); // set image parameters				)
				if (oLinkElement) { // set popup parameters					
					var oCaptionElement = ib.getNextSiblingByName(oLinkElement, 'span');
					ib.getLinkParam(oLinkElement, wArgs);
				}				
			} else if (elm.nodeName.toLowerCase() == 'a') { // selected object is link
				var oLinkElement    = elm;
				var oImageElement   = oLinkElement.childNodes[0].nodeName.toLowerCase() == 'img' ? oLinkElement.childNodes[0] : null;
				var oCaptionElement = ib.getNextSiblingByName(oLinkElement, 'span');
				ib.getLinkParam(oLinkElement, wArgs); // set popup parameters
				if (oImageElement) { // if first child is image					
					ib.getImgParam(oImageElement, wArgs); // set image parameters
				}
			} else if (elm.nodeName.toLowerCase() == 'span') { // selected element is caption container				
				var oCaptionElement = ib.getChildByName(elm, 'span');
				if (ib.isCaption(oCaptionElement)) { // check to see whether it is a caption element					
					if (elm.childNodes[0].nodeName.toLowerCase() == 'img') {
						var oImageElement = elm.childNodes[0];
						ib.getImgParam(oImageElement, wArgs);
					} else if (elm.childNodes[0].nodeName.toLowerCase() == 'a') {
						var oLinkElement  = elm.childNodes[0];
						var oImageElement = oLinkElement.childNodes[0].nodeName.toLowerCase() == 'img' ? oLinkElement.childNodes[0] : null;
						ib.getLinkParam(oLinkElement, wArgs); // set popup parameters
						if (oImageElement) { // if first child is image							
							ib.getImgParam(oImageElement, wArgs); // set image parameters	
						}
					}					
				} else { // selected element is caption text
					oCaptionElement = elm;
					if (ib.isCaption(oCaptionElement)) { // check to see whether it is a caption element				
						if (oCaptionElement.previousSibling.nodeName.toLowerCase() == 'img') {						
							var oImageElement = oCaptionElement.previousSibling;						
							ib.getImgParam(oImageElement, wArgs);
						} else if (oCaptionElement.previousSibling.nodeName.toLowerCase() == 'a') {
							var oLinkElement = oCaptionElement.previousSibling;
							var oImageElement = oLinkElement.childNodes[0].nodeName.toLowerCase() == 'img' ? oLinkElement.childNodes[0] : null;
							ib.getLinkParam(oLinkElement, wArgs); // set popup parameters
							if (oImageElement) { // if first child is image						
								ib.getImgParam(oImageElement, wArgs); // set image parameters					
							}
						}
					}
				}
			}
			//-------------------------------------------------------------------------
			// set caption argument				
			ib.isCaption(oCaptionElement) ? wArgs.caption = 1 : '';
			ib.isCaption(oCaptionElement) ? wArgs.captionClass = oCaptionElement.parentNode.attributes['class'].value : '';
		}		
		//-------------------------------------------------------------------------
		// open iBrowser dialog		
		if (ib.isMSIE) { // IE
			var rArgs = showModalDialog(ib.baseURL, wArgs, 'dialogHeight:500px; dialogWidth:580px; scrollbars: no; menubar: no; toolbar: no; resizable: no; status: no;');  
			if (rArgs) { // returning from iBrowser (IE) and calling callback function				
				iBrowser_callback('','',rArgs);
			}
		} else if (ib.isGecko || ib.isWebKit ) { // Gecko or WebKit 
			var wnd = window.open(ib.baseURL + '?editor=' + ib.editor + '&callback=iBrowser_callback', 'ibrowser', 'status=no, modal=yes, width=625, height=530');
			wnd.dialogArguments = wArgs;
		}
	}
	//-------------------------------------------------------------------------
	// iBrowser callback
	function iBrowser_callback(editor, sender, iArgs) {		
		if (iArgs) { // IE			
			var rArgs = iArgs;
		} else { // Gecko
			var rArgs = sender.returnValue;
		}
		ib.oEditor.contentWindow.focus();
		var elm = ib.selectedElement;	
		if (elm != null) {
			if (elm.nodeName.toLowerCase() == 'img') { // is current cell a image ?
				var oImageElement = elm;
			}
			if (elm.nodeName.toLowerCase() == 'a') { // is current cell a link ?
				var oLinkElement = elm;
			}
			if (elm.nodeName.toLowerCase() == 'span') {
				if (elm.childNodes[0] && elm.childNodes[0].nodeName.toLowerCase() == 'img') { // caption container				
					var oImageElement = elm.childNodes[0];					
				} else if (elm.previousSibling && elm.previousSibling.nodeName.toLowerCase() == 'img') { // caption text				
					var oImageElement = elm.previousSibling;					
				}
			}
		}
		
		if (rArgs) {
			if (!rArgs.action) { // no action set - image				
				if (!oImageElement) { // new image// no image - create new image
					ib.oEditor.contentWindow.document.execCommand('insertimage', false, rArgs.src);
					oImageElement = ib.getElementByAttributeValue(ib.oEditor.contentWindow.document, 'img', 'src', rArgs.src);		
				}
				
				// set image attributes
				ib.setAttrib(oImageElement, 'src', rArgs.src, true);				
				ib.setAttrib(oImageElement, 'alt', rArgs.alt, true);
				ib.setAttrib(oImageElement, 'title', rArgs.title, true);
				ib.setAttrib(oImageElement, 'align', rArgs.align, true);
				ib.setAttrib(oImageElement, 'border', rArgs.border);
				ib.setAttrib(oImageElement, 'hspace', rArgs.hspace);
				ib.setAttrib(oImageElement, 'vspace', rArgs.vspace);
				ib.setAttrib(oImageElement, 'width', rArgs.width);
				ib.setAttrib(oImageElement, 'height', rArgs.height);				
				ib.isMSIE ? ib.setAttrib(oImageElement, 'className', rArgs.className, true) : ib.setAttrib(oImageElement, 'class', rArgs.className, true);
				
				// set caption
				if (oImageElement.parentNode.nodeName.toLowerCase() == 'a') { // popup image
					var oLinkElement = oImageElement.parentNode;
					ib.setCaption(oLinkElement,rArgs.caption,oImageElement.getAttribute('title'),rArgs.captionClass);
				} else {
					ib.setCaption(oImageElement,rArgs.caption,oImageElement.getAttribute('title'),rArgs.captionClass);
				} 
			} else if (rArgs.action == 1) { // action set - image popup								
				if (oLinkElement) { // edit exiting popup link								
					a.href        = "javascript:void(0);";
					rArgs.popSrc  = escape(rArgs.popSrc);					
					ib.setAttrib(oLinkElement, 'title', rArgs.popTitle, true);
					ib.isMSIE ? ib.setAttrib(oLinkElement, 'className', rArgs.popClassName, true) : ib.setAttrib(oLinkElement, 'class', rArgs.popClassName, true);										
       				ib.setAttrib(oLinkElement, 'onclick', "window.open('" + rArgs.popUrl + "?url=" + rArgs.popSrc + '&clTxt=' + rArgs.popTxt + "','Image', 'width=500, height=300, scrollbars=no, toolbar=no, location=no, status=no, resizable=yes, screenX=100, screenY=100'); return false;", true);
				} else { // create new popup link		
					var oLinkElement = ib.oEditor.contentWindow.document.createElement('A');
					oLinkElement.href = "javascript:void(0)";
					rArgs.popSrc  = escape(rArgs.popSrc);				
					ib.setAttrib(oLinkElement, 'title', rArgs.popTitle, true);
					ib.isMSIE ? ib.setAttrib(oLinkElement, 'className', rArgs.popClassName, true) : ib.setAttrib(oLinkElement, 'class', rArgs.popClassName, true);										
					ib.setAttrib(oLinkElement, 'onclick', "window.open('" + rArgs.popUrl + "?url=" + rArgs.popSrc + '&clTxt=' + rArgs.popTxt + "','Image', 'width=500, height=300, scrollbars=no, toolbar=no, location=no, status=no, resizable=yes, screenX=100, screenY=100'); return false;", true);
					
					if (ib.isMSIE) { // IE
						 if (elm.nodeName.toLowerCase() == 'span' || elm.nodeName.toLowerCase() == 'img') {								
							if (elm.lastChild && elm.lastChild.className == 'caption') {
								oLinkElement.innerHTML = elm.firstChild.outerHTML;
								elm.firstChild.outerHTML = oLinkElement.outerHTML;
							} else if (elm.nodeName.toLowerCase() == 'img') {
								oLinkElement.innerHTML = elm.outerHTML;
								elm.outerHTML = oLinkElement.outerHTML;
							} else if (elm.nodeName.toLowerCase() == 'span' && elm.className == 'caption') {
								elm = elm.previousSibling;
								oLinkElement.innerHTML = elm.outerHTML;
								elm.outerHTML = oLinkElement.outerHTML;
							}
						} else {
							var rng = ib.oEditor.contentWindow.document.selection.createRange();
							if (rng.text == '') {								
								oLinkElement.innerHTML = '#';
							} else {
								oLinkElement.innerHTML = rng.htmlText;								
							}
                      		rng.pasteHTML(oLinkElement.outerHTML);							
						}
					} else if (ib.isGecko) { // Gecko
						var sel = ib.oEditor.contentWindow.getSelection();						
						if (sel.rangeCount > 0 && sel.getRangeAt(0).startOffset != sel.getRangeAt(0).endOffset) {
							oLinkElement.appendChild(sel.getRangeAt(0).cloneContents());
						} else {							
							oLinkElement.innerHTML = '#';
						}        
						ib.insertNodeAtSelection(ib.oEditor.contentWindow, oLinkElement);
					}
				}
			//-------------------------------------------------------------------------
			} else if (rArgs.action == 2) { // action set - delete popup link				
				ib.oEditor.contentWindow.document.execCommand('Unlink');
			}
		}
		return;
  	}	
	//-------------------------------------------------------------------------
	// set image attributes
	iBrowser.prototype.getImgParam = function (oImageElement, wArgs) {
		var tsrc = oImageElement.src;
		if (tsrc.lastIndexOf('?') >= 0) { // dynamic thumbnail or random image				
			var str = tsrc.substring(tsrc.lastIndexOf('?')+1, tsrc.length);
			firstIndexOf   	= str.indexOf('&');
			if (tsrc.lastIndexOf('?src') >= 0) {
				wArgs.src  	= str.substring(4, firstIndexOf); // image part of src
				wArgs.tsrc 	= tsrc; // full src incl. dynamic parameters
			} else if (tsrc.lastIndexOf('?dir') >= 0) { // random image				
				wArgs.rsrc 	= tsrc; // full url
				wArgs.rlib 	= str.substring(4,firstIndexOf); // image library for random picture
				wArgs.rset 	= str.substring(firstIndexOf, str.lenght); // random parameter string
			}
		} else { // regular image
			wArgs.src = tsrc;
		}
		
		wArgs.alt 			= oImageElement.alt;
		wArgs.title 		= oImageElement.title;
		if (!wArgs.rsrc) { // if not random picture
			wArgs.width 	= oImageElement.style.width  ? parseInt(oImageElement.style.width)  : oImageElement.width;
			wArgs.height 	= oImageElement.style.height ? parseInt(oImageElement.style.height) : oImageElement.height;
		}
		wArgs.border 		= oImageElement.border;
		wArgs.align 		= oImageElement.align;
		if (oImageElement.hspace >= 0) { // (-1 when not set under gecko for some reason)
			wArgs.hspace    = oImageElement.attributes['hspace'].nodeValue;
		}
		if (oImageElement.vspace >= 0) { // // (-1 when not set under gecko for some reason)
			wArgs.vspace    = oImageElement.attributes['vspace'].nodeValue;
		}	
		wArgs.className 	= oImageElement.className;	
		return wArgs;		
	}
	//-------------------------------------------------------------------------
	// set popup link attributes
	iBrowser.prototype.getLinkParam = function (oLinkElement, wArgs) {		
		wArgs.a = oLinkElement;
		var str = oLinkElement.getAttribute('onclick') ? oLinkElement.attributes['onclick'].value : oLinkElement.attributes['mce_onclick'].value;		
		wArgs.popSrc = unescape(str.substring(str.indexOf('?url=')+5, str.indexOf('&')));	// popup image src			
		wArgs.popTitle     = oLinkElement.title;
		wArgs.popClassName = oLinkElement.className;
		return wArgs;
	}
	//-------------------------------------------------------------------------
	// set image caption
	iBrowser.prototype.setCaption = function (elm,chkCaption, caption, captionClass) {
		if (chkCaption == 1) { // set caption
			var doc = ib.oEditor.contentWindow.document;
			if (elm.nextSibling && elm.nextSibling.className == 'caption') { // existing caption
				var capDiv = elm.parentNode;
				var newtext = elm.nextSibling.firstChild.nodeValue.replace(elm.nextSibling.firstChild.nodeValue, caption); 				// change caption text
				ib.isMSIE ? ib.setAttrib(capDiv, 'className', captionClass, true) : ib.setAttrib(capDiv, 'class', captionClass, true); 	// change class
				elm.nextSibling.firstChild.nodeValue = newtext;
			} else { // new caption					
				var capDiv = doc.createElement('span');
				var capText = doc.createElement('span');
				capText.appendChild(doc.createTextNode(caption));
				if (ib.isMSIE) { // IE
					ib.setAttrib(capDiv, 'className', captionClass, true); 	// set class for caption container
					ib.setAttrib(capText, 'className', 'caption', true); 	// set class for caption text
					capDiv.innerHTML = elm.outerHTML;
					capDiv.appendChild(capText);  
					elm.outerHTML = capDiv.outerHTML;
				} else if (ib.isGecko) { // Gecko
					ib.setAttrib(capDiv, 'class', captionClass, true);		// set class for caption container
					ib.setAttrib(capText, 'class', 'caption', true);		// set class for caption text	
					var sel = ib.oEditor.contentWindow.getSelection();						
					capDiv.appendChild(capText);
					capDiv.insertBefore(elm,capDiv.firstChild);
					ib.insertNodeAtSelection(ib.oEditor.contentWindow, capDiv);
				}
			}
		} else { // no caption set - if caption, remove it        
			if (elm.nextSibling && elm.nextSibling.className == 'caption') {                                        
				var parent = elm.parentNode;                                
				parent.parentNode.replaceChild(elm, elm.parentNode);                                
			};
		}
	}
	//-------------------------------------------------------------------------
	// check if caption
	iBrowser.prototype.isCaption = function (elm) {
		if (elm && elm.className == 'caption') {
			return true;
		}
		return false;
	}
	//-------------------------------------------------------------------------
	// get selected element (focus element)
	iBrowser.prototype.getSelectedElement = function () {		
		if (ib.isMSIE) {
			var sel = ib.oEditor.contentWindow.document.selection;
			var rng = sel.createRange();		
			if (sel.type != 'Control') {
				return rng.parentElement();
			} else {
				return rng(0);    
			}
		} else if (ib.isGecko) {
			var elm = null;
			var sel = ib.oEditor.contentWindow.getSelection();		
			if (sel && sel.rangeCount > 0) {
				var rng = sel.getRangeAt(0);
				elm = rng.startContainer;
				if (elm.nodeType != 1) {
					elm = elm.parentNode;
				}
			}
			return elm;
		}
	}
	//-------------------------------------------------------------------------
	// get element by attribute value
	iBrowser.prototype.getElementByAttributeValue = function (node, element_name, attrib, value) {
		var elements = ib.getElementsByAttributeValue(node, element_name, attrib, value);
		if (elements.length == 0) {
			return null;
		}
		return elements[0];
	};
	//-------------------------------------------------------------------------
	// get elements by attribute value
	iBrowser.prototype.getElementsByAttributeValue = function (node, element_name, attrib, value) {
		var elements = new Array();
		if (node && node.nodeName.toLowerCase() == element_name) {
			if (node.getAttribute(attrib) && node.getAttribute(attrib).indexOf(value) != -1) {
				elements[elements.length] = node;
			}
		}
	
		if (node.hasChildNodes) {
			for (var x=0, n=node.childNodes.length; x<n; x++) {
				var childElements = ib.getElementsByAttributeValue(node.childNodes[x], element_name, attrib, value);
				for (var i=0, m=childElements.length; i<m; i++) {
					elements[elements.length] = childElements[i];
				}
			}
		}
		return elements;
	};
	//-------------------------------------------------------------------------
	// set attributes
	iBrowser.prototype.setAttrib = function (element, name, value, fixval) {
		if (!fixval && value != null) {
			var re = new RegExp('[^0-9%]', 'g');
			value = value.replace(re, '');
		}
		if (value != null && value != '') {
			element.setAttribute(name, value);
		} else {
			element.removeAttribute(name);
		}
	}
	//-------------------------------------------------------------------------
	// insert node at selection
	iBrowser.prototype.insertNodeAtSelection = function (win, insertNode) { // Gecko		  
		var sel   = win.getSelection(); // get current selection
	  	var range = sel.getRangeAt(0); // get the first range of the selection -(there's almost always only one range)
		sel.removeAllRanges(); // deselect everything
		range.deleteContents(); // remove content of current selection from document
	  	var container = range.startContainer; // get location of current selection
	  	var pos = range.startOffset;
		range   = document.createRange(); // make a new range for the new selection
	
		if (container.nodeType == 3 && insertNode.nodeType == 3) {	
			container.insertData(pos, insertNode.nodeValue); // if we insert text in a textnode, do optimized insertion
			range.setEnd(container, pos+insertNode.length); // put cursor after inserted text
			range.setStart(container, pos+insertNode.length);	
		} else {	
			var afterNode;
			if (container.nodeType == 3 ) { // text node
				  // when inserting into a textnode, we create 2 new textnodes and put the insertNode in between
				var textNode   = container;
				container      = textNode.parentNode;
				var text       = textNode.nodeValue;
				var textBefore = text.substr(0,pos); // text before the split
				var textAfter  = text.substr(pos); // text after the split
				var beforeNode = document.createTextNode(textBefore);
				var afterNode  = document.createTextNode(textAfter);
				  
				container.insertBefore(afterNode, textNode); // insert the 3 new nodes before the old one
				container.insertBefore(insertNode, afterNode);
				container.insertBefore(beforeNode, insertNode);
				container.removeChild(textNode); // remove the old node
	
			} else {	
				afterNode = container.childNodes[pos]; // else simply insert the node
				container.insertBefore(insertNode, afterNode);
			}
	
			range.setEnd(afterNode, 0);
			range.setStart(afterNode, 0);
		}
		  	sel.addRange(range);
		  	win.getSelection().removeAllRanges(); // remove all ranges
	}
	//-------------------------------------------------------------------------	
	// get next sibling element by name
	iBrowser.prototype.getNextSiblingByName = function (node, name) {
		while ((node = node.nextSibling) != null) {
			if (node.nodeName.toLowerCase() == name) {
				return node;
			}
		}
		return null;
	}
	//-------------------------------------------------------------------------	
	// get child element by name
	iBrowser.prototype.getChildByName = function (node, name) {
		var nodes = node.childNodes;
		for (var i=0; i<nodes.length; i++) {
			if (nodes[i].nodeName.toLowerCase() == name) {
				return nodes[i];
			}
		}
		return null;
	}
	//-------------------------------------------------------------------------	