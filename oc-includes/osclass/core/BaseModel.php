<?php

    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
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

    abstract class BaseModel
    {
        //action to execute
        protected $action ;
        //array of css
        protected $aCss ;
        //array of js
        protected $aJs ;


        function  __construct() {
            Session::newInstance()->session_start() ;
            $this->action = Params::getParam('action') ;
            $this->aCss = array() ;
            $this->aJs = array() ;
        }

        //to export variables at the business layer
        function _exportVariableToView($key, $value) {
            View::newInstance()->_exportVariableToView($key, $value) ;
        }

        /*
        //to get the exported variables for the view (deprecated, all inside View.php)
        function _get($key) {
            return ( View::newInstance()->_get($key) ) ;
        }*/

        //only for debug (deprecated, all inside View.php)
        function _view($key = null) {
            View::newInstance()->_view($key) ;
        }

        //Funciones que se tendran que reescribir en la clase que extienda de esta
        protected abstract function doModel() ;
        protected abstract function doView($file) ;

        function redirectTo($url) {
            header('Location: ' . $url) ;
            exit ;
        }

        /*
        function osc_get_theme_path($file) {
            if (file_exists(osc_current_web_theme_path() . $file)) {
                return osc_current_web_theme_path() . $file ;
            } else {
                return osc_base_path() . 'oc-includes/osclass/gui/' . $file ;
            }
        }

        function osc_get_theme_url($file) {
            if (file_exists(osc_current_web_theme_path() . $file)) {
                return osc_current_web_theme_url() . $file ;
            } else {
                return osc_base_url() . 'oc-includes/osclass/gui/' . $file ;
            }
        }

        */

        /*function osc_print_head() {
            require $this->osc_get_theme_path('head.php') ;
        }

        function osc_print_header() {
            require $this->osc_get_theme_path('header.php') ;
        }

        function osc_print_html($file) {
            require $this->osc_get_theme_path($file) ;
        }

        function osc_print_footer() {
            require $this->osc_get_theme_path('footer.php') ;
        }

        function add_css($css_filename) {
            $this->aCss[] = $this->osc_get_theme_url('css/' . $css_filename) ;
        }

        function add_js($js_filename) {
            $this->aJs[] = $this->osc_get_theme_url('js/' . $js_filename) ;
        }

        function add_global_css($css_filename) {
            $this->aCss[] = osc_css_url() . $css_filename ;
        }

        function add_global_js($js_filename) {
            $this->aJs[] = osc_js_url() . $js_filename ;
        }

        function get_css() {
            return ( $this->aCss ) ;
        }

        function get_js() {
            return ( $this->aJs ) ;
        }
        */
    }

?>