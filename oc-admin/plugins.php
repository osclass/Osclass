<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    class CAdminPlugins extends AdminSecBaseModel
    {
        function __construct()
        {
            parent::__construct();
            //specific things for this class
        }

        // Business layer...
        function doModel()
        {
            parent::doModel();

            //specific things for this class
            switch ($this->action)
            {
                case 'add':
                    $this->doView("plugins/add.php");
                    break;
                case 'add_post':
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=plugins');
                    }
                    osc_csrf_check();

                    $package = Params::getFiles("package");
                    if(isset($package['size']) && $package['size']!=0) {
                        $path = osc_plugins_path();
                        (int) $status = osc_unzip_file($package['tmp_name'], $path);
                        @unlink($package['tmp_name']);
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
                        osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=plugins');
                    }
                    osc_csrf_check();
                    $pn = Params::getParam('plugin');

                    // set header just in case it's triggered some fatal error
                    header("Location: " . osc_admin_base_url(true) . "?page=plugins&error=" . $pn, true, '302');

                    $installed = Plugins::install($pn);
                    if( is_array($installed) ) {
                        switch($installed['error_code']) {
                            case('error_output'):
                                osc_add_flash_error_message( sprintf( _m('The plugin generated %d characters of <strong>unexpected output</strong> during the installation. Output: "%s"'), strlen($installed['output']), $installed['output'] ), 'admin');
                                break;
                            case('error_installed'):
                                osc_add_flash_error_message( _m('Plugin is already installed'), 'admin');
                            break;
                            case('error_file'):
                                osc_add_flash_error_message( _m("Plugin couldn't be installed because their files are missing"), 'admin');
                            break;
                            case('custom_error'):
                                osc_add_flash_error_message( sprintf(_m("Plugin couldn't be installed because of: %s"), $installed['msg']), 'admin');
                            break;
                            default:
                                osc_add_flash_error_message( _m("Plugin couldn't be installed"), 'admin');
                            break;
                        }
                    } else {
                        osc_add_flash_ok_message( _m('Plugin installed'), 'admin');
                    }

                    $this->redirectTo(osc_admin_base_url(true) . '?page=plugins');
                    break;
                case 'uninstall':
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=plugins');
                    }
                    osc_csrf_check();

                    if( Plugins::uninstall(Params::getParam("plugin")) ) {
                        osc_add_flash_ok_message( _m('Plugin uninstalled'), 'admin');
                    } else {
                        osc_add_flash_error_message( _m("Plugin couldn't be uninstalled"), 'admin');
                    }

                    $this->redirectTo(osc_admin_base_url(true) . '?page=plugins');
                    break;
                case 'enable':
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=plugins');
                    }
                    osc_csrf_check();

                    if( Plugins::activate(Params::getParam('plugin')) ) {
                        osc_add_flash_ok_message( _m('Plugin enabled'), 'admin');
                    } else {
                        osc_add_flash_error_message( _m('Plugin is already enabled'), 'admin');
                    }

                    $this->redirectTo(osc_admin_base_url(true) . '?page=plugins');
                    break;
                case 'disable':
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=plugins');
                    }
                    osc_csrf_check();

                    if( Plugins::deactivate(Params::getParam('plugin')) ) {
                        osc_add_flash_ok_message( _m('Plugin disabled'), 'admin');
                    } else {
                        osc_add_flash_error_message( _m('Plugin is already disabled'), 'admin');
                    }

                    $this->redirectTo(osc_admin_base_url(true) . '?page=plugins');
                    break;
                case 'admin':
                    $plugin = Params::getParam("plugin");
                    if($plugin != "") {
                        osc_run_hook($plugin.'_configure');
                    }
                    break;
                case 'admin_post':
                    osc_run_hook('admin_post');
                    break;
                case 'renderplugin':

                    if(Params::existParam('route')) {
                        $routes = Rewrite::newInstance()->getRoutes();
                        $rid = Params::getParam('route');
                        $file = '../';
                        if(isset($routes[$rid]) && isset($routes[$rid]['file'])) {
                            $file = $routes[$rid]['file'];
                        }
                    } else {
                        // DEPRECATED: Disclosed path in URL is deprecated, use routes instead
                        // This will be REMOVED in 3.4
                        $file = Params::getParam('file');
                        // We pass the GET variables (in case we have somes)
                        if(preg_match('|(.+?)\?(.*)|', $file, $match)) {
                            $file = $match[1];
                            if(preg_match_all('|&([^=]+)=([^&]*)|', urldecode('&'.$match[2].'&'), $get_vars)) {
                                for($var_k=0;$var_k<count($get_vars[1]);$var_k++) {
                                    Params::setParam($get_vars[1][$var_k], $get_vars[2][$var_k]);
                                }
                            }
                        } else {
                            $file = Params::getParam('file');
                        };
                    }
                    osc_run_hook('renderplugin_controller');
                    if(stripos($file, '../')===false && stripos($file, '..\\')===false && $file!="") {
                        $this->_exportVariableToView("file", osc_plugins_path() . $file);
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
                    osc_csrf_check();
                    $plugin_short_name = Params::getParam("plugin_short_name");
                    $categories        = Params::getParam("categories");
                    if( $plugin_short_name != "" ) {
                        Plugins::cleanCategoryFromPlugin($plugin_short_name);
                        if(isset($categories)) {
                            Plugins::addToCategoryPlugin($categories, $plugin_short_name);
                        }
                        osc_run_hook('plugin_categories_'.Params::getParam('plugin'), $categories);
                        osc_add_flash_ok_message( _m('Configuration was saved'), 'admin');
                        $this->redirectTo(osc_admin_base_url(true)."?page=plugins");
                    }

                    osc_add_flash_error_message( _m('No plugin selected'), 'admin');
                    $this->doView('plugins/index.php');
                    break;
                case 'delete':
                    osc_csrf_check();
                    $plugin = str_replace('/index.php', '', Params::getParam("plugin"));
                    $path = preg_replace('([\/]+)', '/', CONTENT_PATH.'plugins/'.$plugin);
                    if($plugin!="" && strpos($plugin, '../')===false && strpos($plugin, '..\\')===false && $path!=CONTENT_PATH.'plugins/') {
                        if(osc_deleteDir($path)) {
                            osc_add_flash_ok_message( _m('The files were deleted'), 'admin');
                        } else {
                            osc_add_flash_error_message( sprintf(_m('There were an error deleting the files, please check the permissions of the files in %s'), $path."/"), 'admin');
                        }
                        $this->redirectTo(osc_admin_base_url(true)."?page=plugins");
                    }

                    osc_add_flash_error_message( _m('No plugin selected'), 'admin');
                    $this->doView('plugins/index.php');
                    break;
                case 'error_plugin':
                    // force php errors and simulate plugin installation to show the errors in the iframe
                    $plugin = Params::getParam('plugin');
                    if(strpos($plugin, '../')!==false || strpos($plugin, '..\\')!==false) {
                        osc_add_flash_error_message( _m('Invalid plugin file'), 'admin');
                        $this->redirectTo(osc_admin_base_url(true)."?page=plugins");
                    }
                    if( !OSC_DEBUG ) {
                        error_reporting( E_ALL | E_STRICT );
                    }
                    @ini_set( 'display_errors', 1 );

                    include( osc_plugins_path() . $plugin );
                    Plugins::install($plugin);
                    exit;
                break;
                default:

                    if(Params::getParam('checkUpdated') != '') {
                        osc_admin_toolbar_update_plugins(true);
                    }

                    if( Params::getParam('iDisplayLength') == '' ) {
                        Params::setParam('iDisplayLength', 25 );
                    }

                    $this->_exportVariableToView('iDisplayLength', Params::getParam('iDisplayLength'));

                    $p_iPage      = 1;
                    if( is_numeric(Params::getParam('iPage')) && Params::getParam('iPage') >= 1 ) {
                        $p_iPage = Params::getParam('iPage');
                    }
                    Params::setParam('iPage', $p_iPage);
                    $aPlugin    = Plugins::listAll();
                    $active_plugins = osc_get_plugins();

                    // pagination
                    $start = ($p_iPage-1) * Params::getParam('iDisplayLength');
                    $limit = Params::getParam('iDisplayLength');
                    $count = count( $aPlugin );

                    $displayRecords = $limit;
                    if( ($start+$limit ) > $count ) {
                        $displayRecords = ($start+$limit) - $count;
                    }
                    // --------------------------------------------------------

                    $aData = array();
                    $aInfo = array();
                    $max = ($start+$limit);
                    if($max > $count) $max = $count;
                    $aPluginsToUpdate = json_decode( osc_get_preference('plugins_to_update') );
                    $bPluginsToUpdate = is_array($aPluginsToUpdate)?true:false;
                    for($i = $start; $i < $max; $i++) {
                        $plugin = $aPlugin[$i];
                        $row   = array();
                        $pInfo = osc_plugin_get_info($plugin);

                        // prepare row 1
                        $installed = 0;
                        if( osc_plugin_is_installed($plugin) ) {
                            $installed = 1;
                        }
                        $enabled = 0;
                        if( osc_plugin_is_enabled($plugin) ) {
                            $enabled = 1;
                        }
                        // prepare row 2
                        $sUpdate = '';
                        // get plugins to update from t_preference
                        if($bPluginsToUpdate) {
                            if(in_array(@$pInfo['plugin_update_uri'],$aPluginsToUpdate )){
                                $sUpdate = '<a class="market_update market-popup" href="#' . htmlentities($pInfo['plugin_update_uri']) . '">' . __("There's a new update available") . '</a>';
                            }
                        }
                        // prepare row 4
                        $sConfigure = '';
                        if( isset($active_plugins[$plugin . '_configure']) ) {
                            $sConfigure = '<a href="' . osc_admin_base_url(true) . '?page=plugins&amp;action=admin&amp;plugin=' . $pInfo['filename'] . "&amp;" . osc_csrf_token_url() . '">' . __('Configure') . '</a>';
                        }
                        // prepare row 5
                        $sEnable = '';
                        if( $installed ) {
                            if( $enabled ) {
                                $sEnable = '<a href="' . osc_admin_base_url(true) . '?page=plugins&amp;action=disable&amp;plugin=' . $pInfo['filename'] . "&amp;" . osc_csrf_token_url() . '">' . __('Disable') . '</a>';
                            } else {
                                $sEnable = '<a href="' . osc_admin_base_url(true) . '?page=plugins&amp;action=enable&amp;plugin=' . $pInfo['filename'] . "&amp;" . osc_csrf_token_url() . '">' . __('Enable') . '</a>';
                            }
                        }
                        // prepare row 6
                        if( $installed ) {
                            $sInstall = '<a onclick="javascript:return uninstall_dialog(\'' . $pInfo['filename'] . '\', \'' . $pInfo['plugin_name'] . '\');" href="' . osc_admin_base_url(true) . '?page=plugins&amp;action=uninstall&amp;plugin=' . $pInfo['filename'] . "&amp;" . osc_csrf_token_url() . '">' . __('Uninstall') . '</a>';
                        } else {
                            $sInstall = '<a href="' . osc_admin_base_url(true) . '?page=plugins&amp;action=install&amp;plugin=' . $pInfo['filename'] . "&amp;" . osc_csrf_token_url() . '">' . __('Install') . '</a>';
                        }
                        $sDelete = '';
                        if( !$installed ) {
                            $sDelete =  '<a href="javascript:delete_plugin(\''.$pInfo['filename'].'\');" >' . __('Delete') . '</a>';
                        }

                        $sHelp = '';
                        if($pInfo['support_uri']!='') {
                            $sHelp = '<span class="plugin-support-icon plugin-tooltip" ><a target="_blank" href="'.osc_sanitize_url($pInfo['support_uri']).'" ><img src="'.osc_current_admin_theme_url('images/question.png').'" alt="'.osc_esc_html(__('Problems with this plugin? Ask for support.')).'" ></a></span>';
                        }
                        $sSiteUrl = '';
                        if($pInfo['plugin_uri']!='') {
							$sSiteUrl = ' | <a target="_blank" href="'. $pInfo['plugin_uri'] . '">'. __('Plugins Site'). '</a>';
						}
						if($pInfo['author_uri']!='') {
							$sAuthor = __('By') . ' <a target="_blank" href="'. $pInfo['author_uri'] . '">'. $pInfo['author'] . '</a>';
						} else {
							$sAuthor = __('By') . ' ' . $pInfo['author'];
						}

                        $row[] = '<input type="hidden" name="installed" value="' . $installed . '" enabled="' . $enabled . '" />' . $pInfo['plugin_name'] . $sHelp . '<div>' . $sUpdate . '</div>';
                        $row[] = $pInfo['description'] . '<br />' . __('Version:') . $pInfo['version'] . ' | ' . $sAuthor . $sSiteUrl ;
                        $row[] = ($sUpdate!='')     ? $sUpdate      : '&nbsp;';
                        $row[] = ($sConfigure!='')  ? $sConfigure   : '&nbsp;';
                        $row[] = ($sEnable!='')     ? $sEnable      : '&nbsp;';
                        $row[] = ($sInstall!='')    ? $sInstall     : '&nbsp;';
                        $row[] = ($sDelete!='')     ? $sDelete      : '&nbsp;';
                        $aData[] = $row;
                        if(@$pInfo['plugin_update_uri'] != '') {
                            $aInfo[@$pInfo['plugin_update_uri']] = $pInfo;
                        } else {
                            $aInfo[$i] = $pInfo;
                        }
                    }

                    $array['iTotalRecords']         = $displayRecords;
                    $array['iTotalDisplayRecords']  = count($aPlugin);
                    $array['iDisplayLength']        = $limit;
                    $array['aaData'] = $aData;
                    $array['aaInfo'] = $aInfo;

                    // --------------------------------------------------------
                    $page  = (int)Params::getParam('iPage');
                    if(count($array['aaData']) == 0 && $page!=1) {
                        $total = (int)$array['iTotalDisplayRecords'];
                        $maxPage = ceil( $total / (int)$array['iDisplayLength'] );

                        $url = osc_admin_base_url(true).'?'.Params::getServerParam('QUERY_STRING', false, false);

                        if($maxPage==0) {
                            $url = preg_replace('/&iPage=(\d)+/', '&iPage=1', $url);
                            $this->redirectTo($url);
                        }

                        if($page > 1) {
                            $url = preg_replace('/&iPage=(\d)+/', '&iPage='.$maxPage, $url);
                            $this->redirectTo($url);
                        }
                    }


                    $this->_exportVariableToView('aPlugins', $array);
                    $this->doView("plugins/index.php");
                break;
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_run_hook("before_admin_html");
            osc_current_admin_theme_path($file);
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_admin_html");
        }
    }

    /* file end: ./oc-admin/plugins.php */
?>
