<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class CAdminPlugins extends AdminSecBaseModel
    {
        function __construct()
        {
            parent::__construct() ;
            //specific things for this class
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel() ;

            //specific things for this class
            switch ($this->action)
            {
                case 'add':
                    $this->doView("plugins/add.php");
                    break;
                case 'add_post':
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action cannot be done because is a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=plugins');
                    }
                    $package = Params::getFiles("package");
                    if(isset($package['size']) && $package['size']!=0) {
                        $path = osc_plugins_path() ;
                        (int) $status = osc_unzip_file($package['tmp_name'], $path);
                    } else {
                        $status = 3;
                    }
                    switch ($status) {
                        case(0):   $msg = _m('The plugin folder is not writable');
                                    osc_add_flash_error_message($msg, 'admin');
                        break;
                        case(1):   $msg = _m('The plugin has been uploaded correctly');
                                   osc_add_flash_ok_message($msg, 'admin');
                        break;
                        case(2):   $msg = _m('The zip file is not valid');
                                   osc_add_flash_error_message($msg, 'admin');
                        break;
                        case(3):   $msg = _m('No file was uploaded');
                                   osc_add_flash_error_message($msg, 'admin');
                                   $this->redirectTo(osc_admin_base_url(true)."?page=plugins&action=add");
                        break;
                        case(-1):
                        default:   $msg = _m('There was a problem adding the plugin');
                                   osc_add_flash_error_message($msg, 'admin');
                        break;
                    }

                    $this->redirectTo(osc_admin_base_url(true)."?page=plugins");
                    break;
                case 'install':
                    $pn = Params::getParam("plugin");

                    // CATCH FATAL ERRORS
                    $old_value = error_reporting(0);
                    register_shutdown_function(array($this, 'errorHandler'), $pn, 'install');
                    $installed = Plugins::install($pn);
                    
                    if($installed) {
                        //run this after installing the plugin
                        Plugins::runHook('install_'.$pn) ;
                        osc_add_flash_ok_message( _m('Plugin installed'), 'admin');
                    } else {
                        osc_add_flash_error_message( _m('Error: Plugin already installed'), 'admin') ;
                    }
                    error_reporting($old_value);            

                    $this->redirectTo(osc_admin_base_url(true)."?page=plugins");
                    break;
                case 'uninstall':
                    $pn = Params::getParam("plugin");

                    Plugins::runHook($pn.'_uninstall') ;
                    Plugins::uninstall($pn);

                    osc_add_flash_ok_message( _m('Plugin uninstalled'), 'admin');
                    $this->redirectTo(osc_admin_base_url(true)."?page=plugins");
                    break;
                case 'enable':
                    $pn = Params::getParam("plugin");

                    // CATCH FATAL ERRORS
                    $old_value = error_reporting(0);
                    register_shutdown_function(array($this, 'errorHandler'), $pn, 'enable');
                    $enabled = Plugins::activate($pn);
                    
                    if($enabled) {
                        Plugins::runHook($pn.'_enable') ;
                        osc_add_flash_ok_message( _m('Plugin enabled'), 'admin');
                    } else {
                        osc_add_flash_error_message( _m('Error: Plugin already enabled'), 'admin') ;
                    }
                    error_reporting($old_value);            

                    $this->redirectTo(osc_admin_base_url(true)."?page=plugins");
                    break;
                case 'disable':
                    $pn = Params::getParam("plugin");

                    Plugins::runHook($pn.'_disable') ;
                    Plugins::deactivate($pn);

                    osc_add_flash_ok_message( _m('Plugin disabled'), 'admin');
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
                        if(preg_match('|(.+?)\?(.*)|', $file, $match)) {
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
                        $this->_exportVariableToView("file", osc_plugins_path() . $file);
                        //osc_renderPluginView($file);
                        $this->doView("plugins/view.php");
                    }
                    break;

                case 'render':
                    $file = Params::getParam("file");
                    if($file!="") {
                        // We pass the GET variables (in case we have somes)
                        if(preg_match('|(.+?)\?(.*)|', $file, $match)) {
                            $file = $match[1];
                            if(preg_match_all('|&([^=]+)=([^&]*)|', urldecode('&'.$match[2].'&'), $get_vars)) {
                                for($var_k=0;$var_k<count($get_vars[1]);$var_k++) {
                                    Params::setParam($get_vars[1][$var_k], $get_vars[2][$var_k]);
                                }
                            }
                        } else {
                            $file = $_REQUEST['file'];
                        };
                        $this->_exportVariableToView("file", ABS_PATH . $file);
                        $this->doView("theme/view.php");
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
                        osc_add_flash_error_message( _m('No plugin selected'), 'admin');
                        $this->doView("plugins/index.php");
                    }
                    osc_add_flash_ok_message( _m('Configuration was saved'), 'admin');
                    $this->redirectTo(osc_admin_base_url(true)."?page=plugins");
                    break;
                default:
                    $this->_exportVariableToView("plugins", Plugins::listAll());
                    $this->doView("plugins/index.php");
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_current_admin_theme_path($file) ;
            Session::newInstance()->_clearVariables();
        }

        function errorHandler($pn, $action)
        {
            if( false === is_null($aError = error_get_last()) ) {
                Plugins::deactivate($pn);
                if($action=='install') {
                    Plugins::uninstall($pn);
                }
                osc_add_flash_error_message( sprintf(_m('There was a fatal error and the plugin was not installed.<br />Error: "%s" Line: %s<br/>File: %s'), $aError['message'], $aError['line'], $aError['file']), 'admin');
                $this->redirectTo(osc_admin_base_url(true)."?page=plugins");
            }
        }
        
    }


?>