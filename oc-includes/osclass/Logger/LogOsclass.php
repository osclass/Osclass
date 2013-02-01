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

    class LogOsclassInstaller extends Logger
    {
        private static $_instance;

        private $os;
        private $component = 'INSTALLER';

        static function instance()
        {
            if (!isset(self::$_instance)) {
                $c = __CLASS__;
                self::$_instance = new $c;
            }
            return self::$_instance;
        }

        public function __construct()
        {
            $this->os = PHP_OS;
        }

        /**
         * Log a message with the INFO level.
         * @param <type> $message
         */
        public function info($message = '', $caller = null )
        {
            $this->sendOsclass('INFO', $message, $caller);
        }

        /**
         * Log a message with the WARN level.
         * @param <type> $message
         */
        public function warn($message = '', $caller = null )
        {
            $this->sendOsclass('WARN', $message, $caller);
        }

        /**
         * Log a message with the ERROR level.
         * @param <type> $message
         */
        public function error($message = '', $caller = null )
        {
            $this->sendOsclass('ERROR', $message, $caller);
        }

        /**
         * Log a message with the DEBUG level.
         * @param <type> $message
         */
        public function debug($message = '', $caller = null )
        {
            $this->sendOsclass('DEBUG', $message, $caller);
        }

        /**
         * Log a message object with the FATAL level including the caller.
         * @param <type> $message
         */
        public function fatal($message = '', $caller = null )
        {
            $this->sendOsclass('FATAL', $message, $caller);
        }

        private function sendOsclass($type, $message, $caller )
        {
            osc_doRequest(
                    "http://admin.osclass.org/logger.php"
                    , array(
                        'type' => $type
                        ,'component' => $this->component
                        ,'os' => $this->os
                        ,'message' => base64_encode($message)
                        ,'fileLine' => base64_encode($caller)
                    )
            );
            /*require_once LIB_PATH . 'libcurlemu/libcurlemu.inc.php';

            $url = "admin.osclass.org/logger.php?type=$type&component=".$this->component;
            $url .= "&os=".$this->os;
            $url .= "&message=".base64_encode($message);
            $url .= "&fileLine=".base64_encode($caller);

            $ch = @curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = @curl_exec($ch);
            @curl_close($ch);
        }
        */
        }
    }

    /* file end: ./oc-includes/osclass/Logger/LogOsclass.php */
?>