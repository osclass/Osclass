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
        $path = '';
        if(MULTISITE) {
            $path = osc_multisite_url();
        } else {
            $path = WEB_PATH ;
        }
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
        $path = '';
        if(MULTISITE) {
            $path = osc_multisite_url();
        } else {
            $path = WEB_PATH ;
        }
        $path .= "oc-admin/" ;
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
    * Gets the content path
    *
    * @return <string>
    */
    function osc_content_path() {
        return(CONTENT_PATH) ;
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
     *  Create automatically the contact url
     *
     * @return string
     */
    function osc_contact_url() {
        if ( osc_rewrite_enabled() ) {
            $path = osc_base_url() . 'contact/' ;
        } else {
            $path = osc_base_url(true) . '?page=contact' ;
        }
        return $path ;
    }
    
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
     * Create automatically the url to activate an account
     *
     * @return string
     */
    function osc_user_activate_url($id, $code) {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . 'user/activate/' . $id . '/' . $code ;
        } else {
            return osc_base_url(true) . '?page=register&action=validate&id=' . $id . '&code=' . $code ;
        }
    }

    /**
     * Create automatically the url of the item details page
     *
     * @return string
     */
    function osc_item_url() {
        if ( osc_rewrite_enabled() ) {
            $sanitized_title = osc_sanitizeString(osc_item_title()) ;
            $sanitized_category = '';
            $cat = Category::newInstance()->hierarchy(osc_item_category_id()) ;
            for ($i = (count($cat)); $i > 0; $i--) {
                $sanitized_category .= $cat[$i - 1]['s_slug'] . '/' ;
            }
            $path = osc_base_url() . sprintf('%s%s_%d', $sanitized_category, $sanitized_title, osc_item_id()) ;
        } else {
            //$path = osc_base_url(true) . sprintf('?page=item&id=%d', osc_item_id()) ;
            $path = osc_item_url_ns( osc_item_id() ) ;
        }
        return $path ;
    }

    /**
     * Create the no friendly url of the item using the id of the item
     * 
     * @param int the primary key of the item
     *
     * @return string
     */
    function osc_item_url_ns($id) {
        $path = osc_base_url(true) . '?page=item&id=' . $id ;

        return $path ;
    }
    
    /**
     * Create automatically the url to for admin to edit an item
     *
     * @return string
     */
    function osc_item_admin_edit_url($id) {
        return osc_admin_base_url(true) . '?page=items&action=item_edit&id=' . $id ;
    }
     
    //osc_createPageURL
    function osc_page_url() {
        if ( osc_rewrite_enabled() ) {
            $sanitizedString = osc_sanitizeString( osc_pages_title() ) ;
            $path = sprintf( osc_base_url() . '%s-p%d', urlencode($sanitizedString), osc_pages_id() ) ;
        } else {
            $path = sprintf( osc_base_url(true) . '?page=page&id=%d', osc_pages_id() ) ;
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

    function osc_user_unsubscribe_alert_url($email = '', $alert = '') {
        if($alert=='') { $alert = osc_alert_search(); }
        if($email=='') { $email = osc_user_email(); }
        return osc_base_url(true) . '?page=user&action=unsub_alert&email='.urlencode($email).'&alert='.$alert ;
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
    function osc_user_list_items_url($page = '') {
        if ( osc_rewrite_enabled() ) {
            if($page=='') {
                return osc_base_url() . 'user/items' ;
            } else {
                return osc_base_url() . 'user/items?iPage='.$page ;
            }
        } else {
            if($page=='') {
                return osc_base_url(true) . '?page=user&action=items' ;
            } else {
                return osc_base_url(true) . '?page=user&action=items&iPage='.$page ;
            }
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

    function osc_forgot_user_password_confirm_url($userId, $code) {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . 'user/forgot/' . $userId . '/' . $code ;
        } else {
            return osc_base_url(true) . '?page=login&action=forgot&userId='.$userId.'&code='.$code;
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
    
    // URL to edit an item
    function osc_item_edit_url($secret = '', $id = '') {
        if ($id == '') $id = osc_item_id();
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . 'item/edit/' . $id . '/' . $secret ;
        } else {
            return osc_base_url(true) . '?page=item&action=item_edit&id=' . $id . ($secret != '' ? '&secret=' . $secret : '') ;
        }
    }

    // URL to delete an item
    function osc_item_delete_url($secret = '', $id = '') {
        if ($id == '') $id = osc_item_id();
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . 'item/delete/' . $id . '/' . $secret ;
        } else {
            return osc_base_url(true) . '?page=item&action=item_delete&id=' . $id . ($secret != '' ? '&secret=' . $secret : '') ;
        }
    }
    
    // URL to activate an item
    function osc_item_activate_url($secret = '', $id = '') {
        if ($id == '') $id = osc_item_id();
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . 'item/activate/' . $id . '/' . $secret ;
        } else {
            return osc_base_url(true) . '?page=item&action=activate&id=' . $id . ($secret != '' ? '&secret=' . $secret : '') ;
        }
    }

    function osc_item_send_friend_url() {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . 'item/send-friend/' . osc_item_id() ;
        } else {
            return osc_base_url(true)."?page=item&action=send_friend&id=".osc_item_id();
        }
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
