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


$adminManager = Admin::newInstance();

$action = osc_readAction();
switch($action) {
	case 'add':
		osc_renderAdminSection('admins/add.php', __('Administrators'), __('Add'));
		break;
	case 'add_post':
		$_POST['s_password'] = sha1($_POST['s_password']);
		try {
			$adminManager->insert($_POST);

			osc_addFlashMessage(__('The item has been added.'));
		} catch (DatabaseException $e) {
			osc_addFlashMessage( __('The administrator could not be created.') . ' (' . $e->getMessage() . ')');
		} 
		osc_redirectTo('admins.php');			
		break;
	case 'edit':
		$admin = null;
		if(isset($_GET['id']))
			$adminEdit = $adminManager->findByPrimaryKey ($_GET['id']);
		elseif(isset($_SESSION['adminId']))
			$adminEdit = $adminManager->findByPrimaryKey($_SESSION['adminId']);
		osc_renderAdminSection('admins/edit.php', __('Administrators'), __('Edit'));
		break;
	case 'edit_post':
            $conditions = array('pk_i_id' => $_POST['id']);
            $admin = $adminManager->findByPrimaryKey($_POST['id']);
            unset($_POST['id']);
            if(empty($_POST['s_password'])) {
                unset($_POST['s_password']);
            } else {
                if(sha1($_POST['old_password'])==$admin['s_password']) {
                    if($_POST['s_password']==$_POST['s_password2']) {
                        $_POST['s_password'] = sha1($_POST['s_password']);
                    } else {
                        unset($_POST['s_password']);
                        osc_addFlashMessage(__('Password didn\'t update. Passwords don\'t match.'));
                    }
                } else {
                    unset($_POST['s_password']);
                    osc_addFlashMessage(__('Password didn\'t update. "Old password" didn\'t match with our records in the database.'));
                }
            }
            unset($_POST['old_password']);
            unset($_POST['s_password2']);
        
            try {
                $adminManager->update($_POST, $conditions);
            } catch (DatabaseException $e) {
                osc_addFlashMessage( __('Error: ') . $e->getMessage());
            }
            osc_redirectTo('admins.php');
            break;
	case 'delete':
		$id = osc_paramRequest('id', false);
		if($id) {
			// Verification to avoid an administrator trying to remove to itself
			if(in_array($_SESSION['adminId'], $id)) {
				osc_addFlashMessage( __('The operation was not completed. You were trying to remove yourself!') );
			} else {
				try {
					$adminManager->delete(array('pk_i_id IN (' . implode(', ', $id) . ')'));
				} catch (DatabaseException $e) {
					osc_addFlashMessage( __('Error: ') . $e->getMessage());
				}
			}
		}
		osc_redirectTo('admins.php');
		break;
	default:
		$admins = $adminManager->listAll();

		osc_renderAdminSection('admins/index.php', __('Administrators'));
}