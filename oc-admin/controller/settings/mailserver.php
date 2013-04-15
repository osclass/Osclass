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
                    $mailserverPassword = Params::getParam('mailserver_password');
                    $mailserverSsl      = Params::getParam('mailserver_ssl');
                    $mailserverMailFrom = Params::getParam('mailserver_mail_from');
                    $mailserverNameFrom = Params::getParam('mailserver_name_from');

                    if( !in_array($mailserverType, array('custom', 'gmail')) ) {
                        osc_add_flash_error_message( _m('Mail server type is incorrect'), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=mailserver');
                    }

                    $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverAuth)
                                                                   ,array('s_name' => 'mailserver_auth'));
                    $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverPop)
                                                                   ,array('s_name' => 'mailserver_pop'));
                    $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverType)
                                                                   ,array('s_name' => 'mailserver_type'));
                    $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverHost)
                                                                   ,array('s_name' => 'mailserver_host'));
                    $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverPort)
                                                                   ,array('s_name' => 'mailserver_port'));
                    $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverUsername)
                                                                   ,array('s_name' => 'mailserver_username'));
                    $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverPassword)
                                                                   ,array('s_name' => 'mailserver_password'));
                    $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverSsl)
                                                                   ,array('s_name' => 'mailserver_ssl'));
                    $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverMailFrom)
                                                                ,array('s_name' => 'mailserver_mail_from'));
                    $iUpdated += Preference::newInstance()->update(array('s_value' => $mailserverNameFrom)
                                                                ,array('s_name' => 'mailserver_name_from'));

                    if($iUpdated > 0) {
                        osc_add_flash_ok_message( _m('Mail server configuration has changed'), 'admin');
                    }
                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=mailserver');
                break;
            }
        }
    }

    // EOF: ./oc-admin/controller/settings/mailserver.php