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

    class CWebMain extends BaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        //Business Layer...
        function doModel()
        {
            switch($this->action) {
                case('logout'):         // unset only the required parameters in Session
                                        osc_run_hook("logout");

                                        Session::newInstance()->_drop('userId');
                                        Session::newInstance()->_drop('userName');
                                        Session::newInstance()->_drop('userEmail');
                                        Session::newInstance()->_drop('userPhone');

                                        Cookie::newInstance()->pop('oc_userId');
                                        Cookie::newInstance()->pop('oc_userSecret');
                                        Cookie::newInstance()->set();

                                        $this->redirectTo( osc_base_url() );
                break;
                default:                $this->doView('main.php');
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

    /* file end: ./main.php */
?>