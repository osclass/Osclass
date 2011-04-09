$(document).ready(function() {

    /**
     * validate item form
     */
     
    // Validate description without HTML.
    $.validator.addMethod(
        "minstriptags", 
        function(value, element) { 
            altered_input = strip_tags(value);
            if (altered_input.length < 10) {
                return false;
            } else {
                return true;
            }
        }
    );
    
    // cache
    var $item_Form = $('div.content form') ;
    
    // validate fields in each locale
    $item_Form.find('button').click(function() {
    
        // how many locales?
        lang_count = $item_Form.find('div.title input').length;
        
        // loop for each locale
        $item_Form.find('div.title input').each(function()
        {
            // grab current name and locale
            lang_name = $(this).parent().prev('h2').text().replace(/^(.+) \((.+)\)$/, '$1');
            lang_locale = $(this).attr('name').replace(/^title\[(.+)\]$/,'$1');
            
            // prepend lang when there are multiple locales
            str = (lang_count > 1) ? "[" + lang_name + "] " : '';

            // title
            $(this).rules("add", {
                required: true,
                minlength: 9,
                maxlength: 80,
                messages: {
                    required: str + "<?php _e("Title",'modern'); ?>"  + ": " + "<?php _e("this field is required",'modern'); ?>.",
                    minlength: str +  "<?php _e("Title",'modern'); ?>" + ": " + "<?php printf(__("enter at least %d characters",'modern'), 9); ?>.",
                    maxlength: str + "<?php _e("Title",'modern'); ?>" + ": " +  "<?php printf(__("no more than %d characters",'modern'), 80); ?>."
                }
            });
            
            // description
            $item_Form.find('#description\\[' + lang_locale + '\\]').rules("add", {
                required: true,
                minlength: 10,
                maxlength: 5000,
                'minstriptags': true,
                messages: {
                    required: str + "<?php _e("Description",'modern'); ?>" + ": " + "<?php _e("this field is required",'modern'); ?>.",
                    minlength: str + "<?php _e("Description",'modern'); ?>" + ": " + "<?php _e("needs to be longer",'modern'); ?>.",
                    maxlength: str + "<?php _e("Description",'modern'); ?>" + ": " + "<?php printf(__("no more than %d characters",'modern'), 5000); ?>.",
                    "minstriptags": str + "<?php _e("Description",'modern'); ?>" + ": " + "<?php _e("needs to be longer",'modern'); ?>."
                }
            });
        });
    });
    
    // Code for form validation
    $item_Form.validate({
        rules: {
            catId: {
                required: true,
                digits: true
            },
<?php if(osc_price_enabled_at_items()) { ?>
            price: {
                number: true,
                maxlength: 15
            },
            currency: "required",
<?php } ?>
<?php if(osc_images_enabled_at_items()) { ?>
            "photos[]": {
                accept: "<?php echo osc_allowed_extension(); ?>"
            },
<?php } ?>
            contactName: {
                minlength: 3,
                maxlength: 35
            },
            contactEmail: {
                required: true,
                email: true
            },
            countryId: {
                required: true,
                minlength: 2
            },
            regionId: {
                required: true,
                digits: true
            },
            cityId: {
                required: true,
                digits: true
            },
            cityArea: {
                minlength: 3,
                maxlength: 35
            },
            address: {
                minlength: 5,
                maxlength: 50
            }
        },
        messages: {
            catId: "<?php printf(__("Select a %s",'modern'), __("category",'modern')); ?>.",
<?php if(osc_price_enabled_at_items()) { ?>
            price: {
                number: "<?php echo __("Price",'modern') . ": " . __("must be a number",'modern'); ?>.",
                maxlength: "<?php echo __("Price",'modern') . ": " . sprintf(__("no more than %d characters",'modern'), 15); ?>."
            },
            currency: "<?php printf(__("Select a %s",'modern'), __("currency",'modern')); ?>.",
<?php } ?>
<?php if(osc_images_enabled_at_items()) { ?>
            "photos[]": {
                accept: "<?php echo __("Photo",'modern') . ": " . sprintf( __("must be %s",'modern'), str_replace( array(" ", ","), array("", ", "), osc_allowed_extension() ) ); ?>."
            },
<?php } ?>
            contactName: {
                minlength: "<?php echo __("Name",'modern') . ": " . sprintf(__("enter at least %d characters",'modern'), 3); ?>.",
                maxlength: "<?php echo __("Name",'modern') . ": " . sprintf(__("no more than %d characters",'modern'), 35); ?>."
            },
            contactEmail: {
                required: "<?php echo __("Email",'modern') . ": " . __("this field is required",'modern'); ?>.",
                email: "<?php _e("Invalid email address",'modern'); ?>."
            },
            countryId: "<?php printf(__("Select a %s",'modern'), __("country",'modern')); ?>.",
            regionId: "<?php printf(__("Select a %s",'modern'), __("region",'modern')); ?>.",
            cityId: "<?php printf(__("Select a %s",'modern'), __("city",'modern')); ?>.",
            cityArea: {
                minlength: "<?php echo __("City Area",'modern') . ": " . sprintf(__("enter at least %d characters",'modern'), 3); ?>.",
                maxlength: "<?php echo __("City Area",'modern') . ": " . sprintf(__("no more than %d characters",'modern'), 35); ?>."
            },
            address: {
                minlength: "<?php echo __("Address",'modern') . ": " . sprintf(__("enter at least %d characters",'modern'), 5); ?>.",
                maxlength: "<?php echo __("Address",'modern') . ": " . sprintf(__("no more than %d characters",'modern'), 50); ?>."
            }
        },
        errorLabelContainer: "#error_list",
        wrapper: "li",
        invalidHandler: function(form, validator) {
            $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
        }
    });
});

/**
 * Strip HTML tags to count number of visible characters.
 */
function strip_tags(html) {
    if (arguments.length < 3) {
        html=html.replace(/<\/?(?!\!)[^>]*>/gi, '');
    } else {
        var allowed = arguments[1];
        var specified = eval("["+arguments[2]+"]");
        if (allowed){
            var regex='</?(?!(' + specified.join('|') + '))\b[^>]*>';
            html=html.replace(new RegExp(regex, 'gi'), '');
        } else{
            var regex='</?(' + specified.join('|') + ')\b[^>]*>';
            html=html.replace(new RegExp(regex, 'gi'), '');
        }
    }
    return html;
}