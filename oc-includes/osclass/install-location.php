<?php

error_reporting(E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_PARSE);

define( 'ABS_PATH', dirname(dirname(dirname(__FILE__))) . '/' );
define( 'LIB_PATH', ABS_PATH . 'oc-includes/');

require_once ABS_PATH . 'config.php';

require_once LIB_PATH . 'osclass/classes/database/DBConnectionClass.php';
require_once LIB_PATH . 'osclass/classes/database/DBCommandClass.php';
require_once LIB_PATH . 'osclass/classes/database/DBRecordsetClass.php';
require_once LIB_PATH . 'osclass/classes/database/DAO.php';
require_once LIB_PATH . 'osclass/Logger/Logger.php' ;
require_once LIB_PATH . 'osclass/Logger/LogDatabase.php' ;
require_once LIB_PATH . 'osclass/Logger/LogOsclass.php' ;
require_once LIB_PATH . 'osclass/core/Session.php';
require_once LIB_PATH . 'osclass/core/Params.php';
require_once LIB_PATH . 'osclass/model/Preference.php' ;
require_once LIB_PATH . 'osclass/helpers/hDatabaseInfo.php';
require_once LIB_PATH . 'osclass/helpers/hDefines.php';
require_once LIB_PATH . 'osclass/helpers/hErrors.php';
require_once LIB_PATH . 'osclass/helpers/hLocale.php';
require_once LIB_PATH . 'osclass/helpers/hPreference.php' ;
require_once LIB_PATH . 'osclass/helpers/hPlugins.php';
require_once LIB_PATH . 'osclass/helpers/hTranslations.php' ;
require_once LIB_PATH . 'osclass/compatibility.php';
require_once LIB_PATH . 'osclass/default-constants.php';
require_once LIB_PATH . 'osclass/formatting.php';
require_once LIB_PATH . 'osclass/install-functions.php';
require_once LIB_PATH . 'osclass/utils.php';
require_once LIB_PATH . 'osclass/core/Translation.php';
require_once LIB_PATH . 'osclass/plugins.php';

if( is_osclass_installed() ) {
    die() ;
}

$json_message = array();
$json_message['status'] = '200';

$result = basic_info();
$json_message['email_status']   = $result['email_status'];
$json_message['password']       = $result['s_password'];

if( $_POST['skip-location-h'] == 0 ) {
    $msg = install_locations() ;
    $json_message['status'] = $msg;
}

echo json_encode($json_message);

