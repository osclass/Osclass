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

    class CAdminMain extends AdminSecBaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        //Business Layer...
        function doModel()
        {
            switch($this->action) {
                case('logout'):     // unset only the required parameters in Session
                                    osc_run_hook('logout_admin');
                                    $this->logout();
                                    $this->redirectTo( osc_admin_base_url(true) );
                break;
                default:            //default dashboard page (main page at oc-admin)
                                    $this->_exportVariableToView( "numItemsPerCategory", osc_get_non_empty_categories() );

                                    $this->_exportVariableToView( "numUsers", User::newInstance()->count() );
                                    $this->_exportVariableToView( "numItems", Item::newInstance()->count() );

                                    // stats
                                    $items = array();
                                    $stats_items = Stats::newInstance()->new_items_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m"), date("d") - 10, date("Y")) ),'day');
                                    for($k = 10; $k >= 0; $k--) {
                                        $items[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )] = 0;
                                    }
                                    foreach($stats_items as $item) {
                                        $items[$item['d_date']] = $item['num'];
                                    }
                                    $users = array();
                                    $stats_users = Stats::newInstance()->new_users_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m"), date("d") - 10, date("Y")) ),'day');
                                    for($k = 10; $k >= 0; $k--) {
                                        $users[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )] = 0;
                                    }
                                    foreach($stats_users as $user) {
                                        $users[$user['d_date']] = $user['num'];
                                    }

                                    if(function_exists('disk_free_space')) {
                                        $freedisk = @disk_free_space(osc_uploads_path());
                                        if($freedisk!==false && $freedisk<52428800) { //52428800 = 50*1024*1024
                                            osc_add_flash_error_message(_m('You have very few free space left, users will not be able to upload pictures'), 'admin');
                                        }
                                    }

                                    // show messages subscribed
                                    $status_subscribe = Params::getParam('subscribe_osclass');
                                    if( $status_subscribe != '' ) {
                                        switch( $status_subscribe ) {
                                            case -1:
                                                osc_add_flash_error_message(_m('Entered an invalid email'), 'admin');
                                            break;
                                            case 0:
                                                osc_add_flash_warning_message(_m("You're already subscribed"), 'admin');
                                            break;
                                            case 1:
                                                osc_add_flash_ok_message(_m('Subscribed correctly'), 'admin');
                                            break;
                                            default:
                                                osc_add_flash_warning_message(_m("Error subscribing"), 'admin');
                                            break;
                                        }
                                    }

                                    $this->_exportVariableToView("item_stats", $items);
                                    $this->_exportVariableToView("user_stats", $users);
                                    //calling the view...
                                    $this->doView('main/index.php');
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

    /* file end: ./oc-admin/main.php */
?>