<?php

    $_P = Preference::newInstance() ;

    if(isset($_REQUEST['webid'])) {
        $webid = $_REQUEST['webid'] ;
    } else {
        $webid = $_P->get('google_analytics_id') ;
    }
    
    if(isset($_REQUEST['option']) && $_REQUEST['option']=='stepone') 
    {
        $_P->update (
            array("s_value" => $webid)
            ,array("s_section" => "plugin-google_analytics", "s_name" => "google_analytics_id")
        ) ;
        echo '<div><p>' . __('Congratulations. The plugin is now configured.') . '</p></div>' ;
    }

?>

<form action="plugins.php" method="post">
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="file" value="google_analytics/admin.php" />
    <input type="hidden" name="option" value="stepone" />
    
    <div>
        <?php _e('Please enter your Google Analytics <label for="id" style="font-weight: bold;">Web property ID</label>') ; ?>:
        <input type="text" name="webid" id="webid" value="<?php echo $webid; ?>" />
        <input type="submit" value="<?php _e('Save') ; ?>" />
    </div>
</form>