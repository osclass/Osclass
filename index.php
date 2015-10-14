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

    define('ABS_PATH', str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME']) . '/'));
    if(PHP_SAPI==='cli') {
        define('CLI', true);
    }

    require_once ABS_PATH . 'oc-load.php';

    if( CLI ) {
        $cli_params = getopt('p:t:');
        Params::setParam('page', $cli_params['p']);
        Params::setParam('cron-type', $cli_params['t']);
        if(Params::getParam('page')=='upgrade') {
            require_once(osc_lib_path() . 'osclass/upgrade-funcs.php');
            exit(1);
        } else if( !in_array(Params::getParam('page'), array('cron')) && !in_array(Params::getParam('cron-type'), array('hourly', 'daily', 'weekly')) ) {
            exit(1);
        }
    }

    if( file_exists(ABS_PATH . '.maintenance') ) {
        if(!osc_is_admin_user_logged_in()) {
            
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            header('Retry-After: 900');
            
            if(file_exists(WebThemes::newInstance()->getCurrentThemePath().'maintenance.php')) {
                osc_current_web_theme_path('maintenance.php');
                die();
            } else {
                require_once LIB_PATH . 'osclass/helpers/hErrors.php';

                $title   = sprintf(__('Maintenance &raquo; %s'), osc_page_title());
                $message = sprintf(__('We are sorry for any inconvenience. %s is undergoing maintenance.') . '.', osc_page_title() );
                osc_die($title, $message);
            }
        } else {
            define('__OSC_MAINTENANCE__', true);
        }
    }

    if(!osc_users_enabled() && osc_is_web_user_logged_in()) {
        Session::newInstance()->_drop('userId');
        Session::newInstance()->_drop('userName');
        Session::newInstance()->_drop('userEmail');
        Session::newInstance()->_drop('userPhone');

        Cookie::newInstance()->pop('oc_userId');
        Cookie::newInstance()->pop('oc_userSecret');
        Cookie::newInstance()->set();
    }

    if(osc_is_web_user_logged_in()) {
        User::newInstance()->lastAccess(osc_logged_user_id(), date('Y-m-d H:i:s'), Params::getServerParam('REMOTE_ADDR'), 3600);
    }

    switch( Params::getParam('page') )
    {
        case ('cron'):      // cron system
                            define('__FROM_CRON__', true);
                            require_once(osc_lib_path() . 'osclass/cron.php');
        break;
        case ('user'):      // user pages (with security)
                            if(Params::getParam('action')=='change_email_confirm' || Params::getParam('action')=='activate_alert'
                            || (Params::getParam('action')=='unsub_alert' && !osc_is_web_user_logged_in())
                            || Params::getParam('action')=='contact_post'
                            || Params::getParam('action')=='pub_profile') {
                                require_once(osc_lib_path() . 'osclass/controller/user-non-secure.php');
                                $do = new CWebUserNonSecure();
                                $do->doModel();
                            } else {
                                require_once(osc_lib_path() . 'osclass/controller/user.php');
                                $do = new CWebUser();
                                $do->doModel();
                            }
        break;
        case ('item'):      // item pages
                            require_once(osc_lib_path() . 'osclass/controller/item.php');
                            $do = new CWebItem();
                            $do->doModel();
        break;
        case ('search'):    // search pages
                            require_once(osc_lib_path() . 'osclass/controller/search.php');
                            $do = new CWebSearch();
                            $do->doModel();
        break;
        case ('page'):      // static pages
                            require_once(osc_lib_path() . 'osclass/controller/page.php');
                            $do = new CWebPage();
                            $do->doModel();
        break;
        case ('register'):  // register page
                            require_once(osc_lib_path() . 'osclass/controller/register.php');
                            $do = new CWebRegister();
                            $do->doModel();
        break;
        case ('ajax'):      // ajax
                            require_once(osc_lib_path() . 'osclass/controller/ajax.php');
                            $do = new CWebAjax();
                            $do->doModel();
        break;
        case ('login'):     // login page
                            require_once(osc_lib_path() . 'osclass/controller/login.php');
                            $do = new CWebLogin();
                            $do->doModel();
        break;
        case ('language'):  // set language
                            require_once(osc_lib_path() . 'osclass/controller/language.php');
                            $do = new CWebLanguage();
                            $do->doModel();
        break;
        case ('contact'):   //contact
                            require_once(osc_lib_path() . 'osclass/controller/contact.php');
                            $do = new CWebContact();
                            $do->doModel();
        break;
        case ('custom'):   //custom
                            require_once(osc_lib_path() . 'osclass/controller/custom.php');
                            $do = new CWebCustom();
                            $do->doModel();
        break;
        default:            // home and static pages that are mandatory...
                            require_once(osc_lib_path() . 'osclass/controller/main.php');
                            $do = new CWebMain();
                            $do->doModel();
        break;
    }

    if(!defined('__FROM_CRON__')) {
        if( osc_auto_cron() ) {
            osc_doRequest(osc_base_url(), array('page' => 'cron'));
        }
    }

    /* file end: ./index.php */
?>
