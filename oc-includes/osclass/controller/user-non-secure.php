<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    class CWebUserNonSecure extends BaseModel
    {
        function __construct()
        {
            parent::__construct();
            if( !osc_users_enabled() && ($this->action != 'activate_alert' && $this->action != 'unsub_alert') ) {
                osc_add_flash_error_message( _m('Users not enabled') );
                $this->redirectTo(osc_base_url());
            }
            osc_run_hook( 'init_user_non_secure' );
        }

        //Business Layer...
        function doModel()
        {
            switch( $this->action ) {
                case 'change_email_confirm':    //change email confirm
                                                if ( Params::getParam('userId') && Params::getParam('code') ) {
                                                    $userManager = new User();
                                                    $user = $userManager->findByPrimaryKey( Params::getParam('userId') );

                                                    if( $user['s_pass_code'] == Params::getParam('code') && $user['b_enabled']==1) {
                                                        $userOldEmail = $user['s_email'];
                                                        $userEmailTmp = UserEmailTmp::newInstance()->findByPrimaryKey( Params::getParam('userId') );
                                                        $code = osc_genRandomPassword(50);
                                                        $userManager->update(
                                                             array('s_email' => $userEmailTmp['s_new_email'])
                                                            ,array('pk_i_id' => $userEmailTmp['fk_i_user_id'])
                                                        );
                                                        Item::newInstance()->update(array('s_contact_email' => $userEmailTmp['s_new_email']), array('fk_i_user_id' => $userEmailTmp['fk_i_user_id']));
                                                        ItemComment::newInstance()->update(array('s_author_email' => $userEmailTmp['s_new_email']), array('fk_i_user_id' => $userEmailTmp['fk_i_user_id']));
                                                        Alerts::newInstance()->update(array('s_email' => $userEmailTmp['s_new_email']), array('fk_i_user_id' => $userEmailTmp['fk_i_user_id']));
                                                        Session::newInstance()->_set('userEmail', $userEmailTmp['s_new_email']);
                                                        UserEmailTmp::newInstance()->delete(array('s_new_email' => $userEmailTmp['s_new_email']));
                                                        
                                                        osc_run_hook('change_email_confirm', Params::getParam('userId'), $userOldEmail, $userEmailTmp['s_new_email']);
                                                        
                                                        osc_add_flash_ok_message( _m('Your email has been changed successfully'));
                                                        $this->redirectTo( osc_user_profile_url() );
                                                    } else {
                                                        osc_add_flash_error_message( _m('Sorry, the link is not valid'));
                                                        $this->redirectTo( osc_base_url() );
                                                    }
                                                } else {
                                                    osc_add_flash_error_message( _m('Sorry, the link is not valid'));
                                                    $this->redirectTo( osc_base_url() );
                                                }
                break;
                case 'activate_alert':
                    $email  = Params::getParam('email');
                    $secret = Params::getParam('secret');
                    $id     = Params::getParam('id');

                    $alert = Alerts::newInstance()->findByPrimaryKey($id);
                    $result = 0;
                    if(!empty($alert)) {
                        if($email==$alert['s_email'] && $secret==$alert['s_secret']) {
                            $user = User::newInstance()->findByEmail($alert['s_email']);
                            if(isset($user['pk_i_id'])) {
                                Alerts::newInstance()->update(array('fk_i_user_id' => $user['pk_i_id']), array('pk_i_id' => $id));
                            }
                            $result = Alerts::newInstance()->activate($id);
                        }
                    }

                    if( $result == 1 ) {
                        osc_add_flash_ok_message(_m('Alert activated'));
                    }else{
                        osc_add_flash_error_message(_m('Oops! There was a problem trying to activate your alert. Please contact an administrator'));
                    }

                    $this->redirectTo( osc_base_url() );
                break;
                case 'unsub_alert':
                    $email  = Params::getParam('email');
                    $secret = Params::getParam('secret');
                    $id     = Params::getParam('id');

                    $alert  = Alerts::newInstance()->findByPrimaryKey($id);
                    $result = 0;
                    if(!empty($alert)) {
                        if($email==$alert['s_email'] && $secret==$alert['s_secret']) {
                            $result = Alerts::newInstance()->unsub($id);
                        }
                    }

                    if( $result == 1 ) {
                        osc_add_flash_ok_message(_m('Unsubscribed correctly'));
                    }else{
                        osc_add_flash_error_message(_m('Oops! There was a problem trying to unsubscribe you. Please contact an administrator'));
                    }

                    $this->redirectTo(osc_base_url());
                break;
                case 'pub_profile':
                    if(Params::getParam('username')!='') {
                        $user = User::newInstance()->findByUsername(Params::getParam('username'));
                    } else {
                        $user = User::newInstance()->findByPrimaryKey(Params::getParam('id'));
                    }
                    // user doesn't exist, show 404 error
                    if( !$user ) {
                        $this->do404();
                        return;
                    }

                    $itemsPerPage = Params::getParam('itemsPerPage');
                    if(is_numeric($itemsPerPage) && intval($itemsPerPage)>0) {
                        $itemsPerPage = intval($itemsPerPage);
                    } else {
                        $itemsPerPage = 10;
                    }

                    $page = Params::getParam('iPage');
                    if(is_numeric($page) && intval($page)>0) {
                        $page = intval($page)-1;
                    } else {
                        $page = 0;
                    }

                    $total_items  = Item::newInstance()->countItemTypesByUserID($user['pk_i_id'], 'active');

                    if($itemsPerPage == 'all') {
                        $total_pages = 1;
                        $items = Item::newInstance()->findItemTypesByUserID($user['pk_i_id'], 0, null, 'active');
                    } else {
                        $total_pages  = ceil($total_items/$itemsPerPage);
                        $items = Item::newInstance()->findItemTypesByUserID($user['pk_i_id'], $page*$itemsPerPage, $itemsPerPage, 'active');
                    }

                    View::newInstance()->_exportVariableToView( 'user', $user );
                    $this->_exportVariableToView('items', $items);
                    $this->_exportVariableToView('search_total_pages', $total_pages);
                    $this->_exportVariableToView('search_total_items', $total_items);
                    $this->_exportVariableToView('items_per_page', $itemsPerPage);
                    $this->_exportVariableToView('search_page', $page);
                    $this->_exportVariableToView('canonical', osc_user_public_profile_url());

                    $this->doView('user-public-profile.php');
                break;
                case 'contact_post':
                    $user = User::newInstance()->findByPrimaryKey( Params::getParam('id') );
                    View::newInstance()->_exportVariableToView('user', $user);
                    if ((osc_recaptcha_private_key() != '')) {
                        if(!osc_check_recaptcha()) {
                            osc_add_flash_error_message( _m('The Recaptcha code is wrong'));
                            Session::newInstance()->_setForm("yourEmail",   Params::getParam('yourEmail'));
                            Session::newInstance()->_setForm("yourName",    Params::getParam('yourName'));
                            Session::newInstance()->_setForm("phoneNumber", Params::getParam('phoneNumber'));
                            Session::newInstance()->_setForm("message_body",Params::getParam('message'));
                            $this->redirectTo( osc_user_public_profile_url( ) );
                            return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
                        }
                    }
                    $banned = osc_is_banned(Params::getParam('yourEmail'));
                    if($banned==1) {
                        osc_add_flash_error_message( _m('Your current email is not allowed'));
                        $this->redirectTo(osc_user_public_profile_url());
                    } else if($banned==2) {
                        osc_add_flash_error_message( _m('Your current IP is not allowed'));
                        $this->redirectTo(osc_user_public_profile_url());
                    }

                    osc_run_hook('hook_email_contact_user', Params::getParam('id'), Params::getParam('yourEmail'), Params::getParam('yourName'), Params::getParam('phoneNumber'), Params::getParam('message'));
                    osc_add_flash_ok_message( _m('Your email has been sent properly.') );
                    $this->redirectTo( osc_user_public_profile_url( ) );
                break;
                default:
                    $this->redirectTo( osc_user_login_url() );
                break;
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_run_hook("before_html");
            osc_current_web_theme_path($file);
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_html");
        }
    }

    /* file end: ./user-non-secure.php */
?>
