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

    class AdminSecBaseModel extends SecBaseModel
    {
	    function __construct() {
		    parent::__construct() ;

            $this->add_css('backoffice.css') ;
            $this->add_global_css('jquery-ui.css') ;
            $this->add_global_js('jquery.js') ;
            $this->add_global_js('jquery-ui.js') ;
            $this->add_js('jquery.cookie.js') ;
            $this->add_js('jquery.json.js') ;
	    }

	    function isLogged() {
            return osc_is_admin_user_logged_in() ;
	    }

	    function showAuthFailPage() {
            require osc_admin_base_path() . 'gui/login.php' ;
            exit ;
        }

        function osc_get_theme_path($file) {
            if (file_exists(osc_current_admin_theme_path() . $file)) {
                return osc_current_admin_theme_path() . $file ;
            } else {
                return osc_admin_base_path() . 'gui/' . $file ;
            }
        }

        function osc_get_theme_url($file) {
            if (file_exists(osc_current_admin_theme_path() . $file)) {
                return osc_current_admin_theme_url() . $file ;
            } else {
                return osc_admin_base_url() . 'gui/' . $file ;
            }
        }
    }

?>
