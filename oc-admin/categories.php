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

define('ABS_PATH', dirname(dirname(__FILE__)) . '/');

require_once ABS_PATH . 'oc-admin/oc-load.php';

$_P = Preference::newInstance() ;

$categoryManager = Category::newInstance();
$categories = $categoryManager->toTreeAll();

$action = osc_readAction();
switch ($action) 
{
    case 'add':
        $languages = Locale::newInstance()->listAllEnabled();
        $category = array();
        osc_renderAdminSection('categories/frm.php', __('Categories'), __('Add'));
    break;
    case 'add_post':
        try {
            // fields contain data of t_category
            $fields['fk_i_parent_id'] = (!empty($_POST['fk_i_parent_id']) ) ? $_POST['fk_i_parent_id'] : null;
            $fields['i_expiration_days'] = $_POST['i_expiration_days'];
            $fields['i_position'] = $_POST['i_position'];
            $fields['b_enabled'] = (!empty($_POST['b_enabled']) ) ? 1 : 0;

            foreach ($_POST as $k => $v) {
                if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                    $aFieldsDescription[$m[1]][$m[2]] = $v;
                }
            }
            $categoryManager->insert($fields, $aFieldsDescription);

            osc_addFlashMessage(__('The category has been added.'));
        } catch (Exception $e) {
            osc_addFlashMessage(__('Error: ') . $e->getMessage());
        }
        osc_redirectTo('categories.php');
    break;
    case 'edit':
        $languages = Locale::newInstance()->listAllEnabled();
        $category = $categoryManager->findByPrimaryKey($_GET['id'], false);
        osc_renderAdminSection('categories/frm.php', __('Categories'), __('Edit'));
    break;
    case 'edit_post':
        $id = $_POST['id'];
        
        $fields['fk_i_parent_id'] = (!empty($_POST['fk_i_parent_id']) ) ? $_POST['fk_i_parent_id'] : null;
        $fields['i_expiration_days'] = $_POST['i_expiration_days'];
        $fields['i_position'] = $_POST['i_position'];
        $fields['b_enabled'] = (!empty($_POST['b_enabled']) ) ? 1 : 0;

        foreach ($_POST as $k => $v) {
            if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                $aFieldsDescription[$m[1]][$m[2]] = $v;
            }
        }
        
        try {
            $categoryManager->updateByPrimaryKey($fields, $aFieldsDescription, $id);
            osc_addFlashMessage(__('The item has been updated.'));
        } catch (Exception $e) {
            osc_addFlashMessage(__('Error: ') . $e->getMessage());
        }
        if( !is_null( $fields['fk_i_parent_id'] ) ) {
            osc_redirectTo( 'categories.php?parentId=' . $fields['fk_i_parent_id'] ) ;
        } else {
            osc_redirectTo( 'categories.php?' ) ;
        }
    break;
    case 'delete':
        $id = osc_paramRequest('id', false);
        try {
            foreach($id as $i) {
                if ( intval($i) ) {
                    $categoryManager->deleteByPrimaryKey($i);
                }
            }
            osc_addFlashMessage(__('The items have been deleted.'));
        } catch (Exception $e) {
            osc_addFlashMessage(__('Error: ') . $e->getMessage());
        }
        osc_redirectTo('categories.php');
    break;
    case 'enable':
        $id = osc_paramRequest('id', false);
        $enabled = osc_paramRequest('enabled', false);
        try {
            if ($id) {
                $categoryManager->update(array('b_enabled' => $enabled), array('pk_i_id' => $id));
                if ($enabled) {
                    $msg = __('The category has been enabled.') ;
                } else {
                    $msg = __('The category has been disabled.') ;
                }
            } else {
                $msg = __('There was a problem with this page. The ID for the category is not set.') ;
            }
            osc_addFlashMessage($msg) ;
        } catch (Exception $e) {
            osc_addFlashMessage(__('Error: ') . $e->getMessage());
        }
        osc_redirectTo('categories.php');
    break;
    case 'enable_selected':
        $ids = osc_paramRequest('id', false);
        try {
            if ($ids) {
                foreach ($ids as $id) {
                    //XXX: bad... needs refactoring.
                    $categoryManager->update(array('b_enabled' => 1), array('pk_i_id' => $id));
                }
            }
            osc_addFlashMessage(__('The categories have been enabled.'));
        } catch (Exception $e) {
            osc_addFlashMessage(__('Error: ') . $e->getMessage());
        }
        osc_redirectTo('categories.php');
    break;
    case 'disable_selected':
        $ids = osc_paramRequest('id', false);
        try {
            if ($ids) {
                foreach ($ids as $id) {
                    //XXX: bad... needs refactoring.
                    $categoryManager->update(array('b_enabled' => 0), array('pk_i_id' => $id));
                }
            }
            osc_addFlashMessage(__('Selected categories have been disabled.'));
        } catch (Exception $e) {
            osc_addFlashMessage(__('Error: ') . $e->getMessage());
        }
        osc_redirectTo('categories.php');
    break;
    default:
        $parent = null;
        $parentId = null;

        if (isset($_GET['parentId']) && !empty($_GET['parentId'])) {
            $parentId = $_GET['parentId'];
        }
        
        if (!is_null($parentId)) {
            $categories = $categoryManager->listWhere('a.fk_i_parent_id = %d ', $_GET['parentId']);
            $parent = $categoryManager->findByPrimaryKey($_GET['parentId']);
        } else {
            $categories = $categoryManager->listWhere("a.fk_i_parent_id IS NULL");// GROUP BY pk_i_id");
        }

        osc_renderAdminSection('categories/index.php', __('Categories'));
}

?>
