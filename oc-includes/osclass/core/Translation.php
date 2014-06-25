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

    require_once osc_lib_path() . 'php-gettext/streams.php';
    require_once osc_lib_path() . 'php-gettext/gettext.php';

    class Translation {
        private $messages;
        private static $instance;

        public static function newInstance($install = false) {
            if(!self::$instance instanceof self) {
                self::$instance = new self($install);
            }
            return self::$instance;
        }

        function __construct($install = false) {
            if(!$install) {
                // get user/admin locale
                if( OC_ADMIN ) {
                    $locale = osc_current_admin_locale();
                } else {
                    $locale = osc_current_user_locale();
                }

                // load core
                $core_file = osc_translations_path() . $locale . '/core.mo';
                $this->_load($core_file, 'core');

                // load messages
                $messages_file = osc_themes_path() . osc_theme() . '/languages/' . $locale . '/messages.mo';
                if(!file_exists($messages_file)) {
                    $messages_file = osc_translations_path() . $locale . '/messages.mo';
                }
                $this->_load($messages_file, 'messages');

                // load theme
                $domain = osc_theme();
                $theme_file = osc_themes_path() . $domain . '/languages/' . $locale . '/theme.mo';
                if(!file_exists($theme_file)) {
                    if(!file_exists(osc_themes_path() . $domain)) {
                        $domain = 'modern';
                    }
                    $theme_file = osc_translations_path() . $locale . '/theme.mo';
                }
                $this->_load($theme_file, $domain);

                // load plugins
                $aPlugins = Plugins::listEnabled();
                foreach($aPlugins as $plugin) {
                    $domain = preg_replace('|/.*|', '', $plugin);
                    $plugin_file = osc_plugins_path() . $domain . '/languages/' . $locale . '/messages.mo';
                    if(file_exists($plugin_file) ) {
                        $this->_load($plugin_file, $domain);
                    }
                }
            } else {
                $core_file = osc_translations_path() . osc_current_admin_locale() . '/core.mo';
                $this->_load($core_file, 'core');
            }
        }

        function _get($domain) {
            if(!isset($this->messages[$domain])) {
                return false;
            }

            return $this->messages[$domain];
        }

        function _set($domain, $reader) {
            if(isset($messages[$domain])) {
               false;
            }

            $this->messages[$domain] = $reader;
            return true;
        }

        function _load($file, $domain) {
            if(!file_exists($file)) {
                return false;
            }

            $streamer = new FileReader($file);
            $reader = new gettext_reader($streamer);
            return $this->_set($domain, $reader);
        }
    }

?>