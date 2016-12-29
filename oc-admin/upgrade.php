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

    class CAdminUpgrade extends AdminSecBaseModel
    {
        function __construct() {
            parent::__construct();
        }

        //Business Layer...
        function doModel() {
            parent::doModel();

            //specific things for this class
            switch ($this->action)
            {
                case 'upgrade-funcs':
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action cannot be done because it is a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true));
                    }
                    require(LIB_PATH.'osclass/upgrade-funcs.php');
                break;
                default:                $this->doView('upgrade/index.php');
            }
        }

        //hopefully generic...
        function doView($file) {
            osc_run_hook("before_admin_html");
            osc_current_admin_theme_path($file);
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_admin_html");
        }
    }

?>