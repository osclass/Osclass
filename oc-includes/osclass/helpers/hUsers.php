<?php
    /**
     * Helper Users
     * @package OSClass
     * @subpackage Helpers
     * @author OSClass
     */

    /**
     * Return a specific field from current user
     *
     * @param <type> $field
     * @param <type> $locale
     * @return <type>
     */
    function osc_user_field($field, $locale = "") {
        if (View::newInstance()->_exists('users')) {
            $user = View::newInstance()->_current('users') ;
        } else {
            $user = View::newInstance()->_get('user') ;
        }
        return osc_field($user, $field, $locale) ;
    }

    /**
     * Return user array from view
     *
     * @return <type>
     */
    function osc_user() {
        if (View::newInstance()->_exists('users')) {
            $user = View::newInstance()->_current('users') ;
        } else {
            $user = View::newInstance()->_get('user') ;
        }

        return($user) ;
    }

    /**
     * Return true if user is logged in web
     *
     * @return boolean
     */
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

    /**
     * Return logged user id
     *
     * @return int
     */
    function osc_logged_user_id() {
        return (int) Session::newInstance()->_get("userId") ;
    }

    /**
     * Return logged user mail
     *
     * @return string
     */
    function osc_logged_user_email() {
        return (string) Session::newInstance()->_get('userEmail') ;
    }

    /**
     * Return logged user email
     *
     * @return string
     */
    function osc_logged_user_name() {
        return (string) Session::newInstance()->_get('userName') ;
    }

    /**
     * Return logged user phone
     *
     * @return string
     */
    function osc_logged_user_phone() {
        return (string) Session::newInstance()->_get('userPhone') ;
    }

    /**
     * Return true if admin user is logged in
     *
     * @return boolean
     */
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

    /**
     * Return logged admin id
     *
     * @return int
     */
    function osc_logged_admin_id() {
        return (int) Session::newInstance()->_get("adminId") ;
    }

    /**
     * Return logged admin username
     *
     * @return string
     */
    function osc_logged_admin_username() {
        return (string) Session::newInstance()->_get('adminUserName') ;
    }

    /**
     * Return logged admin name
     * @return string
     */
    function osc_logged_admin_name() {
        return (string) Session::newInstance()->_get('adminName') ;
    }

    /**
     * Return logged admin email
     *
     * @return string
     */
    function osc_logged_admin_email() {
        return (string) Session::newInstance()->_get('adminEmail') ;
    }

    /**
     * Return name of current user
     *
     * @return string
     */
    function osc_user_name() {
        return (string) osc_user_field("s_name");
    }

    /**
     * Return email of current user
     *
     * @return string
     */
    function osc_user_email() {
        return (string) osc_user_field("s_email");
    }

    /**
     * Return registration date of current user
     *
     * @return string
     */
    function osc_user_regdate() {
        return (string) osc_user_field("dt_reg_date");
    }

    /**
     * Return id of current user
     *
     * @return int
     */
    function osc_user_id() {
        return (int) osc_user_field("pk_i_id");
    }

    /**
     * Return website of current user
     *
     * @return string
     */
    function osc_user_website() {
        return (string) osc_user_field("s_website");
    }

    /**
     * Return description/information of current user
     *
     * @return string
     */
    function osc_user_info() {
        return (string) osc_user_field("s_info");
    }

    /**
     * Return phone of current user
     *
     * @return string
     */
    function osc_user_phone_land() {
        return (string) osc_user_field("s_phone_land");
    }

    /**
     * Return cell phone of current user
     *
     * @return string
     */
    function osc_user_phone_moble() {
        return (string) osc_user_field("s_phone_mobile");
    }

    /**
     * Return phone_land if exist, else if exist return phone_mobile,
     * else return string blank
     * @return string
     */
    function osc_user_phone() {
        if(osc_user_field("s_phone_land")!="") {
            return osc_user_field("s_phone_land");
        } else if(osc_user_field("s_phone_mobile")!="") {
            return osc_user_field("s_phone_mobile");
        }
        return "";
    }

    /**
     * Return country of current user
     *
     * @return string
     */
    function osc_user_country() {
        return (string) osc_user_field("s_country");
    }

    /**
     * Return region of current user
     *
     * @return string
     */
    function osc_user_region() {
        return (string) osc_user_field("s_region");
    }

    /**
     * Return city of current user
     *
     * @return string
     */
    function osc_user_city() {
        return (string) osc_user_field("s_city");
    }

    /**
     * Return city area of current user
     *
     * @return string
     */
    function osc_user_city_area() {
        return (string) osc_user_field("s_city_area");
    }

    /**
     * Return address of current user
     *
     * @return address
     */
    function osc_user_address() {
        return (string) osc_user_field("s_address");
    }

    /**
     * Return postal zip of current user
     *
     * @return string
     */
    function osc_user_zip() {
        return (string) osc_user_field("s_zip");
    }

    /**
     * Return latitude of current user
     *
     * @return float
     */
    function osc_user_latitude() {
        return (float) osc_user_field("d_coord_lat");
    }

    /**
     * Return longitude of current user
     *
     * @return float
     */
    function osc_user_longitude() {
        return (float) osc_user_field("d_coord_long");
    }

    /**
     * Return number of items validated of current user
     *
     * @return int
     */
    function osc_user_items_validated() {
        return (int) osc_user_field("i_items");
    }

    /**
     * Return number of comments validated of current user
     *
     * @return int
     */
    function osc_user_comments_validated() {
        return osc_user_field("i_comments");
    }
    
    /////////////
    // ALERTS  //
    /////////////

    /**
     * Return a specific field from current alert
     *
     * @param array $field
     * @return <type>
     */
    function osc_alert_field($field) {
        return osc_field(View::newInstance()->_current('alerts'), $field, '') ;
    }

    /**
     * Return next alert if there is, else return null
     *
     * @return array
     */
    function osc_has_alerts() {
        $result = View::newInstance()->_next('alerts') ;
        $alert = osc_alert();
        View::newInstance()->_exportVariableToView("items", isset($alert['items'])?$alert['items']:array());
        return $result;
    }

    /**
     * Return number of alerts in array alerts
     * @return int
     */
    function osc_count_alerts() {
        return (int) View::newInstance()->_count('alerts') ;
    }

    /**
     * Return current alert fomr view
     *
     * @return array
     */
    function osc_alert() {
        return View::newInstance()->_current('alerts');
    }

    /**
     * Return search field of current alert
     *
     * @return string
     */
    function osc_alert_search() {
        return (string) osc_alert_field('s_search');
    }

    /**
     * Return secret of current alert
     * @return string
     */
    function osc_alert_secret() {
        return (string) osc_alert_field('s_secret');
    }

    /**
     * Return the search object of a specific alert
     *
     * @return Search
     */
    function osc_alert_search_object() {
        return osc_unserialize(base64_decode(osc_alert_field('s_search')));
    }
    
    /**
     * Return next user in users array
     * 
     * @return <type>
     */
    function osc_prepare_user_info() {
        if ( !View::newInstance()->_exists('users') ) {
            View::newInstance()->_exportVariableToView('users', array ( User::newInstance()->findByPrimaryKey( osc_item_user_id() ) ) ) ;
        }
        return View::newInstance()->_next('users') ;
    }


?>
