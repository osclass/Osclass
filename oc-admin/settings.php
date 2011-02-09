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

define('ABS_PATH', dirname(dirname(__FILE__)) . '/');

require_once ABS_PATH . 'oc-admin/oc-load.php';

$action = Params::getParam('action');

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
    case 'locations':
        $type_action = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
        $mCountries = new Country();
        $mRegions = new Region();
        $mCities = new City();
        switch ($type_action) {
            case 'add_country':
                // check if is from geo or by the user
                if ( !$_POST['c_manual'] ) {
                    install_location_by_country();
                } else {
                    $c_code = $_POST['c_country'] ;
                    $s_name = $_POST['country'] ;
                    $c_language = osc_language() ;

                    $data = array(
                        'pk_c_code' => $c_code,
                        'fk_c_locale_code' => $c_language,
                        's_name' => $s_name
                    );

                    $mCountries->insert($data);
                }
                break;
            case 'edit_country':
                $new_s_country = $_POST['e_country'];
                $old_s_country = $_POST['country_old'];
                $mCountries->update(
                        array('s_name' => $new_s_country),
                        array('s_name' => $old_s_country)
                    );
                break;
            case 'delete_country':
                $code = $_GET['id'];
                $aCountries = $mCountries->findByCode($code);
                $aRegions = $mRegions->listWhere('fk_c_country_code =  \'' . $aCountries['pk_c_code'] . '\'');
                foreach($aRegions as $region) {
                    $mCities->delete(array('fk_i_region_id' => $region['pk_i_id']));
                    $mRegions->delete(array('pk_i_id' => $region['pk_i_id']));
                }
                $mCountries->delete(array('pk_c_code' => $aCountries['pk_c_code']));

                osc_redirectTo('settings.php?action=locations');
                break;
            case 'add_region':
                if ( !$_POST['r_manual'] ) {
                    install_location_by_region();
                } else {
                    $s_name = $_POST['region'];
                    $c_country_code = $_POST['country_c_parent'];

                    $data = array(
                        'fk_c_country_code' => $c_country_code
                        ,'s_name' => $s_name
                    );

                    $mRegions->insert($data);
                }
                break;
            case 'edit_region':
                $new_s_region = $_POST['e_region'];
                $region_id = $_POST['region_id'];
                $mRegions->update(
                        array('s_name' => $new_s_region)
                        ,array('pk_i_id' => $region_id)
                    );
                break;
            case 'delete_region':
                $code = $_GET['id'];

                $mCities->delete(array('fk_i_region_id' => $code));
                $mRegions->delete(array('pk_i_id' => $code));
                
                osc_redirectTo('settings.php?action=locations');
                break;
            case 'add_city':
                $region_id = $_POST['region_parent'];
                $c_country_code = $_POST['country_c_parent'];
                $new_s_city = $_POST['city'];

                $data = array(
                    'fk_i_region_id' => $region_id
                    ,'s_name' => $new_s_city
                    ,'fk_c_country_code' => $c_country_code
                );
                $mCities->insert($data);
                break;
            case'edit_city':
                $new_s_city = $_POST['e_city'];
                $city_id = $_POST['city_id'];
                $mCities->update(
                        array('s_name' => $new_s_city)
                        ,array('pk_i_id' => $city_id)
                    );
                break;
            case 'delete_city':
                $code = $_GET['id'];

                $mCities->delete(array('pk_i_id' => $code));

                osc_redirectTo('settings.php?action=locations');
                break;
            default:
                break;
        }
        $aCountries = $mCountries->listAll();
        osc_renderAdminSection('settings/locations.php', __('Location'));
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
    case 'users':
        osc_renderAdminSection('settings/users.php', __('Functionalities')) ;
        break;
    case 'users_post':
        $enabled_user_validation = false ;
        if(isset($_POST['enabled_user_validation'])) {
            $enabled_user_validation = true ;
        }
        $enabled_user_registration = false ;
        if(isset($_POST['enabled_user_registration'])) {
            $enabled_user_registration = true ;
        }
        $enabled_users = false ;
        if(isset($_POST['enabled_users'])) {
            $enabled_users = true ;
        }
        
        Preference::newInstance()->update(
                array('s_value' => $enabled_user_validation)
                ,array('s_name'  => 'enabled_user_validation')
        );
        Preference::newInstance()->update(
                array('s_value' => $enabled_user_registration)
                ,array('s_name'  => 'enabled_user_registration')
        );
        Preference::newInstance()->update(
                array('s_value' => $enabled_users)
                ,array('s_name'  => 'enabled_users')
        );

        osc_add_flash_message(__('Users settings have been updated.'), 'admin') ;
        osc_redirectTo('settings.php?action=users') ;
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
    case 'permalinks':
        $htaccess_status = isset($_REQUEST['htaccess_status']) ? $_REQUEST['htaccess_status'] : 0 ;
        $file_status = isset($_REQUEST['file_status']) ? $_REQUEST['file_status'] : 0 ;

        osc_renderAdminSection('settings/permalinks.php', __('Settings')) ;
        break;
    case 'permalinks_post':

        $htaccess_status = 0;
        $file_status = 0;
        if(!isset($_REQUEST['enable_mod_rewrite'])) {
       
            Preference::newInstance()->update(
                array('s_value' => isset($_REQUEST['rewrite_enabled']) ? 1 : 0)
                ,array('s_name' => 'rewriteEnabled')
            );
            if(isset($_REQUEST['rewrite_enabled'])) {
                
                require ABS_PATH.'generate_rules.php' ;
                $htaccess_text = '
    <IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase '.REL_WEB_URL.'
    RewriteRule ^index\.php$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . '.REL_WEB_URL.'index.php [L]
    </IfModule>';

                if(file_exists(ABS_PATH.'.htaccess')) {
                    $file_status = 1;
                } else if(file_put_contents(ABS_PATH . '.htaccess', $htaccess_text)) {
                    $file_status = 2;
                } else {
                    $file_status = 3;
                }

                if(apache_mod_loaded('mod_rewrite')) {
                    $htaccess_status = 1;
                    Preference::newInstance()->update(
                        array('s_value' => 1)
                        ,array('s_name' => 'mod_rewrite_loaded')
                    );
                } else {
                    $htaccess_status = 2;
                    Preference::newInstance()->update(
                        array('s_value' => 0)
                        ,array('s_name' => 'mod_rewrite_loaded')
                    );
                }

            }

        } else {
        
            Preference::newInstance()->update(
                array('s_value' => 1)
                ,array('s_name' => 'rewriteEnabled')
            );

            Preference::newInstance()->update(
                array('s_value' => $_REQUEST['enable_mod_rewrite'])
                ,array('s_name' => 'mod_rewrite_loaded')
            );
            
            $htaccess_status = 3+$_REQUEST['enable_mod_rewrite'] ;
        }

        osc_redirectTo('settings.php?action=permalinks&htaccess_status='.$htaccess_status.'&file_status='.$file_status) ;
    case 'items':
        osc_renderAdminSection('settings/items.php', __('Settings')) ;
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
    case 'items_post':
        $enabledRecaptchaItems = Params::getParam('enabled_recaptcha_items');
        $enabledRecaptchaItems = (($enabledRecaptchaItems != '') ? true : false);
        $enabledItemValidation = Params::getParams('enabled_item_validation');
        $enabledItemValidation = (($enabledItemValidation != '') ? true : false);
        $regUserPost           = Params::getParam('reg_user_post');
        $regUserPost           = (($regUserPost != '') ? true : false);
        $notifyNewItem         = Params::getParam('notify_new_item');
        $notifyNewItem         = (($notifyNewItem != '') ? true : false);
        $notifyContactFriends  = Params::getParam('notify_contact_friends');
        $notifyContactFriends  = (($notifyContactFriends != ''));



        Preference::newInstance()->update(array('s_value' => $enabledRecaptchaItems)
                                         ,array('s_name'  => 'enabled_recaptcha_items'));
        Preference::newInstance()->update(array('s_value' => $enabledItemValidation)
                                         ,array('s_name'  => 'enabled_item_validation'));
        Preference::newInstance()->update(array('s_value' => $regUserPost)
                                         ,array('s_name'  => 'reg_user_post'));
        Preference::newInstance()->update(array('s_value' => $notifyNewItem)
                                         ,array('s_name'  => 'notify_new_item'));

        Preference::newInstance()->update(array('s_value' => $notifyNewItem)
                                         ,array('s_name'  => 'notify_contact_friends'));
        Preference::newInstance()->update(array('s_value' => $notifyNewItem)
                                         ,array('s_name'  => 'notify_contact_item'));
        Preference::newInstance()->update(array('s_value' => $notifyNewItem)
                                         ,array('s_name'  => 'enabled_item_validation'));
        Preference::newInstance()->update(array('s_value' => $notifyNewItem)
                                         ,array('s_name'  => 'enableField#f_price@items'));
        Preference::newInstance()->update(array('s_value' => $notifyNewItem)
                                         ,array('s_name'  => 'enableField#images@items'));

        osc_redirectTo('settings.php?action=items') ;
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


