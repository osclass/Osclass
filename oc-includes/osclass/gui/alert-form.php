<script type="text/javascript">
$(document).ready(function(){
    $(".sub_button").click(function(){
        $.post('<?php echo osc_base_url(true); ?>', {email:$("#alert_email").val(), userid:$("#alert_userId").val(), alert:$("#alert").val(), page:"ajax", action:"alerts"}, function(data){ if(data==1) { alert('<?php _e('You have sucessfully subscribed to the alert', 'gui'); ?>'); } else { alert('<?php _e('There was a problem with the alert', 'gui');?>');}; });
        return false;
    });
});
</script>

<div class="alert_form">
    <h3>
        <strong><?php _e('Subscribe to this search', 'gui'); ?></strong>
    </h3>
    <form action="<?php echo osc_base_url(true); ?>" method="post" name="sub_alert" id="sub_alert">
        <fieldset>
            <input type="hidden" name="page" value="search" >
            <input type="hidden" id="alert" name="alert" value="<?php echo osc_search_alert(); ?>" >
            <?php if(osc_is_web_user_logged_in()) { ?>
                <input type="hidden" id="alert_userId" name="alert_userId" value="<?php echo osc_logged_user_id(); ?>" />
                <input type="hidden" id="alert_email" name="alert_email" value="<?php echo osc_logged_user_email(); ?>" />
            <?php } else { ?>
                <input type="hidden" id="alert_userId" name="alert_userId" value="" />
                <input type="text" id="alert_email" name="alert_email" value="<?php _e('Enter your e-mail', 'gui'); ?>" />
            <?php }; ?>
            <button type="submit" class="sub_button" ><?php _e('Subscribe now!', 'gui');?></button>
        </fieldset>
    </form>
</div>