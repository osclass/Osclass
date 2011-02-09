<?php

    //URL Helpers
    function osc_base_url($with_index = false) {
        $path = WEB_PATH ;
        if ($with_index) $path .= "index.php" ;
        return($path) ;
    }

    function osc_admin_base_url($with_index = false) {
        $path = WEB_PATH . "oc-admin/" ;
        if ($with_index) $path .= "index.php" ;
        return($path) ;
    }

    //Path Helpers
    function osc_base_path() {
        return(ABS_PATH) ;
    }

    function osc_admin_base_path() {
        return(ABS_PATH . "oc-admin/") ;
    }

    function osc_lib_path() {
        return(LIB_PATH) ;
    }

    function osc_themes_path() {
        return(THEMES_PATH) ;
    }

    function osc_plugins_path() {
        return(PLUGINS_PATH) ;
    }

    function osc_translations_path() {
        return(TRANSLATIONS_PATH) ;
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

    //ONLY USED AT PUBLIC WEBSITE
    function osc_current_web_theme() {
        return( WebThemes::newInstance()->getCurrentTheme() ) ;
    }

    function osc_current_web_theme_url() {
        return( WebThemes::newInstance()->getCurrentThemeUrl() ) ;
    }

    function osc_current_web_theme_path() {
        return( WebThemes::newInstance()->getCurrentThemePath() ) ;
    }

    function osc_current_web_theme_styles_url() {
        return( WebThemes::newInstance()->getCurrentThemeStyles() ) ;
    }
    
?>