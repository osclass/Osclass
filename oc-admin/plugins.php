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

class CAdminPlugins extends AdminSecBaseModel
{
    //specific for this class
    private $pageManager ;
    
    function __construct() {
        parent::__construct() ;

        //specific things for this class
    }

    //Business Layer...
    function doModel() {
        parent::doModel() ;

        //specific things for this class
        switch ($this->action)
        {

	        case 'add':
		        $this->doView("plugins/add.php");
		        break;
	        case 'add_post':
	            $package = Params::getFiles("package");
		        $path = osc_plugins_path() ;//. pathinfo($package['name'], PATHINFO_FILENAME);
		        osc_packageExtract($package['tmp_name'], $path);
		        $this->redirectTo(osc_admin_base_url(true)."?page=plugins");
		        break;
	        case 'install':
		        $pn = Params::getParam("plugin");

		        Plugins::activate($pn);
		        //run this after installing the plugin
		        Plugins::runHook('install_'.$pn) ;
		        

                osc_add_flash_message(__('Plugin installed'));
		        $this->redirectTo(osc_admin_base_url(true)."?page=plugins");
		        break;
	        case 'uninstall':
		        $pn = Params::getParam("plugin");

                Plugins::runHook($pn.'_uninstall') ;
		        Plugins::deactivate($pn);

                osc_add_flash_message(__('Plugin uninstalled'));
		        $this->redirectTo(osc_admin_base_url(true)."?page=plugins");
		        break;
	        case 'admin':
		        global $active_plugins;
		        $plugin = Params::getParam("plugin");
		        if($plugin!="") {
			        Plugins::runHook($plugin.'_configure');
		        }
		        break;
	        case 'admin_post':
		        Plugins::runHook('admin_post');

	        case 'renderplugin':
		        global $active_plugins;
		        $file = Params::getParam("file");
		        if($file!="") {
			        // We pass the GET variables (in case we have somes)
			        if(preg_match('|(.+?)\?(.*)|', $_REQUEST['file'], $match)) {
				        $file = $match[1];
				        if(preg_match_all('|&([^=]+)=([^&]*)|', urldecode('&'.$match[2].'&'), $get_vars)) {
					        for($var_k=0;$var_k<count($get_vars[1]);$var_k++) {
						        //$_GET[$get_vars[1][$var_k]] = $get_vars[2][$var_k];
						        //$_REQUEST[$get_vars[1][$var_k]] = $get_vars[2][$var_k];
						        Params::setParam($get_vars[1][$var_k], $get_vars[2][$var_k]);
					        }
				        }
			        } else {
				        $file = $_REQUEST['file'];
			        };
			        $this->_exportVariableToView("file", osc_base_path().'oc-content/plugins/'.$file);
			        //osc_renderPluginView($file);
			        $this->doView("plugins/view.php");
		        }
		        break;

	        case 'configure':
	            $plugin = Params::getParam("plugin");
		        if($plugin!='') {
    		        $plugin_data = Plugins::getInfo($plugin);
    		        $this->_exportVariableToView("categories", Category::newInstance()->toTreeAll());
    		        $this->_exportVariableToView("selected", PluginCategory::newInstance()->listSelected($plugin_data['short_name']));
			        $this->_exportVariableToView("plugin_data", $plugin_data);
			        $this->doView("plugins/configuration.php");
		        } else {
    		        $this->redirectTo(osc_admin_base_url(true)."?page=plugins");
		        }
		        break;
	        case 'configure_post':
	            $plugin_short_name = Params::getParam("plugin_short_name");
	            $categories = Params::getParam("categories");
		        if($plugin_short_name!="") {
			        Plugins::cleanCategoryFromPlugin($plugin_short_name);
			        if(isset($categories)) {
				        Plugins::addToCategoryPlugin($categories, $plugin_short_name);
			        }
		        } else {
			        osc_add_flash_message(__('No plugin selected'));
			        $this->doView("plugins/index.php");
		        }
		        osc_add_flash_message(__('Configuration was saved'));
		        $this->redirectTo(osc_admin_base_url(true)."?page=plugins");
		        break;
	        default:
                $this->add_global_js('jquery.dataTables.min.js') ;
                $this->add_css('item_list_layout.css') ;
                $this->add_css('demo_table.css') ;
                $this->_exportVariableToView("plugins", Plugins::listAll());
		        $this->doView("plugins/index.php");
        }
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }
}

?>