function install_location_by_country() {
    $country[] = trim($_POST['country']);

    $manager_country = new Country();
    $countries_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=country&term='.  implode(',', $country) );
    $countries = json_decode($countries_json);
    foreach($countries as $c) {
        $manager_country->insert(array(
            "pk_c_code" => addslashes($c->id)
            ,"fk_c_locale_code" => addslashes($c->locale_code)
            ,"s_name" => addslashes($c->name)
        ));
    }

    $manager_region = new Region();
    $regions_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=region&country=' . implode(',', $country) . '&term=all');
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
            $cities_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=city&country=' . $c->name . '&region=' .$region['s_name'] . '&term=all') ;
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
}

function install_location_by_region() {
    if(!isset($_POST['country_c_parent']))
        return false;

    if(!isset($_POST['region']))
        return false;

    $manager_country = new Country() ;

    $aCountry = $manager_country->findByCode($_POST['country_c_parent']) ;

    $country = array();
    $region = array();

    $country[] = $aCountry['s_name'];
    $region[] = $_POST['region'];

    $manager_region = new Region();
    $regions_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=region&country=' . implode(',', $country) . '&term=' . implode(',', $region));
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
        $regions = $manager_region->findByConditions( array('fk_c_country_code' => $aCountry['pk_c_code'], 's_name' => $_POST['region']) );
        $cities_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=city&country=' . $c . '&region=' .$regions['s_name'] . '&term=all');
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
}

?>