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

    abstract class Themes
    {
        private static $instance ;
        protected $theme ;
        protected $theme_url ;
        protected $theme_path ;
        protected $theme_exists ;

        protected $scripts;
        protected $queue;
        protected $styles;

        protected $resolved;
        protected $unresolved;

        public function __construct()
        {
            $this->scripts = array();
            $this->queue   = array();
            $this->styles  = array();
        }

        abstract protected function setCurrentThemeUrl();
        abstract protected function setCurrentThemePath();

        /* PUBLIC */
        public function setCurrentTheme($theme)
        {
            $this->theme = $theme ;
            $this->setCurrentThemePath() ;
            $this->setCurrentThemeUrl() ;
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
         * Add style to be loaded
         *
         * @param type $id
         * @param type $url
         */
        public function addStyle($id, $url)
        {
            $this->styles[$id] = $url;
        }

        /**
         * Remove style to not be loaded
         *
         * @param type $id
         */
        public function removeStyle($id)
        {
            unset($this->styles[$id]);
        }

        /**
         * Get the css styles urls
         */
        public function getStyles()
        {
            return $this->styles;
        }

        /**
         * Print the HTML tags to load the styles
         */
        public function printStyles()
        {
            foreach($this->styles as $css) {
                echo '<link href="'.$css.'" rel="stylesheet" type="text/css" />' . PHP_EOL;
            }
        }

        public function enqueueScript($id)
        {
            $this->queue[$id] = $id;
        }

        /**
         * Remove script to not be loaded
         *
         * @param type $id
         */
        public function removeScript($id)
        {
            unset($this->queue[$id]);
        }

        /**
         * Add script to be loaded
         *
         * @param type $id
         * @param type $url
         * @param type $dependencies mixed, it could be an array or a string
         */
        public function registerScript($id, $url, $dependencies = null)
        {
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
        public function unregisterScript($id)
        {
            unset($this->scripts[$id]);
        }

        /**
         * Order script before being printed on the HTML
         */
        private function orderScripts()
        {
            $this->resolved = array();
            $this->unresolved = array();
            $this->error = array();
            foreach($this->queue as $queue) {
                if(isset($this->scripts[$queue])) {
                    $node = $this->scripts[$queue];
                    if($node['dependencies']==null) {
                        $this->resolved[$node['key']] = $node['key'];
                    } else {
                        $this->solveDeps($node);
                    }
                } else {
                    $this->error[$queue] = $queue;
                }
            }
            if(!empty($this->error)) {
                echo sprintf(__('ERROR: Some scripts could not be loaded (%s)'), implode(", ", $this->error));
            }
        }

        /**
         *  Get the scripts urls
         */
        public function getScripts()
        {
            $scripts = array();
            $this->orderScripts();
            foreach($this->resolved as $id) {
                if( isset($this->scripts[$id]['url']) ) {
                    $scripts[] = $this->scripts[$id]['url'];
                }
            }
            return $scripts;
        }

        /**
         *  Print the HTML tags to load the scripts
         */
        public function printScripts()
        {
            foreach($this->getScripts() as $script) {
                echo '<script type="text/javascript" src="' . $script . '"></script>' . PHP_EOL;
            }
        }

        /**
         * Algorithm to solve the dependencies of the scripts
         *
         * @param type $node
         */
        private function solveDeps($node)
        {
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
                                    if(isset($this->scripts[$dep])) {
                                        $this->solveDeps($this->scripts[$dep]);
                                    } else {
                                        $this->error[$dep] = $dep;
                                    }
                                }
                            }
                        }
                    } else {
                        if(!in_array($node['dependencies'], $this->resolved)) {
                            if(in_array($node['dependencies'], $this->unresolved)) {
                                $this->error[$node['dependencies']] = $node['dependencies'];
                                $error = true;
                            } else {
                                if(isset($this->scripts[$node['dependencies']])) {
                                    $this->solveDeps($this->scripts[$node['dependencies']]);
                                } else {
                                    $this->error[$node['dependencies']] = $node['dependencies'];
                                }
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

    /* file end: ./oc-includes/osclass/Themes.php */