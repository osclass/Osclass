<?php

    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
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
    * Helper Defines
    * @package Osclass
    * @subpackage Helpers
    * @author Osclass
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
            $path = WEB_PATH;
        }
        if ($with_index) $path .= "index.php";
        return($path);
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
            $path = WEB_PATH;
        }
        $path .= "oc-admin/";
        if ($with_index) $path .= "index.php";
        return($path);
    }

    /**
    * Gets the root path for your installation
    *
    * @return string
    */
    function osc_base_path() {
        return(ABS_PATH);
    }

    /**
    * Gets the root path of oc-admin
    *
    * @return string
    */
    function osc_admin_base_path() {
        return(osc_base_path() . "oc-admin/");
    }

    /**
    * Gets the librarieas path
    *
    * @return string
    */
    function osc_lib_path() {
        return(LIB_PATH);
    }

    /**
    * Gets the content path
    *
    * @return string
    */
    function osc_content_path() {
        return(CONTENT_PATH);
    }

    /**
    * Gets the themes path
    *
    * @return string
    */
    function osc_themes_path() {
        return(THEMES_PATH);
    }

    /**
    * Gets the plugins path
    *
    * @return string
    */
    function osc_plugins_path() {
        return(PLUGINS_PATH);
    }

    /**
    * Gets the translations path
    *
    * @return string
    */
    function osc_translations_path() {
        return(TRANSLATIONS_PATH);
    }

    /**
    * Gets the translations path
    *
    * @return string
    */
    function osc_uploads_path() {
        if( MULTISITE && osc_multisite_upload_path() !== '' ) {
            return osc_multisite_upload_path();
        }

        return(UPLOADS_PATH);
    }

    /**
    * Gets the current oc-admin theme
    *
    * @return string
    */
    function osc_current_admin_theme() {
        return( AdminThemes::newInstance()->getCurrentTheme() );
    }

    /**
     * Gets the complete url of a given admin's file
     *
     * @param string $file the admin's file
     * @return string
     */
    function osc_current_admin_theme_url($file = '') {
        return AdminThemes::newInstance()->getCurrentThemeUrl() . $file;
    }


    /**
     * Gets the complete path of a given admin's file
     *
     * @param string $file the admin's file
     * @return string
     */
    function osc_current_admin_theme_path($file = '') {
        require AdminThemes::newInstance()->getCurrentThemePath() . $file;
    }

    /**
     * Gets the complete url of a given style's file
     *
     * @param string $file the style's file
     * @return string
     */
    function osc_current_admin_theme_styles_url($file = '') {
        return AdminThemes::newInstance()->getCurrentThemeStyles() . $file;
    }

    /**
     * Gets the complete url of a given js's file
     *
     * @param string $file the js's file
     * @return string
     */
    function osc_current_admin_theme_js_url($file = '') {
        return AdminThemes::newInstance()->getCurrentThemeJs() . $file;
    }

    /**
     * Gets the current theme for the public website
     *
     * @return string
     */
    function osc_current_web_theme() {
        return WebThemes::newInstance()->getCurrentTheme();
    }

    /**
     * Gets the complete url of a given file using the theme url as a root
     *
     * @param string $file the given file
     * @return string
     */
    function osc_current_web_theme_url($file = '') {
        return WebThemes::newInstance()->getCurrentThemeUrl() . $file;
    }

    /**
     * Gets the complete path of a given file using the theme path as a root
     *
     * @param string $file
     * @return string
     */
    function osc_current_web_theme_path($file = '') {

        if( file_exists(WebThemes::newInstance()->getCurrentThemePath() . $file) ){
            require WebThemes::newInstance()->getCurrentThemePath() . $file;
        } else {
            WebThemes::newInstance()->setGuiTheme();
            if( file_exists(WebThemes::newInstance()->getCurrentThemePath() . $file) ) {
                require WebThemes::newInstance()->getCurrentThemePath() . $file;
            }
        }
    }

    /**
     * Gets the complete path of a given styles file using the theme path as a root
     *
     * @param string $file
     * @return string
     */
    function osc_current_web_theme_styles_url($file = '') {
        return WebThemes::newInstance()->getCurrentThemeStyles() . $file;
    }

    /**
     * Gets the complete path of a given js file using the theme path as a root
     *
     * @param string $file
     * @return string
     */
    function osc_current_web_theme_js_url($file = '') {
        return WebThemes::newInstance()->getCurrentThemeJs() . $file;
    }

    /**
     * Gets the complete path of a given common asset
     *
     * @since 3.0
     * @param string $file
     * @return string
     */
    function osc_assets_url($file = '') {
        if( stripos($file, '../') !== false ) {
            $file = '';
        }

        return osc_base_url() . 'oc-includes/osclass/assets/' . $file;
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
            $path = osc_base_url() . osc_get_preference('rewrite_contact');
        } else {
            $path = osc_base_url(true) . '?page=contact';
        }
        return $path;
    }

    /**
     * Create automatically the url to post an item in a category
     *
     * @return string
     */
    function osc_item_post_url_in_category() {
        if (osc_category_id() > 0) {
            if ( osc_rewrite_enabled() ) {
                $path = osc_base_url() . osc_get_preference('rewrite_item_new') . '/' . osc_category_id();
            } else {
                $path = sprintf(osc_base_url(true) . '?page=item&action=item_add&catId=%d', osc_category_id());
            }
        } else {
            $path = osc_item_post_url();
        }
        return $path;
    }

    /**
     *  Create automatically the url to post an item
     *
     * @return string
     */
    function osc_item_post_url() {
        if ( osc_rewrite_enabled() ) {
            $path = osc_base_url() . osc_get_preference('rewrite_item_new');
        } else {
            $path = osc_base_url(true) . '?page=item&action=item_add';
        }
        return $path;
    }

    /**
     * Create automatically the url of a category
     *
     * @param string $pattern
     * @return string the url
     */
    function osc_search_category_url() {
        $path = '';
        if(osc_subdomain_type()=='category') {
            if(isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS'])=='on' || $_SERVER['HTTPS']=='1')){
                $path = "https://";
            } else {
                $path = "http://";
            }
            $path .= osc_category_slug().".".osc_subdomain_host().REL_WEB_URL;
        } else if(osc_rewrite_enabled()) {
            $url = osc_get_preference('rewrite_cat_url');
            if( preg_match('|{CATEGORIES}|', $url) ) {
                $category = Category::newInstance()->hierarchy(osc_category_id());
                $sanitized_categories = array();
                for ($i = count($category); $i > 0; $i--) {
                    $sanitized_categories[] = $category[$i - 1]['s_slug'];
                }
                $url = str_replace('{CATEGORIES}', implode("/", $sanitized_categories), $url);
            }
            $seo_prefix = '';
            if( osc_get_preference('seo_url_search_prefix') != '' ) {
                $seo_prefix = osc_get_preference('seo_url_search_prefix') . '/';
            }
            $url = str_replace('{CATEGORY_NAME}', osc_category_slug(), $url);
            // DEPRECATED : CATEGORY_SLUG is going to be removed in 3.4
            $url = str_replace('{CATEGORY_SLUG}', osc_category_slug(), $url);
            $url = str_replace('{CATEGORY_ID}', osc_category_id(), $url);
            $path = osc_base_url() . $seo_prefix . $url;
        } else {
            $path = sprintf( osc_base_url(true) . '?page=search&sCategory=%d', osc_category_id() );
        }
        return $path;
    }

    /**
     * Create automatically the url of the users' dashboard
     *
     * @return string
     */
    function osc_user_dashboard_url() {
        if ( osc_rewrite_enabled() ) {
            $path = osc_base_url() . osc_get_preference('rewrite_user_dashboard');
        } else {
            $path = osc_base_url(true) . '?page=user&action=dashboard';
        }
        return $path;
    }

    /**
     * Create automatically the logout url
     *
     * @return string
     */
    function osc_user_logout_url() {
        if ( osc_rewrite_enabled() ) {
            $path = osc_base_url() . osc_get_preference('rewrite_user_logout');
        } else {
            $path = osc_base_url(true) . '?page=main&action=logout';
        }
        return $path;
    }

    /**
     * Create automatically the login url
     *
     * @return string
     */
    function osc_user_login_url() {
        if ( osc_rewrite_enabled() ) {
            $path = osc_base_url() . osc_get_preference('rewrite_user_login');
        } else {
            $path = osc_base_url(true) . '?page=login';
        }
        return $path;
    }

    /**
     * Create automatically the url to register an account
     *
     * @return string
     */
    function osc_register_account_url() {
        if ( osc_rewrite_enabled() ) {
            $path = osc_base_url() . osc_get_preference('rewrite_user_register');
        } else {
            $path = osc_base_url(true) . '?page=register&action=register';
        }
        return $path;
    }

    /**
     * Create automatically the url to activate an account
     *
     * @param int $id
     * @param string $code
     * @return string
     */
    function osc_user_activate_url($id, $code) {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_user_activate') . '/' . $id . '/' . $code;
        } else {
            return osc_base_url(true) . '?page=register&action=validate&id=' . $id . '&code=' . $code;
        }
    }

    /**
     * Create automatically the url of the item's comments page
     *
     * @param mixed $page
     * @param string $locale
     * @return string
     */
    function osc_item_comments_url($page = 'all', $locale = '') {
        if ( osc_rewrite_enabled() ) {
            return osc_item_url($locale) . "?comments-page=" . $page;
        } else {
            return osc_item_url($locale) . "&comments-page=" . $page;
        }
    }

    /**
     * Create automatically the url of the item's comments page
     *
     * @param string $locale
     * @return string
     */
    function osc_comment_url($locale = '') {
        return osc_item_url($locale) . "?comment=" . osc_comment_id();
    }


    /**
     * Create automatically the url of the item details page
     *
     * @param string $locale
     * @return string
     */
    function osc_item_url($locale = '') {
        if ( osc_rewrite_enabled() ) {
            $url = osc_get_preference('rewrite_item_url');
            if( preg_match('|{CATEGORIES}|', $url) ) {
                $sanitized_categories = array();
                $cat = Category::newInstance()->hierarchy(osc_item_category_id());
                for ($i = (count($cat)); $i > 0; $i--) {
                    $sanitized_categories[] = $cat[$i - 1]['s_slug'];
                }
                $url = str_replace('{CATEGORIES}', implode("/", $sanitized_categories), $url);
            }
            $url = str_replace('{ITEM_ID}', osc_sanitizeString(osc_item_id()), $url);
            $url = str_replace('{ITEM_CITY}', osc_sanitizeString(osc_item_city()), $url);
            $url = str_replace('{ITEM_TITLE}', osc_sanitizeString(osc_item_title()), $url);
            $url = str_replace('?', '', $url);
            if($locale!='') {
                $path = osc_base_url().$locale."/".$url;
            } else {
                $path = osc_base_url().$url;
            }
        } else {
            $path = osc_item_url_ns(osc_item_id(), $locale);
        }
        return $path;
    }

    /**
     * Create automatically the url of the item details page
     *
     * @param string $locale
     * @return string
     */
    function osc_premium_url($locale = '') {
        if ( osc_rewrite_enabled() ) {
            $sanitized_categories = array();
            $cat = Category::newInstance()->hierarchy(osc_premium_category_id());
            for ($i = (count($cat)); $i > 0; $i--) {
                $sanitized_categories[] = $cat[$i - 1]['s_slug'];
            }
            $url = str_replace('{CATEGORIES}', implode("/", $sanitized_categories), str_replace('{ITEM_ID}', osc_premium_id(), str_replace('{ITEM_TITLE}', osc_sanitizeString(osc_premium_title()), osc_get_preference('rewrite_item_url'))));
            if($locale!='') {
                $path = osc_base_url().$locale."/".$url;
            } else {
                $path = osc_base_url().$url;
            }
        } else {
            $path = osc_item_url_ns( osc_premium_id(), $locale );
        }
        return $path;
    }

    /**
     * Create the no friendly url of the item using the id of the item
     *
     * @param int $id the primary key of the item
     * @param $locale
     * @return string
     */
    function osc_item_url_ns($id, $locale = '') {
        $path = osc_base_url(true) . '?page=item&id=' . $id;
        if($locale!='') {
            $path .= "&lang=" . $locale;
        }

        return $path;
    }

    /**
     * Create automatically the url to for admin to edit an item
     *
     * @param int $id
     * @return string
     */
    function osc_item_admin_edit_url($id) {
        return osc_admin_base_url(true) . '?page=items&action=item_edit&id=' . $id;
    }

    /**
     * Gets current user alerts' url
     *
     * @return string
     */
    function osc_user_alerts_url() {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_user_alerts');
        } else {
            return osc_base_url(true) . '?page=user&action=alerts';
        }
    }

    /**
     * Gets current user alert unsubscribe url
     *
     * @param string $email
     * @param string $secret
     * @return string
     */
    function osc_user_unsubscribe_alert_url( $id = '', $email = '', $secret = '') {
        if($secret=='') { $secret = osc_alert_secret(); }
        if($id=='') { $id = osc_alert_id(); }
        if($email=='') { $email = osc_user_email(); }
        return osc_base_url(true) . '?page=user&action=unsub_alert&email='.urlencode($email).'&secret='.$secret.'&id='.$id;
    }

    /**
     * Gets user alert activate url
     *
     * @param string $secret
     * @param string $email
     * @return string
     */
    function osc_user_activate_alert_url( $id, $secret , $email ) {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_user_activate_alert') . '/' . $id . '/' . $secret . '/' . urlencode($email);
        } else {
            return osc_base_url(true) . '?page=user&action=activate_alert&email=' . urlencode($email) . '&secret=' . $secret .'&id='.$id;
        }

    }

    /**
     * Gets current user url
     *
     * @return string
     */
    function osc_user_profile_url() {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_user_profile');
        } else {
            return osc_base_url(true) . '?page=user&action=profile';
        }
    }

    /**
     * Gets current user alert activate url
     *
     * @param int $page
     * @return string
     */
    function osc_user_list_items_url($page = '', $typeItem = '') {
        if ( osc_rewrite_enabled() ) {

            if($page=='') {
                $typeItem = $typeItem != '' ? "?itemType=" . $typeItem : "";
                return osc_base_url() . osc_get_preference('rewrite_user_items') . $typeItem ;
            } else {
                $typeItem = $typeItem != '' ? "&itemType=" . $typeItem  : "";
                return osc_base_url() . osc_get_preference('rewrite_user_items') . "?iPage=" . $page . $typeItem ;
            }
        } else {
            if($page=='') {
                return osc_base_url(true) . '?page=user&action=items';
            } else {
                return osc_base_url(true) . '?page=user&action=items&iPage='.$page;
            }
        }
    }

    /**
     * Gets url to change email
     *
     * @return string
     */
    function osc_change_user_email_url() {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_user_change_email');
        } else {
            return osc_base_url(true) . '?page=user&action=change_email';
        }
    }

    /**
     * Gets url to change username
     *
     * @return string
     */
    function osc_change_user_username_url() {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_user_change_username');
        } else {
            return osc_base_url(true) . '?page=user&action=change_username';
        }
    }

    /**
     * Gets confirmation url of change email
     *
     * @param int $userId
     * @param string $code
     * @return string
     */
    function osc_change_user_email_confirm_url($userId, $code) {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_user_change_email_confirm') . '/' . $userId . '/' . $code;
        } else {
            return osc_base_url(true) . '?page=user&action=change_email_confirm&userId=' . $userId . '&code=' . $code;
        }
    }

    /**
     * Gets url for changing password
     *
     * @return string
     */
    function osc_change_user_password_url() {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_user_change_password');
        } else {
            return osc_base_url(true) . '?page=user&action=change_password';
        }
    }

    /**
     * Gets url for recovering password
     *
     * @return string
     */
    function osc_recover_user_password_url() {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_user_recover');
        } else {
            return osc_base_url(true) . '?page=login&action=recover';
        }
    }

    /**
     * Gets url for confirm the forgot password process
     *
     * @param int $userId
     * @param string $code
     * @return string
     */
    function osc_forgot_user_password_confirm_url($userId, $code) {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_user_forgot') . '/' . $userId . '/' . $code;
        } else {
            return osc_base_url(true) . '?page=login&action=forgot&userId='.$userId.'&code='.$code;
        }
    }

    /**
     * Gets url for confirmation admin password recover proces
     *
     * @param int $adminId
     * @param string $code
     * @return string
     */
    function osc_forgot_admin_password_confirm_url($adminId, $code) {
        return osc_admin_base_url(true) . '?page=login&action=forgot&adminId='.$adminId.'&code='.$code;
    }

    /**
     * Gets url for changing website language (for users)
     *
     * @param string $locale
     * @return string
     */
    function osc_change_language_url($locale) {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_language') . '/' . $locale;
        } else {
            return osc_base_url(true) . '?page=language&locale=' . $locale;
        }
    }

    /////////////////////////////////////
    //       functions for items       //
    /////////////////////////////////////

    /**
     * Gets url for editing an item
     *
     * @param string $secret
     * @param int $id
     * @return string
     */
    function osc_item_edit_url($secret = '', $id = '') {
        if ($id == '') { $id = osc_item_id(); };
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_item_edit') . '/' . $id . '/' . $secret;
        } else {
            return osc_base_url(true) . '?page=item&action=item_edit&id=' . $id . ($secret != '' ? '&secret=' . $secret : '');
        }
    }

    /**
     * Gets url for delete an item
     *
     * @param string $secret
     * @param int $id
     * @return string
     */
    function osc_item_delete_url($secret = '', $id = '') {
        if ($id == '') { $id = osc_item_id(); };
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_item_delete') . '/' . $id . '/' . $secret;
        } else {
            return osc_base_url(true) . '?page=item&action=item_delete&id=' . $id . ($secret != '' ? '&secret=' . $secret : '');
        }
    }

    /**
     * Gets url for activate an item
     *
     * @param string $secret
     * @param int $id
     * @return string
     */
    function osc_item_activate_url($secret = '', $id = '') {
        if ($id == '') { $id = osc_item_id(); };
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_item_activate') . '/' . $id . '/' . $secret;
        } else {
            return osc_base_url(true) . '?page=item&action=activate&id=' . $id . ($secret != '' ? '&secret=' . $secret : '');
        }
    }

    /**
     * Gets url for deleting a resource of an item
     *
     * @param int $id of the resource
     * @param int $item
     * @param string $code
     * @param string $secret
     * @return string
     */
    function osc_item_resource_delete_url($id, $item, $code, $secret = '') {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_item_resource_delete') . '/' . $id . '/' . $item . '/' . $code . ($secret != '' ? '/' . $secret : '');
        } else {
            return osc_base_url(true) . '?page=item&action=deleteResource&id=' . $id . '&item=' . $item . '&code=' . $code . ($secret != '' ? '&secret=' . $secret : '');
        }
    }

    /**
     * Gets url of send a friend (current item)
     *
     * @return string
     */
    function osc_item_send_friend_url() {
        if ( osc_rewrite_enabled() ) {
            return osc_base_url() . osc_get_preference('rewrite_item_send_friend') . '/' . osc_item_id();
        } else {
            return osc_base_url(true)."?page=item&action=send_friend&id=".osc_item_id();
        }
    }

    /**
     * @param $id
     * @param $args
     * @since 3.2
     */
    function osc_route_url($id, $args = array()) {
        $routes = Rewrite::newInstance()->getRoutes();
        if(!isset($routes[$id])) { return ''; };
        if ( osc_rewrite_enabled() ) {
            $uri = $routes[$id]['url'];
            $params_url = '';
            foreach($args as $k => $v) {
                $old_uri = $uri;
                $uri = str_ireplace('{'.$k.'}', $v, $uri);
                if($old_uri==$uri) {
                    $params_url .= '&'.$k.'='.$v;
                }
            }
            return osc_base_url().$uri.(($params_url!='')?'?'.$params_url:'');
        } else {
            $params_url = '';
            foreach($args as $k => $v) {
                $params_url .= '&'.$k.'='.$v;
            }
            return osc_base_url(true)."?page=custom&route=".$id.$params_url;
        }
    }

    /**
     * @param $id
     * @param $args
     * @since 3.2
     */
    function osc_route_admin_url($id, $args = array()) {
        $routes = Rewrite::newInstance()->getRoutes();
        if(!isset($routes[$id])) { return ''; };
        $params_url = '';
        foreach($args as $k => $v) {
            $params_url .= '&'.$k.'='.$v;
        }
        return osc_admin_base_url(true)."?page=plugins&action=renderplugin&route=".$id.$params_url;
    }

    /**
     * @param $id
     * @param $args
     * @since 3.2
     */
    function osc_route_ajax_url($id, $args = array()) {
        $routes = Rewrite::newInstance()->getRoutes();
        if(!isset($routes[$id])) { return ''; };
        $params_url = '';
        foreach($args as $k => $v) {
            $params_url .= '&'.$k.'='.$v;
        }
        return osc_base_url(true)."?page=ajax&action=custom&route=".$id.$params_url;
    }

    /**
     * @param $id
     * @param $args
     * @since 3.2
     */
    function osc_route_admin_ajax_url($id, $args = array()) {
        $routes = Rewrite::newInstance()->getRoutes();
        if(!isset($routes[$id])) { return ''; };
        $params_url = '';
        foreach($args as $k => $v) {
            $params_url .= '&'.$k.'='.$v;
        }
        return osc_admin_base_url(true)."?page=ajax&action=custom&route=".$id.$params_url;
    }

    /////////////////////////////////////
    //functions for locations & search //
    /////////////////////////////////////


    /**
     * Gets list of countries
     *
     * @return string
     */
    function osc_get_countries() {
        if (View::newInstance()->_exists('countries')) {
            return View::newInstance()->_get('countries');
        } else {
            return Country::newInstance()->listAll();
        }
    }

    /**
     * Gets list of regions (from a country)
     *
     * @param int $country
     * @return string
     */
    function osc_get_regions($country = '') {
        if (View::newInstance()->_exists('regions')) {
            return View::newInstance()->_get('regions');
        } else {
            if($country=='') {
                return Region::newInstance()->listAll();
            } else {
                return Region::newInstance()->findByCountry($country);
            }
        }
    }

    /**
     * Gets list of cities (from a region)
     *
     * @param int $region
     * @return string
     */
    function osc_get_cities($region = '') {
        if (View::newInstance()->_exists('cities')) {
            return View::newInstance()->_get('cities');
        } else {
            if($region=='') {
                return City::newInstance()->listAll();
            } else {
                return City::newInstance()->findByRegion($region);
            }
        }
    }

    /**
     * Gets list of currencies
     *
     * @return string
     */
    function osc_get_currencies() {
        if (!View::newInstance()->_exists('currencies')) {
            View::newInstance()->_exportVariableToView('currencies', Currency::newInstance()->listAll());
        }
        return View::newInstance()->_get('currencies');
    }


    /**
     * Prints the additional options to the menu
     *
     * @param array $option with options of the form array('name' => 'display name', 'url' => 'url of link')
     *
     * @return void
     */
    function osc_add_option_menu($option = null) {
        if($option!=null) {
            echo '<li><a href="' . $option['url'] . '" >' . $option['name'] . '</a></li>';
        }
    }

    /**
     * Get if user is on ad page
     *
     * @return boolean
     */
    function osc_is_ad_page() {
        $location = Rewrite::newInstance()->get_location();
        $section = Rewrite::newInstance()->get_section();
        if($location=='item' && $section=='') {
            return true;
        }
        return false;
    }

    /**
     * Get if user is on search page
     *
     * @return boolean
     */
    function osc_is_search_page() {
        $location = Rewrite::newInstance()->get_location();
        if($location=='search') {
            return true;
        }
        return false;
    }

    /**
     * Get if user is on a static page
     *
     * @return boolean
     */
    function osc_is_static_page() {
        $location = Rewrite::newInstance()->get_location();
        if($location=='page') {
            return true;
        }
        return false;
    }

    /**
     * Get if user is on homepage
     *
     * @return boolean
     */
    function osc_is_home_page() {
        $location = Rewrite::newInstance()->get_location();
        $section = Rewrite::newInstance()->get_section();
        if($location=='' && $section=='') {
            return true;
        }
        return false;
    }

    /**
     * Get if user is on user dashboard
     *
     * @return boolean
     */
    function osc_is_user_dashboard() {
        $location = Rewrite::newInstance()->get_location();
        $section = Rewrite::newInstance()->get_section();
        if($location=='user' && $section=='dashboard') {
            return true;
        }
        return false;
    }

    /**
     * Get if user is on publish page
     *
     * @return boolean
     */
    function osc_is_publish_page() {
        $location = Rewrite::newInstance()->get_location();
        $section = Rewrite::newInstance()->get_section();
        if($location=='item' && $section=='item_add') {
            return true;
        }
        return false;
    }

    /**
     * Get if user is on login form
     *
     * @return boolean
     */
    function osc_is_login_form() {
        $location = Rewrite::newInstance()->get_location();
        $section = Rewrite::newInstance()->get_section();
        if($location=='login' && $section=='') {
            return true;
        }
        return false;
    }

    /**
     * Get if the user is on custom page
     *
     * @return boolean
     */
    function osc_is_custom_page($value = null) {
        if(Rewrite::newInstance()->get_location()=='custom') {
            if($value==null || Params::getParam('file')==$value || Params::getParam('route')==$value) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get if the user is on public profile page
     *
     * @return boolean
     */
    function osc_is_public_profile() {
        $location = Rewrite::newInstance()->get_location();
        $section  = Rewrite::newInstance()->get_section();
        if( $location === 'user' && $section === 'pub_profile' ) {
            return true;
        }
        return false;
    }

    /**
     * Get if the user is on items page
     *
     * @return boolean
     */
    function osc_is_list_items() {
        $location = Rewrite::newInstance()->get_location();
        $section  = Rewrite::newInstance()->get_section();
        if( $location === 'user' && $section === 'items' ) {
            return true;
        }
        return false;
    }



    /**
     * Get location
     *
     * @return string
     */
    function osc_get_osclass_location() {
        return Rewrite::newInstance()->get_location();
    }

    /**
     * Get section
     *
     * @return string
     */
    function osc_get_osclass_section() {
        return Rewrite::newInstance()->get_section();
    }


    /**
     * Check is an admin is a super admin or only a moderator
     *
     * @return boolean
     */
    function osc_is_moderator() {
        $admin = Admin::newInstance()->findByPrimaryKey(osc_logged_admin_id());

        if( isset($admin['b_moderator']) && $admin['b_moderator']!=0 ) {
            return true;
        }

        return false;
    }

    function osc_get_domain() {
        $result = parse_url( osc_base_url() );

        return $result['host'];
    }

    function osc_breadcrumb($separator = '&raquo;', $echo = true, $lang = array()) {
        $br = new Breadcrumb($lang);
        $br->init();
        if( $echo ) {
            echo $br->render($separator);
            return;
        }
        return $br->render($separator);
    }

    function osc_subdomain_name() {
        return View::newInstance()->_get('subdomain_name');
    }    
    /* file end: ./oc-includes/osclass/helpers/hDefines.php */
?>