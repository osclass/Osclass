///////////////////////////////////////////////////
///    JavaScript Form Validation Functions      //
//   by James Heinrich <info@silisoftware.com>   //
//   available at http://www.silisoftware.com   ///
///////////////////////////////////////////////////
///                                              //
//  v1.0.3 - 17 Mar 2005                         //
//    ¤ Added IsValidHexColor() function         //
//                                               //
//  v1.0.2 - 07 Mar 2005                         //
//    ¤ Added CharacterReplace() function        //
//                                               //
//  v1.0.1 - 12 Dec 2004                         //
//    ¤ Added generic MatchesPattern() function  //
//      and made IsValidEmail() and IsValidURL() //
//      work through it                          //
//                                               //
//  v1.0.0 - 11 Dec 2004                         //
//    ¤ Initial public release                   //
//                                              ///
///////////////////////////////////////////////////

// ex: <input type="text" name="Name"  onKeyUp="RemoveInvalidChars(this, '[^A-Za-z0-9 \-]');">
// ex: <input type="text" name="Email" onKeyUp="RemoveInvalidChars(this, '[^A-Za-z0-9 \@\.\-]'); ForceLowercase(this);">
// ex: <input type="submit" value="Submit" onClick="if (!IsValidEmail(theform.email.value)) { alert("Please enter a valid E-mail address"); theform.email.focus(); return false; } else if (!IsValidURL(theform.url.value)) { alert("Please enter a valid URL"); theform.url.focus(); return false; }">

// Note: onChange event will not fire in IE if onKeyUp event returns true, so add a return false, like this:
// <input type="text" onKeyUp="RemoveInvalidChars(this, '[^A-Za-z0-9 \-]'); return false;" onChange="doSomethingElse();">

function RemoveInvalidChars(theinput, pattern) {
	reg = new RegExp(pattern, 'g');
	newstring = theinput.value.replace(reg, '');
	// only update the input if invalid chars have been replaced
	// to avoid annoying behavior (e.g. moving cursor to end of text)
	if (newstring != theinput.value) {
		theinput.value = newstring;
	}
	return true;
}

function ForceUppercase(theinput) {
	// ex: <input type="text" onKeyUp="RemoveInvalidChars(this, '[^A-Za-z0-9 \-]');">
	newstring = theinput.value.toUpperCase();
	// only update the input if invalid chars have been replaced
	// to avoid annoying behavior (e.g. moving cursor to end of text)
	if (newstring != theinput.value) {
		theinput.value = newstring;
	}
	return true;
}

function ForceLowercase(theinput) {
	newstring = theinput.value.toLowerCase();
	// only update the input if invalid chars have been replaced
	// to avoid annoying behavior (e.g. moving cursor to end of text)
	if (newstring != theinput.value) {
		theinput.value = newstring;
	}
	return true;
}

function CharacterReplace(theinput, from, to) {
	newstring = theinput.value.replace(from, to);
	if (newstring != theinput.value) {
		theinput.value = newstring;
	}
	return true;
}

function FormatPhoneNumber(theinput) {
	reg = new RegExp('[^0-9]', 'g');
	numbersonly = theinput.value.replace(reg, '');

	if (numbersonly.length > 7) {
		newstring = '(' + numbersonly.substr(0, 3) + ')' + numbersonly.substr(3, 3) + '-' + numbersonly.substr(6, 4);
	} else if (numbersonly.length > 3) {
		newstring = numbersonly.substr(0, 3) + '-' + numbersonly.substr(3, 4);
	} else {
		newstring = numbersonly;
	}
	// only update the input if the text has changed
	// to avoid annoying behavior (e.g. moving cursor to end of text)
	if (newstring != theinput.value) {
		theinput.value = newstring;
	}
	return true;
}

function MatchesPattern(theString, pattern) {
	// Note: regular expressions passed to this function that have escaped
	// characters also need the escape character escaped, otherwise JavaScript
	// will make it disappear, for example:
	// MatchesPattern(mystring, '\w\.\w');    // won't work
	// MatchesPattern(mystring, '\\w\\.\\w'); // will work
	reg = new RegExp(pattern, 'g');
	return Boolean(reg.exec(theString));
}

function IsValidEmail(emailstring) {
	// regex adapted from http://www.yxscripts.com/fg/form.html
	return MatchesPattern(emailstring, '\\w[\\w\\-\\.]*\\@\\w[\\w\\-]+(\\.[\\w\\-]{2,})+');
}

function IsValidURL(urlstring) {
	return MatchesPattern(urlstring, 'http:\\/\\/[\\w\\-]+(\\.[\\w\\-]+)+');
}

function IsValidHexColor(urlstring) {
	return MatchesPattern(urlstring, '^#[0-9a-zA-Z]{6}$');
}

function SetElementTextColor(theelement, thecolor) {
	if (theelement.style) {
		theelement.style.color = thecolor;
	}
	return true;
}

function HighlightThis(theelement) {
	if (theelement.style) {
		theelement.style.background = '#FFFF00';
	}
	return true;
}

function UnHighlightThis(theelement) {
	if (theelement.style) {
		theelement.style.background = '#FFFFFF';
	}
	return true;
}

function StringPad(string, length, character) {
	while (string.length < length) {
  		string = character + string;
	}
 	return string;
}

function RGB2hex(red, green, blue) {
	r = StringPad(parseInt(red).toString(16),   2, '0');
 	g = StringPad(parseInt(green).toString(16), 2, '0');
	b = StringPad(parseInt(blue).toString(16),  2, '0');
	return r+g+b;
} 