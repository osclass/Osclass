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

    abstract class Themes
    {
        private static $instance;
        protected $theme;
        protected $theme_url;
        protected $theme_path;
        protected $theme_exists;

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
            $this->theme = $theme;
            $this->setCurrentThemePath();
            $this->setCurrentThemeUrl();
        }

        public function getCurrentTheme()
        {
            return $this->theme;
        }

        public function getCurrentThemeUrl()
        {
            return $this->theme_url;
        }

        public function getCurrentThemePath()
        {
            return $this->theme_path;
        }

        public function getCurrentThemeStyles()
        {
            return $this->theme_url . 'css/';
        }

        public function getCurrentThemeJs()
        {
            return $this->theme_url . 'js/';
        }

        /**
         * Add style to be loaded
         *
         * @param type $id
         * @param type $url
         * @deprecated deprecated since version 3.1
         */
        public function addStyle($id, $url)
        {
            $this->styles[$id] = $url;
        }

        /**
         * Remove style to not be loaded
         *
         * @param type $id
         * @deprecated deprecated since version 3.1
         */
        public function removeStyle($id)
        {
            unset($this->styles[$id]);
        }

        /**
         * Get the css styles urls
         *
         * @deprecated deprecated since version 3.1
         */
        public function getStyles()
        {
            return Styles::newInstance()->getStyles();
        }

        /**
         * Print the HTML tags to load the styles
         *
         * @deprecated deprecated since version 3.1
         */
        public function printStyles()
        {
            foreach($this->styles as $css) {
                echo '<link href="'.$css.'" rel="stylesheet" type="text/css" />' . PHP_EOL;
            }
        }

        /**
         * Add script to queue
         *
         * @param type $id
         * @deprecated deprecated since version 3.1
         */
        public function enqueueScript($id)
        {
            $this->queue[$id] = $id;
        }

        /**
         * Remove script to not be loaded
         *
         * @param type $id
         * @deprecated deprecated since version 3.1
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
         * @deprecated deprecated since version 3.1
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
         * @deprecated deprecated since version 3.1
         */
        public function unregisterScript($id)
        {
            unset($this->scripts[$id]);
        }

        /**
         * Order script before being printed on the HTML
         * @deprecated deprecated since version 3.1
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
         * Get the scripts urls
         * @deprecated deprecated since version 3.1
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
         * Print the HTML tags to load the scripts
         *
         * @deprecated deprecated since version 3.1
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
         * @deprecated deprecated since version 3.1
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