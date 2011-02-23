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

    function osc_static_page() {
        if (View::newInstance()->_exists('pages')) {
            $page = View::newInstance()->_current('pages') ;
        } else if (View::newInstance()->_exists('page')) {
            $page = View::newInstance()->_get('page') ;
        } else {
            $page = null ;
        }
        return($page) ;
    }
    
    function osc_static_page_field($field, $locale = '') {
        return osc_field(osc_static_page(), $field, $locale) ;
    }

    function osc_static_page_title($locale = '') {
        if ($locale == "") $locale = osc_get_user_locale() ;
        return osc_static_page_field("s_title", $locale) ;
    }

    function osc_static_page_text($locale = '') {
        if ($locale == "") $locale = osc_get_user_locale() ;
        return osc_static_page_field("s_text", $locale) ;
    }

    function osc_static_page_id() {
        return osc_static_page_field("pk_i_id") ;
    }

?>