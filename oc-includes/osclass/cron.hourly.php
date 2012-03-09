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

    function purge_latest_searches_hourly() {
        $purge = osc_purge_latest_searches() ;
        if( $purge == 'hour' ) {
            LatestSearches::newInstance()->purgeDate( date('Y-m-d H:i:s', ( time() - 3600) ) ) ;
        } else if( !in_array($purge, array('forever', 'day', 'week')) ) {
            LatestSearches::newInstance()->purgeNumber($purge) ;
        }
    }
    
    osc_add_hook('cron_hourly', 'purge_latest_searches_hourly') ;

    osc_runAlert('HOURLY') ;

    osc_run_hook('cron_hourly') ;
?>