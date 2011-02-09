<?php

    class AdminSecBaseModel extends SecBaseModel
    {
	    var $section ;
		
	    function __construct() {
		    parent::__construct() ;
	    }

	    function isLogged() {
            if (Session::newInstance()->_get("adminId") == '') return false ;
            return true ;
	    }

	    function showAuthFailPage() {
            require( osc_admin_base_path() . 'gui/login.php' ) ;
            exit ;
        }
    }

?>