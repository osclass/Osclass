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
                                                $life = time() + COOKIE_LIFE ;

                                                //this include contains de osc_genRandomPassword function
                                                require_once ABS_PATH . 'oc-includes/osclass/helpers/hSecurity.php';
                                                $secret = osc_genRandomPassword() ;

                                                Admin::newInstance()->update(
                                                    array('s_secret' => $secret)
                                                    ,array('pk_i_id' => $admin['pk_i_id'])
                                                );

                                                //setcookie('oc_adminId', $admin['pk_i_id'], $life, '/', $_SERVER['SERVER_NAME']);
                                                //setcookie('oc_adminSecret', $secret, $life, '/', $_SERVER['SERVER_NAME']);
                                            } else {
                                                //setcookie('oc_adminId', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
                                                //setcookie('oc_adminSecret', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
                                            }

                                            //we are logged in... let's go!
                                            Session::newInstance()->_set('adminId', $admin['pk_i_id']) ;
                                            Session::newInstance()->_set('adminUserName', $admin['s_username']) ;
                                            Session::newInstance()->_set('adminName', $admin['s_name']) ;
                                            Session::newInstance()->_set('adminEmail', $admin['s_email']) ;
                                            //Session::newInstance()->_set('adminTheme', Params::getParam('theme')) ;
                                            Session::newInstance()->_set('adminLocale', Params::getParam('locale')) ;

                                        } else {
                                            osc_add_flash_message(__('The password is incorrect')) ;
                                        }

                                    } else {
                                        osc_add_flash_message(__('That username does not exist')) ;
                                    }

                                    //returning logged in to the main page...
                                    $this->redirectTo( osc_admin_base_url() ) ;
            break ;
            case('recover'):        //form to recover the password (in this case we have the form in /gui/)
                                    //#dev.conquer: we cannot use the doView here and only here
                                    $this->doView('gui/recover.php') ;
            break ;
            case('recover_post'):   //post execution to recover the password
                                    $admin = Admin::newInstance()->findByEmail( Params::getParam('email') ) ;
                                    if($admin) {
                                        require_once ABS_PATH . 'oc-includes/osclass/helpers/hSecurity.php' ;
                                        $newPassword = osc_genRandomPassword() ;
                                        $body = sprintf( __('Your new password is "%s"'), $newPassword) ;

                                        Admin::newInstance()->update(
                                            array('s_password' => sha1($newPassword))
                                            ,array('pk_i_id' => $admin['pk_i_id'])
                                        );

                                        $params = array(
                                            'from_name' => __('OSClass application')
                                            ,'subject' => __('Recover your password')
                                            ,'to' => $admin['s_email']
                                            ,'to_name' => __('OSClass administrator')
                                            ,'body' => $body
                                            ,'alt_body' => $body
                                        );
                                        osc_sendMail($params) ;

                                        osc_add_flash_message(__('A new password has been sent to your e-mail.')) ;
                                    } else {
                                        osc_add_flash_message(__('The email you have entered does not belong to a valid administrator.')) ;
                                        $this->redirectTo( osc_admin_base_url(true) . '?page=login&action=recover') ;
                                    }

                                    $this->redirectTo( osc_admin_base_url() ) ;
            break ;
        }
       
    }

    //in this case, this function is prepared for the "recover your password" form
    function doView($file) {
        require osc_admin_base_path() . $file ;
    }
}

?>