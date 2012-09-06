<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    class WebThemes
    {
        private static $instance ;
        private $path ;
        private $theme ;
        private $theme_url ;
        private $theme_path ;
        private $theme_exists ;
        private $scripts;
        private $styles;
        
        private $resolved;
        private $unresolved;

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
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        public function __construct()
        {
            $this->scripts = array();
            $this->styles = array();
            $this->path = osc_themes_path();

            if( Params::getParam('theme') != '' && Session::newInstance()->_get('adminId') != '' ) {
                $this->setCurrentTheme( Params::getParam('theme') ) ;
            } else {
                $this->setCurrentTheme( osc_theme() ) ;
            }

            $functions_path = $this->getCurrentThemePath() . 'functions.php';
            if( file_exists($functions_path) ) {
                require_once $functions_path ;
            }
        }

        /* PRIVATE */
        private function setCurrentThemePath()
        {
            if ( file_exists( $this->path . $this->theme . '/' ) ) {
                $this->theme_exists = true ;
                $this->theme_path   = $this->path . $this->theme . '/' ;
            } else {
                $this->theme_exists = false ;
                $this->theme_path   = osc_lib_path() . 'osclass/gui/' ;
            }
        }

        private function setCurrentThemeUrl()
        {
            if ( $this->theme_exists ) {
                $this->theme_url = osc_base_url() . str_replace(osc_base_path(), '', $this->theme_path) ;
            } else {
                $this->theme_url = osc_base_url() . 'oc-includes/osclass/gui/' ;
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
            $this->theme = $theme ;
            $this->setCurrentThemePath() ;
            $this->setCurrentThemeUrl() ;
        }

        public function setGuiTheme()
        {
            $this->theme = '' ;

            $this->theme_exists = false;
            $this->theme_path   = osc_lib_path() . 'osclass/gui/' ;
            $this->theme_url    = osc_base_url() . 'oc-includes/osclass/gui/' ;

            $functions_path = $this->getCurrentThemePath() . 'functions.php';
            if( file_exists($functions_path) ) {
                require_once $functions_path;
            }
        }

        public function getCurrentTheme()
        {
            return $this->theme ;
        }

        public function getCurrentThemeUrl()
        {
            return $this->theme_url ;
        }

        public function getCurrentThemePath()
        {
            return $this->theme_path ;
        }

        public function getCurrentThemeStyles()
        {
            return $this->theme_url . 'css/' ;
        }

        public function getCurrentThemeJs()
        {
            return $this->theme_url . 'js/' ;
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

        
        /**
         * Add style to be loaded
         * 
         * @param type $id
         * @param type $url 
         */
        public function addStyle($id, $url) {
            $this->styles[$id] = $url;
        }
        
        /**
         * Remove style to not be loaded
         * 
         * @param type $id 
         */
        public function removeStyle($id) {
            unset($this->styles[$id]);
        }
        
        /**
         * Print the HTML tags to load the styles 
         */
        public function printStyles() {
            foreach($this->styles as $css) {
                echo '<link href="'.$css.'" rel="stylesheet" type="text/css" />' . PHP_EOL;
            }
        }
        
        /**
         * Add script to be loaded
         * 
         * @param type $id
         * @param type $url
         * @param type $dependencies mixed, it could be an array or a string
         */
        public function addScript($id, $url, $dependencies = null) {
            $this->scripts[$id] = array(
                'key' => $id
                ,'url' => $url
                ,'dependencies' => $dependencies
            );
        }
        
        /**
         * Remove script to not be loaded
         * 
         * @param type $id 
         */
        public function removeScript($id) {
            unset($this->scripts[$id]);
        }
        
        /**
         * Order script before being printed on the HTML
         */
        private function orderScripts() {
            $this->resolved = array();
            $this->unresolved = array();
            $this->error = array();
            foreach($this->scripts as $node) {
                if($node['dependencies']==null) {
                    $this->resolved[$node['key']] = $node['key'];
                } else {
                    $this->solveDeps($node);
                }
            }
            if(!empty($this->error)) {
                _e('ERROR: There was a circular dependency, some script depends on other script that depends on the first one');                
            }
        }
        
        /**
         *  Print the HTML tags to load the scripts
         */
        public function printScripts() {
            $this->orderScripts();
            foreach($this->resolved as $id) {
                if(isset($this->scripts[$id]['url'])) {
                    echo '<script type="text/javascript" src="'.$this->scripts[$id]['url'].'"></script>' . PHP_EOL;
                }
            }
        }
        
        /**
         * Algorithm to solve the dependencies of the scripts
         * 
         * @param type $node 
         */
        private function solveDeps($node) {
            $error = false;
            if(!isset($this->resolved[$node['key']])) {
                $this->unresolved[$node['key']] = $node['key'];
                if($node['dependencies']!=null) {
                    if(is_array($node['dependencies'])) {
                        foreach($node['dependencies'] as $dep) {
                            if(!in_array($dep, $this->resolved)) {
                                if(in_array($dep, $this->unresolved)) {
                                    $this->error[$dep] = $dep;
                                    $error = true;
                                } else {
                                    $this->solveDeps($this->scripts[$dep]);
                                }
                            }
                        }
                    } else {
                        if(!in_array($node['dependencies'], $this->resolved)) {
                            if(in_array($node['dependencies'], $this->unresolved)) {
                                $this->error[$node['dependencies']] = $node['dependencies'];
                                $error = true;
                            } else {
                                $this->solveDeps($this->scripts[$node['dependencies']]);
                            }
                        }
                    }
                }
                if(!$error) {
                    $this->resolved[$node['key']] = $node['key'];
                    unset($this->unresolved[$node['key']]);
                }
            }
        }
        
        
    }

    /* file end: ./oc-includes/osclass/WebThemes.php */
?>