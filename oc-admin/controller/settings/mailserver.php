<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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