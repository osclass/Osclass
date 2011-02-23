/**
 * jquery.strengthy.js
 *
 * JavaScript
 *
 * jQuery password strength plugin
 *
 * @author    Lupo Montero <lupo@e-noise.com>
 * @copyright 2010 E-NOISE.COM LIMITED
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @license   http://www.gnu.org/licenses/gpl-3.0.txt GPL v3 License
 */

/*jslint eqeqeq: true */

(function (jQuery) {

var defSettings = {
    minLength: 8,
    showToggle: true,
    errorClass: 'strengthy-error',
    errorrClass: 'strengthy-errorr',
    errorrrClass: 'strengthy-errorrr',
    errorrrrClass: 'strengthy-errorrrr',
    validClass: 'strengthy-valid',
    showMsgs: true,
    require: {
        numbers: true,
        upperAndLower: true,
        symbols: true
    },
    msgs: [
        'Poor password.',
        'Weak password.',
        'Medium password.',
        'Strong password.',
        'Super-strong password.',
        'Show password'
    ]
};

var createMsgHandler = function (settings, obj, msgContainer) {
    return function (msg, className) {
        obj.attr('title', msg);

        if (settings.showMsgs) {
            msgContainer.attr('class', className).html(msg);
        }
    };
};

var createStrengthChecker = function (settings) {
    var tests = [
        { name: 'numbers', regex: /\d/, msg: settings.msgs[1] },
        { name: 'upperAndLower', regex: /([a-z].*[A-Z]|[A-Z].*[a-z])/, msg: settings.msgs[2] },
        { name: 'symbols', regex: /[^a-zA-Z0-9]/, msg: settings.msgs[3] }
    ];

    return function (obj, displayMsg) {
        var pass = obj.val();
        var score = 0;
        var testCount = 0;
        var i;

        obj.removeClass(settings.validClass);

        if (pass.length < +settings.minLength) {
            //displayMsg(settings.msgs[0], settings.errorClass);
            //return false;
        } else { score +=1; }

        for (i=0; i<tests.length; i++) {
            if (settings.require[tests[i].name] !== true) {
                continue;
            }

            testCount++;

            if (tests[i].regex.test(pass)) {
                score += 1;
            } else {
                //displayMsg(tests[i].msg, settings.errorClass);
            }
        }

        if (score >= 4) {
            displayMsg(settings.msgs[score], settings.validClass);
            obj.addClass(settings.validClass);
        } else if (score==3){
            displayMsg(settings.msgs[score], settings.errorrrrClass);
        } else if (score==2){
            displayMsg(settings.msgs[score], settings.errorrrClass);
        } else if (score==1){
            displayMsg(settings.msgs[score], settings.errorrClass);
        } else {
            displayMsg(settings.msgs[score], settings.errorClass);
        }
    };
};

// Augment the jQuery object with the password strength plugin
jQuery.fn.strengthy = function (options) {
    var settings = jQuery.extend(defSettings, options);
    var checkStrength = createStrengthChecker(settings);

    // Add listener on keyup event for all selected nodes
    return this.each(function () {
        var obj = jQuery(this);
        var nodeName = obj.attr('name');
        var msgContainer;
        var displayMsg;
        var plainInput;

        obj.after('<span id="strengthy-msg-' + nodeName + '"><\/span>');
        msgContainer = jQuery('#strengthy-msg-' + nodeName);
        displayMsg = createMsgHandler(settings, obj, msgContainer);

        if (settings.showToggle) {
            obj.before('<input type="text" id="strengthy-show-toggle-plain-' + nodeName + '" style="display: none;" />');
            plainInput = jQuery('#strengthy-show-toggle-plain-' + nodeName);

            plainInput.keyup(function () {
                obj.val(plainInput.val()).keyup();
            });

            jQuery('#strengthy-show-toggle-' + nodeName).click(function () {
                if (obj.css('display') === 'none') {
                    obj.css('display', 'inline');
                    plainInput.css('display', 'none');
                } else {
                    obj.css('display', 'none');
                    plainInput.css('display', 'inline');
                }
            });
        }

        obj.keyup(function () {
            if (plainInput.length > 0) {
                plainInput.val(obj.val());
            }

            checkStrength(obj, displayMsg);
        });
    });
};

})(jQuery);

