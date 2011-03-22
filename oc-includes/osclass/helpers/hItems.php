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


    ////////////////////////////////////////////////////////////////
    // FUNCTIONS THAT RETURNS OBJECT FROM THE STATIC CLASS (VIEW) //
    ////////////////////////////////////////////////////////////////
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

    function osc_comment() {
        if (View::newInstance()->_exists('comments')) {
            $comment = View::newInstance()->_current('comments') ;
        } else {
            $comment = View::newInstance()->_get('comment') ;
        }

        return($comment) ;
    }

    function osc_resource() {
        if (View::newInstance()->_exists('resources')) {
            $resource = View::newInstance()->_current('resources') ;
        } else {
            $resource = View::newInstance()->_get('resource') ;
        }

        return($resource) ;
    }


    function osc_item_field($field, $locale = "") {
        return osc_field(osc_item(), $field, $locale) ;
    }

    function osc_comment_field($field, $locale = '') {
        return osc_field(osc_comment(), $field, $locale) ;
    }

    function osc_resource_field($field, $locale = '') {
        return osc_field(osc_resource(), $field, $locale) ;
    }
    /////////////////////////////////////////////////
    // END FUNCTIONS THAT RETURNS OBJECT FROM VIEW //
    /////////////////////////////////////////////////


    ///////////////////////
    // HELPERS FOR ITEMS //
    ///////////////////////

    function osc_item_id() {
        return osc_item_field("pk_i_id");
    }

    function osc_item_user_id() {
        return osc_item_field("fk_i_user_id") ;
    }

    function osc_item_description($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale() ;
        return osc_item_field("s_description", $locale) ;
    }

    function osc_item_title($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale() ;
        return osc_item_field("s_title");
    }

    function osc_item_category($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale() ;
        $category = Category::newInstance()->findByPrimaryKey( osc_item_category_id() ) ;
        return osc_field($category, "s_name", $locale) ;
    }

    function osc_item_category_description($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale() ;
        $category = Category::newInstance()->findByPrimaryKey( osc_item_category_id() ) ;
        return osc_field($category, "s_description", $locale) ;
    }

    function osc_item_category_id() {
        return osc_item_field("fk_i_category_id") ;
    }

    function osc_item_pub_date() {
        return osc_item_field("dt_pub_date");
    }

    function osc_item_mod_date() {
        return osc_item_field("dt_mod_date");
    }

    function osc_item_price() {
        return osc_item_field("f_price") ;
    }

    function osc_item_formated_price() {
        return osc_format_price( osc_item_field("f_price") ) ;
    }

    function osc_item_currency() {
        return osc_item_field("fk_c_currency_code");
    }

    function osc_item_contact_name() {
        return osc_item_field("s_contact_name");
    }

    function osc_item_contact_email() {
        return osc_item_field("s_contact_email");
    }

    function osc_item_country() {
        return osc_item_field("s_country");
    }

    function osc_item_region() {
        return osc_item_field("s_region");
    }

    function osc_item_city() {
        return osc_item_field("s_city");
    }

    function osc_item_city_area() {
        return osc_item_field("s_city_area");
    }

    function osc_item_address() {
        return osc_item_field("s_address");
    }

    function osc_item_zip() {
        return osc_item_field("s_zip");
    }

    function osc_item_latitude() {
        return osc_item_field("d_coord_lat");
    }

    function osc_item_longitude() {
        return osc_item_field("d_coord_long");
    }

    function osc_item_is_premium() {
        if ( osc_item_field("b_premium") ) return true ;
        else return false ;
    }
    
    function osc_item_status() {
        return osc_item_field("e_status");
    }

    function osc_item_secret() {
        return osc_item_field("s_secret");
    }
    
    function osc_item_is_active() {
        return (osc_item_field("e_status")=="ACTIVE");
    }
    
    function osc_item_is_inactive() {
        return (osc_item_field("e_status")=="INACTIVE");
    }
    
    function osc_item_is_spam() {
        return (osc_item_field("e_status")=="SPAM");
    }
    
    function osc_item_link_spam() {
        if(!osc_rewrite_enabled ()) {
            $url = osc_base_url(true) . "?page=item&action=mark&as=spam&id=" . osc_item_id() ;
        } else {
            $url = osc_base_url() . "item/mark/spam/" . osc_item_id() ;
        }

        return $url;
    }

    function osc_item_link_bad_category() {
        if(!osc_rewrite_enabled ()) {
            $url = osc_base_url(true) . "?page=item&action=mark&as=badcat&id=" . osc_item_id() ;
        } else {
            $url = osc_base_url() . "item/mark/badcat/" . osc_item_id() ;
        }

        return $url;
    }

    function osc_item_link_repeated() {
        if(!osc_rewrite_enabled ()) {
            $url = osc_base_url(true) . "?page=item&action=mark&as=repeated&id=" . osc_item_id() ;
        } else {
            $url = osc_base_url() . "item/mark/repeated/" . osc_item_id() ;
        }

        return $url;
    }

    function osc_item_link_offensive() {
        if(!osc_rewrite_enabled ()) {
            $url = osc_base_url(true) . "?page=item&action=mark&as=offensive&id=" . osc_item_id() ;
        } else {
            $url = osc_base_url() . "item/mark/offensive/" . osc_item_id() ;
        }

        return $url;
    }

    function osc_item_link_expired() {
        if(!osc_rewrite_enabled ()) {
            $url = osc_base_url(true) . "?page=item&action=mark&as=expired&id=" . osc_item_id() ;
        } else {
            $url = osc_base_url() . "item/mark/expired/" . osc_item_id() ;
        }

        return $url;
    }

    function osc_list_page() {
        return View::newInstance()->_get('list_page');
    }
    
    function osc_list_total_pages() {
        return View::newInstance()->_get('list_total_pages');
    }

    function osc_list_items_per_page() {
        return View::newInstance()->_get('items_per_page');
    }    
    
    ///////////////////////
    // HELPERS FOR ITEMS //
    ///////////////////////
    

    //////////////////////////
    // HELPERS FOR COMMENTS //
    //////////////////////////
    function osc_comment_id() {
        return osc_comment_field("pk_i_id");
    }

    function osc_comment_pub_date() {
        return osc_comment_field("dt_pub_date");
    }

    function osc_comment_title() {
        return osc_comment_field("s_title");
    }

    function osc_comment_author_name() {
        return osc_comment_field("s_author_name");
    }

    function osc_comment_author_email() {
        return osc_comment_field("s_author_email");
    }

    function osc_comment_body() {
        return osc_comment_field("s_body");
    }

    function osc_comment_user_id() {
        return osc_comment_field("fk_i_user_id");
    }
    //////////////////////////////
    // END HELPERS FOR COMMENTS //
    //////////////////////////////

    ///////////////////////////
    // HELPERS FOR RESOURCES //
    ///////////////////////////
    function osc_resource_id() {
        return osc_resource_field("pk_i_id");
    }

    function osc_resource_name() {
        return osc_resource_field("s_name");
    }

    function osc_resource_type() {
        return osc_resource_field("s_content_type");
    }

    function osc_resource_extension() {
        return osc_resource_field("s_extension");
    }

    function osc_resource_path() {
        return osc_base_url().osc_resource_field("s_path");
    }

    function osc_resource_thumbnail_url() {
        return osc_base_url().osc_resource_field("s_path").osc_resource_field("s_name")."_thumbnail.".osc_resource_field("s_extension");
    }

    function osc_resource_url() {
        return osc_base_url().osc_resource_field("s_path").osc_resource_field("s_name").".".osc_resource_field("s_extension");
    }

    function osc_resource_original_url() {
        return osc_base_url().osc_resource_field("s_path").osc_resource_field("s_name")."_original.".osc_resource_field("s_extension");
    }
    ///////////////////////////////
    // END HELPERS FOR RESOURCES //
    ///////////////////////////////
    
    /////////////
    // DETAILS //
    /////////////
    function osc_has_items() {
        if ( View::newInstance()->_exists('resources') ) {
            View::newInstance()->_erase('resources') ;
        }
        return View::newInstance()->_next('items') ;
    }

    function osc_count_items() {
        return osc_priv_count_items() ;
    }

    function osc_count_item_resources() {
        if ( !View::newInstance()->_exists('resources') ) {
            View::newInstance()->_exportVariableToView('resources', ItemResource::newInstance()->getAllResources( osc_item_id() ) ) ;
        }
        return osc_priv_count_item_resources() ;
    }

    function osc_has_item_resources() {
        if ( !View::newInstance()->_exists('resources') ) {
            View::newInstance()->_exportVariableToView('resources', ItemResource::newInstance()->getAllResources( osc_item_id() ) ) ;
        }
        return View::newInstance()->_next('resources') ;
    }

    function osc_get_item_resources() {
        if ( !View::newInstance()->_exists('resources') ) {
            View::newInstance()->_exportVariableToView('resources', ItemResource::newInstance()->getAllResources( osc_item_id() ) ) ;
        }
        return View::newInstance()->_get('resources') ;
    }

    function osc_count_item_comments() {
        if ( !View::newInstance()->_exists('comments') ) {
            View::newInstance()->_exportVariableToView('comments', ItemComment::newInstance()->findByItemID( osc_item_id() ) ) ;
        }
        return View::newInstance()->_count('comments') ;
    }

    function osc_has_item_comments() {
        if ( !View::newInstance()->_exists('comments') ) {
            View::newInstance()->_exportVariableToView('comments', Item::newInstance()->listLatest( osc_item_id() ) ) ;
        }
        return View::newInstance()->_next('comments') ;
    }

    //////////
    // HOME //
    //////////
    function osc_has_latest_items() {
        if ( !View::newInstance()->_exists('items') ) {
            View::newInstance()->_exportVariableToView('items', Item::newInstance()->listLatest( osc_max_latest_items() ) ) ;
        }
        return osc_has_items() ;
    }

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
     */
    //osc_formatPrice
    function osc_format_price($price) {
        if ($price == null) return __('Check with seller') ;
        if ($price == 0) return __('Free') ;
        return sprintf('%.02f %s', $price, osc_item_currency() ) ;
    }






    //PRIVATE
    function osc_priv_count_items() {
        return View::newInstance()->_count('items') ;
    }

    function osc_priv_count_item_resources() {
        return View::newInstance()->_count('resources') ;
    }


?>
