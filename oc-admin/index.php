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

require_once ABS_PATH . 'config.php';
require_once ABS_PATH . 'common.php';

define('OC_ADMIN', true);

require_once LIB_PATH . 'osclass/web.php';
require_once LIB_PATH . 'osclass/classes/DAO.php';
require_once LIB_PATH . 'osclass/db.php';
require_once LIB_PATH . 'osclass/session.php';
require_once LIB_PATH . 'osclass/locale.php';
require_once LIB_PATH . 'osclass/utils.php';
require_once LIB_PATH . 'osclass/model/Admin.php';
require_once ABS_PATH . 'oc-admin/common.php';

$login = true;

$action = osc_readAction();
switch($action) {
	case 'recover':
		require_once 'recover.php';
		break;
	case 'recover_post':
		require_once LIB_PATH . 'osclass/security.php';
		$user = Admin::newInstance()->findByEmail($_POST['email']);

		if($user) {
			$newPassword = osc_genRandomPassword();
			$body = sprintf( __('Your new password is "%s".'), $newPassword);

			Admin::newInstance()->update(
				array('s_password' => sha1($newPassword)),
				array('pk_i_id' => $user['pk_i_id'])
			);

			$params = array(
				'from_name' => __('OSClass application'),
				'subject' => __('Recover your password'),
				'to' => $user['s_email'],
				'to_name' => __('OSClass administrator'),
				'body' => $body,
				'alt_body' => $body
			);
			osc_sendMail($params);

			osc_addFlashMessage(__('A new password has been sent to your account.'));
		} else {
			osc_addFlashMessage(__('The email you have entered does not belong to a valid administrator.'));
		}

		osc_redirectTo('index.php');
		break;
	case 'login_post':
		define('COOKIE_LIFE', 86400);
		require_once LIB_PATH . 'osclass/security.php';
		$admin = Admin::newInstance()->findByCredentials($_POST['userName'], $_POST['password']);
		if($admin) {
			if(isset($_POST['rememberMe']) && $_POST['rememberMe'] == 1) {
				$life = time() + COOKIE_LIFE;
				$adminSecret = osc_genRandomPassword();
				Admin::newInstance()->update(
					array('s_secret' => $adminSecret),
					array('pk_i_id' => $admin['pk_i_id'])
				);
				setcookie('oc_adminId', $admin['pk_i_id'], $life, '/', $_SERVER['SERVER_NAME']);
				setcookie('oc_adminSecret', $adminSecret, $life, '/', $_SERVER['SERVER_NAME']);
			} else {
				setcookie('oc_adminId', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
				setcookie('oc_adminSecret', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
			}

			$_SESSION['adminId'] = $admin['pk_i_id'];

			// this is an ugly fix, we should do an admin preference or something in the database (AND enable the combobox at login.php)
			if(!isset($_POST['theme'])) { $_POST['theme'] = 'modern'; }
			$_SESSION['adminTheme'] = $_POST['theme'];
			$_SESSION['adminLocale'] = $_POST['locale'];

			$data['s_value'] = $_POST['locale'];
			$condition = array( 's_section' => 'osclass', 's_name' => 'admin_language');
			Preference::newInstance()->update($data, $condition);

			osc_redirectTo('main.php');
		} else {
			osc_addFlashMessage(__('Wrong username or password.'));
		}
	case 'logout':
		unset($_SESSION['adminId']);
		setcookie('oc_adminId', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
		setcookie('oc_adminSecret', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
		osc_redirectTo('index.php');
		break;
	default:
		if(isset($_SESSION['adminId'])) {
			osc_redirectTo('main.php');
		} else
		if(isset($_COOKIE['oc_adminId']) && isset($_COOKIE['oc_adminSecret'])) {
			$admin = Admin::newInstance()->findByIdSecret($_COOKIE['oc_adminId'], $_COOKIE['oc_adminSecret']);
			if($admin) {
				$_SESSION['adminId'] = $_COOKIE['oc_adminId'];
				osc_redirectTo('main.php');
			}
		}

		require_once LIB_PATH . 'osclass/model/Locale.php';
		$locales = Locale::newInstance()->listAllEnabled(true);

		require_once 'login.php';
}

?>
