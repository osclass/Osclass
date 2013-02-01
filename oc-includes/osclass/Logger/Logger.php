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

    abstract class Logger
    {
        private function __construct() {}

        /**
         * Log a message with the INFO level.
         * @param <type> $message
         */
        abstract function info($message = '', $caller = null );

        /**
         * Log a message with the WARN level.
         * @param <type> $message
         */
        abstract function warn($message = '', $caller = null );

        /**
         * Log a message with the ERROR level.
         * @param <type> $message
         */
        abstract function error($message = '', $caller = null );

        /**
         * Log a message with the DEBUG level.
         * @param <type> $message
         */
        abstract function debug($message = '', $caller = null );

        /**
         * Log a message object with the FATAL level including the caller.
         * @param <type> $message
         */
        abstract function fatal($message = '', $caller = null );
    }

    /* file end: ./oc-includes/osclass/Logger/Logger.php */
?>