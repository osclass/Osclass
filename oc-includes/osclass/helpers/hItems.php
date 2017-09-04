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
     * Helper Items - returns object from the static class (View)
     * @package Osclass
     * @subpackage Helpers
     * @author Osclass
     */

    ////////////////////////////////////////////////////////////////
    // FUNCTIONS THAT RETURNS OBJECT FROM THE STATIC CLASS (VIEW) //
    ////////////////////////////////////////////////////////////////

    /**
    * Gets current item array from view
    *
    * @return array $item, or null if not exist
    */
    function osc_item() {
        if(View::newInstance()->_exists('item')) {
            $item = View::newInstance()->_get('item');
        } else {
            $item = null;
        }

        return($item);
    }

    /**
    * Gets comment array form view
    *
    * @return array $comment
    */
    function osc_comment() {
        if (View::newInstance()->_exists('comments')) {
            $comment = View::newInstance()->_current('comments');
        } else {
            $comment = View::newInstance()->_get('comment');
        }

        return($comment);
    }

    /**
    * Gets resource array from view
    *
    * @return array $resource
    */
    function osc_resource() {
        if (View::newInstance()->_exists('resources')) {
            $resource = View::newInstance()->_current('resources');
        } else {
            $resource = View::newInstance()->_get('resource');
        }

        return($resource);
    }

    /**
    * Gets a specific field from current item
    *
    * @param type $field
    * @param type $locale
    * @return field_type
    */
    function osc_item_field($field, $locale = "") {
        return osc_field(osc_item(), $field, $locale);
    }

    /**
    * Gets a specific field from current comment
    *
    * @param type $field
    * @param type $locale
    * @return field_type
    */
    function osc_comment_field($field, $locale = '') {
        return osc_field(osc_comment(), $field, $locale);
    }

    /**
    * Gets a specific field from current resource
    *
    * @param type $field
    * @param type $locale
    * @return field_type
    */
    function osc_resource_field($field, $locale = '') {
        return osc_field(osc_resource(), $field, $locale);
    }
    /////////////////////////////////////////////////
    // END FUNCTIONS THAT RETURNS OBJECT FROM VIEW //
    /////////////////////////////////////////////////


    ///////////////////////
    // HELPERS FOR ITEMS //
    ///////////////////////


    /**
    * Gets id from current item
    *
    * @return int
    */
    function osc_item_id() {
        return (int) osc_item_field("pk_i_id");
    }

    /**
    * Gets user id from current item
    *
    * @return int
    */
    function osc_item_user_id() {
        return (int) osc_item_field("fk_i_user_id");
    }

    /**
     * Gets description from current item, if $locale is unspecified $locale is current user locale
     *
     * @param string $locale
     * @return string $desc
     */
    function osc_item_description($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale();
        $desc = osc_item_field("s_description", $locale);
        if($desc=='') {
            $desc = osc_item_field("s_description", osc_language());
            if($desc=='') {
                $aLocales = osc_get_locales();
                foreach($aLocales as $locale) {
                    $desc = osc_item_field("s_description", @$locale['pk_c_code']);
                    if($desc!='') {
                        break;
                    }
                }
            }
        }
        return (string) $desc;
    }

    /**
     * Gets title from current item, if $locale is unspecified $locale is current user locale
     *
     * @param string $locale
     * @return string
     */
    function osc_item_title($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale();
        $title = osc_item_field("s_title", $locale);
        if($title=='') {
            $title = osc_item_field("s_title", osc_language());
            if($title=='') {
                $aLocales = osc_get_locales();
                foreach($aLocales as $locale) {
                    $title = osc_item_field("s_title", @$locale['pk_c_code']);
                    if($title!='') {
                        break;
                    }
                }
            }
        }
        return (string) $title;
    }

    /**
     * Gets category from current item
     *
     * @param string $locale
     * @return string
     */
    function osc_item_category($locale = "") {
        if ( !View::newInstance()->_exists('item_category') ) {
            View::newInstance()->_exportVariableToView('item_category', Category::newInstance()->findByPrimaryKey( osc_item_category_id(), $locale ) );
        }
        $category = View::newInstance()->_get('item_category');
        return (string) osc_field($category, "s_name", $locale);
    }

    /**
     * Gets category description from current item, if $locale is unspecified $locale is current user locale
     *
     * @param type $locale
     * @return string
     */
    function osc_item_category_description($locale = "") {
        if ( !View::newInstance()->_exists('item_category') ) {
            View::newInstance()->_exportVariableToView('item_category', Category::newInstance()->findByPrimaryKey( osc_item_category_id(), $locale ) );
        }
        $category = View::newInstance()->_get('item_category');
        return osc_field($category, "s_description", $locale);
    }

    /**
     * Gets category id of current item
     *
     * @return int
     */
    function osc_item_category_id() {
        return (int) osc_item_field("fk_i_category_id");
    }

    /**
     * Checks to see if the price is enabled for this category.
     *
     * @param string $locale
     * @return string
     */
    function osc_item_category_price_enabled($catId = null) {
		if($catId == null) {
			$category = Category::newInstance()->findByPrimaryKey( osc_item_category_id() ) ;
		} else {
			$category = Category::newInstance()->findByPrimaryKey($catId) ;
		}
        return $category['b_price_enabled']==1?true:false;
    }

    /**
     * Gets publication date of current item
     *
     * @return string
     */
    function osc_item_pub_date() {
        return (string) osc_item_field("dt_pub_date");
    }

    /**
     * Gets modification date of current item
     *
     * @return string
     */
    function osc_item_mod_date() {
        return (string) osc_item_field("dt_mod_date");
    }

    /**
     * Gets date expiration of current item
     *
     * @return string
     */
    function osc_item_dt_expiration() {
        return (string) osc_item_field("dt_expiration");
    }

    /**
     * Gets price of current item
     *
     * @return float
     */
    function osc_item_price() {
        if(osc_item_field("i_price")=='') return null;
        else return (float) osc_item_field("i_price");
    }

    /**
     * Gets formatted price of current item
     *
     * @return string
     */
    function osc_item_formatted_price() {
        return (string) osc_format_price( osc_item_price() );
    }

    /**
     * DEPRECATED: incorrect spelling of "formatted." Kept for legacy purposes
     *
     * Calls osc_item_formatted_price
     *
     * @return string
     */
    function osc_item_formated_price() {
      return osc_item_formatted_price();
    }

    /**
     * Gets currency of current item
     *
     * @return string
     */
    function osc_item_currency() {
        return (string) osc_item_field("fk_c_currency_code");
    }

    /**
     * Gets currency symbol of an item
     *
     * @since 3.0
     * @return string
     */
    function osc_item_currency_symbol() {
        $aCurrency = Currency::newInstance()->findByPrimaryKey(osc_item_currency());
        return $aCurrency['s_description'];
    }

    /**
     * Gets contact name of current item
     *
     * @return string
     */
    function osc_item_contact_name() {
        return (string) osc_item_field("s_contact_name");
    }

    /**
     * Gets contact email of current item
     *
     * @return string
     */
    function osc_item_contact_email() {
        return (string) osc_item_field("s_contact_email");
    }

    /**
     * Gets country name of current item
     *
     * @return string
     */
    function osc_item_country() {
        return (string) osc_item_field("s_country");
    }

    /**
     * Gets country code of current item
     * Country code are two letters like US, ES, ...
     *
     * @return string
     */
    function osc_item_country_code() {
        return (string) osc_item_field("fk_c_country_code");
    }

    /**
     * Gets region of current item
     *
     * @return string
     */
    function osc_item_region() {
        return (string) osc_item_field("s_region");
    }

    /**
     * Gets region id of current item
     *
     * @return string
     */
    function osc_item_region_id() {
        return (string) osc_item_field("fk_i_region_id");
    }

    /**
     * Gets city of current item
     *
     * @return string
     */
    function osc_item_city() {
        return (string) osc_item_field("s_city");
    }

    /**
     * Gets city of current item
     *
     * @return string
     */
    function osc_item_city_id() {
        return (string) osc_item_field("fk_i_city_id");
    }

    /**
     * Gets city area of current item
     *
     * @return string
     */
    function osc_item_city_area() {
        return (string) osc_item_field("s_city_area");
    }

    /**
     * Gets city area of current item
     *
     * @return string
     */
    function osc_item_city_area_id() {
        return (string) osc_item_field("fk_i_city_area_id");
    }

    /**
     * Gets address of current item
     *
     * @return string
     */
    function osc_item_address() {
        return (string) osc_item_field("s_address");
    }

    /**
     * Gets true if can show email user at frontend, else return false
     *
     * @return boolean
     */
    function osc_item_show_email() {
        return (boolean) osc_item_field("b_show_email");
    }

    /**
     * Gets zip code of current item
     *
     * @return string
     */
    function osc_item_zip() {
        return (string) osc_item_field("s_zip");
    }

    /**
     * Gets latitude of current item
     *
     * @return float
     */
    function osc_item_latitude() {
        return (float) osc_item_field("d_coord_lat");
    }

    /**
     * Gets longitude of current item
     *
     * @return float
     */
    function osc_item_longitude() {
        return (float) osc_item_field("d_coord_long");
    }

    /**
     * Gets IP of current item
     *
     * @return string
     */
    function osc_item_ip() {
        return osc_item_field("s_ip");
    }

    /**
     * Gets true if current item is marked premium, else return false
     *
     * @return boolean
     */
    function osc_item_is_premium() {
        if ( osc_item_field("b_premium") ) return true;
        else return false;
    }

    /**
     * return number of views of current item
     *
     * @return int
     */
    function osc_item_views($viewAll = false) {
        $item = osc_item();
        if($viewAll) {
            return ItemStats::newInstance()->getViews(osc_item_id());
        } else {
            if(isset($item['i_num_views'])) {
                return (int) osc_item_field("i_num_views");
            } else {
                return ItemStats::newInstance()->getViews(osc_item_id());
            }
        }
    }

    /**
     * Return true if item is expired, else return false
     *
     * @return boolean
     */
    function osc_item_is_expired() {
        if( osc_item_is_premium() ) {
            return false;
        } else {
            return osc_isExpired(osc_item_dt_expiration());
        }
    }

    /**
     * Gets status of current item.
     * b_active = true  -> item is active
     * b_active = false -> item is inactive
     *
     * @return boolean
     */
    function osc_item_status() {
        return (boolean) osc_item_field("b_active");
    }

    /**
     * Gets secret string of current item
     *
     * @return string
     */
    function osc_item_secret() {
        return (string) osc_item_field("s_secret");
    }

    /**
     * Gets if current item is active
     *
     * @return boolean
     */
    function osc_item_is_active() {
        return (osc_item_field("b_active")==1);
    }

    /**
     * Gets if current item is inactive
     *
     * @return boolean
     */
    function osc_item_is_inactive() {
        return (osc_item_field("b_active")==0);
    }

    /**
     * Gets if current item is enabled
     *
     * @return boolean
     */
    function osc_item_is_enabled() {
        return (osc_item_field("b_enabled")==1);
    }

    /**
     * Gets if item is marked as spam
     *
     * @return boolean
     */
    function osc_item_is_spam() {
        return (osc_item_field("b_spam")==1);
    }

    /**
     * Gets link for mark as spam the current item
     *
     * @return string
     */
    function osc_item_link_spam() {
        if(!osc_rewrite_enabled ()) {
            $url = osc_base_url(true) . "?page=item&action=mark&as=spam&id=" . osc_item_id();
        } else {
            $url = osc_base_url() . osc_get_preference('rewrite_item_mark') . "/spam/" . osc_item_id();
        }

        return (string) $url;
    }

    /**
     * Retrun link for mark as bad category the current item.
     *
     * @return string
     */
    function osc_item_link_bad_category() {
        if(!osc_rewrite_enabled ()) {
            $url = osc_base_url(true) . "?page=item&action=mark&as=badcat&id=" . osc_item_id();
        } else {
            $url = osc_base_url() . osc_get_preference('rewrite_item_mark') . "/badcat/" . osc_item_id();
        }

        return (string) $url;
    }

    /**
     * Gets link for mark as repeated the current item
     *
     * @return string
     */
    function osc_item_link_repeated() {
        if(!osc_rewrite_enabled ()) {
            $url = osc_base_url(true) . "?page=item&action=mark&as=repeated&id=" . osc_item_id();
        } else {
            $url = osc_base_url() . osc_get_preference('rewrite_item_mark') . "/repeated/" . osc_item_id();
        }

        return (string) $url;
    }

    /**
     * Gets link for mark as offensive the current item
     *
     * @return string
     */
    function osc_item_link_offensive() {
        if(!osc_rewrite_enabled ()) {
            $url = osc_base_url(true) . "?page=item&action=mark&as=offensive&id=" . osc_item_id();
        } else {
            $url = osc_base_url() . osc_get_preference('rewrite_item_mark') . "/offensive/" . osc_item_id();
        }

        return (string) $url;
    }

    /**
     * Gets link for mark as expired the current item
     *
     * @return string
     */
    function osc_item_link_expired() {
        if(!osc_rewrite_enabled ()) {
            $url = osc_base_url(true) . "?page=item&action=mark&as=expired&id=" . osc_item_id();
        } else {
            $url = osc_base_url() . osc_get_preference('rewrite_item_mark') . "/expired/" . osc_item_id();
        }

        return (string) $url;
    }

    // DEPRECATED: This function will be removed in version 4.0
    function osc_list_page() {
        return osc_search_page();
    }

    // DEPRECATED: This function will be removed in version 4.0
    function osc_list_total_pages() {
        return osc_search_total_pages();
    }

    /**
     * Gets number of items per page for current pagination
     *
     * @return <type>
     */
    function osc_list_items_per_page() {
        return View::newInstance()->_get('items_per_page');
    }

    /**
     * Gets total number of comments of current item
     *
     * @return int
     */
    function osc_item_total_comments() {
        return ItemComment::newInstance()->totalComments( osc_item_id() );
    }

    /**
     * Gets page of comments in current pagination
     *
     * @return <type>
     */
    function osc_item_comments_page() {
        $page = Params::getParam('comments-page');
        if($page=='') {
            return 0;
        }
        if($page>0) {
            return (int) $page-1;
        }
    }

    ///////////////////////
    // HELPERS FOR ITEMS //
    ///////////////////////


    //////////////////////////
    // HELPERS FOR COMMENTS //
    //////////////////////////

    /**
     * Gets id of current comment
     *
     * @return int
     */
    function osc_comment_id() {
        return (int) osc_comment_field("pk_i_id");
    }

    /**
     * Gets publication date of current comment
     *
     * @return string
     */
    function osc_comment_pub_date() {
        return (string) osc_comment_field("dt_pub_date");
    }

    /**
     * Gets title of current commnet
     *
     * @return string
     */
    function osc_comment_title() {
        return (string) osc_comment_field("s_title");
    }

    /**
     * Gets author name of current comment
     *
     * @return string
     */
    function osc_comment_author_name() {
        return (string) osc_comment_field("s_author_name");
    }

    /**
     * Gets author email of current comment
     *
     * @return string
     */
    function osc_comment_author_email() {
        return (string) osc_comment_field("s_author_email");
    }

    /**
     * Gets body of current comment
     *
     * @return string
     */
    function osc_comment_body() {
        return (string) osc_comment_field("s_body");
    }

    /**
     * Gets user id of current comment
     *
     * @return int
     */
    function osc_comment_user_id() {
        return (int) osc_comment_field("fk_i_user_id");
    }

    /**
     * Gets  link to delete the current comment of current item
     *
     * @return string
     */
    function osc_delete_comment_url() {
        return (string) osc_base_url(true)."?page=item&action=delete_comment&id=".osc_item_id()."&comment=".osc_comment_id()."&".osc_csrf_token_url();
    }

    //////////////////////////////
    // END HELPERS FOR COMMENTS //
    //////////////////////////////

    ///////////////////////////
    // HELPERS FOR RESOURCES //
    ///////////////////////////

    /**
     * Gets id of current resource
     *
     * @return int
     */
    function osc_resource_id() {
        return (int) osc_resource_field("pk_i_id");
    }

    /**
     * Gets name of current resource
     *
     * @return string
     */
    function osc_resource_name() {
        return (string) osc_resource_field("s_name");
    }

    /**
     * Gets content type of current resource
     *
     * @return string
     */
    function osc_resource_type() {
        return (string) osc_resource_field("s_content_type");
    }

    /**
     * Gets extension of current resource
     *
     * @return string
     */
    function osc_resource_extension() {
        return (string) osc_resource_field("s_extension");
    }

    /**
     * Gets path of current resource
     *
     * @return string
     */
    function osc_resource_path() {
        return (string) osc_apply_filter('resource_path', osc_base_url().osc_resource_field("s_path"));
    }

    /**
     * Gets url of current resource
     *
     * @return string
     */
    function osc_resource_url() {
        return (string) osc_resource_path().osc_resource_id().".".osc_resource_field("s_extension");
    }

    /**
     * Gets thumbnail url of current resource
     *
     * @return string
     */
    function osc_resource_thumbnail_url() {
        return (string) osc_resource_path().osc_resource_id()."_thumbnail.".osc_resource_field("s_extension");
    }

    /**
     * Gets preview url of current resource
     *
     * @since 2.3.7
     * @return string
     */
    function osc_resource_preview_url() {
        return (string) osc_resource_path().osc_resource_id()."_preview.".osc_resource_field("s_extension");
    }

    /**
     * Gets original resource url of current resource
     *
     * @return string
     */
    function osc_resource_original_url() {
        return (string) osc_resource_path().osc_resource_id()."_original.".osc_resource_field("s_extension");
    }

    /**
     * Set the internal pointer of array resources to its first element, and return it.
     *
     * @since 2.3.6
     * @return array
     */
    function osc_reset_resources() {
        return View::newInstance()->_reset('resources');
    }


    ///////////////////////////////
    // END HELPERS FOR RESOURCES //
    ///////////////////////////////

    /////////////
    // DETAILS //
    /////////////

    /**
     * Gets next item if there is, else return null
     *
     * @return array
     */
    function osc_has_items() {
        if ( View::newInstance()->_exists('resources') ) {
            View::newInstance()->_erase('resources');
        }
        if ( View::newInstance()->_exists('item_category') ) {
            View::newInstance()->_erase('item_category');
        }
        if ( View::newInstance()->_exists('metafields') ) {
            View::newInstance()->_erase('metafields');
        }
        if(View::newInstance()->_get('itemLoop')!='items') {
            View::newInstance()->_exportVariableToView('oldItem', View::newInstance()->_get('item'));
            View::newInstance()->_exportVariableToView('itemLoop', 'items');
        }
        $item = View::newInstance()->_next('items');
        if(!$item) {
            View::newInstance()->_exportVariableToView('item', View::newInstance()->_get('oldItem'));
            View::newInstance()->_exportVariableToView('itemLoop', '');
        } else {
            View::newInstance()->_exportVariableToView('item', View::newInstance()->_current('items'));
        }
        return $item;
    }

    /**
     * Set the internal pointer of array items to its first element, and return it.
     *
     * @return array
     */
    function osc_reset_items() {
        View::newInstance()->_exportVariableToView('item', View::newInstance()->_get('oldItem'));
        View::newInstance()->_exportVariableToView('itemLoop', '');
        return View::newInstance()->_reset('items');
    }

    /**
     * Set the internal pointer of array latestItems to its first element, and return it.
     *
     * @since 2.4
     * @return array
     */
    function osc_reset_latest_items() {
        View::newInstance()->_exportVariableToView('item', View::newInstance()->_get('oldItem'));
        View::newInstance()->_exportVariableToView('itemLoop', '');
        return View::newInstance()->_reset('latestItems');
    }

    /**
     * Gets number of items in current array items
     *
     * @return int
     */
    function osc_count_items() {
        return (int) View::newInstance()->_count('items');
    }

    /**
     * Gets number of resources in array resources of current item
     *
     * @return int
     */
    function osc_count_item_resources() {
        if ( !View::newInstance()->_exists('resources') ) {
            View::newInstance()->_exportVariableToView('resources', ItemResource::newInstance()->getAllResourcesFromItem( osc_item_id() ) );
        }
        return (int) View::newInstance()->_count('resources');
    }

    /**
     * Gets next item resource if there is, else return null
     *
     * @return array
     */
    function osc_has_item_resources() {
        if ( !View::newInstance()->_exists('resources') ) {
            View::newInstance()->_exportVariableToView('resources', ItemResource::newInstance()->getAllResourcesFromItem( osc_item_id() ) );
        }
        return View::newInstance()->_next('resources');
    }

    /**
     * Gets current resource of current array resources of current item
     *
     * @return array
     */
    function osc_get_item_resources() {
        if ( !View::newInstance()->_exists('resources') ) {
            View::newInstance()->_exportVariableToView('resources', ItemResource::newInstance()->getAllResourcesFromItem( osc_item_id() ) );
        }
        return View::newInstance()->_get('resources');
    }

    /**
     * Gets number of item comments of current item
     *
     * @return int
     */
    function osc_count_item_comments() {
        if ( !View::newInstance()->_exists('comments') ) {
            View::newInstance()->_exportVariableToView('comments', ItemComment::newInstance()->findByItemID( osc_item_id(), osc_item_comments_page(), osc_comments_per_page() ) );
        }
        return View::newInstance()->_count('comments');
    }

    /**
     * Gets next comment of current item comments
     *
     * @return array
     */
    function osc_has_item_comments() {
        if ( !View::newInstance()->_exists('comments') ) {
            View::newInstance()->_exportVariableToView('comments', ItemComment::newInstance()->findByItemID( osc_item_id(), osc_item_comments_page(), osc_comments_per_page() ) );
        }
        return View::newInstance()->_next('comments');
    }

    //////////
    // HOME //
    //////////

    /**
     * Gets next item of latest items query
     *
     * @return boolean It returns true if there is another item available or false if there isn't
     */
    function osc_has_latest_items($total_latest_items = null, $options = array(), $withPicture = false) {
        // if we don't have the latest items loaded, do the query
        if ( !View::newInstance()->_exists('latestItems') ) {
            $search = Search::newInstance();
            if( !is_numeric($total_latest_items) ) {
                $total_latest_items = osc_max_latest_items();
            }

            View::newInstance()->_exportVariableToView('latestItems', $search->getLatestItems($total_latest_items, $options, $withPicture));
        }

        // keys we want to erase from View
        $to_erase = array('resources', 'item_category', 'metafields');
        foreach($to_erase as $t) {
            if ( View::newInstance()->_exists($t) ) {
                View::newInstance()->_erase($t);
            }
        }

        // set itemLoop to latest if it's the first time we enter here
        if(View::newInstance()->_get('itemLoop') !== 'latest') {
            View::newInstance()->_exportVariableToView('oldItem', View::newInstance()->_get('item'));
            View::newInstance()->_exportVariableToView('itemLoop', 'latest');
        }

        // get next item
        $item = View::newInstance()->_next('latestItems');

        if(!$item) {
            View::newInstance()->_exportVariableToView('item', View::newInstance()->_get('oldItem'));
            View::newInstance()->_exportVariableToView('itemLoop', '');
        } else {
            View::newInstance()->_exportVariableToView('item', View::newInstance()->_current('latestItems'));
        }

        // reset the loop once we finish just in case we want to use it again
        if( !$item && View::newInstance()->_count('latestItems') > 0 ) {
            View::newInstance()->_reset('latestItems');
        }

        return $item;
    }

    /**
     * Gets number of latest items
     *
     * @return int
     */
    function osc_count_latest_items($total_latest_items = null, $options = array()) {
        if ( !View::newInstance()->_exists('latestItems') ) {
            $search = Search::newInstance();
            if( !is_numeric($total_latest_items) ) {
                $total_latest_items = osc_max_latest_items();
            }
            if(is_array($options) && empty($options)) {
                $options = osc_get_subdomain_params();
            } else if($options==null) {
                $options = array();
            }
            View::newInstance()->_exportVariableToView('latestItems', $search->getLatestItems($total_latest_items, $options));
        };
        return (int) View::newInstance()->_count('latestItems');
    }

    //////////////
    // END HOME //
    //////////////

    /**
     * Gets next item of custom items
     *
     * @return array
     */
    function osc_has_custom_items() {
        if ( View::newInstance()->_exists('resources') ) {
            View::newInstance()->_erase('resources');
        }
        if ( View::newInstance()->_exists('item_category') ) {
            View::newInstance()->_erase('item_category');
        }
        if ( View::newInstance()->_exists('metafields') ) {
            View::newInstance()->_erase('metafields');
        }
        if(View::newInstance()->_get('itemLoop')!='custom') {
            View::newInstance()->_exportVariableToView('oldItem', View::newInstance()->_get('item'));
            View::newInstance()->_exportVariableToView('itemLoop', 'custom');
        }
        $item = View::newInstance()->_next('customItems');
        if(!$item) {
            View::newInstance()->_exportVariableToView('item', View::newInstance()->_get('oldItem'));
            View::newInstance()->_exportVariableToView('itemLoop', '');
        } else {
            View::newInstance()->_exportVariableToView('item', View::newInstance()->_current('customItems'));
        }
        return $item;
    }

    /**
     * Gets number of custom items
     *
     * @return int
     */
    function osc_count_custom_items() {
        return (int) View::newInstance()->_count('customItems');
    }

    /**
     * Set the internal pointer of array customItems to its first element, and return it.
     *
     * @since 2.4
     * @return array
     */
    function osc_reset_custom_items() {
        View::newInstance()->_exportVariableToView('item', View::newInstance()->_get('oldItem'));
        View::newInstance()->_exportVariableToView('itemLoop', '');
        return View::newInstance()->_reset('customItems');
    }

    /**
     * Formats the price using the appropiate currency.
     *
     * @param float $price
     * @return string
     */
    function osc_format_price($price, $symbol = null) {
        if ($price === null) return osc_apply_filter ('item_price_null', __('Check with seller') );
        if ($price == 0) return osc_apply_filter ('item_price_zero', __('Free') );

        if($symbol==null) { $symbol = osc_item_currency_symbol(); }

        $price = $price/1000000;

        $currencyFormat = osc_locale_currency_format();
        $currencyFormat = str_replace('{NUMBER}', number_format($price, osc_locale_num_dec(), osc_locale_dec_point(), osc_locale_thousands_sep()), $currencyFormat);
        $currencyFormat = str_replace('{CURRENCY}', $symbol, $currencyFormat);
        return osc_apply_filter('item_price', $currencyFormat );
    }

    /**
     * Gets number of items
     *
     * @deprecated deprecated since version 2.4
     * @access private
     * @return int
     */
    function osc_priv_count_items() {
        return (int) View::newInstance()->_count('items');
    }

    /**
     * Gets number of item resources
     *
     * @deprecated deprecated since version 2.4
     * @access private
     * @return int
     */
    function osc_priv_count_item_resources() {
        return (int) View::newInstance()->_count('resources');
    }

    /***************
     * META FIELDS *
     ***************/

    /**
     * Gets number of item meta field
     *
     * @return integer
     */
    function osc_count_item_meta() {
        if ( !View::newInstance()->_exists('metafields') ) {
            View::newInstance()->_exportVariableToView('metafields', Item::newInstance()->metaFields(osc_item_id()) );
        }
        return View::newInstance()->_count('metafields');
    }

    /**
     * Gets next item meta field if there is, else return null
     *
     * @return array
     */
    function osc_has_item_meta() {
        if ( !View::newInstance()->_exists('metafields') ) {
            View::newInstance()->_exportVariableToView('metafields', Item::newInstance()->metaFields(osc_item_id()) );
        }
        return View::newInstance()->_next('metafields');
    }

    /**
     * Gets item meta fields
     *
     * @return array
     */
    function osc_get_item_meta() {
        if ( !View::newInstance()->_exists('metafields') ) {
            View::newInstance()->_exportVariableToView('metafields', Item::newInstance()->metaFields(osc_item_id()) );
        }
        return View::newInstance()->_get('metafields');
    }

    /**
     * Gets item meta field
     *
     * @return array
     */
    function osc_item_meta() {
        return View::newInstance()->_current('metafields');
    }

    /**
     * Gets item meta value
     *
     * @return string
     */
    function osc_item_meta_value() {
        $meta = osc_item_meta();
        if($meta['e_type']=="DATEINTERVAL" || $meta['e_type']=="DATE") {
            $value = osc_field(osc_item_meta(), 's_value', '');
            if(is_array($value)) {
                // from [date_from] to [date_to]
                if(isset($value['from']) && $value['from']!='' && is_numeric($value['from']) && isset($value['to']) && $value['to']!='' && is_numeric($value['to'])) {
                    $return  = __('From') . ' ' . htmlentities( date(osc_date_format(), $value['from']), ENT_COMPAT, "UTF-8");
                    $return .= ' ' . __('to') . ' ' . htmlentities( date(osc_date_format(), $value['to']), ENT_COMPAT, "UTF-8");
                    return $return;
                } else {
                    return '';
                }
            } else {
                if($value!='' && is_numeric($value)) {
                    return htmlentities( date(osc_date_format(), $value), ENT_COMPAT, "UTF-8");
                } else {
                    return '';
                }
            }
        } else if($meta['e_type']=="CHECKBOX") {
            if(osc_field(osc_item_meta(), 's_value', '')==1) {
                return '<img src="'.osc_current_web_theme_url('images/tick.png').'" alt="" title=""/>';
            } else {
                return '<img src="'.osc_current_web_theme_url('images/cross.png').'" alt="" title=""/>';
            }
        } else if($meta['e_type']=="URL") {
            if(osc_field(osc_item_meta(), 's_value', '')!='') {
                if(stripos(osc_field(osc_item_meta(), 's_value', ''),'http://')!==false || stripos(osc_field(osc_item_meta(), 's_value', ''),'https://')!==false) {
                    return '<a href="'.html_entity_decode(osc_field(osc_item_meta(), 's_value', ''), ENT_COMPAT, "UTF-8").'" >'.html_entity_decode(osc_field(osc_item_meta(), 's_value', ''), ENT_COMPAT, "UTF-8").'</a>';
                } else {
                    return '<a href="http://'.html_entity_decode(osc_field(osc_item_meta(), 's_value', ''), ENT_COMPAT, "UTF-8").'" >'.html_entity_decode(osc_field(osc_item_meta(), 's_value', ''), ENT_COMPAT, "UTF-8").'</a>';
                }
            } else {
                return '';
            }
        } else if($meta['e_type']=="DROPDOWN" || $meta['e_type']=="RADIO") {
            return osc_field(osc_item_meta(), 's_value', '');
        } else {
            return nl2br(htmlentities(osc_field(osc_item_meta(), 's_value', ''), ENT_COMPAT, "UTF-8"));
        }
    }

    /**
     * Gets item meta name
     *
     * @return string
     */
    function osc_item_meta_name() {
        return osc_field(osc_item_meta(), 's_name', '');
    }

    /**
     * Gets item meta id
     *
     * @return integer
     */
    function osc_item_meta_id() {
        return osc_field(osc_item_meta(), 'pk_i_id', '');
    }

    /**
     * Gets item meta slug
     *
     * @return string
     */
    function osc_item_meta_slug() {
        return osc_field(osc_item_meta(), 's_slug', '');
    }

    /**
     * Gets total number of active items
     *
     * @return string
     */
    function osc_total_active_items() {
        return Item::newInstance()->totalItems(null, 'ACTIVE|ENABLED|NOTEXPIRED');
    }

    /**
     * Gets total number of all items
     *
     * @return string
     */
    function osc_total_items() {
        return Item::newInstance()->totalItems(null);
    }

    /**
     * Gets total number of active items today
     *
     * @return string
     */
    function osc_total_active_items_today() {
        return Item::newInstance()->totalItems(null, 'ACTIVE|ENABLED|NOTEXPIRED|TODAY');
    }

    /**
     * Gets total number of all items today
     *
     * @return string
     */
    function osc_total_items_today() {
        return Item::newInstance()->totalItems(null, 'TODAY');
    }


    /**
     * Perform a search based on custom filters and conditions
     * export the results to a variable to be able to manage it
     * from custom_items' helpers
     *
     *
     * @param params This could be a string or and array
     * Examples:
     *  Only one keyword
     *  osc_query_item("keyword=value1,value2,value3,...")
     *
     *  Multiple keywords
     *  osc_query_item(array(
     *      'keyword1' => 'value1,value2',
     *      'keyword2' => 'value3,value4'
     *  ))
     *
     * Real live examples:
     *  osc_query_item('category_name=cars,houses');
     *  osc_query_item(array(
     *      'category_name' => 'cars,houses',
     *      'city' => 'Madrid'
     *  ))
     *
     * Possible keywords:
     *  author
     *  country
     *  country_name
     *  region
     *  region_name
     *  city
     *  city_name
     *  city_area
     *  city_area_name
     *  category
     *  category_name
     *  premium
     *  results_per_page
     *  page
     *  offset
     *
     *  Any other keyword will be passed to the hook "custom_query"
     *   osc_run_hook("custom_query", $mSearch, $keyword, $value);
     *  A plugin could be created to handle those extra situation
     *
     * @since 3.0
     */
    function osc_query_item($params = null) {
        $mSearch = new Search();
        if($params==null) {
            $params = array();
        } else if(is_string($params)){
            $keyvalue = explode("=", $params);
            $params = array($keyvalue[0] => $keyvalue[1]);
        }
        foreach($params as $key => $value) {
            switch($key) {
                case 'id':
                    $mSearch->addItemId($value);
                    break;
                case 'pattern':
                    $mSearch->addPattern($value);
                    break;
                case 'author':
                    $tmp = explode(",", $value);
                    foreach($tmp as $t) {
                        $mSearch->fromUser($t);
                    }
                    break;

                case 'category':
                case 'category_name':
                    $tmp = explode(",", $value);
                    foreach($tmp as $t) {
                        $mSearch->addCategory($t);
                    }
                    break;

                case 'country':
                case 'country_name':
                    $tmp = explode(",", $value);
                    foreach($tmp as $t) {
                        $mSearch->addCountry($t);
                    }
                    break;

                case 'region':
                case 'region_name':
                    $tmp = explode(",", $value);
                    foreach($tmp as $t) {
                        $mSearch->addRegion($t);
                    }
                    break;

                case 'city':
                case 'city_name':
                    $tmp = explode(",", $value);
                    foreach($tmp as $t) {
                        $mSearch->addCity($t);
                    }
                    break;

                case 'city_area':
                case 'city_area_name':
                    $tmp = explode(",", $value);
                    foreach($tmp as $t) {
                        $mSearch->addCityArea($t);
                    }
                    break;

                case 'results_per_page':
                    $mSearch->set_rpp($value);
                    break;

                case 'premium':
                    $mSearch->onlyPremium(($value==1?true:false));
                    break;

                case 'page':
                    $mSearch->page($value);
                    break;

                case 'offset':
                    $mSearch->limit($value);
                    break;

                default:
                    osc_run_hook('custom_query', $mSearch, $key, $value);
                    break;
            }
        }
        View::newInstance()->_exportVariableToView("customItems", $mSearch->doSearch());
    }


 ?>
