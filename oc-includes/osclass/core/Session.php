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

class Session {
    //attributes
    private $session ;
    private static $instance ;

    public static function newInstance() {
        if(!self::$instance instanceof self) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }

    public function __construct() {}
    
    function session_start() {
        session_name('osclass') ;
        session_start() ;

        $this->session = $_SESSION ;
        if ($this->_get('messages') == '') {
            $this->_set( 'messages', array() ) ;
        }
    }

    function session_destroy() {
        session_destroy() ;
    }

    function _set($key, $value) {
        $_SESSION[$key] = $value ;
        $this->session[$key] = $value ;
    }

    function _get($key) {
        if (!isset($this->session[$key])) {
            return '' ;
        }

        return ($this->session[$key]) ;
    }

    function _drop($key) {
        unset($_SESSION[$key]) ;
        unset($this->session[$key]) ;

    }

    function _view() {
        print_r($this->session) ;
        echo "\n" ;
        print_r($_SESSION) ;
    }

    function _setMessage($key, $value) {
        $messages = $this->_get('messages') ;
        $messages[$key] = $value ;
        $this->_set('messages', $messages) ;
    }

    function _getMessage($key) {
        $messages = $this->_get('messages') ;
        return ($messages[$key]) ;
    }

    function _dropMessage($key) {
        $messages = $this->_get('messages') ;
        unset($messages[$key]) ;
        $this->_set('messages', $messages) ;
    }

    function _viewMessage() {
        print_r($this->session['messages']) ;
        echo "\n" ;
        print_r($_SESSION['messages']) ;
    }
}

?>