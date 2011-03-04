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

    class CAdminItems extends AdminSecBaseModel
    {
        //specific for this class
        private $itemManager ;

        function __construct() {
            parent::__construct() ;

            //specific things for this class
            $this->itemManager = Item::newInstance() ;
        }

        //Business Layer...
        function doModel() {
            parent::doModel() ;

            //specific things for this class
            switch ($this->action)
            {
                case 'bulk_actions':
                                        switch ( Params::getParam('bulk_actions') )
                                        {
                                            case 'activate_all':
                                                $id = Params::getParam('id') ;
                                                $value = 'ACTIVE' ;
                                                try {
                                                    if ($id) {
                                                        foreach ($id as $_id) {
                                                            $this->itemManager->update(
                                                                    array('e_status' => $value)
                                                                    ,array('pk_i_id' => $_id)
                                                            ) ;
                                                            $item = $this->itemManager->findByPrimaryKey($_id) ;
                                                            CategoryStats::newInstance()->increaseNumItems($item['fk_i_category_id']) ;
                                                        }
                                                    }
                                                    osc_add_flash_message( _m('The items have been activated'), 'admin') ;
                                                } catch (Exception $e) {
                                                    osc_add_flash_message( _m('Error: ') . $e->getMessage(), 'admin') ;
                                                }
                                            break;
                                            case 'deactivate_all':
                                                $id = Params::getParam('id') ;
                                                $value = 'INACTIVE';
                                                try {
                                                    if ($id) {
                                                        foreach ($id as $_id) {
                                                            $this->itemManager->update(
                                                                    array('e_status' => $value)
                                                                    ,array('pk_i_id' => $_id)
                                                            ) ;
                                                            $item = $this->itemManager->findByPrimaryKey($_id) ;
                                                            CategoryStats::newInstance()->decreaseNumItems($item['fk_i_category_id']) ;
                                                        }
                                                    }
                                                    osc_add_flash_message( _m('The items have been deactivated'), 'admin') ;
                                                } catch (Exception $e) {
                                                    osc_add_flash_message( _m('Error: ') . $e->getMessage(), 'admin') ;
                                                }
                                            break;
                                            case 'premium_all':
                                                $id = Params::getParam('id') ;
                                                $value = 1 ;
                                                try {
                                                    if ($id) {
                                                        foreach ($id as $_id) {
                                                            $this->itemManager->update(
                                                                    array('b_premium' => $value)
                                                                    ,array('pk_i_id' => $_id)
                                                            ) ;
                                                        }
                                                    }
                                                    osc_add_flash_message( _m('The items have been marked as premium'), 'admin') ;
                                                } catch (Exception $e) {
                                                    osc_add_flash_message( _m('Error: ') . $e->getMessage(), 'admin') ;
                                                }
                                            break;
                                            case 'depremium_all':
                                                $id = Params::getParam('id') ;
                                                $value = 0 ;
                                                try {
                                                    if ($id) {
                                                        foreach ($id as $_id) {
                                                            $this->itemManager->update(
                                                                    array('b_premium' => $value)
                                                                    ,array('pk_i_id' => $_id)
                                                            ) ;
                                                        }
                                                    }
                                                    osc_add_flash_message( _m('The changes have been made'), 'admin') ;
                                                } catch (Exception $e) {
                                                    osc_add_flash_message( _m('Error: ') . $e->getMessage(), 'admin') ;
                                                }
                                            break;
                                            case 'delete_all':
                                                $id = Params::getParam('id') ;
                                                try {
                                                    foreach($id as $i) {
                                                        if ($i) {
                                                            $item = $this->itemManager->findByPrimaryKey($i) ;
                                                            if( $item['e_status'] == 'ACTIVE' ) {
                                                                CategoryStats::newInstance()->decreaseNumItems($item['fk_i_category_id']);
                                                            }
                                                            $this->itemManager->deleteByPrimaryKey($i);
                                                        }
                                                    }
                                                    osc_add_flash_message( _m('The items have been deleted'), 'admin') ;
                                                } catch (Exception $e) {
                                                    osc_add_flash_message( _m('Error: ') . $e->getMessage(), 'admin') ;
                                                }
                                                $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                                            break;
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                break;
                case 'delete':          //delete
                                        $id = Params::getParam('id') ;
                                        try {
                                            foreach($id as $i) {
                                                if ($i) {
                                                    $item = $this->itemManager->findByPrimaryKey($i) ;
                                                    if( $item['e_status'] == 'ACTIVE' ) {
                                                        CategoryStats::newInstance()->decreaseNumItems($item['fk_i_category_id']);
                                                    }
                                                    $this->itemManager->deleteByPrimaryKey($i) ;
                                                }
                                            }
                                            osc_add_flash_message( _m('The items have been deleted'), 'admin') ;
                                        } catch (Exception $e) {
                                            osc_add_flash_message( _m('Error: ') . $e->getMessage(), 'admin') ;
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                break;
                case 'status':          //status
                                        $id = Params::getParam('id') ;
                                        $value = Params::getParam('value') ;

                                        if (!$id)
                                            return false;

                                        $id = (int) $id;

                                        if (!is_numeric($id))
                                            return false;

                                        if (!in_array($value, array('ACTIVE', 'INACTIVE')))
                                            return false;

                                        try {
                                            $this->itemManager->update(
                                                    array('e_status' => $value),
                                                    array('pk_i_id' => $id)
                                            );

                                            $item = $this->itemManager->findByPrimaryKey($id);
                                            switch ($value) {
                                                case 'ACTIVE':
                                                    CategoryStats::newInstance()->increaseNumItems($item['fk_i_category_id']);
                                                    break;
                                                case 'INACTIVE':
                                                    CategoryStats::newInstance()->decreaseNumItems($item['fk_i_category_id']);
                                                    break;
                                            }

                                            osc_add_flash_message( _m('The item has been activated'), 'admin');
                                        } catch (Exception $e) {
                                            osc_add_flash_message( _m('Error: ') . $e->getMessage(), 'admin');
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                break;
                case 'status_premium':  //status premium
                                        $id = Params::getParam('id') ;
                                        $value = Params::getParam('value') ;

                                        if (!$id)
                                            return false;

                                        $id = (int) $id;

                                        if (!is_numeric($id))
                                            return false;

                                        if (!in_array($value, array(0, 1)))
                                            return false;

                                        try {
                                            $this->itemManager->update(
                                                    array('b_premium' => $value),
                                                    array('pk_i_id' => $id)
                                            );
                                            osc_add_flash_message( _m('Changes have been applied'), 'admin');
                                        } catch (Exception $e) {
                                            osc_add_flash_message( _m('Error: ') . $e->getMessage(), 'admin');
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                break;
                case 'item_edit':
                                        //require_once LIB_PATH . 'osclass/itemActions.php';
                                        $id = Params::getParam('id') ;

                                        $item = Item::newInstance()->findByPrimaryKey($id);
                                        if (count($item) <= 0) {
                                            $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                                        }

                                        $countries = Country::newInstance()->listAll();
                                        $regions = array();
                                        if( count($countries) > 0 ) {
                                            $regions = Region::newInstance()->getByCountry($item['fk_c_country_code']);
                                        }
                                        $cities = array();
                                        if( count($regions) > 0 ) {
                                            $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$item['fk_i_region_id']) ;
                                        }

                                        $resources = Item::newInstance()->findResourcesByID($id);

                                        $this->_exportVariableToView("users", User::newInstance()->listAll());
                                        $this->_exportVariableToView("categories", Category::newInstance()->toTree());
                                        $this->_exportVariableToView("countries", $countries);
                                        $this->_exportVariableToView("regions", $regions);
                                        $this->_exportVariableToView("cities", $cities);
                                        $this->_exportVariableToView("currencies", Currency::newInstance()->listAll());
                                        $this->_exportVariableToView("locales", Locale::newInstance()->listAllEnabled());
                                        $this->_exportVariableToView("item", $item);
                                        $this->_exportVariableToView("resources", $resources);
                                        $this->_exportVariableToView("new_item", FALSE);

                                        $this->doView('items/frm.php') ;
                break;
                case 'item_edit_post':
                                        $mItems = new ItemActions(true);
                                        $success = $mItems->edit();

                                        $id = Params::getParam('userId') ;
                                        if($id !='') {
                                            $user = User::newInstance()->findByPrimaryKey( $id );
                                            Item::newInstance()->update(array(
                                                'fk_i_user_id' => $id,
                                                's_contact_name' => $user['s_name'],
                                                's_contact_email' => $user['s_email']
                                            ), array('pk_i_id' => Params::getParam('id'), 's_secret' => Params::getParam('secret') ) );
                                        } else {
                                            Item::newInstance()->update(array(
                                                'fk_i_user_id' => NULL,
                                                's_contact_name' => Params::getParam('contactName'),
                                                's_contact_email' => Params::getParam('contactEmail')
                                            ), array('pk_i_id' => Params::getParam('id'), 's_secret' => Params::getParam('secret') ) );
                                        }

                                        $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                break;
                case 'deleteResource':  //delete resource
                                        $id = Params::getParam('id') ;
                                        $name = Params::getParam('name') ;
                                        $fkid = Params::getParam('fkid') ;

                                        // delete files
                                        osc_deleteResource($id);

                                        ItemResource::newInstance()->delete(array('pk_i_id' => $id, 'fk_i_item_id' => $fkid, 's_name' => $name)) ;
                                        $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                break;
                case 'post':            //post
                                        $countries = Country::newInstance()->listAll() ;
                                        $regions = array() ;
                                        if( count($countries) > 0 ) {
                                            $regions = Region::newInstance()->getByCountry($countries[0]['pk_c_code']) ;
                                        }
                                        $cities = array() ;
                                        if( count($regions) > 0 ) {
                                            $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$regions[0]['pk_i_id']) ;
                                        }

                                        $this->_exportVariableToView("users", User::newInstance()->listAll());
                                        $this->_exportVariableToView("categories", Category::newInstance()->toTree());
                                        $this->_exportVariableToView("countries", $countries);
                                        $this->_exportVariableToView("regions", $regions);
                                        $this->_exportVariableToView("cities", $cities);
                                        $this->_exportVariableToView("currencies", Currency::newInstance()->listAll());
                                        $this->_exportVariableToView("locales", Locale::newInstance()->listAllEnabled());
                                        $this->_exportVariableToView("item", array());
                                        $this->_exportVariableToView("resources", array());
                                        $this->_exportVariableToView("new_item", TRUE);
                                        $this->doView('items/frm.php') ;
                break;
                case 'post_item':       //post item
                                        $mItem = new ItemActions(true);
                                        $success = $mItem->add();
                                        if( $success ) {
                                            osc_add_flash_message( _m('A new item has been added'), 'admin') ;
                                            $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                                        } else {
                                            osc_add_flash_message( _m('The item can\'t be added'), 'admin') ;
                                            $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                                        }
                break;
                default:                //default
                                        $catId = Params::getParam('catId') ;

                                        //preparing variables for the view
                                        $this->_exportVariableToView("items", ( ($catId) ? $this->itemManager->findByCategoryID($catId) : $this->itemManager->listAllWithCategories() ) ) ;
                                        $this->_exportVariableToView("catId", $catId) ;
                                        $this->_exportVariableToView("stat", Params::getParam('stat')) ;

                                        //calling the view...
                                        $this->doView('items/index.php') ;
            }
        }

        //hopefully generic...
        function doView($file) {
            osc_current_admin_theme_path($file) ;
        }
    }

?>