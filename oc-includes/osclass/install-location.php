<?php

define( 'ABS_PATH', dirname(dirname(dirname(__FILE__))) . '/' );

require_once ABS_PATH . 'config.php';
require_once ABS_PATH . 'oc-includes/osclass/db.php';
require_once ABS_PATH . 'oc-includes/osclass/classes/DAO.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hDatabaseInfo.php';
require_once ABS_PATH . 'oc-includes/osclass/install-functions.php';
require_once ABS_PATH . 'oc-includes/osclass/formatting.php';
require_once ABS_PATH . 'oc-includes/osclass/utils.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hPreference.php' ;

$_POST = add_slashes_extended($_POST) ;

if( is_osclass_installed() ) {
    die() ;
}

basic_info();

if( $_POST['skip-location-h'] == 0 ) {
    install_locations() ;
}

function basic_info() {
    require_once ABS_PATH . 'oc-includes/osclass/model/Admin.php' ;
    require_once ABS_PATH . 'oc-includes/osclass/model/Preference.php' ;

    Admin::newInstance()->insert(
        array(
            's_name' => 'Administrator'
            ,'s_username' => 'admin'
            ,'s_password' => sha1('admin')
            ,'s_email' => $_POST['email']
        )
    ) ;

    $mPreference = Preference::newInstance() ;
    $mPreference->insert (
        array(
            's_section' => 'osclass'
            ,'s_name' => 'pageTitle'
            ,'s_value' => $_POST['webtitle']
            ,'e_type' => 'STRING'
        )
    ) ;
    $mPreference->insert (
        array(
            's_section' => 'osclass'
            ,'s_name' => 'contactEmail'
            ,'s_value' => $_POST['email']
            ,'e_type' => 'STRING'
        )
    ) ;
}

function location_international() {
    $manager_country = Country::newInstance();
    $manager_region = Region::newInstance();
    $manager_city = City::newInstance();

    $countries_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=country&term=all&install=true&version='.osc_version());
    $countries = json_decode($countries_json);

    foreach($countries as $c) {
        $manager_country->insert(array(
            "pk_c_code" => $c->id,
            "fk_c_locale_code" => $c->locale_code,
            "s_name" => $c->name
        )) ;
    }
    
    $regions_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=region&country=all&term=all');
    $regions = json_decode($regions_json);

    foreach($regions as $r) {
        $manager_region->insert(array(
            "pk_i_id" => $r->id,
            "fk_c_country_code" => $r->country_code,
            "s_name" => $r->name
        ));
    }

    foreach($countries as $c) {

        $cities_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=city&country=' . urlencode($c->name) . '&term=all');
        $cities = json_decode($cities_json);

        if(!isset($cities->error)) {
            foreach($cities as $ci) {
                $manager_city->insert(array(
                    "pk_i_id" => $ci->id,
                    "fk_i_region_id" => $ci->region_id,
                    "s_name" => $ci->name,
                    "fk_c_country_code" => $ci->country_code
                ));
            }
        }

        unset($cities);
        unset($cities_json);
    }
}

function location_by_country() {
    if(!isset($_POST['country']))
        return false;

    $country = $_POST['country'];

    $countries_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=country&term='. urlencode(implode(',', $country)) . '&install=true&version='.osc_version() );
    $countries = json_decode($countries_json);

    $manager_country = Country::newInstance();

    foreach($countries as $c) {
        $manager_country->insert(array(
            "pk_c_code" => $c->id,
            "fk_c_locale_code" => $c->locale_code,
            "s_name" => $c->name
        ));
    }

    $manager_region = Region::newInstance();

    $regions_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=region&country=' . urlencode(implode(',', $country)) . '&term=all');
    $regions = json_decode($regions_json);

    foreach($regions as $r) {
        $manager_region->insert(array(
            "pk_i_id" => $r->id,
            "fk_c_country_code" => $r->country_code,
            "s_name" => $r->name
        ));
    }

    $manager_city = City::newInstance();

    foreach($countries as $c) {
        $cities_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=city&country=' . urlencode($c->name) . '&term=all');
        $cities = json_decode($cities_json);

        if(!isset($cities->error)) {
            foreach($cities as $ci) {
                $manager_city->insert(array(
                    "pk_i_id" => $ci->id,
                    "fk_i_region_id" => $ci->region_id,
                    "s_name" => $ci->name,
                    "fk_c_country_code" => $ci->country_code
                ));
            }
        }

        unset($cities);
        unset($cities_json);
    }

}

