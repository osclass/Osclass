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

    class WebThemes extends Themes
    {
        private static $instance;

        private $pages = array( '404',
                                'contact',
                                'alert-form',
                                'custom',
                                'footer',
                                'functions',
                                'head',
                                'header',
                                'inc.search',
                                'index',
                                'item-contact',
                                'item-edit',
                                'item-post',
                                'item-send-friend',
                                'item',
                                'main',
                                'page',
                                'search',
                                'search_gallery',
                                'search_list',
                                'user-alerts',
                                'user-change_email',
                                'user-change_password',
                                'user-dashboard',
                                'user-forgot_password',
                                'user-items',
                                'user-login',
                                'user-profile',
                                'user-recover',
                                'user-register',
                                );

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
            $this->path = osc_themes_path();

            if( Params::getParam('theme') != '' && Session::newInstance()->_get('adminId') != '' ) {
                $this->setCurrentTheme( Params::getParam('theme') );
            } else {
                $this->setCurrentTheme( osc_theme() );
            }

            $functions_path = $this->getCurrentThemePath() . 'functions.php';
            if( file_exists($functions_path) ) {
                require_once $functions_path;
            }

            $info = $this->loadThemeInfo($this->theme);
            if(isset($info['template']) && $info['template'] != '' ) {
				//$this->setCurrentTheme($info['template']);
				$parent_functions_path = osc_base_path() . 'oc-content/themes/' . $info['template'] . '/functions.php';
				if( file_exists($parent_functions_path) ) {
					require_once $parent_functions_path;
				}
			}
        }

        public function setCurrentThemePath()
        {
            if ( file_exists( $this->path . $this->theme . '/' ) ) {
                $this->theme_exists = true;
                $this->theme_path   = $this->path . $this->theme . '/';
            } else {
                $this->theme_exists = false;
                $this->theme_path   = osc_lib_path() . 'osclass/gui/';
            }
        }

        public function setCurrentThemeUrl()
        {
            if ( $this->theme_exists ) {
                $this->theme_url = osc_apply_filter('theme_url', osc_base_url() . str_replace(osc_base_path(), '', $this->theme_path));
            } else {
                $this->theme_url = osc_apply_filter('theme_url', osc_base_url() . 'oc-includes/osclass/gui/');
            }
        }

        /* PUBLIC */
        public function setPath($path)
        {
            if( file_exists($path) ) {
                $this->path = $path;
                return true;
            }

            return false;
        }

        public function setCurrentTheme($theme)
        {
            $this->theme = $theme;
            $this->setCurrentThemePath();
            $this->setCurrentThemeUrl();
        }

        public function setGuiTheme()
        {
            $this->theme = '';

            $this->theme_exists = false;
            $this->theme_path   = osc_lib_path() . 'osclass/gui/';
            $this->theme_url    = osc_base_url() . 'oc-includes/osclass/gui/';

            $functions_path = $this->getCurrentThemePath() . 'functions.php';
            if( file_exists($functions_path) ) {
                require_once $functions_path;
            }
        }

        public function setParentTheme()
        {
			$info = $this->loadThemeInfo($this->theme);

            $this->theme = $info['template'];

            $this->theme_exists = true;
            $this->theme_path   = $this->path . $this->theme . '/';
            $this->theme_url    = osc_base_url() . str_replace(osc_base_path(), '', $this->theme_path);

            //$functions_path = $this->getCurrentThemePath() . 'functions.php';
            //if( file_exists($functions_path) ) {
              //  require_once $functions_path;
            //}
        }

        /**
         * This function returns an array of themes (those copied in the oc-content/themes folder)
         * @return <type>
         */
        public function getListThemes()
        {
            $themes = array();
            $dir    = opendir( $this->path );
            while ($file = readdir($dir)) {
                if (preg_match('/^[a-zA-Z0-9_]+$/', $file)) {
                    $themes[] = $file;
                }
            }
            closedir($dir);
            return $themes;
        }

        /**
         *
         * @param <type> $theme
         * @return <type>
         */
        function loadThemeInfo($theme)
        {
            $path = $this->path . $theme . '/index.php';
            if( !file_exists($path) ) {
                return false;
            }

            // NEW CODE FOR THEME INFO
            $s_info = file_get_contents($path);
            $info   = array();
            if( preg_match('|Theme Name:([^\\r\\t\\n]*)|i', $s_info, $match) ) {
                $info['name'] = trim($match[1]);
            } else {
                $info['name'] = "";
            }

            if( preg_match('|Parent Theme:([^\\r\\t\\n]*)|i', $s_info, $match) ) {
                $info['template'] = trim($match[1]);
            } else {
                $info['template'] = "";
            }

            if( preg_match('|Theme URI:([^\\r\\t\\n]*)|i', $s_info, $match) ) {
                $info['theme_uri'] = trim($match[1]);
            } else {
                $info['theme_uri'] = "";
            }

            if( preg_match('|Theme update URI:([^\\r\\t\\n]*)|i', $s_info, $match) ) {
                $info['theme_update_uri'] = trim($match[1]);
            } else {
                $info['theme_update_uri'] = "";
            }

            if( preg_match('|Description:([^\\r\\t\\n]*)|i', $s_info, $match) ) {
                $info['description'] = trim($match[1]);
            } else {
                $info['description'] = "";
            }

            if( preg_match('|Version:([^\\r\\t\\n]*)|i', $s_info, $match) ) {
                $info['version'] = trim($match[1]);
            } else {
                $info['version'] = "";
            }

            if( preg_match('|Author:([^\\r\\t\\n]*)|i', $s_info, $match) ) {
                $info['author_name'] = trim($match[1]);
            } else {
                $info['author_name'] = "";
            }

            if( preg_match('|Author URI:([^\\r\\t\\n]*)|i', $s_info, $match) ) {
                $info['author_url'] = trim($match[1]);
            } else {
                $info['author_url'] = "";
            }

            if( preg_match('|Widgets:([^\\r\\t\\n]*)|i', $s_info, $match) ) {
                $info['locations'] = explode(",", str_replace(" ", "", $match[1]));
            } else {
                $info['locations'] = array();
            }
            $info['filename'] = $path;
            $info['int_name'] = $theme;

            if($info['name']!='') {
                return $info;
            }

            // OLD CODE INFO
            require_once $path;
            $fxName = $theme . '_theme_info';
            if (!function_exists($fxName)) {
                return false;
            }
            $result = call_user_func($fxName);
            $result['int_name'] = $theme;

            return $result;
        }

        function isValidPage($internal_name)
        {
            return !in_array($internal_name, $this->pages);
        }

        function getAvailableTemplates($theme = null)
        {
            if($theme==null) { $theme = $this->theme; };

            $templates = array();
            $dir = opendir( $this->path . $theme . "/" );
            while ($file = readdir($dir)) {
                if (preg_match('/^template-[a-zA-Z0-9_\.]+$/', $file)) {
                    $templates[] = $file;
                }
            }
            closedir($dir);
            return $templates;

        }

    }

    /* file end: ./oc-includes/osclass/WebThemes.php */