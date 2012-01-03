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

    class CAdminAdmins extends AdminSecBaseModel
    {
        //specific for this class
        private $adminManager ;

        function __construct()
        {
            parent::__construct() ;

            //specific things for this class
            $this->adminManager = Admin::newInstance() ;
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel() ;

            switch ($this->action)
            {
                case 'add':         // callin add view
                                    $this->doView('admins/add.php') ;
                break;
                case 'add_post':    if( defined('DEMO') ) {
                                        osc_add_flash_warning_message( _m("This action cannot be done because is a demo site"), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=admins');
                                    }
                                    // adding a new admin
                                    $sPassword = Params::getParam('s_password');
                                    $sName     = Params::getParam('s_name');
                                    $sEmail    = Params::getParam('s_email');
                                    $sUserName = Params::getParam('s_username');

                                    // cleaning parameters
                                    $sPassword = strip_tags($sPassword);
                                    $sPassword = trim($sPassword);
                                    $sName     = strip_tags($sName);
                                    $sName     = trim($sName);
                                    $sEmail    = strip_tags($sEmail);
                                    $sEmail    = trim($sEmail);
                                    $sUserName = strip_tags($sUserName);
                                    $sUserName = trim($sUserName);
                                    
                                    // Checks for legit data
                                    if( !osc_validate_email($sEmail, true) ) {
                                        osc_add_flash_error_message( _m("Email invalid"), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true).'?page=admins&action=add');
                                    }
                                    if( !osc_validate_username($sUserName) ) {
                                        osc_add_flash_error_message( _m("Username invalid"), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true).'?page=admins&action=add');
                                    }
                                    if($sName=='') {
                                        osc_add_flash_error_message( _m("Real Name invalid"), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true).'?page=admins&action=add');
                                    }
                                    if($sPassword=='') {
                                        osc_add_flash_error_message( _m("Password invalid"), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true).'?page=admins&action=add');
                                    }
                                    $admin = $this->adminManager->findByEmail($sEmail);
                                    if($admin) {
                                        osc_add_flash_error_message( _m("Email already in use"), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true).'?page=admins&action=add');
                                    }
                                    $admin = $this->adminManager->findByUsername($sUserName);
                                    if($admin) {
                                        osc_add_flash_error_message( _m("Username already in use"), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true).'?page=admins&action=add');
                                    }

                                    $array = array('s_password' =>  sha1($sPassword)
                                                  ,'s_name'     =>  $sName
                                                  ,'s_email'    =>  $sEmail
                                                  ,'s_username' =>  $sUserName);

                                    $isInserted = $this->adminManager->insert($array);

                                    if($isInserted) {
                                        osc_add_flash_ok_message( _m('The admin has been added'), 'admin');
                                    } else {
                                        osc_add_flash_error_message( _m('There have been an error adding a new admin'), 'admin') ;
                                    }
                                    $this->redirectTo(osc_admin_base_url(true).'?page=admins');
                break;
                case 'edit':        // calling edit admin view
                                    $adminEdit = null;
                                    $adminId   = Params::getParam('id');

                                    if(Params::getParam('id') != '') {
                                        $adminEdit = $this->adminManager->findByPrimaryKey((int)$adminId);
                                    } elseif( Session::newInstance()->_get('adminId') != '') {
                                        $adminEdit = $this->adminManager->findByPrimaryKey(Session::newInstance()->_get('adminId'));
                                    }

                                    if(count($adminEdit) == 0) {
                                        osc_add_flash_error_message( _m('There is no admin admin with this id'), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true).'?page=admins');
                                    }

                                    $this->_exportVariableToView("admin", $adminEdit);
                                    $this->doView('admins/edit.php') ;
                break;
                case 'edit_post':   if( defined('DEMO') ) {
                                        osc_add_flash_warning_message( _m("This action cannot be done because is a demo site"), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=admins');
                                    }
                                    // updating a new admin
                                    $iUpdated = 0;
                                    $adminId  = Params::getParam('id');

                                    $sPassword      = Params::getParam('s_password');
                                    $sPassword2     = Params::getParam('s_password2');
                                    $sOldPassword   = Params::getParam('old_password');
                                    $sName          = Params::getParam('s_name');
                                    $sEmail         = Params::getParam('s_email');
                                    $sUserName      = Params::getParam('s_username');

                                    // cleaning parameters
                                    $sPassword      = strip_tags($sPassword);
                                    $sPassword      = trim($sPassword);
                                    $sPassword2     = strip_tags($sPassword2);
                                    $sPassword2     = trim($sPassword2);
                                    $sName          = strip_tags($sName);
                                    $sName          = trim($sName);
                                    $sEmail         = strip_tags($sEmail);
                                    $sEmail         = trim($sEmail);
                                    $sUserName      = strip_tags($sUserName);
                                    $sUserName      = trim($sUserName);


                                    // Checks for legit data
                                    if( !osc_validate_email($sEmail, true) ) {
                                        osc_add_flash_error_message( _m("Email invalid"), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true).'?page=admins&action=edit&id='.$adminId);
                                    }
                                    if( !osc_validate_username($sUserName) ) {
                                        osc_add_flash_error_message( _m("Username invalid"), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true).'?page=admins&action=edit&id='.$adminId);
                                    }
                                    if($sName=='') {
                                        osc_add_flash_error_message( _m("Real Name invalid"), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true).'?page=admins&action=edit&id='.$adminId);
                                    }

                                    $aAdmin   = $this->adminManager->findByPrimaryKey($adminId);

                                    if(count($aAdmin) == 0) {
                                        osc_add_flash_error_message( _m('This admin doesn\'t exist'), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true).'?page=admins');
                                    }

                                    if( $aAdmin['s_email'] != $sEmail ){
                                        if($this->adminManager->findByEmail( $sEmail ) ) {
                                            osc_add_flash_error_message( _m('Existing email'), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true).'?page=admins&action=edit&id=' . $adminId);
                                        }
                                    }

                                    if( $aAdmin['s_username'] != $sUserName ){
                                        if($this->adminManager->findByUsername( $sUserName ) ) {
                                            osc_add_flash_error_message( _m('Existing username'), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true).'?page=admins&action=edit&id=' . $adminId);
                                        }
                                    }

                                    $conditions = array('pk_i_id' => $adminId);
                                    $array      = array();

                                    if($sOldPassword != '') {
                                        if($sPassword=='') {
                                            osc_add_flash_error_message( _m("Password invalid"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true).'?page=admins&action=edit&id='.$adminId);
                                        } else {
                                            $firstCondition  = sha1($sOldPassword) == $aAdmin['s_password'];
                                            $secondCondition = $sPassword == $sPassword2;
                                            if( $firstCondition && $secondCondition ) {
                                                $array['s_password'] = sha1($sPassword);
                                            } else {
                                                osc_add_flash_error_message( _m('The password couldn\'t be updated. Passwords don\'t match'), 'admin');
                                                $this->redirectTo(osc_admin_base_url(true).'?page=admins&action=edit&id=' . $adminId);
                                            }
                                        }
                                    }

                                    $array['s_name']     = Params::getParam('s_name');
                                    $array['s_username'] = $sUserName;
                                    $array['s_email']    = $sEmail;

                                    $iUpdated = $this->adminManager->update($array, $conditions);

                                    if($iUpdated > 0) {
                                        osc_add_flash_ok_message( _m('The admin has been updated'), 'admin');
                                    }

                                    $this->redirectTo(osc_admin_base_url(true).'?page=admins');
                break;
                case 'delete':      if( defined('DEMO') ) {
                                        osc_add_flash_warning_message( _m("This action cannot be done because is a demo site"), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=admins');
                                    }
                                    // deleting and admin
                                    $isDeleted = false;
                                    $adminId   = Params::getParam('id') ;

                                    if(!is_array($adminId)) {
                                        osc_add_flash_error_message( _m('The admin id isn\'t in the correct format'), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true).'?page=admins');
                                    }

                                    // Verification to avoid an administrator trying to remove to itself
                                    if(in_array(Session::newInstance()->_get('adminId'), $adminId)) {
                                        osc_add_flash_error_message( _m('The operation hasn\'t been completed. You\'re trying to remove yourself!'), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true).'?page=admins');
                                    }

                                    $isDeleted = $this->adminManager->deleteBatch( $adminId ) ;

                                    if($isDeleted) {
                                        osc_add_flash_ok_message( _m('The admin has been deleted correctly'), 'admin');
                                    } else {
                                        osc_add_flash_error_message( _m('The admin couldn\'t be deleted'), 'admin');
                                    }
                                    $this->redirectTo(osc_admin_base_url(true).'?page=admins') ;
                break;
                default:            // calling manage admins view
                                    $admins = $this->adminManager->listAll();

                                    $this->_exportVariableToView("admins", $admins);
                                    $this->doView('admins/index.php');
                break;
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_current_admin_theme_path($file) ;
            Session::newInstance()->_clearVariables();
        }
    }

?>