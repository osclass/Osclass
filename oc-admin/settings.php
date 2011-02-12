<?php
/**
 * OSClass – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2010 OSCLASS
 *
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU Affero General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

class CAdminSettings extends AdminSecBaseModel
{

    function __construct() {
        parent::__construct() ;
        $this->add_css('settings_layout.css');
    }

    //Business Layer...
    function doModel() {
        switch($this->action) {
            case('items'):          // calling the items settings view
                                    $this->doView('settings/items.php');
            break;
            case('items_post'):     // update item settings
                                    $enabledRecaptchaItems   = Params::getParam('enabled_recaptcha_items');
                                    $enabledRecaptchaItems   = (($enabledRecaptchaItems != '') ? true : false);
                                    $enabledItemValidation   = Params::getParam('enabled_item_validation');
                                    $enabledItemValidation   = (($enabledItemValidation != '') ? true : false);
                                    $regUserPost             = Params::getParam('reg_user_post');
                                    $regUserPost             = (($regUserPost != '') ? true : false);
                                    $notifyNewItem           = Params::getParam('notify_new_item');
                                    $notifyNewItem           = (($notifyNewItem != '') ? true : false);
                                    $notifyContactItem       = Params::getParam('notify_contact_item');
                                    $notifyContactItem       = (($notifyContactItem != '') ? true : false);
                                    $notifyContactFriends    = Params::getParam('notify_contact_friends');
                                    $notifyContactFriends    = (($notifyContactFriends != '') ? true : false);
                                    $enabledFieldPriceItems  = Params::getParam('enableField#f_price@items');
                                    $enabledFieldPriceItems  = (($enabledFieldPriceItems != '') ? true : false);
                                    $enabledFieldImagesItems = Params::getParam('enableField#images@items');
                                    $enabledFieldImagesItems = (($enabledFieldImagesItems != '') ? true : false);
                                    
                                    Preference::newInstance()->update(array('s_value' => $enabledRecaptchaItems)
                                                                     ,array('s_name'  => 'enabled_recaptcha_items'));
                                    Preference::newInstance()->update(array('s_value' => $enabledItemValidation)
                                                                     ,array('s_name'  => 'enabled_item_validation'));
                                    Preference::newInstance()->update(array('s_value' => $regUserPost)
                                                                     ,array('s_name'  => 'reg_user_post'));
                                    Preference::newInstance()->update(array('s_value' => $notifyNewItem)
                                                                     ,array('s_name'  => 'notify_new_item'));
                                    Preference::newInstance()->update(array('s_value' => $notifyContactItem)
                                                                     ,array('s_name'  => 'notify_contact_item'));
                                    Preference::newInstance()->update(array('s_value' => $notifyContactFriends)
                                                                     ,array('s_name'  => 'notify_contact_friends'));
                                    Preference::newInstance()->update(array('s_value' => $enabledFieldPriceItems)
                                                                     ,array('s_name'  => 'enableField#f_price@items'));
                                    Preference::newInstance()->update(array('s_value' => $enabledFieldImagesItems)
                                                                     ,array('s_name'  => 'enableField#images@items'));

                                    osc_add_flash_message(__('Items settings have been updated'), 'admin');
                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=items');
            break;
            case('comments'):       //calling the comments settings view
                                    $this->doView('settings/comments.php');
            break;
            case('comments_post'):  // updating comment
                                    $enabledComments  = Params::getParam('enabled_comments');
                                    $enabledComments  = (($enabledComments != '') ? true : false);
                                    $moderateComments = Params::getParam('moderate_comments');
                                    $moderateComments = (($moderateComments != '') ? true : false);
                                    $notifyNewComment = Params::getParam('notify_new_comment');
                                    $notifyNewComment = (($notifyNewComment != '') ? true : false);

                                    Preference::newInstance()->update(array('s_value' => $enabledComments)
                                                                     ,array('s_name' => 'enabled_comments'));
                                    Preference::newInstance()->update(array('s_value' => $moderateComments)
                                                                     ,array('s_name' => 'moderate_comments'));
                                    Preference::newInstance()->update(array('s_value' => $notifyNewComment)
                                                                     ,array('s_name' => 'notify_new_comment'));

                                    osc_add_flash_message(__('Comments setting have been updated'), 'admin');
                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=comments');
            break;
            case ('users'):         // calling the users settings view
                                    $this->doView('settings/users.php');
            break;
            case ('users_post'):    // updating users
                                    $enabledUserValidation   = Params::getParam('enabled_user_validation');
                                    $enabledUserValidation   = (($enabledUserValidation != '') ? true : false);
                                    $enabledUserRegistration = Params::getParam('enabled_user_registration');
                                    $enabledUserRegistration = (($enabledUserRegistration != '') ? true : false);
                                    $enabledUsers            = Params::getParam('enabled_users');
                                    $enabledUsers            = (($enabledUsers != '') ? true : false);

                                    Preference::newInstance()->update(array('s_value' => $enabledUserValidation)
                                                                     ,array('s_name'  => 'enabled_user_validation'));
                                    Preference::newInstance()->update(array('s_value' => $enabledUserRegistration)
                                                                     ,array('s_name'  => 'enabled_user_registration'));
                                    Preference::newInstance()->update(array('s_value' => $enabledUsers)
                                                                     ,array('s_name'  => 'enabled_users'));
        
                                    osc_add_flash_message(__('Users settings have been updated.'), 'admin');
                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=users');
            break;
            case ('locations'):     // calling the locations settings view
                                    $location_action = Params::getParam('type');
                                    $mCountries = new Country();
                                    
                                    switch ($location_action) {
                                        case('add_country'):    // add country
                                                                if( !Params::getParam('c_manual') ) {
                                                                    $this->install_location_by_country();
                                                                } else {
                                                                    $countryCode     = Params::getParam('c_country');
                                                                    $countryName     = Params::getParam('country');
                                                                    $countryLanguage = osc_language();

                                                                    $data = array('pk_c_code'        => $countryCode,
                                                                                  'fk_c_locale_code' => $countryLanguage,
                                                                                  's_name'           => $countryName);
                                                                    $mCountries->insert($data);
                                                                    
                                                                    osc_add_flash_message(sprintf(__('%s has been added as a new country'),
                                                                                                  $countryName), 'admin');
                                                                }

                                                                $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        break;
                                        case('edit_country'):   // edit country
                                                                $newCountry = Params::getParam('e_country');
                                                                $oldCountry = Params::getParam('country_old');
                                                                $mCountries->update(array('s_name' => $newCountry)
                                                                                   ,array('s_name' => $oldCountry));
                                                                osc_add_flash_message(sprintf(__('%s has been edited'),
                                                                                                  $newCountry), 'admin');
                                                                $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        break;
                                        case('delete_country'): // delete country
                                                                $countryId = Params::getParam('id');
                                                                $mRegions = new Region();
                                                                $mCities = new City();

                                                                $aCountries = $mCountries->findByCode($countryId);
                                                                $aRegions = $mRegions->listWhere('fk_c_country_code =  \'' . $aCountries['pk_c_code'] . '\'');
                                                                foreach($aRegions as $region) {
                                                                    $mCities->delete(array('fk_i_region_id' => $region['pk_i_id']));
                                                                    $mRegions->delete(array('pk_i_id' => $region['pk_i_id']));
                                                                }
                                                                $mCountries->delete(array('pk_c_code' => $aCountries['pk_c_code']));

                                                                osc_add_flash_message(sprintf(__('%s has been deleted'), $aCountries['s_name']), 'admin');
                                                                $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        break;
                                        case('add_region'):     // add region
                                                                if( !Params::getParam('r_manual') ) {
                                                                    $this->install_location_by_region();
                                                                } else {
                                                                    $mRegions    = new Region();
                                                                    $regionName  = Params::getParam('region');
                                                                    $countryCode = Params::getParam('country_c_parent');

                                                                    $data = array('fk_c_country_code' => $countryCode
                                                                                 ,'s_name' => $regionName);
                                                                    $mRegions->insert($data);
                                                                    osc_add_flash_message(sprintf(__('%s has been added as a new region'),
                                                                                                     $regionName), 'admin');
                                                                }
                                                                $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        break;
                                        case('edit_region'):    // edit region
                                                                $mRegions  = new Region();
                                                                $newRegion = Params::getParam('e_region');
                                                                $regionId  = Params::getParam('region_id');

                                                                if($regionId != '') {
                                                                    $mRegions->update(array('s_name' => $newRegion)
                                                                                     ,array('pk_i_id' => $regionId));
                                                                    osc_add_flash_message(sprintf(__('%s has been edited'),
                                                                                                      $newRegion), 'admin');
                                                                }
                                                                $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        break;
                                        case('delete_region'):  // delete region
                                                                $mRegion  = new Region();
                                                                $mCities  = new City();
                                                                
                                                                $regionId = Params::getParam('id');

                                                                if($regionId != '') {
                                                                    $aRegion = $mRegion->findByPrimaryKey($regionId);

                                                                    $mCities->delete(array('fk_i_region_id' => $regionId));
                                                                    $mRegion->delete(array('pk_i_id' => $regionId));

                                                                    osc_add_flash_message(sprintf(__('%s has been deleted'),
                                                                            $aRegion['s_name']), 'admin');
                                                                }
                                                                $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        break;
                                        case('add_city'):       // add city
                                                                $mCities     = new City();
                                                                $regionId    = Params::getParam('region_parent');
                                                                $countryCode = Params::getParam('country_c_parent');
                                                                $newCity     = Params::getParam('city');

                                                                $mCities->insert(array('fk_i_region_id'    => $regionId
                                                                                      ,'s_name'            => $newCity
                                                                                      ,'fk_c_country_code' => $countryCode));

                                                                osc_add_flash_message(sprintf(__('%s has been added as new city'),
                                                                                                 $newCity), 'admin');
                                                                $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        break;
                                        case('edit_city'):      // edit city
                                                                $mCities = new City();
                                                                $newCity = Params::getParam('e_city');
                                                                $cityId  = Params::getParam('city_id');

                                                                $mCities->update(array('s_name' => $newCity)
                                                                                ,array('pk_i_id' => $cityId));
                                                                
                                                                osc_add_flash_message(sprintf(__('%s has been edited'),
                                                                                                 $newCity), 'admin');
                                                                $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        break;
                                        case('delete_city'):    // delete city
                                                                $mCities = new City();
                                                                $cityId  = Params::getParam('id');

                                                                $aCity   = $mCities->findByPrimaryKey($cityId);
                                                                $mCities->delete(array('pk_i_id' => $cityId));

                                                                osc_add_flash_message(sprintf(__('%s has been deleted'),
                                                                                                 $aCity['s_name']), 'admin');
                                                                $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        break;
                                    }
                                    
                                    $aCountries = $mCountries->listAll();
                                    $this->_exportVariableToView('aCountries', $aCountries);

                                    $this->add_css('location_layout.css');
                                    $this->add_js('location.js');
                                    $this->doView('settings/locations.php');
            break;
            case('permalinks'):     // calling the permalinks view
                                    $htaccess = Params::getParam('htaccess_status');
                                    $file     = Params::getParam('file_status');

                                    $this->_exportVariableToView('htaccess', $htaccess);
                                    $this->_exportVariableToView('file', $file);

                                    $this->doView('settings/permalinks.php');
            break;
            case('permalinks_post'):// updating permalinks option
                                    $htaccess_status = 0;
                                    $file_status     = 0;
                                    $rewriteEnabled  = Params::getParam('rewrite_enabled');
                                    $rewriteEnabled  = ($rewriteEnabled ? true : false);

                                    if($rewriteEnabled) {
                                        Preference::newInstance()->update(array('s_value' => '1')
                                                                         ,array('s_name' => 'rewriteEnabled'));

                                        // require ABS_PATH.'generate_rules.php';
                                        $htaccess = '
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase ' . REL_WEB_URL . '
    RewriteRule ^index\.php$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . ' . REL_WEB_URL . 'index.php [L]
</IfModule>';

                                        if( file_exists(ABS_PATH . '.htaccess') ) {
                                            $file_status = 1;
                                        } else if(file_put_contents(ABS_PATH . '.htaccess', $htaccess_text)) {
                                            $file_status = 2;
                                        } else {
                                            $file_status = 3;
                                        }

                                        if(apache_mod_loaded('mod_rewrite')) {
                                            $htaccess_status = 1;
                                            Preference::newInstance()->update(array('s_value' => '1')
                                                                             ,array('s_name' => 'mod_rewrite_loaded'));
                                        } else {
                                            $htaccess_status = 2;
                                            Preference::newInstance()->update(array('s_value' => '0')
                                                                             ,array('s_name' => 'mod_rewrite_loaded'));
                                        }
                                    } else {
                                        $modRewrite = apache_mod_loaded('mod_rewrite');
                                        Preference::newInstance()->update(array('s_value' => '0')
                                                                         ,array('s_name' => 'rewriteEnabled'));
                                        Preference::newInstance()->update(array('s_value' => '0')
                                                                         ,array('s_name' => 'mod_rewrite_loaded'));
                                    }

                                    $redirectUrl  = osc_admin_base_url(true) . '?page=settings&action=permalinks&htaccess_status=';
                                    $redirectUrl .= $htaccess_status . '&file_status=' . $file_status;
                                    $this->redirectTo($redirectUrl);
            break;
            default:                // default dashboard page (main page at oc-admin)
                                    $this->_exportVariableToView( "numUsers", User::newInstance()->count() ) ;
                                    $this->_exportVariableToView( "numAdmins", Admin::newInstance()->count() ) ;

                                    $this->_exportVariableToView( "numItems", Item::newInstance()->count() ) ;
                                    $this->_exportVariableToView( "numItemsPerCategory", CategoryStats::newInstance()->toNumItemsMap() ) ;
                                    $this->_exportVariableToView( "categories", Category::newInstance()->listAll() ) ;
                                    $this->_exportVariableToView( "newsList", osc_listNews() ) ;
                                    $this->_exportVariableToView( "comments", ItemComment::newInstance()->getLastComments(5) ) ;

                                    // calling the view...
                                    $this->doView('main/index.php') ;
        }
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }

    function install_location_by_country() {
        $country    = Params::getParam('country');
        $aCountry[] = trim($country);
        
        $manager_country = new Country();
        $countries_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=country&term=' .
                                                implode(',', $aCountry) );
        $countries = json_decode($countries_json);
        foreach($countries as $c) {
            $manager_country->insert(array(
                "pk_c_code" => addslashes($c->id)
                ,"fk_c_locale_code" => addslashes($c->locale_code)
                ,"s_name" => addslashes($c->name)
            ));
        }

        $manager_region = new Region();
        $regions_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=region&country=' .
                                              implode(',', $aCountry) . '&term=all');
        $regions = json_decode($regions_json);
        foreach($regions as $r) {
            $manager_region->insert(array(
                "fk_c_country_code" => addslashes($r->country_code),
                "s_name" => addslashes($r->name)
            ));
        }
        unset($regions);
        unset($regions_json);

        $manager_city = new City();
        foreach($countries as $c) {
            $regions = $manager_region->listWhere('fk_c_country_code = \'' . $c->id . '\'') ;
            foreach($regions as $region) {
                $cities_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=city&country=' .
                                                     $c->name . '&region=' .$region['s_name'] . '&term=all') ;
                $cities = json_decode($cities_json) ;
                if(!isset($cities->error)) {
                    foreach($cities as $ci) {
                        $manager_city->insert(array(
                            "fk_i_region_id" => addslashes($region['pk_i_id'])
                            ,"s_name" => addslashes($ci->name)
                            ,"fk_c_country_code" => addslashes($ci->country_code)
                        ));
                    }
                }
                unset($cities) ;
                unset($cities_json) ;
            }
        }

        osc_add_flash_message(sprintf(__('%s has been added as a new country'), $country), 'admin');
    }

    function install_location_by_region() {
        $countryParent = Params::getParam('country_c_parent');
        $region        = Params::getParam('region');

        if($countryParent == '') {
            return false;
        }

        if($region == '') {
            return false;
        }

        $manager_country = new Country() ;
        $country = $manager_country->findByCode($countryParent) ;

        $aCountry   = array();
        $aRegion    = array();
        $aCountry[] = $country['s_name'];
        $aRegion[]  = $region;

        $manager_region = new Region();
        $regions_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=region&country=' .
                                              implode(',', $aCountry) . '&term=' . implode(',', $aRegion));
        $regions = json_decode($regions_json);
        foreach($regions as $r) {
            $manager_region->insert(array(
                "fk_c_country_code" => addslashes($r->country_code),
                "s_name" => addslashes($r->name)
            ));
        }
        unset($regions);
        unset($regions_json);

        $manager_city = new City();
        foreach($country as $c) {
            $regions = $manager_region->findByConditions(array('fk_c_country_code' => $country['pk_c_code']
                                                              ,'s_name' => $region));
            $cities_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=city&country=' .
                                                 $c . '&region=' . $regions['s_name'] . '&term=all');
            $cities = json_decode($cities_json);
            if(!isset($cities->error)) {
                foreach($cities as $ci) {
                    $manager_city->insert(array(
                        "fk_i_region_id" => addslashes($regions['pk_i_id']),
                        "s_name" => addslashes($ci->name),
                        "fk_c_country_code" => addslashes($ci->country_code)
                    ));
                }
            }
            unset($cities);
            unset($cities_json);
        }

        osc_add_flash_message(sprintf(__('%s has been added as a region of %s'), $region, $country['s_name']), 'admin');
    }
}

/*

switch ($action) {
    case 'spamNbots':
        osc_renderAdminSection('settings/spamNbots.php', __('Settings'));
        break;
    case 'spamNbots_post':
        $akismetKey = trim($_POST['akismetKey']);
        if (empty($akismetKey)) {
            Preference::newInstance()->delete(array('s_name' => 'akismetKey'));
        } else {
            Preference::newInstance()->delete(array('s_name' => 'akismetKey')); // @TODO remove
            Preference::newInstance()->insert(array('s_section' => 'osclass', 's_name' => 'akismetKey', 's_value' => $akismetKey, 'e_type' => 'STRING'));
        }

        $recaptchaPrivKey = trim($_POST['recaptchaPrivKey']);
        $recaptchaPubKey = trim($_POST['recaptchaPubKey']);
        if (empty($recaptchaPrivKey) || empty($recaptchaPubKey)) {
            Preference::newInstance()->delete(array('s_name' => 'recaptchaPrivKey'));
            Preference::newInstance()->delete(array('s_name' => 'recaptchaPubKey'));
        } else {
            Preference::newInstance()->delete(array('s_name' => 'recaptchaPrivKey')); // @TODO remove
            Preference::newInstance()->delete(array('s_name' => 'recaptchaPubKey')); // @TODO remove
            Preference::newInstance()->insert(array('s_section' => 'osclass', 's_name' => 'recaptchaPrivKey', 's_value' => $recaptchaPrivKey, 'e_type' => 'STRING'));
            Preference::newInstance()->insert(array('s_section' => 'osclass', 's_name' => 'recaptchaPubKey', 's_value' => $recaptchaPubKey, 'e_type' => 'STRING'));
        }

        osc_redirectTo('settings.php?action=spamNbots');
        break;
    case 'registry':
        osc_renderAdminSection('settings/registry.php', __('Settings'));
        break;
    case 'currencies':
        $currencies = Currency::newInstance()->listAll();
        osc_renderAdminSection('settings/currencies.php', __('Settings'));
        break;
    case 'addCurrency':
        osc_renderAdminSection('settings/addCurrency.php', __('Settings'));
        break;
    case 'addCurrency_post':
        try {
            Currency::newInstance()->insert($_POST);
        } catch (Exception $e) {
            osc_add_flash_message($e->getMessage());
        }
        osc_redirectTo('settings.php?action=currencies');
        break;
    case 'editCurrency':
        if(isset($_GET['code'])) {
            $currency = Currency::newInstance()->findByCode($_GET['code']);
            osc_renderAdminSection('settings/editCurrency.php', __('Settings'));
        } else {
            osc_redirectTo('settings.php?action=currencies');
        }
        break;
    case 'editCurrency_post':
        try {
            Currency::newInstance()->update(array('s_name' => $_POST['s_name'], 's_description' => $_POST['s_description']), array('pk_c_code' => $_POST['pk_c_code']));
        } catch (Exception $e) {
            osc_add_flash_message($e->getMessage());
        }
        osc_redirectTo('settings.php?action=currencies');
        break;
    case 'deleteCurrency':
        $codes = $_GET['code'];

        isset($_POST['id']) ? $codes = $_POST['id'] : '';

        foreach ($codes as &$code)
            $code = "'$code'";
        unset($code);
        $cond = 'pk_c_code IN (' . implode(', ', $codes) . ')';
        try {
            Currency::newInstance()->delete(array(DB_CUSTOM_COND => $cond));
        } catch (Exception $e) {
            if($e->getMessage()=="1451") {
                osc_add_flash_message(__('This currency is currently being used in some items. It can not be deleted.')) ;
            } else {
                osc_add_flash_message($e->getMessage()) ;
            }
        }

        osc_redirectTo('settings.php?action=currencies') ;
        break;
    case 'functionalities':
        osc_renderAdminSection('settings/functionalities.php', __('Functionalities')) ;
        break;
    case 'functionalities_post':
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['enabled_comments']) ? true : false)
                ,array('s_name' => 'enabled_comments')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['enabled_recaptcha_items']) ? true : false)
                ,array('s_name' => 'enabled_recaptcha_items')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['enabled_item_validation']) ? true : false)
                ,array('s_name' => 'enabled_item_validation')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['moderate_comments']) ? true : false)
                ,array('s_name' => 'moderate_comments')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['reg_user_post']) ? true : false)
                ,array('s_name' => 'reg_user_post')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['auto_cron']) ? true : false)
                ,array('s_name' => 'auto_cron')
        );
        //XXX: Maybe is not needed. We want to reload the values from Preference
        Preference::newInstance()->toArray() ;
        osc_redirectTo('settings.php?action=functionalities') ;
        break;
    case 'notifications':
        osc_renderAdminSection('settings/notifications.php', __('Notifications')) ;
        break;
    case 'notifications_post':
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['notify_new_item']) ? true : false)
                ,array('s_name' => 'notify_new_item')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['notify_contact_friends']) ? true : false)
                ,array('s_name' => 'notify_contact_friends')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['notify_new_comment']) ? true : false)
                ,array('s_name' => 'notify_new_comment')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['notify_contact_item']) ? true : false)
                ,array('s_name' => 'notify_contact_item')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['enabled_item_validation']) ? true : false)
                ,array('s_name' => 'enabled_item_validation')
        );
        //XXX: Maybe is not needed. We want to reload the values from Preference
        Preference::newInstance()->toArray() ;
        osc_redirectTo('settings.php?action=notifications') ;
        break;
    case 'mailserver':
        osc_renderAdminSection('settings/mailserver.php', __('Functionalities')) ;
        break;
    case 'mailserver_post':
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['mailserver_auth']) ? true : false)
                ,array('s_name' => 'mailserver_auth')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['mailserver_type']) ? $_POST['mailserver_type'] : 'custom')
                ,array('s_name' => 'mailserver_type')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['mailserver_host']) ? $_POST['mailserver_host'] : '')
                ,array('s_name' => 'mailserver_host')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['mailserver_port']) ? $_POST['mailserver_port'] : '')
                ,array('s_name' => 'mailserver_port')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['mailserver_username']) ? $_POST['mailserver_username'] : '')
                ,array('s_name' => 'mailserver_username')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['mailserver_password']) ? $_POST['mailserver_password'] : '')
                ,array('s_name' => 'mailserver_password')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['mailserver_ssl']) ? $_POST['mailserver_ssl'] : '')
                ,array('s_name' => 'mailserver_ssl')
        );
        Preference::newInstance()->toArray();
        osc_redirectTo('settings.php?action=mailserver') ;
        break;
    case 'notifications':
        osc_renderAdminSection('settings/notifications.php', __('Notifications')) ;
        break;

    case 'comments':
        osc_renderAdminSection('settings/comments.php', __('Settings')) ;
        break;
    case 'cron':
        osc_renderAdminSection('settings/cron.php', __('Settings')) ;
        break;
    case 'cron_post':
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['auto_cron']) ? true : false)
                ,array('s_name' => 'auto_cron')
        );
        Preference::newInstance()->toArray() ;
        osc_redirectTo('settings.php?action=cron') ;
        break;
    case 'comments_post':
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['enabled_comments']) ? true : false)
                ,array('s_name' => 'enabled_comments')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['moderate_comments']) ? true : false)
                ,array('s_name' => 'moderate_comments')
        );
        Preference::newInstance()->update(
                array('s_value' => isset($_POST['notify_new_comment']) ? true : false)
                ,array('s_name' => 'notify_new_comment')
        );
        Preference::newInstance()->toArray();
        osc_redirectTo('settings.php?action=comments');
        break;

        break;
    case 'update':
        $sPageTitle    = Params::getParam('pageTitle');
        $sPageDesc     = Params::getParam('pageDesc');
        $sContactEmail = Params::getParam('contactEmail');
        $sLanguage     = Params::getParam('language');
        $sDateFormat   = Params::getParam('dateFormat');
        $sCurrency     = Params::getParam('currency');
        $sWeekStart    = Params::getParam('weekStart');
        $sTimeFormat   = Params::getParam('tf');
        $sTimeFormat   = Params::getParam('timeFormat');
        $sNumRssItems  = Params::getParam('num_rss_items');

        Preference::newInstance()->update(array('s_value'   => $sPageTitle)
                                         ,array('s_section' => 'osclass', 's_name' => 'pageTitle'));
        Preference::newInstance()->update(array('s_value'   => $sPageDesc)
                                         ,array('s_section' => 'osclass', 's_name' => 'pageDesc'));
        Preference::newInstance()->update(array('s_value'   => $sContactEmail)
                                         ,array('s_section' => 'osclass', 's_name' => 'contactEmail'));
        Preference::newInstance()->update(array('s_value'   => $sLanguage)
                                         ,array('s_section' => 'osclass', 's_name' => 'language'));
        Preference::newInstance()->update(array('s_value'   => $sDateFormat)
                                         ,array('s_section' => 'osclass', 's_name' => 'dateFormat'));
        Preference::newInstance()->update(array('s_value'   => $sCurrency)
                                         ,array('s_section' => 'osclass', 's_name' => 'currency'));
        Preference::newInstance()->update(array('s_value'   => $sWeekStart)
                                         ,array('s_section' => 'osclass', 's_name' => 'weekStart'));
        Preference::newInstance()->update(array('s_value'   => $sTimeFormat)
                                         ,array('s_section' => 'osclass', 's_name' => 'timeFormat'));
        Preference::newInstance()->update(array('s_value'   => $sNumRssItems)
                                         ,array('s_section' => 'osclass', 's_name' => 'num_rss_items'));

        osc_redirectTo('settings.php?action=items');
        Preference::newInstance()->toArray() ;
    default:
        $languages = Locale::newInstance()->listAllEnabled() ;
        $mCurrencies = new Currency() ;
        $aCurrencies = $mCurrencies->listAll() ;
        osc_renderAdminSection('settings/index.php', __('General settings')) ;
}
*/



?>