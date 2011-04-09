$(document).ready(function() {
    /**
     * validate login form: user-login.php
     */
    $('#user-login').validate({
        rules: {
            email: {
                required: true,
                email: true 
            },
            password: "required"
        },
        messages: {
            email: {
                required: "<?php echo __('Email','modern') . ": " . __("this field is required", 'modern'); ?>.",
                email: "<?php _e("Invalid email address", 'modern'); ?>."
            },
            password: "<?php echo __('Password','modern') . ": " . __("this field is required", 'modern'); ?>."
        },
        errorLabelContainer: "#error_list",
        wrapper: "li",
        invalidHandler: function(form, validator) {
            $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
        }
    });
    
    
    /**
     * validate recover password form: user-recover.php
     */
    $('#user-recover').validate({
		rules: {
			s_email: {
				required: true,
				email: true
			}
		},
		messages: {
            s_email: {
                required: "<?php echo __('Email','modern') . ": " . __("this field is required", 'modern'); ?>.",
                email: "<?php _e("Invalid email address", 'modern'); ?>."
            }
        },
        errorLabelContainer: "#error_list",
        wrapper: "li",
        invalidHandler: function(form, validator) {
            $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
        }
    });
    
    
    /**
     * validate new password form: user-forgot_password.php
     */
    $('#user-recover-change').validate({
		rules: {
			new_password: {
				required: true,
				maxlength: 25
			},
			new_password2: {
				required: true,
				maxlength: 25,
				equalTo: "#new_password"
			}
		},
		messages: {
            new_password: {
                required: "<?php echo __('New password','modern') . ": " . __("this field is required", 'modern'); ?>.",
                maxlength: "<?php echo __('New password', 'modern') . ": " . sprintf(__("no more than %d characters", 'modern'), 25); ?>."
            },
            new_password2: {
                required: "<?php echo __('Re-type password','modern') . ": " . __("this field is required", 'modern'); ?>.",
                maxlength: "<?php echo __('Re-type password', 'modern') . ": " . sprintf(__("no more than %d characters", 'modern'), 25); ?>.",
                equalTo: "<?php _e("The passwords don't match", 'modern'); ?>."
            }
        },
        errorLabelContainer: "#error_list",
        wrapper: "li",
        invalidHandler: function(form, validator) {
            $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
        }
    });
    
    
    /**
     * validate register form: user-register.php
     */
    $('#user-register').validate({
		rules: {
            s_name: {
                required: true,
                minlength: 3,
                maxlength: 35
            },
			s_password: {
				required: true,
				maxlength: 25
			},
			s_password2: {
				required: true,
				maxlength: 25,
				equalTo: "#s_password"
			},
			s_email: {
				required: true,
				email: true
			}
		},
		messages: {
            s_name: {
                required:  "<?php echo __('Name', 'modern') . ": " . __("this field is required", 'modern'); ?>.",
                minlength: "<?php echo __('Name', 'modern') . ": " . sprintf(__("enter at least %d characters", 'modern'), 3); ?>.",
                maxlength: "<?php echo __('Name', 'modern') . ": " . sprintf(__("no more than %d characters", 'modern'), 35); ?>."
            },
            s_password: {
                required: "<?php echo __('Password','modern') . ": " . __("this field is required", 'modern'); ?>.",
                maxlength: "<?php echo __('Password', 'modern') . ": " . sprintf(__("no more than %d characters", 'modern'), 25); ?>."
            },
            s_password2: {
                required: "<?php echo __('Re-type password','modern') . ": " . __("this field is required", 'modern'); ?>.",
                maxlength: "<?php echo __('Re-type password', 'modern') . ": " . sprintf(__("no more than %d characters", 'modern'), 25); ?>.",
                equalTo: "<?php _e("The passwords don't match", 'modern'); ?>."
            },
            s_email: {
                required: "<?php echo __('Email','modern') . ": " . __("this field is required", 'modern'); ?>.",
                email: "<?php _e("Invalid email address", 'modern'); ?>."
            }
        },
        errorLabelContainer: "#error_list",
        wrapper: "li",
        invalidHandler: function(form, validator) {
            $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
        }
    });
    
    
    /**
     * validate profile form: user-profile.php
     */
    $('#user-profile').validate({
		rules: {
            s_name: {
                required: true,
                minlength: 3,
                maxlength: 35
            },
            b_company: {
                digits: true
            },
			s_phone_mobile: {
                minlength: 7,
				maxlength: 35
			},
			s_phone_land: {
                minlength: 7,
				maxlength: 35
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
            },
			s_website: {
				url: true
			}
		},
		messages: {
            s_name: {
                required:  "<?php echo __('Name', 'modern') . ": " . __("this field is required", 'modern'); ?>.",
                minlength: "<?php echo __('Name', 'modern') . ": " . sprintf(__("enter at least %d characters", 'modern'), 3); ?>.",
                maxlength: "<?php echo __('Name', 'modern') . ": " . sprintf(__("no more than %d characters", 'modern'), 35); ?>."
            },
            b_company: "<?php printf(__("Select a %s",'modern'), __("user type",'modern')); ?>.",
            s_phone_mobile: {
                minlength: "<?php echo __('Cell phone', 'modern') . ": " . sprintf(__("enter at least %d characters", 'modern'), 7); ?>.",
                maxlength: "<?php echo __('Cell phone', 'modern') . ": " . sprintf(__("no more than %d characters", 'modern'), 35); ?>."
            },
            s_phone_land: {
                minlength: "<?php echo __('Phone', 'modern') . ": " . sprintf(__("enter at least %d characters", 'modern'), 7); ?>.",
                maxlength: "<?php echo __('Phone', 'modern') . ": " . sprintf(__("no more than %d characters", 'modern'), 35); ?>."
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
            },
            s_website: "<?php _e("Invalid website address",'modern'); ?>."
        },
        errorLabelContainer: "#error_list",
        wrapper: "li",
        invalidHandler: function(form, validator) {
            $('html,body').animate({ scrollTop: $('h2').offset().top }, { duration: 250, easing: 'swing'});
        }
    });
    
    
    /**
     * validate change email form: user-change_email.php
     */
    $('#user-email').validate({
		rules: {
			new_email: {
				required: true,
				email: true
			}
		},
		messages: {
            new_email: {
                required: "<?php echo __('Email','modern') . ": " . __("this field is required", 'modern'); ?>.",
                email: "<?php _e("Invalid email address", 'modern'); ?>."
            }
        },
        errorLabelContainer: "#error_list",
        wrapper: "li",
        invalidHandler: function(form, validator) {
            $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
        }
    });
    
    
    /**
     * add validation: notEqual
     */
    jQuery.validator.addMethod("notEqual", function(value, element, param) {
      return this.optional(element) || value != $(param).val();
    });
    

    /**
     * validate change password form: user-change_password.php
     */
    $('#user-password').validate({
		rules: {
            password: {
				required: true,
				maxlength: 25
			},
            new_password: {
				required: true,
				maxlength: 25,
                notEqual: "#password"
			},
			new_password2: {
				required: true,
				maxlength: 25,
				equalTo: "#new_password"
			}
		},
		messages: {
            password: {
                required: "<?php echo __('Current password','modern') . ": " . __("this field is required", 'modern'); ?>.",
                maxlength: "<?php echo __('Current password', 'modern') . ": " . sprintf(__("no more than %d characters", 'modern'), 25); ?>."
            },
            new_password: {
                required: "<?php echo __('New password','modern') . ": " . __("this field is required", 'modern'); ?>.",
                maxlength: "<?php echo __('New password', 'modern') . ": " . sprintf(__("no more than %d characters", 'modern'), 25); ?>.",
                notEqual: "<?php echo __('New password', 'modern') . ": " . __("must be different from current password", 'modern'); ?>."
            },
            new_password2: {
                required: "<?php echo __('Re-type new password','modern') . ": " . __("this field is required", 'modern'); ?>.",
                maxlength: "<?php echo __('Re-type new password', 'modern') . ": " . sprintf(__("no more than %d characters", 'modern'), 25); ?>.",
                equalTo: "<?php _e("The new passwords don't match", 'modern'); ?>."
            }
        },
        errorLabelContainer: "#error_list",
        wrapper: "li",
        invalidHandler: function(form, validator) {
            $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
        }
    });
});