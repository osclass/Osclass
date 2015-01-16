<?php
    /**
     * Helper Users
     * @package Osclass
     * @subpackage Helpers
     * @author Osclass
     */

    /**
     * Gets a specific field from current user
     *
     * @param string $field
     * @param string $locale
     * @return mixed
     */
    function osc_user_field($field, $locale = "") {
        if (View::newInstance()->_exists('users')) {
            $user = View::newInstance()->_current('users');
        } else {
            $user = View::newInstance()->_get('user');
        }
        return osc_field($user, $field, $locale);
    }

    /**
     * Gets user array from view
     *
     * @return array
     */
    function osc_user() {
        if (View::newInstance()->_exists('users')) {
            $user = View::newInstance()->_current('users');
        } else {
            $user = View::newInstance()->_get('user');
        }

        return($user);
    }

    /**
     * Gets true if user is logged in web
     *
     * @return boolean
     */
    function osc_is_web_user_logged_in() {
        if(View::newInstance()->_exists('_loggedUser')) {
            $user = View::newInstance()->_get('_loggedUser');
            if(isset($user['b_enabled']) && $user['b_enabled']==1) {
                return true;
            } else {
                return false;
            }
        }

        if (Session::newInstance()->_get("userId") != '') {
            $user = User::newInstance()->findByPrimaryKey(Session::newInstance()->_get("userId"));
            View::newInstance()->_exportVariableToView('_loggedUser', $user);
            if(isset($user['b_enabled']) && $user['b_enabled']==1) {
                return true;
            } else {
                return false;
            }
        }

        //can already be a logged user or not, we'll take a look into the cookie
        if ( Cookie::newInstance()->get_value('oc_userId') != '' && Cookie::newInstance()->get_value('oc_userSecret') != '') {
            $user = User::newInstance()->findByIdSecret( Cookie::newInstance()->get_value('oc_userId'), Cookie::newInstance()->get_value('oc_userSecret') );
            View::newInstance()->_exportVariableToView('_loggedUser', $user);
            if(isset($user['b_enabled']) && $user['b_enabled']==1) {
                Session::newInstance()->_set('userId', $user['pk_i_id']);
                Session::newInstance()->_set('userName', $user['s_name']);
                Session::newInstance()->_set('userEmail', $user['s_email']);
                $phone = ($user['s_phone_mobile'])? $user['s_phone_mobile'] : $user['s_phone_land'];
                Session::newInstance()->_set('userPhone', $phone);

                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    /**
     * Gets logged user id
     *
     * @return int
     */
    function osc_logged_user_id() {
        return (int) Session::newInstance()->_get("userId");
    }

    /**
     * Gets logged user mail
     *
     * @return string
     */
    function osc_logged_user_email() {
        return (string) Session::newInstance()->_get('userEmail');
    }

    /**
     * Gets logged user name
     *
     * @return string
     */
    function osc_logged_user_name() {
        return (string) Session::newInstance()->_get('userName');
    }

    /**
     * Gets logged user phone
     *
     * @return string
     */
    function osc_logged_user_phone() {
        return (string) Session::newInstance()->_get('userPhone');
    }

    /**
     * Gets user's profile url
     *
     * @return string
     */
    function osc_user_public_profile_url($id = null) {
        if($id==null) {
            $id = osc_user_id();
        }
        if ($id != '') {
            if ( osc_rewrite_enabled() ) {
                $user = User::newInstance()->findByPrimaryKey($id);
                $path = osc_base_url().osc_get_preference('rewrite_user_profile')."/".$user['s_username'];
            } else {
                $path = sprintf(osc_base_url(true) . '?page=user&action=pub_profile&id=%d', $id);
            }
        } else {
            $path = '';
        }
        return $path;
    }

    /**
     * Gets current items page from public profile
     *
     * @param int $page
     * @return string
     */
    function osc_user_list_items_pub_profile_url($page = '', $itemsPerPage = false) {
        $path  = osc_user_public_profile_url();
        if ($itemsPerPage) {
            $path .= "?itemsPerPage=" . $itemsPerPage;
        }
        if($page) {
            if(!$itemsPerPage) {
                $path .= "?iPage=" . $page;
            } else {
                $path .= "&iPage=" . $page;
            }
        }

        return $path;
    }

    /**
     * Gets true if admin user is logged in
     *
     * @return boolean
     */
    function osc_is_admin_user_logged_in() {
        if (Session::newInstance()->_get("adminId") != '') {
            $admin = Admin::newInstance()->findByPrimaryKey( Session::newInstance()->_get("adminId") );
            if(isset($admin['pk_i_id'])) {
                return true;
            } else {
                return false;
            }
        }

        //can already be a logged user or not, we'll take a look into the cookie
        if ( Cookie::newInstance()->get_value('oc_adminId') != '' && Cookie::newInstance()->get_value('oc_adminSecret') != '') {
            $admin = Admin::newInstance()->findByIdSecret( Cookie::newInstance()->get_value('oc_adminId'), Cookie::newInstance()->get_value('oc_adminSecret') );
            if(isset($admin['pk_i_id'])) {
                Session::newInstance()->_set('adminId', $admin['pk_i_id']);
                Session::newInstance()->_set('adminUserName', $admin['s_username']);
                Session::newInstance()->_set('adminName', $admin['s_name']);
                Session::newInstance()->_set('adminEmail', $admin['s_email']);
                Session::newInstance()->_set('adminLocale', Cookie::newInstance()->get_value('oc_adminLocale'));

                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    /**
     * Gets logged admin id
     *
     * @return int
     */
    function osc_logged_admin_id() {
        return (int) Session::newInstance()->_get("adminId");
    }

    /**
     * Gets logged admin username
     *
     * @return string
     */
    function osc_logged_admin_username() {
        return (string) Session::newInstance()->_get('adminUserName');
    }

    /**
     * Gets logged admin name
     * @return string
     */
    function osc_logged_admin_name() {
        return (string) Session::newInstance()->_get('adminName');
    }

    /**
     * Gets logged admin email
     *
     * @return string
     */
    function osc_logged_admin_email() {
        return (string) Session::newInstance()->_get('adminEmail');
    }

    /**
     * Gets name of current user
     *
     * @return string
     */
    function osc_user_name() {
        return (string) osc_user_field("s_name");
    }

    /**
     * Gets email of current user
     *
     * @return string
     */
    function osc_user_email() {
        return (string) osc_user_field("s_email");
    }

    /**
     * Gets username of current user
     *
     * @return string
     */
    function osc_user_username() {
        return (string) osc_user_field("s_username");
    }

    /**
     * Gets registration date of current user
     *
     * @return string
     */
    function osc_user_regdate() {
        return (string) osc_user_field("dt_reg_date");
    }

    /**
     * Gets id of current user
     *
     * @return int
     */
    function osc_user_id() {
        return (int) osc_user_field("pk_i_id");
    }

    /**
     * Gets last access date
     *
     * @return string
     */
    function osc_user_access_date() {
        return (int) osc_user_field("dt_access_date");
    }

    /**
     * Gets last access ip
     *
     * @return string
     */
    function osc_user_access_ip() {
        return (int) osc_user_field("s_access_ip");
    }

    /**
     * Gets website of current user
     *
     * @return string
     */
    function osc_user_website() {
        return (string) osc_user_field("s_website");
    }

    /**
     * Gets description/information of current user
     *
     * @return string
     */
    function osc_user_info($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale();
        $info = osc_user_field("s_info", $locale);
        if($info == '') {
            $info = osc_user_field("s_info", osc_language());
            if($info=='') {
                $aLocales = osc_get_locales();
                foreach($aLocales as $locale) {
                    $info = osc_user_field("s_info", $locale['pk_c_code']);
                    if($info!='') {
                        break;
                    }
                }
            }
        }
        return (string) $info;
    }

    /**
     * Gets phone of current user
     *
     * @return string
     */
    function osc_user_phone_land() {
        return (string) osc_user_field("s_phone_land");
    }

    /**
     * Gets cell phone of current user
     *
     * @return string
     */
    function osc_user_phone_mobile() {
        return (string) osc_user_field("s_phone_mobile");
    }

    /**
     * Gets phone_land if exist, else if exist return phone_mobile,
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
     * Gets country of current user
     *
     * @return string
     */
    function osc_user_country() {
        return (string) osc_user_field("s_country");
    }

    /**
     * Gets region of current user
     *
     * @return string
     */
    function osc_user_region() {
        return (string) osc_user_field("s_region");
    }

    /**
     * Gets region id of current user
     *
     * @return string
     */
    function osc_user_region_id() {
        return (string) osc_user_field("fk_i_region_id");
    }

    /**
     * Gets city of current user
     *
     * @return string
     */
    function osc_user_city() {
        return (string) osc_user_field("s_city");
    }

    /**
     * Gets city id of current user
     *
     * @return string
     */
    function osc_user_city_id() {
        return (string) osc_user_field("fk_i_city_id");
    }

    /**
     * Gets city area of current user
     *
     * @return string
     */
    function osc_user_city_area() {
        return (string) osc_user_field("s_city_area");
    }

    /**
     * Gets city area id of current user
     *
     * @return string
     */
    function osc_user_city_area_id() {
        return (string) osc_user_field("fk_i_city_area_id");
    }

    /**
     * Gets address of current user
     *
     * @return address
     */
    function osc_user_address() {
        return (string) osc_user_field("s_address");
    }

    /**
     * Gets postal zip of current user
     *
     * @return string
     */
    function osc_user_zip() {
        return (string) osc_user_field("s_zip");
    }

    /**
     * Gets latitude of current user
     *
     * @return float
     */
    function osc_user_latitude() {
        return (float) osc_user_field("d_coord_lat");
    }

    /**
     * Gets longitude of current user
     *
     * @return float
     */
    function osc_user_longitude() {
        return (float) osc_user_field("d_coord_long");
    }

    /**
     * Gets type (company/user) of current user
     *
     * @return float
     */
    function osc_user_is_company() {
        return (bool) osc_user_field("b_company");
    }

    /**
     * Gets number of items validated of current user
     *
     * @return int
     */
    function osc_user_items_validated() {
        return (int) osc_user_field("i_items");
    }

    /**
     * Gets number of comments validated of current user
     *
     * @return int
     */
    function osc_user_comments_validated() {
        return osc_user_field("i_comments");
    }

    /**
     * Gets number of users
     *
     * @return int
     */
    function osc_total_users($condition = '') {
        switch($condition) {
            case 'active':
                return User::newInstance()->countUsers('b_active = 1');
                break;
            case 'enabled':
                return User::newInstance()->countUsers('b_enabled = 1');
                break;
            default:
                return User::newInstance()->countUsers();
                break;
        }
    }
    /////////////
    // ALERTS  //
    /////////////

    /**
     * Gets a specific field from current alert
     *
     * @param array $field
     * @return mixed
     */
    function osc_alert_field($field) {
        return osc_field(View::newInstance()->_current('alerts'), $field, '');
    }

    /**
     * Gets next alert if there is, else return null
     *
     * @return array
     */
    function osc_has_alerts() {
        $result = View::newInstance()->_next('alerts');
        $alert = osc_alert();
        View::newInstance()->_exportVariableToView("items", isset($alert['items'])?$alert['items']:array());
        return $result;
    }

    /**
     * Gets number of alerts in array alerts
     * @return int
     */
    function osc_count_alerts() {
        return (int) View::newInstance()->_count('alerts');
    }

    /**
     * Gets current alert fomr view
     *
     * @return array
     */
    function osc_alert() {
        return View::newInstance()->_current('alerts');
    }

    /**
     * Gets search field of current alert
     *
     * @return string
     */
    function osc_alert_search() {
        return (string) osc_alert_field('s_search');
    }

    /**
     * Gets secret of current alert
     * @return string
     */
    function osc_alert_secret() {
        return (string) osc_alert_field('s_secret');
    }

    /**
     * Gets id of current alert
     * @return string
     */
    function osc_alert_id() {
        return (string) osc_alert_field('pk_i_id');
    }

    /**
     * Gets aate of current alert
     * @return string
     */
    function osc_alert_date() {
        return (string) osc_alert_field('dt_date');
    }

    /**
     * Gets unsub date of current alert
     * @return string
     */
    function osc_alert_unsub_date() {
        return (string) osc_alert_field('dt_unsub_date');
    }

    /**
     * Gets type of current alert
     * @return string
     */
    function osc_alert_type() {
        return (string) osc_alert_field('e_type');
    }

    /**
     * Gets active of current alert
     * @return boolean
     */
    function osc_alert_is_active() {
        return (bool) osc_alert_field('b_active');
    }

    /**
     * Gets next user in users array
     *
     * @return <type>
     */
    function osc_prepare_user_info() {
        if ( !View::newInstance()->_exists('users') ) {
            View::newInstance()->_exportVariableToView('users', array ( User::newInstance()->findByPrimaryKey( osc_item_user_id() ) ) );
        }
        return View::newInstance()->_next('users');
    }


?>
