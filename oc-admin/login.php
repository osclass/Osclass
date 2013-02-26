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

    class CAdminLogin extends AdminBaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        //Business Layer...
        function doModel()
        {
            switch( $this->action ) {
                case('login_post'):     //post execution for the login
                                        osc_csrf_check();
                                        $url_redirect  = osc_get_http_referer();
                                        $page_redirect = '';
                                        if(preg_match('|[\?&]page=([^&]+)|', $url_redirect.'&', $match)) {
                                            $page_redirect = $match[1];
                                        }
                                        if($page_redirect=='' || $page_redirect=='login' || $url_redirect=='') {
                                            $url_redirect = osc_admin_base_url();
                                        }

                                        if( Params::getParam('user') == '' ) {
                                            osc_add_flash_error_message( _m('The username field is empty'), 'admin');
                                            $this->redirectTo( osc_admin_base_url(true)."?page=login" );
                                        }

                                        if( Params::getParam('password', false, false) == '' ) {
                                            osc_add_flash_error_message( _m('The password field is empty'), 'admin');
                                            $this->redirectTo( osc_admin_base_url(true)."?page=login" );
                                        }

                                        // fields are not empty
                                        $admin = Admin::newInstance()->findByUsername( Params::getParam('user') );

                                        if( !$admin ) {
                                            osc_add_flash_error_message( sprintf(_m('Sorry, incorrect username. <a href="%s">Have you lost your password?</a>'), osc_admin_base_url(true) . '?page=login&amp;action=recover' ), 'admin');
                                            $this->redirectTo( osc_admin_base_url(true)."?page=login" );
                                        }

                                        if( $admin["s_password"] !== sha1( Params::getParam('password', false, false) ) ) {
                                            osc_add_flash_error_message( sprintf(_m('Sorry, incorrect password. <a href="%s">Have you lost your password?</a>'), osc_admin_base_url(true) . '?page=login&amp;action=recover' ), 'admin');
                                            $this->redirectTo( osc_admin_base_url(true)."?page=login" );
                                        }

                                        if( Params::getParam('remember') ) {
                                            // this include contains de osc_genRandomPassword function
                                            require_once osc_lib_path() . 'osclass/helpers/hSecurity.php';
                                            $secret = osc_genRandomPassword();

                                            Admin::newInstance()->update(
                                                array('s_secret' => $secret),
                                                array('pk_i_id' => $admin['pk_i_id'])
                                            );

                                            Cookie::newInstance()->set_expires( osc_time_cookie() );
                                            Cookie::newInstance()->push('oc_adminId', $admin['pk_i_id']);
                                            Cookie::newInstance()->push('oc_adminSecret', $secret);
                                            Cookie::newInstance()->push('oc_adminLocale', Params::getParam('locale'));
                                            Cookie::newInstance()->set();
                                        }

                                        // we are logged in... let's go!
                                        Session::newInstance()->_set('adminId', $admin['pk_i_id']);
                                        Session::newInstance()->_set('adminUserName', $admin['s_username']);
                                        Session::newInstance()->_set('adminName', $admin['s_name']);
                                        Session::newInstance()->_set('adminEmail', $admin['s_email']);
                                        Session::newInstance()->_set('adminLocale', Params::getParam('locale'));

                                        osc_run_hook('login_admin', $admin);

                                        $this->redirectTo( $url_redirect );
                break;
                case('recover'):        // form to recover the password (in this case we have the form in /gui/)
                                        $this->doView('gui/recover.php');
                break;
                case('recover_post'):   if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                                            $this->redirectTo( osc_admin_base_url() );
                                        }
                                        osc_csrf_check();

                                        // post execution to recover the password
                                        $admin = Admin::newInstance()->findByEmail( Params::getParam('email') );
                                        if( $admin ) {
                                            if( (osc_recaptcha_private_key() != '') ) {
                                                if( !osc_check_recaptcha() ) {
                                                    osc_add_flash_error_message( _m('The reCAPTCHA code is wrong'), 'admin');
                                                    $this->redirectTo( osc_admin_base_url(true).'?page=login&action=recover' );
                                                    return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
                                                }
                                            }

                                            require_once osc_lib_path() . 'osclass/helpers/hSecurity.php';
                                            $newPassword = osc_genRandomPassword(40);

                                            Admin::newInstance()->update(
                                                array('s_secret' => $newPassword),
                                                array('pk_i_id' => $admin['pk_i_id'])
                                            );
                                            $password_url = osc_forgot_admin_password_confirm_url($admin['pk_i_id'], $newPassword);

                                            osc_run_hook('hook_email_user_forgot_password', $admin, $password_url);
                                        }

                                        osc_add_flash_ok_message( _m('A new password has been sent to your e-mail'), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=login');
                break;
                case('forgot'):         // form to recover the password (in this case we have the form in /gui/)
                                        $admin = Admin::newInstance()->findByIdSecret(Params::getParam('adminId'), Params::getParam('code'));
                                        if( !$admin ) {
                                            osc_add_flash_error_message( _m('Sorry, the link is not valid'), 'admin');
                                            $this->redirectTo( osc_admin_base_url() );
                                        }

                                        $this->doView( 'gui/forgot_password.php' );
                break;
                case('forgot_post'):
                                        osc_csrf_check();
                                        $admin = Admin::newInstance()->findByIdSecret(Params::getParam('adminId'), Params::getParam('code'));
                                        if( !$admin ) {
                                            osc_add_flash_error_message( _m('Sorry, the link is not valid'), 'admin');
                                            $this->redirectTo( osc_admin_base_url() );
                                        }

                                        if( Params::getParam('new_password', false, false) == Params::getParam('new_password2', false, false) ) {
                                            Admin::newInstance()->update(
                                                array('s_secret' => osc_genRandomPassword()
                                                    , 's_password' => sha1(Params::getParam('new_password', false, false))
                                                ), array('pk_i_id' => $admin['pk_i_id'])
                                            );
                                            osc_add_flash_ok_message( _m('The password has been changed'), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=login');
                                        } else {
                                            osc_add_flash_error_message( _m("Error, the passwords don't match"), 'admin');
                                            $this->redirectTo(osc_forgot_admin_password_confirm_url(Params::getParam('adminId'), Params::getParam('code')));
                                        }
                break;
                default:
                                        osc_run_hook( 'init_admin' );
                                        Session::newInstance()->_setReferer(osc_get_http_referer());
                                        $this->doView( 'gui/login.php' );
                break;
            }
        }

        //in this case, this function is prepared for the "recover your password" form
        function doView($file)
        {
            $login_admin_title = osc_apply_filter('login_admin_title', 'Osclass');
            $login_admin_url   = osc_apply_filter('login_admin_url', 'http://osclass.org/');
            $login_admin_image = osc_apply_filter('login_admin_image', osc_admin_base_url() . 'images/osclass-logo.gif');

            View::newInstance()->_exportVariableToView('login_admin_title', $login_admin_title);
            View::newInstance()->_exportVariableToView('login_admin_url', $login_admin_url);
            View::newInstance()->_exportVariableToView('login_admin_image', $login_admin_image);

            osc_run_hook("before_admin_html");
            require osc_admin_base_path() . $file;
            osc_run_hook("after_admin_html");

        }
    }

    /* file end: ./oc-admin/login.php */
