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

    class SiteInfo extends DAO
    {
        private static $instance ;
        private $site_info ;

        public static function newInstance() {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        public function __construct() {
            $this->createMetadataConnection() ;
            $this->toArray() ;
        }

        public function getTableName() {
            return 'tbl_sites' ;
        }

        public function toArray() {
            $domain = $_SERVER['HTTP_HOST'] ;
            $this->site_info = $this->findByPrimaryKeyInMetadataDB($domain) ;
        }

        public function get($key) {
            if (!isset($this->site_info[$key])) {
                return '' ;
            }
            return ($this->site_info[$key]) ;
        }
    }

?>