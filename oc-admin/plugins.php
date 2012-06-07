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

        // Business layer...
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
                        osc_add_flash_warning_message( _m("This action cannot be done because it is a demo site"), 'admin');
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
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action cannot be done because it is a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=plugins');
                    }
                    $pn = Params::getParam('plugin') ;

                    // set header just in case it's triggered some fatal error
                    header("Location: " . osc_admin_base_url(true) . "?page=plugins&error=" . $pn, true, '302') ;

                    $installed = Plugins::install($pn) ;
                    if( is_array($installed) ) {
                        switch($installed['error_code']) {
                            case('error_output'):
                                osc_add_flash_error_message( sprintf( _m('The plugin generated %d characters of <strong>unexpected output</strong> during the installation'), strlen($installed['output']) ), 'admin') ;
                            break;
                            case('error_installed'):
                                osc_add_flash_error_message( _m('Plugin is already installed'), 'admin') ;
                            break;
                            case('error_file'):
                                osc_add_flash_error_message( _m("Plugin couldn't be installed because their files are missing"), 'admin') ;
                            break;
                            case('custom_error'):
                                osc_add_flash_error_message( sprintf(_m("Plugin couldn't be installed because of: %s"), $installed['msg']), 'admin') ;
                            break;
                            default:
                                osc_add_flash_error_message( _m("Plugin couldn't be installed"), 'admin') ;
                            break;
                        }
                    } else {
                        osc_add_flash_ok_message( _m('Plugin installed'), 'admin') ;
                    }

                    $this->redirectTo(osc_admin_base_url(true) . '?page=plugins') ;
                    break;
                case 'uninstall':
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action cannot be done because it is a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=plugins');
                    }

                    if( Plugins::uninstall(Params::getParam("plugin")) ) {
                        osc_add_flash_ok_message( _m('Plugin uninstalled'), 'admin') ;
                    } else {
                        osc_add_flash_error_message( _m("Plugin couldn't be uninstalled"), 'admin') ;
                    }

                    $this->redirectTo(osc_admin_base_url(true) . '?page=plugins') ;
                    break;
                case 'enable':
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action cannot be done because it is a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=plugins');
                    }

                    if( Plugins::activate(Params::getParam('plugin')) ) {
                        osc_add_flash_ok_message( _m('Plugin enabled'), 'admin') ;
                    } else {
                        osc_add_flash_error_message( _m('Plugin is already enabled'), 'admin') ;
                    }

                    $this->redirectTo(osc_admin_base_url(true) . '?page=plugins') ;
                    break;
                case 'disable':
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action cannot be done because it is a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=plugins');
                    }

                    if( Plugins::deactivate(Params::getParam('plugin')) ) {
                        osc_add_flash_ok_message( _m('Plugin disabled'), 'admin') ;
                    } else {
                        osc_add_flash_error_message( _m('Plugin is already disabled'), 'admin') ;
                    }

                    $this->redirectTo(osc_admin_base_url(true) . '?page=plugins') ;
                    break;
                case 'admin':
                    $plugin = Params::getParam("plugin");
                    if($plugin != "") {
                        Plugins::runHook($plugin.'_configure');
                    }
                    break;
                case 'admin_post':
                    Plugins::runHook('admin_post');

                case 'renderplugin':
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
                case 'error_plugin':
                    // force php errors and simulate plugin installation to show the errors in the iframe
                    if( !OSC_DEBUG ) {
                        error_reporting( E_ALL | E_STRICT ) ;
                    }
                    @ini_set( 'display_errors', 1 ) ;

                    include( osc_plugins_path() . Params::getParam('plugin') ) ;
                    Plugins::install(Params::getParam('plugin')) ;
                    exit ;
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
    }

    /* file end: ./oc-admin/plugins.php */
?>