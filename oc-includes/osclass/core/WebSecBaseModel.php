<?php

    class WebSecBaseModel extends SecBaseModel
    {
	    function __construct() {
		    parent::__construct() ;
	    }

	    function isLogged() {
            return osc_is_user_logged_in() ;
	    }

	    function showAuthFailPage() {
            if (file_exists(osc_current_web_theme_path() . 'user-login.php')) {
                require osc_current_web_theme_path() . 'user-login.php' ;
            } else {
                require osc_base_path() . 'oc-includes/osclass/gui/user-login.php' ;
            }
            exit ;
        }
    }

?>