<?php

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

            $this->add_css('style.css') ;
            $this->add_css('jquery-ui.css') ;
            $this->add_global_js('tiny_mce.js') ;
            $this->add_global_js('jquery-1.4.2.js') ;
            $this->add_global_js('jquery-ui-1.8.5.js') ;
            $this->add_js('jquery-extends.js') ;
            $this->add_js('global.js') ;

            $this->mSearch = new Search() ;
        }

        //Business Layer...
        function doModel() {
            $mCategories = new Category() ;
            $aCategories = $mCategories->findRootCategories() ;
            $mCategoryStats = new CategoryStats() ;

            ////////////////////////////////
            //GETTING AND FIXING SENT DATA//
            ////////////////////////////////
            $p_sCategory  = Params::getParam('sCategory');
            if(!is_array($p_sCategory)) {
                if($p_sCategory == '') {
                    $p_sCategory = array() ;
                } else {
                    $p_sCategory = array($p_sCategory);
                }
            }

            $p_sCity      = Params::getParam('sCity');
            if(!is_array($p_sCity)) {
                if($p_sCity == '') {
                    $p_sCity = array() ;
                } else {
                    $p_sCity = array($p_sCity);
                }
            }

            $p_sRegion    = Params::getParam('sRegion');
            if(!is_array($p_sRegion)) {
                if($p_sRegion == '') {
                    $p_sRegion = array() ;
                } else {
                    $p_sRegion = array($p_sRegion);
                }
            }

            $p_sCountry   = Params::getParam('sCountry');
            if(!is_array($p_sCountry)) {
                if($p_sCountry == '') {
                    $p_sCountry = array() ;
                } else {
                    $p_sCountry = array($p_sCountry);
                }
            }

            $p_sPattern   = strip_tags(Params::getParam('sPattern'));

            $p_bPic       = Params::getParam('bPic');
            ($p_bPic == '') ? $p_bPic = 0 : $p_bPic = 1 ;

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
            if(!array_key_exists($p_iOrderType, $allowedTypesForSorting)) {
                $p_iOrderType = osc_default_order_type_at_search() ;
            }

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

            //FILTERING CITY
            foreach($p_sCity as $city) {
                $this->mSearch->addCity($city);
            }

            //FILTERING REGION
            foreach($p_sRegion as $region) {
                $this->mSearch->addRegion($region);
            }

            //FILTERING COUNTRY
            foreach($p_sCountry as $country) {
                $this->mSearch->addCountry($country);
            }

            // FILTERING PATTERN
            if($p_sPattern != '') {
                $this->mSearch->addConditions(sprintf("(d.s_title LIKE '%%%s%%' OR d.s_description LIKE '%%%s%%')", $p_sPattern, $p_sPattern));
                $osc_request['sPattern'] = $p_sPattern;
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

            $this->mSearch->addConditions(sprintf("%st_item.e_status = 'ACTIVE' ", DB_TABLE_PREFIX));

            // RETRIEVE ITEMS AND TOTAL
            $iTotalItems = $this->mSearch->count();
            $aItems = $this->mSearch->search();

            if($p_sFeed == '') {
                $iStart    = $p_iPage * $p_iPageSize ;
                $iEnd      = min(($p_iPage+1) * $p_iPageSize, $iTotalItems) ;
                $aOrders   = array(
                                 __('Newly listed')       => array('sOrder' => 'dt_pub_date', 'iOrderType' => 'desc')
                                ,__('Lower price first')  => array('sOrder' => 'f_price', 'iOrderType' => 'asc')
                                ,__('Higher price first') => array('sOrder' => 'f_price', 'iOrderType' => 'desc')
                             );
                $iNumPages = ceil($iTotalItems / $p_iPageSize) ;

                //Categories for select at view "search.php"
                $mCategories = new Category();
                $aCategories = $mCategories->findRootCategories();
                $mCategoryStats = new CategoryStats();
                $aCategories = $mCategories->toTree();
                foreach($aCategories as $k => $v) {
                    $iCategoryNumItems = CategoryStats::newInstance()->getNumItems($v);
                    if($iCategoryNumItems > 0) {
                        $aCategories[$k]['total'] = $iCategoryNumItems;
                    } else {
                        unset($aCategories[$k]);
                    }
                }

                osc_run_hook('search', $this->mSearch) ;

                //preparing variables...
                $this->_exportVariableToView('aCategories', $aCategories) ;
                $this->_exportVariableToView('sCategory', $p_sCategory) ;
                $this->_exportVariableToView('aOrders', $aOrders) ;
                $this->_exportVariableToView('iOrderType', $p_iOrderType) ;
                $this->_exportVariableToView('sOrder', $p_sOrder) ;
                $this->_exportVariableToView('sPattern', $p_sPattern) ;
                $this->_exportVariableToView('iNumPages', $iNumPages) ;
                $this->_exportVariableToView('bPic', $p_bPic) ;
                $this->_exportVariableToView('sCity', $p_sCity) ;
                $this->_exportVariableToView('sPriceMin', $p_sPriceMin) ;
                $this->_exportVariableToView('sPriceMax', $p_sPriceMax) ;
                
                //calling the view...
                $this->doView('search.php') ;

            } else {
                osc_run_hook('feed_' . $p_sFeed, $aItems) ;
            }
        }

        //hopefully generic...
        function doView($file) {
            $this->osc_print_html($file) ;
        }

        function osc_update_search_url($params, $delimiter = '&amp;') {
            $merged = array_merge($_REQUEST, $params);
            return osc_base_url() . '/search.php?' . http_build_query($merged, '', $delimiter);
        }

        function alert_form() {
            $this->mSearch->order() ;
            $this->mSearch->limit() ;
            //$search_alert = base64_encode(serialize($this->mSearch)) ;

            $this->doView('alert-form.php') ;
        }
/*
        function osc_is_category_checked($aCategory, $aCategories) {
            if(in_array($aCategory['pk_i_id'], $aCategories)) {
                return(true) ;
            }
            if(in_array($aCategory['s_slug'], $aCategories)) {
                return(true) ;
            }
        }
 */
    }

?>