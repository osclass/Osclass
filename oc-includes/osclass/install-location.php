<?php
//error_reporting(E_ALL);

error_reporting(E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_PARSE);

define( 'ABS_PATH', dirname(dirname(dirname(__FILE__))) . '/' );
define( 'LIB_PATH', ABS_PATH . 'oc-includes/');

require_once ABS_PATH . 'config.php';
require_once LIB_PATH . 'osclass/db.php';
require_once LIB_PATH . 'osclass/classes/DAO.php';
require_once LIB_PATH . 'osclass/helpers/hDatabaseInfo.php';
require_once LIB_PATH . 'osclass/install-functions.php';
require_once LIB_PATH . 'osclass/formatting.php';
require_once LIB_PATH . 'osclass/compatibility.php';
require_once LIB_PATH . 'osclass/utils.php';
require_once LIB_PATH . 'osclass/helpers/hPreference.php' ;

require_once LIB_PATH . 'osclass/Logger/Logger.php' ;
require_once LIB_PATH . 'osclass/Logger/LogOsclass.php' ;

$_POST = add_slashes_extended($_POST) ;

if( is_osclass_installed() ) {
    die() ;
}

$json_message = array();
$json_message['status'] = '200';

basic_info();

if( $_POST['skip-location-h'] == 0 ) {
    $msg = install_locations() ;
    $json_message['status'] = $msg;
}

echo json_encode($json_message);

function basic_info() {
    require_once LIB_PATH . 'osclass/model/Admin.php' ;
    require_once LIB_PATH . 'osclass/model/Preference.php' ;

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

    if( count($countries) ==  0 ) {
        if (reportToOsclass()){
            LogOsclassInstaller::instance()->error('Cannot get countries' , __FILE__."::".__LINE__) ;
        }
        return '300';
    }

    foreach($countries as $c) {
        $manager_country->insert(array(
            "pk_c_code" => $c->id,
            "fk_c_locale_code" => $c->locale_code,
            "s_name" => $c->name
        )) ;
    }
    
    $regions_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=region&country=all&term=all');
    $regions = json_decode($regions_json);

    if( count($regions) ==  0 && reportToOsclass()){
        LogOsclassInstaller::instance()->error('Cannot get regions' , __FILE__."::".__LINE__) ;
    }
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
        } else {
            if( reportToOsclass() ){
                LogOsclassInstaller::instance()->error('Cannot get cities by country ' . $c->name , __FILE__."::".__LINE__) ;
            }
        }

        unset($cities);
        unset($cities_json);
    }
    
    return '200';
}

function location_by_country() {
    if(!isset($_POST['country']))
        return false;

    $country = $_POST['country'];

    $countries_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=country&term='. urlencode(implode(',', $country)) . '&install=true&version='.osc_version() );
    $countries = json_decode($countries_json);

    $manager_country = Country::newInstance();

    if( count($countries) ==  0 ) {
        if( reportToOsclass() ){
            LogOsclassInstaller::instance()->error('Cannot get countries - ' . implode(',', $country) , __FILE__."::".__LINE__) ;
        }
        return '300';
    }

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

    if( count($regions) ==  0 && reportToOsclass()){
        LogOsclassInstaller::instance()->error('Cannot get regions by - ' . implode(',', $country) , __FILE__."::".__LINE__) ;
    }

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
        } else {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error('Cannot get cities by country - ' . $c->name , __FILE__."::".__LINE__) ;
            }
        }

        unset($cities);
        unset($cities_json);
    }
    
    return '200';
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

    if( count($countries) == 0 ) {
        if( reportToOsclass() ){
            LogOsclassInstaller::instance()->error('Cannot get countries - ' . implode(',', $country) , __FILE__."::".__LINE__) ;
        }
        return '300';
    }
    
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

    if( count($regions) ==  0 ) {
        if( reportToOsclass() ){
            LogOsclassInstaller::instance()->error('Cannot get regions - ' . implode(',', $country) .'- term' . implode(',', $region) , __FILE__."::".__LINE__) ;
        }
        return '300';
    }

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
        } else {
            if( reportToOsclass() ){
                LogOsclassInstaller::instance()->error('Cannot get regions by country - ' . $c->name .'- by region ' . implode(',', $region) , __FILE__."::".__LINE__) ;
            }
        }
        unset($cities);
        unset($cities_json);
    }
    
    return '200';
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

    if( count($countries) ==  0 && reportToOsclass()){
        LogOsclassInstaller::instance()->error('Cannot get countries - ' . implode(',', $country) , __FILE__."::".__LINE__) ;
    }

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

                if( count($regions) == 0 && reportToOsclass() ){
                    LogOsclassInstaller::instance()->error('Cannot get regions by - ' .$ci->region_id  , __FILE__."::".__LINE__) ;
                }

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
        } else {
            if( reportToOsclass() ){
                LogOsclassInstaller::instance()->error('Cannot get cities by - ' . $c->name . ' - term ' . implode(',', $city) , __FILE__."::".__LINE__) ;
            }
            return '300';
        }
        
        unset($cities);
        unset($cities_json);
    }
    
    return '200';
}

function install_locations ( ) {
    // first of all we check if is a international or a specific installation
    if( !isset($_POST['c_country']) )
        return false;

    require_once ABS_PATH . 'oc-includes/osclass/model/Country.php';
    require_once ABS_PATH . 'oc-includes/osclass/model/Region.php';
    require_once ABS_PATH . 'oc-includes/osclass/model/City.php';

    if( isset($_POST['city']) )
        return location_by_city(); 
    else if( isset($_POST['region']) )
        return location_by_region();
    else if( isset($_POST['country']) )
        return location_by_country();
    else
        return location_international ();
}

?>