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
                    $this->_exportVariableToView("categories", $this->categoryManager->toTreeAll());
                    $this->_exportVariableToView("languages", OSCLocale::newInstance()->listAllEnabled());
                    $this->_exportVariableToView("category", array());
                    $this->doView("categories/frm.php");
                break;
                case 'add_post':
                    try {
                        // fields contain data of t_category
                        $fields['fk_i_parent_id'] = (Params::getParam("fk_i_parent_id")!='') ? Params::getParam("fk_i_parent_id") : null;
                        $fields['i_expiration_days'] = (Params::getParam("i_expiration_days") != '') ? Params::getParam("i_expiration_days") : 0;
                        $fields['i_position'] = (Params::getParam("i_position") != '') ? Params::getParam("i_position") : 0;
                        $fields['b_enabled'] = (Params::getParam("b_enabled")!='' ) ? 1 : 0;

                        $postParams = Params::getParamsAsArray();
                        foreach ($postParams as $k => $v) {
                            if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                                $aFieldsDescription[$m[1]][$m[2]] = $v;
                            }
                        }
                        $this->categoryManager->insert($fields, $aFieldsDescription);

                        osc_add_flash_ok_message( _m('The category has been added'), 'admin');
                    } catch (Exception $e) {
                        osc_add_flash_error_message( sprintf(_m('The category could\'t be added. Reason: %s'), $e->getMessage()), 'admin');
                    }
                    $this->redirectTo(osc_admin_base_url(true).'?page=categories');
                break;
                case 'edit':
                    $this->_exportVariableToView("category", $this->categoryManager->findByPrimaryKey(Params::getParam("id")));
                    $this->_exportVariableToView("categories", $this->categoryManager->toTreeAll());
                    $this->_exportVariableToView("languages", OSCLocale::newInstance()->listAllEnabled());
                    $this->doView("categories/frm.php");
                break;
                case 'quick_edit':
                    $id = Params::getParam('catId');
                    $name = Params::getParam('s_name');
                    $locale = Params::getParam('locale');
                    Category::newInstance()->update_name($id, $locale, $name);
                    osc_add_flash_ok_message( _m('The category has been updated.'), 'admin');
                    $this->redirectTo(osc_admin_base_url(true).'?page=categories');
                break;
                case 'edit_post':
                    $id = Params::getParam("id");

                    $fields['fk_i_parent_id'] = (Params::getParam("fk_i_parent_id")!='') ? Params::getParam("fk_i_parent_id") : null;
                    $fields['i_expiration_days'] = (Params::getParam("i_expiration_days") != '') ? Params::getParam("i_expiration_days") : 0;
                    $fields['i_position'] = (Params::getParam("i_position") != '') ? Params::getParam("i_position") : 0;
                    $fields['b_enabled'] = (Params::getParam("b_enabled")!='' ) ? 1 : 0;

                    $postParams = Params::getParamsAsArray();
                    foreach ($postParams as $k => $v) {
                        if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                            $aFieldsDescription[$m[1]][$m[2]] = $v;
                        }
                    }

                    try {
                        $this->categoryManager->updateByPrimaryKey($fields, $aFieldsDescription, $id);
                        osc_add_flash_ok_message( _m('The category has been updated.'), 'admin');
                    } catch (Exception $e) {
                        osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin');
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
                        osc_add_flash_ok_message( _m('The categories have been deleted'), 'admin');
                    } catch (Exception $e) {
                        osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin');
                    }
                    $this->redirectTo(osc_admin_base_url(true).'?page=categories');
                break;

                case 'enable':
                    $id = Params::getParam("id");
                    $enabled = (Params::getParam("enabled")!='')?Params::getParam("enabled"):0;
                    try {
                        if ($id!='') {
                            $this->categoryManager->update(array('b_enabled' => $enabled), array('pk_i_id' => $id));
                            if ($enabled==1) {
                                $msg = _m('The category has been enabled') ;
                            } else {
                                $msg = _m('The category has been disabled') ;
                            }
                        } else {
                            $msg = _m('There was a problem with this page. The ID for the category hasn\'t been set') ;
                        }
                        osc_add_flash_ok_message($msg, 'admin') ;
                    } catch (Exception $e) {
                        osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin');
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
                        osc_add_flash_ok_message( _m('The categories have been enabled'), 'admin');
                    } catch (Exception $e) {
                        osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin');
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
                        osc_add_flash_ok_message( _m('The selected categories have been disabled'), 'admin');
                    } catch (Exception $e) {
                        osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin');
                    }
                    $this->redirectTo(osc_admin_base_url(true).'?page=categories');
                break;

                default:

                    $parentId = Params::getParam("parentId");
                    if($parentId != '') {
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
            osc_current_admin_theme_path($file) ;
        }
    }

?>