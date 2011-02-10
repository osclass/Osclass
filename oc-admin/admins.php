<?php

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

    function __construct() {
        parent::__construct() ;

        //specific things for this class
        $this->adminManager = Category::newInstance() ;
    }

    //Business Layer...
    function doModel() {
        parent::doModel() ;

        switch ($this->action)
        {
            case 'add':         $this->doView('admins/add.php') ;
            break;
            case 'add_post':    $_POST['s_password'] = sha1($_POST['s_password']) ;
                                $this->adminManager->insert($_POST) ;
                                osc_add_flash_message(__('The item has been added.')) ;
                                $this->redirectTo(osc_admin_base_url(true).'?page=admins') ;
            break;
            case 'edit':        $admin = null;
                                if(Params::getParam('id') != '') $adminEdit = $this->adminManager->findByPrimaryKey ( Params::getParam('id') ) ;
                                elseif( Session::newInstance()->_get('adminId') != '') $adminEdit = $this->adminManager->findByPrimaryKey( Session::newInstance()->_get('adminId') ) ;
                                $this->doView('admins/edit.php') ;
            break;
            case 'edit_post':   $conditions = array('pk_i_id' => Params::getParam('id')) ;
                                $admin = $this->adminManager->findByPrimaryKey(Params::getParam('id')) ;
                                unset($_POST['id']);
                                if(empty($_POST['s_password'])) {
                                    unset($_POST['s_password']);
                                } else {
                                    if(sha1($_POST['old_password'])==$admin['s_password']) {
                                        if($_POST['s_password']==$_POST['s_password2']) {
                                            $_POST['s_password'] = sha1($_POST['s_password']);
                                        } else {
                                            unset($_POST['s_password']);
                                            osc_add_flash_message(__('Password didn\'t update. Passwords don\'t match.'));
                                        }
                                    } else {
                                        unset($_POST['s_password']);
                                        osc_add_flash_message(__('Password didn\'t update. "Old password" didn\'t match with our records in the database.'));
                                    }
                                }
                                unset($_POST['old_password']);
                                unset($_POST['s_password2']);

                                $this->adminManager->update($_POST, $conditions);
                                $this->redirectTo(osc_admin_base_url(true).'?page=admins') ;
            break;
            case 'delete':      $id = osc_paramRequest('id', false);
                                if($id) {
                                    // Verification to avoid an administrator trying to remove to itself
                                    if(in_array($_SESSION['adminId'], $id)) {
                                        osc_add_flash_message( __('The operation was not completed. You were trying to remove yourself!') );
                                    } else {
                                        $this->adminManager->delete(array('pk_i_id IN (' . implode(', ', $id) . ')')) ;
                                    }
                                }
                                $this->redirectTo(osc_admin_base_url(true).'?page=admins') ;
            break;
            default:            $admins = $this->adminManager->listAll();
                                $this->doView('admins/index.php') ;
        }
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }
}

?>