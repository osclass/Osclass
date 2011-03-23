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
                                    if ($user) {
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
                                        $code = osc_genRandomPassword(50);
                                        $date = date('Y-m-d H:i:s');
                                        $date2 = date('Y-m-d H:i:').'00';
                                        User::newInstance()->update(
                                            array('s_pass_code' => $code, 's_pass_date' => $date, 's_pass_ip' => $_SERVER['REMOTE_ADDR']),
                                            array('pk_i_id' => $user['pk_i_id'])
                                        );

                                        $password_link = osc_forgot_user_password_confirm_url($user['pk_i_id'], $code);
                                        
                                        $aPage = Page::newInstance()->findByInternalName('email_user_forgot_password');

                                        $content = array();
                                        if(isset($aPage['locale'][$locale]['s_title'])) {
                                            $content = $aPage['locale'][$locale];
                                        } else {
                                            $content = current($aPage['locale']);
                                        }

                                        if (!is_null($content)) {
                                            $words   = array();
                                            $words[] = array('{USER_NAME}', '{USER_EMAIL}', '{WEB_TITLE}', '{IP_ADDRESS}',
                                                             '{PASSWORD_LINK}', '{DATE_TIME}');
                                            $words[] = array($user['s_name'], $user['s_email'], $preferences['pageTitle'],
                                                             $_SERVER['REMOTE_ADDR'], $password_link, $date2);
                                            $title = osc_mailBeauty($content['s_title'], $words);
                                            $body = osc_mailBeauty($content['s_text'], $words);

                                            $emailParams = array('subject'  => $title,
                                                                 'to'       => $user['s_email'],
                                                                 'to_name'  => $user['s_name'],
                                                                 'body'     => $body,
                                                                 'alt_body' => $body);
                                            osc_sendMail($emailParams);
                                        }
                                    }
                                    // We ALWAYS show the same message, so we don't give clues about which emails are in our database and which don't!
                                    osc_add_flash_message( _m('If the email is in our database, we will send and email with instruction to reset your password')) ;
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
                                                    , 's_password' => Params::getParam('new_password')
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
                                    $this->doView( 'user-login.php' ) ;
        }
       
    }

    //hopefully generic...
    function doView($file) {
        osc_current_web_theme_path($file) ;
    }
}

?>
