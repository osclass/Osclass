<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    class CAdminSettingsLocations extends AdminSecBaseModel
    {
        //Business Layer...
        function doModel()
        {
            // calling the locations settings view
            $location_action = Params::getParam('type');
            $mCountries = new Country();

            switch ($location_action) {
                case('add_country'):    // add country
                                        if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        }
                                        osc_csrf_check();
                                        $countryCode = strtoupper(Params::getParam('c_country'));
                                        $countryName = Params::getParam('country');
                                        $exists = $mCountries->findByCode($countryCode);
                                        if(isset($exists['s_name'])) {
                                            osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $countryName), 'admin');
                                        } else {
                                            if(Params::getParam('c_manual')==1) {
                                                $mCountries->insert(array('pk_c_code' => $countryCode,
                                                                        's_name' => $countryName));
                                                osc_add_flash_ok_message(sprintf(_m('%s has been added as a new country'), $countryName), 'admin');
                                            } else {
                                                if(!osc_validate_min($countryCode, 1) || !osc_validate_min($countryName, 1)) {
                                                    osc_add_flash_error_message(_m('Country code and name should have at least two characters'), 'admin');
                                                } else {
                                                    $data_sql = osc_file_get_contents('http://geo.osclass.org/newgeo.download.php?action=country&term=' . urlencode($countryCode) );

                                                    if($data_sql!='') {
                                                        $conn = DBConnectionClass::newInstance();
                                                        $c_db = $conn->getOsclassDb();
                                                        $comm = new DBCommandClass($c_db);
                                                        $comm->query("SET FOREIGN_KEY_CHECKS = 0");
                                                        $comm->importSQL($data_sql);
                                                        $comm->query("SET FOREIGN_KEY_CHECKS = 1");
                                                    } else {
                                                        $mCountries->insert(array('pk_c_code' => $countryCode,
                                                                                's_name' => $countryName));
                                                    }
                                                    osc_add_flash_ok_message(sprintf(_m('%s has been added as a new country'), $countryName), 'admin');
                                                }
                                            }
                                        }
                                        osc_calculate_location_slug('country');
                                        osc_calculate_location_slug('region');
                                        osc_calculate_location_slug('city');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                break;
                case('edit_country'):   // edit country
                                        if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        }
                                        osc_csrf_check();
                                        if(!osc_validate_min(Params::getParam('e_country'), 1)) {
                                            osc_add_flash_error_message(_m('Country name cannot be blank'), 'admin');
                                        } else {
                                            $name = Params::getParam('e_country');
                                            $slug = Params::getParam('e_country_slug');
                                            if($slug=='') {
                                                $slug_tmp = $slug = osc_sanitizeString($name);
                                            } else {
                                                $exists = $mCountries->findBySlug($slug);
                                                if(isset($exists['s_slug']) && $exists['pk_c_code']!=Params::getParam('country_code')) {
                                                    $slug_tmp = $slug = osc_sanitizeString($name);
                                                } else {
                                                    $slug_tmp = $slug = osc_sanitizeString($slug);
                                                }
                                            }
                                            $slug_unique = 1;
                                            while(true) {
                                                $location_slug = $mCountries->findBySlug($slug);
                                                if(isset($location_slug['s_slug']) && $location_slug['pk_c_code']!=Params::getParam('country_code')) {
                                                    $slug = $slug_tmp . '-' . $slug_unique;
                                                    $slug_unique++;
                                                } else {
                                                    break;
                                                }
                                            }

                                            $ok = $mCountries->update(array('s_name'=> $name, 's_slug' => $slug), array('pk_c_code' => Params::getParam('country_code')));

                                            if( $ok ) {
                                                osc_add_flash_ok_message(_m('Country has been edited'), 'admin');
                                            } else {
                                                osc_add_flash_error_message(_m('There were some problems editing the country'), 'admin');
                                            }
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                break;
                case('delete_country'): // delete country
                                        if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        }
                                        osc_csrf_check();
                                        $countryIds = Params::getParam('id');

                                        if(is_array($countryIds)) {
                                            $locations = 0;
                                            $del_locations = 0;
                                            foreach($countryIds as $countryId) {
                                                $ok = $mCountries->deleteByPrimaryKey($countryId);
                                            }
                                            if($ok==0) {
                                                $del_locations++;
                                            } else {
                                                $locations += $ok;
                                            }
                                            if($locations==0) {
                                                osc_add_flash_ok_message(sprintf(_n('One location has been deleted', '%s locations have been deleted', $del_locations), $del_locations), 'admin');
                                            } else {
                                                osc_add_flash_error_message(_m('There was a problem deleting locations'), 'admin');
                                            }
                                        } else {
                                            osc_add_flash_error_message(_m('No country was selected'), 'admin');
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                break;
                case('add_region'):     // add region
                                        if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        }
                                        osc_csrf_check();
                                        if( !Params::getParam('r_manual') ) {
                                            $regionId    = Params::getParam('region_id');
                                            $regionName  = Params::getParam('region');
                                            if($regionId!='') {
                                                $data_sql = osc_file_get_contents('http://geo.osclass.org/newgeo.download.php?action=region&term=' . urlencode($regionId) );

                                                $conn = DBConnectionClass::newInstance();
                                                $c_db = $conn->getOsclassDb();
                                                $comm = new DBCommandClass($c_db);
                                                $comm->query("SET FOREIGN_KEY_CHECKS = 0");
                                                $comm->importSQL($data_sql);
                                                $comm->query("SET FOREIGN_KEY_CHECKS = 1");
                                                osc_add_flash_ok_message(sprintf(_m('%s has been added as a new region'), $regionName), 'admin');
                                            } else {
                                                osc_add_flash_error_message(sprintf(_m("%s can't be added"), $regionName), 'admin');
                                            }

                                        } else {
                                            $mRegions    = new Region();
                                            $regionName  = Params::getParam('region');
                                            $countryCode = Params::getParam('country_c_parent');
                                            $country     = Country::newInstance()->findByCode($countryCode);

                                            if(!osc_validate_min($regionName, 1)) {
                                                osc_add_flash_error_message(_m('Region name cannot be blank'), 'admin');
                                            } else {
                                                $exists = $mRegions->findByName($regionName, $countryCode);
                                                if(!isset($exists['s_name'])) {
                                                    $data = array('fk_c_country_code' => $countryCode
                                                                ,'s_name' => $regionName);
                                                    $mRegions->insert($data);
                                                    $id = $mRegions->dao->insertedId();
                                                    RegionStats::newInstance()->setNumItems($id, 0);

                                                    osc_add_flash_ok_message(sprintf(_m('%s has been added as a new region'), $regionName), 'admin');
                                                } else {
                                                    osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $regionName), 'admin');
                                                }
                                            }
                                        }
                                        osc_calculate_location_slug('region');
                                        osc_calculate_location_slug('city');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations&country_code='.@$countryCode."&country=".@$country['s_name']);
                break;
                case('edit_region'):    // edit region
                                        if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        }
                                        osc_csrf_check();
                                        $mRegions  = new Region();
                                        $newRegion = Params::getParam('e_region');
                                        $regionId  = Params::getParam('region_id');

                                        if(!osc_validate_min($newRegion, 1)) {
                                            osc_add_flash_error_message(_m('Region name cannot be blank'), 'admin');
                                        } else {
                                            $aRegion = $mRegions->findByPrimaryKey($regionId);
                                            $exists = $mRegions->findByName($newRegion, $aRegion['fk_c_country_code']);
                                            if(!isset($exists['pk_i_id']) || $exists['pk_i_id']==$regionId) {
                                                if($regionId != '') {
                                                    $country = Country::newInstance()->findByCode($aRegion['fk_c_country_code']);

                                                    $name = $newRegion;
                                                    $slug = Params::getParam('e_region_slug');
                                                    if($slug=='') {
                                                        $slug_tmp = $slug = osc_sanitizeString($name);
                                                    } else {
                                                        $exists = $mRegions->findBySlug($slug);
                                                        if(isset($exists['s_slug']) && $exists['pk_i_id']!=$regionId) {
                                                            $slug_tmp = $slug = osc_sanitizeString($name);
                                                        } else {
                                                            $slug_tmp = $slug = osc_sanitizeString($slug);
                                                        }
                                                    }
                                                    $slug_unique = 1;
                                                    while(true) {
                                                        $location_slug = $mRegions->findBySlug($slug);
                                                        if(isset($location_slug['s_slug']) && $location_slug['pk_i_id']!=$regionId) {
                                                            $slug = $slug_tmp . '-' . $slug_unique;
                                                            $slug_unique++;
                                                        } else {
                                                            break;
                                                        }
                                                    }

                                                    $mRegions->update(array('s_name' => $newRegion, 's_slug' => $slug)
                                                                     ,array('pk_i_id' => $regionId));
                                                    ItemLocation::newInstance()->update(
                                                        array('s_region'       => $newRegion),
                                                        array('fk_i_region_id' => $regionId)
                                                    );
                                                    osc_add_flash_ok_message(sprintf(_m('%s has been edited'), $newRegion), 'admin');
                                                }
                                            } else {
                                                osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $newRegion), 'admin');
                                            }
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations&country_code='.@$country['pk_c_code']."&country=".@$country['s_name']);
                break;
                case('delete_region'):  // delete region
                                        if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        }
                                        osc_csrf_check();
                                        $mRegion  = new Region();
                                        $regionIds = Params::getParam('id');

                                        if(is_array($regionIds)) {
                                            $locations = 0;
                                            $del_locations = 0;
                                            if(count($regionIds)>0) {
                                                $region = $mRegion->findByPrimaryKey($regionIds[0]);
                                                $country = Country::newInstance()->findByCode($region['fk_c_country_code']);
                                                foreach($regionIds as $regionId) {
                                                    if($regionId != '') {
                                                        $ok = $mRegion->deleteByPrimaryKey($regionId);
                                                        if($ok==0) {
                                                            $del_locations++;
                                                        } else {
                                                            $locations += $ok;
                                                        }
                                                    }
                                                }
                                            }
                                            if($locations==0) {
                                                osc_add_flash_ok_message(sprintf(_n('One location has been deleted', '%s locations have been deleted', $del_locations), $del_locations), 'admin');
                                            } else {
                                                osc_add_flash_error_message(_m('There was a problem deleting locations'), 'admin');
                                            }
                                        } else {
                                            osc_add_flash_error_message(_m('No region was selected'), 'admin');
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations&country_code='.@$country['pk_c_code']."&country=".@$country['s_name']);
                break;
                case('add_city'):       // add city
                                        if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        }
                                        osc_csrf_check();
                                        $regionId    = Params::getParam('region_parent');
                                        $countryCode = Params::getParam('country_c_parent');
                                        $mRegion     = new Region();
                                        $region = $mRegion->findByPrimaryKey($regionId);
                                        $country = Country::newInstance()->findByCode($region['fk_c_country_code']);
                                        if( !Params::getParam('ci_manual') ) {
                                            $cityId    = Params::getParam('city_id');
                                            $cityName  = Params::getParam('city');
                                            if($cityId!='') {
                                                $data_sql = osc_file_get_contents('http://geo.osclass.org/newgeo.download.php?action=city&term=' . urlencode($cityId) );

                                                $conn = DBConnectionClass::newInstance();
                                                $c_db = $conn->getOsclassDb();
                                                $comm = new DBCommandClass($c_db);
                                                $comm->query("SET FOREIGN_KEY_CHECKS = 0");
                                                $comm->importSQL($data_sql);
                                                $comm->query("SET FOREIGN_KEY_CHECKS = 1");
                                                osc_add_flash_ok_message(sprintf(_m('%s has been added as a new city'), $cityName), 'admin');
                                            } else {
                                                osc_add_flash_error_message(sprintf(_m("%s can't be added"), $cityName), 'admin');
                                            }

                                        } else {
                                            $mCities     = new City();
                                            $newCity     = Params::getParam('city');

                                            if(!osc_validate_min($newCity, 1)) {
                                                osc_add_flash_error_message(_m('New city name cannot be blank'), 'admin');
                                            } else {
                                                $exists = $mCities->findByName($newCity, $regionId);
                                                if(!isset($exists['s_name'])) {
                                                    $mCities->insert(array('fk_i_region_id'    => $regionId
                                                                        ,'s_name'            => $newCity
                                                                        ,'fk_c_country_code' => $countryCode));
                                                    $id = $mCities->dao->insertedId();
                                                    CityStats::newInstance()->setNumItems($id, 0);

                                                    osc_add_flash_ok_message(sprintf(_m('%s has been added as a new city'), $newCity), 'admin');
                                                } else {
                                                    osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $newCity), 'admin');
                                                }
                                            }
                                        }
                                        osc_calculate_location_slug('city');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations&country_code='.@$country['pk_c_code']."&country=".@$country['s_name']."&region=".$regionId);
                break;
                case('edit_city'):      // edit city
                                        if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        }
                                        osc_csrf_check();
                                        $mRegion = new Region();
                                        $mCities = new City();
                                        $newCity = Params::getParam('e_city');
                                        $cityId  = Params::getParam('city_id');

                                        if(!osc_validate_min($newCity, 1)) {
                                            osc_add_flash_error_message(_m('City name cannot be blank'), 'admin');
                                        } else {
                                            $city = $mCities->findByPrimaryKey($cityId);
                                            $exists = $mCities->findByName($newCity, $city['fk_i_region_id']);
                                            if(!isset($exists['pk_i_id']) || $exists['pk_i_id']==$cityId) {
                                                $region = $mRegion->findByPrimaryKey($city['fk_i_region_id']);
                                                $country = Country::newInstance()->findByCode($region['fk_c_country_code']);

                                                $name = $newCity;
                                                $slug = Params::getParam('e_country_slug');
                                                if($slug=='') {
                                                    $slug_tmp = $slug = osc_sanitizeString($name);
                                                } else {
                                                    $exists = $mCities->findBySlug($slug);
                                                    if(isset($exists['s_slug']) && $exists['pk_i_id']!=$cityId) {
                                                        $slug_tmp = $slug = osc_sanitizeString($name);
                                                    } else {
                                                        $slug_tmp = $slug = osc_sanitizeString($slug);
                                                    }
                                                }
                                                $slug_unique = 1;
                                                while(true) {
                                                    $location_slug = $mCities->findBySlug($slug);
                                                    if(isset($location_slug['s_slug']) && $location_slug['pk_i_id']!=$cityId) {
                                                        $slug = $slug_tmp . '-' . $slug_unique;
                                                        $slug_unique++;
                                                    } else {
                                                        break;
                                                    }
                                                }

                                                $mCities->update(array('s_name' => $newCity, 's_slug' => $slug)
                                                                ,array('pk_i_id' => $cityId));
                                                ItemLocation::newInstance()->update(
                                                    array('s_city'       => $newCity),
                                                    array('fk_i_city_id' => $cityId)
                                                );
                                                osc_add_flash_ok_message(sprintf(_m('%s has been edited'), $newCity), 'admin');
                                            } else {
                                                osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $newCity), 'admin');
                                            }
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations&country_code='.@$country['pk_c_code']."&country=".@$country['s_name']."&region=".@$region['pk_i_id']);
                break;
                case('delete_city'):    // delete city
                                        if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                                        }
                                        osc_csrf_check();
                                        $mCities = new City();
                                        $cityIds  = Params::getParam('id');
                                        if(is_array($cityIds)) {
                                            $locations = 0;
                                            $del_locations = 0;
                                            $cCity = end($cityIds);
                                            $cCity = $mCities->findByPrimaryKey($cCity);
                                            $region = Region::newInstance()->findByPrimaryKey($cCity['fk_i_region_id']);
                                            $country = Country::newInstance()->findByCode($cCity['fk_c_country_code']);
                                            foreach($cityIds as $cityId) {
                                                $ok = $mCities->deleteByPrimaryKey($cityId);
                                                if($ok==0) {
                                                    $del_locations++;
                                                } else {
                                                    $locations += $ok;
                                                }
                                            }
                                            if($locations==0) {
                                                osc_add_flash_ok_message(sprintf(_n('One location has been deleted', '%d locations have been deleted', $del_locations), $del_locations), 'admin');
                                            } else {
                                                osc_add_flash_error_message(_m('There was a problem deleting locations'), 'admin');
                                            }
                                        } else {
                                            osc_add_flash_error_message(_m('No city was selected'), 'admin');
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations&country_code='.@$country['pk_c_code']."&country=".@$country['s_name']."&region=".@$region['pk_i_id']);
                break;
            }

            $aCountries = $mCountries->listAll();
            $this->_exportVariableToView('aCountries', $aCountries);

            $this->doView('settings/locations.php');
        }
    }

    // EOF: ./oc-admin/controller/settings/locations.php