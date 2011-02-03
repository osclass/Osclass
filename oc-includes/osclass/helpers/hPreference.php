<?php

    function osc_comments_enabled() {
        return (getBoolPreference('enabled_comments')) ;
    }

    function osc_moderate_comments() {
        return (getBoolPreference('moderate_comments')) ;
    }

    function osc_notify_new_comment() {
        return (getBoolPreference('notify_new_comment')) ;
    }

    function osc_rewrite_enabled() {
        return (getBoolPreference('rewriteEnabled')) ;
    }

    function osc_mod_rewrite_loaded() {
        return (getBoolPreference('mod_rewrite_loaded')) ;
    }

    function osc_keep_original_image() {
        return (getBoolPreference('keep_original_image')) ;
    }

    function osc_auto_cron() {
        return (getBoolPreference('auto_cron')) ;
    }

    function osc_enabled_recaptcha_items() {
        return (getBoolPreference('enabled_recaptcha_items')) ;
    }

    function osc_enabled_item_validation() {
        return (getBoolPreference('enabled_item_validation')) ;
    }
    
    function osc_reg_user_post() {
        return (getBoolPreference('reg_user_post')) ;
    }
    
    

    //OTHER FUNCTIONS TO GET INFORMATION OF PREFERENCES
    function osc_rewrite_rules() {
        return (getPreference('rewrite_rules')) ;
    }

    function osc_max_size_kb() {
        return (getPreference('maxSizeKb')) ;
    }

    function osc_allowed_extension() {
        return (getPreference('allowedExt')) ;
    }

    function osc_thumbnail_dimensions() {
        return (getPreference('dimThumbnail')) ;
    }

    function osc_preview_dimensions() {
        return (getPreference('dimPreview')) ;
    }

    function osc_normal_dimensions() {
        return (getPreference('dimNormal')) ;
    }
    

    //PRIVATE FUNCTION (if there was a class :P)
    function getBoolPreference($key) {
        $_P = Preference::newInstance() ;

        if($_P->get($key)) {
            return true ;
        } else {
            return false ;
        }
    }

    //PRIVATE FUNCTION FOR GETTING NO BOOLEAN INFORMATION (if there was a class :P)
    function getPreference($key) {
        $_P = Preference::newInstance() ;
        return($_P->get($key)) ;
    }
    
?>
