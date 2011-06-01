<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class CWebRegister extends BaseModel
    {

        function __construct() {
            parent::__construct() ;
        }

        //Business Layer...
        function doModel() {
            switch( $this->action ) {
                case('register'):       //register user
                                        $this->doView('user-register.php') ;
                break;
                case('register_post'):  //register user
                                        require_once LIB_PATH . 'osclass/UserActions.php' ;
                                        $userActions = new UserActions(false) ;
                                        $success = $userActions->add() ;
                                        switch($success) {
                                            case 1: osc_add_flash_ok_message( _m('The user has been created. An activation email has been sent')) ;
                                                    $this->redirectTo( osc_base_url() ) ;
                                            break;
                                            case 2: osc_add_flash_ok_message( _m('Your account has been created successfully')) ;
                                                    $this->doView('user-login.php') ;
                                            break;
                                            case 3: osc_add_flash_error_message( _m('The specified e-mail is already in use')) ;
                                                    $this->doView('user-register.php') ;
                                            break;
                                            case 4: osc_add_flash_error_message( _m('The reCAPTCHA was not introduced correctly')) ;
                                                    $this->doView('user-register.php') ;
                                            break;
                                            case 5: osc_add_flash_error_message( _m('The email is not valid')) ;
                                                    $this->doView('user-register.php') ;
                                            break;
                                        }
                break;
                case('validate'):       //validate account
                                        $id = intval( Params::getParam('id') ) ;
                                        $code = Params::getParam('code') ;
                                        $userManager = new User() ;
                                        $user = $userManager->findByIdSecret($id, $code) ;
                                            
                                        if ($user) {
                                            if ($user['b_active']==0) {
                                                $userManager = new User() ;
                                                $userManager->update(
                                                        array('b_active' => '1')
                                                        ,array('pk_i_id' => $id, 's_secret' => $code)
                                                ) ;
                                                
                                                $pageManager = new Page() ;
                                                $locale = osc_current_user_locale() ;
                                                $aPage = $pageManager->findByInternalName('email_user_registration') ;
                                                $content = array() ;
                                                if(isset($aPage['locale'][$locale]['s_title'])) {
                                                    $content = $aPage['locale'][$locale] ;
                                                } else {
                                                    $content = current($aPage['locale']) ;
                                                }

                                                if (!is_null($content)) {
                                                    $words   = array();
                                                    $words[] = array('{USER_NAME}', '{USER_EMAIL}', '{WEB_TITLE}', '{WEB_URL}') ;
                                                    $words[] = array($user['s_name'], $user['s_email'], osc_page_title(), '<a href="' . osc_base_url() . '" >' . osc_base_url() . '</a>' ) ;
                                                    $title = osc_mailBeauty($content['s_title'], $words) ;
                                                    $body = osc_mailBeauty($content['s_text'], $words) ;

                                                    $emailParams = array(
                                                        'subject'  => $title
                                                        ,'to'       => $user['s_email']
                                                        ,'to_name'  => $user['s_name']
                                                        ,'body'     => $body
                                                        ,'alt_body' => $body
                                                    );
                                                    osc_sendMail($emailParams) ;
                                                }
                                                osc_run_hook('validate_user', $user) ;
                                                osc_add_flash_ok_message( _m('Your account has been validated')) ;
                                                // Auto-login
                                                Session::newInstance()->_set('userId', $user['pk_i_id']) ;
                                                Session::newInstance()->_set('userName', $user['s_name']) ;
                                                Session::newInstance()->_set('userEmail', $user['s_email']) ;
                                                $phone = ($user['s_phone_mobile']) ? $user['s_phone_mobile'] : $user['s_phone_land'];
                                                Session::newInstance()->_set('userPhone', $phone) ;
                                            } else {
                                                osc_add_flash_error_message( _m('Your account has already been activated')) ;
                                            }
                                        } else {
                                            osc_add_flash_error_message( _m('The link is not valid anymore. Sorry for the inconvenience!')) ;
                                        }
                                        $this->redirectTo( osc_base_url() ) ;
                break;
            }

        }

        //hopefully generic...
        function doView($file) {
            osc_run_hook("before_html");
            osc_current_web_theme_path($file) ;
            osc_run_hook("after_html");
        }
    }

?>