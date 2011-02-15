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
    
    $dao_preference = new Preference() ;
    $dao_preference->insert($fields) ;
    unset($dao_preference) ;
}

function google_analytics_call_after_uninstall() {
    $dao_preference = new Preference() ;
    $dao_preference->delete( array("s_section" => "plugin-google_analytics", "s_name" => "google_analytics_id") ) ;
    unset($dao_preference) ;
}

function google_analytics_admin() {
	
	osc_admin_render_plugin(dirname(__FILE__).'/admin.php') ;
}


/**
 * This function is called every time the page footer is being rendered
 */
function google_analytics_footer() {
	$preferences = Preference::newInstance()->toArray() ;
	if(isset($preferences['google_analytics_id']) && !empty($preferences['google_analytics_id'])) {
		$id = $preferences['google_analytics_id'] ;
		require 'footer.php' ;
	}
}

// This is needed in order to be able to activate the plugin
osc_register_plugin(__FILE__, 'google_analytics_call_after_install') ;
// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_add_hook(__FILE__."_uninstall", 'google_analytics_call_after_uninstall') ;
osc_add_hook(__FILE__."_configure", 'google_analytics_admin') ;
osc_add_hook('footer', 'google_analytics_footer') ;
