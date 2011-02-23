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
            <?php AlertForm::page_search_hidden(); ?>
            <?php AlertForm::alert_hidden(); ?>

            <?php if(osc_is_web_user_logged_in()) { ?>
                <?php AlertForm::user_id_hidden(); ?>
                <?php AlertForm::email_hidden(); ?>

            <?php } else { ?>
                <?php AlertForm::user_id_hidden(); ?>
                <?php AlertForm::email_text(); ?>

            <?php }; ?>
            <button type="submit" class="sub_button" ><?php _e('Subscribe now!', 'gui');?></button>
        </fieldset>
    </form>
</div>
