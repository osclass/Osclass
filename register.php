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
                                        $userManager = new User() ;

                                        $success = 0;
                                        $input['s_name'] = Params::getParam('s_name') ;
                                        $input['s_email'] = Params::getParam('s_email') ;
                                        $input['s_password'] = sha1( Params::getParam('s_password') ) ;
                                        $input['dt_reg_date'] = DB_FUNC_NOW ;
                                        $code = osc_genRandomPassword() ;
                                        $input['s_secret'] = $code ;

                                        $email_taken = $userManager->findByEmail( $input['s_email'] ) ;
                                        if($email_taken == null)
                                        {
                                            $userManager->insert($input) ;
                                            $userId = $userManager->getConnection()->get_last_id() ;

                                            /*
                                            //for multilanguage descriptions
                                            $data = array();
                                            foreach ($_REQUEST as $k => $v) {
                                                if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                                                    $data[$m[1]][$m[2]] = $v;
                                                }
                                            }
                                            foreach ($data as $k => $_data) {
                                                $manager->updateDescription($userId, $k, $_data['s_info']);
                                            }*/

                                            osc_run_hook('user_register_completed') ;

                                            if( osc_user_validation_enabled() )
                                            {
                                                $PageManager = new Page() ;
                                                $locale = osc_get_user_locale() ;
                                                $aPage = $PageManager->findByInternalName('email_user_validation') ;

                                                $content = array() ;
                                                if(isset($aPage['locale'][$locale]['s_title'])) {
                                                    $content = $aPage['locale'][$locale] ;
                                                } else {
                                                    $content = current($aPage['locale']) ;
                                                }

                                                if (!is_null($content)) {
                                                    $validationLink = sprintf(
                                                                        '%s?page=register&action=validate&id=%d&code=%s'
                                                                        ,osc_base_url(true)
                                                                        ,$userId
                                                                        ,$code
                                                    ) ;

                                                    $words   = array() ;
                                                    $words[] = array('{USER_NAME}', '{USER_EMAIL}', '{WEB_URL}', '{VALIDATION_LINK}') ;
                                                    $words[] = array($input['s_name'], $input['s_email'], osc_base_url(), $validationLink) ;
                                                    $title = osc_mailBeauty($content['s_title'], $words) ;
                                                    $body = osc_mailBeauty($content['s_text'], $words) ;

                                                    $emailParams = array(
                                                                        'subject'  => $title
                                                                        ,'to'       => $_POST['s_email']
                                                                        ,'to_name'  => $_POST['s_name']
                                                                        ,'body'     => $body
                                                                        ,'alt_body' => $body
                                                    ) ;

                                                    osc_sendMail($emailParams) ;
                                                }

                                                osc_add_flash_message('The account must be validated. An e-mail has been sent to your e-mail') ;
                                                $this->redirectTo( osc_base_url() ) ;
                                            } else {
                                                User::newInstance()->update(
                                                                        array('b_enabled' => '1')
                                                                        ,array('pk_i_id' => $userId)
                                                ) ;

                                                osc_add_flash_message(__('Your account has been created successfully. Log in!')) ;
                                                $this->doView('user-login.php') ;
                                            }
                                        } else {
                                            osc_add_flash_message(__('The specified e-mail is taken. Did you forget your password?')) ;
                                            $this->doView('user-register.php') ;
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
                                        osc_redirectTo('index.php') ;
                break;
            }

        }

        //hopefully generic...
        function doView($file) {
            $this->osc_print_html($file) ;
        }
    }

?>