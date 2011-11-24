<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    class CAdminSettings extends AdminSecBaseModel
    {

        function __construct() {
            parent::__construct() ;
        }

        //Business Layer...
        function doModel() {
            switch($this->action) {
                case('comments'):       //calling the comments settings view
                                        $this->doView('settings/comments.php');
                break;
                case('comments_post'):  // updating comment
                                        $iUpdated         = 0;
                                        $enabledComments  = Params::getParam('enabled_comments');
                                        $enabledComments  = (($enabledComments != '') ? true : false);
                                        $moderateComments = Params::getParam('moderate_comments');
                                        $moderateComments = (($moderateComments != '') ? true : false);
                                        $numModerateComments = Params::getParam('num_moderate_comments');
                                        $commentsPerPage  = Params::getParam('comments_per_page');
                                        $notifyNewComment = Params::getParam('notify_new_comment');
                                        $notifyNewComment = (($notifyNewComment != '') ? true : false);
                                        $notifyNewCommentUser = Params::getParam('notify_new_comment_user');
                                        $notifyNewCommentUser = (($notifyNewCommentUser != '') ? true : false);
                                        $regUserPostComments  = Params::getParam('reg_user_post_comments');
                                        $regUserPostComments  = (($regUserPostComments != '') ? true : false);

                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $enabledComments)
                                                                                      ,array('s_name' => 'enabled_comments'));
                                        if($moderateComments) {
                                            $iUpdated += Preference::newInstance()->update(array('s_value' => $numModerateComments)
                                                                                          ,array('s_name' => 'moderate_comments'));
                                        } else {
                                            $iUpdated += Preference::newInstance()->update(array('s_value' => '-1')
                                                                                          ,array('s_name' => 'moderate_comments'));
                                        }
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $notifyNewComment)
                                                                                      ,array('s_name' => 'notify_new_comment'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $notifyNewCommentUser)
                                                                                      ,array('s_name' => 'notify_new_comment_user'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $commentsPerPage)
                                                                                      ,array('s_name' => 'comments_per_page'));

                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $regUserPostComments )
                                                                                      ,array('s_name' => 'reg_user_post_comments'));

                                        if($iUpdated > 0) {
                                            osc_add_flash_ok_message( _m('Comments\' settings have been updated'), 'admin');
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=comments');
                break;
                case ('locations'):     // calling the locations settings view
                                        $location_action = Params::getParam('type');
                                        $mCountries = new Country();

                                        switch ($location_action) {
                                            case('add_country'):    // add country
                                                                    $countryCode = strtoupper(Params::getParam('c_country'));
                                                                    $request = Params::getParam('country');
                                                                    foreach($request as $k => $v) {
                                                                        $countryName = $v;
                                                                        break;
                                                                    }
                                                                    $exists = $mCountries->findByCode($countryCode);
                                                                    if(isset($exists['s_name'])) {
                                                                        osc_add_flash_error_message(sprintf(_m('%s already was in the database'),
                                                                                                      $countryName), 'admin');
                                                                    } else {
                                                                        $countries_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=country_code&term=' .
                                                                                                                 urlencode($countryCode) );
                                                                        $countries = json_decode($countries_json);
                                                                        foreach($request as $k => $v) {
                                                                            $data = array('pk_c_code'        => $countryCode,
                                                                                        'fk_c_locale_code' => $k,
                                                                                        's_name'           => $v);
                                                                            $mCountries->insert($data);
                                                                        }
                                                                        if(isset($countries->error)) { // Country is not in our GEO database
                                                                            // We have no region for user-typed countries
                                                                        } else { // Country is in our GEO database, add regions and cities
                                                                            $manager_region = new Region();
                                                                            $regions_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=region&country_code=' .
                                                                                                                  urlencode($countryCode) . '&term=all');
                                                                            $regions = json_decode($regions_json);
                                                                            if(!isset($regions->error)) {

                                                                                if(count($regions) > 0) {
                                                                                    foreach($regions as $r) {
                                                                                        $manager_region->insert(array(
                                                                                            "fk_c_country_code" => $r->country_code,
                                                                                            "s_name" => $r->name
                                                                                        ));
                                                                                    }
                                                                                }
                                                                                unset($regions);
                                                                                unset($regions_json);

                                                                                $manager_city = new City();
                                                                                if(count($countries) > 0) {
                                                                                    foreach($countries as $c) {
                                                                                        $regions = $manager_region->findByCountry( $c->id ) ;
                                                                                        if(!isset($regions->error)) {
                                                                                            if(count($regions) > 0) {
                                                                                                foreach($regions as $region) {
                                                                                                    $cities_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=city&country=' .
                                                                                                                                         urlencode($c->name) . '&region=' . urlencode($region['s_name']) . '&term=all') ;
                                                                                                    $cities = json_decode($cities_json) ;
                                                                                                    if(!isset($cities->error)) {
                                                                                                        if(count($cities) > 0) {
                                                                                                            foreach($cities as $ci) {
                                                                                                                $manager_city->insert(array(
                                                                                                                    "fk_i_region_id" => $region['pk_i_id']
                                                                                                                    ,"s_name" => $ci->name
                                                                                                                    ,"fk_c_country_code" => $ci->country_code
                                                                                                                ));
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                    unset($cities) ;
                                                                                                    unset($cities_json) ;
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        osc_add_flash_ok_message(sprintf(_m('%s has been added as a new country'), $countryName), 'admin');
                                                                    }                                                


                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                            break;
                                            case('edit_country'):   // edit country
                                                                    $countryCode = Params::getParam('country_code');
                                                                    $request     = Params::getParam('e_country');
                                                                    $ok          = true;

                                                                    foreach($request as $k => $v) {
                                                                        $result = $mCountries->updateLocale($countryCode, $k, $v) ;
                                                                        if( $result === false ) {
                                                                            $ok = false ;
                                                                        }
                                                                    }

                                                                    if( $ok ) {
                                                                        osc_add_flash_ok_message(_m('Country has been edited'), 'admin');
                                                                    } else {
                                                                        osc_add_flash_error_message(_m('There were some problems editing the country'), 'admin');
                                                                    }
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                            break;
                                            case('delete_country'): // delete country
                                                                    $countryId = Params::getParam('id');
                                                                    // HAS ITEMS?
                                                                    $has_items = Item::newInstance()->listWhere('l.fk_c_country_code = \'%s\' LIMIT 1', $countryId);
                                                                    if(!$has_items) {
                                                                        $mRegions = new Region();
                                                                        $mCities = new City();

                                                                        $aCountries = $mCountries->findByCode($countryId);
                                                                        $aRegions = $mRegions->findByCountry($aCountries['pk_c_code']);
                                                                        foreach($aRegions as $region) {
                                                                            $mCities->delete(array('fk_i_region_id' => $region['pk_i_id']));
                                                                            $mRegions->delete(array('pk_i_id' => $region['pk_i_id']));
                                                                        }
                                                                        $mCountries->delete(array('pk_c_code' => $aCountries['pk_c_code']));

                                                                        osc_add_flash_ok_message(sprintf(_m('%s has been deleted'), $aCountries['s_name']), 'admin');
                                                                    } else {
                                                                        osc_add_flash_error_message(sprintf(_m('%s can not be deleted, some items are located in it'), $aCountries['s_name']), 'admin');
                                                                    }
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                            break;
                                            case('add_region'):     // add region
                                                                    if( !Params::getParam('r_manual') ) {
                                                                        $this->install_location_by_region();
                                                                    } else {
                                                                        $mRegions    = new Region();
                                                                        $regionName  = Params::getParam('region');
                                                                        $countryCode = Params::getParam('country_c_parent');

                                                                        $exists = $mRegions->findByName($regionName, $countryCode);
                                                                        if(!isset($exists['s_name'])) {
                                                                            $data = array('fk_c_country_code' => $countryCode
                                                                                         ,'s_name' => $regionName);
                                                                            $mRegions->insert($data);
                                                                            osc_add_flash_ok_message(sprintf(_m('%s has been added as a new region'),
                                                                                                             $regionName), 'admin');
                                                                        } else {
                                                                            osc_add_flash_error_message(sprintf(_m('%s already was in the database'),
                                                                                                             $regionName), 'admin');
                                                                        }
                                                                    }
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                            break;
                                            case('edit_region'):    // edit region
                                                                    $mRegions  = new Region();
                                                                    $newRegion = Params::getParam('e_region');
                                                                    $regionId  = Params::getParam('region_id');
                                                                    $exists = $mRegions->findByName($newRegion);
                                                                    if(!$exists['pk_i_id'] || $exists['pk_i_id']==$regionId) {
                                                                        if($regionId != '') {
                                                                            $mRegions->update(array('s_name' => $newRegion)
                                                                                             ,array('pk_i_id' => $regionId));
                                                                            ItemLocation::newInstance()->update(
                                                                                array('s_region'       => $newRegion),
                                                                                array('fk_i_region_id' => $regionId)
                                                                            );
                                                                            osc_add_flash_ok_message(sprintf(_m('%s has been edited'),
                                                                                                              $newRegion), 'admin');
                                                                        }
                                                                    } else {
                                                                        osc_add_flash_error_message(sprintf(_m('%s already was in the database'),
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

                                                                        osc_add_flash_ok_message(sprintf(_m('%s has been deleted'),
                                                                                $aRegion['s_name']), 'admin');
                                                                    }
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                            break;
                                            case('add_city'):       // add city
                                                                    $mCities     = new City();
                                                                    $regionId    = Params::getParam('region_parent');
                                                                    $countryCode = Params::getParam('country_c_parent');
                                                                    $newCity     = Params::getParam('city');

                                                                    $exists = $mCities->findByName($newCity, $regionId);
                                                                    if(!isset($exists['s_name'])) {
                                                                        $mCities->insert(array('fk_i_region_id'    => $regionId
                                                                                              ,'s_name'            => $newCity
                                                                                              ,'fk_c_country_code' => $countryCode));

                                                                        osc_add_flash_ok_message(sprintf(_m('%s has been added as a new city'),
                                                                                                         $newCity), 'admin');
                                                                    } else {
                                                                        osc_add_flash_error_message(sprintf(_m('%s already was in the database'),
                                                                                                         $newCity), 'admin');
                                                                    }
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                            break;
                                            case('edit_city'):      // edit city
                                                                    $mCities = new City();
                                                                    $newCity = Params::getParam('e_city');
                                                                    $cityId  = Params::getParam('city_id');

                                                                    $exists = $mCities->findByName($newCity);
                                                                    if(!isset($exists['pk_i_id']) || $exists['pk_i_id']==$cityId) {
                                                                        $mCities->update(array('s_name' => $newCity)
                                                                                        ,array('pk_i_id' => $cityId));
                                                                        ItemLocation::newInstance()->update(
                                                                            array('s_city'       => $newCity),
                                                                            array('fk_i_city_id' => $cityId)
                                                                        );
                                                                        osc_add_flash_ok_message(sprintf(_m('%s has been edited'),
                                                                                                         $newCity), 'admin');
                                                                    } else {
                                                                        osc_add_flash_error_message(sprintf(_m('%s already was in the database'),
                                                                                                         $newCity), 'admin');
                                                                    }
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                            break;
                                            case('delete_city'):    // delete city
                                                                    $mCities = new City();
                                                                    $cityId  = Params::getParam('id');

                                                                    $aCity   = $mCities->findByPrimaryKey($cityId);
                                                                    $mCities->delete(array('pk_i_id' => $cityId));

                                                                    osc_add_flash_ok_message(sprintf(_m('%s has been deleted'),
                                                                                                     $aCity['s_name']), 'admin');
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                            break;
                                        }

                                        $aCountries = $mCountries->listAllAdmin();
                                        $this->_exportVariableToView('aCountries', $aCountries);

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

                                            require_once ABS_PATH . 'generate_rules.php';
                                            $htaccess = '
    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteBase ' . REL_WEB_URL . '
        RewriteRule ^index\.php$ - [L]
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule . ' . REL_WEB_URL . 'index.php [L]
    </IfModule>';

                                            if( file_exists(osc_base_path() . '.htaccess') ) {
                                                $file_status = 1;
                                            } else if(file_put_contents(osc_base_path() . '.htaccess', $htaccess)) {
                                                $file_status = 2;
                                            } else {
                                                $file_status = 3;
                                            }

                                            if(apache_mod_loaded('mod_rewrite')) {
                                                $htaccess_status = 1;
                                                Preference::newInstance()->update(array('s_value' => '1')
                                                                                 ,array('s_name'  => 'mod_rewrite_loaded'));
                                            } else {
                                                $htaccess_status = 2;
                                                Preference::newInstance()->update(array('s_value' => '0')
                                                                                 ,array('s_name'  => 'mod_rewrite_loaded'));
                                            }
                                        } else {
                                            $modRewrite = apache_mod_loaded('mod_rewrite');
                                            Preference::newInstance()->update(array('s_value' => '0')
                                                                             ,array('s_name'  => 'rewriteEnabled'));
                                            Preference::newInstance()->update(array('s_value' => '0')
                                                                             ,array('s_name'  => 'mod_rewrite_loaded'));
                                        }

                                        $redirectUrl  = osc_admin_base_url(true) . '?page=settings&action=permalinks&htaccess_status=';
                                        $redirectUrl .= $htaccess_status . '&file_status=' . $file_status;
                                        $this->redirectTo($redirectUrl);
                break;
                case('spamNbots'):      // calling the spam and bots view
                                        $this->doView('settings/spamNbots.php');
                break;
                case('spamNbots_post'): // updating spam and bots option
                                        $iUpdated         = 0;
                                        $akismetKey       = Params::getParam('akismetKey');
                                        $akismetKey       = trim($akismetKey);
                                        $recaptchaPrivKey = Params::getParam('recaptchaPrivKey');
                                        $recaptchaPrivKey = trim($recaptchaPrivKey);
                                        $recaptchaPubKey  = Params::getParam('recaptchaPubKey');
                                        $recaptchaPubKey  = trim($recaptchaPubKey);

                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $akismetKey)
                                                                                      ,array('s_name'  => 'akismetKey'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $recaptchaPrivKey)
                                                                                      ,array('s_name'  => 'recaptchaPrivKey'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $recaptchaPubKey)
                                                                                      ,array('s_name'  => 'recaptchaPubKey'));

                                        if($iUpdated > 0) {
                                            osc_add_flash_ok_message( _m('Akismet and reCAPTCHA have been updated') ,'admin');
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=spamNbots');
                break;
                case('currencies'):     // currencies settings
                                        $currencies_action = Params::getParam('type');

                                        switch ($currencies_action) {
                                            case('add'):        // calling add currency view
                                                                $this->doView('settings/add_currency.php');
                                            break;
                                            case('add_post'):   // adding a new currency
                                                                $currencyCode         = Params::getParam('pk_c_code');
                                                                $currencyName         = Params::getParam('s_name');
                                                                $currencyDescription  = Params::getParam('s_description');

                                                                // cleaning parameters
                                                                $currencyName        = strip_tags($currencyName);
                                                                $currencyDescription = strip_tags($currencyDescription);
                                                                $currencyCode        = strip_tags($currencyCode);
                                                                $currencyCode        = trim($currencyCode);

                                                                if(!preg_match('/^.{1,3}$/', $currencyCode)) {
                                                                    osc_add_flash_error_message( _m('Error: the currency code is not in the correct format'), 'admin');
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
                                                                }

                                                                $fields               = array('pk_c_code'     => $currencyCode
                                                                                             ,'s_name'        => $currencyName
                                                                                             ,'s_description' => $currencyDescription);

                                                                $isInserted = Currency::newInstance()->insert($fields);

                                                                if($isInserted) {
                                                                    osc_add_flash_ok_message( _m('New currency has been added'), 'admin');
                                                                } else {
                                                                    osc_add_flash_error_message( _m('Error: currency couldn\'t be added'), 'admin');
                                                                }
                                                                $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
                                            break;
                                            case('edit'):       // calling edit currency view
                                                                $currencyCode = Params::getParam('code');
                                                                $currencyCode = strip_tags($currencyCode);
                                                                $currencyCode = trim($currencyCode);

                                                                if($currencyCode == '') {
                                                                    osc_add_flash_error_message( _m('Error: the currency code is not in the correct format'), 'admin');
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
                                                                }

                                                                $aCurrency = Currency::newInstance()->findByPrimaryKey($currencyCode);

                                                                if(count($aCurrency) == 0) {
                                                                    osc_add_flash_error_message( _m('Error: the currency doesn\'t exist'), 'admin');
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
                                                                }

                                                                $this->_exportVariableToView('aCurrency', $aCurrency);
                                                                $this->doView('settings/edit_currency.php');
                                            break;
                                            case('edit_post'):  // updating currency
                                                                $currencyName        = Params::getParam('s_name');
                                                                $currencyDescription = Params::getParam('s_description');
                                                                $currencyCode        = Params::getParam('pk_c_code');

                                                                // cleaning parameters
                                                                $currencyName        = strip_tags($currencyName);
                                                                $currencyDescription = strip_tags($currencyDescription);
                                                                $currencyCode        = strip_tags($currencyCode);
                                                                $currencyCode        = trim($currencyCode);

                                                                if(!preg_match('/.{1,3}/', $currencyCode)) {
                                                                    osc_add_flash_error_message( _m('Error: the currency code is not in the correct format'), 'admin');
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
                                                                }

                                                                $iUpdated = Currency::newInstance()->update(array('s_name'        => $currencyName
                                                                                                                  ,'s_description' => $currencyDescription)
                                                                                                            ,array('pk_c_code'     => $currencyCode));

                                                                if($iUpdated == 1) {
                                                                    osc_add_flash_ok_message( _m('Currency has been updated'), 'admin');
                                                                }
                                                                $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
                                            break;
                                            case('delete'):     // deleting a currency
                                                                $rowChanged    = 0;
                                                                $aCurrencyCode = Params::getParam('code');

                                                                if(!is_array($aCurrencyCode)) {
                                                                    osc_add_flash_error_message( _m('Error: the currency code is not in the correct format'), 'admin');
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
                                                                }

                                                                $msg_current = '';
                                                                foreach($aCurrencyCode as $currencyCode) {
                                                                    if(preg_match('/.{1,3}/', $currencyCode) && $currencyCode != osc_currency()) {
                                                                        $rowChanged += Currency::newInstance()->delete(array('pk_c_code' => $currencyCode));
                                                                    }
                                                                    if($currencyCode == osc_currency()) {
                                                                        $msg_current = sprintf('. ' . _m("%s could not be deleted because it's the default currency"), $currencyCode) ;
                                                                    }
                                                                }

                                                                $msg = '';
                                                                switch ($rowChanged) {
                                                                    case ('0'): $msg = _m('No currencies have been deleted');
                                                                            osc_add_flash_error_message($msg . $msg_current, 'admin');
                                                                    break;
                                                                    case ('1'): $msg = _m('One currency has been deleted');
                                                                            osc_add_flash_ok_message($msg . $msg_current, 'admin');
                                                                    break;
                                                                    case ('-1'): $msg = sprintf(_m("%s could not be deleted because this currency still in use"), $currencyCode);
                                                                            osc_add_flash_error_message($msg . $msg_current, 'admin');
                                                                    break;
                                                                    default:    $msg = sprintf(_m('%s currencies have been deleted'), $rowChanged);
                                                                            osc_add_flash_ok_message($msg . $msg_current, 'admin');
                                                                    break;
                                                                }

                                                                $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
                                            break;
                                            default:            // calling the currencies view
                                                                $aCurrencies = Currency::newInstance()->listAll();
                                                                $this->_exportVariableToView('aCurrencies', $aCurrencies);

                                                                $this->doView('settings/currencies.php');
                                            break;
                                        }
                break;
                case('mailserver'):     // calling the mailserver view
                                        $this->doView('settings/mailserver.php');
                break;
                case('mailserver_post'):if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action cannot be done because is a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=mailserver');
                                        }
                                        // updating mailserver
                                        $iUpdated           = 0;
                                        $mailserverAuth     = Params::getParam('mailserver_auth');
                                        $mailserverAuth     = ($mailserverAuth != '' ? true : false);
                                        $mailserverPop     = Params::getParam('mailserver_pop');
                                        $mailserverPop     = ($mailserverPop != '' ? true : false);
                                        $mailserverType     = Params::getParam('mailserver_type');
                                        $mailserverHost     = Params::getParam('mailserver_host');
                                        $mailserverPort     = Params::getParam('mailserver_port');
                                        $mailserverUsername = Params::getParam('mailserver_username');
                                        $mailserverPassword = Params::getParam('mailserver_password');
                                        $mailserverSsl      = Params::getParam('mailserver_ssl');

                                        if( !in_array($mailserverType, array('custom', 'gmail')) ) {
                                            osc_add_flash_error_message( _m('Mail server type is incorrect'), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=mailserver');
                                        }

                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverAuth)
                                                                                       ,array('s_name' => 'mailserver_auth'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverPop)
                                                                                       ,array('s_name' => 'mailserver_pop'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverType)
                                                                                       ,array('s_name' => 'mailserver_type'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverHost)
                                                                                       ,array('s_name' => 'mailserver_host'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverPort)
                                                                                       ,array('s_name' => 'mailserver_port'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverUsername)
                                                                                       ,array('s_name' => 'mailserver_username'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverPassword)
                                                                                       ,array('s_name' => 'mailserver_password'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverSsl)
                                                                                       ,array('s_name' => 'mailserver_ssl'));

                                        if($iUpdated > 0) {
                                            osc_add_flash_ok_message( _m('Mail server configuration has changed'), 'admin');
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=mailserver');
                break;
                case('media'):          // calling the media view
                                        $this->doView('settings/media.php') ;
                break;
                case('media_post'):     // updating the media config
                                        $iUpdated          = 0;
                                        $maxSizeKb         = Params::getParam('maxSizeKb');
                                        $allowedExt        = Params::getParam('allowedExt');
                                        $dimThumbnail      = Params::getParam('dimThumbnail');
                                        $dimPreview        = Params::getParam('dimPreview');
                                        $dimNormal         = Params::getParam('dimNormal');
                                        $keepOriginalImage = Params::getParam('keep_original_image');
                                        $use_imagick       = Params::getParam('use_imagick');
                                        $type_watermark    = Params::getParam('watermark_type');
                                        $watermark_color   = Params::getParam('watermark_text_color');
                                        $watermark_text    = Params::getParam('watermark_text');
                                        $watermark_image   = Params::getParam('watermark_image');
                                       
                                        switch ($type_watermark) {
                                            case 'none':
                                                $iUpdated += Preference::newInstance()->update(array('s_value' => '')
                                                                                              ,array('s_name'  => 'watermark_text_color'));
                                                $iUpdated += Preference::newInstance()->update(array('s_value' => '')
                                                                                              ,array('s_name'  => 'watermark_text'));
                                                $iUpdated += Preference::newInstance()->update(array('s_value' => '')
                                                                                              ,array('s_name'  => 'watermark_image'));
                                            break;
                                            case 'text':
                                                $iUpdated += Preference::newInstance()->update(array('s_value' => $watermark_color)
                                                                                              ,array('s_name'  => 'watermark_text_color'));
                                                $iUpdated += Preference::newInstance()->update(array('s_value' => $watermark_text)
                                                                                              ,array('s_name'  => 'watermark_text'));
                                                $iUpdated += Preference::newInstance()->update(array('s_value' => '')
                                                                                              ,array('s_name'  => 'watermark_image'));
                                                $iUpdated += Preference::newInstance()->update(array('s_value' => Params::getParam('watermark_text_place'))
                                                                                              ,array('s_name'  => 'watermark_place'));
                                            break;
                                            case 'image':
                                                // upload image & move to path
                                                if( $_FILES['watermark_image']['error'] == UPLOAD_ERR_OK ) {
                                                    $tmpName = $_FILES['watermark_image']['tmp_name'] ;
                                                    $path = osc_content_path() . 'uploads/watermark.png' ;
                                                    if( move_uploaded_file($tmpName, $path) ){
                                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $path)
                                                                                                      ,array('s_name'  => 'watermark_image'));
                                                    } else {
                                                        $iUpdated += Preference::newInstance()->update(array('s_value' => '')
                                                                                                      ,array('s_name'  => 'watermark_image'));
                                                    }
                                                }
                                                $iUpdated += Preference::newInstance()->update(array('s_value' => '')
                                                                                              ,array('s_name'  => 'watermark_text_color'));
                                                $iUpdated += Preference::newInstance()->update(array('s_value' => '')
                                                                                              ,array('s_name'  => 'watermark_text'));
                                                $iUpdated += Preference::newInstance()->update(array('s_value' => Params::getParam('watermark_image_place'))
                                                                                              ,array('s_name'  => 'watermark_place'));
                                            break;

                                            default:
                                            break;
                                        }
                                        
                                        // format parameters
                                        $maxSizeKb         = strip_tags($maxSizeKb);
                                        $allowedExt        = strip_tags($allowedExt);
                                        $dimThumbnail      = strip_tags($dimThumbnail);
                                        $dimPreview        = strip_tags($dimPreview);
                                        $dimNormal         = strip_tags($dimNormal);
                                        $keepOriginalImage = ($keepOriginalImage != '' ? true : false);
                                        $use_imagick       = ($use_imagick != '' ? true : false);
                                        if(!extension_loaded('imagick')) {
                                            $use_imagick = false;
                                        }

                                        
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $maxSizeKb)
                                                                                      ,array('s_name'  => 'maxSizeKb'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $allowedExt)
                                                                                      ,array('s_name'  => 'allowedExt'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $dimThumbnail)
                                                                                      ,array('s_name'  => 'dimThumbnail'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $dimPreview)
                                                                                      ,array('s_name'  => 'dimPreview'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $dimNormal)
                                                                                      ,array('s_name'  => 'dimNormal'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $keepOriginalImage)
                                                                                      ,array('s_name'  => 'keep_original_image'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $use_imagick)
                                                                                      ,array('s_name'  => 'use_imagick'));

                                        if($iUpdated > 0) {
                                            osc_add_flash_ok_message( _m('Media config has been updated'), 'admin');
                                        }

                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=media');
                break ;
                case('contact'):        // calling the media view
                                        $this->doView('settings/contact.php') ;
                break;
                case('contact_post'):   // updating the media config
                                        $enabled_attachment = Params::getParam('enabled_attachment');
                                        if ($enabled_attachment == '') $enabled_attachment = 0 ;
                                        else $enabled_attachment = 1 ;

                                        // format parameters
                                        $iUpdated = Preference::newInstance()->update(array('s_value' => $enabled_attachment)
                                                                                      ,array('s_name'  => 'contact_attachment'));

                                        if($iUpdated > 0) {
                                            osc_add_flash_ok_message( _m('Contact configuration has been updated'), 'admin');
                                        }

                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=contact');
                break ;
                case('cron'):           // viewing the cron view
                                        $this->doView('settings/cron.php');
                break;
                case('cron_post'):      // updating cron config
                                        $iUpdated  = 0;
                                        $bAutoCron = Params::getParam('auto_cron');
                                        $bAutoCron = ($bAutoCron != '' ? true : false);

                                        $iUpdated  += Preference::newInstance()->update(array('s_value' => $bAutoCron)
                                                                                       ,array('s_name' => 'auto_cron'));

                                        if($iUpdated > 0) {
                                            osc_add_flash_ok_message( _m('Cron config has been updated'), 'admin');
                                        }

                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=cron');
                break;
                case('update'):         // update index view
                                        $iUpdated       = 0;
                                        $sPageTitle     = Params::getParam('pageTitle');
                                        $sPageDesc      = Params::getParam('pageDesc');
                                        $sContactEmail  = Params::getParam('contactEmail');
                                        $sLanguage      = Params::getParam('language');
                                        $sDateFormat    = Params::getParam('dateFormat');
                                        $sCurrency      = Params::getParam('currency');
                                        $sWeekStart     = Params::getParam('weekStart');
                                        $sTimeFormat    = Params::getParam('timeFormat');
                                        $sTimezone      = Params::getParam('timezone');
                                        $sNumRssItems   = Params::getParam('num_rss_items');
                                        $maxLatestItems = Params::getParam('max_latest_items_at_home');
                                        $numItemsSearch = Params::getParam('default_results_per_page') ;

                                        // preparing parameters
                                        $sPageTitle     = strip_tags($sPageTitle);
                                        $sPageDesc      = strip_tags($sPageDesc);
                                        $sContactEmail  = strip_tags($sContactEmail);
                                        $sLanguage      = strip_tags($sLanguage);
                                        $sDateFormat    = strip_tags($sDateFormat);
                                        $sCurrency      = strip_tags($sCurrency);
                                        $sWeekStart     = strip_tags($sWeekStart);
                                        $sTimeFormat    = strip_tags($sTimeFormat);
                                        $sNumRssItems   = (int) strip_tags($sNumRssItems);
                                        $maxLatestItems = (int) strip_tags($maxLatestItems);
                                        $numItemsSearch = (int) $numItemsSearch ;

                                        $error = "";

                                        $iUpdated += Preference::newInstance()->update(array('s_value'   => $sPageTitle)
                                                                                      ,array('s_section' => 'osclass', 's_name' => 'pageTitle'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value'   => $sPageDesc)
                                                                                      ,array('s_section' => 'osclass', 's_name' => 'pageDesc'));
                                        if( !defined('DEMO') ) {
                                            $iUpdated += Preference::newInstance()->update(array('s_value'   => $sContactEmail)
                                                                                          ,array('s_section' => 'osclass', 's_name' => 'contactEmail'));
                                        }
                                        $iUpdated += Preference::newInstance()->update(array('s_value'   => $sLanguage)
                                                                                      ,array('s_section' => 'osclass', 's_name' => 'language'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value'   => $sDateFormat)
                                                                                      ,array('s_section' => 'osclass', 's_name' => 'dateFormat'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value'   => $sCurrency)
                                                                                      ,array('s_section' => 'osclass', 's_name' => 'currency'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value'   => $sWeekStart)
                                                                                      ,array('s_section' => 'osclass', 's_name' => 'weekStart'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value'   => $sTimeFormat)
                                                                                      ,array('s_section' => 'osclass', 's_name' => 'timeFormat'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value'   => $sTimezone)
                                                                                      ,array('s_section' => 'osclass', 's_name' => 'timezone'));
                                        if(is_int($sNumRssItems)) {
                                            $iUpdated += Preference::newInstance()->update(array('s_value'   => $sNumRssItems)
                                                                                          ,array('s_section' => 'osclass', 's_name' => 'num_rss_items'));
                                        } else {
                                            if($error != '') $error .= "<br/>";
                                            $error .= _m('Number of items in the RSS must be integer');
                                        }

                                        if(is_int($maxLatestItems)) {
                                            $iUpdated += Preference::newInstance()->update(array('s_value'   => $maxLatestItems)
                                                                                          ,array('s_section' => 'osclass', 's_name' => 'maxLatestItems@home'));
                                        } else {
                                            if($error != '') $error .= "<br/>";
                                            $error .= _m('Number of recent items displayed at home must be integer');
                                        }

                                        $iUpdated += Preference::newInstance()->update(
                                                array('s_value'   => $numItemsSearch),
                                                array('s_section' => 'osclass',
                                                      's_name'    => 'defaultResultsPerPage@search')
                                        );

                                        if($iUpdated > 0) {
                                            if($error != '') {
                                                osc_add_flash_error_message( $error . "<br/>" . _m('General settings have been updated'), 'admin');
                                            } else {
                                                osc_add_flash_ok_message( _m('General settings have been updated'), 'admin');
                                            }
                                        } else if($error != '') {
                                            osc_add_flash_error_message( $error , 'admin');
                                        }

                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings');
                break;
                case('latestsearches'):       //calling the comments settings view
                                        $this->doView('settings/searches.php');
                break;
                case('latestsearches_post'):  // updating comment
                                        if(Params::getParam('save_latest_searches')=='on') {
                                            Preference::newInstance()->update(array('s_value' => 1)
                                                                                ,array('s_name' => 'save_latest_searches'));
                                        } else {
                                            Preference::newInstance()->update(array('s_value' => 0)
                                                                                ,array('s_name' => 'save_latest_searches'));
                                        }
                                        Preference::newInstance()->update(array('s_value' => Params::getParam('customPurge'))
                                                                                ,array('s_name' => 'purge_latest_searches'));
                                        osc_add_flash_ok_message( _m('Settings have been updated'), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=latestsearches');
                break;
                default:                // calling the view
                                        $aLanguages = OSCLocale::newInstance()->listAllEnabled() ;
                                        $aCurrencies = Currency::newInstance()->listAll() ;

                                        $this->_exportVariableToView('aLanguages', $aLanguages);
                                        $this->_exportVariableToView('aCurrencies', $aCurrencies);

                                        $this->doView('settings/index.php') ;
                break;
            }
        }

        //hopefully generic...
        function doView($file) {
            osc_current_admin_theme_path($file) ;
            Session::newInstance()->_clearVariables();
        }

        function install_location_by_country() {
            $country_code    = Params::getParam('c_country');
            $aCountryCode[] = trim($country_code);
            
            $manager_country = new Country();
            $countries_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=country_id&term=' .
                                                     urlencode(implode(',', $aCountryCode)) );

            $countries = json_decode($countries_json);
            if(isset($countries->error)) {
                osc_add_flash_error_message(sprintf(_m('%s cannot be added'), $country), 'admin');
                return false;
            }

            foreach($countries as $c) {
                $exists = $manager_country->findByCode($c->id);
                if(isset($exists['s_name'])) {
                    osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $exists['s_name']), 'admin');
                    return false;
                }
                $manager_country->insert(array(
                    "pk_c_code" => $c->id
                    ,"fk_c_locale_code" => $c->locale_code
                    ,"s_name" => $c->name
                ));
            }

            $manager_region = new Region();
            $regions_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=region&country_id=' .
                                                  urlencode(implode(',', $aCountryCode)) . '&term=all');
            $regions = json_decode($regions_json);
            foreach($regions as $r) {
                $manager_region->insert(array(
                    "fk_c_country_code" => $r->country_code,
                    "s_name" => $r->name
                ));
            }
            unset($regions);
            unset($regions_json);

            $manager_city = new City();
            foreach($countries as $c) {
                $regions = $manager_region->finbByCountry( $c->id );
                foreach($regions as $region) {
                    $cities_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=city&country=' .
                                                         urlencode($c->name) . '&region=' . urlencode($region['s_name']) . '&term=all') ;
                    $cities = json_decode($cities_json) ;
                    if(!isset($cities->error)) {
                        foreach($cities as $ci) {
                            $manager_city->insert(array(
                                "fk_i_region_id" => $region['pk_i_id']
                                ,"s_name" => $ci->name
                                ,"fk_c_country_code" => $ci->country_code
                            ));
                        }
                    }
                    unset($cities) ;
                    unset($cities_json) ;
                }
            }

            osc_add_flash_ok_message(sprintf(_m('%s has been added as a new country'), $country), 'admin');
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
                                                  urlencode(implode(',', $aCountry)) . '&term=' . urlencode(implode(',', $aRegion)));
            $regions = json_decode($regions_json);
            if(isset($regions->error)) {
                osc_add_flash_error_message(sprintf(_m('%s cannot be added'), $region), 'admin');
                return false;
            }

            foreach($regions as $r) {
                $exists = $manager_region->findByName($r->name, $r->country_code);
                if(isset($exists['s_name'])) {
                    osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $c_exists['s_name']), 'admin');
                    return false;
                }
                $manager_region->insert(array(
                    "fk_c_country_code" => $r->country_code,
                    "s_name" => $r->name
                ));
            }
            unset($regions);
            unset($regions_json);

            $manager_city = new City();
            foreach($country as $c) {
                $regions = $manager_region->findByName($region, $country['pk_c_code']);
                $cities_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=city&country=' .
                                                     urlencode($c) . '&region=' . urlencode($regions['s_name']) . '&term=all');
                $cities = json_decode($cities_json);
                if(!isset($cities->error)) {
                    foreach($cities as $ci) {
                        $manager_city->insert(array(
                            "fk_i_region_id" => $regions['pk_i_id'],
                            "s_name" => $ci->name,
                            "fk_c_country_code" => $ci->country_code
                        ));
                    }
                }
                unset($cities);
                unset($cities_json);
            }

            osc_add_flash_ok_message(sprintf(_m('%s has been added as a region of %s'), $region, $country['s_name']), 'admin');
        }
    }

?>
