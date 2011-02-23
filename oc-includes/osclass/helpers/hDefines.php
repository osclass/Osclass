<?php

    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */


    /**
     * Gets the root url for your installation
     *
     * @param boolean $with_index true if index.php in the url is needed
     * @return string
     */
    function osc_base_url($with_index = false) {
        $path = WEB_PATH ;
        if ($with_index) $path .= "index.php" ;
        return($path) ;
    }

    /**
     * Gets the root url of oc-admin for your installation
     * 
     * @param boolean $with_index true if index.php in the url is needed
     * @return string
     */
    function osc_admin_base_url($with_index = false) {
        $path = WEB_PATH . "oc-admin/" ;
        if ($with_index) $path .= "index.php" ;
        return($path) ;
    }
    
    /**
    * Gets the root path for your installation
    *
    * @return <string>
    */
    function osc_base_path() {
        return(ABS_PATH) ;
    }

    /**
    * Gets the root path of oc-admin
    *
    * @return <string>
    */
    function osc_admin_base_path() {
        return(osc_base_path() . "oc-admin/") ;
    }

    /**
    * Gets the librarieas path
    *
    * @return <string>
    */
    function osc_lib_path() {
        return(LIB_PATH) ;
    }

    /**
    * Gets the themes path
    *
    * @return <string>
    */
    function osc_themes_path() {
        return(THEMES_PATH) ;
    }

    /**
    * Gets the plugins path
    *
    * @return <string>
    */
    function osc_plugins_path() {
        return(PLUGINS_PATH) ;
    }

    /**
    * Gets the translations path
    *
    * @return <string>
    */
    function osc_translations_path() {
        return(TRANSLATIONS_PATH) ;
    }

    /**
    * Gets the current oc-admin theme
    *
    * @return <string>
    */
    function osc_current_admin_theme() {
        return( AdminThemes::newInstance()->getCurrentTheme() ) ;
    }

    /**
     * Gets the complete url of a given admin's file
     *
     * @param <string> $file the admin's file
     * @return <string>
     */
    function osc_current_admin_theme_url($file = '') {
        return AdminThemes::newInstance()->getCurrentThemeUrl() . $file ;
    }


    /**
     * Gets the complete path of a given admin's file
     *
     * @param <string> $file the admin's file
     * @return <string>
     */
    function osc_current_admin_theme_path($file = '') {
        require AdminThemes::newInstance()->getCurrentThemePath() . $file ;
    }

    /**
     * Gets the complete url of a given style's file
     *
     * @param <string> $file the style's file
     * @return <string>
     */
    function osc_current_admin_theme_styles_url($file = '') {
        return AdminThemes::newInstance()->getCurrentThemeStyles() . $file ;
    }

    /**
     * Gets the complete url of a given js's file
     *
     * @param <string> $file the js's file
     * @return <string>
     */
    function osc_current_admin_theme_js_url($file = '') {
        return AdminThemes::newInstance()->getCurrentThemeJs() . $file ;
    }

    /**
     * Gets the current theme for the public website
     *
     * @return <string>
     */
    function osc_current_web_theme() {
        return WebThemes::newInstance()->getCurrentTheme() ;
    }

    /**
     * Gets the complete url of a given file using the theme url as a root
     *
     * @param <string> $file the given file
     * @return <string>
     */
    function osc_current_web_theme_url($file = '') {
        return WebThemes::newInstance()->getCurrentThemeUrl() . $file ;
    }

    /**
     * Gets the complete path of a given file using the theme path as a root
     *
     * @param <type> $file
     * @return <string>
     */
    function osc_current_web_theme_path($file = '') {
        require WebThemes::newInstance()->getCurrentThemePath() . $file ;
    }

    /**
     * Gets the complete path of a given styles file using the theme path as a root
     *
     * @param string $file
     * @return string
     */
    function osc_current_web_theme_styles_url($file = '') {
        return WebThemes::newInstance()->getCurrentThemeStyles() . $file ;
    }

    /**
     * Gets the complete path of a given js file using the theme path as a root
     *
     * @param string $file
     * @return string
     */
    function osc_current_web_theme_js_url($file = '') {
        return WebThemes::newInstance()->getCurrentThemeJs() . $file ;
    }

    
    /////////////////////////////////////
    //functions for the public website //
    /////////////////////////////////////


    /**
     * Create automatically the url to post an item in a category
     *
     * @return string
     */
    function osc_item_post_url_in_category() {
        if (osc_category_id() > 0) {
            if ( osc_rewrite_enabled() ) {
                $path = osc_base_url() . 'item/new/' . osc_category_id();
            } else {
                $path = sprintf(osc_base_url(true) . '?page=item&action=item_add&catId=%d', osc_category_id()) ;
            }
        } else {
            $path = osc_item_post_url() ;
        }
        return $path ;
    }

    /**
     *  Create automatically the url to post an item
     *
     * @return string
     */
    function osc_item_post_url() {
        if ( osc_rewrite_enabled() ) {
            $path = osc_base_url() . 'item/new' ;
        } else {
            $path = sprintf(osc_base_url(true) . '?page=item&action=item_add') ;
        }
        return $path ;
    }

    /**
     * Create automatically the url of a category
     *
     * @return string the url 
     */
    function osc_search_category_url($pattern = '') {
        $category = osc_category() ;

        $path = '' ;
        if ( osc_rewrite_enabled() ) {
            if ($category != '') {
                $category = Category::newInstance()->hierarchy($category['pk_i_id']) ;
                $sanitized_category = "" ;
                for ($i = count($category); $i > 0; $i--) {
                    $sanitized_category .= $category[$i - 1]['s_slug'] . '/' ;
                }
                $path = osc_base_url() . $sanitized_category ;
            }
            if ($pattern != '') {
                if ($path == '') {
                    $path = osc_base_url() . 'search/' . $pattern ;
                } else {
                    $path .= 'search/' . $pattern ;
                }
            }
        } else {
            $path = sprintf( osc_base_url(true) . '?page=search&sCategory=%d', $category['pk_i_id'] ) ;
        }
        return $path ;
    }

    /**
     * Create automatically the url of the users' dashboard
     *
     * @return string
     */
    function osc_user_dashboard_url() {
        if ( osc_rewrite_enabled() ) {
            $path = osc_base_url() . 'user/dashboard' ;
        } else {
            $path = osc_base_url(true) . '?page=user&action=dashboard' ;
        }
        return $path ;
    }

    /**
     * Create automatically the logout url
     *
     * @return string
     */
    function osc_user_logout_url() {
        if ( osc_rewrite_enabled() ) {
            $path = osc_base_url() . 'user/logout' ;
        } else {
            $path = osc_base_url(true) . '?page=main&action=logout' ;
        }
        return $path ;
    }

    /**
     * Create automatically the login url
     *
     * @return string
     */
    function osc_user_login_url() {
        if ( osc_rewrite_enabled() ) {
            $path = osc_base_url() . 'user/login' ;
        } else {
            $path = osc_base_url(true) . '?page=login' ;
        }
        return $path ;
    }

    /**
     * Create automatically the url to register an account
     *
     * @return string
     */
    function osc_register_account_url() {
        if ( osc_rewrite_enabled() ) {
            $path = osc_base_url() . 'user/register' ;
        } else {
            $path = osc_base_url(true) . '?page=register&action=register' ;
        }
        return $path ;
    }

    /**
     * Create automatically the url to register an account
     *
     * @return string
     */
    function osc_item_url($item = null) {
        if($item==null) {
            return osc_base_url(true)."?page=item&id=".osc_item_id();        
        } else {//This part is deprecated
            if ( osc_rewrite_enabled() ) {
                $sanitized_title = osc_sanitizeString($item['s_title']) ;
                $sanitized_category = '';
                $cat = Category::newInstance()->hierarchy($item['fk_i_category_id']) ;
                for ($i = (count($cat)); $i > 0; $i--) {
                    $sanitized_category .= $cat[$i - 1]['s_slug'] . '/' ;
                }
                $path = osc_base_url() . sprintf('%s%s_%d', $sanitized_category, $sanitized_title, $item['pk_i_id']) ;
            } else {
                $path = osc_base_url(true) . sprintf('?page=item&id=%d', $item['pk_i_id']) ;
            }
            return $path ;
        }
    }

    //osc_createPageURL
    function osc_page_url($page) {
        if ( osc_rewrite_enabled() ) {
            $sanitizedString = osc_sanitizeString($page['s_title']);
            $path = sprintf(osc_base_url() . '%s-p%d', urlencode($sanitizedString), $page['pk_i_id']) ;
        } else {
            $path = sprintf(osc_base_url(true) . '?page=page&id=%d', $page['pk_i_id']) ;
        }
        return $path ;
    }

    //osc_createUserAlertsURL
    function osc_user_alerts_url() {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . 'user/alerts' ;
        } else {
            return osc_base_url(true) . '?page=user&action=alerts' ;
        }
    }

    //osc_createProfileURL
    function osc_user_profile_url() {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . 'user/profile' ;
        } else {
            return osc_base_url(true) . '?page=user&action=profile' ;
        }
    }

    //osc_createUserItemsURL
    function osc_user_list_items_url() {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . 'user/items' ;
        } else {
            return osc_base_url(true) . '?page=user&action=items' ;
        }
    }

    function osc_change_user_email_url() {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . 'user/change_email' ;
        } else {
            return osc_base_url(true) . '?page=user&action=change_email' ;
        }
    }

    function osc_change_user_email_confirm_url($userId, $code) {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . 'user/change_email_confirm/' . $userId . '/' . $code ;
        } else {
            return osc_base_url(true) . '?page=user&action=change_email_confirm&userId=' . $userId . '&code=' . $code ;
        }
    }

    function osc_change_user_password_url() {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . 'user/change_password' ;
        } else {
            return osc_base_url(true) . '?page=user&action=change_password' ;
        }
    }

    //doens't exists til now
    function osc_change_language_url($locale) {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . 'language/' . $locale ;
        } else {
            return osc_base_url(true) . '?page=language&locale=' . $locale ;
        }
    }
    
    /////////////////////////////////////
    //       functions for items       //
    /////////////////////////////////////
    function osc_item_edit_url($secret = '') {
        if($secret!='') {
            return osc_base_url(true)."?page=item&action=item_edit&id=".osc_item_id()."&secret=".$secret;
        } else {
            return osc_base_url(true)."?page=item&action=item_edit&id=".osc_item_id();
        }
    }

    function osc_item_delete_url($secret = '') {
        if($secret!='') {
            return osc_base_url(true)."?page=item&action=item_delete&id=".osc_item_id()."&secret=".$secret;
        } else {
            return osc_base_url(true)."?page=item&action=item_delete&id=".osc_item_id();
        }
    }

    function osc_item_activate_url($secret = '') {
        if($secret!='') {
            return osc_base_url(true)."?page=item&action=activate&id=".osc_item_id()."&secret=".$secret;
        } else {
            return osc_base_url(true)."?page=item&action=activate&id=".osc_item_id();
        }
    }

    function osc_item_send_friend_url() {
        return osc_base_url(true)."?page=item&action=send_friend&id=".osc_item_id();
    }
    /////////////////////////////////////
    //functions for locations & search //
    /////////////////////////////////////


    function osc_get_countries() {
        if (View::newInstance()->_exists('countries')) {
            return View::newInstance()->_get('countries') ;
        } else {
            return Country::newInstance()->listAll() ;
        }
    }
    
    function osc_get_regions($country = '') {
        if (View::newInstance()->_exists('regions')) {
            return View::newInstance()->_get('regions') ;
        } else {
            if($country=='') {
                return Region::newInstance()->listAll() ;
            } else {
                return Region::newInstance()->getByCountry($country);
            }
        }
    }
    
    function osc_get_cities($region = '') {
        if (View::newInstance()->_exists('cities')) {
            return View::newInstance()->_get('cities') ;
        } else {
            if($region=='') {
                return City::newInstance()->listAll() ;
            } else {
                return City::newInstance()->listWhere("fk_i_region_id = %d", $region);
            }
        }
    }
    
    function osc_get_currencies() {
        if (!View::newInstance()->_exists('currencies')) {
            View::newInstance()->_exportVariableToView('currencies', Currency::newInstance()->listAll());
        }
        return View::newInstance()->_get('currencies');
    }


    
    
?>
