<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
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

    class AdminThemes extends Themes
    {
        private static $instance;

        public static function newInstance()
        {
            if(!self::$instance instanceof self) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public function __construct()
        {
            parent::__construct();
            $this->setCurrentTheme( osc_admin_theme() );
        }

        public function setCurrentThemeUrl()
        {
            if ($this->theme_exists) {
                $this->theme_url = osc_admin_base_url() . 'themes/' . $this->theme . '/';
            } else {
                $this->theme_url = osc_admin_base_url() . 'gui/';
            }
        }

        public function setCurrentThemePath()
        {
            if (file_exists(osc_admin_base_path() . 'themes/' . $this->theme . '/')) {
                $this->theme_exists = true;
                $this->theme_path = osc_admin_base_path() . 'themes/' . $this->theme . '/';
            } else {
                $this->theme_exists = false;
                $this->theme_path = osc_admin_base_path() . 'gui/';
            }
        }
    }

    /* file end: ./oc-includes/osclass/AdminThemes.php */