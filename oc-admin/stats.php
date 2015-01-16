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

    class CAdminStats extends AdminSecBaseModel
    {
        //specific for this class
        function __construct()
        {
            parent::__construct();

            //specific things for this class
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel();

            //specific things for this class
            switch ($this->action) {
                case('reports'):        // manage stats view
                                        $reports = array();
                                        if( Params::getParam('type_stat') == 'week' ) {
                                            $stats_reports = Stats::newInstance()->new_reports_count(date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 70, date("Y")) ),'week');
                                            for($k = 10; $k >= 0; $k--) {
                                                $reports[date( 'W', mktime(0, 0, 0, date("m"), date("d"), date("Y")) ) - $k]['views']          = 0;
                                                $reports[date( 'W', mktime(0, 0, 0, date("m"), date("d"), date("Y")) ) - $k]['spam']           = 0;
                                                $reports[date( 'W', mktime(0, 0, 0, date("m"), date("d"), date("Y")) ) - $k]['repeated']       = 0;
                                                $reports[date( 'W', mktime(0, 0, 0, date("m"), date("d"), date("Y")) ) - $k]['bad_classified'] = 0;
                                                $reports[date( 'W', mktime(0, 0, 0, date("m"), date("d"), date("Y")) ) - $k]['offensive']      = 0;
                                                $reports[date( 'W', mktime(0, 0, 0, date("m"), date("d"), date("Y")) ) - $k]['expired']        = 0;
                                            }
                                        } else if( Params::getParam('type_stat') == 'month' ) {
                                            $stats_reports = Stats::newInstance()->new_reports_count(date( 'Y-m-d', mktime(0, 0, 0, date("m") - 10, date("d"), date("Y")) ),'month');
                                            for($k = 10; $k >= 0; $k--) {
                                                $reports[date( 'F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y")) )]['views']          = 0;
                                                $reports[date( 'F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y")) )]['spam']           = 0;
                                                $reports[date( 'F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y")) )]['repeated']       = 0;
                                                $reports[date( 'F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y")) )]['bad_classified'] = 0;
                                                $reports[date( 'F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y")) )]['offensive']      = 0;
                                                $reports[date( 'F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y")) )]['expired']        = 0;
                                            }
                                        } else {
                                            $stats_reports = Stats::newInstance()->new_reports_count(date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 10, date("Y")) ),'day');
                                            for($k = 10; $k >= 0; $k--) {
                                                $reports[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )]['views']          = 0;
                                                $reports[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )]['spam']           = 0;
                                                $reports[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )]['repeated']       = 0;
                                                $reports[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )]['bad_classified'] = 0;
                                                $reports[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )]['offensive']      = 0;
                                                $reports[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )]['expired']        = 0;
                                            }
                                        }
                                        $max = array();
                                        $max['views'] = 0;
                                        $max['other'] = 0;
                                        foreach($stats_reports as $report) {
                                            $reports[$report['d_date']]['views']          = $report['views'];
                                            $reports[$report['d_date']]['spam']           = $report['spam'];
                                            $reports[$report['d_date']]['repeated']       = $report['repeated'];
                                            $reports[$report['d_date']]['bad_classified'] = $report['bad_classified'];
                                            $reports[$report['d_date']]['offensive']      = $report['offensive'];
                                            $reports[$report['d_date']]['expired']        = $report['expired'];
                                            if( $report['views']>$max['views'] ) {
                                                $max['views'] = $report['views'];
                                            }
                                            if( $report['spam']>$max['other'] ) {
                                                $max['other'] = $report['spam'];
                                            }
                                            if( $report['repeated']>$max['other'] ) {
                                                $max['other'] = $report['repeated'];
                                            }
                                            if( $report['bad_classified']>$max['other'] ) {
                                                $max['other'] = $report['bad_classified'];
                                            }
                                            if( $report['offensive']>$max['other'] ) {
                                                $max['other'] = $report['offensive'];
                                            }
                                            if( $report['expired']>$max['other'] ) {
                                                $max['other'] = $report['expired'];
                                            }
                                        }
                                        $this->_exportVariableToView("reports", $reports);
                                        $this->_exportVariableToView("max", $max);
                                        $this->doView("stats/reports.php");
                break;
                case('comments'):       // manage stats view
                                        $comments = array();
                                        if( Params::getParam('type_stat') == 'week' ) {
                                            $stats_comments = Stats::newInstance()->new_comments_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m"), date("d") - 70, date("Y")) ),'week');
                                            for($k = 10; $k >= 0; $k--) {
                                                $comments[date( 'W', mktime(0, 0, 0, date("m"), date("d"), date("Y")) ) - $k] = 0;
                                            }
                                        } else if( Params::getParam('type_stat') == 'month' ) {
                                            $stats_comments = Stats::newInstance()->new_comments_count(date('Y-m-d H:i:s',  mktime(0, 0, 0, date("m") - 10, date("d"), date("Y")) ),'month');
                                            for($k = 10; $k >= 0; $k--) {
                                                $comments[date( 'F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y")) )] = 0;
                                            }
                                        } else {
                                            $stats_comments = Stats::newInstance()->new_comments_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m"), date("d") - 10, date("Y")) ),'day');
                                            for($k = 10; $k >= 0; $k--) {
                                                $comments[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )] = 0;
                                            }
                                        }
                                        $max = 0;
                                        foreach($stats_comments as $comment) {
                                            $comments[$comment['d_date']] = $comment['num'];
                                            if( $comment['num'] > $max ) {
                                                $max = $comment['num'];
                                            }
                                        }
                                        $this->_exportVariableToView("comments", $comments);
                                        $this->_exportVariableToView("latest_comments", Stats::newInstance()->latest_comments());
                                        $this->_exportVariableToView("max", $max);
                                        $this->doView("stats/comments.php");
                break;
                default:
                case('items'):          // manage stats view
                                        $items = array();
                                        $reports = array();
                                        if( Params::getParam('type_stat') == 'week' ) {
                                            $stats_items = Stats::newInstance()->new_items_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m"), date("d") - 70, date("Y")) ),'week');
                                            $stats_reports = Stats::newInstance()->new_reports_count(date( 'Y-m-d',  mktime(0, 0, 0, date("m"), date("d") - 70, date("Y")) ),'week');
                                            for($k = 10; $k >= 0; $k--) {
                                                $reports[date( 'W', mktime(0, 0, 0, date("m"), date("d"), date("Y")) ) - $k]['views'] = 0;
                                                $items[date( 'W', mktime(0, 0, 0, date("m"), date("d"), date("Y")) ) - $k] = 0;
                                            }
                                        } else if( Params::getParam('type_stat') == 'month' ) {
                                            $stats_items = Stats::newInstance()->new_items_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m") - 10, date("d"), date("Y")) ),'month');
                                            $stats_reports = Stats::newInstance()->new_reports_count(date( 'Y-m-d',  mktime(0, 0, 0, date("m") - 10, date("d"), date("Y")) ),'month');
                                            for($k = 10; $k >= 0; $k--) {
                                                $reports[date( 'F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y")) )]['views'] = 0;
                                                $items[date( 'F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y")) )] = 0;
                                            }
                                        } else {
                                            $stats_items = Stats::newInstance()->new_items_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m"), date("d") - 10, date("Y")) ),'day');
                                            $stats_reports = Stats::newInstance()->new_reports_count(date('Y-m-d',  mktime(0, 0, 0, date("m"), date("d") - 10, date("Y")) ),'day');
                                            for($k = 10; $k >= 0; $k--) {
                                                $reports[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )]['views'] = 0;
                                                $items[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )] = 0;
                                            }
                                        }
                                        $max = 0;
                                        foreach($stats_items as $item) {
                                            $items[$item['d_date']] = $item['num'];
                                            if( $item['num'] > $max ) {
                                                $max = $item['num'];
                                            }
                                        }
                                        $max_views = 0;
                                        foreach($stats_reports as $report) {
                                            $reports[$report['d_date']]['views'] = $report['views'];
                                            if( $report['views'] > $max_views ) {
                                                $max_views = $report['views'];
                                            }
                                        }


                                        $alerts = array();
                                        $subscribers = array();
                                        if( Params::getParam('type_stat') == 'week' ) {
                                            $stats_alerts = Stats::newInstance()->new_alerts_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m"), date("d") - 70, date("Y")) ),'week');
                                            $stats_subscribers = Stats::newInstance()->new_subscribers_count(date( 'Y-m-d',  mktime(0, 0, 0, date("m"), date("d") - 70, date("Y")) ),'week');
                                            for($k = 10; $k >= 0; $k--) {
                                                $subscribers[date( 'W', mktime(0, 0, 0, date("m"), date("d"), date("Y")) ) - $k] = 0;
                                                $alerts[date( 'W', mktime(0, 0, 0, date("m"), date("d"), date("Y")) ) - $k] = 0;
                                            }
                                        } else if( Params::getParam('type_stat') == 'month' ) {
                                            $stats_alerts = Stats::newInstance()->new_alerts_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m") - 10, date("d"), date("Y")) ),'month');
                                            $stats_subscribers = Stats::newInstance()->new_subscribers_count(date( 'Y-m-d',  mktime(0, 0, 0, date("m") - 10, date("d"), date("Y")) ),'month');
                                            for($k = 10; $k >= 0; $k--) {
                                                $subscribers[date( 'F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y")) )] = 0;
                                                $alerts[date( 'F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y")) )] = 0;
                                            }
                                        } else {
                                            $stats_alerts = Stats::newInstance()->new_alerts_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m"), date("d") - 10, date("Y")) ),'day');
                                            $stats_subscribers = Stats::newInstance()->new_subscribers_count(date('Y-m-d',  mktime(0, 0, 0, date("m"), date("d") - 10, date("Y")) ),'day');
                                            for($k = 10; $k >= 0; $k--) {
                                                $subscribers[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )] = 0;
                                                $alerts[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )] = 0;
                                            }
                                        }
                                        $max        = 0;
                                        $max_alerts = 0;
                                        foreach($stats_alerts as $alert) {
                                            $alerts[$alert['d_date']] = $alert['num'];
                                            if( $alert['num'] > $max ) {
                                                $max_alerts = $alert['num'];
                                            }
                                        }
                                        $max_subs = 0;
                                        foreach($stats_subscribers as $subscriber) {
                                            $subscribers[$subscriber['d_date']] = $subscriber['num'];
                                            if( $subscriber['num'] > $max_subs ) {
                                                $max_subs = $subscriber['num'];
                                            }
                                        }


                                        $this->_exportVariableToView("reports", $reports);
                                        $this->_exportVariableToView("items", $items);
                                        $this->_exportVariableToView("latest_items", Stats::newInstance()->latest_items());
                                        $this->_exportVariableToView("max", $max);
                                        $this->_exportVariableToView("max_views", $max_views);

                                        $this->_exportVariableToView("subscribers", $subscribers);
                                        $this->_exportVariableToView("alerts", $alerts);
                                        $this->_exportVariableToView("max_alerts", $max_alerts);
                                        $this->_exportVariableToView("max_subs", $max_subs);

                                        $this->doView("stats/items.php");
                break;
                case('users'):          // manage stats view
                                        $users = array();
                                        if( Params::getParam('type_stat') == 'week' ) {
                                            $stats_users = Stats::newInstance()->new_users_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m"), date("d") - 70, date("Y")) ),'week');
                                            for($k = 10; $k >= 0; $k--) {
                                                $users[date('W', mktime(0,0,0, date("m"), date("d"), date("Y")))-$k] = 0;
                                            }
                                        } else if( Params::getParam('type_stat') == 'month' ) {
                                            $stats_users = Stats::newInstance()->new_users_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m") - 10, date("d"), date("Y")) ),'month');
                                            for($k = 10; $k >= 0; $k--) {
                                                $users[date('F', mktime(0,0,0, date("m")-$k, date("d"), date("Y")))] = 0;
                                            }
                                        } else {
                                            $stats_users = Stats::newInstance()->new_users_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m"), date("d") - 10, date("Y")) ),'day');
                                            for($k = 10; $k >= 0; $k--) {
                                                $users[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )] = 0;
                                            }
                                        }
                                        $max = 0;
                                        foreach($stats_users as $user) {
                                            $users[$user['d_date']] = $user['num'];
                                            if($user['num']>$max) {
                                                $max = $user['num'];
                                            }
                                        }
                                        $item = Stats::newInstance()->items_by_user();
                                        $this->_exportVariableToView("users_by_country", Stats::newInstance()->users_by_country());
                                        $this->_exportVariableToView("users_by_region", Stats::newInstance()->users_by_region());
                                        $this->_exportVariableToView("item", (!isset($item[0]['avg']) || !is_numeric($item[0]['avg'])) ? 0 : $item[0]['avg']);
                                        $this->_exportVariableToView("latest_users", Stats::newInstance()->latest_users());
                                        $this->_exportVariableToView("users", $users);
                                        $this->_exportVariableToView("max", $max);
                                        $this->doView("stats/users.php");
                break;
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_run_hook("before_admin_html");
            osc_current_admin_theme_path($file);
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_admin_html");
        }
    }

    /* file end: ./oc-admin/stats.php */
?>