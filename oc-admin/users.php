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


$userManager = User::newInstance();

$action = osc_readAction();
switch($action) {
	case 'create':
		osc_renderAdminSection('users/add.php', __('Users'), __('Add'));
		break;
	case 'create_post':
		$_POST['s_password'] = sha1($_POST['s_password']);
		try {
			$userManager->insert($_POST);
			osc_addFlashMessage(__('The item has been added.'));
		} catch (DatabaseException $e) {
			osc_addFlashMessage(__('Error: ') . $e->getMessage());
		}
		osc_redirectTo('users.php');
		break;
	case 'edit':
		$user = $userManager->findByPrimaryKey($_GET['id']);
		osc_renderAdminSection('users/edit.php', __('Users'), __('Edit'));
		break;
	case 'edit_post':
		$conditions = array('pk_i_id' => $_POST['id']);
		unset($_POST['id']);

        if(!isset($_POST['b_enabled']) || $_POST['b_enabled']!=1) {
            $_POST['b_enabled'] = 0;
        }

		if(empty($_POST['s_password']))
			unset($_POST['s_password']);
		else
			$_POST['s_password'] = sha1($_POST['s_password']);
		try {
			$userManager->update($_POST, $conditions);
			osc_addFlashMessage(__('The user has been updated.'));
		} catch (DatabaseException $e) {
			osc_addFlashMessage(__('Error: ') . $e->getMessage());
		}
		osc_redirectTo('users.php');
		break;
	case 'activate':
        foreach($_REQUEST['id'] as $id) {
		    $conditions = array('pk_i_id' => $id);
            $values = array('b_enabled' => 1);
		    try {
			    $userManager->update($values, $conditions);
			    osc_addFlashMessage(__('The user has been deactivated.'));
		    } catch (DatabaseException $e) {
			    osc_addFlashMessage(__('Error: ') . $e->getMessage());
		    }
        }
        osc_redirectTo('users.php');
		break;
	case 'deactivate':
        foreach($_REQUEST['id'] as $id) {
		    $conditions = array('pk_i_id' => $id);
            $values = array('b_enabled' => 0);
		    try {
			    $userManager->update($values, $conditions);
			    osc_addFlashMessage(__('The user has been deactivated.'));
		    } catch (DatabaseException $e) {
			    osc_addFlashMessage(__('Error: ') . $e->getMessage());
		    }
        }
        osc_redirectTo('users.php');
		break;
	case 'delete':
		foreach($_REQUEST['id'] as $id)
			$userManager->deleteByID($id);
		osc_redirectTo('users.php');
		break;
	default:
		$users = $userManager->listAll();

		osc_renderAdminSection('users/index.php', __('Users'));
}

?>
