<?php

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

    if( !defined('MULTISITE') ) {
        define('MULTISITE', 0);
    }

    if( !defined('OC_ADMIN') ) {
        define('OC_ADMIN', false);
    }

    if( !defined('LIB_PATH') ) {
        define('LIB_PATH', ABS_PATH . 'oc-includes/');
    }

    if( !defined('CONTENT_PATH') ) {
        define('CONTENT_PATH', ABS_PATH . 'oc-content/');
    }

    if( !defined('THEMES_PATH') ) {
        define('THEMES_PATH', CONTENT_PATH . 'themes/');
    }

    if( !defined('PLUGINS_PATH') ) {
        define('PLUGINS_PATH', CONTENT_PATH . 'plugins/');
    }

    if( !defined('TRANSLATIONS_PATH') ) {
        define('TRANSLATIONS_PATH', CONTENT_PATH . 'languages/');
    }

    if( !defined('UPLOADS_PATH') ) {
        define('UPLOADS_PATH', CONTENT_PATH . 'uploads/');
    }

    if( !defined('OSC_DEBUG_DB') ) {
        define('OSC_DEBUG_DB', false);
    }

    if( !defined('OSC_DEBUG_DB_LOG') ) {
        define('OSC_DEBUG_DB_LOG', false);
    }

    if( !defined('OSC_DEBUG_DB_EXPLAIN') ) {
        define('OSC_DEBUG_DB_EXPLAIN', false);
    }

    if( !defined('OSC_DEBUG') ) {
        define('OSC_DEBUG', false);
    }

    if( !defined('OSC_DEBUG_LOG') ) {
        define('OSC_DEBUG_LOG', false);
    }

    if( !defined('OSC_MEMORY_LIMIT') ) {
        define('OSC_MEMORY_LIMIT', '32M');
    }

    if( function_exists('memory_get_usage') && ( (int) @ini_get('memory_limit') < abs(intval(OSC_MEMORY_LIMIT)) ) ) {
        @ini_set('memory_limit', OSC_MEMORY_LIMIT);
    }

    if( !defined('CLI') ) {
        define('CLI', false);
    }

    if( !defined('OSC_CACHE_TTL') ) {
        define('OSC_CACHE_TTL', 60);
    }
