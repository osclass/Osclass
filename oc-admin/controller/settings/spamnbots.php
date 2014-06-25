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