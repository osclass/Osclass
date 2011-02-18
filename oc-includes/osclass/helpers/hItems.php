<?php

    function osc_latest_items() {
        return (Item::newInstance()->listLatest( osc_max_latest_items() )) ;
    }

    function osc_field($item, $field, $locale) {
        if($item != null) {
            if($locale == "") {
                if(isset($item[$field])) {
                    return $item[$field] ;
                }
            } else {
                if(isset($item["locale"]) && isset($item["locale"][$locale]) && isset($item["locale"][$locale][$field])) {
                    return $item["locale"][$locale][$field] ;
                }
            }
            return $item ;
        }
        return "";
    }

    function osc_item() {
        if (View::newInstance()->_exists('items')) {
            $item = View::newInstance()->_current('items') ;
        } else {
            $item = View::newInstance()->_get('item') ;
        }

        return($item) ;
    }

    function osc_item_field($field, $locale = "") {
        if (View::newInstance()->_exists('items')) {
            $item = View::newInstance()->_current('items') ;
        } else {
            $item = View::newInstance()->_get('item') ;
        }
        return osc_field($item, $field, $locale) ;
    }

    function osc_category_field($field, $locale = '') {
        if (View::newInstance()->_exists('categories')) {
            $category = View::newInstance()->_current('categories') ;
        } else {
            $category = View::newInstance()->_get('category') ;
        }
        return osc_field($category, $field, $locale) ;
    }
    
    function osc_comment_field($field, $locale = '') {
        if (View::newInstance()->_exists('comments')) {
            $comment = View::newInstance()->_current('comments') ;
        } else {
            $comment = View::newInstance()->_get('comment') ;
        }
        return osc_field($comment, $field, $locale) ;
    }

    function osc_resource_field($field, $locale = '') {
        if (View::newInstance()->_exists('resources')) {
            $resource = View::newInstance()->_current('resources') ;
        } else {
            $resource = View::newInstance()->_get('resource') ;
        }
        return osc_field($resource, $field, $locale) ;
    }


    function osc_item_id() {
        return osc_item_field("pk_i_id");
    }

    function osc_item_description($locale = "") {
        if ($locale == "") $locale = osc_get_user_locale() ;
        return osc_item_field("s_description", $locale) ;
    }

    function osc_item_title($locale = "") {
        if ($locale == "") $locale = osc_get_user_locale() ;
        return osc_item_field("s_title");
    }

    function osc_item_category($locale = "") {
        if ($locale == "") $locale = osc_get_user_locale() ;
        $category = Category::newInstance()->findByPrimaryKey( osc_item_category_id() ) ;
        return osc_field($category, "s_name", $locale) ;
    }

    function osc_item_category_description($locale = "") {
        if ($locale == "") $locale = osc_get_user_locale() ;
        $category = Category::newInstance()->findByPrimaryKey( osc_item_category_id() ) ;
        return osc_field($category, "s_description", $locale) ;
    }

    function osc_item_pub_date() {
        return osc_item_field("dt_pub_date");
    }

    function osc_item_mod_date() {
        return osc_item_field("dt_mod_date");
    }

    function osc_item_price() {
        return osc_item_field("f_price");
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
    
    function osc_item_link_spam() {
        return osc_base_url(true) . "?page=item&action=mark&as=spam&id=" . osc_item_id() ;
    }

    function osc_item_link_bad_category() {
        return osc_base_url(true) . "?page=item&action=mark&as=badcat&id=" . osc_item_id() ;
    }

    function osc_item_link_repeated() {
        return osc_base_url(true) . "?page=item&action=mark&as=repeated&id=" . osc_item_id() ;
    }

    function osc_item_link_offensive() {
        return osc_base_url(true) ."?page=item&action=mark&as=offensive&id=" . osc_item_id() ;
    }

    function osc_item_link_expired() {
        return osc_base_url(true) . "?page=item&action=mark&as=expired&id=" . osc_item_id() ;
    }
    
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
    
    //using View Class (the static class for the view layer)
    function has_items() {
        return View::newInstance()->_next('items') ;
    }

    function has_item_resources() {
        return View::newInstance()->_next('resources') ;
    }

    function has_item_comments() {
        return View::newInstance()->_next('comments') ;
    }

    function count_items() {
        return View::newInstance()->_count('items') ;
    }

    function count_item_resources() {
        return View::newInstance()->_count('resources') ;
    }

    function count_item_comments() {
        return View::newInstance()->_count('comments') ;
    }

    function resource_url() {
        if (View::newInstance()->_exists('resources')) {
            $resource = View::newInstance()->_get('resources') ;
        } else {
            $resource = View::newInstance()->_get('resource') ;
        }
        return ( osc_resource_url($resource) ) ;
    }

    function osc_item_category_id() {
        return osc_item_field("fk_i_category_id") ;
    }

    /**
     * Formats the price using the appropiate currency.
     */
    //osc_formatPrice
    function osc_format_price() {
        if (View::newInstance()->_exists('items')) {
            $item = View::newInstance()->_get('items') ;
        } else {
            $item = View::newInstance()->_get('item') ;
        }

        if (!isset($item['f_price']))
            return __('Check with seller') ;

        if ($item['f_price'] == 0)
            return __('Free') ;

        if (!empty($item['f_price']))
            return sprintf('%.02f %s', $item['f_price'], $item['fk_c_currency_code']) ;

        return __('Check with seller') ;
    }


?>
