<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class CAdminMarket extends AdminSecBaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel() ;
            //specific things for this class
            switch ($this->action) {
                case('plugins'):
                    $this->doView("market/plugins.php");
                break;
                case('themes'):
                    $this->doView("market/themes.php");
                break;
                default:
                    $this->doView("market/plugins.php");
                break;
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_current_admin_theme_path($file);
            Session::newInstance()->_clearVariables();
        }
    }

    /* file end: ./oc-admin/market.php */
?>