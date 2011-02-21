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

    function osc_category() {
        if (View::newInstance()->_exists('subcategories')) {
            $category = View::newInstance()->_current('subcategories') ;
        } elseif (View::newInstance()->_exists('categories')) {
            $category = View::newInstance()->_current('categories') ;
        } else {
            $category = View::newInstance()->_get('category') ;
        }

        return($category) ;
    }

    function osc_get_categories() {
       if ( !View::newInstance()->_exists('categories') ) {
            View::newInstance()->_exportVariableToView('categories', Category::newInstance()->toTree() ) ;
        }
        return  View::newInstance()->_get('categories') ;
    }
    
    /* #dev.conquer: review that. If the result of toTree had the same format as items or comments, it would be the same as osc_field */
    function osc_field_toTree($item, $field) {
        if(isset($item[$field])) {
            return $item[$field] ;
        }
        return '' ;
    }

    function osc_category_field($field, $locale = '') {
        return osc_field_toTree(osc_category(), $field) ;
    }

    function osc_priv_count_categories() {
        return View::newInstance()->_count('categories') ;
    }

    function osc_priv_count_subcategories() {
        return View::newInstance()->_count('subcategories') ;
    }

    function osc_count_categories() {
        if ( !View::newInstance()->_exists('categories') ) {
            View::newInstance()->_exportVariableToView('categories', Category::newInstance()->toTree() ) ;
        }
        return osc_priv_count_categories() ;
    }

    function osc_has_categories() {
        if ( !View::newInstance()->_exists('categories') ) {
            View::newInstance()->_exportVariableToView('categories', Category::newInstance()->toTree() ) ;
        }
        
        return View::newInstance()->_next('categories') ;
    }

    function osc_count_subcategories() {
        $category = View::newInstance()->_current('categories') ;
        if ( $category == '' ) return -1 ;
        if ( !isset($category['categories']) ) return 0 ;

        if ( !View::newInstance()->_exists('subcategories') ) {
            View::newInstance()->_exportVariableToView('subcategories', $category['categories']) ;
        }
        return osc_priv_count_subcategories() ;
    }

    function osc_has_subcategories() {
        $category = View::newInstance()->_current('categories') ;
        if ( $category == '' ) return -1 ;
        if ( !isset($category['categories']) ) return false ;

        if ( !View::newInstance()->_exists('subcategories') ) {
            View::newInstance()->_exportVariableToView('subcategories', $category['categories']) ;
        }
        $ret = View::newInstance()->_next('subcategories') ;
        //we have to delete for next iteration
        if (!$ret) View::newInstance()->_erase('subcategories') ;
        return $ret ;
    }

    function osc_category_name($locale = "") {
        if ($locale == "") $locale = osc_get_user_locale() ;
        return osc_category_field("s_name", $locale) ;
    }

    function osc_category_id($locale = "") {
        if ($locale == "") $locale = osc_get_user_locale() ;
        return osc_category_field("pk_i_id", $locale) ;
    }

    function osc_category_total_items() {
        $category = osc_category() ;
        return CategoryStats::newInstance()->getNumItems($category) ;
    }

    function osc_goto_first_category() {
        View::newInstance()->_reset('categories') ;
    }

?>