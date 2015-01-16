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

    /**
     * AdminToolbar class
     *
     * @since 3.0
     * @package Osclass
     * @subpackage classes
     * @author Osclass
     */
    class AdminToolbar
    {
        private static $instance;
        private $nodes = array();

        public function __construct()
        {
        }

        public static function newInstance()
        {
            if(!self::$instance instanceof self) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public function init(){}

        /**
         * Add toolbar menus and add menus running hook add_admin_toolbar_menus
         */
        function add_menus()
        {
            // User related, aligned right.
            osc_add_hook( 'add_admin_toolbar_menus', 'osc_admin_toolbar_menu'    , 0 );
            osc_add_hook( 'add_admin_toolbar_menus', 'osc_admin_toolbar_comments', 0 );
            osc_add_hook( 'add_admin_toolbar_menus', 'osc_admin_toolbar_spam'    , 0 );

            osc_add_hook( 'add_admin_toolbar_menus', 'osc_admin_toolbar_update_core'        , 0 );

            osc_add_hook( 'add_admin_toolbar_menus', 'osc_admin_toolbar_update_themes'      , 0 );
            osc_add_hook( 'add_admin_toolbar_menus', 'osc_admin_toolbar_update_plugins'     , 0 );
            osc_add_hook( 'add_admin_toolbar_menus', 'osc_admin_toolbar_update_languages'   , 0 );

            osc_add_hook( 'add_admin_toolbar_menus', 'osc_admin_toolbar_logout'  , 0 );

            osc_run_hook( 'add_admin_toolbar_menus' );
        }
        /**
         * Add a node to the menu.
         *
         * @todo implement parent nodes
         *
         * @param array $args - The arguments for each node.
         * - id         - string    - The ID of the item.
         * - title      - string    - The title of the node.
         * - href       - string    - The link for the item. Optional.
         * - meta       - array     - Meta data including the following keys: html, class, onclick, target, title, tabindex.
         * - target     - string    - _blank
         */
        function add_menu( $array )
        {
                $this->nodes[ $array['id'] ] = (object) $array;
        }

        /**
         * Remove entry with id $id
         *
         * @param type $id
         */
        function remove_menu( $id )
        {
            unset( $this->nodes[ $id ] );
        }

        /**
         * Render admin toolbar
         *
         * <div>
         *   <a></a>
         * </div>
         */
        public function render()
        {
            if( count($this->nodes) > 0) {
                echo '<div id="header" class="navbar"><div class="header-wrapper">';
                foreach( $this->nodes as $value ) {
                    $meta = "";
                    if( isset($value->meta) ) {
                        foreach($value->meta as $k => $v)
                            $meta .= $k.'="'.$v.'" ';
                    }
                    echo '<a id="osc_toolbar_'.$value->id.'" '.$meta.' href="'.$value->href.'" ' . ((isset($value->target)) ? 'target="' . $value->target . '"' : '') . '>'.$value->title.'</a>';
                }
                osc_run_hook('render_admintoolbar');
                echo '</div></div>';
            }
        }
    }
?>