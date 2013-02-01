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

    class CWebLanguage extends BaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        // business layer...
        function doModel()
        {
            $locale = Params::getParam('locale');

            if(preg_match('/.{2}_.{2}/', $locale)) {
                Session::newinstance()->_set('userLocale', $locale);
            }

            $redirect_url = '';
            if($_SERVER['HTTP_REFERER'] != '') {
                $redirect_url = $_SERVER['HTTP_REFERER'];
            } else {
                $redirect_url = osc_base_url(true);
            }

            $this->redirectTo($redirect_url);
        }

        // hopefully generic...
        function doView($file) { }
    }

    /* file end: ./language.php */
?>