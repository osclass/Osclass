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

    class CAdminSettingsMailserver extends AdminSecBaseModel
    {
        //Business Layer...
        function doModel()
        {
            switch($this->action) {
                case('mailserver'):
                    // calling the mailserver view
                    $this->doView('settings/mailserver.php');
                break;
                case('mailserver_post'):
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=mailserver');
                    }

                    osc_csrf_check();
                    // updating mailserver
                    $iUpdated           = 0;
                    $mailserverAuth     = Params::getParam('mailserver_auth');
                    $mailserverAuth     = ($mailserverAuth != '' ? true : false);
                    $mailserverPop      = Params::getParam('mailserver_pop');
                    $mailserverPop      = ($mailserverPop != '' ? true : false);
                    $mailserverType     = Params::getParam('mailserver_type');
                    $mailserverHost     = Params::getParam('mailserver_host');
                    $mailserverPort     = Params::getParam('mailserver_port');
                    $mailserverUsername = Params::getParam('mailserver_username');
                    $mailserverPassword = Params::getParam('mailserver_password', false, false);
                    $mailserverSsl      = Params::getParam('mailserver_ssl');
                    $mailserverMailFrom = Params::getParam('mailserver_mail_from');
                    $mailserverNameFrom = Params::getParam('mailserver_name_from');

                    if( !in_array($mailserverType, array('custom', 'gmail')) ) {
                        osc_add_flash_error_message( _m('Mail server type is incorrect'), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=mailserver');
                    }

                    $iUpdated += osc_set_preference('mailserver_auth', $mailserverAuth);
                    $iUpdated += osc_set_preference('mailserver_pop', $mailserverPop);
                    $iUpdated += osc_set_preference('mailserver_type', $mailserverType);
                    $iUpdated += osc_set_preference('mailserver_host', $mailserverHost);
                    $iUpdated += osc_set_preference('mailserver_port', $mailserverPort);
                    $iUpdated += osc_set_preference('mailserver_username', $mailserverUsername);
                    $iUpdated += osc_set_preference('mailserver_password', $mailserverPassword);
                    $iUpdated += osc_set_preference('mailserver_ssl', $mailserverSsl);
                    $iUpdated += osc_set_preference('mailserver_mail_from', $mailserverMailFrom);
                    $iUpdated += osc_set_preference('mailserver_name_from', $mailserverNameFrom);

                    if($iUpdated > 0) {
                        osc_add_flash_ok_message( _m('Mail server configuration has changed'), 'admin');
                    }
                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=mailserver');
                break;
            }
        }
    }

    // EOF: ./oc-admin/controller/settings/mailserver.php