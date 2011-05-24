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

     /**
     * Helper Items - returns object from the static class (View)
     * @package OSClass
     * @subpackage Items
     * @author OSClass
     */

    ////////////////////////////////////////////////////////////////
    // FUNCTIONS THAT RETURNS OBJECT FROM THE STATIC CLASS (VIEW) //
    ////////////////////////////////////////////////////////////////

    /**
    * Return item array from view
    * 
    * @return array $item, or null if not exist
    */
    function osc_item() {
        if (View::newInstance()->_exists('items')) {
            $item = View::newInstance()->_current('items') ;
        } else if(View::newInstance()->_exists('item')) {
            $item = View::newInstance()->_get('item') ;
        } else {
            $item = null;
        }

        return($item) ;
    }

    /**
    * Return comment array form view
    * 
    * @return array $comment 
    */
    function osc_comment() {
        if (View::newInstance()->_exists('comments')) {
            $comment = View::newInstance()->_current('comments') ;
        } else {
            $comment = View::newInstance()->_get('comment') ;
        }

        return($comment) ;
    }
    
    /**
    * Return resource array from view
    * 
    * @return array $resource
    */
    function osc_resource() {
        if (View::newInstance()->_exists('resources')) {
            $resource = View::newInstance()->_current('resources') ;
        } else {
            $resource = View::newInstance()->_get('resource') ;
        }

        return($resource) ;
    }

    /**
    * Return a specific field from current item
    * 
    * @param type $field
    * @param type $locale
    * @return field_type 
    */
    function osc_item_field($field, $locale = "") {
        return osc_field(osc_item(), $field, $locale) ;
    }

    /**
    * Return a specific field from current comment
    * 
    * @param type $field
    * @param type $locale
    * @return field_type 
    */
    function osc_comment_field($field, $locale = '') {
        return osc_field(osc_comment(), $field, $locale) ;
    }

    /**
    * Return a specific field from current resource
    * 
    * @param type $field
    * @param type $locale
    * @return field_type 
    */
    function osc_resource_field($field, $locale = '') {
        return osc_field(osc_resource(), $field, $locale) ;
    }
    /////////////////////////////////////////////////
    // END FUNCTIONS THAT RETURNS OBJECT FROM VIEW //
    /////////////////////////////////////////////////


    ///////////////////////
    // HELPERS FOR ITEMS //
    ///////////////////////

    
    /**
    * Return id from current item
    * 
    * @return int
    */
    function osc_item_id() {
        return (int) osc_item_field("pk_i_id");
    }

    /**
    * Return user id from current item
    * 
    * @return int
    */
    function osc_item_user_id() {
        return (int) osc_item_field("fk_i_user_id") ;
    }

    /**
     * Return description from current item, if $locale is unspecified $locale is current user locale
     *
     * @param string $locale
     * @return string $desc 
     */
    function osc_item_description($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale() ;
        $desc = osc_item_field("s_description", $locale) ;
        if($desc=='') {
            $desc = osc_item_field("s_description", osc_language());
            if($desc=='') {
                $aLocales = osc_get_locales();
                foreach($aLocales as $locale) {
                    $desc = osc_item_field("s_description", $locale);
                    if($desc!='') {
                        break;
                    }
                }
            }
        }
        return (string) $desc;
    }

    /**
     * Return title from current item, if $locale is unspecified $locale is current user locale
     * 
     * @param string $locale
     * @return string 
     */
    function osc_item_title($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale() ;
        $title = osc_item_field("s_title", $locale) ;
        if($title=='') {
            $title = osc_item_field("s_title", osc_language());
            if($title=='') {
                $aLocales = osc_get_locales();
                foreach($aLocales as $locale) {
                    $title = osc_item_field("s_title", $locale);
                    if($title!='') {
                        break;
                    }
                }
            }
        }
        return (string) $title;
    }

    /**
     * Return category from current item
     *
     * @param string $locale
     * @return string 
     */
    function osc_item_category($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale() ;
        $category = Category::newInstance()->findByPrimaryKey( osc_item_category_id() ) ;
        return (string) osc_field($category, "s_name", $locale) ;
    }

    /**
     * Return category description from current item, if $locale is unspecified $locale is current user locale
     *
     * @param type $locale
     * @return string 
     */
    function osc_item_category_description($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale() ;
        $category = Category::newInstance()->findByPrimaryKey( osc_item_category_id() ) ;
        return osc_field($category, "s_description", $locale) ;
    }

    /**
     * Return category id of current item
     *
     * @return int 
     */
    function osc_item_category_id() {
        return (int) osc_item_field("fk_i_category_id") ;
    }

    /**
     * Return publication date of current item
     *
     * @return string
     */
    function osc_item_pub_date() {
        return (string) osc_item_field("dt_pub_date");
    }

    /**
     * Return modification date of current item
     *
     * @return string
     */
    function osc_item_mod_date() {
        return (string) osc_item_field("dt_mod_date");
    }

    /**
     * Return price of current item
     *
     * @return float
     */
    function osc_item_price() {
        return (float) osc_item_field("f_price") ;
    }

    /**
     * Return formated price of current item
     *
     * @return string
     */
    function osc_item_formated_price() {
        return (string) osc_format_price( osc_item_field("f_price") ) ;
    }

    /**
     * Return currency of current item
     *
     * @return string
     */
    function osc_item_currency() {
        return (string) osc_item_field("fk_c_currency_code");
    }

    /**
     * Return contact name of current item
     *
     * @return string
     */
    function osc_item_contact_name() {
        return (string) osc_item_field("s_contact_name");
    }

    /**
     * Return contact email of current item
     *
     * @return string
     */
    function osc_item_contact_email() {
        return (string) osc_item_field("s_contact_email");
    }

    /**
     * Return country name of current item
     *
     * @return string
     */
    function osc_item_country() {
        return (string) osc_item_field("s_country");
    }

    /**
     * Return country code of current item
     * Country code are two letters like US, ES, ...
     *
     * @return string
     */
    function osc_item_country_code() {
        return (string) osc_item_field("fk_c_country_code");
    }

    /**
     * Return region of current item
     *
     * @return string
     */
    function osc_item_region() {
        return (string) osc_item_field("s_region");
    }

    /**
     * Return city of current item
     *
     * @return string
     */
    function osc_item_city() {
        return (string) osc_item_field("s_city");
    }

    /**
     * Return city area of current item
     *
     * @return string
     */
    function osc_item_city_area() {
        return (string) osc_item_field("s_city_area");
    }

    /**
     * Return address of current item
     *
     * @return string
     */
    function osc_item_address() {
        return (string) osc_item_field("s_address");
    }

    /**
     * Return true if can show email user at frontend, else return false
     *
     * @return boolean
     */
    function osc_item_show_email() {
        return (boolean) osc_item_field("b_show_email");
    }

    /**
     * Return zup code of current item
     *
     * @return string
     */
    function osc_item_zip() {
        return (string) osc_item_field("s_zip");
    }

    /**
     * Return latitude of current item
     *
     * @return float
     */
    function osc_item_latitude() {
        return (float) osc_item_field("d_coord_lat");
    }

    /**
     * Return longitude of current item
     *
     * @return float
     */
    function osc_item_longitude() {
        return (float) osc_item_field("d_coord_long");
    }

    /**
     * Return true if current item is marked premium, else return false
     *
     * @return boolean
     */
    function osc_item_is_premium() {
        if ( osc_item_field("b_premium") ) return true ;
        else return false ;
    }

    /**
     * return number of views of current item
     *
     * @return int
     */
    function osc_item_views() {
        return (int) osc_item_field("i_num_views") ;
    }

    /**
     * Return status of current item.
     * b_active = true  -> item is active
     * b_active = false -> item is inactive
     *
     * @return boolean
     */
    function osc_item_status() {
        return (boolean) osc_item_field("b_active");
    }

    /**
     * Return secret string of current item
     *
     * @return string
     */
    function osc_item_secret() {
        return (string) osc_item_field("s_secret");
    }

    /**
     * Return if current item is active
     *
     * @return boolean
     */
    function osc_item_is_active() {
        return (osc_item_field("b_active")==1);
    }

    /**
     * Return if current item is inactive
     *
     * @return boolean
     */
    function osc_item_is_inactive() {
        return (osc_item_field("b_active")==0);
    }

    /**
     * Return if item is marked as spam
     *
     * @return boolean
     */
    function osc_item_is_spam() {
        return (osc_item_field("b_spam")==1);
    }

    /**
     * Return link for mark as spam the current item
     *
     * @return string
     */
    function osc_item_link_spam() {
        if(!osc_rewrite_enabled ()) {
            $url = osc_base_url(true) . "?page=item&action=mark&as=spam&id=" . osc_item_id() ;
        } else {
            $url = osc_base_url() . "item/mark/spam/" . osc_item_id() ;
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
            $url = osc_base_url(true) . "?page=item&action=mark&as=badcat&id=" . osc_item_id() ;
        } else {
            $url = osc_base_url() . "item/mark/badcat/" . osc_item_id() ;
        }

        return (string) $url;
    }

    /**
     * Return link for mark as repeated the current item
     *
     * @return string
     */
    function osc_item_link_repeated() {
        if(!osc_rewrite_enabled ()) {
            $url = osc_base_url(true) . "?page=item&action=mark&as=repeated&id=" . osc_item_id() ;
        } else {
            $url = osc_base_url() . "item/mark/repeated/" . osc_item_id() ;
        }

        return (string) $url;
    }

    /**
     * Return link for mark as offensive the current item
     *
     * @return string
     */
    function osc_item_link_offensive() {
        if(!osc_rewrite_enabled ()) {
            $url = osc_base_url(true) . "?page=item&action=mark&as=offensive&id=" . osc_item_id() ;
        } else {
            $url = osc_base_url() . "item/mark/offensive/" . osc_item_id() ;
        }

        return (string) $url;
    }

    /**
     * Return link for mark as expired the current item
     *
     * @return string
     */
    function osc_item_link_expired() {
        if(!osc_rewrite_enabled ()) {
            $url = osc_base_url(true) . "?page=item&action=mark&as=expired&id=" . osc_item_id() ;
        } else {
            $url = osc_base_url() . "item/mark/expired/" . osc_item_id() ;
        }

        return (string) $url;
    }

    /**
     * Return actual page for current pagination
     *
     * @return int
     */
    function osc_list_page() {
        return View::newInstance()->_get('list_page');
    }

    /**
     * Return total of pages for current pagination
     *
     * @return int
     */
    function osc_list_total_pages() {
        return View::newInstance()->_get('list_total_pages');
    }

    /**
     * Return number of items per page for current pagination
     *
     * @return <type>
     */
    function osc_list_items_per_page() {
        return View::newInstance()->_get('items_per_page');
    }    

    /**
     * Return total number of comments of current item
     *
     * @return int
     */
    function osc_item_total_comments() {
        return ItemComment::newInstance()->total_comments(osc_item_id());
    }

    /**
     * Return page of comments in current pagination
     *
     * @return <type>
     */
    function osc_item_comments_page() {
        $page = Params::getParam('comments-page');
        if($page=='') {
            $page = 0;
        }
        return (int) $page;
    }
    
    ///////////////////////
    // HELPERS FOR ITEMS //
    ///////////////////////
    

    //////////////////////////
    // HELPERS FOR COMMENTS //
    //////////////////////////

    /**
     * Return id of current comment
     *
     * @return int
     */
    function osc_comment_id() {
        return (int) osc_comment_field("pk_i_id");
    }

    /**
     * Return publication date of current comment
     *
     * @return string
     */
    function osc_comment_pub_date() {
        return (string) osc_comment_field("dt_pub_date");
    }

    /**
     * Return title of current commnet
     *
     * @return string
     */
    function osc_comment_title() {
        return (string) osc_comment_field("s_title");
    }

    /**
     * Return author name of current comment
     *
     * @return string
     */
    function osc_comment_author_name() {
        return (string) osc_comment_field("s_author_name");
    }

    /**
     * Return author email of current comment
     *
     * @return string
     */
    function osc_comment_author_email() {
        return (string) osc_comment_field("s_author_email");
    }

    /**
     * Return body of current comment
     *
     * @return string
     */
    function osc_comment_body() {
        return (string) osc_comment_field("s_body");
    }

    /**
     * Return user id of current comment
     *
     * @return int
     */
    function osc_comment_user_id() {
        return (int) osc_comment_field("fk_i_user_id");
    }

    /**
     * Return  link to delete the current comment of current item
     *
     * @return string
     */
    function osc_delete_comment_url() {
        return (string) osc_base_url(true) . "?page=item&action=delete_comment&id=" . osc_item_id() . "&comment=" . osc_comment_id();
    }

    //////////////////////////////
    // END HELPERS FOR COMMENTS //
    //////////////////////////////

    ///////////////////////////
    // HELPERS FOR RESOURCES //
    ///////////////////////////

    /**
     * Return id of current resource
     *
     * @return int
     */
    function osc_resource_id() {
        return (int) osc_resource_field("pk_i_id");
    }

    /**
     * Return name of current resource
     *
     * @return string
     */
    function osc_resource_name() {
        return (string) osc_resource_field("s_name");
    }

    /**
     * Return content type of current resource
     *
     * @return string
     */
    function osc_resource_type() {
        return (string) osc_resource_field("s_content_type");
    }

    /**
     * Return extension of current resource
     *
     * @return string
     */
    function osc_resource_extension() {
        return (string) osc_resource_field("s_extension");
    }

    /**
     * Return path of current resource
     *
     * @return string
     */
    function osc_resource_path() {
        return (string) osc_base_url().osc_resource_field("s_path");
    }

    /**
     * Return thumbnail url of current resource
     *
     * @return <type>
     */
    function osc_resource_thumbnail_url() {
        return (string) osc_resource_path().osc_resource_id()."_thumbnail.".osc_resource_field("s_extension");
    }

    /**
     * Return url of current resource
     *
     * @return string
     */
    function osc_resource_url() {
        return (string) osc_resource_path().osc_resource_id().".".osc_resource_field("s_extension");
    }

    /**
     * Return original resource url of current resource
     *
     * @return string
     */
    function osc_resource_original_url() {
        return (string) osc_resource_path().osc_resource_id()."_original.".osc_resource_field("s_extension");
    }
    ///////////////////////////////
    // END HELPERS FOR RESOURCES //
    ///////////////////////////////
    
    /////////////
    // DETAILS //
    /////////////

    /**
     * Return next item if there is, else return null
     *
     * @return array
     */
    function osc_has_items() {
        if ( View::newInstance()->_exists('resources') ) {
            View::newInstance()->_erase('resources') ;
        }
        return View::newInstance()->_next('items') ;
    }

    /**
     * Set the internal pointer of array items to its first element, and return it.
     *
     * @return array
     */
    function osc_reset_items() {
        return View::newInstance()->_reset('items') ;
    }

    /**
     * Return number of items in current array items
     *
     * @return int
     */
    function osc_count_items() {
        return osc_priv_count_items() ;
    }

    /**
     * Return number of resources in array resources of current item
     *
     * @return int
     */
    function osc_count_item_resources() {
        if ( !View::newInstance()->_exists('resources') ) {
            View::newInstance()->_exportVariableToView('resources', ItemResource::newInstance()->getAllResources( osc_item_id() ) ) ;
        }
        return osc_priv_count_item_resources() ;
    }

    /**
     * Return next item resource if there is, else return null
     *
     * @return array
     */
    function osc_has_item_resources() {
        if ( !View::newInstance()->_exists('resources') ) {
            View::newInstance()->_exportVariableToView('resources', ItemResource::newInstance()->getAllResources( osc_item_id() ) ) ;
        }
        return View::newInstance()->_next('resources') ;
    }

    /**
     * Return current resource of current array resources of current item
     *
     * @return array
     */
    function osc_get_item_resources() {
        if ( !View::newInstance()->_exists('resources') ) {
            View::newInstance()->_exportVariableToView('resources', ItemResource::newInstance()->getAllResources( osc_item_id() ) ) ;
        }
        return View::newInstance()->_get('resources') ;
    }

    /**
     * Return number of item comments of current item
     *
     * @return int
     */
    function osc_count_item_comments() {
        if ( !View::newInstance()->_exists('comments') ) {
            View::newInstance()->_exportVariableToView('comments', ItemComment::newInstance()->findByItemID( osc_item_id(), osc_item_comments_page(), osc_comments_per_page() ) ) ;
        }
        return View::newInstance()->_count('comments') ;
    }

    /**
     * Return next comment of current item comments
     *
     * @return array
     */
    function osc_has_item_comments() {
        if ( !View::newInstance()->_exists('comments') ) {
            View::newInstance()->_exportVariableToView('comments', ItemComment::newInstance()->findByItemID( osc_item_id(), osc_item_comments_page(), osc_comments_per_page() ) ) ;
        }
        return View::newInstance()->_next('comments') ;
    }

    //////////
    // HOME //
    //////////
    /**
     * Return next item of last items
     *
     * @return array
     */
    function osc_has_latest_items() {
        if ( !View::newInstance()->_exists('items') ) {
            View::newInstance()->_exportVariableToView('items', Item::newInstance()->listLatest( osc_max_latest_items() ) ) ;
        }
        return osc_has_items() ;
    }

    /**
     * Return number of latest items
     *
     * @return int
     */
    function osc_count_latest_items() {
        if ( !View::newInstance()->_exists('items') ) {
            View::newInstance()->_exportVariableToView('items', Item::newInstance()->listLatest( osc_max_latest_items() ) ) ;
        }
        return osc_priv_count_items() ;
    }
    //////////////
    // END HOME //
    //////////////


    /**
     * Formats the price using the appropiate currency.
     *
     * @param float $price
     * @return string
     */
    function osc_format_price($price) {
        if ($price == 0) return __('Check with seller') ;
        //if ($price == null) return __('Check with seller') ;
        //if ($price == 0) return __('Free') ;
        $currencyFormat =  osc_locale_currency_format();

        $currencyFormat = preg_replace('/%s/', 'CURRENCY', $currencyFormat) ;
        $currencyFormat = sprintf($currencyFormat, $price);
        $currencyFormat = preg_replace('/CURRENCY/', '%s', $currencyFormat) ;
        return sprintf($currencyFormat , osc_item_currency() ) ;
    }

    /**
     * Return number of items
     *
     * @access private
     * @return int
     */
    function osc_priv_count_items() {
        return (int) View::newInstance()->_count('items') ;
    }

    /**
     * Return number of item resources
     *
     * @access private
     * @return int
     */
    function osc_priv_count_item_resources() {
        return (int) View::newInstance()->_count('resources') ;
    }
?>
