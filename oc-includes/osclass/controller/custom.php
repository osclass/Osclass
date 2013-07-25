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

    class CWebCustom extends BaseModel
    {
        function __construct()
        {
            parent::__construct();
            //specific things for this class
        }

        //Business Layer...
        function doModel()
        {
            $user_menu = false;
            if(Params::existParam('route')) {
                $routes = Rewrite::newInstance()->getRoutes();
                $rid = Params::getParam('route');
                $file = '../';
                if(isset($routes[$rid]) && isset($routes[$rid]['file'])) {
                    $file = $routes[$rid]['file'];
                    $user_menu = $routes[$rid]['user_menu'];
                }
            } else {
                // DEPRECATED: Disclosed path in URL is deprecated, use routes instead
                // This will be REMOVED in 3.4
                $file = Params::getParam('file');
            }

            // valid file?
            if( stripos($file, '../') !== false || stripos($file, '/admin/') !== false ) { //If the file is inside an "admin" folder, it should NOT be opened in frontend
                $this->do404();
                return;
            }

            // check if the file exists
            if( !file_exists(osc_plugins_path() . $file) ) {
                $this->do404();
                return;
            }

            $this->_exportVariableToView('file', $file);
            if($user_menu) {
                Params::setParam('in_user_menu', true);
                $this->doView('user-custom.php');
            } else {
                $this->doView('custom.php');
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_run_hook("before_html");
            osc_current_web_theme_path($file);
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_html");
        }
    }

    /* file end: ./custom.php */
?>