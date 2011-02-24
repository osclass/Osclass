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

    function osc_static_page_url() {
        if(osc_rewrite_enabled()) {
            return osc_base_url().osc_static_page_field("s_internal_name")."-p".osc_static_page_field("pk_i_id");
        } else {
            return osc_base_url(true)."?page=page&id=".osc_static_page_field("pk_i_id");
        }
    }
    
    /**
     * Gets the specified static page by internal name.
     *
     * @return <boolean>
     */
    function osc_get_static_page($internal_name, $locale = '') {
        if ($locale == "") $locale = osc_get_user_locale() ;
        return View::newInstance()->_exportVariableToView('page', Page::newInstance()->findByInternalName($internal_name, $locale) ) ;
    }    
    
    /**
     * Gets the total of static pages. If static pages are not loaded, this function will load them.
     *
     * @return <int>
     */
    function osc_count_static_pages() {
        if ( !View::newInstance()->_exists('pages') ) {
            View::newInstance()->_exportVariableToView('pages', Page::newInstance()->listAll(0) ) ;
        }
        return View::newInstance()->_count('pages') ;
    }

    /**
     * Let you know if there are more static pages in the list. If static pages are not loaded, this function will load them.
     *
     * @return <boolean>
     */
    function osc_has_categories() {
        if ( !View::newInstance()->_exists('pages') ) {
            View::newInstance()->_exportVariableToView('pages', Page::newInstance()->listAll(0) ) ;
        }
        
        return View::newInstance()->_next('pages') ;
    }

?>
