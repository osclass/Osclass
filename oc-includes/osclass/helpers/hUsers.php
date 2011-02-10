<?php

    function osc_is_user_logged_in() {
        if (Session::newInstance()->_get("adminId") == '') return false ;
        return true ;
    }

    function osc_is_admin_user_logged_in() {
        Session::newInstance()->_get("userId") ;
    }

?>
