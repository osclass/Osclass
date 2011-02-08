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

$userManager = User::newInstance();

$action = Params::getParam('action');
switch($action) {
    case 'create':
        $user = null;

        $countries = Country::newInstance()->listAll();
        $regions = Region::newInstance()->getByCountry($countries[0]['pk_c_code']);
        $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$regions[0]['pk_i_id']) ;
	$locales = Locale::newInstance()->listAllEnabled();
        
        osc_renderAdminSection('users/frm.php', __('Users'), __('Add'));
        break;
    case 'create_post':
        require_once LIB_PATH . 'osclass/users.php';

        switch($success) {
            case 0:
                break;
                
            case 1:
                osc_addFlashMessage(__('The account has been created. An activation email has been sent to the user\'s email address.'));
                break;
                
            case 2:
                osc_addFlashMessage(__('The account has been created and it was activated.'));
                break;
                
            case 3:
                osc_addFlashMessage(__('Sorry, but that email is already in use. Did you forget your password?'));
                break;
                
            case 4:
                osc_addFlashMessage(__('The user could not be registered, sorry.'));
                break;
                
            default:
                break;
        }

        osc_redirectTo('users.php');
        break;

    case 'edit':
        $user = $userManager->findByPrimaryKey($_GET['id']);
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
	$locales = Locale::newInstance()->listAllEnabled();
        osc_renderAdminSection('users/frm.php', __('Users'), __('Edit'));
        break;

    case 'edit_post':
        $userId = $_POST['id'];
	
        require_once LIB_PATH . 'osclass/users.php';

        if(!isset($_POST['b_enabled']) || $_POST['b_enabled']!=1) {
            $manager->update(array('b_enabled' => 0), array('pk_i_id' => $userId));
        } else {
            $manager->update(array('b_enabled' => 1), array('pk_i_id' => $userId));
        }
        
        if($success==0) {
            osc_addFlashMessage(__('This should never happened.'));
        } else if($success==1) {
            osc_addFlashMessage(__('Passwords don\'t match.'));
        } else {
            osc_addFlashMessage(__('The user has been updated.'));
        }
			
        osc_redirectTo('users.php');
        break;

    case 'activate':
        foreach($_REQUEST['id'] as $id) {
            $conditions = array('pk_i_id' => $id);
            $values = array('b_enabled' => 1);
            try {
                $userManager->update($values, $conditions);
                osc_addFlashMessage(__('The user has been activated.'));
            } catch (Exception $e) {
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
            } catch (Exception $e) {
                osc_addFlashMessage(__('Error: ') . $e->getMessage());
            }
        }
        osc_redirectTo('users.php');
        break;
    case 'delete':
        foreach($_REQUEST['id'] as $id) {
            $userManager->deleteByID($id);
        }

        osc_redirectTo('users.php');
        break;
    default:
        $users = $userManager->listAll();

        osc_renderAdminSection('users/index.php', __('Users'));
}

?>
