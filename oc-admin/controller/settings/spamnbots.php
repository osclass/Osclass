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

    class CAdminSettingsSpamnBots extends AdminSecBaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        //Business Layer...
        function doModel()
        {
            switch($this->action) {
                case('spamNbots'):
                    // calling the spam and bots view
                    $akismet_key    = osc_akismet_key();
                    $akismet_status = 3;
                    if( $akismet_key != '' ) {
                        require_once( osc_lib_path() . 'Akismet.class.php' );
                        $akismet_obj    = new Akismet(osc_base_url(), $akismet_key);
                        $akismet_status = 2;
                        if( $akismet_obj->isKeyValid() ) {
                            $akismet_status = 1;
                        }
                    }

                    View::newInstance()->_exportVariableToView('akismet_status', $akismet_status);
                    $this->doView('settings/spamNbots.php');
                break;
                case('akismet_post'):
                    // updating spam and bots option
                    osc_csrf_check();
                    $updated    = 0;
                    $akismetKey = Params::getParam('akismetKey');
                    $akismetKey = trim($akismetKey);

                    $updated = osc_set_preference('akismetKey', $akismetKey);

                    if( $akismetKey == '' ) {
                        osc_add_flash_info_message(_m('Your Akismet key has been cleared'), 'admin');
                    } else {
                        osc_add_flash_ok_message(_m('Your Akismet key has been updated'), 'admin');
                    }
                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=spamNbots');
                break;
                case('recaptcha_post'):
                    // updating spam and bots option
                    osc_csrf_check();
                    $iUpdated = 0;
                    $recaptchaPrivKey = Params::getParam('recaptchaPrivKey');
                    $recaptchaPrivKey = trim($recaptchaPrivKey);
                    $recaptchaPubKey  = Params::getParam('recaptchaPubKey');
                    $recaptchaPubKey  = trim($recaptchaPubKey);

                    $iUpdated += osc_set_preference('recaptchaPrivKey', $recaptchaPrivKey);
                    $iUpdated += osc_set_preference('recaptchaPubKey', $recaptchaPubKey);

                    if( $recaptchaPubKey == '' ) {
                        osc_add_flash_info_message(_m('Your reCAPTCHA key has been cleared'), 'admin');
                    } else {
                        osc_add_flash_ok_message( _m('Your reCAPTCHA key has been updated') ,'admin');
                    }
                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=spamNbots');
                break;
            }
        }
    }

    // EOF: ./oc-admin/controller/settings/spamnbots.php