
/**
 * The simplest form validator class created for the OSClass software.
 * @author OSClass
 */
function FormValidator() {
	// Properties
	this.validations = new Array();

	// Methods
	this.addValidation = function() {
		if(arguments.length < 2) {
			throw "Illegal arguments for the function 'addValidation'.";
		} else {
			var argv = new Array();
			for(var i = 0; i < arguments.length; i++) {
				argv.push(arguments[i]);
			}
			this.validations.push(argv);
		}
	}
	this.logError = function(message) {
		throw "FormValidator: " + message;
	}
	this.run = function() {
		for(var i = 0; i < this.validations.length; i++) {
			var validation = this.validations[i];
			var element = document.getElementById(validation[0]);
			if(element) {
				switch(validation[1]) {
				case FormValidator.TYPE_COMPLETED:
					if(element.value.length == 0) {
						element.style.border = '2px solid red';
						element.focus();
						return false;
					} else {
						element.style.border = '2px solid green';
					}
					break;
				case FormValidator.TYPE_EMAIL:
					if(element.value.length == 0) {
						element.style.border = '2px solid red';
						element.focus();
						return false;
					} else {
						element.style.border = '2px solid green';
					}
					break;
				case FormValidator.TYPE_REGEX:
					var re = new RegExp(validation[2]);
					if(!re.test(element.value)) {
						element.style.border = '2px solid red';
						element.focus();
						return false;
					} else {
						element.style.border = '2px solid green';
					}
					break;
				}
			} else
				this.logError("Element '" + validation[0] + "' not found.");
		}
		return true;
	}
}

// Constants
FormValidator.TYPE_COMPLETED = 0;
FormValidator.TYPE_EMAIL = 1;
FormValidator.TYPE_REGEX = 2;

