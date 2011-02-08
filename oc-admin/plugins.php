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

$action = Params::getParam('action');
switch($action) {
	case 'add':
		osc_renderAdminSection('plugins/add.php', __('Plugins'), __('Upload'));
		break;
	case 'add_post':
		$path = PLUGINS_PATH . pathinfo($_FILES['package']['name'], PATHINFO_FILENAME);
		osc_packageExtract($_FILES['package']['tmp_name'], $path);
		osc_redirectTo('plugins.php');
		break;
	case 'install':
		$pn = $_GET['plugin'];

		osc_activatePlugin($pn);
		//Re-load the plugins
		osc_loadActivePlugins();
		//run this after installing the plugin
		osc_run_hooks('install_'.$pn) ;

        osc_addFlashMessage(__('Plugin installed.'));
		osc_redirectTo('plugins.php') ;
		break;
	case 'uninstall':
		$pn = $_GET['plugin'];

		osc_deactivatePlugin($pn);
		osc_run_hooks($pn.'_uninstall') ;
		//Re-load the plugins
		osc_loadActivePlugins();

        osc_addFlashMessage(__('Plugin uninstalled.'));
		osc_redirectTo('plugins.php');
		break;
	case 'admin':
		global $active_plugins;
		if(isset($_GET['plugin']) && $_GET['plugin']!="") {
			osc_run_hook($_GET['plugin'].'_configure');
		}
		break;
	case 'admin_post':
		osc_run_hook('admin_post');

	case 'renderplugin':
		global $active_plugins;
		if(isset($_REQUEST['file']) && $_REQUEST['file']!="") {
			// We pass the GET variables (in case we have somes)
			if(preg_match('|(.+?)\?(.*)|', $_REQUEST['file'], $match)) {
				$file = $match[1];
				if(preg_match_all('|&([^=]+)=([^&]*)|', urldecode('&'.$match[2].'&'), $get_vars)) {
					for($var_k=0;$var_k<count($get_vars[1]);$var_k++) {
						$_GET[$get_vars[1][$var_k]] = $get_vars[2][$var_k];
						$_REQUEST[$get_vars[1][$var_k]] = $get_vars[2][$var_k];
					}
				}
			} else {
				$file = $_REQUEST['file'];
			};
			$file = '../oc-content/plugins/'.$file;
			osc_renderPluginView($file);
		}
		break;

	case 'configure':
		if(isset($_REQUEST['plugin']) && $_REQUEST['plugin']!="") {
			$plugin_data = osc_getPluginInfo($_REQUEST['plugin']);
			osc_renderAdminSection('plugins/configuration.php', __('Plugins'), __('Configuration'));
		} else {
			osc_renderAdminSection('plugins/index.php', __('Plugins'));
		}
		break;
	case 'configure_post':
		if(isset($_REQUEST['plugin_short_name']) && $_REQUEST['plugin_short_name']!="") {
			osc_cleanCategoryFromPlugin($_REQUEST['plugin_short_name']);
			if(isset($_POST['categories'])) {
				osc_addToCategoryPlugin($_POST['categories'], $_REQUEST['plugin_short_name']);
			}
		} else {
			osc_addFlashMessage(__('No plugin selected'));
			osc_renderAdminSection('plugins/index.php', __('Plugins'));
		}
		osc_addFlashMessage(__('Configuration was saved'));
		osc_redirectTo('plugins.php');
		break;
	default:
		osc_renderAdminSection('plugins/index.php', __('Plugins'));
}

?>
