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
    private $instance ;

    public static function newInstance() {
        if(!self::$instance instanceof self) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }

    public function __construct() {
        $this->session = $_SESSION ;
    }
    
    function session_start() {
        session_start() ;
    }

    function session_destroy() {
        session_destroy() ;
    }

    function _set($key, $value) {
        $_SESSION[$key] = $this->session[$key] = $value ;
        
    }

    function _get($key) {
        if (!isset($this->session[$key])) {
            return '' ;
        }

        return ($this->session[$key]) ;
    }
}





if(defined('OC_SESSION_INC')) {
	_e('defined session');
	return;
} else
	define('OC_SESSION_INC', true);

session_name('osclass');
session_start();

$adminTheme = osc_paramSession('adminTheme', 'default');

/**
 * Adds an ephemeral message to the session.
 */
function osc_addFlashMessage($msg, $section = 'pubMessages') {
	if(!isset($_SESSION[$section]))
		$_SESSION[$section] = array();

	$msg = null;
	$argv = func_get_args();
	switch(func_num_args()) {
		case 0: return; break;
		case 1: $msg = $argv[0]; break;
		default:
			$format = array_shift($argv);
			$msg = vsprintf($format, $argv);
			break;
	}

	$_SESSION[$section][] = $msg;
}

/**
 * Shows all the pending flash messages in session and cleans up the array.
 */
function osc_show_flash_messages($section = 'pubMessages', $class = "FlashMessage", $id = "FlashMessage") {
	if(!isset($_SESSION[$section]) || !count($_SESSION[$section])) return;

	echo "<div id='$id' class='$class'>";
	foreach($_SESSION[$section] as $msg) {
		echo $msg . '<br />';
	}
	echo '</div>';

	unset($_SESSION[$section]);
}

/**
 * Shows all the pending flash messages in session and cleans up the array.
 */
function osc_hasFlashMessages($section = 'pubMessages') {
	if(!isset($_SESSION[$section]) || !count($_SESSION[$section])) {
        return false;
    } else {
        return true;
    }
}

/**
 * @return true if the user has logged in.
 */
function osc_isUserLoggedIn() {
	if(isset($_SESSION['userId'])) return true;

	if(isset($_COOKIE['oc_userId']) && isset($_COOKIE['oc_userSecret'])) {
		$user = User::newInstance()->findByIdSecret($_COOKIE['oc_userId'], $_COOKIE['oc_userSecret']);
		if($user) {
			$_SESSION['userId'] = $_COOKIE['oc_userId'];
			return true;
		}
	}

	return false;
}

function osc_userInfo($property) {
	static $user = null;
	if(is_null($user)) {
		require_once LIB_PATH . 'osclass/model/User.php';
		$manager = User::newInstance();
		$user = $manager->findByPrimaryKey($_SESSION['userId']);
	}
	return $user[$property];
}

function osc_checkAdminSession() {
	// This is a simply but effective security check
	if(!isset($_SESSION) || !isset($_SESSION['adminId'])) {
		header('Location: index.php');
		exit;
	}
}

