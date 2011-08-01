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
        private $userManager;

        function __construct() {
            parent::__construct() ;

            //specific things for this class
            $this->userManager = User::newInstance() ;
        }

        //Business Layer...
        function doModel() {
            parent::doModel() ;

            //specific things for this class
            switch ($this->action)
            {
                case 'create':          // callign create view
                                        $aCountries = array();
                                        $aRegions   = array();
                                        $aCities    = array();

                                        $aCountries = Country::newInstance()->listAll();
                                        if(isset($aCountries[0]['pk_c_code'])) {
                                            $aRegions = Region::newInstance()->getByCountry($aCountries[0]['pk_c_code']);
                                        }
                                        if(isset($aRegions[0]['pk_i_id'])) {
                                            $aCities  = City::newInstance()->listWhere("fk_i_region_id = %d" ,$aRegions[0]['pk_i_id']) ;
                                        }

                                        $this->_exportVariableToView("user", null);
                                        $this->_exportVariableToView("countries", $aCountries);
                                        $this->_exportVariableToView("regions", $aRegions);
                                        $this->_exportVariableToView("cities", $aCities);
                                        $this->_exportVariableToView("locales", OSCLocale::newInstance()->listAllEnabled());

                                        $this->doView("users/frm.php");
                break;
                case 'create_post':     // creating the user...
                                        require_once LIB_PATH . 'osclass/UserActions.php' ;
                                        $userActions = new UserActions(true) ;
                                        $success = $userActions->add() ;
                                        switch($success) {
                                            case 1: osc_add_flash_ok_message( _m('The user has been created. We\'ve sent an activation e-mail'), 'admin') ;
                                            break;
                                            case 2: osc_add_flash_ok_message( _m('The user has been created and activated'), 'admin') ;
                                            break;
                                            case 3: osc_add_flash_error_message( _m('Sorry, but that e-mail is already in use'), 'admin') ;
                                            break;
                                        }

                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                break;
                case 'edit':            // calling the edit view
                                        $aUser      = array();
                                        $aCountries = array();
                                        $aRegions   = array();
                                        $aCities    = array();

                                        $aUser = $this->userManager->findByPrimaryKey(Params::getParam("id"));
                                        $aCountries = Country::newInstance()->listAll();
                                        $aRegions = array();
                                        if( $aUser['fk_c_country_code'] != '') {
                                            $aRegions = Region::newInstance()->getByCountry($aUser['fk_c_country_code']);
                                        } else if( count($aCountries) > 0 ) {
                                            $aRegions = Region::newInstance()->getByCountry($aCountries[0]['pk_c_code']);
                                        }
                                        $aCities = array();
                                        if( $aUser['fk_i_region_id']!='' ) {
                                            $aCities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$aUser['fk_i_region_id']) ;
                                        } else if( count($aRegions) > 0 ) {
                                            $aCities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$aRegions[0]['pk_i_id']) ;
                                        }

                                        $this->_exportVariableToView("user", $aUser);
                                        $this->_exportVariableToView("countries", $aCountries);
                                        $this->_exportVariableToView("regions", $aRegions);
                                        $this->_exportVariableToView("cities", $aCities);
                                        $this->_exportVariableToView("locales", OSCLocale::newInstance()->listAllEnabled());
                                        $this->doView("users/frm.php");
                break;
                case 'edit_post':       // edit post
                                        require_once LIB_PATH . 'osclass/UserActions.php' ;
                                        $userActions = new UserActions(true) ;
                                        $success = $userActions->edit( Params::getParam("id") ) ;

                                        switch($success) {
                                            case (1):  osc_add_flash_error_message( _m('Passwords don\'t match'), 'admin') ;
                                            break;
                                            case (2):  osc_add_flash_ok_message( _m('The user has been updated and activated'), 'admin') ;
                                            break;
                                            default:   osc_add_flash_ok_message( _m('The user has been updated'), 'admin');
                                            break;
                                        }

                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                break;
                case 'activate':        //activate
                                        require_once LIB_PATH . 'osclass/UserActions.php' ;
                                        $iUpdated = 0;
                                        $userId   = Params::getParam('id');
                                        if(!is_array($userId)) {
                                            osc_add_flash_error_message(_m('User id isn\'t in the correct format'), 'admin');
                                        }

                                        $userActions = new UserActions(true) ;
                                        foreach($userId as $id) {
                                            $iUpdated   += $userActions->activate($id);
                                        }

                                        switch ($iUpdated) {
                                            case (0):   $msg = _m('No user has been activated');
                                            break;
                                            case (1):   $msg = _m('One user has been activated');
                                            break;
                                            default:    $msg = sprintf(_m('%s users have been activated'), $iUpdated);
                                            break;
                                        }

                                        osc_add_flash_ok_message($msg, 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                break;
                case 'deactivate':      //deactivate
                                        require_once LIB_PATH . 'osclass/UserActions.php' ;
                                        $iUpdated = 0;
                                        $userId   = Params::getParam('id');
                                        if(!is_array($userId)) {
                                            osc_add_flash_error_message(_m('User id isn\'t in the correct format'), 'admin');
                                        }

                                        $userActions = new UserActions(true) ;
                                        foreach($userId as $id) {
                                            $iUpdated   += $userActions->deactivate($id);
                                        }

                                        switch ($iUpdated) {
                                            case (0):   $msg = _m('No user has been deactivated');
                                            break;
                                            case (1):   $msg = _m('One user has been deactivated');
                                            break;
                                            default:    $msg = sprintf(_m('%s users have been deactivated'), $iUpdated);
                                            break;
                                        }

                                        osc_add_flash_ok_message($msg, 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                break;
                case 'enable':
                                        require_once LIB_PATH . 'osclass/UserActions.php' ;
                                        $iUpdated = 0;
                                        $userId   = Params::getParam('id');
                                        if(!is_array($userId)) {
                                            osc_add_flash_error_message(_m('User id isn\'t in the correct format'), 'admin');
                                        }

                                        $userActions = new UserActions(true) ;
                                        foreach($userId as $id) {
                                            $iUpdated   += $userActions->enable($id);
                                        }

                                        switch ($iUpdated) {
                                            case (0):   $msg = _m('No user has been enabled');
                                            break;
                                            case (1):   $msg = _m('One user has been enabled');
                                            break;
                                            default:    $msg = sprintf(_m('%s users have been enabled'), $iUpdated);
                                            break;
                                        }

                                        osc_add_flash_ok_message($msg, 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                break;
                case 'disable':
                                        require_once LIB_PATH . 'osclass/UserActions.php' ;
                                        $iUpdated = 0;
                                        $userId   = Params::getParam('id');
                                        if(!is_array($userId)) {
                                            osc_add_flash_error_message(_m('User id isn\'t in the correct format'), 'admin');
                                        }

                                        $userActions = new UserActions(true) ;
                                        foreach($userId as $id) {
                                            $iUpdated   += $userActions->disable($id);
                                        }

                                        switch ($iUpdated) {
                                            case (0):   $msg = _m('No user has been disabled');
                                            break;
                                            case (1):   $msg = _m('One user has been disabled');
                                            break;
                                            default:    $msg = sprintf(_m('%s users have been disabled'), $iUpdated);
                                            break;
                                        }

                                        osc_add_flash_ok_message($msg, 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                break;
                case 'delete':          //delete
                                        $iDeleted = 0;
                                        $userId   = Params::getParam('id');
                                        if(!is_array($userId)) {
                                            osc_add_flash_error_message(_m('User id isn\'t in the correct format'), 'admin');
                                        }

                                        foreach($userId as $id) {
                                            $user = $this->userManager->findByPrimaryKey($id);
                                            Log::newInstance()->insertLog('user', 'delete', $id, $user['s_email'], 'admin', osc_logged_admin_id());
                                            if($this->userManager->deleteUser($id)) {
                                                $iDeleted++;
                                            }
                                        }

                                        switch ($iDeleted) {
                                            case (0):   $msg = _m('No user has been deleted');
                                            break;
                                            case (1):   $msg = _m('One user has been deleted');
                                            break;
                                            default:    $msg = sprintf(_m('%s users have been deleted'), $iDeleted);
                                            break;
                                        }

                                        osc_add_flash_ok_message($msg, 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=users');
                break;
                default:                // manage users view
                                        $aUsers = $this->userManager->listAll();

                                        $this->_exportVariableToView("users", $aUsers);
                                        $this->doView("users/index.php");
                break;
            }
        }

        //hopefully generic...
        function doView($file) {
            osc_current_admin_theme_path($file) ;
            Session::newInstance()->_clearVariables();
        }
    }

?>