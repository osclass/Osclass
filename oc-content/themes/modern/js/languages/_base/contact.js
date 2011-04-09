$(document).ready(function() {

    /**
     * validate contact form: contact.php
     */
    $('#contact').validate({
		rules: {
            subject: {
                minlength: 3,
                maxlength: 80
            },
            message: {
                required: true,
                minlength: 10,
                maxlength: 2000
            },
            yourName: {
                minlength: 3,
                maxlength: 35
            },
            yourEmail: {
                required: true,
                email: true
            }
		},
		messages: {
            subject: {
                minlength: "<?php echo __('Subject', 'modern') . ": " . sprintf(__("enter at least %d characters", 'modern'), 3); ?>.",
                maxlength: "<?php echo __('Subject', 'modern') . ": " . sprintf(__("no more than %d characters", 'modern'), 80); ?>."
            },
            message: {
                required: "<?php echo __('Message','modern') . ": " . __("this field is required", 'modern'); ?>.",
                minlength: "<?php echo __('Message', 'modern') . ": " . sprintf(__("enter at least %d characters", 'modern'), 10); ?>.",
                maxlength: "<?php echo __('Message', 'modern') . ": " . sprintf(__("no more than %d characters", 'modern'), 2000); ?>."
            },
            yourName: {
                minlength: "<?php echo __('Your name', 'modern') . ": " . sprintf(__("enter at least %d characters", 'modern'), 3); ?>.",
                maxlength: "<?php echo __('Your name', 'modern') . ": " . sprintf(__("no more than %d characters", 'modern'), 35); ?>."
            },
            yourEmail: {
                required: "<?php echo __('Email','modern') . ": " . __("this field is required", 'modern'); ?>.",
                email: "<?php _e("Invalid email address", 'modern'); ?>."
            },
        },
        errorLabelContainer: "#error_list",
        wrapper: "li",
        invalidHandler: function(form, validator) {
            $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
        }
    });
    
});