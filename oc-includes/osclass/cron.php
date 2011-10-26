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

    $d_now = date('Y-m-d H:i:s') ;
    $i_now = strtotime($d_now) ;

    // Hourly crons
    $cron = Cron::newInstance()->getCronByType('HOURLY') ;
    if( is_array($cron) ) {
        $i_next = strtotime($cron['d_next_exec']);

        if( ($i_now - $i_next) >= 0 ) {
            require_once LIB_PATH . 'osclass/cron.hourly.php' ;

            // update the next execution time in t_cron
            $d_next = date('Y-m-d H:i:s', $i_now + 3600) ;
            Cron::newInstance()->update(array('d_last_exec' => $d_now, 'd_next_exec' => $d_next),
                                        array('e_type'      => 'HOURLY')) ;
        }
    }

    // Daily crons
    $cron = Cron::newInstance()->getCronByType('DAILY') ;
    if( is_array($cron) ) {
        $i_next = strtotime($cron['d_next_exec']) ;

        if( ($i_now - $i_next) >= 0 ) {
            require_once LIB_PATH . 'osclass/cron.daily.php' ;

            // update the next execution time in t_cron
            $d_next = date('Y-m-d H:i:s', $i_now + (24 * 3600)) ;
            Cron::newInstance()->update(array('d_last_exec' => $d_now, 'd_next_exec' => $d_next),
                                        array('e_type'      => 'DAILY')) ;
        }
    }

    // Weekly crons
    $cron = Cron::newInstance()->getCronByType('WEEKLY') ;
    if(is_array($cron)) {
        $i_next = strtotime($cron['d_next_exec']) ;

        if( ($i_now - $i_next) >= 0 ) {
            require_once LIB_PATH . 'osclass/cron.weekly.php' ;

            // update the next execution time in t_cron
            $d_next = date('Y-m-d H:i:s', $i_now + (7 * 24 * 3600)) ;
            Cron::newInstance()->update(array('d_last_exec' => $d_now, 'd_next_exec' => $d_next),
                                        array('e_type'      => 'WEEKLY')) ;
        }
    }

    osc_run_hook('cron') ;

?>