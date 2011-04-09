$(document).ready(function(){
    /**
     * Filter: autocomplete city
     */
    $('#sCity').autocomplete({
        source: "<?php echo osc_base_url(true); ?>?page=ajax&action=location",
        minLength: 2,
        select: function( event, ui ) {
            log( ui.item ?
                "<?php _e('Selected', 'modern'); ?>: " + ui.item.value + " aka " + ui.item.id :
                "<?php _e('Nothing selected, input was', 'modern'); ?> " + this.value );
        }
    });

    
    /**
     * Filter form submit - one category required
     */
    $('#search-filter button').click(function(event){
        if ( $("#sCategory:checked").length > 0 ) {
            return true;
        } else {
            alert("<?php _e('Select at least one category', 'modern'); ?>");
            return false;
        }
    });
    
     
    /**
     * Alert
     */
     
    // cache jQuery objects - improve performance
    var $alert_Email = $("#alert_email"); 
    var $alert_Form = $("#sub_alert");
    var $alert_Reply = $('#error_list');
    
    // email field - focus effects
    var email_Empty = '<?php echo AlertForm::default_email_text(); ?>';
    $alert_Email.blur(function(){
        if ($(this).val() == '' || $(this).val() == email_Empty){
            $(this).val(email_Empty).css('color','gray');
        }
    }).focus(function(){
        if ($(this).val() == email_Empty){
            $(this).val('').css('color','');
        }
    });
    $alert_Email.trigger('blur'); // init
    
    
    /**
     * Alert: validate email
     */
    $alert_Form.validate({
        rules: { alert_email: { required: true, email: true } },
        messages: {
            alert_email: {
                required: "<?php echo __('Email','modern') . ": " . __("this field is required", 'modern'); ?>.",
                email: "<?php _e("Invalid email address", 'modern'); ?>."
            }
        },
        errorLabelContainer: "#error_list",
        wrapper: "li",
        invalidHandler: function(form, validator) {
            $('html,body').animate({ scrollTop: $(this).offset().top }, { duration: 250, easing: 'swing' });
        },
        submitHandler: function(form) {
            // ajax submit - display reply
            $.ajax({
                dataType: 'json',
                url: $(form).attr('action'),
                type: 'post',
                data: $(form).serialize(),
                success: function(reply) {
                    if (!reply.error) {
                        $alert_Reply.text(reply.message + '.').slideDown(250);
                        $alert_Email.slideUp(100);
                        $(form).find('div.button').slideUp(100);
                    } else { 
                        $alert_Reply.html('<li>' + reply.message + '.</li>').slideDown(250);
                    }
                },
                error: function() {
                    $alert_Reply.html('<li>' + reply.message + '.</li>').slideDown(250);
                }
            });
        }
    });
});


/** 
 *  Filter: autocomplete - display results
 */
function log(message) {
    $("<div/>").text(message).prependTo("#log");
    $("#log").attr( "scrollTop", 0 );
}