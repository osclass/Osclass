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

define('ABS_PATH', dirname(dirname(__FILE__)) . '/');

require_once ABS_PATH . 'oc-admin/oc-load.php';

$pageManager = new Page();

$action = osc_readAction();
switch($action) {
    case 'edit':
        if(!isset($_REQUEST['id'])) {
            osc_redirectTo('pages.php');
        }
        $page = $pageManager->findByPrimaryKey($_REQUEST['id']);
        osc_renderAdminSection('pages/frm.php', __('Pages'), __('Edit'));
        break;
    case 'edit_post':
        $id = osc_paramRequest('id', false);
        $s_internal_name = osc_paramRequest('s_internal_name', '');
        
        $aFieldsDescription = array();
        foreach($_REQUEST as $k => $v) {
            if(preg_match('|(.+?)#(.+)|', $k, $m)) {
                $aFieldsDescription[$m[1]][$m[2]] = $v;
            }
        }

        foreach($aFieldsDescription as $k => $_data) {
            $pageManager->updateDescription($_REQUEST['id'], $k, $_data['s_title'], $_data['s_text']);
        }
        
        if(!pageInternalNameExists($id, $s_internal_name)) {
            if(!pageIsIndelible($id)) {
                $pageManager->updateInternalName($id, $s_internal_name);
            }
            osc_addFlashMessage( __('The page has been updated.'), 'admin' );
            osc_redirectTo('pages.php');
        }
        osc_addFlashMessage(__('You couldn\'t repeat internal name.'), 'admin');
        osc_redirectTo( 'pages.php?action=edit&id=' . $id);
        break;
    case 'delete':
        $id = osc_paramRequest('id', false);
        $page_deleted_correcty = 0;
        $page_deleted_error = 0;
        $page_indelible = 0;
        
        if(!is_array($id)) {
            $id = array($id);
        }
        
        foreach($id as $_id) {
            $result = pageDeleteById($_id);
            switch ($result) {
                case -1:
                    $page_indelible++;
                    break;
                case 0:
                    $page_deleted_error++;
                    break;
                case 1:
                    $page_deleted_correcty++;
            }
        }

        if($page_indelible > 0) {
            if($page_indelible == 1) {
                osc_addFlashMessage(__('1 page couldn\'t be deleted because it is indelible.'), 'admin');
            } else {
                osc_addFlashMessage($page_indelible . ' ' .__('pages couldn\'t be deleted because are indelible.'), 'admin');
            }
        }
        if($page_deleted_error > 0) {
            if($page_deleted_error == 1) {
                osc_addFlashMessage(__('1 page couldn\'t be deleted.'), 'admin');
            } else {
                osc_addFlashMessage($page_deleted_error . ' ' .__('pages couldn\'t be deleted.'), 'admin');
            }
        }
        if($page_deleted_correcty > 0) {
            if($page_deleted_correcty == 1) {
                osc_addFlashMessage(__('1 page has been deleted correctly.'), 'admin');
            } else {
                osc_addFlashMessage($page_deleted_correcty . ' ' .__('pages have been deleted correctly.'), 'admin');
            }
        }
        osc_redirectTo('pages.php');
        break;
    case 'add':
        $page['s_internal_name'] = '';
        osc_renderAdminSection('pages/frm.php', __('Pages'), __('Add'));
        break;
    case 'add_post':
        if(!isset($_REQUEST['s_internal_name'])) {
            osc_addFlashMessage(__('You have to put some internal name.'), 'admin');
        }

        $page = $pageManager->findByInternalName($_REQUEST['s_internal_name']);
        if(!isset($page['pk_i_id'])) {
            $aFields = array('s_internal_name' => $_REQUEST['s_internal_name'], 'b_indelible' => '0');
            $aFieldsDescription = array();
            foreach($_REQUEST as $k => $v) {
                if(preg_match('|(.+?)#(.+)|', $k, $m)) {
                    $aFieldsDescription[$m[1]][$m[2]] = $v;
                }
            }

            $result = $pageManager->insert($aFields, $aFieldsDescription);
            osc_addFlashMessage(__('The page has been added.'), 'admin');
        } else {
            osc_addFlashMessage(__('Ops! That internal name is already in use. We couldn\'t made the changes.'), 'admin');
        }
    default:
        $prefLocale = null;
        if(!isset($_SESSION['adminLocale'])) {
            $prefLocale = Preference::newInstance()->findValueByName('language');
        } else {
            $prefLocale = $_SESSION['adminLocale'];
        }
        $pages = $pageManager->listAll();
        osc_renderAdminSection('pages/index.php', __('Pages'));
}

?>
