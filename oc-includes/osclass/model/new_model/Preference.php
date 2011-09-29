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

    class Preference
    {
        private static $instance ;
        private $dao ;

        public static function newInstance() {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        public function __construct() {
            $this->dao = new DAO_osclass_t_preference() ;
            $this->dao->selectAll() ;
        }

        public function get($key, $section = "osclass") {
            if (!isset($this->dao->aDO['pk_' . $section . '_' . $key])) {
                return '' ;
            }
            return ($this->dao->aDO['pk_' . $section . '_' . $key]) ;
        }

        public function set($key, $value, $section = "osclass") {
            $this->dao->aDO['pk_' . $section . '_' . $key] = $value ;
        }

    }

?>