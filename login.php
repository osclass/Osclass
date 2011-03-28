<?php

    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
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

    function __construct() {
        parent::__construct() ;
    }

    //Business Layer...
    function doModel() {
        switch( $this->action ) {
            case('login_post'):     //post execution for the login
                                    $user = User::newInstance()->findByEmail( Params::getParam('email') ) ;
                                    if (!$user) {
                                        osc_add_flash_message(_m('The username doesn\'t exist')) ;
                                        $this->redirectTo(osc_user_login_url());
                                    }

                                    if(!$user['b_enabled']) {
                                        osc_add_flash_message(_m('The user has not been validated yet'));
                                        $this->redirectTo(osc_user_login_url());
                                    }

                                    if ( $user["s_password"] == sha1( Params::getParam('password') ) ) {
                                        if ( Params::getParam('remember') == 1 ) {

                                            //this include contains de osc_genRandomPassword function
                                            require_once ABS_PATH . 'oc-includes/osclass/helpers/hSecurity.php';
                                            $secret = osc_genRandomPassword() ;

                                            User::newInstance()->update(
                                                array('s_secret' => $secret)
                                                ,array('pk_i_id' => $user['pk_i_id'])
                                            );

                                            Cookie::newInstance()->set_expires( osc_time_cookie() ) ;
                                            Cookie::newInstance()->push('oc_userId', $user['pk_i_id']) ;
                                            Cookie::newInstance()->push('oc_userSecret', $secret) ;
                                            Cookie::newInstance()->set() ;
                                        }

                                        //we are logged in... let's go!
                                        Session::newInstance()->_set('userId', $user['pk_i_id']) ;
                                        Session::newInstance()->_set('userName', $user['s_name']) ;
                                        Session::newInstance()->_set('userEmail', $user['s_email']) ;
                                        $phone = ($user['s_phone_mobile']) ? $user['s_phone_mobile'] : $user['s_phone_land'];
                                        Session::newInstance()->_set('userPhone', $phone) ;

                                    } else {
                                        osc_add_flash_message( _m('The password is incorrect')) ;
                                    }

                                    //returning logged in to the main page...
                                    $this->redirectTo( osc_user_dashboard_url() ) ;
            break ;
            case('recover'):        //form to recover the password (in this case we have the form in /gui/)
                                    $this->doView( 'user-recover.php' ) ;
            break ;
            case('recover_post'):   //post execution to recover the password
                                    require_once LIB_PATH . 'osclass/UserActions.php' ;
                                    $userActions = new UserActions(false) ;
                                    $userActions->recover_password() ;
                                    // We ALWAYS show the same message, so we don't give clues about which emails are in our database and which don't!
                                    osc_add_flash_message( _m('We have sent you an email with the instructions to reset your password')) ;
                                    $this->redirectTo( osc_base_url() ) ;
            break ;
            
            case('forgot'):         //form to recover the password (in this case we have the form in /gui/)
                                    $user = User::newInstance()->findByIdPasswordSecret(Params::getParam('userId'), Params::getParam('code'));
                                    if($user) {
                                        $this->doView( 'user-forgot_password.php' ) ;
                                    } else {
                                        osc_add_flash_message( _m('Sorry, the link is not valid')) ;
                                        $this->redirectTo( osc_base_url() ) ;
                                    }
            break;
            case('forgot_post'):
                                    $user = User::newInstance()->findByIdPasswordSecret(Params::getParam('userId'), Params::getParam('code'));
                                    if($user) {
                                        if(Params::getParam('new_password')==Params::getParam('new_password2')) {
                                            User::newInstance()->update(
                                                array('s_pass_code' => osc_genRandomPassword(50)
                                                    , 's_pass_date' => date('Y-m-d H:i:s', 0)
                                                    , 's_pass_ip' => $_SERVER['REMOTE_ADDR']
                                                    , 's_password' => sha1(Params::getParam('new_password'))
                                                ), array('pk_i_id' => $user['pk_i_id'])
                                            );
                                            osc_add_flash_message( _m('The password has been changed'));
                                            $this->redirectTo(osc_user_login_url());
                                        } else {
                                            osc_add_flash_message( _m('Error, the password don\'t match')) ;
                                            $this->redirectTo(osc_forgot_user_password_confirm_url(Params::getParam('userId'), Params::getParam('code')));
                                        }
                                    } else {
                                        osc_add_flash_message( _m('Sorry, the link is not valid')) ;
                                    }
                                    $this->redirectTo( osc_base_url() ) ;
            break;
            
            default:                //login
                                    if( osc_logged_user_id() != '') {
                                        $this->redirectTo(osc_user_dashboard_url());
                                    }
                                    $this->doView( 'user-login.php' ) ;
        }
       
    }

    //hopefully generic...
    function doView($file) {
        osc_current_web_theme_path($file) ;
    }
}

?>
