<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    class CAdminUsers extends AdminSecBaseModel
    {
        //specific for this class
        private $userManager ;

        function __construct()
        {
            parent::__construct() ;

            //specific things for this class
            $this->userManager = User::newInstance() ;
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel() ;

            //specific things for this class
            switch ($this->action) {
                case('create'):         // callign create view
                                        $aCountries = array() ;
                                        $aRegions   = array() ;
                                        $aCities    = array() ;

                                        $aCountries = Country::newInstance()->listAll() ;

                                        if( isset($aCountries[0]['pk_c_code']) ) {
                                            $aRegions = Region::newInstance()->findByCountry($aCountries[0]['pk_c_code']) ;
                                        }

                                        if( isset($aRegions[0]['pk_i_id']) ) {
                                            $aCities  = City::newInstance()->findByRegion($aRegions[0]['pk_i_id']) ;
                                        }

                                        $this->_exportVariableToView( 'user', null ) ;
                                        $this->_exportVariableToView( 'countries', $aCountries ) ;
                                        $this->_exportVariableToView( 'regions', $aRegions ) ;
                                        $this->_exportVariableToView( 'cities', $aCities ) ;
                                        $this->_exportVariableToView( 'locales', OSCLocale::newInstance()->listAllEnabled() ) ;

                                        $this->doView("users/frm.php") ;
                break ;
                case('create_post'):    // creating the user...
                                        require_once LIB_PATH . 'osclass/UserActions.php' ;
                                        $userActions = new UserActions(true) ;
                                        $success     = $userActions->add() ;

                                        switch($success) {
                                            case 1: osc_add_flash_ok_message( _m("The user has been created. We've sent an activation e-mail"), 'admin') ;
                                            break ;
                                            case 2: osc_add_flash_ok_message( _m('The user has been created successfully'), 'admin') ;
                                            break ;
                                            case 3: osc_add_flash_warning_message( _m('Sorry, but that e-mail is already in use'), 'admin') ;
                                            break ;
                                            case 5: osc_add_flash_warning_message( _m('The specified e-mail is not valid'), 'admin') ;
                                            break ;
                                            case 6: osc_add_flash_warning_message( _m('Sorry, the password cannot be empty'), 'admin') ;
                                            break ;
                                            case 7: osc_add_flash_warning_message( _m("Sorry, passwords don't match"), 'admin') ;
                                            break ;
                                        }

                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users') ;
                break;
                case('edit'):           // calling the edit view
                                        $aUser      = array() ;
                                        $aCountries = array() ;
                                        $aRegions   = array() ;
                                        $aCities    = array() ;

                                        $aUser = $this->userManager->findByPrimaryKey(Params::getParam("id")) ;
                                        $aCountries = Country::newInstance()->listAll() ;
                                        $aRegions = array() ;
                                        if( $aUser['fk_c_country_code'] != '' ) {
                                            $aRegions = Region::newInstance()->findByCountry($aUser['fk_c_country_code']) ;
                                        } else if( count($aCountries) > 0 ) {
                                            $aRegions = Region::newInstance()->findByCountry($aCountries[0]['pk_c_code']) ;
                                        }
                                        $aCities = array() ;
                                        if( $aUser['fk_i_region_id'] != '' ) {
                                            $aCities = City::newInstance()->findByRegion($aUser['fk_i_region_id']) ;
                                        } else if( count($aRegions) > 0 ) {
                                            $aCities = City::newInstance()->findByRegion($aRegions[0]['pk_i_id']) ;
                                        }

                                        $this->_exportVariableToView("user", $aUser) ;
                                        $this->_exportVariableToView("countries", $aCountries) ;
                                        $this->_exportVariableToView("regions", $aRegions) ;
                                        $this->_exportVariableToView("cities", $aCities) ;
                                        $this->_exportVariableToView("locales", OSCLocale::newInstance()->listAllEnabled()) ;
                                        $this->doView("users/frm.php") ;
                break ;
                case('edit_post'):      // edit post
                                        require_once LIB_PATH . 'osclass/UserActions.php' ;
                                        $userActions = new UserActions(true) ;
                                        $success = $userActions->edit( Params::getParam("id") ) ;

                                        switch($success) {
                                            case (1):  osc_add_flash_error_message( _m("Passwords don't match"), 'admin') ;
                                            break ;
                                            case (2):  osc_add_flash_ok_message( _m('The user has been updated and activated'), 'admin') ;
                                            break ;
                                            default:   osc_add_flash_ok_message( _m('The user has been updated'), 'admin') ;
                                            break ;
                                        }

                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users') ;
                break ;
                case('resend_activation'):
                                        //activate
                                        require_once LIB_PATH . 'osclass/UserActions.php' ;
                                        $iUpdated = 0;
                                        $userId   = Params::getParam('id');
                                        if(!is_array($userId)) {
                                            osc_add_flash_error_message( _m("User id isn't in the correct format"), 'admin') ;
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users') ;
                                        }

                                        $userActions = new UserActions(true) ;
                                        foreach($userId as $id) {
                                            $iUpdated   += $userActions->resend_activation($id) ;
                                        }

                                        if($iUpdated==0) {
                                            osc_add_flash_error_message(_m('No users have been selected'), 'admin');
                                        } else {
                                            osc_add_flash_ok_message(sprintf( _mn('Activation email sent to one user', 'Activation email sent to %s users', $iUpdated), $iUpdated ), 'admin');
                                        }

                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users') ;
                break ;
                case('activate'):       //activate
                                        require_once LIB_PATH . 'osclass/UserActions.php' ;
                                        $iUpdated = 0 ;
                                        $userId   = Params::getParam('id') ;
                                        if( !is_array($userId) ) {
                                            osc_add_flash_error_message( _m("User id isn't in the correct format"), 'admin') ;
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users') ;
                                        }

                                        $userActions = new UserActions(true) ;
                                        foreach($userId as $id) {
                                            $iUpdated   += $userActions->activate($id) ;
                                        }

                                        if( $iUpdated == 0 ) {
                                            $msg = _m('No users have been activated') ;
                                        } else {
                                            $msg = sprintf( _mn('One user has been activated', '%s users have been activated', $iUpdated), $iUpdated ) ;
                                        }

                                        osc_add_flash_ok_message($msg, 'admin') ;
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users') ;
                break ;
                case('deactivate'):     //deactivate
                                        require_once LIB_PATH . 'osclass/UserActions.php' ;
                                        $iUpdated = 0 ;
                                        $userId   = Params::getParam('id') ;
                                        if( !is_array($userId) ) {
                                            osc_add_flash_error_message( _m("User id isn't in the correct format"), 'admin') ;
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users') ;
                                        }

                                        $userActions = new UserActions(true) ;
                                        foreach($userId as $id) {
                                            $iUpdated += $userActions->deactivate($id) ;
                                        }

                                        if( $iUpdated == 0 ) {
                                            $msg = _m('No users have been deactivated') ;
                                        } else {
                                            $msg = sprintf( _mn('One user has been deactivated', '%s users have been deactivated', $iUpdated), $iUpdated ); 
                                        }

                                        osc_add_flash_ok_message($msg, 'admin') ;
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users') ;
                break ;
                case('enable'):
                                        require_once LIB_PATH . 'osclass/UserActions.php' ;
                                        $iUpdated = 0 ;
                                        $userId   = Params::getParam('id') ;
                                        if( !is_array($userId) ) {
                                            osc_add_flash_error_message(_m("User id isn't in the correct format"), 'admin') ;
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users') ;
                                        }

                                        $userActions = new UserActions(true) ;
                                        foreach($userId as $id) {
                                            $iUpdated += $userActions->enable($id) ;
                                        }

                                        if( $iUpdated == 0 ) {
                                            $msg = _m('No users have been enabled') ;
                                        } else {
                                            $msg = sprintf( _mn('One user has been unblocked', '%s users have been unblocked', $iUpdated), $iUpdated ) ;
                                        }

                                        osc_add_flash_ok_message($msg, 'admin') ;
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users') ;
                break ;
                case('disable'):
                                        require_once LIB_PATH . 'osclass/UserActions.php' ;
                                        $iUpdated = 0 ;
                                        $userId   = Params::getParam('id') ;
                                        if( !is_array($userId) ) {
                                            osc_add_flash_error_message( _m("User id isn't in the correct format"), 'admin') ;
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users') ;
                                        }

                                        $userActions = new UserActions(true) ;
                                        foreach($userId as $id) {
                                            $iUpdated   += $userActions->disable($id) ;
                                        }

                                        if( $iUpdated == 0 ) {
                                            $msg = _m('No users have been disabled') ;
                                        } else {
                                            $msg = sprintf( _mn('One user has been blocked', '%s users have been blocked', $iUpdated), $iUpdated ) ;
                                        }

                                        osc_add_flash_ok_message($msg, 'admin') ;
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users') ;
                break ;
                case('delete'):         //delete
                                        $iDeleted = 0 ;
                                        $userId   = Params::getParam('id') ;
                                        if( !is_array($userId) ) {
                                            osc_add_flash_error_message( _m("User id isn't in the correct format"), 'admin') ;
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=users') ;
                                        }

                                        foreach($userId as $id) {
                                            $user = $this->userManager->findByPrimaryKey($id) ;
                                            Log::newInstance()->insertLog('user', 'delete', $id, $user['s_email'], 'admin', osc_logged_admin_id()) ;
                                            if( $this->userManager->deleteUser($id) ) {
                                                $iDeleted++ ;
                                            }
                                        }

                                        if( $iDeleted == 0 ) {
                                            $msg = _m('No users have been deleted') ;
                                        } else {
                                            $msg = sprintf( _mn('One user has been deleted', '%s users have been deleted', $iDeleted), $iDeleted ) ;
                                        }

                                        osc_add_flash_ok_message($msg, 'admin') ;
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users') ;
                break ;
                case('settings'):       // calling the users settings view
                                        $this->doView('users/settings.php') ;
                break ;
                case('settings_post'):  // updating users
                                        $iUpdated                = 0 ;
                                        $enabledUserValidation   = Params::getParam('enabled_user_validation') ;
                                        $enabledUserValidation   = (($enabledUserValidation != '') ? true : false) ;
                                        $enabledUserRegistration = Params::getParam('enabled_user_registration') ;
                                        $enabledUserRegistration = (($enabledUserRegistration != '') ? true : false) ;
                                        $enabledUsers            = Params::getParam('enabled_users') ;
                                        $enabledUsers            = (($enabledUsers != '') ? true : false) ;
                                        $notifyNewUser           = Params::getParam('notify_new_user') ;
                                        $notifyNewUser           = (($notifyNewUser != '') ? true : false) ;

                                        $iUpdated += Preference::newInstance()->update(
                                                array('s_value' => $enabledUserValidation),
                                                array('s_name'  => 'enabled_user_validation')
                                        ) ;
                                        $iUpdated += Preference::newInstance()->update(
                                                array('s_value' => $enabledUserRegistration),
                                                array('s_name'  => 'enabled_user_registration')
                                        ) ;
                                        $iUpdated += Preference::newInstance()->update(
                                                array('s_value' => $enabledUsers),
                                                array('s_name'  => 'enabled_users')
                                        ) ;
                                        $iUpdated += Preference::newInstance()->update(
                                                array('s_value' => $notifyNewUser),
                                                array('s_name'  => 'notify_new_user')
                                        ) ;

                                        if( $iUpdated > 0 ) {
                                            osc_add_flash_ok_message( _m("User settings have been updated"), 'admin') ;
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users&action=settings') ;
                break ;
                default:                // manage users view
                                        // set default iDisplayLength 
                                        if( Params::getParam('iDisplayLength') == '' ) {
                                            Params::setParam('iDisplayLength', 10 ) ;
                                        }
                                        $p_iPage      = 1;
                                        if( is_numeric(Params::getParam('iPage')) && Params::getParam('iPage') >= 1 ) {
                                            $p_iPage = Params::getParam('iPage');
                                        }
                                        Params::setParam('iPage', $p_iPage);
                                        $this->_exportVariableToView('iDisplayLength', Params::getParam('iDisplayLength'));
                                        $this->_exportVariableToView('sSearch', Params::getParam('sSearch'));
                                        
                                        require_once osc_admin_base_path() . 'ajax/users_processing.php';
                                        $users_processing = new UsersProcessingAjax(Params::getParamsAsArray("get"));
                                        $aData = $users_processing->result() ;
                                        
                                        $page  = (int)Params::getParam('iPage');
                                        if(count($aData['aaData']) == 0 && $page!=1) {
                                            $total = (int)$aData['iTotalDisplayRecords'];
                                            $maxPage = ceil( $total / (int)$aData['iDisplayLength'] ) ;

                                            $url = osc_admin_base_url(true).'?'.$_SERVER['QUERY_STRING'];

                                            if($maxPage==0) {
                                                $url = preg_replace('/&iPage=(\d)+/', '&iPage=1', $url) ;
                                                $this->redirectTo($url) ;
                                            }

                                            if($page > 1) {   
                                                $url = preg_replace('/&iPage=(\d)+/', '&iPage='.$maxPage, $url) ;
                                                $this->redirectTo($url) ;
                                            }
                                        }
                                        
                                        $this->_exportVariableToView('aUsers', $aData) ;
                                        $this->_exportVariableToView('locales', OSCLocale::newInstance()->listAllEnabled() );
                                        
                                        $this->doView("users/index.php") ;
                break ;
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_current_admin_theme_path($file) ;
            Session::newInstance()->_clearVariables() ;
        }
    }

    /* file end: ./oc-admin/users.php */
?>