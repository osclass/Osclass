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

class CAdminCategories extends AdminSecBaseModel
{
    //specific for this class
    private $categoryManager ;
    
    function __construct() {
        parent::__construct() ;

        //specific things for this class
        $this->categoryManager = Category::newInstance() ;
    }

    //Business Layer...
    function doModel() {
        parent::doModel() ;

        //specific things for this class
        switch ($this->action)
        {


            case 'add':
                $this->add_css('tabs.css') ;
                $this->add_global_js('tabber-minimized.js') ;
                $this->_exportVariableToView("categories", $this->categoryManager->toTreeAll());
                $this->_exportVariableToView("languages", Locale::newInstance()->listAllEnabled());
                $this->_exportVariableToView("category", array());
                $this->doView("categories/frm.php");
            break;
            
            case 'add_post':
                try {
                    // fields contain data of t_category
                    $fields['fk_i_parent_id'] = (Params::getParam("fk_i_parent_id")!='') ? Params::getParam("fk_i_parent_id") : null;
                    $fields['i_expiration_days'] = Params::getParam("i_expiration_days");
                    $fields['i_position'] = Params::getParam("i_position");
                    $fields['b_enabled'] = (Params::getParam("b_enabled")!='' ) ? 1 : 0;

                    $postParams = Params::getParamsAsArray();
                    foreach ($postParams as $k => $v) {
                        if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                            $aFieldsDescription[$m[1]][$m[2]] = $v;
                        }
                    }
                    $this->categoryManager->insert($fields, $aFieldsDescription);

                    osc_add_flash_message(__('The category has been added.'));
                } catch (Exception $e) {
                    osc_add_flash_message(__('Error: ') . $e->getMessage());
                }
                $this->redirectTo(osc_admin_base_url(true).'?page=categories');
            break;
            
            
            case 'edit':
                $this->add_css('tabs.css') ;
                $this->add_global_js('tabber-minimized.js') ;
                $this->_exportVariableToView("category", $this->categoryManager->findByPrimaryKey(Params::getParam("id")));
                $this->_exportVariableToView("categories", $this->categoryManager->toTreeAll());
                $this->_exportVariableToView("languages", Locale::newInstance()->listAllEnabled());
                $this->doView("categories/frm.php");
            break;
            
            case 'edit_post':
                $id = Params::getParam("id");
                
                $fields['fk_i_parent_id'] = (Params::getParam("fk_i_parent_id")!='') ? Params::getParam("fk_i_parent_id") : null;
                $fields['i_expiration_days'] = Params::getParam("i_expiration_days");
                $fields['i_position'] = Params::getParam("i_position");
                $fields['b_enabled'] = (Params::getParam("b_enabled")!='' ) ? 1 : 0;

                $postParams = Params::getParamsAsArray();
                foreach ($postParams as $k => $v) {
                    if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                        $aFieldsDescription[$m[1]][$m[2]] = $v;
                    }
                }
                
                try {
                    $this->categoryManager->updateByPrimaryKey($fields, $aFieldsDescription, $id);
                    osc_add_flash_message(__('The item has been updated.'));
                } catch (Exception $e) {
                    osc_add_flash_message(__('Error: ') . $e->getMessage());
                }
                if( !is_null( $fields['fk_i_parent_id'] ) ) {
                    $this->redirectTo(osc_admin_base_url(true).'?page=categories&parentId=' . $fields['fk_i_parent_id']);
                } else {
                    $this->redirectTo(osc_admin_base_url(true).'?page=categories');
                }
            break;
            
            case 'delete':
                $id = Params::getParam("id");
                try {
                    foreach($id as $i) {
                        if ( intval($i) ) {
                            $this->categoryManager->deleteByPrimaryKey($i);
                        }
                    }
                    osc_add_flash_message(__('The items have been deleted.'));
                } catch (Exception $e) {
                    osc_add_flash_message(__('Error: ') . $e->getMessage());
                }
                $this->redirectTo(osc_admin_base_url(true).'?page=categories');
            break;
            
            case 'enable':
                $id = Params::getParam("id");
                $enabled = Params::getParam("enabled");
                try {
                    if ($id!='') {
                        $this->categoryManager->update(array('b_enabled' => $enabled), array('pk_i_id' => $id));
                        if ($enabled!='') {
                            $msg = __('The category has been enabled.') ;
                        } else {
                            $msg = __('The category has been disabled.') ;
                        }
                    } else {
                        $msg = __('There was a problem with this page. The ID for the category is not set.') ;
                    }
                    osc_add_flash_message($msg) ;
                } catch (Exception $e) {
                    osc_add_flash_message(__('Error: ') . $e->getMessage());
                }
                $this->redirectTo(osc_admin_base_url(true).'?page=categories');
            break;
            
            case 'enable_selected':
                $ids = Params::getParam("id");
                try {
                    if ($ids!='') {
                        foreach ($ids as $id) {
                            //XXX: bad... needs refactoring.
                            $this->categoryManager->update(array('b_enabled' => 1), array('pk_i_id' => $id));
                        }
                    }
                    osc_add_flash_message(__('The categories have been enabled.'));
                } catch (Exception $e) {
                    osc_add_flash_message(__('Error: ') . $e->getMessage());
                }
                $this->redirectTo(osc_admin_base_url(true).'?page=categories');
            break;
            
            case 'disable_selected':
                $ids = Params::getParam("id");
                try {
                    if ($ids!='') {
                        foreach ($ids as $id) {
                            //XXX: bad... needs refactoring.
                            $this->categoryManager->update(array('b_enabled' => 0), array('pk_i_id' => $id));
                        }
                    }
                    osc_add_flash_message(__('Selected categories have been disabled.'));
                } catch (Exception $e) {
                    osc_add_flash_message(__('Error: ') . $e->getMessage());
                }
                $this->redirectTo(osc_admin_base_url(true).'?page=categories');
            break;
            
            default:
                $this->add_global_js('jquery.dataTables.min.js') ;
                $this->add_css('item_list_layout.css') ;
                $this->add_css('tabs.css') ;
                $this->add_global_js('tabber-minimized.js') ;
                $this->add_css('demo_table.css') ;

                $parentId = Params::getParam("parentId");
                if($parentId!='') {
                    $this->_exportVariableToView("categories", $this->categoryManager->listWhere("a.fk_i_parent_id = %d ", $parentId));
                    $this->_exportVariableToView("parent", $this->categoryManager->findByPrimaryKey($parentId));
                } else {
                    $this->_exportVariableToView("categories", $this->categoryManager->listWhere("a.fk_i_parent_id IS NULL"));
                }
                
                $this->doView("categories/index.php");

        }
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }
}

?>
