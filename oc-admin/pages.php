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

require_once 'oc-load.php';


$pageManager = Page::newInstance();

$action = osc_readAction();
switch($action) {
	case 'edit':
		if(isset($_REQUEST['id'])) {
			$page = $pageManager->findByIDObject($_REQUEST['id']);
			osc_renderAdminSection('pages/frm.php', __('Pages'), __('Edit'));
		} else {
			osc_redirectTo('pages.php');
		}
		break;
	case 'edit_post':
		try {
            if(isset($_REQUEST['autosave']) && $_REQUEST['autosave']=='yes') {
                $pageManager->insertDraft($_REQUEST['id'], $_REQUEST['s_internal_name']);
                $data = array();
                foreach($_REQUEST as $k => $v) {
                    if(preg_match('|(.+?)#(.+)|', $k, $m)) {
                        $data[$m[1]][$m[2]] = $v;
                    }
                }
                foreach($data as $k => $_data) {
                    $pageManager->insertDescriptionDraft($_REQUEST['id'], $k, $_data['s_title'], $_data['s_text']);
                }
                die;
            } else { 
                //$pageManager->updateInternalName($_REQUEST['id'], $_REQUEST['s_internal_name']);
			    $data = array();
			    foreach($_REQUEST as $k => $v) {
				    if(preg_match('|(.+?)#(.+)|', $k, $m)) {
					    $data[$m[1]][$m[2]] = $v;
				    }
			    }
			    foreach($data as $k => $_data) {
				    $pageManager->updateLocaleForce($_REQUEST['id'], $k, $_data['s_title'], $_data['s_text']);
			    }
                $pageManager->deleteDraft($_REQUEST['id']);
			    osc_addFlashMessage( __('The page has been updated.') );
            }
		} catch (Exception $e) {
			osc_addFlashMessage( __('Error: ') . $e->getMessage() );
		}
		osc_redirectTo('pages.php');
	case 'delete':
		$id = osc_paramRequest('id', false);
        if(!is_array($id)) {
		    try {
			    if($id) {
				    if($pageManager->deleteByID($id)) {
					    osc_addFlashMessage(__('The item have been deleted.'));
					    osc_redirectTo('pages.php');
				    } else {
					    osc_addFlashMessage( __('Error, this page can not be deleted.'));
					    osc_redirectTo('pages.php');
				    }
			    }
			    osc_addFlashMessage(__('The item have been deleted.'));
		    } catch (Exception $e) {
			    osc_addFlashMessage( __('Error: ') . $e->getMessage());
		    }
        } else {
					    

			    foreach($id as $_id) {
					    osc_addFlashMessage(print_r($_id));
				    if($pageManager->deleteByID($_id)) {
					    osc_addFlashMessage(__('The item have been deleted.'));
				    } else {
					    osc_addFlashMessage( __('Error, this page can not be deleted.'));
				    }
    			    osc_addFlashMessage(__('The item have been deleted.'));
                }
        }
		osc_redirectTo('pages.php');
		break;
	case 'add':
        //$page = $pageManager->insert('');
        $page['s_internal_name'] = '';
		osc_renderAdminSection('pages/frm.php', __('Pages'), __('Add'));
		break;
	case 'add_post':

		try {
            if(isset($_REQUEST['autosave']) && $_REQUEST['autosave']=='yes') {
                $pageManager->insertDraft($_REQUEST['id'], $_REQUEST['s_internal_name']);
                $data = array();
                foreach($_REQUEST as $k => $v) {
                    if(preg_match('|(.+?)#(.+)|', $k, $m)) {
                        $data[$m[1]][$m[2]] = $v;
                    }
                }
                foreach($data as $k => $_data) {
                    $pageManager->insertDescriptionDraft($_REQUEST['id'], $k, $_data['s_title'], $_data['s_text']);
                }
                die;
            } else {

			    if(isset($_REQUEST['s_internal_name'])) { $intName = $_REQUEST['s_internal_name']; } else { $intName = ""; };
                $data = $pageManager->insert($intName);
                if(isset($data['pk_i_id']) && $data['pk_i_id']!="") {
                    $id = $data['pk_i_id'];
                    $data = array();
			        foreach($_REQUEST as $k => $v) {
                        if(preg_match('|(.+?)#(.+)|', $k, $m)) {
                            $data[$m[1]][$m[2]] = $v;
                        }
                    }
                    foreach($data as $k => $_data) {
                        $pageManager->updateLocaleForce($id, $k, $_data['s_title'], $_data['s_text']);
                    }
                    osc_addFlashMessage(__('The item has been added.'));
                } else {
                    osc_addFlashMessage(__('Ops! That internal name is already in use. We couldn\'t made the changes.'));
                }
            }
		} catch (Exception $e) {
			osc_addFlashMessage(__('Error: ') . $e->getMessage());
		}
	default:
		$prefLocale = Preference::newInstance()->findValueByName('language');
		$pages = $pageManager->listAllObject();
		osc_renderAdminSection('pages/index.php', __('Pages'));
}

?>
