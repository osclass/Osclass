<?php

    $dao_preference = new Preference() ;
    if(isset($_REQUEST['webid'])) {
        $webid = $_REQUEST['webid'];
    } else {
        $preferences = $dao_preference->toArray() ;
    	$webid = isset($preferences['google_analytics_id']) ? $preferences['google_analytics_id'] : '';
    }
    
    if(isset($_REQUEST['option']) && $_REQUEST['option']=='stepone') 
    {
        $dao_preference->update(array("s_value" => $webid), array("s_section" => "plugin-google_analytics", "s_name" => "google_analytics_id")) ;
        echo '<div><p>Congratulations. The plugin is now configured.</p></div>' ;
    }

?>

<form action="plugins.php" method="post">
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="file" value="google_analytics/admin.php" />
    <input type="hidden" name="option" value="stepone" />
    
    <div>
        Please enter your Google Analytics <label for="id" style="font-weight: bold;">Web property ID</label>: <input type="text" name="webid" id="webid" value="<?php echo $webid; ?>" /> <input type="submit" value="Save" />
    </div>
</form>