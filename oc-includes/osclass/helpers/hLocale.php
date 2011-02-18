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

    function osc_locale_field($field, $locale = '') {
        return osc_field(osc_locale(), $field, $locale) ;
    }

    function osc_locale() {
        if (View::newInstance()->_exists('locales')) {
            $locale = View::newInstance()->_current('locales') ;
        } else {
            $locale = View::newInstance()->_get('locale') ;
        }

        return($locale) ;
    }

    function osc_priv_count_locales() {
        return View::newInstance()->_count('locales') ;
    }

    function osc_goto_first_locale() {
        View::newInstance()->_reset('locales') ;
    }

    //SELECT OF LOCALES AT ALL THE PAGES
    function osc_count_web_enabled_locales() {
        if ( !View::newInstance()->_exists('locales') ) {
            View::newInstance()->_exportVariableToView('locales', Locale::newInstance()->listAllEnabled() ) ;
        }
        return osc_priv_count_locales() ;
    }

    function osc_has_web_enabled_locales() {
        if ( !View::newInstance()->_exists('locales') ) {
            View::newInstance()->_exportVariableToView('locales', Locale::newInstance()->listAllEnabled() ) ;
        }
        
        return View::newInstance()->_next('locales') ;
    }

    function osc_locale_code() {
        return osc_locale_field("pk_c_code") ;
    }

    function osc_locale_name() {
        return osc_locale_field("s_name") ;
    }

    function osc_all_enabled_locales_for_admin($indexed_by_pk = false) {
        return ( Locale::newInstance()->listAllEnabled(true, $indexed_by_pk)) ;
    }

?>