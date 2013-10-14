<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /**
     * Osclass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2012 OSCLASS
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

    class CAdminAppearance extends AdminSecBaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel();
            //specific things for this class
            switch ($this->action) {
                case('add'):
                    $this->doView("appearance/add.php");
                break;
                case('add_post'):
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=appearance');
                    }
                    osc_csrf_check();
                    $filePackage = Params::getFiles('package');
                    if(isset($filePackage['size']) && $filePackage['size']!=0) {
                        $path = osc_themes_path();
                        (int) $status = osc_unzip_file($filePackage['tmp_name'], $path);
                    } else {
                        $status = 3;
                    }

                    switch ($status) {
                        case(0):   $msg = _m('The theme folder is not writable');
                                   osc_add_flash_error_message($msg, 'admin');
                        break;
                        case(1):   $msg = _m('The theme has been installed correctly');
                                   osc_add_flash_ok_message($msg, 'admin');
                        break;
                        case(2):   $msg = _m('The zip file is not valid');
                                   osc_add_flash_error_message($msg, 'admin');
                        break;
                        case(3):   $msg = _m('No file was uploaded');
                                   osc_add_flash_error_message($msg, 'admin');
                                   $this->redirectTo(osc_admin_base_url(true)."?page=appearance&action=add");
                        break;
                        case(-1):
                        default:   $msg = _m('There was a problem adding the theme');
                                   osc_add_flash_error_message($msg, 'admin');
                        break;
                    }

                    $this->redirectTo( osc_admin_base_url(true) . "?page=appearance" );
                break;
                case('delete'):
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=appearance');
                    }
                    osc_csrf_check();
                    $theme = Params::getParam('webtheme');
                    if($theme!='') {
                        if($theme!=  osc_current_web_theme()) {
                            if(osc_deleteDir(osc_content_path()."themes/".$theme."/")) {
                                osc_add_flash_ok_message(_m("Theme removed successfully"), "admin");
                            } else {
                                osc_add_flash_error_message(_m("There was a problem removing the theme"), "admin");
                            }
                        } else {
                            osc_add_flash_error_message(_m("Current theme can not be deleted"), "admin");
                        }
                    } else {
                        osc_add_flash_error_message(_m("No theme selected"), "admin");
                    }

                    $this->redirectTo( osc_admin_base_url(true) . "?page=appearance" );
                break;
                /* widgets */
                case('widgets'):
                    $info = WebThemes::newInstance()->loadThemeInfo(osc_theme());

                    $this->_exportVariableToView("info", $info);

                    $this->doView('appearance/widgets.php');
                break;
                case('add_widget'):
                    $this->doView('appearance/add_widget.php');
                break;
                case('edit_widget'):
                    $id = Params::getParam('id');

                    $widget = Widget::newInstance()->findByPrimaryKey($id);
                    $this->_exportVariableToView("widget", $widget);

                    $this->doView('appearance/add_widget.php');
                break;
                case('delete_widget'):
                    osc_csrf_check();
                    Widget::newInstance()->delete(
                        array('pk_i_id' => Params::getParam('id') )
                    );
                    osc_add_flash_ok_message( _m('Widget removed correctly'), 'admin');
                    $this->redirectTo( osc_admin_base_url(true) . "?page=appearance&action=widgets" );
                break;
                case('edit_widget_post'):
                    osc_csrf_check();
                    if(!osc_validate_text(Params::getParam("description"))) {
                        osc_add_flash_error_message( _m('Description field is required'), 'admin');
                        $this->redirectTo( osc_admin_base_url(true) . "?page=appearance&action=widgets" );
                    }

                    $res = Widget::newInstance()->update(
                        array(
                            's_description' => Params::getParam('description'),
                            's_content' => Params::getParam('content', false, false)
                        ),
                        array('pk_i_id' => Params::getParam('id') )
                    );

                    if( $res ) {
                        osc_add_flash_ok_message( _m('Widget updated correctly'), 'admin');
                    } else {
                        osc_add_flash_ok_message( _m('Widget cannot be updated correctly'), 'admin');
                    }
                    $this->redirectTo( osc_admin_base_url(true) . "?page=appearance&action=widgets" );
                    break;
                case('add_widget_post'):
                    osc_csrf_check();
                    if(!osc_validate_text(Params::getParam("description"))) {
                        osc_add_flash_error_message( _m('Description field is required'), 'admin');
                        $this->redirectTo( osc_admin_base_url(true) . "?page=appearance&action=widgets" );
                    }

                    Widget::newInstance()->insert(
                        array(
                            's_location' => Params::getParam('location'),
                            'e_kind' => 'html',
                            's_description' => Params::getParam('description'),
                            's_content' => Params::getParam('content', false, false)
                        )
                    );
                    osc_add_flash_ok_message( _m('Widget added correctly'), 'admin');
                    $this->redirectTo( osc_admin_base_url(true) . "?page=appearance&action=widgets" );
                break;
                /* /widget */
                case('activate'):
                    osc_csrf_check();
                    osc_set_preference('theme', Params::getParam('theme'));
                    osc_add_flash_ok_message( _m('Theme activated correctly'), 'admin');
                    osc_run_hook("theme_activate", Params::getParam('theme'));
                    $this->redirectTo( osc_admin_base_url(true) . "?page=appearance" );
                break;
                case('render'):
                    $this->_exportVariableToView('file', osc_base_path() . Params::getParam("file"));
                    $this->doView('appearance/view.php');
                break;
                default:
//                    $marketError = Params::getParam('marketError');
//                    $slug = Params::getParam('slug');
//                    if($marketError!='') {
//                        if($marketError == '0') { // no error installed ok
//                            $help = '<br/><br/><b>' . __('You only need to activate or preview the theme').'</b>';
//                            osc_add_flash_ok_message( __('Everything was OK!') . ' ( ' . $slug .' ) ' . $help, 'admin');
//                        } else {
//                            osc_add_flash_error_message( __('Error occurred') . ' ( ' . $slug .' ) ', 'admin');
//                        }
//                    }

                    // force the recount of themes that need to be updated
                    if(Params::getParam('checkUpdated') != '') {
                        osc_admin_toolbar_update_themes(true);
                    }

                    $themes = WebThemes::newInstance()->getListThemes();

                    //preparing variables for the view
                    $this->_exportVariableToView("themes", $themes);

                    $this->doView('appearance/index.php');
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

    /* file end: ./oc-admin/appearance.php */
?>