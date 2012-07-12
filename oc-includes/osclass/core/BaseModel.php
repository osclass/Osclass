<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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
        protected $page ;
        protected $action ;
        protected $ajax ;
        protected $time ;

        function __construct()
        {
            $this->page   = Params::getParam('page') ;
            $this->action = Params::getParam('action') ;
            $this->ajax   = false ;
            $this->time   = list($sm, $ss) = explode(' ', microtime()) ;
            WebThemes::newInstance() ;
            osc_run_hook( 'init' ) ;
        }

        function __destruct()
        {
            if( !$this->ajax && OSC_DEBUG ) {
                echo '<!-- ' . $this->getTime() . ' seg. -->' ;
            }
        }

        //to export variables at the business layer
        function _exportVariableToView($key, $value)
        {
            View::newInstance()->_exportVariableToView($key, $value) ;
        }

        //only for debug (deprecated, all inside View.php)
        function _view($key = null)
        {
            View::newInstance()->_view($key) ;
        }

        //Funciones que se tendran que reescribir en la clase que extienda de esta
        protected abstract function doModel() ;
        protected abstract function doView($file) ;

        function do404()
        {
            Rewrite::newInstance()->set_location('error');
            header('HTTP/1.1 404 Not Found') ;
            osc_current_web_theme_path('404.php') ;
        }

        function redirectTo($url)
        {
            header('Location: ' . $url) ;
            exit ;
        }

        function getTime()
        {
            $timeEnd = list($em, $es) = explode(' ', microtime()) ;
            return ($timeEnd[0] + $timeEnd[1]) - ($this->time[0] + $this->time[1]) ;
        }
    }

    /* file end: ./oc-includes/osclass/core/BaseModel.php */
?>