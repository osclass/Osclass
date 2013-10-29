<?php
    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
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

    /**
    * Helper Search
    * @package Osclass
    * @subpackage Helpers
    * @author Osclass
    */

    /**
     * Gets search object
     *
     * @return mixed
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

    /**
     * Gets available search orders
     *
     * @return array
     */
    function osc_list_orders() {
        return  array(
                     __('Newly listed')        => array('sOrder' => 'dt_pub_date', 'iOrderType' => 'desc')
                    ,__('Lower price first')   => array('sOrder' => 'i_price', 'iOrderType' => 'asc')
                    ,__('Higher price first')  => array('sOrder' => 'i_price', 'iOrderType' => 'desc')
                );
    }

    /**
     * Gets current search page
     *
     * @return int
     */
    function osc_search_page() {
        return View::newInstance()->_get('search_page');
    }

    /**
     * Gets total pages of search
     *
     * @return int
     */
    function osc_search_total_pages() {
        return View::newInstance()->_get('search_total_pages');
    }

    /**
     * Gets if "has pic" option is enabled or not in the search
     *
     * @return boolean
     */
    function osc_search_has_pic() {
        return View::newInstance()->_get('search_has_pic');
    }

    /**
     * Gets if "only premium" option is enabled or not in the search
     *
     * @return boolean
     */
    function osc_search_only_premium() {
        return View::newInstance()->_get('search_only_premium');
    }

    /**
     * Gets current search order
     *
     * @return string
     */
    function osc_search_order() {
        return View::newInstance()->_get('search_order');
    }

    /**
     * Gets current search order type
     *
     * @return string
     */
    function osc_search_order_type() {
        return View::newInstance()->_get('search_order_type');
    }

    /**
     * Gets current search pattern
     *
     * @return string
     */
    function osc_search_pattern() {
        if(View::newInstance()->_exists('search_pattern')) {
            return View::newInstance()->_get('search_pattern');
        } else {
            return '';
        }
    }

    /**
     * Gets current search region
     *
     * @return string
     */
    function osc_search_region() {
        return View::newInstance()->_get('search_region');
    }

    /**
     * Gets current search city
     *
     * @return string
     */
    function osc_search_city() {
        return View::newInstance()->_get('search_city');
    }

    /**
     * Gets current search users
     *
     * @return string
     */
    function osc_search_user() {
        if(is_array(View::newInstance()->_get('search_from_user') ) ){
            return View::newInstance()->_get('search_from_user');
        }
        return array();
    }

    /**
     * Gets current search max price
     *
     * @return float
     */
    function osc_search_price_max() {
        return View::newInstance()->_get('search_price_max');
    }

    /**
     * Gets current search min price
     *
     * @return float
     */
    function osc_search_price_min() {
        return View::newInstance()->_get('search_price_min');
    }

    /**
     * Gets current search total items
     *
     * @return int
     */
    function osc_search_total_items() {
        return View::newInstance()->_get('search_total_items');
    }

    /**
     * Gets current search "show as" variable (show the items as a list or as a gallery)
     *
     * @return string
     */
    function osc_search_show_as() {
        return View::newInstance()->_get('search_show_as');
    }

    /**
     * Gets current search start item record
     *
     * @return int
     */
    function osc_search_start() {
        return View::newInstance()->_get('search_start');
    }

    /**
     * Gets current search end item record
     *
     * @return int
     */
    function osc_search_end() {
        return View::newInstance()->_get('search_end');
    }

    /**
     * Gets current search category
     *
     * @return array
     */
    function osc_search_category() {
        if (View::newInstance()->_exists('search_subcategories')) {
            $category = View::newInstance()->_current('search_subcategories');
        } elseif (View::newInstance()->_exists('search_categories')) {
            $category = View::newInstance()->_current('search_categories');
        } else {
            $category = View::newInstance()->_get('search_category');
        }
        if(!is_array($category)) { $category = array(); }
        return($category);
    }

    /**
     * Gets current search category id
     *
     * @return int
     */
    function osc_search_category_id() {
        $categories = osc_search_category();
        $category   = array();
        $where      = array();

        foreach($categories as $cat) {
            if( is_numeric($cat) ) {
                $where[] = "a.pk_i_id = " . $cat;
            } else {
                $slug_cat = explode( "/", trim($cat, "/") );
                $where[]  = "b.s_slug = '" . addslashes( $slug_cat[count($slug_cat)-1] ) . "'";
            }
        }

        if( empty($where) ) {
            return null;
        }

        // TODO: not the best way to do it
        $categories = Category::newInstance()->listWhere( implode(" OR ", $where) );
        foreach($categories as $cat) {
            $category[] = $cat['pk_i_id'];
        }

        return $category;
    }

    /**
     * Update the search url with new options
     *
     * @return string
     */
    function osc_update_search_url($params, $delimiter = '&amp;') {
        $request = Params::getParamsAsArray('get');
        unset($request['osclass']);
        // sCategory
        if(isset($request['sCategory[0]'])) {
            unset($request['sCategory']);
        }
        unset($request['sCategory[]']);
        // sUser
        if(isset($request['sUser[0]'])) {
            unset($request['sUser']);
        }
        unset($request['sUser[]']);
        $merged = array_merge($request, $params);
        return osc_search_url($merged);
        //return osc_base_url(true) ."?" . http_build_query($merged, '', $delimiter);
    }

    /**
     * Load the form for the alert subscription
     *
     * @return void
     */
    function osc_alert_form() {
        osc_current_web_theme_path('alert-form.php');
    }

    /**
     * Gets alert of current search
     *
     * @return string
     */
    function osc_search_alert() {
        return View::newInstance()->_get('search_alert');
    }

    /**
     * Gets for a default search (all categories, noother option)
     *
     * @return string
     */
    function osc_search_show_all_url( ) {
        if(osc_rewrite_enabled ()) {
            return osc_base_url().osc_get_preference('rewrite_search_url');
        } else {
            return osc_base_url(true) . '?page=search';
        }
    }

    /**
     * Gets search url given params
     *
     * @params array $params
     * @return string
     */
    function osc_search_url($params = null) {
        if(is_array($params)) {
            osc_prune_array($params);
        }
        $countP = count($params);
        if($countP==1) {
            if(isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS'])=='on' || $_SERVER['HTTPS']=='1')){
                $url = "https://";
            } else {
                $url = "http://";
            }
            if(osc_subdomain_type()=='category' && isset($params['sCategory'])) {
                if(is_array($params['sCategory'])) {
                    $params['sCategory'] = implode(",", $params['sCategory']);
                }
                if($params['sCategory']!='' && strpos($params['sCategory'], ",")===false) {
                    if(is_numeric($params['sCategory'])) {
                        $category = Category::newInstance()->findByPrimaryKey($params['sCategory']);
                    } else {
                        $category = Category::newInstance()->findBySlug($params['sCategory']);
                    }
                    if(isset($category['s_slug'])) {
                        return $url.$category['s_slug'].".".osc_subdomain_host().REL_WEB_URL;
                    }
                }
            } else if(osc_subdomain_type()=='country' && isset($params['sCountry'])) {
                if(is_array($params['sCountry'])) {
                    $params['sCountry'] = implode(",", $params['sCountry']);
                }
                if($params['sCountry']!='' && strpos($params['sCountry'], ",")===false) {
                    if(is_numeric($params['sCountry'])) {
                        $country = Country::newInstance()->findByPrimaryKey($params['sCountry']);
                    } else {
                        $country = Country::newInstance()->findByCode($params['sCountry']);
                    }
                    if(isset($country['s_slug'])) {
                        return $url.$country['s_slug'].".".osc_subdomain_host().REL_WEB_URL;
                    }
                }
            } else if(osc_subdomain_type()=='region' && isset($params['sRegion'])) {
                if(is_array($params['sRegion'])) {
                    $params['sRegion'] = implode(",", $params['sRegion']);
                }
                if($params['sRegion']!='' && strpos($params['sRegion'], ",")===false) {
                    if(is_numeric($params['sRegion'])) {
                        $region = Region::newInstance()->findByPrimaryKey($params['sRegion']);
                    } else {
                        $region = Region::newInstance()->findByName($params['sRegion']);
                    }
                    if(isset($region['s_slug'])) {
                        return $url.$region['s_slug'].".".osc_subdomain_host().REL_WEB_URL;
                    }

                }
            } else if(osc_subdomain_type()=='city' && isset($params['sCity'])) {
                if(is_array($params['sCity'])) {
                    $params['sCity'] = implode(",", $params['sCity']);
                }
                if($params['sCity']!='' && strpos($params['sCity'], ",")===false) {
                    if(is_numeric($params['sCity'])) {
                        $city = City::newInstance()->findByPrimaryKey($params['sCity']);
                    } else {
                        $city = City::newInstance()->findByName($params['sCity']);
                    }
                    if(isset($city['s_slug'])) {
                        return $url.$city['s_slug'].".".osc_subdomain_host().REL_WEB_URL;
                    }

                }
            }
        }
        if(osc_rewrite_enabled()) {
            $url = osc_base_url().osc_get_preference('rewrite_search_url');

            if($countP==1 && isset($params['sRegion'])) {
                $url = osc_base_url();
                if( osc_get_preference('seo_url_search_prefix') != '' ) {
                    $url .= osc_get_preference('seo_url_search_prefix') . '/';
                }
                if(osc_list_region_id()==$params['sRegion']) {
                    $url .= osc_sanitizeString(osc_list_region_name()) . '-r' . osc_list_region_id();
                } else {
                    if(is_numeric($params['sRegion'])) {
                        $region = Region::newInstance()->findByPrimaryKey($params['sRegion']);
                    } else {
                        $region = Region::newInstance()->findByName($params['sRegion']);
                    }
                    $url .= osc_sanitizeString($region['s_name']) . '-r' . $region['pk_i_id'];
                }
                return $url;
            } else if($countP==1 && isset($params['sCity'])) {
                $url = osc_base_url();
                if( osc_get_preference('seo_url_search_prefix') != '' ) {
                    $url .= osc_get_preference('seo_url_search_prefix') . '/';
                }
                if(osc_list_region_id()==$params['sCity']) {
                    $url .= osc_sanitizeString(osc_list_city_name()) . '-c' . osc_list_city_id();
                } else {
                    if(is_numeric($params['sCity'])) {
                        $city = City::newInstance()->findByPrimaryKey($params['sCity']);
                    } else {
                        $city = City::newInstance()->findByName($params['sCity']);
                    }
                    $url .= osc_sanitizeString($city['s_name']) . '-c' . $city['pk_i_id'];
                }
                return $url;
            } else if($params!=null) {
                $url .= "/";
                foreach($params as $k => $v) {
                    switch($k) {
                        case 'sCountry':
                            $k = osc_get_preference('rewrite_search_country');
                            break;
                        case 'sRegion':
                            $k = osc_get_preference('rewrite_search_region');
                            break;
                        case 'sCity':
                            $k = osc_get_preference('rewrite_search_city');
                            break;
                        case 'sCityArea':
                            $k = osc_get_preference('rewrite_search_city_area');
                            break;
                        case 'sCategory':
                            $k = osc_get_preference('rewrite_search_category');
                            if(is_array($v)) {
                                $v = implode(",", $v);
                            }
                            break;
                        case 'sUser':
                            $k = osc_get_preference('rewrite_search_user');
                            if(is_array($v)) {
                                $v = implode(",", $v);
                            }
                            break;
                        case 'sPattern':
                            $k = osc_get_preference('rewrite_search_pattern');
                            break;
                        case 'meta':
                            // meta(@id),value/meta(@id),value2/...
                            foreach ($v as $key => $value) {
                                if(is_array($value)) {
                                    foreach ($value as $_key => $_value) {
                                        if($value!='') {
                                            $url .= 'meta'.$key.'-'.$_key.','.$_value.'/';
                                        }
                                    }
                                } else {
                                    if($value!='') {
                                        $url .= 'meta'.$key.','.$value.'/';
                                    }
                                }
                            }
                            break;
                        default:
                            break;
                    }

                    if($k!='page') {
                        if(!is_array($v) ) {
                            $url .= $k.",".$v."/";
                        }
                    }
                }
            }
        } else {
            $url = osc_base_url(true) . '?page=search';
            if($params!=null) {
                foreach($params as $k => $v) {
                    if($k!='page') {
                        if($k=='meta') {
                            if( is_array($v) ) {
                                foreach($v as $_k => $aux) {
                                    if(is_array($aux)) {
                                        foreach( array_keys($aux) as $aux_k ) {
                                            $url .= "&" . $k . "[$_k][$aux_k]=" . $aux[$aux_k];
                                        }
                                    } else {
                                        $url .= "&" . $_k . "[]=" . $aux;
                                    }
                                }
                            }
                        } else {
                            if( is_array($v) ) {
                                $v = implode(",", $v);
                            }
                            $url .= "&" . $k . "=" . $v;
                        }
                    }
                }
            }
        }
        return $url;
    }

    /**
     * Gets list of countries with items
     *
     * @return array
     */
    function osc_list_country() {
        if (View::newInstance()->_exists('list_countries')) {
            return View::newInstance()->_current('list_countries');
        } else {
            return null;
        }
    }

    /**
     * Gets list of regions with items
     *
     * @return array
     */
    function osc_list_region() {
        if (View::newInstance()->_exists('list_regions')) {
            return View::newInstance()->_current('list_regions');
        } else {
            return null;
        }
    }

    /**
     * Gets list of cities with items
     *
     * @return array
     */
    function osc_list_city() {
        if (View::newInstance()->_exists('list_cities')) {
            return View::newInstance()->_current('list_cities');
        } else {
            return null;
        }
    }

    /**
     * Gets the next country in the list_countries list
     *
     * @return array
     */
    function osc_has_list_countries() {
        if ( !View::newInstance()->_exists('list_countries') ) {
            View::newInstance()->_exportVariableToView('list_countries', CountryStats::newInstance()->listCountries() );
        }
        $result = View::newInstance()->_next('list_countries');
        if (!$result) {
            View::newInstance()->_reset('list_countries');
        }
        return $result;
    }

    /**
     * Gets the next region in the list_regions list
     *
     * @param string $country
     * @return array
     */
    function osc_has_list_regions($country = '%%%%') {
        if ( !View::newInstance()->_exists('list_regions') ) {
            View::newInstance()->_exportVariableToView('list_regions', RegionStats::newInstance()->listRegions($country) );
        }
        $result = View::newInstance()->_next('list_regions');
        if (!$result) {
            View::newInstance()->_reset('list_regions');
        }
        return $result;
    }

    /**
     * Gets the next city in the list_cities list
     *
     * @param string $region
     * @return array
     */
    function osc_has_list_cities($region = '%%%%') {
        if ( !View::newInstance()->_exists('list_cities') ) {
            View::newInstance()->_exportVariableToView('list_cities', CityStats::newInstance()->listCities($region) );
        }
        $result = View::newInstance()->_next('list_cities');
        if (!$result) {
            View::newInstance()->_reset('list_cities');
        }
        return $result;
    }

    /**
     * Gets the total number of countries in list_countries
     *
     * @return int
     */
    function osc_count_list_countries() {
        if ( !View::newInstance()->_exists('list_countries') ) {
            View::newInstance()->_exportVariableToView('list_countries', CountryStats::newInstance()->listCountries() );
        }
        return View::newInstance()->_count('list_countries');
    }

    /**
     * Gets the total number of regions in list_regions
     *
     * @param string $country
     * @return int
     */
    function osc_count_list_regions($country = '%%%%') {
        if ( !View::newInstance()->_exists('list_regions') ) {
            View::newInstance()->_exportVariableToView('list_regions', RegionStats::newInstance()->listRegions($country) );
        }
        return View::newInstance()->_count('list_regions');
    }

    /**
     * Gets the total number of cities in list_cities
     *
     * @param string $region
     * @return int
     */
    function osc_count_list_cities($region = '%%%%') {
        if ( !View::newInstance()->_exists('list_cities') ) {
            View::newInstance()->_exportVariableToView('list_cities', CityStats::newInstance()->listCities($region) );
        }
        return View::newInstance()->_count('list_cities');
    }

    // country attributes
    /**
     * Gets the the name of current "list country"
     *
     * @return string
     */
    function osc_list_country_name() {
        return osc_field(osc_list_country(), 'country_name', '');
    }

    /**
     * Gets the number of items of current "list country"
     *
     * @return int
     */
    function osc_list_country_code() {
        return osc_field(osc_list_country(), 'country_code', '');
    }

    /**
     * Gets the number of items of current "list country"
     *
     * @return int
     */
    function osc_list_country_items() {
        return osc_field(osc_list_country(), 'items', '');
    }

    /**
     * Gets the url of current "list country""
     *
     * @return string
     */
    function osc_list_country_url() {
        return osc_search_url(array('sCountry' => osc_list_country_code()));
    }

    // region attributes
    /**
     * Gets the name of current "list region"
     *
     * @return string
     */
    function osc_list_region_name() {
        return osc_field(osc_list_region(), 'region_name', '');
    }

    /**
     * Gets the id of current "list region"
     *
     * @return string
     */
    function osc_list_region_id() {
        return osc_field(osc_list_region(), 'region_id', '');
    }

    /**
     * Gets the number of items of current "list region"
     *
     * @return int
     */
    function osc_list_region_items() {
        return osc_field(osc_list_region(), 'items', '');
    }

    /**
     * Gets the url of current "list region"
     *
     * @return string
     */
    function osc_list_region_url() {
        return osc_search_url( array( 'sRegion' => osc_list_region_id() ) );
    }

    // city attributes
    /**
     * Gets the the name of current "list city""
     *
     * @return string
     */
    function osc_list_city_name() {
        return osc_field(osc_list_city(), 'city_name', '');
    }

    /**
     * Gets the id of current "list city"
     *
     * @return string
     */
    function osc_list_city_id() {
        return osc_field(osc_list_city(), 'city_id', '');
    }

    /**
     * Gets the number of items of current "list city"
     *
     * @return int
     */
    function osc_list_city_items() {
        return osc_field(osc_list_city(), 'items', '');
    }

    /**
     * Gets the url of current "list city""
     *
     * @return string
     */
    function osc_list_city_url() {
        return osc_search_url(array('sCity' => osc_list_city_id()));
    }

    /**********************
     ** LATEST SEARCHES **
     **********************/
    /**
     * Gets the latest searches done in the website
     *
     * @param int $limit
     * @return array
     */
    function osc_get_latest_searches($limit = 20) {
        if ( !View::newInstance()->_exists('latest_searches') ) {
            View::newInstance()->_exportVariableToView('latest_searches', LatestSearches::newInstance()->getSearches($limit) );
        }
        return View::newInstance()->_count('latest_searches');
    }

    /**
     * Gets the total number of latest searches done in the website
     *
     * @return int
     */
    function osc_count_latest_searches() {
        if ( !View::newInstance()->_exists('latest_searches') ) {
            View::newInstance()->_exportVariableToView('latest_searches', LatestSearches::newInstance()->getSearches() );
        }
        return View::newInstance()->_count('latest_searches');
    }

    /**
     * Gets the next latest search
     *
     * @return array
     */
    function osc_has_latest_searches() {
        if ( !View::newInstance()->_exists('latest_searches') ) {
            View::newInstance()->_exportVariableToView('latest_searches', LatestSearches::newInstance()->getSearches() );
        }
        return View::newInstance()->_next('latest_searches');
    }

    /**
     * Gets the current latest search
     *
     * @return array
     */
    function osc_latest_search() {
        if (View::newInstance()->_exists('latest_searches')) {
            return View::newInstance()->_current('latest_searches');
        }
        return null;
    }

    /**
     * Gets the current latest search pattern
     *
     * @return string
     */
    function osc_latest_search_text() {
        return osc_field(osc_latest_search(), 's_search', '');
    }

    /**
     * Gets the current latest search date
     *
     * @return string
     */
    function osc_latest_search_date() {
        return osc_field(osc_latest_search(), 'd_date', '');
    }

    /**
     * Gets the current latest search total
     *
     * @return string
     */
    function osc_latest_search_total() {
        return osc_field(osc_latest_search(), 'i_total', '');
    }

    function osc_get_canonical() {
        if( View::newInstance()->_exists('canonical') ) {
            return View::newInstance()->_get('canonical');
        }
        return '';
    }


    function osc_get_raw_search($conditions) {
        $keys = array("aCategories", "countries", "regions", "cities", "city_areas");
        $mCategory = Category::newInstance();
        foreach($keys as $key) {
            if(isset($conditions[$key]) && is_array($conditions[$key]) && !empty($conditions[$key])) {
                foreach($conditions[$key] as $k => $v) {
                    if(preg_match('|([0-9]+)|', $v, $match)) {
                        if($key=="aCategories") {
                            $conditions[$key][$k] = $mCategory->findNameByPrimaryKey($match[1]);
                        } else {
                            $conditions[$key][$k] = $match[1];
                        }
                    }
                }
            } else {
                unset($conditions[$key]);
            }
        }

        if(!isset($conditions['price_min']) || $conditions['price_min']==0) {
            unset($conditions['price_min']);
        }

        if(!isset($conditions['price_max']) || $conditions['price_max']==0) {
            unset($conditions['price_max']);
        }

        if(!isset($conditions['sPattern']) || $conditions['sPattern']=='') {
            unset($conditions['sPattern']);
        }

        unset($conditions['withPattern']);
        unset($conditions['tables']);
        unset($conditions['tables_join']);
        unset($conditions['no_catched_tables']);
        unset($conditions['no_catched_conditions']);
        unset($conditions['user_ids']);
        unset($conditions['order_column']);
        unset($conditions['order_direction']);
        unset($conditions['limit_init']);
        unset($conditions['results_per_page']);
        return $conditions;
    }

?>
