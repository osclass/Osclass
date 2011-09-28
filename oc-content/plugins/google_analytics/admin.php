<?php

    $dao_preference = new Preference() ;
    $webid          = Params::getParam('webid');
    $option         = Params::getParam('option');
    
    if( $option == 'stepone' ) {
        $dao_preference->update(array("s_value" => $webid)
                               ,array("s_section" => "plugin-google_analytics", "s_name" => "google_analytics_id")) ;
        echo '<div style="text-align:center; font-size:22px; background-color:#00bb00;"><p>' . __('Congratulations. The plugin is now configured','google_analytics') . '.</p></div>' ;
    } else {
        $webid = osc_google_analytics_id() ;
    }

?>

<form action="<?php osc_admin_base_url(true); ?>" method="get">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="file" value="google_analytics/admin.php" />
    <input type="hidden" name="option" value="stepone" />
    
    <div>
        <?php _e('Please enter your Google Analytics', 'google_analytics'); ?> <label for="id" style="font-weight: bold;"><?php _e('Web property ID', 'google_analytics'); ?></label>: <input type="text" name="webid" id="webid" value="<?php echo $webid; ?>" /> <input type="submit" value="<?php _e('Save', 'google_analytics'); ?>" />
    </div>
</form>