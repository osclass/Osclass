<?php

    function osc_is_web_user_logged_in() {
        if (Session::newInstance()->_get("userId") == '') return false ;
        return true ;
    }

    function osc_is_admin_user_logged_in() {
        if (Session::newInstance()->_get("adminId") == '') return false ;
        return true ;
    }

    function osc_logged_web_user_name() {
        return Session::newInstance()->_get("adminUserName") ;
    }

    function osc_logged_admin_user_name() {
        return Session::newInstance()->_get("webUserName") ;
    }

?>
