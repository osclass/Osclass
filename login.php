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
            case('login'):          //login
                                    $this->doView( 'user-login.php' ) ;
            break;
            case('login_post'):     //post execution for the login
                                    $user = User::newInstance()->findByEmail( Params::getParam('email') ) ;
                                    if ($user) {
                                        if ( $user["s_password"] == sha1( Params::getParam('password') ) ) {
                                            if ( Params::getParam('remember') ) {
                                                $life = time() + COOKIE_LIFE ;

                                                //this include contains de osc_genRandomPassword function
                                                require_once ABS_PATH . 'oc-includes/osclass/helpers/hSecurity.php';
                                                $secret = osc_genRandomPassword() ;

                                                User::newInstance()->update(
                                                    array('s_secret' => $secret)
                                                    ,array('pk_i_id' => $user['pk_i_id'])
                                                );

                                                //setcookie('oc_adminId', $admin['pk_i_id'], $life, '/', $_SERVER['SERVER_NAME']);
                                                //setcookie('oc_adminSecret', $secret, $life, '/', $_SERVER['SERVER_NAME']);
                                            } else {
                                                //setcookie('oc_adminId', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
                                                //setcookie('oc_adminSecret', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
                                            }

                                            //we are logged in... let's go!
                                            Session::newInstance()->_set('userId', $user['pk_i_id']) ;
                                            Session::newInstance()->_set('userName', $user['s_name']) ;
                                            Session::newInstance()->_set('userEmail', $user['s_email']) ;
                                            Session::newInstance()->_set('userLocale', Params::getParam('locale')) ;
                                            
                                        } else {
                                            osc_add_flash_message( _m('The password is incorrect')) ;
                                        }

                                    } else {
                                        osc_add_flash_message( _m('The username doesn\'t exist')) ;
                                    }

                                    //returning logged in to the main page...
                                    $this->redirectTo( osc_user_dashboard_url() ) ;
            break ;
            case('recover'):        //form to recover the password (in this case we have the form in /gui/)
                                    $this->doView( 'user-recover.php' ) ;
            break ;
            case('recover_post'):   //post execution to recover the password
                                    $user = User::newInstance()->findByEmail( Params::getParam('s_email') ) ;
                                    if($user) {
                                        require_once ABS_PATH . 'oc-includes/osclass/helpers/hSecurity.php' ;
                                        $newPassword = osc_genRandomPassword() ;
                                        $body = sprintf( __('Your new password is "%s"'), $newPassword) ;

                                        User::newInstance()->update(
                                            array('s_password' => sha1($newPassword))
                                            ,array('pk_i_id' => $user['pk_i_id'])
                                        );

                                        $params = array(
                                            'from_name' => __('OSClass application')
                                            ,'subject' => __('Recover your password')
                                            ,'to' => $user['s_email']
                                            ,'to_name' => $user['s_name']
                                            ,'body' => $body
                                            ,'alt_body' => $body
                                        );
                                        osc_sendMail($params) ;

                                        osc_add_flash_message( _m('A new password has been sent to your account')) ;
                                    } else {
                                        osc_add_flash_message( _m('The email isn\'t associated to a valid user. Please, try again')) ;
                                        $this->redirectTo( osc_base_url(true) . '?page=login&action=recover') ;
                                    }

                                    $this->redirectTo( osc_base_url() ) ;
            break ;
        }
       
    }

    //hopefully generic...
    function doView($file) {
        osc_current_web_theme_path($file) ;
    }
}

?>
