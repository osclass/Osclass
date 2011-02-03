<?php

    if(isset($_REQUEST['key'])) {
        $key = $_REQUEST['key'] ;
    } else {
        $key = osc_google_maps_key() ;
    }
    
    if (isset($_REQUEST['option']) && $_REQUEST['option'] == 'stepone') 
    {
        Preference::newInstance()->update(
            array("s_value" => $key)
            ,array("s_section" => "plugin-google_maps", "s_name" => "google_maps_key")
        ) ;
        echo '<div><p>' . __('Congratulations. The plugin is now configured.') . '</p></div>' ;
    }
    
?>

<form action="plugins.php" method="post">
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="file" value="google_maps/admin.php" />
    <input type="hidden" name="option" value="stepone" />
    <div>
        <?php _e('Please enter your Google Maps <label for="key" style="font-weight: bold;">developer key*</label>') ; ?>:<br />
        <input type="text" name="key" id="key" value="<?php echo $key ; ?>" maxlength="100" size="60" />
        <input type="submit" value="<?php _e('Save') ; ?>" />
    </div>
</form>
<br />
<div style="font-size: small;">
    <strong>*</strong> <?php _e('You can freely obtain a developer key after signing up on this URL:'); ?>
    <a rel="nofollow" target="_blank" href="http://code.google.com/apis/maps/signup.html">http://code.google.com/apis/maps/signup.html</a>.
</div>