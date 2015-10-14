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

    class CWebUser extends WebSecBaseModel
    {
        function __construct()
        {
            parent::__construct();
            if( !osc_users_enabled() ) {
                osc_add_flash_error_message( _m('Users not enabled') );
                $this->redirectTo(osc_base_url());
            }
        }

        //Business Layer...
        function doModel()
        {
            switch( $this->action ) {
                case('dashboard'):      //dashboard...
                                        $max_items = (Params::getParam('max_items')!='')?Params::getParam('max_items'):5;
                                        $aItems = Item::newInstance()->findByUserIDEnabled(osc_logged_user_id(), 0, $max_items);
                                        //calling the view...
                                        $this->_exportVariableToView('items', $aItems);
                                        $this->_exportVariableToView('max_items', $max_items);
                                        $this->doView('user-dashboard.php');
                break;
                case('profile'):        //profile...
                                        $user = User::newInstance()->findByPrimaryKey( osc_logged_user_id() );
                                        $aCountries = Country::newInstance()->listAll();
                                        $aRegions = array();
                                        if( $user['fk_c_country_code'] != '' ) {
                                            $aRegions = Region::newInstance()->findByCountry( $user['fk_c_country_code'] );
                                        } elseif( count($aCountries) > 0 ) {
                                            $aRegions = Region::newInstance()->findByCountry( $aCountries[0]['pk_c_code'] );
                                        }
                                        $aCities = array();
                                        if( $user['fk_i_region_id'] != '' ) {
                                            $aCities = City::newInstance()->findByRegion($user['fk_i_region_id']);
                                        } else if( count($aRegions) > 0 ) {
                                            $aCities = City::newInstance()->findByRegion($aRegions[0]['pk_i_id']);
                                        }

                                        //calling the view...
                                        $this->_exportVariableToView('countries', $aCountries);
                                        $this->_exportVariableToView('regions', $aRegions);
                                        $this->_exportVariableToView('cities', $aCities);
                                        $this->_exportVariableToView('user', $user);
                                        $this->_exportVariableToView('locales', OSCLocale::newInstance()->listAllEnabled() );

                                        $this->doView('user-profile.php');
                break;
                case('profile_post'):   //profile post...
                                        osc_csrf_check();
                                        $userId = Session::newInstance()->_get('userId');

                                        require_once LIB_PATH . 'osclass/UserActions.php';
                                        $userActions = new UserActions(false);
                                        $success = $userActions->edit( $userId );
                                        if($success==1 || $success==2) {
                                            osc_add_flash_ok_message( _m('Your profile has been updated successfully') );
                                        } else {
                                            osc_add_flash_error_message( $success);
                                        }
                                        $this->redirectTo( osc_user_profile_url() );
                break;
                case('alerts'):         //alerts
                                        $aAlerts = Alerts::newInstance()->findByUser( Session::newInstance()->_get('userId'), false );
                                        $user = User::newInstance()->findByPrimaryKey( Session::newInstance()->_get('userId'));
                                        foreach($aAlerts as $k => $a) {
                                            $array_conditions   = (array)json_decode($a['s_search']);

//                                            $search = Search::newInstance();
                                            $search = new Search();
                                            $search->setJsonAlert($array_conditions);
                                            $search->limit(0, 3);

                                            $aAlerts[$k]['items'] = $search->doSearch();
                                        }

                                        $this->_exportVariableToView('alerts', $aAlerts);
                                        View::newInstance()->_reset('alerts');
                                        $this->_exportVariableToView('user', $user);
                                        $this->doView('user-alerts.php');
                break;
                case('change_email'):           //change email
                                                $this->doView('user-change_email.php');
                break;
                case('change_email_post'):      //change email post
                                                osc_csrf_check();
                                                if(!osc_validate_email(Params::getParam('new_email'))) {
                                                    osc_add_flash_error_message( _m('The specified e-mail is not valid'));
                                                    $this->redirectTo( osc_change_user_email_url() );
                                                } else {
                                                    $user = User::newInstance()->findByEmail(Params::getParam('new_email'));
                                                    if(!isset($user['pk_i_id'])) {
                                                        $userEmailTmp = array();
                                                        $userEmailTmp['fk_i_user_id'] = Session::newInstance()->_get('userId');
                                                        $userEmailTmp['s_new_email'] = Params::getParam('new_email');

                                                        UserEmailTmp::newInstance()->insertOrUpdate($userEmailTmp);

                                                        $code = osc_genRandomPassword(30);
                                                        $date = date('Y-m-d H:i:s');

                                                        $userManager = new User();
                                                        $userManager->update (
                                                            array( 's_pass_code' => $code, 's_pass_date' => $date, 's_pass_ip' => Params::getServerParam('REMOTE_ADDR') )
                                                            ,array( 'pk_i_id' => Session::newInstance()->_get('userId') )
                                                        );

                                                        $validation_url = osc_change_user_email_confirm_url( Session::newInstance()->_get('userId'), $code );
                                                        osc_run_hook('hook_email_new_email', Params::getParam('new_email'), $validation_url);
                                                        $this->redirectTo( osc_user_profile_url() );
                                                    } else {
                                                        osc_add_flash_error_message( _m('The specified e-mail is already in use'));
                                                        $this->redirectTo( osc_change_user_email_url() );
                                                    }
                                                }
                break;
                case('change_username'):        //change username
                                                $this->doView('user-change_username.php');
                break;
                case('change_username_post'):   //change username
                                                $username = osc_sanitize_username(Params::getParam('s_username'));
                                                osc_run_hook('before_username_change', Session::newInstance()->_get('userId'), $username);
                                                if($username!='') {
                                                    $user = User::newInstance()->findByUsername($username);
                                                    if(isset($user['s_username'])) {
                                                        osc_add_flash_error_message(_m('The specified username is already in use'));
                                                    } else {
                                                        if(!osc_is_username_blacklisted($username)) {
                                                            User::newInstance()->update(
                                                                     array('s_username' => $username)
                                                                    ,array('pk_i_id' => Session::newInstance()->_get('userId')));
                                                            osc_add_flash_ok_message(_m('The username was updated'));
                                                            osc_run_hook('after_username_change', Session::newInstance()->_get('userId'), Params::getParam('s_username'));
                                                            $this->redirectTo(osc_user_profile_url());
                                                        } else {
                                                            osc_add_flash_error_message(_m('The specified username is not valid, it contains some invalid words'));
                                                        }
                                                    }
                                                } else {
                                                    osc_add_flash_error_message(_m('The specified username could not be empty'));
                                                }
                                                $this->redirectTo( osc_change_user_username_url() );
                break;
                case('change_password'):        //change password
                                                $this->doView('user-change_password.php');
                break;
                case 'change_password_post':    //change password post
                                                osc_csrf_check();
                                                $user = User::newInstance()->findByPrimaryKey( Session::newInstance()->_get('userId') );

                                                if( (Params::getParam('password', false, false) == '') || (Params::getParam('new_password', false, false) == '') || (Params::getParam('new_password2', false, false) == '') ) {
                                                    osc_add_flash_warning_message( _m('Password cannot be blank') );
                                                    $this->redirectTo( osc_change_user_password_url() );
                                                }

                                                if(!osc_verify_password(Params::getParam('password', false, false), $user['s_password'])) {
                                                    osc_add_flash_error_message( _m("Current password doesn't match") );
                                                    $this->redirectTo( osc_change_user_password_url() );
                                                }

                                                if( !Params::getParam('new_password', false, false) ) {
                                                    osc_add_flash_error_message( _m("Passwords can't be empty") );
                                                    $this->redirectTo( osc_change_user_password_url() );
                                                }

                                                if( Params::getParam('new_password', false, false) != Params::getParam('new_password2', false, false) ) {
                                                    osc_add_flash_error_message( _m("Passwords don't match") );
                                                    $this->redirectTo( osc_change_user_password_url() );
                                                }

                                                User::newInstance()->update(array( 's_password' => osc_hash_password(Params::getParam ('new_password', false, false)))
                                                                           ,array( 'pk_i_id' => Session::newInstance()->_get('userId') ) );

                                                osc_add_flash_ok_message( _m('Password has been changed') );
                                                $this->redirectTo( osc_user_profile_url() );
                break;
                case 'items':                   // view items user
                                                $itemsPerPage = (Params::getParam('itemsPerPage')!='')?Params::getParam('itemsPerPage'):10;
                                                $page         = (Params::getParam('iPage') > 0) ? Params::getParam('iPage') -1 : 0;
                                                $itemType     = Params::getParam('itemType');
                                                $total_items  = Item::newInstance()->countItemTypesByUserID(osc_logged_user_id(), $itemType);
                                                $total_pages  = ceil($total_items/$itemsPerPage);
                                                $items        = Item::newInstance()->findItemTypesByUserID(osc_logged_user_id(), $page*$itemsPerPage, $itemsPerPage, $itemType);

                                                $this->_exportVariableToView('items', $items);
                                                $this->_exportVariableToView('search_total_pages', $total_pages);
                                                $this->_exportVariableToView('search_total_items', $total_items);
                                                $this->_exportVariableToView('items_per_page', $itemsPerPage);
                                                $this->_exportVariableToView('items_type', $itemType);
                                                $this->_exportVariableToView('search_page', $page);

                                                $this->doView('user-items.php');
                break;
                case 'activate_alert':
                    $email  = Params::getParam('email');
                    $secret = Params::getParam('secret');

                    $result = 0;
                    if($email!='' && $secret!='') {
                        $result = Alerts::newInstance()->activate($email, $secret );
                    }

                    if( $result == 1 ) {
                        osc_add_flash_ok_message(_m('Alert activated'));
                    }else{
                        osc_add_flash_error_message(_m('Oops! There was a problem trying to activate your alert. Please contact an administrator'));
                    }

                    $this->redirectTo( osc_base_url() );
                break;
                case 'unsub_alert':
                    $email = Params::getParam('email');
                    $secret = Params::getParam('secret');
                    $id     = Params::getParam('id');

                    $alert = Alerts::newInstance()->findByPrimaryKey($id);
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

                    $this->redirectTo(osc_user_alerts_url());
                break;
                case 'delete':
                    $id     = Params::getParam('id');
                    $secret = Params::getParam('secret');
                    if(osc_is_web_user_logged_in()) {
                        $user = User::newInstance()->findByPrimaryKey(osc_logged_user_id());
                        View::newInstance()->_exportVariableToView('user', $user);
                        if(!empty($user) && osc_logged_user_id()==$id && $secret==$user['s_secret']) {
                            User::newInstance()->deleteUser(osc_logged_user_id());

                            Session::newInstance()->_drop('userId');
                            Session::newInstance()->_drop('userName');
                            Session::newInstance()->_drop('userEmail');
                            Session::newInstance()->_drop('userPhone');

                            Cookie::newInstance()->pop('oc_userId');
                            Cookie::newInstance()->pop('oc_userSecret');
                            Cookie::newInstance()->set();

                            osc_add_flash_ok_message(_m("Your account have been deleted"));
                            $this->redirectTo( osc_base_url() );
                        } else {
                            osc_add_flash_error_message(_m("Oops! you can not do that"));
                            $this->redirectTo(osc_user_dashboard_url() );
                        }
                    } else {
                        osc_add_flash_error_message(_m("Oops! you can not do that"));
                        $this->redirectTo(osc_base_url() );
                    }
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

    /* file end: ./user.php */
?>