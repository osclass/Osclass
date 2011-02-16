<?php

    function osc_is_web_user_logged_in() {
        if (Session::newInstance()->_get("userId") == '') return false ;
        return true ;
    }

    function osc_logged_user_email() {
        return Session::newInstance()->_get('userEmail') ;
    }

    function osc_logged_user_name() {
        return Session::newInstance()->_get('userName') ;
    }

    function osc_is_admin_user_logged_in() {
        if (Session::newInstance()->_get("adminId") == '') return false ;
        return true ;
    }

    function osc_logged_admin_username() {
        return Session::newInstance()->_get('adminUserName') ;
    }

    function osc_logged_admin_name() {
        return Session::newInstance()->_get('adminName') ;
    }

    function osc_logged_admin_email() {
        return Session::newInstance()->_get('adminEmail') ;
    }
    
    function osc_user_field($user = null, $field) {
        if($user!=null && isset($user[$field])) {
            return $user[$field];
        }
        return "";
    }

    function osc_user_name($user = null) {
        return osc_user_field($user, "s_name");
    }
    
    function osc_user_email($user = null) {
        return osc_user_field($user, "s_email");
    }
    
    function osc_user_username($user = null) {
        return osc_user_field($user, "s_username");
    }
    
    function osc_user_regdate($user = null) {
        return osc_user_field($user, "dt_reg_date");
    }
    
    function osc_user_id($user = null) {
        return osc_user_field($user, "pk_i_id");
    }
    
    function osc_user_website($user = null) {
        return osc_user_field($user, "s_website");
    }
    
    function osc_user_info($user = null) {
        return osc_user_field($user, "s_info");
    }
    
    function osc_user_phone_land($user = null) {
        return osc_user_field($user, "s_phone_land");
    }
    
    function osc_user_phone_moble($user = null) {
        return osc_user_field($user, "s_phone_mobile");
    }

    function osc_user_phone($user = null) {
        if(osc_user_field($user, "s_phone_land")!="") {
            return osc_user_field($user, "s_phone_land");
        } else if(osc_user_field($user, "s_phone_mobile")!="") {
            return osc_user_field($user, "s_phone_mobile");
        }
        return "";
    }
        
    function osc_user_country($user = null) {
        return osc_user_field($user, "s_country");
    }

    function osc_user_region($user = null) {
        return osc_user_field($user, "s_region");
    }

    function osc_user_city($user = null) {
        return osc_user_field($user, "s_city");
    }

    function osc_user_city_area($user = null) {
        return osc_user_field($user, "s_city_area");
    }

    function osc_user_address($user = null) {
        return osc_user_field($user, "s_address");
    }

    function osc_user_zip($user = null) {
        return osc_user_field($user, "s_zip");
    }

    function osc_user_latitude($user = null) {
        return osc_user_field($user, "d_coord_lat");
    }

    function osc_user_longitude($user = null) {
        return osc_user_field($user, "d_coord_long");
    }


    

?>
