<?php

    class WebSecBaseModel extends SecBaseModel
    {
	    function __construct() {
		    parent::__construct() ;
	    }

	    function isLogged() {
            return osc_is_web_user_logged_in() ;
	    }

	    function showAuthFailPage() {
            $this->redirectTo( osc_user_login_url() ) ;
        }
    }

?>