<?php

/*
    Plugin Name: Google Analytics
    Plugin URI: http://www.osclass.org/
    Description: This plugin adds the Google Analytics script at the footer of every page.
    Version: 1.0
    Author: OSClass
    Author URI: http://www.osclass.org/
    Plugin update URI: http://www.osclass.org/files/plugins/google_analytics/update.php
*/

function google_analytics_call_after_install() 
{
    $fields = array() ;
    $fields["s_section"] = 'plugin-google_analytics' ;
    $fields["s_name"] = 'google_analytics_id' ;
    $fields["s_value"] = '' ;
    $fields["e_type"] = 'STRING' ;
    
    Preference::newInstance()->insert($fields) ;
}

function google_analytics_call_after_uninstall() {
    Preference::newInstance()->delete (
        array(
            "s_section" => "plugin-google_analytics"
            ,"s_name" => "google_analytics_id"
        )
    ) ;
}

function google_analytics_admin() {
	osc_renderPluginView(dirname(__FILE__) . '/admin.php') ;
}


/**
 * This function is called every time the page footer is being rendered
 */
function google_analytics_footer() {
	if(osc_google_analytics_id() != '') {
		$id = osc_google_analytics_id() ;
		require 'footer.php' ;
	}
}

osc_registerPlugin(__FILE__, '') ;
// This is needed in order to be able to activate the plugin
osc_registerPlugin(__FILE__, 'google_analytics_call_after_install') ;
// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_addHook(__FILE__."_uninstall", 'google_analytics_call_after_uninstall') ;
osc_addHook(__FILE__."_configure", 'google_analytics_admin') ;
osc_addHook('footer', 'google_analytics_footer') ;