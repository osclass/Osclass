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
            if( strpos($file, '../') !== false || strpos($file, '..\\') !==false || stripos($file, '/admin/') !== false ) { //If the file is inside an "admin" folder, it should NOT be opened in frontend
                $this->do404();
                return;
            }

            // check if the file exists
            if( !file_exists(osc_plugins_path() . $file) && !file_exists(osc_themes_path() . $file) ) {
                $this->do404();
                return;
            }

            osc_run_hook('custom_controller');

            $this->_exportVariableToView('file', $file);
            if($user_menu) {
                if(osc_is_web_user_logged_in()) {
                    Params::setParam('in_user_menu', true);
                    $this->doView('user-custom.php');
                } else {
                    $this->redirectTo(osc_user_login_url());
                }
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