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

class CAdminAppearance extends AdminSecBaseModel
{

    function __construct() {
        parent::__construct() ;
        // common css
        $this->add_css('appearance_layout.css');
    }

    //Business Layer...
    function doModel() {
        parent::doModel() ;
        //specific things for this class
        switch ($this->action) {
            case 'add':
                $this->doView("appearance/add.php");
            break;
            case 'add_post':
                $filePackage = Params::getFiles('package');
                $path = osc_translations_path() ;
                osc_packageExtract($filePackage['tmp_name'], $path);
                $this->redirectTo( osc_admin_base_url(true) . "?page=appearance" );
            break;
            case 'delete':
                $themes = Params::getParam('theme') ;
                if ( isset( $themes ) && is_array( $themes ) ) {
                    foreach ($themes as $theme) {
                        if (!osc_deleteDir(THEMES_PATH . $theme))
                            osc_add_flash_message(__('Directory "%s" can\'t be removed'), $theme);
                    }
                } else if (isset( $themes )) {
                    if (!osc_deleteDir(THEMES_PATH . $themes)){
                        osc_add_flash_message(__('Directory "%s" can\'t be removed'), $themes);
                    }
                } else {
                    osc_add_flash_message(__('No theme selected'));
                }
                $this->redirectTo( osc_admin_base_url(true) . "?page=appearance" );
            break;
            case 'widgets':
                $info = WebThemes::newInstance()->loadThemeInfo(osc_theme());

                $this->_exportVariableToView("info", $info);

                $this->doView('appearance/widgets.php');
            break;
            case 'add_widget':
                $this->doView('appearance/add_widget.php');
            break;
            case 'delete_widget':
                Widget::newInstance()->delete(
                    array('pk_i_id' => Params::getParam('id') )
                );
                $this->redirectTo( osc_admin_base_url(true) . "?page=appearance&action=widgets" );
            break;
            case 'add_widget_post':
                Widget::newInstance()->insert(
                    array(
                        's_location' => Params::getParam('location')
                        ,'e_kind' => 'html'
                        ,'s_description' => Params::getParam('description')
                        ,'s_content' => Params::getParam('content')
                    )
                );
                $this->redirectTo( osc_admin_base_url(true) . "?page=appearance&action=widgets" );
            break;
            case 'activate':
                Preference::newInstance()->update(
                        array('s_value' => Params::getParam('theme') )
                        ,array('s_section' => 'osclass', 's_name' => 'theme')
                );
            default:
                $themes = WebThemes::newInstance()->getListThemes();
                $info = WebThemes::newInstance()->loadThemeInfo(osc_theme());
                
                //preparing variables for the view
                $this->_exportVariableToView("themes", $themes);
                $this->_exportVariableToView("info", $info);

                $this->doView('appearance/index.php');
        }
    }

    //hopefully generic...
    function doView($file) {
        osc_current_admin_theme_url($file) ;
    }
}
