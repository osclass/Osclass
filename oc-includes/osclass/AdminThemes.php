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

    class AdminThemes {

        private static $instance ;
        private $theme ;
        private $theme_url ;
        private $theme_path ;

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
            $this->theme_url = osc_base_url() . 'oc-admin/themes/' . $this->theme . '/' ;
        }

        private function setCurrentThemePath() {
            $this->theme_path = osc_base_path() . 'oc-admin/themes/' . $this->theme . '/' ; //XXX: must take data from defined global var.
        }

        /* PUBLIC */
        public function setCurrentTheme($theme) {
            $this->theme = $theme ;
            $this->setCurrentThemeUrl() ;
            $this->setCurrentThemePath() ;
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