function basic_info() {
    require_once LIB_PATH . 'osclass/model/Admin.php' ;
    require_once LIB_PATH . 'osclass/helpers/hSecurity.php' ;

    $admin = Params::getParam('s_name') ;
    if( $admin == '' ) {
        $admin = 'admin' ;
    }

    $password = Params::getParam('s_passwd', false, false) ;
    if( $password == '' ) {
        $password = osc_genRandomPassword() ;
    }

    Admin::newInstance()->insert(
        array(
            's_name'      => 'Administrator'
            ,'s_username' => $admin
            ,'s_password' => sha1($password)
            ,'s_email'    => Params::getParam('email')
        )
    ) ;

    $mPreference = Preference::newInstance() ;
    $mPreference->insert (
        array(
            's_section' => 'osclass'
            ,'s_name'   => 'pageTitle'
            ,'s_value'  => Params::getParam('webtitle')
            ,'e_type'   => 'STRING'
        )
    ) ;

    $mPreference->insert (
        array(
            's_section' => 'osclass'
            ,'s_name'   => 'contactEmail'
            ,'s_value'  => Params::getParam('email')
            ,'e_type'   => 'STRING'
        )
    ) ;
    
    $body  = sprintf(__('Welcome %s,'),Params::getParam('webtitle'))."<br/><br/>" ;
    $body .= sprintf(__('Your OSClass installation at %s is up and running. You can access the administration panel with these details:'), WEB_PATH)."<br/>";
    $body .= '<ul>';
    $body .= '<li>'.sprintf(__('username: %s'), $admin).'</li>';
    $body .= '<li>'.sprintf(__('password: %s'), $password).'</li>';
    $body .= '</ul>' ;
    $body .= __('Regards,')."<br/>";
    $body .= __('The <a href="http://osclass.org/">OSClass</a> team') ;

    $sitename = strtolower( $_SERVER['SERVER_NAME'] ) ;
    if ( substr( $sitename, 0, 4 ) == 'www.' ) {
        $sitename = substr( $sitename, 4 ) ;
    }

    try{
        require_once LIB_PATH . 'phpmailer/class.phpmailer.php' ;
        $mail = new PHPMailer(true) ;
        $mail->CharSet  = "utf-8" ;
        $mail->Host     = "localhost" ;
        $mail->From     = 'osclass@' . $sitename ;
        $mail->FromName = 'OSClass' ;
        $mail->Subject  = 'OSClass successfully installed!' ;
        $mail->AddAddress(Params::getParam('email'), 'OSClass administrator') ;
        $mail->Body     = $body ;
        $mail->AltBody  = $body ;
        if( !$mail->Send() ) {
            return array('email_status' => Params::getParam('email') . "<br>" . $mail->ErrorInfo, 's_password'   => $password ) ;
        }
        
        return array('email_status' => '', 's_password'   => $password ) ;
    } catch(phpmailerException $exception) {
        return array('email_status' => Params::getParam('email') . "<br>" . $exception->errorMessage(), 's_password'   => $password ) ;
    }
}

function location_international() {
    $manager_country = Country::newInstance() ;
    $manager_region  = Region::newInstance() ;
    $manager_city    = City::newInstance() ;

    $countries_json = osc_file_get_contents( 'http://geo.osclass.org/geo.download.php?action=country&term=all&install=true&version=' . osc_version() ) ;
    $countries      = json_decode($countries_json) ;

    if( count($countries) ==  0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error( 'Cannot get countries' , __FILE__ . "::" . __LINE__ ) ;
        }
        return '300' ;
    }

    foreach($countries as $c) {
        $manager_country->insert(array(
            "pk_c_code"        => $c->id,
            "s_name"           => $c->name
        )) ;
    }
    
    $regions_json = osc_file_get_contents( 'http://geo.osclass.org/geo.download.php?action=region&country=all&term=all') ;
    $regions      = json_decode($regions_json) ;

    if( count($regions) == 0 && reportToOsclass() ) {
        LogOsclassInstaller::instance()->error( 'Cannot get regions', __FILE__ . "::" . __LINE__ ) ;
    }
    foreach($regions as $r) {
        $manager_region->insert(array(
            "pk_i_id"           => $r->id,
            "fk_c_country_code" => $r->country_code,
            "s_name"            => $r->name
        )) ;
    }

    foreach($countries as $c) {
        $cities_json = osc_file_get_contents( 'http://geo.osclass.org/geo.download.php?action=city&country=' . urlencode($c->name) . '&term=all' ) ;
        $cities      = json_decode($cities_json) ;

        if( !isset($cities->error) ) {
            foreach($cities as $ci) {
                $manager_city->insert(array(
                    "pk_i_id"           => $ci->id,
                    "fk_i_region_id"    => $ci->region_id,
                    "s_name"            => $ci->name,
                    "fk_c_country_code" => $ci->country_code
                )) ;
            }
        } else {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error( 'Cannot get cities by country ' . $c->name , __FILE__ . "::" . __LINE__ ) ;
            }
        }

        unset($cities) ;
        unset($cities_json) ;
    }
    
    return '200' ;
}

