<?php

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

function osc_runAlert($type = null) {

    if ($type == null) {
        return;
    }

    $internal_name = 'alert_email_hourly' ;
    switch ($type) {
        case 'HOURLY':
            $internal_name = 'alert_email_hourly' ;
        break;
        case 'DAILY':
            $internal_name = 'alert_email_daily' ;
        break;
        case 'WEEKLY':
            $internal_name = 'alert_email_weekly' ;
        break;
        case 'INSTANT':
            $internal_name = 'alert_email_instant' ;
        break;
    }


    $searches = Alerts::newInstance()->getAlertsByTypeGroup($type) ;
    foreach ($searches as $s_search) {
        $a_search = Search::newInstance();

        // Get if there're new ads on this search
        $a_search = osc_unserialize(base64_decode($s_search['s_search'])) ;
        $crons = Cron::newInstance()->getCronByType($type);
        if (isset($crons[0])) {
            $last_exec = $crons[0]['d_last_exec'] ;
        } else {
            $last_exec = '0000-00-00 00:00:00' ;
        }

        $a_search->addConditions(sprintf(" %st_item.dt_pub_date > '%s' ", DB_TABLE_PREFIX, $last_exec)) ;

        $totalItems = $a_search->count();
        $items = $a_search->search();


        if (count($items) > 0) {
            //If we have new items from last check
            //Catch the user subscribed to this search
            $users = Alerts::newInstance()->getUsersBySearchAndType($search['s_search'], $type) ;

            if (count($users > 0)) {
                $prefLocale = osc_language() ;
                $_page = Page::newInstance()->findByInternalName($internal_name) ;
                $page = array();
                $data = osc_unserialize($_page['s_data']) ;
                $page = $data[$prefLocale] ;
                unset($data);
                unset($_page);

                $ads = "";
                foreach ($items as $item) {
                    $ads .= "<a>" . $item['s_title'] . "</a><br/>" ;
                }

                foreach ($users as $user)
                {
                    $unsub_link = osc_user_unsubscribe_alert_url($user['s_email'], $s_search['s_search']);//osc_create_url(array('file' => 'user', 'action' => 'unsub_alert', 'email' => $user['s_email'], 'alert' => $s_search['s_search'])) ;
                    $unsub_link = '<a href="'.$unsub_link.'" >'.$unsub_link.'</a>';

                    $words = array() ;
                    $words = array('{USER_NAME}', '{USER_EMAIL}', '{ADS}', '{UNSUB_LINK}') ;
                    $words = array($user['s_name'], $user['s_email'], $ads, $unsub_link) ;
                    $title = osc_mailBeauty($page['s_title'], $words) ;
                    $body = osc_mailBeauty($page['s_body'], $words) ;

                    $params = array(
                        'subject' => $title
                        ,'to' => $user['s_email']
                        ,'to_name' => $user['s_name']
                        ,'body' => $body
                        ,'alt_body' => $body
                    ) ;
                    
                    osc_sendMail($params) ;
                }
            }
        }
    }
}

?>
