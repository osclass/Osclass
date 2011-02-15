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

    class CWebRegister extends BaseModel
    {

        function __construct() {
            parent::__construct() ;

            $this->add_css('style.css') ;
            $this->add_css('jquery-ui.css') ;
            $this->add_global_js('tiny_mce.js') ;
            $this->add_global_js('jquery-1.4.2.js') ;
            $this->add_global_js('jquery-ui-1.8.5.js') ;
            $this->add_js('jquery-extends.js') ;
            $this->add_js('global.js') ;
        }

        //Business Layer...
        function doModel() {
            switch( $this->action ) {
                case('register'):       //register user
                                        $this->doView('user-register.php') ;
                break;
                case('register_post'):  //register user
                                        require_once LIB_PATH . 'osclass/users.php' ;
                                        $userActions = new UserActions(false) ;
                                        $success = $userActions->add() ;
                                        switch($success) {
                                            case 1: osc_add_flash_message(__('The user has been created. An activation email has been sent to the user\'s email address')) ;
                                                    $this->redirectTo( osc_base_url() ) ;
                                            break;
                                            case 2: osc_add_flash_message(__('Your account has been created successfully')) ;
                                                    $this->doView('user-login.php') ;
                                            break;
                                            case 3: osc_add_flash_message(__('The specified e-mail is already in use')) ;
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
                                            if (!$user['b_enabled']) {
                                                $userManager = new User() ;
                                                $userManager->update(
                                                        array('b_enabled' => '1')
                                                        ,array('pk_i_id' => $id, 's_secret' => $code)
                                                ) ;
                                                
                                                $pageManager = new Page() ;
                                                $locale = osc_get_user_locale() ;
                                                $aPage = $pageManager->findByInternalName('email_user_registration') ;
                                                $content = array() ;
                                                if(isset($aPage['locale'][$locale]['s_title'])) {
                                                    $content = $aPage['locale'][$locale] ;
                                                } else {
                                                    $content = current($aPage['locale']) ;
                                                }

                                                if (!is_null($content)) {
                                                    $words   = array();
                                                    $words[] = array('{USER_NAME}', '{USER_EMAIL}', '{WEB_TITLE}') ;
                                                    $words[] = array($user['s_name'], $user['s_email'], osc_page_title()) ;
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
                                                osc_add_flash_message(__('Your account has been correctly validated. Thanks!')) ;
                                            } else {
                                                osc_add_flash_message(__('Your account has already been activated')) ;
                                            }
                                        } else {
                                            osc_add_flash_message(__('The link is not valid anymore. Sorry for the inconvenience!')) ;
                                        }
                                        $this->redirectTo( osc_base_url() ) ;
                break;
            }

        }

        //hopefully generic...
        function doView($file) {
            $this->osc_print_html($file) ;
        }
    }

?>