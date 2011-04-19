<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class AdminThemes {

        private static $instance ;
        private $theme ;
        private $theme_url ;
        private $theme_path ;
        private $theme_exists ;

        public static function newInstance() {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        public function __construct() {
            $this->setCurrentTheme( osc_admin_theme() ) ;
        }

        /* PRIVATE */
        private function setCurrentThemeUrl() {
            if ($this->theme_exists) {
                $this->theme_url = osc_admin_base_url() . 'themes/' . $this->theme . '/' ;
            } else {
                $this->theme_url = osc_admin_base_url() . 'gui/' ;
            }
        }

        private function setCurrentThemePath() {
            if (file_exists(osc_admin_base_path() . 'themes/' . $this->theme . '/')) {
                $this->theme_exists = true ;
                $this->theme_path = osc_admin_base_path() . 'themes/' . $this->theme . '/' ;
            } else {
                $this->theme_exists = false ;
                $this->theme_path = osc_admin_base_path() . 'gui/' ;
            }
        }

        /* PUBLIC */
        public function setCurrentTheme($theme) {
            $this->theme = $theme ;
            $this->setCurrentThemePath() ;
            $this->setCurrentThemeUrl() ;
        }

        public function getCurrentTheme() {
            return $this->theme ;
        }

        public function getCurrentThemeUrl() {
            return $this->theme_url ;
        }

        public function getCurrentThemePath() {
            return $this->theme_path ;
        }

        public function getCurrentThemeStyles() {
            return $this->theme_url . 'css/' ;
        }

        public function getCurrentThemeJs() {
            return $this->theme_url . 'js/' ;
        }
    }

?>