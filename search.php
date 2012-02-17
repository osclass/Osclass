<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    class CWebSearch extends BaseModel
    {
        var $mSearch ;
        
        function __construct() {
            parent::__construct() ;

            $this->mSearch = Search::newInstance();
        }

        //Business Layer...
        function doModel() {
            osc_run_hook('before_search');
            $mCategories = new Category() ;

            ////////////////////////////////
            //GETTING AND FIXING SENT DATA//
            ////////////////////////////////
            $p_sCategory  = Params::getParam('sCategory');
            if(!is_array($p_sCategory)) {
                if($p_sCategory == '') {
                    $p_sCategory = array() ;
                } else {
                    $p_sCategory = explode(",",$p_sCategory);
                }
            }

            $p_sCityArea    = Params::getParam('sCityArea');
            if(!is_array($p_sCityArea)) {
                if($p_sCityArea == '') {
                    $p_sCityArea = array() ;
                } else {
                    $p_sCityArea = explode(",", $p_sCityArea);
                }
            }

            $p_sCity      = Params::getParam('sCity');
            if(!is_array($p_sCity)) {
                if($p_sCity == '') {
                    $p_sCity = array() ;
                } else {
                    $p_sCity = explode(",", $p_sCity);
                }
            }

            $p_sRegion    = Params::getParam('sRegion');
            if(!is_array($p_sRegion)) {
                if($p_sRegion == '') {
                    $p_sRegion = array() ;
                } else {
                    $p_sRegion = explode(",", $p_sRegion);
                }
            }

            $p_sCountry   = Params::getParam('sCountry');
            if(!is_array($p_sCountry)) {
                if($p_sCountry == '') {
                    $p_sCountry = array() ;
                } else {
                    $p_sCountry = explode(",", $p_sCountry);
                }
            }

            $p_sPattern   = strip_tags(Params::getParam('sPattern'));
            $p_sUser      = strip_tags(Params::getParam('sUser'));
            
            // ADD TO THE LIST OF LAST SEARCHES
            if(osc_save_latest_searches()) {
                if(trim($p_sPattern)!='') {
                    LatestSearches::newInstance()->insert(array( 's_search' => trim($p_sPattern), 'd_date' => date('Y-m-d H:i:s')));
                }
            }

            $p_bPic       = Params::getParam('bPic');
            ($p_bPic == 1) ? $p_bPic = 1 : $p_bPic = 0 ;

            $p_sPriceMin  = Params::getParam('sPriceMin');
            $p_sPriceMax  = Params::getParam('sPriceMax');

            //WE CAN ONLY USE THE FIELDS RETURNED BY Search::getAllowedColumnsForSorting()
            $p_sOrder     = Params::getParam('sOrder');
            if(!in_array($p_sOrder, Search::getAllowedColumnsForSorting())) {
                $p_sOrder = osc_default_order_field_at_search() ;
            }

            //ONLY 0 ( => 'asc' ), 1 ( => 'desc' ) AS ALLOWED VALUES
            $p_iOrderType = Params::getParam('iOrderType');
            $allowedTypesForSorting = Search::getAllowedTypesForSorting() ;
            $orderType = osc_default_order_type_at_search();
            foreach($allowedTypesForSorting as $k => $v) {
                if($p_iOrderType==$v) {
                    $orderType = $k;
                    break;
                }
            }
            $p_iOrderType = $orderType;

            $p_sFeed      = Params::getParam('sFeed');
            $p_iPage      = intval(Params::getParam('iPage'));

            if($p_sFeed != '') {
                $p_sPageSize = 1000;
            }

            $p_sShowAs    = Params::getParam('sShowAs');
            $aValidShowAsValues = array('list', 'gallery');
            if (!in_array($p_sShowAs, $aValidShowAsValues)) {
                $p_sShowAs = osc_default_show_as_at_search() ;
            }

            // search results: it's blocked with the maxResultsPerPage@search defined in t_preferences
            $p_iPageSize  = intval(Params::getParam('iPagesize')) ;
            if($p_iPageSize > 0) {
                if($p_iPageSize > osc_max_results_per_page_at_search()) $p_iPageSize = osc_max_results_per_page_at_search() ;
            } else {
                $p_iPageSize = osc_default_results_per_page_at_search() ;
            }

            //FILTERING CATEGORY
            $bAllCategoriesChecked = false ;
            if(count($p_sCategory) > 0) {
                foreach($p_sCategory as $category) {
                    $this->mSearch->addCategory($category);
                }
            } else {
                $bAllCategoriesChecked = true ;
            }

            //FILTERING CITY_AREA
            foreach($p_sCityArea as $city_area) {
                $this->mSearch->addCityArea($city_area);
            }
            $p_sCityArea = implode(", ", $p_sCityArea);

            //FILTERING CITY
            foreach($p_sCity as $city) {
                $this->mSearch->addCity($city);
            }
            $p_sCity = implode(", ", $p_sCity);

            //FILTERING REGION
            foreach($p_sRegion as $region) {
                $this->mSearch->addRegion($region);
            }
            $p_sRegion = implode(", ", $p_sRegion);

            //FILTERING COUNTRY
            foreach($p_sCountry as $country) {
                $this->mSearch->addCountry($country);
            }
            $p_sCountry = implode(", ", $p_sCountry);

            // FILTERING PATTERN
            if($p_sPattern != '') {
                $this->mSearch->addTable(sprintf('%st_item_description as d', DB_TABLE_PREFIX));
                $this->mSearch->addConditions(sprintf("d.fk_i_item_id = %st_item.pk_i_id", DB_TABLE_PREFIX));
                $this->mSearch->addConditions(sprintf("MATCH(d.s_title, d.s_description) AGAINST('%s' IN BOOLEAN MODE)", $p_sPattern));
                $osc_request['sPattern'] = $p_sPattern;
            }

            // FILTERING USER
            if($p_sUser != '') {
                $this->mSearch->fromUser(explode(",", $p_sUser));
            }

            // FILTERING IF WE ONLY WANT ITEMS WITH PICS
            if($p_bPic) {
                $this->mSearch->withPicture(true) ;
            }

            //FILTERING BY RANGE PRICE
            $this->mSearch->priceRange($p_sPriceMin, $p_sPriceMax);

            //ORDERING THE SEARCH RESULTS
            $this->mSearch->order($p_sOrder, $allowedTypesForSorting[$p_iOrderType]) ;

            //SET PAGE
            $this->mSearch->page($p_iPage, $p_iPageSize);

            osc_run_hook('search_conditions', Params::getParamsAsArray());

            if(!Params::existParam('sFeed')) {
                // RETRIEVE ITEMS AND TOTAL
                $aItems = $this->mSearch->doSearch();
                $iTotalItems = $this->mSearch->count();
                
                $iStart    = $p_iPage * $p_iPageSize ;
                $iEnd      = min(($p_iPage+1) * $p_iPageSize, $iTotalItems) ;
                $iNumPages = ceil($iTotalItems / $p_iPageSize) ;

                osc_run_hook('search', $this->mSearch) ;

                //preparing variables...
                //$this->_exportVariableToView('non_empty_categories', $aCategories) ;
                $this->_exportVariableToView('search_start', $iStart) ;
                $this->_exportVariableToView('search_end', $iEnd) ;
                $this->_exportVariableToView('search_category', $p_sCategory) ;
                $this->_exportVariableToView('search_order_type', $p_iOrderType) ;
                $this->_exportVariableToView('search_order', $p_sOrder) ;
                $this->_exportVariableToView('search_pattern', $p_sPattern) ;
                $this->_exportVariableToView('search_from_user', $p_sUser) ;
                $this->_exportVariableToView('search_total_pages', $iNumPages) ;
                $this->_exportVariableToView('search_page', $p_iPage) ;
                $this->_exportVariableToView('search_has_pic', $p_bPic) ;
                $this->_exportVariableToView('search_region', $p_sRegion) ;
                $this->_exportVariableToView('search_city', $p_sCity) ;
                $this->_exportVariableToView('search_price_min', $p_sPriceMin) ;
                $this->_exportVariableToView('search_price_max', $p_sPriceMax) ;
                $this->_exportVariableToView('search_total_items', $iTotalItems) ;
                $this->_exportVariableToView('items', $aItems) ;
                $this->_exportVariableToView('search_show_as', $p_sShowAs) ;
                $this->_exportVariableToView('search', $this->mSearch) ;
                $this->_exportVariableToView('search_alert', base64_encode(serialize($this->mSearch))) ;
                
                //calling the view...
                $this->doView('search.php') ;

            } else {
                $this->mSearch->page(0, osc_num_rss_items());
                // RETRIEVE ITEMS AND TOTAL
                $iTotalItems = $this->mSearch->count();
                $aItems = $this->mSearch->doSearch();
                
                $this->_exportVariableToView('items', $aItems) ;
                if($p_sFeed=='' || $p_sFeed=='rss') {
                    // FEED REQUESTED!
                    header('Content-type: text/xml; charset=utf-8');
                    
                    $feed = new RSSFeed;
                    $feed->setTitle(__('Latest items added') . ' - ' . osc_page_title());
                    $feed->setLink(osc_base_url());
                    $feed->setDescription(__('Latest items added in') . ' ' . osc_page_title());

                    if(osc_count_items()>0) {
                        while(osc_has_items()) {
                            
                            if(osc_count_item_resources() > 0){
                                osc_has_item_resources();
                                $feed->addItem(array(
                                    'title' => osc_item_title(),
                                    'link' => htmlentities( osc_item_url() ),
                                    'description' => osc_item_description(),
                                    'dt_pub_date' => osc_item_pub_date(),
                                    'image'     => array(  'url'    => htmlentities(osc_resource_thumbnail_url()),
                                                           'title'  => osc_item_title(),
                                                           'link'   => htmlentities( osc_item_url() ) )
                                ));
                            } else {
                                $feed->addItem(array(
                                    'title' => osc_item_title(),
                                    'link' => htmlentities( osc_item_url() ),
                                    'description' => osc_item_description(),
                                    'dt_pub_date' => osc_item_pub_date()
                                ));
                            }
                        }
                    }

                    osc_run_hook('feed', $feed);
                    $feed->dumpXML();
                } else {
                    osc_run_hook('feed_' . $p_sFeed, $aItems) ;
                }
            }
        }

        //hopefully generic...
        function doView($file) {
            osc_run_hook("before_html");
            osc_current_web_theme_path($file) ;
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_html");
        }

    }

?>