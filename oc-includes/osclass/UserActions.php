<?php if ( !defined('ABS_PATH') ) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    Class UserActions
    {
        var $is_admin;
        var $manager;

        function __construct($is_admin)
        {
            $this->is_admin = $is_admin;
            $this->manager  = User::newInstance();
        }

        //add...
        function add()
        {
            $error = array();
            $flash_error = '';
            if( (osc_recaptcha_private_key() != '') && !$this->is_admin ) {
                if( !osc_check_recaptcha() ) {
                    $flash_error .= _m('The reCAPTCHA was not entered correctly') . PHP_EOL;
                    $error[] = 4;
                }
            }

            if( Params::getParam('s_password', false, false) == '' ) {
                $flash_error .= _m('The password cannot be empty') . PHP_EOL;
                $error[] = 6;
            }

            if( Params::getParam('s_password', false, false) != Params::getParam('s_password2', false, false) ) {
                $flash_error .= _m("Passwords don't match") . PHP_EOL;
                $error[] = 7;
            }

            $input = $this->prepareData(true);

            if( $input['s_name']=='' ) {
                $flash_error .= _m('The name cannot be empty') . PHP_EOL;
                $error[] = 10;
            }

            if( !osc_validate_email($input['s_email']) ) {
                $flash_error .= _m('The email is not valid') . PHP_EOL;
                $error[] = 5;
            }

            $email_taken = $this->manager->findByEmail($input['s_email']);
            if( $email_taken != false ) {
                osc_run_hook('register_email_taken', $input['s_email']);
                $flash_error .= _m('The specified e-mail is already in use') . PHP_EOL;
                $error[] = 3;
            }

            if($input['s_username']!='') {
                $username_taken = $this->manager->findByUsername($input['s_username']);
                if( !$error && $username_taken != false ) {
                    $flash_error .= _m("Username is already taken") . PHP_EOL;
                    $error[] = 8;
                }
                if(osc_is_username_blacklisted($input['s_username'])) {
                    $flash_error .= _m("The specified username is not valid, it contains some invalid words") . PHP_EOL;
                    $error[] = 9;
                }
            }

            $flash_error = osc_apply_filter('user_add_flash_error', $flash_error);
            if($flash_error!='') {
                osc_run_hook('user_register_failed', $error);
                return $flash_error;
            }

            // hook pre add or edit
            osc_run_hook('pre_user_post');


            $this->manager->insert($input);
            $userId = $this->manager->dao->insertedId();

            if($input['s_username']=='') {
                $this->manager->update(
                                 array('s_username' => $userId)
                                ,array('pk_i_id'  => $userId)
                );
            }

            if ( is_array( Params::getParam('s_info') ) ) {
                foreach (Params::getParam('s_info') as $key => $value) {
                    $this->manager->updateDescription($userId, $key, $value);
                }
            }

            Log::newInstance()->insertLog('user', 'add', $userId, $input['s_email'], $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : $userId);

            // update items with s_contact_email the same as new user email
            $aItems = Item::newInstance()->findByEmail( $input['s_email'] );
            foreach( $aItems as $aux ) {
                if( Item::newInstance()->update(array('fk_i_user_id' => $userId, 's_contact_name' => $input['s_name']), array('pk_i_id' => $aux['pk_i_id']) ) ) {
                    $this->manager->increaseNumItems($userId);
                }
            }
            // update alerts user id with the same email
            Alerts::newInstance()->update(array('fk_i_user_id' => $userId), array('s_email' => $input['s_email']));

            $user = $this->manager->findByPrimaryKey($userId);

            if( osc_notify_new_user() && !$this->is_admin ) {
                osc_run_hook('hook_email_admin_new_user', $user);
            }

            if( osc_user_validation_enabled() && !$this->is_admin ) {
                osc_run_hook('hook_email_user_validation', $user, $input);
                $success = 1;
            } else {
                $this->manager->update(
                                 array('b_active' => '1')
                                ,array('pk_i_id'  => $userId)
                );
                $success = 2;
            }

            osc_run_hook('user_register_completed', $userId);
            return $success;
        }

        //edit...
        function edit($userId)
        {

            $input = $this->prepareData(false);

            // hook pre add or edit
            osc_run_hook('pre_user_post');
            $flash_error = '';
            $error = array();
            if($this->is_admin) {
                $user_email = $this->manager->findByEmail($input['s_email']);
                if(isset($user_email['pk_i_id']) && $user_email['pk_i_id']!=$userId) {
                    $flash_error .= sprintf(_m('The specified e-mail is already used by %s') , $user_email['s_username']) . PHP_EOL;
                    $error[] = 3;
                }
            }

            if($input['s_name']=='') {
                $flash_error .= _m('The name cannot be empty').PHP_EOL;
                $error[] = 10;
            }

            if($this->is_admin){
                if( Params::getParam('s_password', false, false) != Params::getParam('s_password2', false, false) ) {
                    $flash_error .= _m("Passwords don't match") . PHP_EOL;
                    $error[] = 7;
                }
            }

            $flash_error = osc_apply_filter('user_edit_flash_error', $flash_error, $userId);
            if($flash_error!='') {
                return $flash_error;
            }

            $this->manager->update($input, array('pk_i_id' => $userId));

            if($this->is_admin) {
                Item::newInstance()->update( array('s_contact_name' => $input['s_name'], 's_contact_email' => $input['s_email']), array('fk_i_user_id' => $userId) );
                ItemComment::newInstance()->update( array('s_author_name' => $input['s_name'], 's_author_email' => $input['s_email']), array('fk_i_user_id' => $userId) );
                Alerts::newInstance()->update( array('s_email' => $input['s_email']), array('fk_i_user_id' => $userId) );

                Log::newInstance()->insertLog( 'user', 'edit', $userId, $input['s_email'], $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : osc_logged_user_id() );
            } else {
                Item::newInstance()->update( array('s_contact_name' => $input['s_name']), array('fk_i_user_id' => $userId) );
                ItemComment::newInstance()->update( array('s_author_name' => $input['s_name']), array('fk_i_user_id' => $userId) );
                $user = $this->manager->findByPrimaryKey($userId);

                Log::newInstance()->insertLog('user', 'edit', $userId, $user['s_email'], $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : osc_logged_user_id() );
            }

            if(!$this->is_admin) {
                Session::newInstance()->_set('userName', $input['s_name']);
                $phone = ($input['s_phone_mobile'])? $input['s_phone_mobile'] : $input['s_phone_land'];
                Session::newInstance()->_set('userPhone', $phone);
            }

            if ( is_array( Params::getParam('s_info') ) ) {
                foreach (Params::getParam('s_info') as $key => $value) {
                    $this->manager->updateDescription($userId, $key, $value);
                }
            }

            osc_run_hook('user_edit_completed', $userId);

            if ( $this->is_admin ) {
                $iUpdated = 0;
                if( (Params::getParam("b_enabled") != '') && (Params::getParam("b_enabled") == 1 ) ) {
                    $iUpdated += $this->manager->update( array('b_enabled' => 1), array('pk_i_id' => $userId) );
                } else {
                    $iUpdated += $this->manager->update( array('b_enabled' => 0), array('pk_i_id' => $userId) );
                }

                if( (Params::getParam("b_active") != '') && (Params::getParam("b_active") == 1) ) {
                    $iUpdated += $this->manager->update( array('b_active' => 1), array('pk_i_id' => $userId) );
                } else {
                    $iUpdated += $this->manager->update( array('b_active' => 0), array('pk_i_id' => $userId) );
                }

                if($iUpdated > 0) {
                    return 2;
                }
            }

            return 1;
        }

        function recover_password()
        {
            $user = User::newInstance()->findByEmail( Params::getParam('s_email') );
            Session::newInstance()->_set( 'recover_time', time() );

            if ( (osc_recaptcha_private_key() != '') ) {
                if( !osc_check_recaptcha() ) {
                    return 2; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
                }
            }

            if( !$user || ($user['b_enabled'] == 0) ) {
                return 1;
            }

            $code = osc_genRandomPassword(30);
            $date = date('Y-m-d H:i:s');
            User::newInstance()->update(
                array('s_pass_code' => $code, 's_pass_date' => $date, 's_pass_ip' => $_SERVER['REMOTE_ADDR']),
                array('pk_i_id'     => $user['pk_i_id'])
            );

            $password_url = osc_forgot_user_password_confirm_url($user['pk_i_id'], $code);
            osc_run_hook('hook_email_user_forgot_password', $user, $password_url);

            return 0;
        }

        function prepareData($is_add)
        {
            $input = array();

            if ( $is_add ) {
                $input['s_secret']    = osc_genRandomPassword();
                $input['dt_reg_date'] = date('Y-m-d H:i:s');
            } else {
                $input['dt_mod_date'] = date('Y-m-d H:i:s');
            }

            //only for administration, in the public website this two params are edited separately
            if ( $this->is_admin || $is_add ) {
                $input['s_email'] = Params::getParam('s_email');

                //if we want to change the password
                if( Params::getParam('s_password', false, false) != '') {
                    $input['s_password'] = osc_hash_password(Params::getParam('s_password', false, false));
                }
                $input['s_username']     = osc_sanitize_username(Params::getParam('s_username'));
            }

            $input['s_name']         = trim(Params::getParam('s_name'));
            $input['s_website']      = trim(Params::getParam('s_website'));
            $input['s_phone_land']   = trim(Params::getParam('s_phone_land'));
            $input['s_phone_mobile'] = trim(Params::getParam('s_phone_mobile'));

            if(strtolower(substr($input['s_website'], 0, 4))!=='http') {
                $input['s_website'] = 'http://'.$input['s_website'];
            }
            $input['s_website'] = osc_sanitize_url($input['s_website']);
            if ( ! osc_validate_url($input['s_website'])) $input['s_website'] = '';

            //locations...
            $country = Country::newInstance()->findByCode( Params::getParam('countryId') );
            if(count($country) > 0) {
                $countryId   = $country['pk_c_code'];
                $countryName = $country['s_name'];
            } else {
                $countryId   = null;
                $countryName = Params::getParam('country');
            }

            if( intval( Params::getParam('regionId') ) ) {
                $region = Region::newInstance()->findByPrimaryKey( Params::getParam('regionId') );
                if( count($region) > 0 ) {
                    $regionId   = $region['pk_i_id'];
                    $regionName = $region['s_name'];
                }
            } else {
                $regionId   = null;
                $regionName = Params::getParam('region');
            }

            if( intval( Params::getParam('cityId') ) ) {
                $city = City::newInstance()->findByPrimaryKey( Params::getParam('cityId') );
                if( count($city) > 0 ) {
                    $cityId   = $city['pk_i_id'];
                    $cityName = $city['s_name'];
                }
            } else {
                $cityId   = null;
                $cityName = Params::getParam('city');
            }

            $input['fk_c_country_code'] = $countryId;
            $input['s_country'] = $countryName;
            $input['fk_i_region_id'] = $regionId;
            $input['s_region']       = $regionName;
            $input['fk_i_city_id']   = $cityId;
            $input['s_city']         = $cityName;
            $input['s_city_area']    = Params::getParam('cityArea');
            $input['s_address']      = Params::getParam('address');
            $input['s_zip']          = Params::getParam('zip');
            $input['d_coord_lat']    = (Params::getParam('d_coord_lat')  != '') ? Params::getParam('d_coord_lat') : null;
            $input['d_coord_long']   = (Params::getParam('d_coord_long') != '') ? Params::getParam('d_coord_long') : null;
            $input['b_company']      = (Params::getParam('b_company') != '' && Params::getParam('b_company') != 0) ? 1 : 0;

            return($input);
        }

        public function activate($user_id)
        {
            $user = $this->manager->findByPrimaryKey($user_id);

            if( !$user ) {
                return false;
            }

            $this->manager->update( array('b_active' => 1), array('pk_i_id' => $user_id) );

            if( !$this->is_admin ) {
                osc_run_hook('hook_email_admin_new_user', $user);
            }

            Log::newInstance()->insertLog('user', 'activate', $user_id, $user['s_email'], $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : osc_logged_user_id() );

            if( $user['b_enabled'] == 1 ) {
                $mItem = new ItemActions(true);
                $items = Item::newInstance()->findByUserID($user_id);
                foreach($items as $item) {
                    $mItem->enable($item['pk_i_id']);
                }
            }
            osc_run_hook('activate_user', $user);

            return true;
        }

        public function deactivate($user_id)
        {
            $user = $this->manager->findByPrimaryKey($user_id);

            if( !$user ) {
                return false;
            }

            $this->manager->update( array('b_active' => 0), array('pk_i_id' => $user_id) );

            Log::newInstance()->insertLog('user', 'deactivate', $user_id, $user['s_email'], $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : osc_logged_user_id() );

            if( $user['b_enabled'] == 1 ) {
                $mItem = new ItemActions(true);
                $items = Item::newInstance()->findByUserID($user_id);
                foreach($items as $item) {
                    $mItem->disable($item['pk_i_id']);
                }
            }
            osc_run_hook('deactivate_user', $user);

            return true;
        }

        public function enable($user_id)
        {
            $user = $this->manager->findByPrimaryKey($user_id);

            if( !$user ) {
                return false;
            }

            $this->manager->update( array('b_enabled' => 1), array('pk_i_id' => $user_id) );

            Log::newInstance()->insertLog('user', 'enable', $user_id, $user['s_email'], $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : osc_logged_user_id() );

            if( $user['b_active'] == 1 ) {
                $mItem = new ItemActions(true);
                $items = Item::newInstance()->findByUserID($user_id);
                foreach($items as $item) {
                    $mItem->enable($item['pk_i_id']);
                }
            }
            osc_run_hook('enable_user', $user);

            return true;
        }

        public function disable($user_id)
        {
            $user = $this->manager->findByPrimaryKey($user_id);

            if( !$user ) {
                return false;
            }

            $this->manager->update( array('b_enabled' => 0), array('pk_i_id' => $user_id) );

            Log::newInstance()->insertLog('user', 'disable', $user_id, $user['s_email'], $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : osc_logged_user_id());

            if( $user['b_active'] == 1 ) {
                $mItem = new ItemActions(true);
                $items = Item::newInstance()->findByUserID($user_id);
                foreach($items as $item) {
                    $mItem->disable($item['pk_i_id']);
                }
            }
            osc_run_hook('disable_user', $user);

            return true;
        }

        public function resend_activation($user_id)
        {
            $user = $this->manager->findByPrimaryKey($user_id);
            $input['s_secret'] = $user['s_secret'];

            if( !$user  || $user['b_active']==1) {
                return 0;
            }

            if( osc_user_validation_enabled() ) {
                osc_run_hook('hook_email_user_validation', $user, $input);
                return 1;
            }

            return 0;
        }

        public function bootstrap_login($user_id)
        {
            $user = User::newInstance()->findByPrimaryKey( $user_id );

            if( !$user ) {
                return 0;
            }

            if( !$user['b_active'] ) {
                return 1;
            }

            if( !$user['b_enabled'] ) {
                return 2;
            }

            //we are logged in... let's go!
            Session::newInstance()->_set('userId', $user['pk_i_id']);
            Session::newInstance()->_set('userName', $user['s_name']);
            Session::newInstance()->_set('userEmail', $user['s_email']);
            $phone = ($user['s_phone_mobile']) ? $user['s_phone_mobile'] : $user['s_phone_land'];
            Session::newInstance()->_set('userPhone', $phone);

            return 3;
        }
    }

?>
