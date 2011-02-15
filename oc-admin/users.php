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


            case 'create':          $countries = Country::newInstance()->listAll();
                                    $regions = Region::newInstance()->getByCountry($countries[0]['pk_c_code']);
                                    $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$regions[0]['pk_i_id']) ;

                                    $this->_exportVariableToView("user", null);
                                    $this->_exportVariableToView("countries", $countries);
                                    $this->_exportVariableToView("regions", $regions);
                                    $this->_exportVariableToView("cities", $cities);
                                    $this->_exportVariableToView("locales", Locale::newInstance()->listAllEnabled());

                                    $this->doView("users/frm.php");
            break;
            case 'create_post':     //creating the user...
                                    require_once LIB_PATH . 'osclass/users.php' ;
                                    $userActions = new UserActions(true) ;
                                    $success = $userActions->add() ;
                                    switch($success) {
                                        case 1: osc_add_flash_message(__('The user has been created. An activation email has been sent to the user\'s email address')) ;
                                        break;
                                        case 2: osc_add_flash_message(__('The user has been created and it was activated')) ;
                                        break;
                                        case 3: osc_add_flash_message(__('Sorry, but that email is already in use')) ;
                                        break;
                                    }
                                    $this->redirectTo("index.php?page=users") ;
            break;
            case 'edit':            $user = $this->userManager->findByPrimaryKey(Params::getParam("id"));
                                    $countries = Country::newInstance()->listAll();
                                    $regions = array();
                                    if( isset($user['fk_c_country_code']) && $user['fk_c_country_code']!='' ) {
                                        $regions = Region::newInstance()->getByCountry($user['fk_c_country_code']);
                                    } else if( count($countries) > 0 ) {
                                        $regions = Region::newInstance()->getByCountry($countries[0]['pk_c_code']);
                                    }
                                    $cities = array();
                                    if( isset($user['fk_i_region_id']) && $user['fk_i_region_id']!='' ) {
                                        $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$user['fk_i_region_id']) ;
                                    } else if( count($regions) > 0 ) {
                                        $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$regions[0]['pk_i_id']) ;
                                    }
                                    
                                    $this->_exportVariableToView("user", $user);
                                    $this->_exportVariableToView("countries", $countries);
                                    $this->_exportVariableToView("regions", $regions);
                                    $this->_exportVariableToView("cities", $cities);
                                    $this->_exportVariableToView("locales", Locale::newInstance()->listAllEnabled());
                                    $this->doView("users/frm.php");
            break;
            case 'edit_post':       //edit post
                                    require_once LIB_PATH . 'osclass/users.php' ;
                                    $userActions = new UserActions(true) ;
                                    $success = $userActions->edit( Params::getParam("id") ) ;

                                    switch($success) {
                                        case 1: osc_add_flash_message(__('Passwords don\'t match')) ;
                                        break;
                                        case 2: osc_add_flash_message(__('The user has been updated and it was activated')) ;
                                        break;
                                        default: osc_add_flash_message(__('The user has been updated'));
                                        break;
                                    }

                                    $this->redirectTo("index.php?page=users") ;
            break;
            case 'activate':        //activate
                                    $ids = Params::getParam('id');
                                    foreach($ids as $id) {
                                        $conditions = array('pk_i_id' => $id);
                                        $values = array('b_enabled' => 1);
                                        try {
                                            $this->userManager->update($values, $conditions);
                                            osc_add_flash_message(__('The user has been activated'));
                                        } catch (Exception $e) {
                                            osc_add_flash_message(__('Error: ') . $e->getMessage());
                                        }
                                    }
                                    $this->redirectTo("index.php?page=users");
            break;
            case 'deactivate':      //deactivate
                                    $ids = Params::getParam('id');
                                    foreach($ids as $id) {
                                        $conditions = array('pk_i_id' => $id);
                                        $values = array('b_enabled' => 0);
                                        try {
                                            $this->userManager->update($values, $conditions);
                                            osc_add_flash_message(__('The user has been deactivated.'));
                                        } catch (Exception $e) {
                                            osc_add_flash_message(__('Error: ') . $e->getMessage());
                                        }
                                    }
                                    $this->redirectTo("index.php?page=users");
            break;
            case 'delete':          //delete
                                    $ids = Params::getParam('id');
                                    foreach($ids as $id) {
                                        $this->userManager->deleteUser($id);
                                    }
                                    $this->redirectTo("index.php?page=users") ;
            break;
            default:
                                    $this->add_global_js('jquery.dataTables.min.js') ;
                                    $this->add_css('item_list_layout.css') ;
                                    $this->add_css('demo_table.css') ;

                                    $this->_exportVariableToView("users", $this->userManager->listAll());
                                    $this->doView("users/index.php");
        }
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }
}

?>
