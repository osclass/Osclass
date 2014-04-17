<?php if ( !defined('ABS_PATH') ) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class CWebRegister extends BaseModel
    {
        function __construct()
        {
            parent::__construct();

            if( !osc_users_enabled() ) {
                osc_add_flash_error_message( _m('Users not enabled') );
                $this->redirectTo( osc_base_url() );
            }

            if( !osc_user_registration_enabled() ) {
                osc_add_flash_error_message( _m('User registration is not enabled') );
                $this->redirectTo( osc_base_url() );
            }

            if( osc_is_web_user_logged_in() ) {
                $this->redirectTo( osc_base_url() );
            }
        }

        function doModel()
        {
            switch( $this->action ) {
                case('register'):       //register user
                                        $this->doView('user-register.php');
                break;
                case('register_post'):  //register user
                                        osc_csrf_check();
                                        if( !osc_users_enabled() ) {
                                            osc_add_flash_error_message( _m('Users are not enabled') );
                                            $this->redirectTo( osc_base_url() );
                                        }

                                        osc_run_hook('before_user_register');

                                        $banned = osc_is_banned(Params::getParam('s_email'));
                                        if($banned==1) {
                                            osc_add_flash_error_message( _m('Your current email is not allowed'));
                                            $this->redirectTo(osc_register_account_url());
                                        } else if($banned==2) {
                                            osc_add_flash_error_message( _m('Your current IP is not allowed'));
                                            $this->redirectTo(osc_register_account_url());
                                        }

                                        require_once LIB_PATH . 'osclass/UserActions.php';
                                        $userActions = new UserActions(false);
                                        $success     = $userActions->add();

                                        switch($success) {
                                            case 1: osc_add_flash_ok_message( _m('The user has been created. An activation email has been sent'));
                                                    $this->redirectTo( osc_base_url() );
                                            break;
                                            case 2: osc_add_flash_ok_message( _m('Your account has been created successfully'));
                                                    Params::setParam('action', 'login_post');
                                                    Params::setParam('email', Params::getParam('s_email'));
                                                    Params::setParam('password', Params::getParam('s_password', false, false));
                                                    require_once(osc_lib_path() . 'osclass/controller/login.php');
                                                    $do = new CWebLogin();
                                                    $do->doModel();
                                                    //$this->doView('user-login.php');
                                            break;
                                            case 3: osc_add_flash_warning_message( _m('The specified e-mail is already in use'));
                                                    $this->doView('user-register.php');
                                            break;
                                            case 4: osc_add_flash_error_message( _m('The reCAPTCHA was not entered correctly'));
                                                    $this->doView('user-register.php');
                                            break;
                                            case 5: osc_add_flash_warning_message( _m('The email is not valid'));
                                                    $this->doView('user-register.php');
                                            break;
                                            case 6: osc_add_flash_warning_message( _m('The password cannot be empty'));
                                                    $this->doView('user-register.php');
                                            break;
                                            case 7: osc_add_flash_warning_message( _m("Passwords don't match"));
                                                    $this->doView('user-register.php');
                                            break;
                                            case 8: osc_add_flash_warning_message( _m("Username is already taken"));
                                                $this->doView('user-register.php');
                                            break;
                                            case 9: osc_add_flash_warning_message( _m("The specified username is not valid, it contains some invalid words"));
                                                $this->doView('user-register.php');
                                            break;
                                            case 10: osc_add_flash_warning_message( _m('The name cannot be empty'));
                                                $this->doView('user-register.php');
                                            break;
                                        }
                break;
                case('validate'):       //validate account
                                        $id          = intval( Params::getParam('id') );
                                        $code        = Params::getParam('code');
                                        $userManager = new User();
                                        $user        = $userManager->findByIdSecret($id, $code);

                                        if ( !$user ) {
                                            osc_add_flash_error_message( _m('The link is not valid anymore. Sorry for the inconvenience!') );
                                            $this->redirectTo( osc_base_url() );
                                        }

                                        if ( $user['b_active'] == 1 ) {
                                            osc_add_flash_error_message( _m('Your account has already been validated'));
                                            $this->redirectTo( osc_base_url() );
                                        }

                                        $userManager = new User();
                                        $userManager->update(
                                                 array('b_active' => '1')
                                                ,array('pk_i_id' => $id, 's_secret' => $code)
                                        );

                                        // Auto-login
                                        Session::newInstance()->_set('userId', $user['pk_i_id']);
                                        Session::newInstance()->_set('userName', $user['s_name']);
                                        Session::newInstance()->_set('userEmail', $user['s_email']);
                                        $phone = ($user['s_phone_mobile']) ? $user['s_phone_mobile'] : $user['s_phone_land'];
                                        Session::newInstance()->_set('userPhone', $phone);

                                        osc_run_hook('hook_email_user_registration', $user);
                                        osc_run_hook('validate_user', $user);

                                        osc_add_flash_ok_message( _m('Your account has been validated'));
                                        $this->redirectTo( osc_base_url() );
                break;
            }
        }

        function doView($file)
        {
            osc_run_hook( 'before_html' );
            osc_current_web_theme_path( $file );
            Session::newInstance()->_clearVariables();
            osc_run_hook( 'after_html' );
        }
    }

    /* file end: ./register.php */
?>