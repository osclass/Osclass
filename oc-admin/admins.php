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

        $this->add_css('admins_list_layout.css');
        $this->add_css('demo_table.css');
        $this->add_global_js('jquery.dataTables.min.js');

        //specific things for this class
        $this->adminManager = Admin::newInstance() ;
    }

    //Business Layer...
    function doModel() {
        parent::doModel() ;

        switch ($this->action)
        {
            case 'add':         $this->doView('admins/add.php') ;
            break;
            case 'add_post':    $array = array(
                                        's_password'    =>  sha1(Params::getParam('s_password')),
                                        's_name'        =>  Params::getParam('s_name'),
                                        's_email'       =>  Params::getParam('s_email'),
                                        's_username'    =>  Params::getParam('s_username'),
                                );
                                $this->adminManager->insert( $array ) ;

                                osc_add_flash_message(__('The item has been added.')) ;
                                $this->redirectTo(osc_admin_base_url(true).'?page=admins') ;
            break;
            case 'edit':        $adminEdit = null;
                                if(Params::getParam('id') != '') $adminEdit = $this->adminManager->findByPrimaryKey ( Params::getParam('id') ) ;
                                elseif( Session::newInstance()->_get('adminId') != '') $adminEdit = $this->adminManager->findByPrimaryKey( Session::newInstance()->_get('adminId') ) ;

                                $this->_exportVariableToView("admin", $adminEdit);

                                $this->doView('admins/edit.php') ;
            break;
            case 'edit_post':   $conditions = array('pk_i_id' => Params::getParam('id')) ;
                                $admin = $this->adminManager->findByPrimaryKey(Params::getParam('id')) ;
                                $array = array();
                                if( Params::getParam('s_password') == '' ) {  // OJO
                                } else {
                                    if( sha1(Params::getParam('old_password')) == $admin['s_password'] ) {
                                        if( Params::getParam('s_password') == Params::getParam('s_password2') ) {


                                            if( $admin['s_username'] != Params::getParam('s_username') ){  // si cambia el username
                                                if($this->adminManager->findByUsername( Params::getParam('s_username') ) ) {  // si exisite username NO PUEDE UPDATE
                                                    osc_add_flash_message(__('Existing username.'));
                                                }
                                            }
                                            $array['s_password']    = sha1(Params::getParam('s_password') );
                                            $array['s_email']       = Params::getParam('s_email');
                                            $array['s_username']    = Params::getParam('s_username');

                                        } else {
                                            osc_add_flash_message(__('Password didn\'t update. Passwords don\'t match.'));
                                        }
                                    } else {
                                        osc_add_flash_message(__('Password didn\'t update. "Old password" didn\'t match with our records in the database.'));
                                    }
                                }
                                $this->adminManager->update($array, $conditions);
                                $this->redirectTo(osc_admin_base_url(true).'?page=admins') ;
            break;
            case 'delete':      $id = Params::getParam('id') ;
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

                                $this->_exportVariableToView("admins", $admins);
                                
                                $this->doView('admins/index.php');
        }
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }
}

?>