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
            echo "isLogged de AdminSecBaseModel" ;
	    	if (Session::newInstance()->_get("adminId") == '') return false ;
            return true ;
	    }

	    function showAuthFailPage() {
	    	echo "isLogged de showAuthFailPage" ;
            osc_redirectTo(osc_admin_base_url (true)) ;
	    }
    }

?>