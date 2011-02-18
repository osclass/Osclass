<?php

    function osc_user_field($field, $locale = "") {
        if (View::newInstance()->_exists('users')) {
            $user = View::newInstance()->_current('users') ;
        } else {
            $user = View::newInstance()->_get('user') ;
        }
        return osc_field($user, $field, $locale) ;
    }
    
    function osc_user() {
        if (View::newInstance()->_exists('users')) {
            $user = View::newInstance()->_current('users') ;
        } else {
            $user = View::newInstance()->_get('user') ;
        }

        return($user) ;
    }


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

    function osc_user_name() {
        return osc_user_field("s_name");
    }
    
    function osc_user_email() {
        return osc_user_field("s_email");
    }
    
    function osc_user_username() {
        return osc_user_field("s_username");
    }
    
    function osc_user_regdate() {
        return osc_user_field("dt_reg_date");
    }
    
    function osc_user_id() {
        return osc_user_field("pk_i_id");
    }
    
    function osc_user_website() {
        return osc_user_field("s_website");
    }
    
    function osc_user_info() {
        return osc_user_field("s_info");
    }
    
    function osc_user_phone_land() {
        return osc_user_field("s_phone_land");
    }
    
    function osc_user_phone_moble() {
        return osc_user_field("s_phone_mobile");
    }

    function osc_user_phone() {
        if(osc_user_field("s_phone_land")!="") {
            return osc_user_field("s_phone_land");
        } else if(osc_user_field("s_phone_mobile")!="") {
            return osc_user_field("s_phone_mobile");
        }
        return "";
    }
        
    function osc_user_country() {
        return osc_user_field("s_country");
    }

    function osc_user_region() {
        return osc_user_field("s_region");
    }

    function osc_user_city() {
        return osc_user_field("s_city");
    }

    function osc_user_city_area() {
        return osc_user_field("s_city_area");
    }

    function osc_user_address() {
        return osc_user_field("s_address");
    }

    function osc_user_zip() {
        return osc_user_field("s_zip");
    }

    function osc_user_latitude() {
        return osc_user_field("d_coord_lat");
    }

    function osc_user_longitude() {
        return osc_user_field("d_coord_long");
    }
    
    /////////////
    // ALERTS  //
    /////////////
    function osc_has_alerts() {
        $alert = View::newInstance()->_next('alerts') ;
        View::newInstance()->_exportVariableToView("items", isset($alert['items'])?$alert['items']:array());
        return $alert;
    }

    function osc_count_alerts() {
        return View::newInstance()->_count('alerts') ;
    }
    
    function osc_alert() {
        return View::newInstance()->_get('alerts');
    }
    
    function osc_prepare_user_info() {
        if ( !View::newInstance()->_exists('users') ) {
            View::newInstance()->_exportVariableToView('users', array ( User::newInstance()->findByPrimaryKey( osc_item_id() ) ) ) ;
        }
        return View::newInstance()->_next('users') ;
    }


?>