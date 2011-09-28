<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
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

    class CAdminLogin extends BaseModel
    {

        function __construct() {
            parent::__construct() ;
        }

        //Business Layer...
        function doModel() {
            switch( $this->action ) {
                case('login_post'):     //post execution for the login
                                        $admin = Admin::newInstance()->findByUsername( Params::getParam('user') ) ;
                                        if ($admin) {
                                            if ( $admin["s_password"] == sha1( Params::getParam('password') ) ) {
                                                if ( Params::getParam('remember') ) {
                                                    //this include contains de osc_genRandomPassword function
                                                    require_once osc_lib_path() . 'osclass/helpers/hSecurity.php';
                                                    $secret = osc_genRandomPassword() ;

                                                    Admin::newInstance()->update(
                                                        array('s_secret' => $secret)
                                                        ,array('pk_i_id' => $admin['pk_i_id'])
                                                    );

                                                    Cookie::newInstance()->set_expires( osc_time_cookie() ) ;
                                                    Cookie::newInstance()->push('oc_adminId', $admin['pk_i_id']) ;
                                                    Cookie::newInstance()->push('oc_adminSecret', $secret) ;
                                                    Cookie::newInstance()->push('oc_adminLocale', Params::getParam('locale')) ;
                                                    Cookie::newInstance()->set() ;
                                                }

                                                //we are logged in... let's go!
                                                Session::newInstance()->_set('adminId', $admin['pk_i_id']) ;
                                                Session::newInstance()->_set('adminUserName', $admin['s_username']) ;
                                                Session::newInstance()->_set('adminName', $admin['s_name']) ;
                                                Session::newInstance()->_set('adminEmail', $admin['s_email']) ;
                                                Session::newInstance()->_set('adminLocale', Params::getParam('locale')) ;

                                            } else {
                                                osc_add_flash_error_message( _m('The password is incorrect'), 'admin') ;
                                            }

                                        } else {
                                            osc_add_flash_error_message( _m('That username does not exist'), 'admin') ;
                                        }

                                        //returning logged in to the main page...
                                        $this->redirectTo( osc_admin_base_url() ) ;
                break ;
                case('recover'):        //form to recover the password (in this case we have the form in /gui/)
                                        //#dev.conquer: we cannot use the doView here and only here
                                        $this->doView('gui/recover.php') ;
                break ;
                case('recover_post'):   if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action cannot be done because is a demo site"), 'admin');
                                            $this->redirectTo( osc_admin_base_url() );
                                        }
                                        //post execution to recover the password
                                        $admin = Admin::newInstance()->findByEmail( Params::getParam('email') ) ;
                                        if($admin) {
                                        
                                            if ((osc_recaptcha_private_key() != '') && Params::existParam("recaptcha_challenge_field")) {
                                                if(!osc_check_recaptcha()) {
                                                    osc_add_flash_error_message( _m('The Recaptcha code is wrong'), 'admin') ;
                                                    $this->redirectTo( osc_admin_base_url(true).'?page=login&action=recover' );
                                                    return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
                                                }
                                            }

                                            require_once osc_lib_path() . 'osclass/helpers/hSecurity.php' ;
                                            $newPassword = osc_genRandomPassword(40) ;

                                            Admin::newInstance()->update(
                                                array('s_secret' => $newPassword)
                                                ,array('pk_i_id' => $admin['pk_i_id'])
                                            );
                                            $password_url = osc_forgot_admin_password_confirm_url($admin['pk_i_id'], $newPassword);
                                            
                                            osc_run_hook('hook_email_user_forgot_password', $admin, $password_url);
                                        }
                                        
                                        osc_add_flash_ok_message( _m('A new password has been sent to your e-mail'), 'admin') ;
                                        $this->redirectTo( osc_admin_base_url() ) ;
                break ;

                case('forgot'):         //form to recover the password (in this case we have the form in /gui/)
                                        $admin = Admin::newInstance()->findByIdSecret(Params::getParam('adminId'), Params::getParam('code'));
                                        if($admin) {
                                            $this->doView( 'gui/forgot_password.php' ) ;
                                        } else {
                                            osc_add_flash_error_message( _m('Sorry, the link is not valid'), 'admin') ;
                                            $this->redirectTo( osc_admin_base_url() ) ;
                                        }
                break;
                case('forgot_post'):
                                        $admin = Admin::newInstance()->findByIdSecret(Params::getParam('adminId'), Params::getParam('code'));
                                        if($admin) {
                                            if(Params::getParam('new_password')==Params::getParam('new_password2')) {
                                                Admin::newInstance()->update(
                                                    array('s_secret' => osc_genRandomPassword()
                                                        , 's_password' => sha1(Params::getParam('new_password'))
                                                    ), array('pk_i_id' => $admin['pk_i_id'])
                                                );
                                                osc_add_flash_ok_message( _m('The password has been changed'), 'admin');
                                                $this->redirectTo(osc_admin_base_url());
                                            } else {
                                                osc_add_flash_error_message( _m('Error, the password don\'t match'), 'admin') ;
                                                $this->redirectTo(osc_forgot_admin_password_confirm_url(Params::getParam('adminId'), Params::getParam('code')));
                                            }
                                        } else {
                                            osc_add_flash_error_message( _m('Sorry, the link is not valid'), 'admin') ;
                                        }
                                        $this->redirectTo( osc_admin_base_url() ) ;
                break;
            
            }
        }

        //in this case, this function is prepared for the "recover your password" form
        function doView($file) {
            require osc_admin_base_path() . $file ;
        }
    }

?>