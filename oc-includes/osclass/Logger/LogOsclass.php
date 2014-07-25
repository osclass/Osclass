<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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