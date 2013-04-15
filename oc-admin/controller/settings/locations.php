<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
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
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                break;
                case('edit_country'):   // edit country
                                        osc_csrf_check();
                                        if(!osc_validate_min(Params::getParam('e_country'), 1)) {
                                            osc_add_flash_error_message(_m('Country name cannot be blank'), 'admin');
                                        } else {
                                            $ok = $mCountries->update(array('s_name'=> Params::getParam('e_country')), array('pk_c_code' => Params::getParam('country_code')));

                                            if( $ok ) {
                                                osc_add_flash_ok_message(_m('Country has been edited'), 'admin');
                                            } else {
                                                osc_add_flash_error_message(_m('There were some problems editing the country'), 'admin');
                                            }
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
                break;
                case('delete_country'): // delete country
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
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations&country_code='.@$countryCode."&country=".@$country['s_name']);
                break;
                case('edit_region'):    // edit region
                                        osc_csrf_check();
                                        $mRegions  = new Region();
                                        $newRegion = Params::getParam('e_region');
                                        $regionId  = Params::getParam('region_id');

                                        if(!osc_validate_min($newRegion, 1)) {
                                            osc_add_flash_error_message(_m('Region name cannot be blank'), 'admin');
                                        } else {
                                            $exists = $mRegions->findByName($newRegion);
                                            if(!isset($exists['pk_i_id']) || $exists['pk_i_id']==$regionId) {
                                                if($regionId != '') {
                                                    $aRegion = $mRegions->findByPrimaryKey($regionId);
                                                    $country = Country::newInstance()->findByCode($aRegion['fk_c_country_code']);
                                                    $mRegions->update(array('s_name' => $newRegion)
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
                                        osc_csrf_check();
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
                                            $mRegion     = new Region();
                                            $mCities     = new City();
                                            $regionId    = Params::getParam('region_parent');
                                            $countryCode = Params::getParam('country_c_parent');
                                            $newCity     = Params::getParam('city');

                                            if(!osc_validate_min($newCity, 1)) {
                                                osc_add_flash_error_message(_m('New city name cannot be blank'), 'admin');
                                            } else {
                                                $exists = $mCities->findByName($newCity, $regionId);
                                                $region = $mRegion->findByPrimaryKey($regionId);
                                                $country = Country::newInstance()->findByCode($region['fk_c_country_code']);
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
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations&country_code='.@$country['pk_c_code']."&country=".@$country['s_name']."&region=".$regionId);
                break;
                case('edit_city'):      // edit city
                                        osc_csrf_check();
                                        $mRegion = new Region();
                                        $mCities = new City();
                                        $newCity = Params::getParam('e_city');
                                        $cityId  = Params::getParam('city_id');

                                        if(!osc_validate_min($newCity, 1)) {
                                            osc_add_flash_error_message(_m('City name cannot be blank'), 'admin');
                                        } else {
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
                                                osc_add_flash_ok_message(sprintf(_m('%s has been edited'), $newCity), 'admin');
                                            } else {
                                                osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $newCity), 'admin');
                                            }
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations&country_code='.@$country['pk_c_code']."&country=".@$country['s_name']."&region=".@$region['pk_i_id']);
                break;
                case('delete_city'):    // delete city
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