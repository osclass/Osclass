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
        function __construct()
        {
            parent::__construct() ;
        }

        //Business Layer...
        function doModel()
        {
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

                                        $msg = '';
                                        if(!osc_validate_int(Params::getParam("num_moderate_comments"))) {
                                            $msg .= _m("Number of moderate comments must only contain numeric characters")."<br/>";
                                        }
                                        if(!osc_validate_int(Params::getParam("comments_per_page"))) {
                                            $msg .= _m("Comments per page must only contain numeric characters")."<br/>";
                                        }
                                        if($msg!='') {
                                            osc_add_flash_error_message( $msg, 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=comments');
                                        }

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
                                            osc_add_flash_ok_message( _m("Comment settings have been updated"), 'admin');
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=comments');
                break;
                case ('locations'):     // calling the locations settings view
                                        $location_action = Params::getParam('type');
                                        $mCountries = new Country();

                                        switch ($location_action) {
                                            case('add_country'):    // add country
                                                                    $countryCode = strtoupper(Params::getParam('c_country'));
                                                                    $countryName = Params::getParam('country');

                                                                    $exists = $mCountries->findByCode($countryCode);
                                                                    if(isset($exists['s_name'])) {
                                                                        osc_add_flash_error_message(sprintf(_m('%s already was in the database'),
                                                                                                      $countryName), 'admin');
                                                                    } else {
                                                                        $countries_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=country_code&term=' .
                                                                                                                 urlencode($countryCode) );
                                                                        $countries = json_decode($countries_json);
                                                                        $mCountries->insert(array('pk_c_code' => $countryCode,
                                                                                                  's_name' => $countryName));
                                                                        CountryStats::newInstance()->setNumItems($countryCode, 0);
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
                                                                                        $id = $manager_region->dao->insertedId();
                                                                                        RegionStats::newInstance()->setNumItems($id, 0);
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
                                                                                                                $id = $manager_city->dao->insertedId();
                                                                                                                CityStats::newInstance()->setNumItems($id, 0);
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
                                                                    $ok = $mCountries->update(array('s_name'=> Params::getParam('e_country')), array('pk_c_code' => Params::getParam('country_code')));

                                                                    if( $ok ) {
                                                                        osc_add_flash_ok_message(_m('Country has been edited'), 'admin');
                                                                    } else {
                                                                        osc_add_flash_error_message(_m('There were some problems editing the country'), 'admin');
                                                                    }
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                            break;
                                            case('delete_country'): // delete country
                                                                    $countryId = Params::getParam('id');

                                                                    Item::newInstance()->deleteByRegion($countryId);
                                                                    $mRegions = new Region();
                                                                    $mCities = new City();

                                                                    $aCountries = $mCountries->findByCode($countryId);
                                                                    $aRegions = $mRegions->findByCountry($aCountries['pk_c_code']);
                                                                    foreach($aRegions as $region) {
                                                                        // remove city_stats
                                                                        CityStats::newInstance()->deleteByRegion($region['pk_i_id']) ;
                                                                        // remove region_stats
                                                                        RegionStats::newInstance()->delete( array('fk_i_region_id' => $region['pk_i_id']) ) ;
                                                                    }
                                                                    //remove country stats
                                                                    CountryStats::newInstance()->delete( array('fk_c_country_code' => $aCountries['pk_c_code'] ) ) ;
                                                                    $ok = $mCountries->deleteByPrimaryKey($aCountries['pk_c_code']);

                                                                    if($ok) {
                                                                        osc_add_flash_ok_message(sprintf(_m('%s has been deleted'), $aCountries['s_name']), 'admin');
                                                                    } else {
                                                                        osc_add_flash_error_message(sprintf(_m('There was a problem deleting %s'), $aCountries['s_name']), 'admin');
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
                                                                        $country     = Country::newInstance()->findByCode($countryCode);

                                                                        $exists = $mRegions->findByName($regionName, $countryCode);
                                                                        if(!isset($exists['s_name'])) {
                                                                            $data = array('fk_c_country_code' => $countryCode
                                                                                         ,'s_name' => $regionName);
                                                                            $mRegions->insert($data);
                                                                            $id = $mRegions->dao->insertedId();
                                                                            RegionStats::newInstance()->setNumItems($id, 0);
                                                                            osc_add_flash_ok_message(sprintf(_m('%s has been added as a new region'),
                                                                                                             $regionName), 'admin');
                                                                        } else {
                                                                            osc_add_flash_error_message(sprintf(_m('%s already was in the database'),
                                                                                                             $regionName), 'admin');
                                                                        }
                                                                    }
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations&country_code='.@$countryCode."&country=".@$country['s_name']);
                                            break;
                                            case('edit_region'):    // edit region
                                                                    $mRegions  = new Region();
                                                                    $newRegion = Params::getParam('e_region');
                                                                    $regionId  = Params::getParam('region_id');
                                                                    $exists = $mRegions->findByName($newRegion);
                                                                    if(!isset($exists['pk_i_id']) || $exists['pk_i_id']==$regionId) {
                                                                        if($regionId != '') {
                                                                            $aRegion = $mRegions->findByPrimaryKey($regionId);
                                                                            $country     = Country::newInstance()->findByCode($aRegion['fk_c_country_code']);
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
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations&country_code='.@$country['pk_c_code']."&country=".@$country['s_name']);
                                            break;
                                            case('delete_region'):  // delete region
                                                                    $mRegion  = new Region();
                                                                    $mCities  = new City();

                                                                    $regionId = Params::getParam('id');

                                                                    if($regionId != '') {
                                                                        Item::newInstance()->deleteByRegion($regionId);
                                                                        $aRegion = $mRegion->findByPrimaryKey($regionId);
                                                                        $country = Country::newInstance()->findByCode($aRegion['fk_c_country_code']);

                                                                        // remove city_stats
                                                                        CityStats::newInstance()->deleteByRegion($regionId) ;
                                                                        $mCities->delete(array('fk_i_region_id' => $regionId));
                                                                        // remove region_stats
                                                                        RegionStats::newInstance()->delete( array('fk_i_region_id' => $regionId) ) ;
                                                                        $mRegion->delete(array('pk_i_id' => $regionId));

                                                                        osc_add_flash_ok_message(sprintf(_m('%s has been deleted'),
                                                                                $aRegion['s_name']), 'admin');
                                                                    }
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations&country_code='.@$country['pk_c_code']."&country=".@$country['s_name']);
                                            break;
                                            case('add_city'):       // add city
                                                                    $mRegion  = new Region();
                                                                    $mCities     = new City();
                                                                    $regionId    = Params::getParam('region_parent');
                                                                    $countryCode = Params::getParam('country_c_parent');
                                                                    $newCity     = Params::getParam('city');

                                                                    $exists = $mCities->findByName($newCity, $regionId);
                                                                    $region = $mRegion->findByPrimaryKey($regionId);
                                                                    $country = Country::newInstance()->findByCode($region['fk_c_country_code']);
                                                                    if(!isset($exists['s_name'])) {
                                                                        $mCities->insert(array('fk_i_region_id'    => $regionId
                                                                                              ,'s_name'            => $newCity
                                                                                              ,'fk_c_country_code' => $countryCode));
                                                                        $id = $mCities->dao->insertedId();
                                                                        CityStats::newInstance()->setNumItems($id, 0);

                                                                        osc_add_flash_ok_message(sprintf(_m('%s has been added as a new city'),
                                                                                                         $newCity), 'admin');
                                                                    } else {
                                                                        osc_add_flash_error_message(sprintf(_m('%s already was in the database'),
                                                                                                         $newCity), 'admin');
                                                                    }
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations&country_code='.@$country['pk_c_code']."&country=".@$country['s_name']."&region=".$regionId);
                                            break;
                                            case('edit_city'):      // edit city
                                                                    $mRegion  = new Region();
                                                                    $mCities = new City();
                                                                    $newCity = Params::getParam('e_city');
                                                                    $cityId  = Params::getParam('city_id');

                                                                    $exists = $mCities->findByName($newCity);
                                                                    if(!isset($exists['pk_i_id']) || $exists['pk_i_id']==$cityId) {
                                                                        $city = $mCities->findByPrimaryKey($cityId);
                                                                        $region = $mRegion->findByPrimaryKey($city['fk_i_region_id']);
                                                                        $country = Country::newInstance()->findByCode($region['fk_c_country_code']);
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
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations&country_code='.@$country['pk_c_code']."&country=".@$country['s_name']."&region=".@$region['pk_i_id']);
                                            break;
                                            case('delete_city'):    // delete city
                                                                    $mRegion  = new Region();
                                                                    $mCities = new City();
                                                                    $cityId  = Params::getParam('id');
                                                                    Item::newInstance()->deleteByCity($cityId);
                                                                    $aCity   = $mCities->findByPrimaryKey($cityId);
                                                                    // remove region_stats
                                                                    $region = $mRegion->findByPrimaryKey($aCity['fk_i_region_id']);
                                                                    $country = Country::newInstance()->findByCode($region['fk_c_country_code']);
                                                                    CityStats::newInstance()->delete( array('fk_i_city_id' => $cityId) ) ;
                                                                    $mCities->delete(array('pk_i_id' => $cityId));

                                                                    osc_add_flash_ok_message(sprintf(_m('%s has been deleted'),
                                                                                                     $aCity['s_name']), 'admin');
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations&country_code='.@$country['pk_c_code']."&country=".@$country['s_name']."&region=".@$region['pk_i_id']);
                                            break;
                                        }

                                        $aCountries = $mCountries->listAll();
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
                                        $htaccess_file  = osc_base_path() . '.htaccess' ;
                                        $rewriteEnabled = (Params::getParam('rewrite_enabled') ? true : false) ;

                                        if( $rewriteEnabled ) {
                                            Preference::newInstance()->update(array('s_value' => '1')
                                                                             ,array('s_name' => 'rewriteEnabled') ) ;

                                            $rewrite_base = REL_WEB_URL ;
                                            $htaccess     = <<<HTACCESS
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase {$rewrite_base}
    RewriteRule ^index\.php$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . {$rewrite_base}index.php [L]
</IfModule>
HTACCESS;

                                            // 1. OK (ok)
                                            // 2. OK no apache module detected (warning)
                                            // 3. No se puede crear + apache
                                            // 4. No se puede crear + no apache
                                            $status = 3 ;
                                            if( file_exists($htaccess_file) ) {
                                                if( is_writable($htaccess_file) && file_put_contents($htaccess_file, $htaccess) ) {
                                                    $status = 1 ;
                                                }
                                            } else {
                                                if( is_writable(osc_base_path()) && file_put_contents($htaccess_file, $htaccess) ) {
                                                    $status = 1 ;
                                                }
                                            }

                                            if( !@apache_mod_loaded('mod_rewrite') ) {
                                                $status++ ;
                                            }

                                            $errors = 0;
                                            $item_url = substr(str_replace('//', '/', Params::getParam('rewrite_item_url').'/'), 0, -1);
                                            if(!osc_validate_text($item_url)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $item_url)
                                                                                 ,array('s_name' => 'rewrite_item_url'));
                                            }
                                            $page_url = substr(str_replace('//', '/', Params::getParam('rewrite_page_url').'/'), 0, -1);
                                            if(!osc_validate_text($page_url)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $page_url)
                                                                                 ,array('s_name' => 'rewrite_page_url'));
                                            }
                                            $cat_url = substr(str_replace('//', '/', Params::getParam('rewrite_cat_url').'/'), 0, -1);
                                            if(!osc_validate_text($cat_url)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $cat_url)
                                                                                 ,array('s_name' => 'rewrite_cat_url'));
                                            }
                                            $search_url = substr(str_replace('//', '/', Params::getParam('rewrite_search_url').'/'), 0, -1);
                                            if(!osc_validate_text($search_url)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $search_url)
                                                                                 ,array('s_name' => 'rewrite_search_url'));
                                            }

                                            if(!osc_validate_text(Params::getParam('rewrite_search_country'))) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => Params::getParam('rewrite_search_country'))
                                                                                 ,array('s_name' => 'rewrite_search_country'));
                                            }
                                            if(!osc_validate_text(Params::getParam('rewrite_search_region'))) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => Params::getParam('rewrite_search_region'))
                                                                                 ,array('s_name' => 'rewrite_search_region'));
                                            }
                                            if(!osc_validate_text(Params::getParam('rewrite_search_city'))) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => Params::getParam('rewrite_search_city'))
                                                                                 ,array('s_name' => 'rewrite_search_city'));
                                            }
                                            if(!osc_validate_text(Params::getParam('rewrite_search_city_area'))) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => Params::getParam('rewrite_search_city_area'))
                                                                                 ,array('s_name' => 'rewrite_search_city_area'));
                                            }
                                            if(!osc_validate_text(Params::getParam('rewrite_search_category'))) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => Params::getParam('rewrite_search_category'))
                                                                                 ,array('s_name' => 'rewrite_search_category'));
                                            }
                                            if(!osc_validate_text(Params::getParam('rewrite_search_user'))) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => Params::getParam('rewrite_search_user'))
                                                                                 ,array('s_name' => 'rewrite_search_user'));
                                            }
                                            if(!osc_validate_text(Params::getParam('rewrite_search_pattern'))) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => Params::getParam('rewrite_search_pattern'))
                                                                                 ,array('s_name' => 'rewrite_search_pattern'));
                                            }

                                            $rewrite_contact = substr(str_replace('//', '/', Params::getParam('rewrite_contact').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_contact)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_contact)
                                                                                 ,array('s_name' => 'rewrite_contact'));
                                            }
                                            $rewrite_feed = substr(str_replace('//', '/', Params::getParam('rewrite_feed').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_feed)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_feed)
                                                                                 ,array('s_name' => 'rewrite_feed'));
                                            }
                                            $rewrite_language = substr(str_replace('//', '/', Params::getParam('rewrite_language').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_language)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_language)
                                                                                 ,array('s_name' => 'rewrite_language'));
                                            }
                                            $rewrite_item_mark = substr(str_replace('//', '/', Params::getParam('rewrite_item_mark').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_item_mark)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_item_mark)
                                                                                 ,array('s_name' => 'rewrite_item_mark'));
                                            }
                                            $rewrite_item_send_friend = substr(str_replace('//', '/', Params::getParam('rewrite_item_send_friend').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_item_send_friend)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_item_send_friend)
                                                                                 ,array('s_name' => 'rewrite_item_send_friend'));
                                            }
                                            $rewrite_item_contact = substr(str_replace('//', '/', Params::getParam('rewrite_item_contact').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_item_contact)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_item_contact)
                                                                                 ,array('s_name' => 'rewrite_item_contact'));
                                            }
                                            $rewrite_item_new = substr(str_replace('//', '/', Params::getParam('rewrite_item_new').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_item_new)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_item_new)
                                                                                 ,array('s_name' => 'rewrite_item_new'));
                                            }
                                            $rewrite_item_activate = substr(str_replace('//', '/', Params::getParam('rewrite_item_activate').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_item_activate)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_item_activate)
                                                                                 ,array('s_name' => 'rewrite_item_activate'));
                                            }
                                            $rewrite_item_edit = substr(str_replace('//', '/', Params::getParam('rewrite_item_edit').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_item_edit)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_item_edit)
                                                                                 ,array('s_name' => 'rewrite_item_edit'));
                                            }
                                            $rewrite_item_delete = substr(str_replace('//', '/', Params::getParam('rewrite_item_delete').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_item_delete)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_item_delete)
                                                                                 ,array('s_name' => 'rewrite_item_delete'));
                                            }
                                            $rewrite_item_resource_delete = substr(str_replace('//', '/', Params::getParam('rewrite_item_resource_delete').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_item_resource_delete)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_item_resource_delete)
                                                                                 ,array('s_name' => 'rewrite_item_resource_delete'));
                                            }
                                            $rewrite_user_login = substr(str_replace('//', '/', Params::getParam('rewrite_user_login').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_user_login)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_user_login)
                                                                                 ,array('s_name' => 'rewrite_user_login'));
                                            }
                                            $rewrite_user_dashboard = substr(str_replace('//', '/', Params::getParam('rewrite_user_dashboard').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_user_dashboard)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_user_dashboard)
                                                                                 ,array('s_name' => 'rewrite_user_dashboard'));
                                            }
                                            $rewrite_user_logout = substr(str_replace('//', '/', Params::getParam('rewrite_user_logout').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_user_logout)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_user_logout)
                                                                                 ,array('s_name' => 'rewrite_user_logout'));
                                            }
                                            $rewrite_user_register = substr(str_replace('//', '/', Params::getParam('rewrite_user_register').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_user_register)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_user_register)
                                                                                 ,array('s_name' => 'rewrite_user_register'));
                                            }
                                            $rewrite_user_activate = substr(str_replace('//', '/', Params::getParam('rewrite_user_activate').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_user_activate)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_user_activate)
                                                                                 ,array('s_name' => 'rewrite_user_activate'));
                                            }
                                            $rewrite_user_activate_alert = substr(str_replace('//', '/', Params::getParam('rewrite_user_activate_alert').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_user_activate_alert)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_user_activate_alert)
                                                                                 ,array('s_name' => 'rewrite_user_activate_alert'));
                                            }
                                            $rewrite_user_profile = substr(str_replace('//', '/', Params::getParam('rewrite_user_profile').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_user_profile)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_user_profile)
                                                                                 ,array('s_name' => 'rewrite_user_profile'));
                                            }
                                            $rewrite_user_items = substr(str_replace('//', '/', Params::getParam('rewrite_user_items').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_user_items)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_user_items)
                                                                                 ,array('s_name' => 'rewrite_user_items'));
                                            }
                                            $rewrite_user_alerts = substr(str_replace('//', '/', Params::getParam('rewrite_user_alerts').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_user_alerts)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_user_alerts)
                                                                                 ,array('s_name' => 'rewrite_user_alerts'));
                                            }
                                            $rewrite_user_recover = substr(str_replace('//', '/', Params::getParam('rewrite_user_recover').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_user_recover)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_user_recover)
                                                                                 ,array('s_name' => 'rewrite_user_recover'));
                                            }
                                            $rewrite_user_forgot = substr(str_replace('//', '/', Params::getParam('rewrite_user_forgot').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_user_forgot)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_user_forgot)
                                                                                 ,array('s_name' => 'rewrite_user_forgot'));
                                            }
                                            $rewrite_user_change_password = substr(str_replace('//', '/', Params::getParam('rewrite_user_change_password').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_user_change_password)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_user_change_password)
                                                                                 ,array('s_name' => 'rewrite_user_change_password'));
                                            }
                                            $rewrite_user_change_email = substr(str_replace('//', '/', Params::getParam('rewrite_user_change_email').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_user_change_email)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_user_change_email)
                                                                                 ,array('s_name' => 'rewrite_user_change_email'));
                                            }
                                            $rewrite_user_change_email_confirm = substr(str_replace('//', '/', Params::getParam('rewrite_user_change_email_confirm').'/'), 0, -1);
                                            if(!osc_validate_text($rewrite_user_change_email_confirm)) {
                                                $errors += 1;
                                            } else {
                                                Preference::newInstance()->update(array('s_value' => $rewrite_user_change_email_confirm)
                                                                                 ,array('s_name' => 'rewrite_user_change_email_confirm'));
                                            }

                                            osc_reset_preferences();

                                            $rewrite = Rewrite::newInstance();
                                            osc_run_hook("before_rewrite_rules", array(&$rewrite));
                                            $rewrite->clearRules();

                                            /*****************************
                                             ********* Add rules *********
                                             *****************************/

                                            // Contact rules
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_contact').'/?$', 'index.php?page=contact');

                                            // Feed rules
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_feed').'/?$', 'index.php?page=search&sFeed=rss');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_feed').'/(.+)/?$', 'index.php?page=search&sFeed=$1');

                                            // Language rules
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_language').'/(.*?)/?$', 'index.php?page=language&locale=$1');

                                            // Search rules
                                            $rewrite->addRule('^'.$search_url.'$', 'index.php?page=search');
                                            $rewrite->addRule('^'.$search_url.'/(.*)$', 'index.php?page=search&sParams=$1');

                                            // Item rules
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_item_mark').'/(.*?)/([0-9]+)/?$', 'index.php?page=item&action=mark&as=$1&id=$2');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_item_send_friend').'/([0-9]+)/?$', 'index.php?page=item&action=send_friend&id=$1');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_item_contact').'/([0-9]+)/?$', 'index.php?page=item&action=contact&id=$1');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_item_new').'/?$', 'index.php?page=item&action=item_add');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_item_new').'/([0-9]+)/?$', 'index.php?page=item&action=item_add&catId=$1');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_item_activate').'/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=activate&id=$1&secret=$2');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_item_edit').'/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=item_edit&id=$1&secret=$2');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_item_delete').'/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=item_delete&id=$1&secret=$2');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_item_resource_delete').'/([0-9]+)/([0-9]+)/([0-9A-Za-z]+)/?(.*?)/?$', 'index.php?page=item&action=deleteResource&id=$1&item=$2&code=$3&secret=$4');

                                            // Item rules
                                            $id_pos = stripos($item_url, '{ITEM_ID}');
                                            $title_pos = stripos($item_url, '{ITEM_TITLE}');
                                            $cat_pos = stripos($item_url, '{CATEGORIES');
                                            $param_pos = 1;
                                            if($title_pos!==false && $id_pos>$title_pos) {
                                                $param_pos++;
                                            }
                                            if($cat_pos!==false && $id_pos>$cat_pos) {
                                                $param_pos++;
                                            }
                                            $comments_pos = 1;
                                            if($id_pos!==false) { $comments_pos++; }
                                            if($title_pos!==false) { $comments_pos++; }
                                            if($cat_pos!==false) { $comments_pos++; }
                                            $rewrite->addRule('^'. str_replace('{ITEM_CITY}', '.*', str_replace('{CATEGORIES}', '.*', str_replace('{ITEM_TITLE}', '.*', str_replace('{ITEM_ID}', '([0-9]+)', $item_url.'\?comments-page=([0-9al]*)')))).'$', 'index.php?page=item&id=$1&comments-page=$2');
                                            $rewrite->addRule('^([a-z]{2})_([A-Z]{2})/'. str_replace('{ITEM_CITY}', '.*', str_replace('{CATEGORIES}', '.*', str_replace('{ITEM_TITLE}', '.*', str_replace('{ITEM_ID}', '([0-9]+)', $item_url.'\?comments-page=([0-9al]*)')))).'$', 'index.php?page=item&id=$3&lang=$1_$2&comments-page=$4');
                                            $rewrite->addRule('^'. str_replace('{ITEM_CITY}', '.*', str_replace('{CATEGORIES}', '.*', str_replace('{ITEM_TITLE}', '.*', str_replace('{ITEM_ID}', '([0-9]+)', $item_url)))).'$', 'index.php?page=item&id=$1');
                                            $rewrite->addRule('^([a-z]{2})_([A-Z]{2})/'. str_replace('{ITEM_CITY}', '.*', str_replace('{CATEGORIES}', '.*', str_replace('{ITEM_TITLE}', '.*', str_replace('{ITEM_ID}', '([0-9]+)', $item_url)))).'$', 'index.php?page=item&id=$3&lang=$1_$2');

                                            // User rules
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_user_login').'/?$', 'index.php?page=login');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_user_dashboard').'/?$', 'index.php?page=user&action=dashboard');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_user_logout').'/?$', 'index.php?page=main&action=logout');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_user_register').'/?$', 'index.php?page=register&action=register');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_user_activate').'/([0-9]+)/(.*?)/?$', 'index.php?page=register&action=validate&id=$1&code=$2');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_user_activate_alert').'/([a-zA-Z0-9]+)/(.+)$', 'index.php?page=user&action=activate_alert&email=$2&secret=$1');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_user_profile').'/?$', 'index.php?page=user&action=profile');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_user_profile').'/([0-9]+)/?$', 'index.php?page=user&action=pub_profile&id=$1');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_user_items').'/?$', 'index.php?page=user&action=items');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_user_alerts').'/?$', 'index.php?page=user&action=alerts');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_user_recover').'/?$', 'index.php?page=login&action=recover');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_user_forgot').'/([0-9]+)/(.*)/?$', 'index.php?page=login&action=forgot&userId=$1&code=$2');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_user_change_password').'/?$', 'index.php?page=user&action=change_password');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_user_change_email').'/?$', 'index.php?page=user&action=change_email');
                                            $rewrite->addRule('^'.osc_get_preference('rewrite_user_change_email_confirm').'/([0-9]+)/(.*?)/?$', 'index.php?page=user&action=change_email_confirm&userId=$1&code=$2');

                                            // Page rules
                                            $pos_pID   = stripos($page_url, '{PAGE_ID}') ;
                                            $pos_pSlug = stripos($page_url, '{PAGE_SLUG}') ;
                                            $pID_pos   = 1 ;
                                            $pSlug_pos = 1 ;
                                            if( is_numeric($pos_pID) && is_numeric($pos_pSlug) ) {
                                                // set the order of the parameters
                                                if($pos_pID > $pos_pSlug) {
                                                    $pID_pos++;
                                                } else {
                                                    $pSlug_pos++;
                                                }

                                                $rewrite->addRule('^' . str_replace('{PAGE_SLUG}', '([\p{L}\p{N}_\-,]+)', str_replace('{PAGE_ID}', '([0-9]+)', $page_url)) . '/?$', 'index.php?page=page&id=$' . $pID_pos . "&slug=$" . $pSlug_pos) ;
                                                $rewrite->addRule('^([a-z]{2})_([A-Z]{2})/' . str_replace('{PAGE_SLUG}', '([\p{L}\p{N}_\-,]+)', str_replace('{PAGE_ID}', '([0-9]+)', $page_url)) . '/?$', 'index.php?page=page&lang=$1_$2&id=$' . ($pID_pos + 2) . '&slug=$' . ($pSlug_pos + 2) ) ;
                                            } else if( is_numeric($pos_pID) ) {
                                                $rewrite->addRule('^' .  str_replace('{PAGE_ID}', '([0-9]+)', $page_url) . '/?$', 'index.php?page=page&id=$1') ;
                                                $rewrite->addRule('^([a-z]{2})_([A-Z]{2})/' . str_replace('{PAGE_ID}', '([0-9]+)', $page_url) . '/?$', 'index.php?page=page&lang=$1_$2&id=$3' ) ;
                                            } else {
                                                $rewrite->addRule('^' . str_replace('{PAGE_SLUG}', '([\p{L}\p{N}_\-,]+)', $page_url) . '/?$', 'index.php?page=page&slug=$1') ;
                                                $rewrite->addRule('^([a-z]{2})_([A-Z]{2})/' . str_replace('{PAGE_SLUG}', '([\p{L}\p{N}_\-,]+)', $page_url) . '/?$', 'index.php?page=page&lang=$1_$2&slug=$3' ) ;
                                            }

                                            // Clean archive files
                                            $rewrite->addRule('^(.+?)\.php(.*)$', '$1.php$2');

                                            // Category rules
                                            $id_pos = stripos($item_url, '{CATEGORY_ID}');
                                            $title_pos = stripos($item_url, '{CATEGORY_SLUG}');
                                            $cat_pos = stripos($item_url, '{CATEGORIES');
                                            $param_pos = 1;
                                            if($title_pos!==false && $id_pos>$title_pos) {
                                                $param_pos++;
                                            }
                                            if($cat_pos!==false && $id_pos>$cat_pos) {
                                                $param_pos++;
                                            }
                                            $rewrite->addRule('^'.str_replace('{CATEGORIES}', '(.+)', str_replace('{CATEGORY_SLUG}', '([^/]+)', str_replace('{CATEGORY_ID}', '([0-9]+)', $cat_url))).'$', 'index.php?page=search&sCategory=$'.$param_pos);

                                            osc_run_hook("after_rewrite_rules", array(&$rewrite));

                                            //Write rule to DB
                                            $rewrite->setRules();

                                            $msg_error = '<br/>'._m('All fields are required.')." ".sprintf(_mn('One field was not updated', '%s fields were not updated', $errors), $errors);
                                            switch($status) {
                                                case 1:
                                                    $msg  = _m("Permalinks structure updated") ;
                                                    if($errors>0) {
                                                        $msg .= $msg_error;
                                                        osc_add_flash_warning_message($msg, 'admin') ;
                                                    } else {
                                                        osc_add_flash_ok_message($msg, 'admin') ;
                                                    }
                                                break;
                                                case 2:
                                                    $msg  = _m("Permalinks structure updated.") ;
                                                    $msg .= " " ;
                                                    $msg .= _m("However, we can't check if Apache module <b>mod_rewrite</b> is loaded. If you experience some problems with the URLs, you should deactivate <em>Friendly URLs</em>") ;
                                                    if($errors>0) {
                                                        $msg .= $msg_error;
                                                    }
                                                    osc_add_flash_warning_message($msg, 'admin') ;
                                                break;
                                                case 3:
                                                    $msg  = _m("File <b>.htaccess</b> couldn't be filled out with the right content.") ;
                                                    $msg .= " " ;
                                                    $msg .= _m("Here's the content you have to add to the <b>.htaccess</b> file. If you can't create the file, please deactivate the <em>Friendly URLs</em> option.") ;
                                                    $msg .= "</p><pre>" . htmlentities($htaccess, ENT_COMPAT, "UTF-8") . '</pre><p>' ;
                                                    if($errors>0) {
                                                        $msg .= $msg_error;
                                                    }
                                                    osc_add_flash_error_message($msg, 'admin') ;
                                                break;
                                                case 4:
                                                    $msg  = _m("File <b>.htaccess</b> couldn't be filled out with the right content.") ;
                                                    $msg .= " " ;
                                                    $msg .= _m("Here's the content you have to add to the <b>.htaccess</b> file. If you can't create the file or experience some problems with the URLs, please deactivate the <em>Friendly URLs</em> option.") ;
                                                    $msg .= "</p><pre>" . htmlentities($htaccess, ENT_COMPAT, "UTF-8") . '</pre><p>' ;
                                                    if($errors>0) {
                                                        $msg .= $msg_error;
                                                    }
                                                    osc_add_flash_error_message($msg, 'admin') ;
                                                break;
                                            }
                                        } else {
                                            Preference::newInstance()->update(array('s_value' => '0')
                                                                             ,array('s_name'  => 'rewriteEnabled')) ;
                                            Preference::newInstance()->update(array('s_value' => '0')
                                                                             ,array('s_name'  => 'mod_rewrite_loaded')) ;

                                            osc_add_flash_ok_message(_m('Friendly URLs successfully deactivated'), 'admin') ;
                                        }

                                        $this->redirectTo( osc_admin_base_url(true) . '?page=settings&action=permalinks' ) ;
                break;
                case('spamNbots'):      // calling the spam and bots view
                                        $akismet_key    = osc_akismet_key() ;
                                        $akismet_status = 3 ;
                                        if( $akismet_key != '' ) {
                                            require_once( osc_lib_path() . 'Akismet.class.php' ) ;
                                            $akismet_obj    = new Akismet(osc_base_url(), $akismet_key) ;
                                            $akismet_status = 2 ;
                                            if( $akismet_obj->isKeyValid() ) {
                                                $akismet_status = 1 ;
                                            }
                                        }

                                        View::newInstance()->_exportVariableToView('akismet_status', $akismet_status) ;
                                        $this->doView('settings/spamNbots.php');
                break;
                case('akismet_post'):   // updating spam and bots option
                                        $updated    = 0;
                                        $akismetKey = Params::getParam('akismetKey');
                                        $akismetKey = trim($akismetKey);

                                        $updated = Preference::newInstance()->update(array('s_value' => $akismetKey)
                                                                                    ,array('s_name'  => 'akismetKey')) ;

                                        if( $akismetKey == '' ) {
                                            osc_add_flash_info_message(_m('Your Akismet key has been cleared'), 'admin') ;
                                        } else {
                                            osc_add_flash_ok_message(_m('Your Akismet key has been updated'), 'admin') ;
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=spamNbots');
                break;
                case('recaptcha_post'): // updating spam and bots option
                                        $iUpdated = 0 ;
                                        $recaptchaPrivKey = Params::getParam('recaptchaPrivKey') ;
                                        $recaptchaPrivKey = trim($recaptchaPrivKey) ;
                                        $recaptchaPubKey  = Params::getParam('recaptchaPubKey') ;
                                        $recaptchaPubKey  = trim($recaptchaPubKey) ;

                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $recaptchaPrivKey)
                                                                                      ,array('s_name'  => 'recaptchaPrivKey')) ;
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $recaptchaPubKey)
                                                                                      ,array('s_name'  => 'recaptchaPubKey')) ;
                                        if( $recaptchaPubKey == '' ) {
                                            osc_add_flash_info_message(_m('Your reCAPTCHA key has been cleared'), 'admin') ;
                                        } else {
                                            osc_add_flash_ok_message( _m('Your reCAPTCHA key has been updated') ,'admin') ;
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=spamNbots') ;
                break;
                case('currencies'):     // currencies settings
                                        $currencies_action = Params::getParam('type') ;

                                        switch ($currencies_action) {
                                            case('add'):        // calling add currency view
                                                                $aCurrency = array(
                                                                    'pk_c_code'     => '',
                                                                    's_name'        => '',
                                                                    's_description' => '',
                                                                ) ;
                                                                $this->_exportVariableToView('aCurrency', $aCurrency) ;
                                                                $this->_exportVariableToView('typeForm', 'add_post') ;

                                                                $this->doView('settings/currency_form.php') ;
                                            break ;
                                            case('add_post'):   // adding a new currency
                                                                $currencyCode        = Params::getParam('pk_c_code') ;
                                                                $currencyName        = Params::getParam('s_name') ;
                                                                $currencyDescription = Params::getParam('s_description') ;

                                                                // cleaning parameters
                                                                $currencyName        = strip_tags($currencyName) ;
                                                                $currencyDescription = strip_tags($currencyDescription) ;
                                                                $currencyCode        = strip_tags($currencyCode) ;
                                                                $currencyCode        = trim($currencyCode) ;

                                                                if( !preg_match('/^.{1,3}$/', $currencyCode) ) {
                                                                    osc_add_flash_error_message( _m('The currency code is not in the correct format'), 'admin') ;
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies') ;
                                                                }

                                                                $fields = array(
                                                                    'pk_c_code'     => $currencyCode,
                                                                    's_name'        => $currencyName,
                                                                    's_description' => $currencyDescription,
                                                                ) ;

                                                                $isInserted = Currency::newInstance()->insert($fields) ;

                                                                if( $isInserted ) {
                                                                    osc_add_flash_ok_message( _m('Currency added'), 'admin') ;
                                                                } else {
                                                                    osc_add_flash_error_message( _m("Currency couldn't be added"), 'admin') ;
                                                                }
                                                                $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies') ;
                                            break ;
                                            case('edit'):       // calling edit currency view
                                                                $currencyCode = Params::getParam('code') ;
                                                                $currencyCode = strip_tags($currencyCode) ;
                                                                $currencyCode = trim($currencyCode) ;

                                                                if( $currencyCode == '' ) {
                                                                    osc_add_flash_warning_message( sprintf( _m("The currency code '%s' doesn't exist"), $currencyCode ), 'admin') ;
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies') ;
                                                                }

                                                                $aCurrency = Currency::newInstance()->findByPrimaryKey($currencyCode) ;

                                                                if( !       $aCurrency ) {
                                                                    osc_add_flash_warning_message( sprintf( _m("The currency code '%s' doesn't exist"), $currencyCode ), 'admin') ;
                                                                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies') ;
                                                                }

                                                                $this->_exportVariableToView('aCurrency', $aCurrency) ;
                                                                $this->_exportVariableToView('typeForm', 'edit_post') ;

                                                                $this->doView('settings/currency_form.php');
                                            break ;
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

                                                                $updated = Currency::newInstance()->update(
                                                                        array(
                                                                            's_name'        => $currencyName,
                                                                            's_description' => $currencyDescription
                                                                        ),
                                                                        array('pk_c_code'   => $currencyCode)
                                                                ) ;

                                                                if($updated == 1) {
                                                                    osc_add_flash_ok_message( _m('Currency updated'), 'admin') ;
                                                                } else {
                                                                    osc_add_flash_info_message( _m('No changes were made'), 'admin') ;
                                                                }
                                                                $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies') ;
                                            break ;
                                            case('delete'):     // deleting a currency
                                                                $rowChanged    = 0 ;
                                                                $aCurrencyCode = Params::getParam('code') ;

                                                                if( !is_array($aCurrencyCode) ) {
                                                                    $aCurrencyCode = array($aCurrencyCode) ;
                                                                }

                                                                $msg_current = '';
                                                                foreach($aCurrencyCode as $currencyCode) {
                                                                    if( preg_match('/.{1,3}/', $currencyCode) && $currencyCode != osc_currency() ) {
                                                                        $rowChanged += Currency::newInstance()->delete( array('pk_c_code' => $currencyCode) ) ;
                                                                    }

                                                                    // foreign key error
                                                                    if( Currency::newInstance()->getErrorLevel() == '1451' ) {
                                                                        $msg_current .= sprintf('</p><p>' . _m("%s couldn't be deleted because it has listings associated to it"), $currencyCode) ;
                                                                    } else if( $currencyCode == osc_currency() ) {
                                                                        $msg_current .= sprintf('</p><p>' . _m("%s couldn't be deleted because it's the default currency"), $currencyCode) ;
                                                                    }
                                                                }

                                                                $msg    = '' ;
                                                                $status = '' ;
                                                                switch($rowChanged) {
                                                                    case('0'):
                                                                                $msg    = _m('No currencies have been deleted') ;
                                                                                $status = 'error' ;
                                                                    break ;
                                                                    case('1'):
                                                                                $msg    = _m('One currency has been deleted') ;
                                                                                $status = 'ok' ;
                                                                    break ;
                                                                    default:
                                                                                $msg    = sprintf( _m('%s currencies have been deleted'), $rowChanged) ;
                                                                                $status = 'ok' ;
                                                                    break ;
                                                                }

                                                                if( $status == 'ok' && $msg_current != '' ) {
                                                                    $status = 'warning' ;
                                                                }

                                                                switch($status) {
                                                                    case('error'):      osc_add_flash_error_message($msg . $msg_current, 'admin') ;
                                                                    break;
                                                                    case('warning'):    osc_add_flash_warning_message($msg . $msg_current, 'admin') ;
                                                                    break;
                                                                    case('ok'):         osc_add_flash_ok_message($msg, 'admin') ;
                                                                    break;
                                                                }

                                                                $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies') ;
                                            break;
                                            default:            // calling the currencies view
                                                                $aCurrencies = Currency::newInstance()->listAll() ;
                                                                $this->_exportVariableToView('aCurrencies', $aCurrencies) ;

                                                                $this->doView('settings/currencies.php') ;
                                            break;
                                        }
                break ;
                case('mailserver'):     // calling the mailserver view
                                        $this->doView('settings/mailserver.php') ;
                break;
                case('mailserver_post'):if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin') ;
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=mailserver') ;
                                        }
                                        // updating mailserver
                                        $iUpdated           = 0 ;
                                        $mailserverAuth     = Params::getParam('mailserver_auth') ;
                                        $mailserverAuth     = ($mailserverAuth != '' ? true : false) ;
                                        $mailserverPop      = Params::getParam('mailserver_pop') ;
                                        $mailserverPop      = ($mailserverPop != '' ? true : false) ;
                                        $mailserverType     = Params::getParam('mailserver_type') ;
                                        $mailserverHost     = Params::getParam('mailserver_host') ;
                                        $mailserverPort     = Params::getParam('mailserver_port') ;
                                        $mailserverUsername = Params::getParam('mailserver_username') ;
                                        $mailserverPassword = Params::getParam('mailserver_password') ;
                                        $mailserverSsl      = Params::getParam('mailserver_ssl') ;

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
                                        $max_upload   = (int)( ini_get('upload_max_filesize') ) ;
                                        $max_post     = (int)( ini_get('post_max_size') ) ;
                                        $memory_limit = (int)( ini_get('memory_limit') ) ;
                                        $upload_mb    = min($max_upload, $max_post, $memory_limit) * 1024 ;

                                        $this->_exportVariableToView('max_size_upload', $upload_mb) ;
                                        $this->doView('settings/media.php') ;
                break;
                case('media_post'):     // updating the media config
                                        $status = 'ok' ;
                                        $error  = '' ;

                                        $iUpdated          = 0 ;
                                        $maxSizeKb         = Params::getParam('maxSizeKb') ;
                                        $allowedExt        = Params::getParam('allowedExt') ;
                                        $dimThumbnail      = Params::getParam('dimThumbnail') ;
                                        $dimPreview        = Params::getParam('dimPreview') ;
                                        $dimNormal         = Params::getParam('dimNormal') ;
                                        $keepOriginalImage = Params::getParam('keep_original_image') ;
                                        $use_imagick       = Params::getParam('use_imagick') ;
                                        $type_watermark    = Params::getParam('watermark_type') ;
                                        $watermark_color   = Params::getParam('watermark_text_color') ;
                                        $watermark_text    = Params::getParam('watermark_text') ;

                                        switch ($type_watermark) {
                                            case 'none':
                                                $iUpdated += Preference::newInstance()->update(
                                                        array('s_value' => ''),
                                                        array('s_name'  => 'watermark_text_color')
                                                ) ;
                                                $iUpdated += Preference::newInstance()->update(
                                                        array('s_value' => ''),
                                                        array('s_name'  => 'watermark_text')
                                                ) ;
                                                $iUpdated += Preference::newInstance()->update(
                                                        array('s_value' => ''),
                                                        array('s_name'  => 'watermark_image')
                                                ) ;
                                            break ;
                                            case 'text':
                                                $iUpdated += Preference::newInstance()->update(
                                                        array('s_value' => $watermark_color),
                                                        array('s_name'  => 'watermark_text_color')
                                                ) ;
                                                $iUpdated += Preference::newInstance()->update(
                                                        array('s_value' => $watermark_text),
                                                        array('s_name'  => 'watermark_text')
                                                ) ;
                                                $iUpdated += Preference::newInstance()->update(
                                                        array('s_value' => ''),
                                                        array('s_name'  => 'watermark_image')
                                                ) ;
                                                $iUpdated += Preference::newInstance()->update(
                                                        array('s_value' => Params::getParam('watermark_text_place')),
                                                        array('s_name'  => 'watermark_place')
                                                ) ;
                                            break ;
                                            case 'image':
                                                // upload image & move to path
                                                if( $_FILES['watermark_image']['error'] == UPLOAD_ERR_OK ) {
                                                    if($_FILES['watermark_image']['type']=='image/png') {
                                                        $tmpName = $_FILES['watermark_image']['tmp_name'] ;
                                                        $path    = osc_content_path() . 'uploads/watermark.png' ;
                                                        if( move_uploaded_file($tmpName, $path) ){
                                                            $iUpdated += Preference::newInstance()->update(
                                                                    array('s_value' => $path),
                                                                    array('s_name'  => 'watermark_image')
                                                            ) ;
                                                        } else {
                                                            $error .= _m('There was a problem uploading the watermark image')."<br />";
                                                        }
                                                    } else {
                                                        $error .= _m('The watermark image has to be a .PNG file')."<br />";
                                                    }
                                                } else {
                                                    $error .= _m('There was a problem uploading the watermark image')."<br />";
                                                }
                                                $iUpdated += Preference::newInstance()->update(
                                                        array('s_value' => ''),
                                                        array('s_name'  => 'watermark_text_color')
                                                ) ;
                                                $iUpdated += Preference::newInstance()->update(
                                                        array('s_value' => ''),
                                                        array('s_name'  => 'watermark_text')
                                                ) ;
                                                $iUpdated += Preference::newInstance()->update(
                                                        array('s_value' => Params::getParam('watermark_image_place')),
                                                        array('s_name'  => 'watermark_place')
                                                ) ;
                                            break;
                                            default:
                                            break;
                                        }

                                        // format parameters
                                        $maxSizeKb         = strip_tags($maxSizeKb) ;
                                        $allowedExt        = strip_tags($allowedExt) ;
                                        $dimThumbnail      = strip_tags($dimThumbnail) ;
                                        $dimPreview        = strip_tags($dimPreview);
                                        $dimNormal         = strip_tags($dimNormal) ;
                                        $keepOriginalImage = ($keepOriginalImage != '' ? true : false) ;
                                        $use_imagick       = ($use_imagick != '' ? true : false) ;

                                        // is imagick extension loaded?
                                        if( !@extension_loaded('imagick') ) {
                                            $use_imagick = false ;
                                        }

                                        // max size allowed by PHP configuration?
                                        $max_upload   = (int)( ini_get('upload_max_filesize') ) ;
                                        $max_post     = (int)( ini_get('post_max_size') ) ;
                                        $memory_limit = (int)( ini_get('memory_limit') ) ;
                                        $upload_mb    = min($max_upload, $max_post, $memory_limit) * 1024 ;

                                        // set maxSizeKB equals to PHP configuration if it's bigger
                                        if( $maxSizeKb > $upload_mb ) {
                                            $status    = 'warning' ;
                                            $maxSizeKb = $upload_mb ;
                                            // flash message text warning
                                            $error     .= sprintf( _m("You cannot set a maximum file size higher than the one allowed in the PHP configuration: <b>%d KB</b>"), $upload_mb ) ;
                                        }

                                        $iUpdated += Preference::newInstance()->update(
                                                array('s_value' => $maxSizeKb),
                                                array('s_name'  => 'maxSizeKb')
                                        ) ;
                                        $iUpdated += Preference::newInstance()->update(
                                                array('s_value' => $allowedExt),
                                                array('s_name'  => 'allowedExt')
                                        ) ;
                                        $iUpdated += Preference::newInstance()->update(
                                                array('s_value' => $dimThumbnail),
                                                array('s_name'  => 'dimThumbnail')
                                        ) ;
                                        $iUpdated += Preference::newInstance()->update(
                                                array('s_value' => $dimPreview),
                                                array('s_name'  => 'dimPreview')
                                        ) ;
                                        $iUpdated += Preference::newInstance()->update(
                                                array('s_value' => $dimNormal),
                                                array('s_name'  => 'dimNormal')
                                        );
                                        $iUpdated += Preference::newInstance()->update(
                                                array('s_value' => $keepOriginalImage),
                                                array('s_name'  => 'keep_original_image')
                                        ) ;
                                        $iUpdated += Preference::newInstance()->update(
                                                array('s_value' => $use_imagick),
                                                array('s_name'  => 'use_imagick')
                                        ) ;

                                        if( $error != '' ) {
                                            switch($status) {
                                                case('error'):
                                                    osc_add_flash_error_message($error, 'admin');
                                                break;
                                                case('warning'):
                                                    osc_add_flash_warning_message($error, 'admin');
                                                break;
                                                default:
                                                    osc_add_flash_ok_message($error, 'admin');
                                                break;
                                            }
                                        } else {
                                            osc_add_flash_ok_message(_m('Media config has been updated'), 'admin') ;
                                        }

                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=media') ;
                break ;
                case('images_post'):    if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin') ;
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=media') ;
                                        }

                                        $wat = new Watermark();
                                        $aResources = ItemResource::newInstance()->getAllResources();
                                        foreach($aResources as $resource) {
                                            osc_run_hook('regenerate_image', $resource);

                                            $path = osc_content_path() . 'uploads/' ;
                                            // comprobar que no haya original
                                            $img_original = $path . $resource['pk_i_id']. "_original*";
                                            $aImages = glob($img_original);
                                            // there is original image
                                            if( count($aImages) == 1 ) {
                                                $image_tmp = $aImages[0] ;
                                            } else {
                                                $img_normal = $path . $resource['pk_i_id']. ".*" ;
                                                $aImages = glob( $img_normal );
                                                if( count($aImages) == 1 ) {
                                                    $image_tmp = $aImages[0] ;
                                                } else {
                                                    $img_thumbnail = $path . $resource['pk_i_id']. "_thumbnail*" ;
                                                    $aImages = glob( $img_thumbnail );
                                                    $image_tmp = $aImages[0] ;
                                                }
                                            }

                                            // extension
                                            preg_match('/\.(.*)$/', $image_tmp, $matches) ;
                                            if( isset($matches[1]) ) {
                                                $extension = $matches[1] ;

                                                // Create normal size
                                                $path_normal = $path = osc_content_path() . 'uploads/' . $resource['pk_i_id'] . '.jpg' ;
                                                $size = explode('x', osc_normal_dimensions()) ;
                                                ImageResizer::fromFile($image_tmp)->resizeTo($size[0], $size[1])->saveToFile($path) ;

                                                if( osc_is_watermark_text() ) {
                                                    $wat->doWatermarkText( $path , osc_watermark_text_color(), osc_watermark_text() , 'image/jpeg' );
                                                } elseif ( osc_is_watermark_image() ){
                                                    $wat->doWatermarkImage( $path, 'image/jpeg');
                                                }

                                                // Create preview
                                                $path = osc_content_path(). 'uploads/' . $resource['pk_i_id'] . '_preview.jpg' ;
                                                $size = explode('x', osc_preview_dimensions()) ;
                                                ImageResizer::fromFile($path_normal)->resizeTo($size[0], $size[1])->saveToFile($path) ;

                                                // Create thumbnail
                                                $path = osc_content_path(). 'uploads/' . $resource['pk_i_id'] . '_thumbnail.jpg' ;
                                                $size = explode('x', osc_thumbnail_dimensions()) ;
                                                ImageResizer::fromFile($path_normal)->resizeTo($size[0], $size[1])->saveToFile($path) ;

                                                // update resource info
                                                ItemResource::newInstance()->update(
                                                                        array(
                                                                            's_path'            => 'oc-content/uploads/'
                                                                            ,'s_name'           => osc_genRandomPassword()
                                                                            ,'s_extension'      => 'jpg'
                                                                            ,'s_content_type'   => 'image/jpeg'
                                                                        )
                                                                        ,array(
                                                                            'pk_i_id'       => $resource['pk_i_id']
                                                                        )
                                                ) ;
                                                osc_run_hook('regenerated_image', ItemResource::newInstance()->findByPrimaryKey($resource['pk_i_id']));
                                                // si extension es direfente a jpg, eliminar las imagenes con $extension si hay
                                                if( $extension != 'jpg' ) {
                                                    @unlink(osc_content_path(). 'uploads/' . $resource['pk_i_id'] . "." . $extension);
                                                    @unlink(osc_content_path(). 'uploads/' . $resource['pk_i_id'] . "_original." . $extension);
                                                    @unlink(osc_content_path(). 'uploads/' . $resource['pk_i_id'] . "_preview." . $extension);
                                                    @unlink(osc_content_path(). 'uploads/' . $resource['pk_i_id'] . "_thumbnail." . $extension);
                                                }
                                                // ....
                                            } else {
                                                // no es imagen o imagen sin extesión
                                            }

                                        }

                                        osc_add_flash_ok_message( _m('Re-generation complete'), 'admin') ;
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=media') ;
                break;
                case('update'):         // update index view
                                        $iUpdated          = 0 ;
                                        $sPageTitle        = Params::getParam('pageTitle');
                                        $sPageDesc         = Params::getParam('pageDesc');
                                        $sContactEmail     = Params::getParam('contactEmail');
                                        $sLanguage         = Params::getParam('language');
                                        $sDateFormat       = Params::getParam('dateFormat');
                                        $sCurrency         = Params::getParam('currency');
                                        $sWeekStart        = Params::getParam('weekStart');
                                        $sTimeFormat       = Params::getParam('timeFormat');
                                        $sTimezone         = Params::getParam('timezone');
                                        $sNumRssItems      = Params::getParam('num_rss_items');
                                        $maxLatestItems    = Params::getParam('max_latest_items_at_home');
                                        $numItemsSearch    = Params::getParam('default_results_per_page');
                                        $contactAttachment = Params::getParam('enabled_attachment');
                                        $selectableParent  = Params::getParam('selectable_parent_categories');
                                        $bAutoCron         = Params::getParam('auto_cron');
                                        $bMarketSources    = Params::getParam('market_external_sources') == 1 ? 1 : 0;

                                        // preparing parameters
                                        $sPageTitle        = strip_tags($sPageTitle) ;
                                        $sPageDesc         = strip_tags($sPageDesc) ;
                                        $sContactEmail     = strip_tags($sContactEmail) ;
                                        $sLanguage         = strip_tags($sLanguage) ;
                                        $sDateFormat       = strip_tags($sDateFormat) ;
                                        $sCurrency         = strip_tags($sCurrency) ;
                                        $sWeekStart        = strip_tags($sWeekStart) ;
                                        $sTimeFormat       = strip_tags($sTimeFormat) ;
                                        $sNumRssItems      = (int) strip_tags($sNumRssItems) ;
                                        $maxLatestItems    = (int) strip_tags($maxLatestItems) ;
                                        $numItemsSearch    = (int) $numItemsSearch ;
                                        $contactAttachment = ($contactAttachment != '' ? true : false) ;
                                        $bAutoCron         = ($bAutoCron != '' ? true : false) ;
                                        $error = "";

                                        $msg = '';
                                        if(!osc_validate_text($sPageTitle)) {
                                            $msg .= _m("Page title field is required")."<br/>";
                                        }
                                        if(!osc_validate_text($sContactEmail)) {
                                            $msg .= _m("Contact email field is required")."<br/>";
                                        }
                                        if(!osc_validate_int($sNumRssItems)) {
                                            $msg .= _m("Number of listings in the RSS has to be a numeric value")."<br/>";
                                        }
                                        if(!osc_validate_int($maxLatestItems)) {
                                            $msg .= _m("Max latest listings has to be a numeric value")."<br/>";
                                        }
                                        if(!osc_validate_int($numItemsSearch)) {
                                            $msg .= _m("Number of listings on search has to be a numeric value")."<br/>";
                                        }
                                        if($msg!='') {
                                            osc_add_flash_error_message( $msg, 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings');
                                        }

                                        $iUpdated += Preference::newInstance()->update(
                                            array('s_value'   => $sPageTitle),
                                            array('s_section' => 'osclass', 's_name' => 'pageTitle')
                                        );
                                        $iUpdated += Preference::newInstance()->update(
                                            array('s_value'   => $sPageDesc),
                                            array('s_section' => 'osclass', 's_name' => 'pageDesc')
                                        );
                                        
                                        if( !defined('DEMO') ) {
                                            $iUpdated += Preference::newInstance()->update(
                                                array('s_value'   => $sContactEmail),
                                                array('s_section' => 'osclass', 's_name' => 'contactEmail')
                                            );
                                        }
                                        $iUpdated += Preference::newInstance()->update(
                                            array('s_value'   => $sLanguage),
                                            array('s_section' => 'osclass', 's_name' => 'language')
                                        );
                                        $iUpdated += Preference::newInstance()->update(
                                            array('s_value'   => $sDateFormat),
                                            array('s_section' => 'osclass', 's_name' => 'dateFormat')
                                        );
                                        $iUpdated += Preference::newInstance()->update(
                                            array('s_value'   => $sCurrency),
                                            array('s_section' => 'osclass', 's_name' => 'currency')
                                        );
                                        $iUpdated += Preference::newInstance()->update(
                                            array('s_value'   => $sWeekStart),
                                            array('s_section' => 'osclass', 's_name' => 'weekStart')
                                        );
                                        $iUpdated += Preference::newInstance()->update(
                                            array('s_value'   => $sTimeFormat),
                                            array('s_section' => 'osclass', 's_name' => 'timeFormat')
                                        );
                                        $iUpdated += Preference::newInstance()->update(
                                            array('s_value'   => $sTimezone),
                                            array('s_section' => 'osclass', 's_name' => 'timezone')
                                        );
                                        $iUpdated += Preference::newInstance()->update(
                                            array('s_value'   => $bMarketSources),
                                            array('s_section' => 'osclass', 's_name' => 'marketAllowExternalSources')
                                        );
                                        if(is_int($sNumRssItems)) {
                                            $iUpdated += Preference::newInstance()->update(
                                                array('s_value'   => $sNumRssItems),
                                                array('s_section' => 'osclass', 's_name' => 'num_rss_items')
                                            );
                                        } else {
                                            if($error != '') $error .= "</p><p>";
                                            $error .= _m('Number of listings in the RSS must be an integer');
                                        }

                                        if(is_int($maxLatestItems)) {
                                            $iUpdated += Preference::newInstance()->update(
                                                array('s_value'   => $maxLatestItems),
                                                array('s_section' => 'osclass', 's_name' => 'maxLatestItems@home')
                                            );
                                        } else {
                                            if($error != '') $error .= "</p><p>";
                                            $error .= _m('Number of recent listings displayed at home must be an integer');
                                        }

                                        $iUpdated += Preference::newInstance()->update(
                                                array('s_value'   => $numItemsSearch),
                                                array('s_section' => 'osclass',
                                                      's_name'    => 'defaultResultsPerPage@search')
                                        );
                                        $iUpdated += Preference::newInstance()->update(
                                            array('s_value' => $contactAttachment),
                                            array('s_name'  => 'contact_attachment')
                                        );
                                        $iUpdated += Preference::newInstance()->update(
                                            array('s_value' => $bAutoCron),
                                            array('s_name' => 'auto_cron')
                                        );
                                        $iUpdated += Preference::newInstance()->update(
                                            array('s_value' => $selectableParent),
                                            array('s_name'  => 'selectable_parent_categories')
                                        );

                                        if( $iUpdated > 0 ) {
                                            if( $error != '' ) {
                                                osc_add_flash_error_message( $error . "</p><p>" . _m('General settings have been updated'), 'admin');
                                            } else {
                                                osc_add_flash_ok_message( _m('General settings have been updated'), 'admin');
                                            }
                                        } else if($error != '') {
                                            osc_add_flash_error_message( $error , 'admin');
                                        }

                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings');
                break;
                case('check_updates'): 
                                        osc_admin_toolbar_update_themes(true);
                                        osc_admin_toolbar_update_plugins(true);
                                        
                                        osc_add_flash_ok_message( _m('Last check') . ':   ' . date("Y-m-d H:i") , 'admin');
                                        
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings');
                break;
                case('latestsearches'):       //calling the comments settings view
                                        $this->doView('settings/searches.php');
                break;
                case('latestsearches_post'):  // updating comment
                                        if( Params::getParam('save_latest_searches') == 'on' ) {
                                            Preference::newInstance()->update(
                                                    array('s_value' => 1),
                                                    array('s_name'  => 'save_latest_searches')
                                            ) ;
                                        } else {
                                            Preference::newInstance()->update(
                                                    array('s_value' => 0),
                                                    array('s_name'  => 'save_latest_searches')
                                            ) ;
                                        }

                                        if(Params::getParam('customPurge')=='') {
                                            osc_add_flash_error_message(_m('Custom number could not be left empty'), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=latestsearches') ;
                                        } else {

                                            Preference::newInstance()->update(
                                                    array('s_value' => Params::getParam('customPurge')),
                                                    array('s_name'  => 'purge_latest_searches')
                                            ) ;

                                            osc_add_flash_ok_message( _m('Last search settings have been updated'), 'admin') ;
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=latestsearches') ;
                                        }
                break;
                default:                // calling the view
                                        $aLanguages = OSCLocale::newInstance()->listAllEnabled() ;
                                        $aCurrencies = Currency::newInstance()->listAll() ;

                                        $this->_exportVariableToView('aLanguages', $aLanguages) ;
                                        $this->_exportVariableToView('aCurrencies', $aCurrencies) ;

                                        $this->doView('settings/index.php') ;
                break;
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_current_admin_theme_path($file) ;
            Session::newInstance()->_clearVariables();
        }

        function install_location_by_country()
        {
            $country_code    = Params::getParam('c_country');
            $aCountryCode[] = trim($country_code);

            $manager_country = new Country();
            $countries_json = osc_file_get_contents('http://geo.osclass.org/geo.download.php?action=country_id&term=' .
                                                     urlencode(implode(',', $aCountryCode)) );

            $countries = json_decode($countries_json);
            if(isset($countries->error)) {
                osc_add_flash_error_message(sprintf(_m("%s can't be added"), $country), 'admin');
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

        function install_location_by_region()
        {
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
                osc_add_flash_error_message(sprintf(_m("%s can't be added"), $region), 'admin');
                return false;
            }

            foreach($regions as $r) {
                $exists = $manager_region->findByName($r->name, $r->country_code);
                if(isset($exists['s_name'])) {
                    osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $exists['s_name']), 'admin');
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

    /* file end: ./oc-admin/settings.php */
?>