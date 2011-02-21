/**
 * jQuery Plugin Autosave
 *
 * @author Raymond Julin (raymond[dot]julin[at]gmail[dot]com)
 * @author Mads Erik Forberg (mads[at]hardware[dot]no)
 * @author Simen Graaten (simen[at]hardware[dot]no)
 *
 * Licensed under the MIT License
 *
 * Usage: 
 * $("input.autosave").autosave({ 
 *     url: url, // Defaults to parent form url or window.location.href
 *     method: "post",  // Defaults to parent form url or get
 *     grouped: true, // Defaults to false. States whether all selected fields should be sent in the request or only the one it was triggered upon
 *     success: function(data) { 
 *         console.log(data); 
 *     },
 *     send: function(eventTriggeredByNode) { 
 *         // Do stuff while we wait for the ajax response, defaults to doing nothing
 *         console.log("Saving");
 *     },
 *     error: function(xmlReq, text, errorThrown) { 
 *         // Handler if the ajax request fails, defaults to console.log-ing the ajax request scope
 *         console.log(text);
 *     },
 *     dataType: "json" // Defaults to JSON, but can be XML, HTML and so on
 * });
 *
 * $("form#myForm").autosave(); // Submits entire form each time one of the 
 *                              // elements are changed, except buttons and submits
 *
 *
 * Todo:
 * - Support timed autosave for textareas
 */

(function($) {
    $.fn.autosave = function(options) {
        /**
         * Define some needed variables
         * elems is a shortcut for the selected nodes
         * nodes is another shortcut for elems later (wtf)
         * eventName will be used to set what event to connect to
         */
        var elems = $(this), nodes = $(this), eventName;

        options = $.extend({
            grouped: false,
            send: false, // Callback
            error: false, // Callback
            success: false, // Callback
            dataType: "json" // From ajax return point
        }, options);
        
        /**
         * If the root form is used as selector
         * bind to its submit and find all its
         * input fields and bind to them
         */
        if ($(this).is('form')) {
            /* Group all inputelements in this form */
            options.grouped = true;
            elems = nodes = $(this).find(":input,button,textarea");
            // Bind to forms submit
            /*$(this).bind('submit', function(e) {
                e.preventDefault();
                $.fn.autosave._makeRequest(e, nodes, options, $(this));
            });*/
        }
        /**
         * For each element selected (typically a list of form elements
         * that may, or may not, reside in the same form
         * Build a list of these nodes and bind them to some
         * onchange/onblur events for submitting
         */
        $(this).everyTime(30000, function(){$.fn.autosave._makeRequest(false, nodes, options, this)});
        /*elems.each(function(i) {
            eventName = $(this).is('button,:submit') ? 'click' : 'change';
            $(this).bind(eventName, function (e) {
                eventName == 'click' ? e.preventDefault() : false;
                //$(this).everyTime(5000, function(){$.fn.autosave._makeRequest(e, nodes, options, this)});
            });
        });*/


        return $(this);
    }
    
    /**
     * Actually make the http request
     * using previously supplied data
     */
    $.fn.autosave._makeRequest = function(e, nodes, options, actsOn) {
        // Keep variables from global scope

        var vals = {}, form;
        /**
         * Further set default options that require
         * to actually inspect what node autosave was triggered upon
         * Defaults:
         *  -method: post
         *  -url: Will default to parent form if one is found,
         *        if not it will use the current location
         */
        form = $(actsOn).is('form') ? $(actsOn) : $(actsOn.form);
        options = $.extend({
            url: (form.attr('action'))? form.attr('action') : window.location.href,
            method: (form.attr('method')) ? form.attr('method') : "post"
        }, options);

        /**
         * If options.grouped is true we collect every
         * value from every node
         * But if its false we should only push
         * the one element we are acting on
         */
        if (options.grouped) {
            nodes.each(function (i) {
                /**
                 * Do not include button and input:submit as nodes to 
                 * send, EXCEPT if the button/submit was the explicit
                 * target, aka it was clicked
                 */
                if (!$(this).is('button,:submit') || e.currentTarget == this) {
                    if ($(this).is(':radio') && $(this).attr('checked')==false)
                        return;
                    vals[this.name] = $(this).is(':checkbox') ? 
                        $(this).attr('checked') : 
                        $(this).val();
                }
            });
        }
        else {
            vals[actsOn.name] = $(actsOn).is(':checkbox') ? 
                $(actsOn).attr('checked') : 
                $(actsOn).val();
        }
        /**
         * Perform http request and trigger callbacks respectively
         */
        // Callback triggered when ajax sending starts
        options.send ? options.send($(actsOn)) : false;
        $.ajax({
            type: options.method,
            data: vals,
            url: options.url,
            dataType: options.dataType,
            success: function(resp) {
                options.success ? options.success(resp) : false;
            },
            error: function(resp) {
                options.error ? options.error(resp) : false;
            }
        });
    }
})(jQuery);

/**
 * A default (example) of a visualizer you can use that will
 * put a neat loading image in the nearest <legend>
 * for the element/form you were autosaving.
 * Notice: No default "remover" of this spinner exists
 */
defaultAutosaveSendVisualizer = function(node) {
    var refNode;
    if (node.is('form'))
        refNode = $(node).find('legend');
    else
        refNode = $(node).parent('fieldset').find('legend');
    // Create spinner
    var spinner = $('<img src="spin.gif" />').css({
        'position':'relative',
        'margin-left':'10px',
        'height': refNode.height(),
        'width': refNode.height()
    });
    spinner.appendTo(refNode);
}
