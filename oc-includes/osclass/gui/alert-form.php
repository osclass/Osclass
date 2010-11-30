<?php global $search_alert; ?>
<script type="text/javascript">
$(document).ready(function(){
    $(".sub_button").click(function(){
        $.post('<?php echo ABS_WEB_URL; ?>/oc-includes/osclass/ajax/alerts.php', {email:$("#email").val(), alert:$("#alert").val()}, function(data){ if(data==1) { alert('<?php _e('You have been subscribe correctly to the alert'); ?>'); } else { alert('<?php _e('There was a problem with the alert');?>');}; });
        return false;
    });
});
</script>

<form  method="post" name="sub_alert" id="sub_alert">
    <input type="hidden" id="alert" name="alert" value="<?php echo $search_alert; ?>" >
    <input type="text" id="email" name="email" value="" />
    <button type="submit" class="sub_button" ><?php _e('Subscribe');?></button>
</form>
