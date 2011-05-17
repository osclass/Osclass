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
        if (Session::newInstance()->_get("userId") != '') {
            $user = User::newInstance()->findByPrimaryKey(Session::newInstance()->_get("userId"));
            if(isset($user['b_enabled']) && $user['b_enabled']==1) {
                return true ;
            } else {
                return false;
            }
        }

        //can already be a logged user or not, we'll take a look into the cookie
        if ( Cookie::newInstance()->get_value('oc_userId') != '' && Cookie::newInstance()->get_value('oc_userSecret') != '') {
            $user = User::newInstance()->findByIdSecret( Cookie::newInstance()->get_value('oc_userId'), Cookie::newInstance()->get_value('oc_userSecret') ) ;
            if(isset($user['b_enabled']) && $user['b_enabled']==1) {
                Session::newInstance()->_set('userId', $user['pk_i_id']) ;
                Session::newInstance()->_set('userName', $user['s_name']) ;
                Session::newInstance()->_set('userEmail', $user['s_email']) ;
                $phone = ($user['s_phone_mobile'])? $user['s_phone_mobile'] : $user['s_phone_land'];
                Session::newInstance()->_set('userPhone', $phone) ;
            
                return true ;
            } else {
                return false;
            }
        }

        return false ;
    }

    function osc_logged_user_id() {
        return Session::newInstance()->_get("userId") ;
    }

    function osc_logged_user_email() {
        return Session::newInstance()->_get('userEmail') ;
    }

    function osc_logged_user_name() {
        return Session::newInstance()->_get('userName') ;
    }

    function osc_logged_user_phone() {
        return Session::newInstance()->_get('userPhone') ;
    }

    function osc_is_admin_user_logged_in() {
        if (Session::newInstance()->_get("adminId") != '') {
            $admin = Admin::newInstance()->findByPrimaryKey( Session::newInstance()->_get("adminId") ) ;
            if(isset($admin['pk_i_id'])) {
                return true ;
            } else {
                return false;
            }
        }

        //can already be a logged user or not, we'll take a look into the cookie
        if ( Cookie::newInstance()->get_value('oc_adminId') != '' && Cookie::newInstance()->get_value('oc_adminSecret') != '') {
            $admin = Admin::newInstance()->findByIdSecret( Cookie::newInstance()->get_value('oc_adminId'), Cookie::newInstance()->get_value('oc_adminSecret') ) ;
            if(isset($admin['pk_i_id'])) {
                Session::newInstance()->_set('adminId', $admin['pk_i_id']) ;
                Session::newInstance()->_set('adminUserName', $admin['s_username']) ;
                Session::newInstance()->_set('adminName', $admin['s_name']) ;
                Session::newInstance()->_set('adminEmail', $admin['s_email']) ;
                Session::newInstance()->_set('adminLocale', Cookie::newInstance()->get_value('oc_adminLocale')) ;

                return true ;
            } else {
                return false;
            }
        }

        return false ;
    }

    function osc_logged_admin_id() {
        return Session::newInstance()->_get("adminId") ;
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
    
    function osc_user_items_validated() {
        return osc_user_field("i_items");
    }
    
    function osc_user_comments_validated() {
        return osc_user_field("i_comments");
    }
    
    /////////////
    // ALERTS  //
    /////////////
    function osc_alert_field($field) {
        return osc_field(View::newInstance()->_current('alerts'), $field, '') ;
    }

    function osc_has_alerts() {
        $result = View::newInstance()->_next('alerts') ;
        $alert = osc_alert();
        View::newInstance()->_exportVariableToView("items", isset($alert['items'])?$alert['items']:array());
        return $result;
    }

    function osc_count_alerts() {
        return View::newInstance()->_count('alerts') ;
    }
    
    function osc_alert() {
        return View::newInstance()->_current('alerts');
    }
    
    function osc_alert_search() {
        return osc_alert_field('s_search');
    }

    function osc_alert_secret() {
        return osc_alert_field('s_secret');
    }
    
    function osc_alert_search_object() {
        return osc_unserialize(base64_decode(osc_alert_field('s_search')));
    }
    
    
    function osc_prepare_user_info() {
        if ( !View::newInstance()->_exists('users') ) {
            View::newInstance()->_exportVariableToView('users', array ( User::newInstance()->findByPrimaryKey( osc_item_user_id() ) ) ) ;
        }
        return View::newInstance()->_next('users') ;
    }


?>
