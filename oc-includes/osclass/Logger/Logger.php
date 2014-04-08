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