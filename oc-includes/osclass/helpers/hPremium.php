<?php

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

     /**
     * Helper Premiums - returns object from the static class (View)
     * @package Osclass
     * @subpackage premiums
     * @author Osclass
     */

    ////////////////////////////////////////////////////////////////
    // FUNCTIONS THAT RETURNS OBJECT FROM THE STATIC CLASS (VIEW) //
    ////////////////////////////////////////////////////////////////


    /**
    * Gets new premiums ads
    *
    * @return array $premiums
    */
    function osc_get_premiums($max = 2) {
        if (View::newInstance()->_exists('search')) {
            $mSearch = View::newInstance()->_get('search');
        } else {
            $mSearch = Search::newInstance();
            View::newInstance()->_exportVariableToView('search', $mSearch);
        }

        // juanramon: it should be fixed, little hack to get alerts work in search layout
        $mSearch->reconnect();
        $premiums = $mSearch->getPremiums($max);
        View::newInstance()->_exportVariableToView('premiums', $premiums);
        return $premiums;
    }


    /**
    * Gets current premium array from view
    *
    * @return array $premium, or null if not exist
    */
    function osc_premium() {
        if (View::newInstance()->_exists('premiums')) {
            return View::newInstance()->_current('premiums');
        } else {
            return null;
        }
    }

    /**
    * Gets a specific field from current premium
    *
    * @param type $field
    * @param type $locale
    * @return field_type
    */
    function osc_premium_field($field, $locale = "") {
        return osc_field(osc_premium(), $field, $locale);
    }

    /////////////////////////////////////////////////
    // END FUNCTIONS THAT RETURNS OBJECT FROM VIEW //
    /////////////////////////////////////////////////


    ///////////////////////
    // HELPERS FOR PREMIUMS //
    ///////////////////////


    /**
    * Gets id from current premium
    *
    * @return int
    */
    function osc_premium_id() {
        return (int) osc_premium_field("pk_i_id");
    }

    /**
    * Gets user id from current premium
    *
    * @return int
    */
    function osc_premium_user_id() {
        return (int) osc_premium_field("fk_i_user_id");
    }

    /**
     * Gets description from current premium, if $locale is unspecified $locale is current user locale
     *
     * @param string $locale
     * @return string $desc
     */
    function osc_premium_description($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale();
        $desc = osc_premium_field("s_description", $locale);
        if($desc=='') {
            $desc = osc_premium_field("s_description", osc_language());
            if($desc=='') {
                $aLocales = osc_get_locales();
                foreach($aLocales as $locale) {
                    $desc = osc_premium_field("s_description", $locale);
                    if($desc!='') {
                        break;
                    }
                }
            }
        }
        return (string) $desc;
    }

    /**
     * Gets title from current premium, if $locale is unspecified $locale is current user locale
     *
     * @param string $locale
     * @return string
     */
    function osc_premium_title($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale();
        $title = osc_premium_field("s_title", $locale);
        if($title=='') {
            $title = osc_premium_field("s_title", osc_language());
            if($title=='') {
                $aLocales = osc_get_locales();
                foreach($aLocales as $locale) {
                    $title = osc_premium_field("s_title", $locale);
                    if($title!='') {
                        break;
                    }
                }
            }
        }
        return (string) $title;
    }

    /**
     * Gets category from current premium
     *
     * @param string $locale
     * @return string
     */
    function osc_premium_category($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale();
        if ( !View::newInstance()->_exists('premium_category') ) {
            View::newInstance()->_exportVariableToView('premium_category', Category::newInstance()->findByPrimaryKey( osc_premium_category_id(), $locale ) );
        }
        $category = View::newInstance()->_get('premium_category');
        return (string) osc_field($category, "s_name", $locale);
    }

    /**
     * Gets category description from current premium, if $locale is unspecified $locale is current user locale
     *
     * @param type $locale
     * @return string
     */
    function osc_premium_category_description($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale();
        if ( !View::newInstance()->_exists('premium_category') ) {
            View::newInstance()->_exportVariableToView('premium_category', Category::newInstance()->findByPrimaryKey( osc_premium_category_id() ) );
        }
        $category = View::newInstance()->_get('premium_category');
        return osc_field($category, "s_description", $locale);
    }

    /**
     * Gets category id of current premium
     *
     * @return int
     */
    function osc_premium_category_id() {
        return (int) osc_premium_field("fk_i_category_id");
    }

    /**
     * Gets publication date of current premium
     *
     * @return string
     */
    function osc_premium_pub_date() {
        return (string) osc_premium_field("dt_pub_date");
    }

    /**
     * Gets modification date of current premium
     *
     * @return string
     */
    function osc_premium_mod_date() {
        return (string) osc_premium_field("dt_mod_date");
    }

    /**
     * Gets price of current premium
     *
     * @return float
     */
    function osc_premium_price() {
        if(osc_premium_field("i_price")=='') return null;
        else return (float) osc_premium_field("i_price");

    }

    /**
     * Gets formatted price of current premium
     *
     * @return string
     */
    function osc_premium_formated_price() {
        return (string) osc_format_price( osc_premium_price(), osc_premium_currency_symbol() );
    }

    /**
     * Gets currency symbol of an item
     *
     * @since 3.0
     * @return string
     */
    function osc_premium_currency_symbol() {
        $aCurrency = Currency::newInstance()->findByPrimaryKey(osc_premium_currency());
        return $aCurrency['s_description'];
    }

    /**
     * Gets currency of current premium
     *
     * @return string
     */
    function osc_premium_currency() {
        return (string) osc_premium_field("fk_c_currency_code");
    }

    /**
     * Gets contact name of current premium
     *
     * @return string
     */
    function osc_premium_contact_name() {
        return (string) osc_premium_field("s_contact_name");
    }

    /**
     * Gets contact email of current premium
     *
     * @return string
     */
    function osc_premium_contact_email() {
        return (string) osc_premium_field("s_contact_email");
    }

    /**
     * Gets country name of current premium
     *
     * @return string
     */
    function osc_premium_country() {
        return (string) osc_premium_field("s_country");
    }

    /**
     * Gets country code of current premium
     * Country code are two letters like US, ES, ...
     *
     * @return string
     */
    function osc_premium_country_code() {
        return (string) osc_premium_field("fk_c_country_code");
    }

    /**
     * Gets region of current premium
     *
     * @return string
     */
    function osc_premium_region() {
        return (string) osc_premium_field("s_region");
    }

    /**
     * Gets city of current premium
     *
     * @return string
     */
    function osc_premium_city() {
        return (string) osc_premium_field("s_city");
    }

    /**
     * Gets city area of current premium
     *
     * @return string
     */
    function osc_premium_city_area() {
        return (string) osc_premium_field("s_city_area");
    }

    /**
     * Gets address of current premium
     *
     * @return string
     */
    function osc_premium_address() {
        return (string) osc_premium_field("s_address");
    }

    /**
     * Gets true if can show email user at frontend, else return false
     *
     * @return boolean
     */
    function osc_premium_show_email() {
        return (boolean) osc_premium_field("b_show_email");
    }

    /**
     * Gets zip code of current premium
     *
     * @return string
     */
    function osc_premium_zip() {
        return (string) osc_premium_field("s_zip");
    }

    /**
     * Gets latitude of current premium
     *
     * @return float
     */
    function osc_premium_latitude() {
        return (float) osc_premium_field("d_coord_lat");
    }

    /**
     * Gets longitude of current premium
     *
     * @return float
     */
    function osc_premium_longitude() {
        return (float) osc_premium_field("d_coord_long");
    }

    /**
     * Gets true if current premium is marked premium, else return false
     *
     * @return boolean
     */
    function osc_premium_is_premium() {
        if ( osc_premium_field("b_premium") ) return true;
        else return false;
    }

    /**
     * return number of views of current premium
     *
     * @return int
     */
    function osc_premium_views() {
        $item = osc_premium();
        if(isset($item['i_num_premium_views'])) {
            return (int) osc_premium_field("i_num_premium_views");
        } else {
            return ItemStats::newInstance()->getViews(osc_premium_id());
        }
    }

    /**
     * Gets status of current premium.
     * b_active = true  -> premium is active
     * b_active = false -> premium is inactive
     *
     * @return boolean
     */
    function osc_premium_status() {
        return (boolean) osc_premium_field("b_active");
    }

    /**
     * Gets secret string of current premium
     *
     * @return string
     */
    function osc_premium_secret() {
        return (string) osc_premium_field("s_secret");
    }

    /**
     * Gets if current premium is active
     *
     * @return boolean
     */
    function osc_premium_is_active() {
        return (osc_premium_field("b_active")==1);
    }

    /**
     * Gets if current premium is inactive
     *
     * @return boolean
     */
    function osc_premium_is_inactive() {
        return (osc_premium_field("b_active")==0);
    }

    /**
     * Gets if premium is marked as spam
     *
     * @return boolean
     */
    function osc_premium_is_spam() {
        return (osc_premium_field("b_spam")==1);
    }

    /**
     * Gets total number of comments of current premium
     *
     * @return int
     */
    function osc_premium_total_comments() {
        return premiumComment::newInstance()->total_comments(osc_premium_id());
    }

    /**
     * Gets page of comments in current pagination
     *
     * @return <type>
     */
    function osc_premium_comments_page() {
        $page = Params::getParam('comments-page');
        if($page=='') {
            $page = 0;
        }
        return (int) $page;
    }

    //////////////////////////
    // HELPERS FOR PREMIUMS //
    //////////////////////////



    /////////////
    // DETAILS //
    /////////////

    /**
     * Gets next premium if there is, else return null
     *
     * @return array
     */
    function osc_has_premiums() {
        if ( View::newInstance()->_exists('resources') ) {
            View::newInstance()->_erase('resources');
        }
        if ( View::newInstance()->_exists('premium_category') ) {
            View::newInstance()->_erase('premium_category');
        }
        if ( View::newInstance()->_exists('metafields') ) {
            View::newInstance()->_erase('metafields');
        }
        return View::newInstance()->_next('premiums');
    }

    /**
     * Set the internal pointer of array premiums to its first element, and return it.
     *
     * @return array
     */
    function osc_reset_premiums() {
        return View::newInstance()->_reset('premiums');
    }

    /**
     * Gets number of premiums in current array premiums
     *
     * @return int
     */
    function osc_count_premiums() {
        return (int) View::newInstance()->_count('premiums');
    }

    /**
     * Gets number of resources in array resources of current premium
     *
     * @return int
     */
    function osc_count_premium_resources() {
        if ( !View::newInstance()->_exists('resources') ) {
            View::newInstance()->_exportVariableToView('resources', ItemResource::newInstance()->getAllResourcesFromItem( osc_premium_id() ) );
        }
        return osc_priv_count_item_resources();
    }

    /**
     * Gets next premium resource if there is, else return null
     *
     * @return array
     */
    function osc_has_premium_resources() {
        if ( !View::newInstance()->_exists('resources') ) {
            View::newInstance()->_exportVariableToView('resources', ItemResource::newInstance()->getAllResourcesFromItem( osc_premium_id() ) );
        }
        return View::newInstance()->_next('resources');
    }

    /**
     * Gets current resource of current array resources of current premium
     *
     * @return array
     */
    function osc_get_premium_resources() {
        if ( !View::newInstance()->_exists('resources') ) {
            View::newInstance()->_exportVariableToView('resources', ItemResource::newInstance()->getAllResourcesFromItem( osc_premium_id() ) );
        }
        return View::newInstance()->_get('resources');
    }

    /**
     * Gets number of premium comments of current premium
     *
     * @return int
     */
    function osc_count_premium_comments() {
        if ( !View::newInstance()->_exists('comments') ) {
            View::newInstance()->_exportVariableToView('comments', ItemComment::newInstance()->findBypremiumID( osc_premium_id(), osc_premium_comments_page(), osc_comments_per_page() ) );
        }
        return View::newInstance()->_count('comments');
    }

    /**
     * Gets next comment of current premium comments
     *
     * @return array
     */
    function osc_has_premium_comments() {
        if ( !View::newInstance()->_exists('comments') ) {
            View::newInstance()->_exportVariableToView('comments', ItemComment::newInstance()->findBypremiumID( osc_premium_id(), osc_premium_comments_page(), osc_comments_per_page() ) );
        }
        return View::newInstance()->_next('comments');
    }


    /**
     * Gets number of premiums
     *
     * @access private
     * @return int
     */
    function osc_priv_count_premiums() {
        return (int) View::newInstance()->_count('premiums');
    }


    /***************
     * META FIELDS *
     ***************/

    /**
     * Gets number of premium meta field
     *
     * @return integer
     */
    function osc_count_premium_meta() {
        if ( !View::newInstance()->_exists('metafields') ) {
            View::newInstance()->_exportVariableToView('metafields', Item::newInstance()->metaFields(osc_premium_id()) );
        }
        return View::newInstance()->_count('metafields');
    }

    /**
     * Gets next premium meta field if there is, else return null
     *
     * @return array
     */
    function osc_has_premium_meta() {
        if ( !View::newInstance()->_exists('metafields') ) {
            View::newInstance()->_exportVariableToView('metafields', Item::newInstance()->metaFields(osc_premium_id()) );
        }
        return View::newInstance()->_next('metafields');
    }

    /**
     * Gets premium meta fields
     *
     * @return array
     */
    function osc_get_premium_meta() {
        if ( !View::newInstance()->_exists('metafields') ) {
            View::newInstance()->_exportVariableToView('metafields', Item::newInstance()->metaFields(osc_premium_id()) );
        }
        return View::newInstance()->_get('metafields');
    }


 ?>
