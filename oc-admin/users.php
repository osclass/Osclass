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

    class CAdminUsers extends AdminSecBaseModel
    {
        //specific for this class
        private $userManager;

        function __construct()
        {
            parent::__construct();

            //specific things for this class
            $this->userManager = User::newInstance();
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel();

            //specific things for this class
            switch ($this->action) {
                case('create'):         // calling create view
                                        $aRegions   = array();
                                        $aCities    = array();

                                        $aCountries = Country::newInstance()->listAll();

                                        if( isset($aCountries[0]['pk_c_code']) ) {
                                            $aRegions = Region::newInstance()->findByCountry($aCountries[0]['pk_c_code']);
                                        }

                                        if( isset($aRegions[0]['pk_i_id']) ) {
                                            $aCities  = City::newInstance()->findByRegion($aRegions[0]['pk_i_id']);
                                        }

                                        $this->_exportVariableToView( 'user', null );
                                        $this->_exportVariableToView( 'countries', $aCountries );
                                        $this->_exportVariableToView( 'regions', $aRegions );
                                        $this->_exportVariableToView( 'cities', $aCities );
                                        $this->_exportVariableToView( 'locales', OSCLocale::newInstance()->listAllEnabled() );

                                        $this->doView("users/frm.php");
                break;
                case('create_post'):    // creating the user...
                                        osc_csrf_check();
                                        require_once LIB_PATH . 'osclass/UserActions.php';
                                        $userActions = new UserActions(true);
                                        $success     = $userActions->add();

                                        switch($success) {
                                            case 1: osc_add_flash_ok_message( _m("The user has been created. We've sent an activation e-mail"), 'admin');
                                            break;
                                            case 2: osc_add_flash_ok_message( _m('The user has been created successfully'), 'admin');
                                            break;
                                            default: osc_add_flash_error_message( $success, 'admin');
                                            break;
                                        }

                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                break;
                case('edit'):           // calling the edit view
                                        $aUser = $this->userManager->findByPrimaryKey(Params::getParam("id"));
                                        $aCountries = Country::newInstance()->listAll();
                                        $aRegions = array();
                                        if( $aUser['fk_c_country_code'] != '' ) {
                                            $aRegions = Region::newInstance()->findByCountry($aUser['fk_c_country_code']);
                                        } else if( count($aCountries) > 0 ) {
                                            $aRegions = Region::newInstance()->findByCountry($aCountries[0]['pk_c_code']);
                                        }
                                        $aCities = array();
                                        if( $aUser['fk_i_region_id'] != '' ) {
                                            $aCities = City::newInstance()->findByRegion($aUser['fk_i_region_id']);
                                        } else if( count($aRegions) > 0 ) {
                                            $aCities = City::newInstance()->findByRegion($aRegions[0]['pk_i_id']);
                                        }

                                        $csrf_token = osc_csrf_token_url();
                                        if( $aUser['b_active'] ) {
                                            $actions[] = '<a class="btn float-left" href="'.osc_admin_base_url(true).'?page=users&action=deactivate&id[]='.$aUser['pk_i_id'].'&'.$csrf_token.'&value=INACTIVE">'.__('Deactivate') .'</a>';
                                            } else {
                                            $actions[] = '<a class="btn btn-red float-left" href="'.osc_admin_base_url(true).'?page=users&action=activate&id[]='.$aUser['pk_i_id'].'&'.$csrf_token.'&value=ACTIVE">'.__('Activate') .'</a>';
                                        }
                                        if( $aUser['b_enabled'] ) {
                                            $actions[] = '<a class="btn float-left" href="'.osc_admin_base_url(true).'?page=users&action=disable&id[]='.$aUser['pk_i_id'].'&'.$csrf_token.'&value=DISABLE">'.__('Block') .'</a>';
                                            } else {
                                            $actions[] = '<a class="btn btn-red float-left" href="'.osc_admin_base_url(true).'?page=users&action=enable&id[]='.$aUser['pk_i_id'].'&'.$csrf_token.'&value=ENABLE">'.__('Unblock') .'</a>';
                                        }
                                        $aLocale = $aUser['locale'];
                                        foreach ($aLocale as $locale => $aInfo) {
                                            $aUser['locale'][$locale]['s_info'] = osc_apply_filter('admin_user_profile_info', $aInfo['s_info'], $aUser['pk_i_id'], $aInfo['fk_c_locale_code']);
                                        }

                                        $this->_exportVariableToView("actions", $actions);

                                        $this->_exportVariableToView("user", $aUser);
                                        $this->_exportVariableToView("countries", $aCountries);
                                        $this->_exportVariableToView("regions", $aRegions);
                                        $this->_exportVariableToView("cities", $aCities);
                                        $this->_exportVariableToView("locales", OSCLocale::newInstance()->listAllEnabled());
                                        $this->doView("users/frm.php");
                break;
                case('edit_post'):      // edit post
                                        osc_csrf_check();
                                        require_once LIB_PATH . 'osclass/UserActions.php';
                                        $userActions = new UserActions(true);
                                        $success = $userActions->edit( Params::getParam("id") );
                                        if($success==1) {
                                            osc_add_flash_ok_message( _m('The user has been updated'), 'admin');
                                        } else if($success==2) {
                                            osc_add_flash_ok_message( _m('The user has been updated and activated'), 'admin');
                                        } else {
                                            osc_add_flash_error_message( $success);
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=edit&id='.Params::getParam('id'));
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                break;
                case('resend_activation'):
                                        //activate
                                        osc_csrf_check();
                                        require_once LIB_PATH . 'osclass/UserActions.php';
                                        $iUpdated = 0;
                                        $userId   = Params::getParam('id');
                                        if(!is_array($userId)) {
                                            osc_add_flash_error_message( _m("User id isn't in the correct format"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                                        }

                                        $userActions = new UserActions(true);
                                        foreach($userId as $id) {
                                            $iUpdated   += $userActions->resend_activation($id);
                                        }

                                        if($iUpdated==0) {
                                            osc_add_flash_error_message(_m('No users have been selected'), 'admin');
                                        } else {
                                            osc_add_flash_ok_message(sprintf( _mn('Activation email sent to one user', 'Activation email sent to %s users', $iUpdated), $iUpdated ), 'admin');
                                        }

                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                break;
                case('activate'):       //activate
                                        osc_csrf_check();
                                        require_once LIB_PATH . 'osclass/UserActions.php';
                                        $iUpdated = 0;
                                        $userId   = Params::getParam('id');
                                        if( !is_array($userId) ) {
                                            osc_add_flash_error_message( _m("User id isn't in the correct format"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                                        }

                                        $userActions = new UserActions(true);
                                        foreach($userId as $id) {
                                            $iUpdated   += $userActions->activate($id);
                                        }

                                        if( $iUpdated == 0 ) {
                                            $msg = _m('No users have been activated');
                                        } else {
                                            $msg = sprintf( _mn('One user has been activated', '%s users have been activated', $iUpdated), $iUpdated );
                                        }

                                        osc_add_flash_ok_message($msg, 'admin');
                                        $this->redirectTo( Params::getServerParam('HTTP_REFERER', false, false) );
                break;
                case('deactivate'):     //deactivate
                                        osc_csrf_check();
                                        require_once LIB_PATH . 'osclass/UserActions.php';
                                        $iUpdated = 0;
                                        $userId   = Params::getParam('id');

                                        if( !is_array($userId) ) {
                                            osc_add_flash_error_message( _m("User id isn't in the correct format"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                                        }

                                        $userActions = new UserActions(true);
                                        foreach($userId as $id) {
                                            $iUpdated += $userActions->deactivate($id);
                                        }

                                        if( $iUpdated == 0 ) {
                                            $msg = _m('No users have been deactivated');
                                        } else {
                                            $msg = sprintf( _mn('One user has been deactivated', '%s users have been deactivated', $iUpdated), $iUpdated );
                                        }

                                        osc_add_flash_ok_message($msg, 'admin');
                                        $this->redirectTo( Params::getServerParam('HTTP_REFERER', false, false) );
                break;
                case('enable'):
                                        osc_csrf_check();
                                        require_once LIB_PATH . 'osclass/UserActions.php';
                                        $iUpdated = 0;
                                        $userId   = Params::getParam('id');
                                        if( !is_array($userId) ) {
                                            osc_add_flash_error_message(_m("User id isn't in the correct format"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                                        }

                                        $userActions = new UserActions(true);
                                        foreach($userId as $id) {
                                            $iUpdated += $userActions->enable($id);
                                        }

                                        if( $iUpdated == 0 ) {
                                            $msg = _m('No users have been enabled');
                                        } else {
                                            $msg = sprintf( _mn('One user has been unblocked', '%s users have been unblocked', $iUpdated), $iUpdated );
                                        }

                                        osc_add_flash_ok_message($msg, 'admin');
                                        $this->redirectTo( Params::getServerParam('HTTP_REFERER', false, false) );
                break;
                case('disable'):
                                        osc_csrf_check();
                                        require_once LIB_PATH . 'osclass/UserActions.php';
                                        $iUpdated = 0;
                                        $userId   = Params::getParam('id');
                                        if( !is_array($userId) ) {
                                            osc_add_flash_error_message( _m("User id isn't in the correct format"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                                        }

                                        $userActions = new UserActions(true);
                                        foreach($userId as $id) {
                                            $iUpdated   += $userActions->disable($id);
                                        }

                                        if( $iUpdated == 0 ) {
                                            $msg = _m('No users have been disabled');
                                        } else {
                                            $msg = sprintf( _mn('One user has been blocked', '%s users have been blocked', $iUpdated), $iUpdated );
                                        }

                                        osc_add_flash_ok_message($msg, 'admin');
                                        $this->redirectTo( Params::getServerParam('HTTP_REFERER', false, false) );
                break;
                case('delete'):         //delete
                                        osc_csrf_check();
                                        $iDeleted = 0;
                                        $userId   = Params::getParam('id');

                                        if( !is_array($userId) ) {
                                            osc_add_flash_error_message( _m("User id isn't in the correct format"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                                        }

                                        foreach($userId as $id) {
                                            $user = $this->userManager->findByPrimaryKey($id);
                                            Log::newInstance()->insertLog('user', 'delete', $id, $user['s_email'], 'admin', osc_logged_admin_id());
                                            if( $this->userManager->deleteUser($id) ) {
                                                $iDeleted++;
                                            }
                                        }

                                        if( $iDeleted == 0 ) {
                                            $msg = _m('No users have been deleted');
                                        } else {
                                            $msg = sprintf( _mn('One user has been deleted', '%s users have been deleted', $iDeleted), $iDeleted );
                                        }

                                        osc_add_flash_ok_message($msg, 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                break;
                case('delete_alerts'):         //delete

                                        $iDeleted = 0;
                                        $alertId   = Params::getParam('alert_id');
                                        if( !is_array($alertId) ) {
                                            osc_add_flash_error_message( _m("Alert id isn't in the correct format"), 'admin');
                                            if(Params::getParam('user_id')=='') {
                                                $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=alerts');
                                            } else {
                                                $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=edit&id='.Params::getParam('user_id'));
                                            }
                                        }

                                        $mAlerts = new Alerts();
                                        foreach($alertId as $id) {
                                            Log::newInstance()->insertLog('user', 'delete_alerts', $id, $id, 'admin', osc_logged_admin_id());
                                            $iDeleted += $mAlerts->delete(array('pk_i_id' => $id));
                                        }

                                        if( $iDeleted == 0 ) {
                                            $msg = _m('No alerts have been deleted');
                                        } else {
                                            $msg = sprintf( _mn('One alert has been deleted', '%s alerts have been deleted', $iDeleted), $iDeleted );
                                        }

                                        osc_add_flash_ok_message($msg, 'admin');
                                        if(Params::getParam('user_id')=='') {
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=alerts');
                                        } else {
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=edit&id='.Params::getParam('user_id'));
                                        }
                break;
                case('status_alerts'):         //delete

                                        $status = Params::getParam("status");
                                        $iUpdated = 0;
                                        $alertId   = Params::getParam('alert_id');

                                        if( !is_array($alertId) ) {
                                            osc_add_flash_error_message( _m("Alert id isn't in the correct format"), 'admin');
                                            if(Params::getParam('user_id')=='') {
                                                $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=alerts');
                                            } else {
                                                $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=edit&id='.Params::getParam('user_id'));
                                            }
                                        }

                                        $mAlerts = new Alerts();
                                        foreach($alertId as $id) {
                                            if($status==1) {
                                                $iUpdated += $mAlerts->activate($id);
                                            } else {
                                                $iUpdated += $mAlerts->deactivate($id);
                                            }
                                        }


                                        if($status==1) {
                                            if( $iUpdated == 0 ) {
                                                $msg = _m('No alerts have been activated');
                                            } else {
                                                $msg = sprintf( _mn('One alert has been activated', '%s alerts have been activated', $iUpdated), $iUpdated );
                                            }
                                        } else {
                                            if( $iUpdated == 0 ) {
                                                $msg = _m('No alerts have been deactivated');
                                            } else {
                                                $msg = sprintf( _mn('One alert has been deactivated', '%s alerts have been deactivated', $iUpdated), $iUpdated );
                                            }
                                        }

                                        osc_add_flash_ok_message($msg, 'admin');
                                        if(Params::getParam('user_id')=='') {
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=alerts');
                                        } else {
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=edit&id='.Params::getParam('user_id'));
                                        }
                break;
                case('settings'):       // calling the users settings view
                                        $this->doView('users/settings.php');
                break;
                case('settings_post'):  // updating users
                                        osc_csrf_check();
                                        $iUpdated                = 0;
                                        $enabledUserValidation   = Params::getParam('enabled_user_validation');
                                        $enabledUserValidation   = (($enabledUserValidation != '') ? true : false);
                                        $enabledUserRegistration = Params::getParam('enabled_user_registration');
                                        $enabledUserRegistration = (($enabledUserRegistration != '') ? true : false);
                                        $enabledUsers            = Params::getParam('enabled_users');
                                        $enabledUsers            = (($enabledUsers != '') ? true : false);
                                        $notifyNewUser           = Params::getParam('notify_new_user');
                                        $notifyNewUser           = (($notifyNewUser != '') ? true : false);
                                        $usernameBlacklistTmp    = explode(",", Params::getParam('username_blacklist'));
                                        foreach($usernameBlacklistTmp as $k => $v) {
                                            $usernameBlacklistTmp[$k] = strtolower(trim($v));
                                        }
                                        $usernameBlacklist = implode(",", $usernameBlacklistTmp);

                                        $iUpdated += osc_set_preference('enabled_user_validation', $enabledUserValidation);
                                        $iUpdated += osc_set_preference('enabled_user_registration', $enabledUserRegistration);
                                        $iUpdated += osc_set_preference('enabled_users', $enabledUsers);
                                        $iUpdated += osc_set_preference('notify_new_user', $notifyNewUser);
                                        $iUpdated += osc_set_preference('username_blacklist', $usernameBlacklist);

                                        if( $iUpdated > 0 ) {
                                            osc_add_flash_ok_message( _m("User settings have been updated"), 'admin');
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=settings');
                break;
                case('alerts'):                // manage alerts view
                                        require_once osc_lib_path()."osclass/classes/datatables/AlertsDataTable.php";

                                        // set default iDisplayLength
                                        if( Params::getParam('iDisplayLength') != '' ) {
                                            Cookie::newInstance()->push('listing_iDisplayLength', Params::getParam('iDisplayLength'));
                                            Cookie::newInstance()->set();
                                        } else {
                                            // set a default value if it's set in the cookie
                                            if( Cookie::newInstance()->get_value('listing_iDisplayLength') != '' ) {
                                                Params::setParam('iDisplayLength', Cookie::newInstance()->get_value('listing_iDisplayLength'));
                                            } else {
                                                Params::setParam('iDisplayLength', 10 );
                                            }
                                        }
                                        $this->_exportVariableToView('iDisplayLength', Params::getParam('iDisplayLength'));

                                        // Table header order by related
                                        if( Params::getParam('sort') == '') {
                                            Params::setParam('sort', 'date');
                                        }
                                        if( Params::getParam('direction') == '') {
                                            Params::setParam('direction', 'desc');
                                        }

                                        $page  = (int)Params::getParam('iPage');
                                        if($page==0) { $page = 1; };
                                        Params::setParam('iPage', $page);

                                        $params = Params::getParamsAsArray();

                                        $alertsDataTable = new AlertsDataTable();
                                        $alertsDataTable->table($params);
                                        $aData = $alertsDataTable->getData();

                                        if(count($aData['aRows']) == 0 && $page!=1) {
                                            $total = (int)$aData['iTotalDisplayRecords'];
                                            $maxPage = ceil( $total / (int)$aData['iDisplayLength'] );

                                            $url = osc_admin_base_url(true).'?'.Params::getServerParam('QUERY_STRING', false, false);

                                            if($maxPage==0) {
                                                $url = preg_replace('/&iPage=(\d)+/', '&iPage=1', $url);
                                                $this->redirectTo($url);
                                            }

                                            if($page > 1) {
                                                $url = preg_replace('/&iPage=(\d)+/', '&iPage='.$maxPage, $url);
                                                $this->redirectTo($url);
                                            }
                                        }


                                        $this->_exportVariableToView('aData', $aData);
                                        $this->_exportVariableToView('aRawRows', $alertsDataTable->rawRows());

                                        $this->doView("users/alerts.php");
                break;
                case('ban'):             // manage ban rules view

                                        if(Params::getParam("action")!="") {
                                            osc_run_hook("ban_rules_bulk_".Params::getParam("action"), Params::getParam('id'));
                                        }

                                        require_once osc_lib_path()."osclass/classes/datatables/BanRulesDataTable.php";

                                        // set default iDisplayLength
                                        if( Params::getParam('iDisplayLength') != '' ) {
                                            Cookie::newInstance()->push('listing_iDisplayLength', Params::getParam('iDisplayLength'));
                                            Cookie::newInstance()->set();
                                        } else {
                                            // set a default value if it's set in the cookie
                                            if( Cookie::newInstance()->get_value('listing_iDisplayLength') != '' ) {
                                                Params::setParam('iDisplayLength', Cookie::newInstance()->get_value('listing_iDisplayLength'));
                                            } else {
                                                Params::setParam('iDisplayLength', 10 );
                                            }
                                        }
                                        $this->_exportVariableToView('iDisplayLength', Params::getParam('iDisplayLength'));

                                        // Table header order by related
                                        if( Params::getParam('sort') == '') {
                                            Params::setParam('sort', 'date');
                                        }
                                        if( Params::getParam('direction') == '') {
                                            Params::setParam('direction', 'desc');
                                        }

                                        $page  = (int)Params::getParam('iPage');
                                        if($page==0) { $page = 1; };
                                        Params::setParam('iPage', $page);

                                        $params = Params::getParamsAsArray();

                                        $banRulesDataTable = new BanRulesDataTable();
                                        $banRulesDataTable->table($params);
                                        $aData = $banRulesDataTable->getData();

                                        if(count($aData['aRows']) == 0 && $page!=1) {
                                            $total = (int)$aData['iTotalDisplayRecords'];
                                            $maxPage = ceil( $total / (int)$aData['iDisplayLength'] );

                                            $url = osc_admin_base_url(true).'?'.Params::getServerParam('QUERY_STRING', false, false);

                                            if($maxPage==0) {
                                                $url = preg_replace('/&iPage=(\d)+/', '&iPage=1', $url);
                                                $this->redirectTo($url);
                                            }

                                            if($page > 1) {
                                                $url = preg_replace('/&iPage=(\d)+/', '&iPage='.$maxPage, $url);
                                                $this->redirectTo($url);
                                            }
                                        }


                                        $this->_exportVariableToView('aData', $aData);
                                        $this->_exportVariableToView('aRawRows', $banRulesDataTable->rawRows());

                                        $bulk_options = array(
                                            array('value' => '', 'data-dialog-content' => '', 'label' => __('Bulk actions')),
                                            array('value' => 'delete_ban_rule', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected ban rules?'), strtolower(__('Delete'))),
                                                'label' => __('Delete'))
                                        );

                                        $bulk_options = osc_apply_filter("ban_rule_bulk_filter", $bulk_options);
                                        $this->_exportVariableToView('bulk_options', $bulk_options);


                                        //calling the view...
                                        $this->doView('users/ban.php');
                break;
                case('edit_ban_rule'):
                                        $this->_exportVariableToView('rule', BanRule::newInstance()->findByPrimaryKey(Params::getParam('id')));
                                        $this->doView('users/ban_frm.php');
                break;
                case('edit_ban_rule_post'):
                                        osc_csrf_check();
                                        if(Params::getParam('s_ip')=='' && Params::getParam('s_email')=='') {
                                            osc_add_flash_warning_message( _m("Both rules can not be empty"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=ban');
                                        }

                                        BanRule::newInstance()->update(array('s_name' => Params::getParam('s_name'), 's_ip' => Params::getParam('s_ip'), 's_email' => strtolower(Params::getParam('s_email'))), array('pk_i_id' => Params::getParam('id')));
                                        osc_add_flash_ok_message(_m('Rule updated correctly'), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=ban');
                break;
                case('create_ban_rule'):
                                        $this->_exportVariableToView('rule', null);
                                        $this->doView('users/ban_frm.php');
                break;
                case('create_ban_rule_post'):
                                        osc_csrf_check();
                                        if(Params::getParam('s_ip')=='' && Params::getParam('s_email')=='') {
                                            osc_add_flash_warning_message( _m("Both rules can not be empty"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=ban');
                                        }

                                        BanRule::newInstance()->insert(array('s_name' => Params::getParam('s_name'), 's_ip' => Params::getParam('s_ip'), 's_email' => strtolower(Params::getParam('s_email'))));
                                        osc_add_flash_ok_message(_m('Rule saved correctly'), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=ban');
                break;
                case('delete_ban_rule'):         //delete ban rules
                                        osc_csrf_check();
                                        $iDeleted = 0;
                                        $ruleId   = Params::getParam('id');

                                        if( !is_array($ruleId) ) {
                                            osc_add_flash_error_message( _m("User id isn't in the correct format"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=ban');
                                        }

                                        $ruleMgr = BanRule::newInstance();
                                        foreach($ruleId as $id) {
                                            if($ruleMgr->deleteByPrimaryKey($id)) {
                                                $iDeleted++;
                                            }
                                        }

                                        if( $iDeleted == 0 ) {
                                            $msg = _m('No rules have been deleted');
                                        } else {
                                            $msg = sprintf( _mn('One ban rule has been deleted', '%s ban rules have been deleted', $iDeleted), $iDeleted );
                                        }

                                        osc_add_flash_ok_message($msg, 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=ban');
                break;
                default:                // manage users view

                                        if(Params::getParam("action")!="") {
                                            osc_run_hook("user_bulk_".Params::getParam("action"), Params::getParam('id'));
                                        }

                                        require_once osc_lib_path()."osclass/classes/datatables/UsersDataTable.php";

                                        // set default iDisplayLength
                                        if( Params::getParam('iDisplayLength') != '' ) {
                                            Cookie::newInstance()->push('listing_iDisplayLength', Params::getParam('iDisplayLength'));
                                            Cookie::newInstance()->set();
                                        } else {
                                            // set a default value if it's set in the cookie
                                            if( Cookie::newInstance()->get_value('listing_iDisplayLength') != '' ) {
                                                Params::setParam('iDisplayLength', Cookie::newInstance()->get_value('listing_iDisplayLength'));
                                            } else {
                                                Params::setParam('iDisplayLength', 10 );
                                            }
                                        }
                                        $this->_exportVariableToView('iDisplayLength', Params::getParam('iDisplayLength'));

                                        // Table header order by related
                                        if( Params::getParam('sort') == '') {
                                            Params::setParam('sort', 'date');
                                        }
                                        if( Params::getParam('direction') == '') {
                                            Params::setParam('direction', 'desc');
                                        }

                                        $page  = (int)Params::getParam('iPage');
                                        if($page==0) { $page = 1; };
                                        Params::setParam('iPage', $page);

                                        $params = Params::getParamsAsArray();

                                        $usersDataTable = new UsersDataTable();
                                        $usersDataTable->table($params);
                                        $aData = $usersDataTable->getData();

                                        if(count($aData['aRows']) == 0 && $page!=1) {
                                            $total = (int)$aData['iTotalDisplayRecords'];
                                            $maxPage = ceil( $total / (int)$aData['iDisplayLength'] );

                                            $url = osc_admin_base_url(true).'?'.Params::getServerParam('QUERY_STRING', false, false);

                                            if($maxPage==0) {
                                                $url = preg_replace('/&iPage=(\d)+/', '&iPage=1', $url);
                                                $this->redirectTo($url);
                                            }

                                            if($page > 1) {
                                                $url = preg_replace('/&iPage=(\d)+/', '&iPage='.$maxPage, $url);
                                                $this->redirectTo($url);
                                            }
                                        }


                                        $this->_exportVariableToView('aData', $aData);
                                        $this->_exportVariableToView('withFilters', $usersDataTable->withFilters());
                                        $this->_exportVariableToView('aRawRows', $usersDataTable->rawRows());

                                        $bulk_options = array(
                                            array('value' => '', 'data-dialog-content' => '', 'label' => __('Bulk actions')),
                                            array('value' => 'activate', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected users?'), strtolower(__('Activate'))), 'label' => __('Activate')),
                                            array('value' => 'deactivate', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected users?'), strtolower(__('Deactivate'))), 'label' => __('Deactivate')),
                                            array('value' => 'enable', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected users?'), strtolower(__('Unblock'))), 'label' => __('Unblock')),
                                            array('value' => 'disable', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected users?'), strtolower(__('Block'))), 'label' => __('Block')),
                                            array('value' => 'delete', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected users?'), strtolower(__('Delete'))), 'label' => __('Delete'))
                                        );
                                        if( osc_user_validation_enabled() ) {
                                            $bulk_options[] = array('value' => 'resend_activation', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected users?'), strtolower(__('Resend the activation to'))), 'label' => __('Resend activation'));
                                        }

                                        $bulk_options = osc_apply_filter("user_bulk_filter", $bulk_options);
                                        $this->_exportVariableToView('bulk_options', $bulk_options);


                                        //calling the view...
                                        $this->doView('users/index.php');
                break;
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_run_hook("before_admin_html");
            osc_current_admin_theme_path($file);
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_admin_html");
        }
    }

    /* file end: ./oc-admin/users.php */
?>
