<?php
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

    function osc_runAlert($type = null) {
        if ( !in_array($type, array('HOURLY', 'DAILY', 'WEEKLY', 'INSTANT')) ) {
            return;
        }

        $internal_name = 'alert_email_hourly';
        switch ($type) {
            case 'HOURLY':
                $internal_name = 'alert_email_hourly';
            break;
            case 'DAILY':
                $internal_name = 'alert_email_daily';
            break;
            case 'WEEKLY':
                $internal_name = 'alert_email_weekly';
            break;
            case 'INSTANT':
                $internal_name = 'alert_email_instant';
            break;
        }

        $active   = TRUE;
        $searches = Alerts::newInstance()->findByType($type, $active);
        foreach($searches as $s_search) {
            // Get if there're new ads on this search
            $json             = base64_decode($s_search['s_search']);
            $array_conditions = (array)json_decode($json);

            $new_search = Search::newInstance();
            $new_search->setJsonAlert($array_conditions);

            $cron = Cron::newInstance()->getCronByType($type);
            if( is_array($cron) ) {
                $last_exec = $cron['d_last_exec'];
            } else {
                $last_exec = '0000-00-00 00:00:00';
            }

            $new_search->addConditions(sprintf(" %st_item.dt_pub_date > '%s' ", DB_TABLE_PREFIX, $last_exec));

            $items      = $new_search->doSearch();
            $totalItems = $new_search->count();

            if( count($items) > 0 ) {
                // If we have new items from last check
                // Catch the user subscribed to this search
                $users = Alerts::newInstance()->findUsersBySearchAndType($s_search['s_search'], $type, $active);

                if( count($users) > 0 ) {
                    $ads = '';
                    foreach($items as $item) {
                        $ads .= '<a href="'. osc_item_url_ns($item['pk_i_id']).'">' . $item['s_title'] . '</a><br/>';
                    }

                    foreach($users as $user) {
                        osc_run_hook('hook_'.$internal_name, $user, $ads, $s_search);
                        AlertsStats::newInstance()->increase(date('Y-m-d'));
                    }
                }
            }
        }
    }

?>