function location_by_region() {
    if(!isset($_POST['country']))
        return false;
    
    if(!isset($_POST['region']))
        return false;
    
    $country = $_POST['country'];
    $region = $_POST['region'];

    $countries_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=country&term=' . urlencode(implode(',', $country)) . '&install=true&version='.osc_version() );
    $countries = json_decode($countries_json);
    
    $manager_country = Country::newInstance();
    foreach($countries as $c) {
        $manager_country->insert(array(
            "pk_c_code" => $c->id,
            "fk_c_locale_code" => $c->locale_code,
            "s_name" => $c->name
        ));
    }

    $regions_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=region&country=' . urlencode(implode(',', $country)) . '&term=' . urlencode(implode(',', $region)));
    $regions = json_decode($regions_json);

    $manager_region = Region::newInstance();
    foreach($regions as $r) {
        $manager_region->insert(array(
            "pk_i_id" => $r->id,
            "fk_c_country_code" => $r->country_code,
            "s_name" => $r->name
        ));
    }

    $manager_city = City::newInstance();
    foreach($countries as $c) {
        $cities_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=city&country=' . urlencode($c->name) . '&region=' . urlencode(implode(',', $region)) . '&term=');
        $cities = json_decode($cities_json);
        if(!isset($cities->error)) {
            foreach($cities as $ci) {
                $manager_city->insert(array(
                    "pk_i_id" => $ci->id,
                    "fk_i_region_id" => $ci->region_id,
                    "s_name" => $ci->name,
                    "fk_c_country_code" => $ci->country_code
                ));
            }
        }
        unset($cities);
        unset($cities_json);
    }
}

function location_by_city() {
    if(!isset($_POST['country']))
        return false;

    if(!isset($_POST['city']))
        return false;

    $country = $_POST['country'];
    $city = $_POST['city'];

    $countries_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=country&term='.  urlencode(implode(',', $country)) . '&install=true&version='.osc_version() );
    $countries = json_decode($countries_json);

    $manager_country = Country::newInstance();
    foreach($countries as $c) {
        $manager_country->insert(array(
            "pk_c_code" => $c->id,
            "fk_c_locale_code" => $c->locale_code,
            "s_name" => $c->name
        ));
    }

    $manager_city = City::newInstance();
    $manager_region = Region::newInstance();
    foreach($countries as $c) {
        $cities_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=city&country=' . urlencode($c->name) . '&term=' . urlencode(implode(',', $city)));
        $cities = json_decode($cities_json);
        if(!isset($cities->error)) {
            foreach($cities as $ci) {
                $regions_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=region&country=&id=' . $ci->region_id);
                $regions = json_decode($regions_json);

                foreach($regions as $r) {
                    $manager_region->insert(array(
                        "pk_i_id" => $r->id,
                        "fk_c_country_code" => $r->country_code,
                        "s_name" => $r->name
                    ));
                }

                $manager_city->insert(array(
                    "pk_i_id" => $ci->id,
                    "fk_i_region_id" => $ci->region_id,
                    "s_name" => $ci->name,
                    "fk_c_country_code" => $ci->country_code
                ));
            }
        }
        unset($cities);
        unset($cities_json);
    }
}

function install_locations ( ) {
    // first of all we check if is a international or a specific installation
    if( !isset($_POST['c_country']) )
        return false;

    require_once ABS_PATH . 'oc-includes/osclass/model/Country.php';
    require_once ABS_PATH . 'oc-includes/osclass/model/Region.php';
    require_once ABS_PATH . 'oc-includes/osclass/model/City.php';

    if( isset($_POST['city']) )
        location_by_city(); 
    else if( isset($_POST['region']) )
        location_by_region();
    else if( isset($_POST['country']) )
        location_by_country();
    else
        location_international ();
}
?>
