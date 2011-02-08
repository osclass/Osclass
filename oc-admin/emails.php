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

$action = Params::getParam('action');
switch($action) {
    case 'edit':
        if(!isset($_REQUEST['id'])) {
            osc_redirectTo('emails.php');
        }
        $page = $pageManager->findByPrimaryKey($_REQUEST['id']);
        osc_renderAdminSection('emails/frm.php', __('Emails & Alerts'), __('Edit'));
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
            osc_addFlashMessage( __('The email/alert has been updated.'), 'admin' );
            osc_redirectTo('emails.php');
        }
        osc_addFlashMessage(__('You couldn\'t repeat internal name.'), 'admin');
        osc_redirectTo( 'emails.php?action=edit&id=' . $id);
        break;
    default:
        $prefLocale = null;
        if(!isset($_SESSION['adminLocale'])) {
            $prefLocale = osc_language() ;
        } else {
            $prefLocale = $_SESSION['adminLocale'];
        }
        $pages = $pageManager->listAll(1);
        osc_renderAdminSection('emails/index.php', __('Emails & Alerts'));
}

?>