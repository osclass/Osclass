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

class CWebUserNonSecure extends BaseModel
{

    function __construct() {
        parent::__construct() ;
    }

    //Business Layer...
    function doModel() {
        switch( $this->action ) {
            case 'change_email_confirm':    //change email confirm
                                            if ( Params::getParam('userId') && Params::getParam('code') ) {

                                                $userManager = new User() ;
                                                $user = $userManager->findByPrimaryKey( Params::getParam('userId') ) ;

                                                if( $user['s_pass_code'] == Params::getParam('code') ) {
                                                    $userEmailTmp = UserEmailTmp::newInstance()->findByPk( Params::getParam('userId') ) ;
                                                    $code = osc_genRandomPassword(50) ;
                                                    $userManager->update(
                                                         array('s_email' => $userEmailTmp['s_new_email'])
                                                        ,array('pk_i_id' => $userEmailTmp['fk_i_user_id'])
                                                    );
                                                    Session::newInstance()->_set('userEmail', $userEmailTmp['s_new_email']) ;
                                                    UserEmailTmp::newInstance()->delete(array('s_new_email' => $userEmailTmp['s_new_email']));
                                                    osc_add_flash_message( _m('Your email has been changed successfully'));
                                                    $this->redirectTo( osc_user_profile_url() ) ;
                                                } else {
                                                    osc_add_flash_message( _m('Sorry, the link is not valid'));
                                                    $this->redirectTo( osc_base_url() ) ;
                                                }
                                            } else {
                                                osc_add_flash_message( _m('Sorry, the link is not valid'));
                                                $this->redirectTo( osc_base_url() ) ;
                                            }
            break;
            
            case 'unsub_alert':
                $email = Params::getParam('email');
                $alert = Params::getParam('alert');
                if($email!='' && $alert!='') {
                    Alerts::newInstance()->delete(array('s_email' => $email, 's_search' => $alert));
                    osc_add_flash_message(__('Unsubscribed correctly.'));
                } else {
                    osc_add_flash_message(__('Ops! There was a problem trying to unsubscribe you. Please contact the administrator.'));
                }
                $this->redirectTo(osc_base_url());
            break;
            
            case 'forgot':
            
            break;
            
            case 'forgot_post':
            
            break;
            
            default:
                $this->redirectTo( osc_user_login_url() );
            break;
        }
    }

    //hopefully generic...
    function doView($file) {
        osc_current_web_theme_path($file) ;
    }
}



?>
