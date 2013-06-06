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

    define('CACHE_PATH', osc_uploads_path());

    /**
     * This is the simplest cache service on earth.
     *
     * @author Osclass
     * @version 1.0
     */
    class Cache {

        private $objectKey;
        private $expiration;

        public function __construct($objectKey, $expiration = 900 /* 15 minutes */) {
            $this->objectKey = $objectKey;
            $this->expiration = $expiration;
        }

        public function __destruct() {
        }

        /**
         * @return true if the object is cached and has not expired, false otherwise.
         */
        public function check() {
            $path = $this->preparePath();
            if(!file_exists($path)) return false;

            if(time() - filemtime($path) > $this->expiration) {
                unlink($path);
                return false;
            }

            return true;
        }

        /**
         * Stores the object passed as parameter in the cache backend (filesystem).
         */
        public function store($object) {
            $serialized = serialize($object);
            file_put_contents($this->preparePath(), $serialized);
        }

        /**
         * Returns the data of the current cached object.
         */
        public function retrieve() {
            $content = file_get_contents($this->preparePath());
            return unserialize($content);
        }

        /**
         * Constructs the path to object in filesystem.
         */
        private function preparePath() {
            return CACHE_PATH . $this->objectKey . '.cache';
        }
    }

?>