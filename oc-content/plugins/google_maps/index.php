<?php
/*
Plugin Name: Google Maps
Plugin URI: http://www.osclass.org/
Description: This plugin shows a Google Map on the location space of every item.
Version: 1.0
Author: OSClass & kingsult
Author URI: http://www.osclass.org/
Plugin update URI: http://www.osclass.org/files/plugins/google_maps/update.php
*/

function google_maps_call_after_install() 
{
    $fields = array() ;
    $fields["s_section"] = 'plugin-google_maps' ;
    $fields["s_name"] = 'google_maps_key' ;
    $fields["s_value"] = '' ;
    $fields["e_type"] = 'STRING' ;
    
    $dao_preference = new Preference() ;
    $dao_preference->insert($fields) ;
    unset($dao_preference) ;
}

function google_maps_call_after_uninstall() {
    $dao_preference = new Preference() ;
    $dao_preference->delete( array("s_section" => "plugin-google_maps", "s_name" => "google_maps_key") ) ;
    unset($dao_preference) ;
}

function google_maps_admin() {
    osc_renderPluginView(dirname(__FILE__) . '/admin.php') ;
}

function google_maps_location() {
	global $item;
	$preferences = Preference::newInstance()->toArray();
	if(isset($preferences['google_maps_key']) && !empty($preferences['google_maps_key'])) {
		$key = $preferences['google_maps_key'];
        require 'map.php';
	}
}

// This is needed in order to be able to activate the plugin
osc_register_plugin(__FILE__, 'google_maps_call_after_install') ;
// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_add_hook(__FILE__."_uninstall", 'google_maps_call_after_uninstall') ;
osc_add_hook(__FILE__."_configure", 'google_maps_admin') ;
osc_add_hook('location', 'google_maps_location') ;
