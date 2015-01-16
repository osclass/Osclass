<?php
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

    function osc_runAlert($type = null, $last_exec = null) {
        if ( !in_array($type, array('HOURLY', 'DAILY', 'WEEKLY', 'INSTANT')) ) {
            return;
        }

        if($last_exec==null) {
            $cron = Cron::newInstance()->getCronByType($type);
            if( is_array($cron) ) {
                $last_exec = $cron['d_last_exec'];
            } else {
                $last_exec = '0000-00-00 00:00:00';
            }
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
        $searches = Alerts::newInstance()->findByTypeGroup($type, $active);


        foreach($searches as $s_search) {
            // Get if there're new ads on this search
            $json             = $s_search['s_search'];
            $array_conditions = (array)json_decode($json);

            $new_search = Search::newInstance();
            $new_search->setJsonAlert($array_conditions);

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
                        osc_run_hook('hook_'.$internal_name, $user, $ads, $s_search, $items, $totalItems);
                        AlertsStats::newInstance()->increase(date('Y-m-d'));
                    }
                }
            }
        }
    }

?>
