<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.') ;

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

    set_time_limit(0);

    if( !defined('__FROM_CRON__') ) {
        define('__FROM_CRON__', true) ;
    }

    osc_update_cat_stats();
    
    function update_location_stats()
    {
        $aCountries     = Country::newInstance()->listAll();
        $aCountryValues = array();
        
        $aRegions       = array();
        $aRegionValues  = array();
        
        $aCities        = array();
        $aCityValues    = array();
        
        foreach($aCountries as $country){
            $id = $country['pk_c_code'] ;
            $numItems = CountryStats::newInstance()->calculateNumItems( $id ) ;
            array_push($aCountryValues, "('$id', $numItems)" );
            unset($numItems) ;
            
            $aRegions = Region::newInstance()->findByCountry($id);
            foreach($aRegions as $region) {
                $id = $region['pk_i_id'] ;
                $numItems = RegionStats::newInstance()->calculateNumItems( $id ) ;
                array_push($aRegionValues, "($id, $numItems)" );
                unset($numItems) ;
                
                $aCities = City::newInstance()->findByRegion($id) ;
                foreach($aCities as $city) {
                    $id = $city['pk_i_id'];
                    $numItems = CityStats::newInstance()->calculateNumItems( $id );
                    array_push($aCityValues, "($id, $numItems)" );
                    unset($numItems);
                }
            }
        }    
        
        // insert Country stats
        $sql_country  = 'REPLACE INTO '.DB_TABLE_PREFIX.'t_country_stats (fk_c_country_code, i_num_items) VALUES ';
        $sql_country .= implode(',', $aCountryValues);
        CountryStats::newInstance()->dao->query($sql_country);
        // insert Region stats
        $sql_region   = 'REPLACE INTO '.DB_TABLE_PREFIX.'t_region_stats (fk_i_region_id, i_num_items) VALUES ';
        $sql_region  .= implode(',', $aRegionValues);
        RegionStats::newInstance()->dao->query($sql_region);
        // insert City stats
        $sql_city     = 'REPLACE INTO '.DB_TABLE_PREFIX.'t_city_stats (fk_i_city_id, i_num_items) VALUES ';
        $sql_city    .= implode(',', $aCityValues);
        CityStats::newInstance()->dao->query($sql_city);
    }
    
    function purge_latest_searches_daily() {
        $purge = osc_purge_latest_searches() ;
        if( $purge == 'day' ) {
            LatestSearches::newInstance()->purgeDate( date('Y-m-d H:i:s', ( time() - (24 * 3600) ) ) ) ;
        }
    }

    osc_add_hook('cron_daily', 'update_cat_stats') ;
    osc_add_hook('cron_daily', 'update_location_stats') ;
    osc_add_hook('cron_daily', 'purge_latest_searches_daily') ;
    
    osc_runAlert('DAILY') ;

    osc_run_hook('cron_daily') ;

?>