function location_by_country() {
    $country = Params::getParam('country') ;
    if( $country == '' ) {
        return false ;
    }

    $countries_json = osc_file_get_contents( 'http://geo.osclass.org/geo.download.php?action=country&term=' . urlencode(implode(',', $country)) . '&install=true&version=' . osc_version() ) ;
    $countries      = json_decode($countries_json) ;

    $manager_country = Country::newInstance() ;

    if( count($countries) ==  0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error('Cannot get countries - ' . implode(',', $country) , __FILE__."::".__LINE__) ;
        }
        return '300' ;
    }

    foreach($countries as $c) {
        $manager_country->insert(array(
            "pk_c_code"        => $c->id,
            "s_name"           => $c->name
        )) ;
    }

    $manager_region = Region::newInstance() ;

    $regions_json = osc_file_get_contents( 'http://geo.osclass.org/geo.download.php?action=region&country=' . urlencode(implode(',', $country)) . '&term=all' ) ;
    $regions      = json_decode($regions_json) ;

    if( count($regions) == 0 && reportToOsclass() ) {
        LogOsclassInstaller::instance()->error( 'Cannot get regions by - ' . implode(',', $country) , __FILE__ . "::" . __LINE__ ) ;
    }

    foreach($regions as $r) {
        $manager_region->insert(array(
            "pk_i_id"           => $r->id,
            "fk_c_country_code" => $r->country_code,
            "s_name"            => $r->name
        )) ;
    }

    $manager_city = City::newInstance() ;

    foreach($countries as $c) {
        $cities_json = osc_file_get_contents( 'http://geo.osclass.org/geo.download.php?action=city&country=' . urlencode($c->name) . '&term=all' ) ;
        $cities      = json_decode($cities_json) ;

        if( !isset($cities->error) ) {
            foreach($cities as $ci) {
                $manager_city->insert(array(
                    "pk_i_id"           => $ci->id,
                    "fk_i_region_id"    => $ci->region_id,
                    "s_name"            => $ci->name,
                    "fk_c_country_code" => $ci->country_code
                )) ;
            }
        } else {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error( 'Cannot get cities by country - ' . $c->name , __FILE__ . "::" . __LINE__ ) ;
            }
        }

        unset($cities) ;
        unset($cities_json) ;
    }

    return '200' ;
}

function location_by_region() {
    $country = Params::getParam('country') ;
    $region  = Params::getParam('region') ;

    if( $country == '' ) {
        return false ;
    }

    if( $region == '' ) {
        return false ;
    }

    $countries_json = osc_file_get_contents( 'http://geo.osclass.org/geo.download.php?action=country&term=' . urlencode(implode(',', $country)) . '&install=true&version=' . osc_version() ) ;
    $countries      = json_decode($countries_json) ;

    $manager_country = Country::newInstance() ;

    if( count($countries) == 0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error('Cannot get countries - ' . implode(',', $country) , __FILE__."::".__LINE__) ;
        }
        return '300' ;
    }
    
    foreach($countries as $c) {
        $manager_country->insert(array(
            "pk_c_code"        => $c->id,
            "s_name"           => $c->name
        )) ;
    }

    $regions_json = osc_file_get_contents( 'http://geo.osclass.org/geo.download.php?action=region&country=' . urlencode(implode(',', $country)) . '&term=' . urlencode(implode(',', $region)) ) ;
    $regions      = json_decode($regions_json);

    $manager_region = Region::newInstance() ;

    if( count($regions) == 0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error( 'Cannot get regions - ' . implode(',', $country) . '- term' . implode(',', $region) , __FILE__ . "::" . __LINE__ ) ;
        }
        return '300' ;
    }

    foreach($regions as $r) {
        $manager_region->insert(array(
            "pk_i_id"           => $r->id,
            "fk_c_country_code" => $r->country_code,
            "s_name"            => $r->name
        )) ;
    }

    $manager_city = City::newInstance() ;
    foreach($countries as $c) {
        $cities_json = osc_file_get_contents( 'http://geo.osclass.org/geo.download.php?action=city&country=' . urlencode($c->name) . '&region=' . urlencode(implode(',', $region)) . '&term=') ;
        $cities      = json_decode($cities_json) ;
        if( !isset($cities->error) ) {
            foreach($cities as $ci) {
                $manager_city->insert(array(
                    "pk_i_id"           => $ci->id,
                    "fk_i_region_id"    => $ci->region_id,
                    "s_name"            => $ci->name,
                    "fk_c_country_code" => $ci->country_code
                )) ;
            }
        } else {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error( 'Cannot get regions by country - ' . $c->name . '- by region ' . implode(',', $region) , __FILE__ . "::" . __LINE__ ) ;
            }
        }
        unset($cities) ;
        unset($cities_json) ;
    }
    
    return '200' ;
}

