<?php
/*
Plugin Name: Google Maps
Plugin URI: http://www.osclass.org/
Description: This plugin shows a Google Map on the location space of every item.
Version: 2.1.2
Author: OSClass & kingsult
Author URI: http://www.osclass.org/
Plugin update URI: http://www.osclass.org/files/plugins/google_maps/update.php
*/

    function google_maps_location() {
        $item = osc_item();
        osc_google_maps_header();
        require 'map.php';
    }

    // HELPER
    function osc_google_maps_header() {
        echo '<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>';
    }

    function insert_geo_location($catId, $itemId) {
        $aItem = Item::newInstance()->findByPrimaryKey($itemId);
        $sAddress = (isset($aItem['s_address']) ? $aItem['s_address'] : '');
        $sRegion = (isset($aItem['s_region']) ? $aItem['s_region'] : '');
        $sCity = (isset($aItem['s_city']) ? $aItem['s_city'] : '');
        $address = sprintf('%s, %s %s', $sAddress, $sRegion, $sCity);
        $response = osc_file_get_contents(sprintf('http://maps.google.com/maps/geo?q=%s&output=json&sensor=false', urlencode($address)));
        $jsonResponse = json_decode($response);
        if (isset($jsonResponse->Placemark) && count($jsonResponse->Placemark[0]) > 0) {
            $coord = $jsonResponse->Placemark[0]->Point->coordinates;
            ItemLocation::newInstance()->update (array('d_coord_lat' => $coord[1]
                                                      ,'d_coord_long' => $coord[0])
                                                ,array('fk_i_item_id' => $itemId));
        }
    }

    osc_add_hook('location', 'google_maps_location') ;

    osc_add_hook('item_form_post', 'insert_geo_location') ;
    osc_add_hook('item_edit_post', 'insert_geo_location') ;

?>