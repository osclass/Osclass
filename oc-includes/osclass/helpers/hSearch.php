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

    function osc_search() {
        if(View::newInstance()->_exists('search')) {
            return View::newInstance()->_get('search');
        } else {
            $search = new Search();
            View::newInstance()->_exportVariableToView('search', $search);
            return $search;
        }
    }

    function osc_list_orders() {
        return  array(
                     __('Newly listed')       => array('sOrder' => 'dt_pub_date', 'iOrderType' => 'desc')
                    ,__('Lower price first')  => array('sOrder' => 'f_price', 'iOrderType' => 'asc')
                    ,__('Higher price first') => array('sOrder' => 'f_price', 'iOrderType' => 'desc')
                );
    }
    
    function osc_search_page() {
        return View::newInstance()->_get('search_page');
    }
    
    function osc_search_total_pages() {
        return View::newInstance()->_get('search_total_pages');
    }
    
    function osc_search_has_pic() {
        return View::newInstance()->_get('search_has_pic');
    }
    
    function osc_search_order() {
        return View::newInstance()->_get('search_order');
    }
    
    function osc_search_order_type() {
        return View::newInstance()->_get('search_order_type');
    }
    
    function osc_search_pattern() {
        if(View::newInstance()->_exists('search_pattern')) {
            return View::newInstance()->_get('search_pattern');
        } else {
            return '';
        }
    }
    
    function osc_search_city() {
        return View::newInstance()->_get('search_city');
    }
    
    function osc_search_price_max() {
        return View::newInstance()->_get('search_price_max');
    }
    
    function osc_search_price_min() {
        return View::newInstance()->_get('search_price_min');
    }
    
    function osc_search_total_items() {
        return View::newInstance()->_get('search_total_items');
    }
    
    function osc_search_show_as() {
        return View::newInstance()->_get('search_show_as');
    }
    
    function osc_search_category() {
        if (View::newInstance()->_exists('search_subcategories')) {
            $category = View::newInstance()->_current('search_subcategories') ;
        } elseif (View::newInstance()->_exists('search_categories')) {
            $category = View::newInstance()->_current('search_categories') ;
        } else {
            $category = View::newInstance()->_get('search_category') ;
        }
        return($category) ;
    }
    
    function osc_search_category_id() {
        $categories = osc_search_category();
        $category = array();
        $where = array();
        foreach($categories as $cat) {
            if(is_numeric($cat)) {
                $where[] = "a.pk_i_id = " . $cat;
            } else {
                $where[] = "b.s_slug = '" . trim($cat, "/") . "'";
            }
        }
        $categories = Category::newInstance()->listWhere(implode(" OR ", $where));
        foreach($categories as $cat) {
            $category[] = $cat['pk_i_id'];
        }
        return $category;    
    }
    
    function osc_update_search_url($params, $delimiter = '&amp;') {
        $merged = array_merge($_REQUEST, $params);
        return osc_base_url(true) ."?" . http_build_query($merged, '', $delimiter);
    }

    function osc_alert_form() {
        $search = osc_search();
        $search->order() ;
        $search->limit() ;
        View::newInstance()->_exportVariableToView('search_alert', base64_encode(serialize($search))) ;
        osc_current_web_theme_path('alert-form.php') ;
    }
    
    function osc_search_alert() {
        return View::newInstance()->_get('search_alert');
    }
    
        function osc_search_url($params = null) {
        $url = osc_base_url(true) . '?page=search';
        if($params!=null) {
            foreach($params as $k => $v) {
                $url .= "&" . $k . "=" . $v;
            }
        }
        return $url;
    }
    
    function osc_list_country() {
        if (View::newInstance()->_exists('list_countries')) {
            return View::newInstance()->_current('list_countries') ;
        } else {
            return null;
        }
    }

    function osc_list_region() {
        if (View::newInstance()->_exists('list_regions')) {
            return View::newInstance()->_current('list_regions') ;
        } else {
            return null;
        }
    }

    function osc_list_city() {
        if (View::newInstance()->_exists('list_cities')) {
            return View::newInstance()->_current('list_cities') ;
        } else {
            return null;
        }
    }
    
    function osc_has_list_countries() {
        if ( !View::newInstance()->_exists('list_countries') ) {
            View::newInstance()->_exportVariableToView('list_countries', Search::newInstance()->listCountries() ) ;
        }
        return View::newInstance()->_next('list_countries') ;
    }

    function osc_has_list_regions($country = '%%%%') {
        if ( !View::newInstance()->_exists('list_regions') ) {
            View::newInstance()->_exportVariableToView('list_regions', Search::newInstance()->listRegions($country) ) ;
        }
        return View::newInstance()->_next('list_regions') ;
    }

    function osc_has_list_cities($region = '%%%%') {
        if ( !View::newInstance()->_exists('list_cities') ) {
            View::newInstance()->_exportVariableToView('list_cities', Search::newInstance()->listCities($region) ) ;
        }
        return View::newInstance()->_next('list_cities') ;
    }

    function osc_count_list_countries() {
        if ( !View::newInstance()->_exists('list_contries') ) {
            View::newInstance()->_exportVariableToView('list_countries', Search::newInstance()->listCountries() ) ;
        }
        return View::newInstance()->_count('list_countries') ;
    }

    function osc_count_list_regions($country = '%%%%') {
        if ( !View::newInstance()->_exists('list_regions') ) {
            View::newInstance()->_exportVariableToView('list_regions', Search::newInstance()->listRegions($country) ) ;
        }
        return View::newInstance()->_count('list_regions') ;
    }

    function osc_count_list_cities($region = '%%%%') {
        if ( !View::newInstance()->_exists('list_cities') ) {
            View::newInstance()->_exportVariableToView('list_cities', Search::newInstance()->listCities($region) ) ;
        }
        return View::newInstance()->_count('list_cities') ;
    }
    
    function osc_list_region_name() {
        return osc_field(osc_list_region(), 'region_name', '') ;
    }
    
    function osc_list_region_items() {
        return osc_field(osc_list_region(), 'items', '') ;
    }

    

?>