function location_by_city() {
    $country = Params::getParam('country') ;
    $city    = Params::getParam('city') ;

    if( $country == '' ) {
        return false ;
    }

    if( $city == '' ) {
        return false ;
    }

    $countries_json = osc_file_get_contents( 'http://geo.osclass.org/geo.download.php?action=country&term=' . urlencode(implode(',', $country)) . '&install=true&version=' . osc_version() ) ;
    $countries      = json_decode($countries_json) ;

    $manager_country = Country::newInstance() ;

    if( count($countries) == 0 && reportToOsclass() ) {
        LogOsclassInstaller::instance()->error( 'Cannot get countries - ' . implode(',', $country) , __FILE__ . "::" . __LINE__ ) ;
    }

    foreach($countries as $c) {
        $manager_country->insert(array(
            "pk_c_code"        => $c->id,
            "s_name"           => $c->name
        )) ;
    }

    $manager_city   = City::newInstance() ;
    $manager_region = Region::newInstance() ;
    foreach($countries as $c) {
        $cities_json = osc_file_get_contents( 'http://geo.osclass.org/geo.download.php?action=city&country=' . urlencode($c->name) . '&term=' . urlencode(implode(',', $city) ) ) ;
        $cities      = json_decode($cities_json) ;
        if( !isset($cities->error) ) {
            foreach($cities as $ci) {
                $regions_json = osc_file_get_contents( 'http://geo.osclass.org/geo.download.php?action=region&country=&id=' . $ci->region_id ) ;
                $regions      = json_decode($regions_json) ;

                if( count($regions) == 0 && reportToOsclass() ) {
                    LogOsclassInstaller::instance()->error( 'Cannot get regions by - ' . $ci->region_id , __FILE__ . "::" . __LINE__ ) ;
                }

                foreach($regions as $r) {
                    $manager_region->insert(array(
                        "pk_i_id"           => $r->id,
                        "fk_c_country_code" => $r->country_code,
                        "s_name"            => $r->name
                    )) ;
                }

                $manager_city->insert(array(
                    "pk_i_id"           => $ci->id,
                    "fk_i_region_id"    => $ci->region_id,
                    "s_name"            => $ci->name,
                    "fk_c_country_code" => $ci->country_code
                )) ;
            }
        } else {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error( 'Cannot get cities by - ' . $c->name . ' - term ' . implode(',', $city) , __FILE__ . "::" . __LINE__ ) ;
            }
            return '300' ;
        }
        
        unset($cities) ;
        unset($cities_json) ;
    }

    return '200' ;
}

function install_locations ( ) {
    if( Params::getParam('c_country') == '' ) {
        return false;
    }

    require_once ABS_PATH . 'oc-includes/osclass/model/Country.php';
    require_once ABS_PATH . 'oc-includes/osclass/model/Region.php';
    require_once ABS_PATH . 'oc-includes/osclass/model/City.php';

    if( Params::getParam('city') != '' ) {
        return location_by_city() ;
    } else if( Params::getParam('region') != '' ) {
        return location_by_region() ;
    } else if( Params::getParam('country') != '' ) {
        return location_by_country() ;
    }

    return location_international ();
}

?>