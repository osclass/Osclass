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

    class AdminThemes extends Themes
    {
        private static $instance;

        public static function newInstance()
        {
            if(!self::$instance instanceof self) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public function __construct()
        {
            parent::__construct();
            $this->setCurrentTheme( osc_admin_theme() );
        }

        public function setCurrentThemeUrl()
        {
            if ($this->theme_exists) {
                $this->theme_url = osc_admin_base_url() . 'themes/' . $this->theme . '/';
            } else {
                $this->theme_url = osc_admin_base_url() . 'gui/';
            }
        }

        public function setCurrentThemePath()
        {
            if (file_exists(osc_admin_base_path() . 'themes/' . $this->theme . '/')) {
                $this->theme_exists = true;
                $this->theme_path = osc_admin_base_path() . 'themes/' . $this->theme . '/';
            } else {
                $this->theme_exists = false;
                $this->theme_path = osc_admin_base_path() . 'gui/';
            }
        }
    }

    /* file end: ./oc-includes/osclass/AdminThemes.php */