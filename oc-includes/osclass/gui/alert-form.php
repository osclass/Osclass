<?php global $search_alert; ?>
<script type="text/javascript">
$(document).ready(function(){
    $(".sub_button").click(function(){
        $.post('<?php echo osc_base_path(); ?>oc-includes/osclass/ajax/alerts.php', {email:$("#email").val(), userid:$("#userId").val(), alert:$("#alert").val()}, function(data){ if(data==1) { alert('<?php _e('You have been subscribe correctly to the alert'); ?>'); } else { alert('<?php _e('There was a problem with the alert');?>');}; });
        return false;
    });
});
</script>

<div class="alert_form">
    <h3><strong><?php _e('Subscribe to this search'); ?></strong></h3>
    <form  method="post" name="sub_alert" id="sub_alert">
    <fieldset>
        <input type="hidden" id="alert" name="alert" value="<?php echo $search_alert; ?>" >
        <?php if(osc_is_web_user_logged_in()) {
            $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;
            $user = User::newInstance()->findByPrimaryKey($userId);
        ?>
            <input type="hidden" id="userId" name="userId" value="<?php echo $userId; ?>" />
            <input type="hidden" id="email" name="email" value="<?php echo $user['s_email']; ?>" />
        <?php } else { ?>
            <input type="hidden" id="userId" name="userId" value="" />
            <input type="text" id="email" name="email" value="<?php _e('Enter your e-mail'); ?>" />
        <?php }; ?>
        <button type="submit" class="sub_button" ><?php _e('Subscribe now!');?></button>
    </fieldset>
    </form>
</div>
