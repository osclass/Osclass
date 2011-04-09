<div class="alert_form">
    <h3>
        <strong><?php _e('Subscribe to this search', 'modern'); ?></strong>
    </h3>
    <ul id="error_list"></ul>    
    <form action="<?php echo osc_base_url(true); ?>" method="post" name="sub_alert" id="sub_alert">
        <fieldset>
            <?php AlertForm::page_hidden(); ?>
            <?php AlertForm::action_hidden(); ?>
            <?php AlertForm::alert_hidden(); ?>

            <?php if(osc_is_web_user_logged_in()) { ?>
                <?php AlertForm::user_id_hidden(); ?>
                <?php AlertForm::email_hidden(); ?>
            <?php } else { ?>
                <?php AlertForm::user_id_hidden(); ?>
                <?php AlertForm::email_text(); ?>
            <?php }; ?>
            <button type="submit" class="sub_button" ><?php _e('Subscribe now', 'modern'); ?>!</button>
        </fieldset>
    </form>
</div>