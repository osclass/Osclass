<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /**
     * Osclass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2012 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    class CWebLogin extends BaseModel
    {
        function __construct()
        {
            parent::__construct();
            if( !osc_users_enabled() ) {
                osc_add_flash_error_message( _m('Users not enabled') );
                $this->redirectTo(osc_base_url());
            }
        }

        //Business Layer...
        function doModel()
        {
            switch( $this->action ) {
                case('login_post'):     //post execution for the login
                                        if(!osc_users_enabled()) {
                                            osc_add_flash_error_message(_m('Users are not enabled'));
                                            $this->redirectTo(osc_base_url());
                                        }
                                        osc_csrf_check();
                                        require_once LIB_PATH . 'osclass/UserActions.php';
                                        $user = User::newInstance()->findByEmail( Params::getParam('email') );
                                        if (!$user) {
                                            $user = User::newInstance()->findByUsername( Params::getParam('email') );
                                        }

                                        $url_redirect = osc_get_http_referer();
                                        $page_redirect = '';
                                        if(osc_rewrite_enabled()) {
                                            if($url_redirect!='') {
                                                $request_uri = urldecode(preg_replace('@^' . osc_base_url() . '@', "", $url_redirect));
                                                $tmp_ar = explode("?", $request_uri);
                                                $request_uri = $tmp_ar[0];
                                                $rules = Rewrite::newInstance()->listRules();
                                                foreach($rules as $match => $uri) {
                                                    if(preg_match('#'.$match.'#', $request_uri, $m)) {
                                                        $request_uri = preg_replace('#'.$match.'#', $uri, $request_uri);
                                                        if(preg_match('|([&?]{1})page=([^&]*)|', '&'.$request_uri.'&', $match)) {
                                                            $page_redirect = $match[2];
                                                            if($page_redirect=='' || $page_redirect=='login') {
                                                                $url_redirect = osc_user_dashboard_url();
                                                            }
                                                        }
                                                        break;
                                                    }
                                                }
                                            }
                                        //} else if(preg_match('|[\?&]page=([^&]+)|', $url_redirect.'&', $match)) {
                                        //    $page_redirect = $match[1];
                                        }
                                        if($url_redirect=='') {
                                            $url_redirect = osc_user_dashboard_url();
                                        }

                                        if (!$user) {
                                            osc_add_flash_error_message(_m("The user doesn't exist"));
                                            $this->redirectTo(osc_user_login_url());
                                        }

                                        if ( $user["s_password"] != sha1( Params::getParam('password', false, false) ) ) {
                                            osc_add_flash_error_message( _m('The password is incorrect'));
                                            $this->redirectTo(osc_user_login_url());
                                        }

                                        $banned = osc_is_banned(Params::getParam('email'));
                                        if($banned==1) {
                                            osc_add_flash_error_message( _m('Your current email is not allowed'));
                                            $this->redirectTo(osc_user_login_url());
                                        } else if($banned==2) {
                                            osc_add_flash_error_message( _m('Your current IP is not allowed'));
                                            $this->redirectTo(osc_user_login_url());
                                        }

                                        $uActions = new UserActions(false);
                                        $logged = $uActions->bootstrap_login($user['pk_i_id']);

                                        if($logged==0) {
                                            osc_add_flash_error_message(_m("The user doesn't exist"));
                                        } else if($logged==1) {
                                            osc_add_flash_error_message(_m('The user has not been validated yet'));
                                        } else if($logged==2) {
                                            osc_add_flash_error_message(_m('The user has been suspended'));
                                        } else if($logged==3) {
                                            if ( Params::getParam('remember') == 1 ) {

                                                //this include contains de osc_genRandomPassword function
                                                require_once osc_lib_path() . 'osclass/helpers/hSecurity.php';
                                                $secret = osc_genRandomPassword();

                                                User::newInstance()->update(
                                                    array('s_secret' => $secret)
                                                    ,array('pk_i_id' => $user['pk_i_id'])
                                                );

                                                Cookie::newInstance()->set_expires( osc_time_cookie() );
                                                Cookie::newInstance()->push('oc_userId', $user['pk_i_id']);
                                                Cookie::newInstance()->push('oc_userSecret', $secret);
                                                Cookie::newInstance()->set();

                                            }

                                            osc_run_hook("after_login", $user, $url_redirect);

                                            $this->redirectTo( osc_apply_filter('correct_login_url_redirect', $url_redirect) );

                                        } else {
                                            osc_add_flash_error_message(_m('This should never happen'));
                                        }

                                        if(!$user['b_enabled']) {
                                            $this->redirectTo(osc_user_login_url());
                                        }

                                        $this->redirectTo(osc_user_login_url());
                break;
                case('recover'):        //form to recover the password (in this case we have the form in /gui/)
                                        $this->doView( 'user-recover.php' );
                break;
                case('recover_post'):   //post execution to recover the password
                                        osc_csrf_check();
                                        require_once LIB_PATH . 'osclass/UserActions.php';

                                        // e-mail is incorrect
                                        if( !preg_match('|^[a-z0-9\.\_\+\-]+@[a-z0-9\.\-]+\.[a-z]{2,3}$|i', Params::getParam('s_email')) ) {
                                            osc_add_flash_error_message( _m('Invalid email address') );
                                            $this->redirectTo( osc_recover_user_password_url() );
                                        }

                                        $userActions = new UserActions(false);
                                        $success = $userActions->recover_password();

                                        switch ($success) {
                                            case(0): // recover ok
                                                     osc_add_flash_ok_message( _m('We have sent you an email with the instructions to reset your password'));
                                                     $this->redirectTo( osc_base_url() );
                                                     break;
                                            case(1): // e-mail does not exist
                                                     osc_add_flash_error_message( _m('We were not able to identify you given the information provided'));
                                                     $this->redirectTo( osc_recover_user_password_url() );
                                                     break;
                                            case(2): // recaptcha wrong
                                                     osc_add_flash_error_message( _m('The recaptcha code is wrong'));
                                                     $this->redirectTo( osc_recover_user_password_url() );
                                                     break;
                                        }
                break;
                case('forgot'):         //form to recover the password (in this case we have the form in /gui/)
                                        $user = User::newInstance()->findByIdPasswordSecret(Params::getParam('userId'), Params::getParam('code'));
                                        if($user) {
                                            $this->doView( 'user-forgot_password.php' );
                                        } else {
                                            osc_add_flash_error_message( _m('Sorry, the link is not valid'));
                                            $this->redirectTo( osc_base_url() );
                                        }
                break;
                case('forgot_post'):
                                        osc_csrf_check();
                                        if( (Params::getParam('new_password', false, false) == '') || (Params::getParam('new_password2', false, false) == '') ) {
                                            osc_add_flash_warning_message( _m('Password cannot be blank'));
                                            $this->redirectTo(osc_forgot_user_password_confirm_url(Params::getParam('userId'), Params::getParam('code')));
                                        }

                                        $user = User::newInstance()->findByIdPasswordSecret(Params::getParam('userId'), Params::getParam('code'));
                                        if($user['b_enabled'] == 1) {
                                            if(Params::getParam('new_password', false, false)==Params::getParam('new_password2', false, false)) {
                                                User::newInstance()->update(
                                                    array('s_pass_code' => osc_genRandomPassword(50)
                                                        , 's_pass_date' => date('Y-m-d H:i:s', 0)
                                                        , 's_pass_ip' => $_SERVER['REMOTE_ADDR']
                                                        , 's_password' => sha1(Params::getParam('new_password', false, false))
                                                    ), array('pk_i_id' => $user['pk_i_id'])
                                                );
                                                osc_add_flash_ok_message( _m('The password has been changed'));
                                                $this->redirectTo(osc_user_login_url());
                                            } else {
                                                osc_add_flash_error_message( _m("Error, the password don't match"));
                                                $this->redirectTo(osc_forgot_user_password_confirm_url(Params::getParam('userId'), Params::getParam('code')));
                                            }
                                        } else {
                                            osc_add_flash_error_message( _m('Sorry, the link is not valid'));
                                        }
                                        $this->redirectTo( osc_base_url() );
                break;
                default:                //login
                                        Session::newInstance()->_setReferer(osc_get_http_referer());
                                        if( osc_logged_user_id() != '') {
                                            $this->redirectTo(osc_user_dashboard_url());
                                        }
                                        $this->doView( 'user-login.php' );
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_run_hook("before_html");
            osc_current_web_theme_path($file);
            osc_run_hook("after_html");
        }
    }

    /* file end: ./login.php */
?>