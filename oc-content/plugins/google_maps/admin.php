<?php

    $key            = '';
    $dao_preference = new Preference();
    if(Params::getParam('key') != '') {
        $key = Params::getParam('key');
    } else {
        $key = (osc_google_maps_key() != '') ? osc_google_maps_key() : '' ;
    }
    
    if( Params::getParam('option') == 'stepone' ) {
        $dao_preference->update(array("s_value" => $key), array("s_section" => "plugin-google_maps", "s_name" => "google_maps_key")) ;
        echo '<div style="text-align:center; font-size:22px; background-color:#00bb00;"><p>' . __('Congratulations. The plugin is now configured', 'google_maps') . '.</p></div>';
    }
    unset($dao_preference) ;
    
?>

<form action="<?php osc_admin_base_url(true); ?>" method="post">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="file" value="google_maps/admin.php" />
    <input type="hidden" name="option" value="stepone" />
    <div>
        <?php _e('Please enter your Google Maps', 'google_maps'); ?> <label for="key" style="font-weight: bold;"><?php _e('developer key', 'google_maps'); ?>*</label>:<br />
        <input type="text" name="key" id="key" value="<?php echo $key; ?>" maxlength="100" size="60" /> <input type="submit" value="<?php _e('Save', 'google_maps'); ?>" />
    </div>
</form>
<br />
<div style="font-size: small;">
    <strong>*</strong> <?php _e('You can freely obtain a developer key after signing up on this URL', 'google_maps'); ?>: <a rel="nofollow" target="_blank" href="http://code.google.com/apis/maps/signup.html">http://code.google.com/apis/maps/signup.html</a>.
</div>