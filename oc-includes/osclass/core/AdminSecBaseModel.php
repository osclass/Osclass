<?php

    class AdminSecBaseModel extends SecBaseModel
    {
	    function __construct() {
		    parent::__construct() ;

            $this->add_css('backoffice.css') ;
            $this->add_global_css('jquery-ui.css') ;
            $this->add_global_js('jquery-1.4.2.js') ;
            $this->add_global_js('jquery-ui-1.8.5.js') ;
            $this->add_js('jquery.cookie.js') ;
            $this->add_js('jquery.json.js') ;
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