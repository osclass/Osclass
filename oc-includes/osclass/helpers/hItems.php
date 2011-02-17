<?php

    function osc_latest_items() {
        return (Item::newInstance()->listLatest( osc_max_latest_items() )) ;
    }

    function osc_item_field($item = null, $field, $locale = "") {
        if($item!=null) {
            if($locale=="") {
                if(isset($item[$field])) {
                    return $item[$field];
                }
            } else {
                if(isset($item["locale"]) && isset($item["locale"][$locale]) && isset($item["locale"][$locale][$field])) {
                    return $item["locale"][$locale][$field];
                }
            }
        }
        return "";
    }

    function osc_item_id($item = null) {
        return osc_item_field($item, "pk_i_id");
    }

    function osc_item_description($item = null, $locale = "") {
        return osc_item_field($item, "s_description", $locale);
    }

    function osc_item_title($item = null, $locale = "") {
        return osc_item_field($item, "s_title");
    }

    function osc_item_category_id($item = null) {
        return osc_item_field($item, "fk_i_category_id");
    }
    
    function osc_item_category($item = null, $locale = "") {
        $category = Category::newInstance()->findByPrimaryKey(osc_item_category_id($item));
        return osc_item_field($category, "s_name", $locale);
    }

    function osc_item_category_description($item = null, $locale = "") {
        $category = Category::newInstance()->findByPrimaryKey(osc_item_category_id($item));
        return osc_item_field($category, "s_description", $locale);
    }

    function osc_item_pub_date($item = null) {
        return osc_item_field($item, "dt_pub_date");
    }

    function osc_item_mod_date($item = null) {
        return osc_item_field($item, "dt_mod_date");
    }

    function osc_item_price($item = null) {
        return osc_item_field($item, "f_price");
    }

    function osc_item_currency($item = null) {
        return osc_item_field($item, "fk_c_currency_code");
    }

    function osc_item_contact_name($item = null) {
        return osc_item_field($item, "s_contact_name");
    }

    function osc_item_contact_email($item = null) {
        return osc_item_field($item, "s_contact_email");
    }

    function osc_item_country($item = null) {
        return osc_item_field($item, "s_country");
    }

    function osc_item_region($item = null) {
        return osc_item_field($item, "s_region");
    }

    function osc_item_city($item = null) {
        return osc_item_field($item, "s_city");
    }

    function osc_item_city_area($item = null) {
        return osc_item_field($item, "s_city_area");
    }

    function osc_item_address($item = null) {
        return osc_item_field($item, "s_address");
    }

    function osc_item_zip($item = null) {
        return osc_item_field($item, "s_zip");
    }

    function osc_item_latitude($item = null) {
        return osc_item_field($item, "d_coord_lat");
    }

    function osc_item_longitude($item = null) {
        return osc_item_field($item, "d_coord_long");
    }

    function osc_item_is_premium($item = null) {
        if($item!=null && isset($item["b_premium"]) && $item["b_premium"]==1) {
            return true;
        } else { 
            return false;
        }
    }
    
    function osc_item_link_spam($item = null) {
        return osc_base_url(true)."?page=item&action=mark&as=spam&id=".osc_item_id($item);
    }

    function osc_item_link_bad_category($item = null) {
        return osc_base_url(true)."?page=item&action=mark&as=badcat&id=".osc_item_id($item);
    }

    function osc_item_link_repeated($item = null) {
        return osc_base_url(true)."?page=item&action=mark&as=repeated&id=".osc_item_id($item);
    }

    function osc_item_link_offensive($item = null) {
        return osc_base_url(true)."?page=item&action=mark&as=offensive&id=".osc_item_id($item);
    }

    function osc_item_link_expired($item = null) {
        return osc_base_url(true)."?page=item&action=mark&as=expired&id=".osc_item_id($item);
    }
    
    function osc_comment_id($comment = null) {
        return osc_item_field($comment, "pk_i_id");    
    }

    function osc_comment_pub_date($comment = null) {
        return osc_item_field($comment, "dt_pub_date");
    }

    function osc_comment_title($comment = null) {
        return osc_item_field($comment, "s_title");
    }

    function osc_comment_author_name($comment = null) {
        return osc_item_field($comment, "s_author_name");
    }

    function osc_comment_author_email($comment = null) {
        return osc_item_field($comment, "s_author_email");
    }

    function osc_comment_body($comment = null) {
        return osc_item_field($comment, "s_body");
    }

    function osc_comment_user_id($comment = null) {
        return osc_item_field($comment, "fk_i_user_id");
    }
    
    function osc_resource_id($resource = null) {
        return osc_item_field($resource, "pk_i_id");
    }

    function osc_resource_name($resource = null) {
        return osc_item_field($resource, "s_name");
    }

    function osc_resource_type($resource = null) {
        return osc_item_field($resource, "s_content_type");
    }

    function osc_resource_extension($resource = null) {
        return osc_item_field($resource, "s_extension");
    }

    function osc_resource_path($resource = null) {
        return osc_base_url().osc_item_field($resource, "s_path");
    }

    function osc_resource_thumbnail($resource = null) {
        return osc_base_url().osc_item_field($resource, "s_path").osc_item_field($resource, "s_name")."_thumbnail.".osc_item_field($resource, "s_extension");
    }

    function osc_resource($resource = null) {
        return osc_base_url().osc_item_field($resource, "s_path").osc_item_field($resource, "s_name").".".osc_item_field($resource, "s_extension");
    }

    function osc_resource_normal($resource = null) {
        return osc_base_url().osc_item_field($resource, "s_path").osc_item_field($resource, "s_name").".".osc_item_field($resource, "s_extension");
    }

    function osc_resource_original($resource = null) {
        return osc_base_url().osc_item_field($resource, "s_path").osc_item_field($resource, "s_name")."_original.".osc_item_field($resource, "s_extension");
    }

?>
