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
    
    Preference::newInstance()->insert($fields) ;
}

function google_maps_call_after_uninstall() {
    Preference::newInstance()->delete (
        array(
            "s_section" => "plugin-google_maps"
            ,"s_name" => "google_maps_key"
        )
    ) ;
}

function google_maps_admin() {
    osc_renderPluginView(dirname(__FILE__) . '/admin.php') ;
}

function google_maps_location() {
	global $item ;
	if(osc_google_maps_key() != '') {
		$key = osc_google_maps_key() ;
        require 'map.php' ;
	}
}

osc_registerPlugin(__FILE__, '') ;
// This is needed in order to be able to activate the plugin
osc_registerPlugin(__FILE__, 'google_maps_call_after_install') ;
// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_addHook(__FILE__."_uninstall", 'google_maps_call_after_uninstall') ;
osc_addHook(__FILE__."_configure", 'google_maps_admin') ;
osc_addHook('location', 'google_maps_location') ;