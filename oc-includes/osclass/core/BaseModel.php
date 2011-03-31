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


        function  __construct() {
            // Moved Session start to oc-load to be able to use it on index.php
            //Session::newInstance()->session_start() ;
            $this->action = Params::getParam('action') ;
        }

        //to export variables at the business layer
        function _exportVariableToView($key, $value) {
            View::newInstance()->_exportVariableToView($key, $value) ;
        }

        //only for debug (deprecated, all inside View.php)
        function _view($key = null) {
            View::newInstance()->_view($key) ;
        }

        //Funciones que se tendran que reescribir en la clase que extienda de esta
        protected abstract function doModel() ;
        protected abstract function doView($file) ;

        function do404() {
            header('HTTP/1.1 404 Not Found') ;
            osc_current_web_theme_path('404.php') ;
        }
        
        function redirectTo($url) {
            header('Location: ' . $url) ;
            exit ;
        }
    }

?>
