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
            require osc_admin_base_path() . 'gui/login.php' ;
            exit ;
        }

        function osc_print_head() {
            require osc_current_admin_theme_path() . 'head.php' ;
        }

        function osc_print_header() {
            require osc_current_admin_theme_path() . 'header.php' ;
        }

        function osc_print_html($file) {
            require osc_current_admin_theme_path() . $file ;
        }

        function osc_print_footer() {
            require osc_current_admin_theme_path() . 'footer.php' ;
        }
    }

?>