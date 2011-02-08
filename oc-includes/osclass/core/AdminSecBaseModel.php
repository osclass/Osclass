<?php

    class AdminSecBaseModel extends SecBaseModel
    {
	    var $section ;
		
	    function __construct()
	    {
		    parent::__construct() ;

	    }

	    function isLogged()
	    {
	    	if (Session::newInstance()->_get("adminId") == '') return false ;
            return true ;
	    }

	    function showAuthFailPage() {
	    	osc_redirectTo(osc_admin_base_url (true)) ;
	    }

		function logout() {
            Session::newInstance()->session_destroy() ;
		}
    }

?>