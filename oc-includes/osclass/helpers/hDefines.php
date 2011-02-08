<?php

    function osc_base_url($with_index = false) {
        $path = WEB_PATH ;
        if ($with_index) $path . "index.php" ;
        return($path) ;
    }

    function osc_admin_base_url($with_index = false) {
        $path = WEB_PATH . "oc-admin/" ;
        if ($with_index) $path . "index.php" ;
        return($path) ;
    }

    //ONLY USED AT OC-ADMIN
    function osc_current_admin_theme() {
        return( AdminThemes::newInstance()->getCurrentTheme() ) ;
    }

    function osc_current_admin_theme_url() {
        return( AdminThemes::newInstance()->getCurrentThemeUrl() ) ;
    }
    
    function osc_current_admin_theme_path() {
        return( AdminThemes::newInstance()->getCurrentThemePath() ) ;
    }

    function osc_current_admin_theme_styles_url() {
        return( AdminThemes::newInstance()->getCurrentThemeStyles() ) ;
    }
    
?>