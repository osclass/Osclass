<?php

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    /**
    * Helper Preferences
    * @package Osclass
    * @subpackage Helpers
    * @author Osclass
    */

    /**
     * Gets cookie's life
     *
     * @return int
     */
    function osc_time_cookie() {
        return ( 31536000 ); // one year in seconds
    }

    /**
     * Gets if comments are enabled or not
     *
     * @return boolean
     */
    function osc_comments_enabled() {
        return (getBoolPreference('enabled_comments'));
    }

    /**
     * Force uploaded images to be JPEG
     *
     * @return boolean
     */
    function osc_force_jpeg() {
        return (getBoolPreference('force_jpeg'));
    }

    /**
     * Gets comments per page
     *
     * @return int
     */
    function osc_comments_per_page() {
        return (getPreference('comments_per_page'));
    }

    /**
     * Gets auto update settings
     *
     * @return string
     */
    function osc_auto_update() {
        return (getPreference('auto_update'));
    }

    /**
     * Gets number of days to warn about an ad being expired
     *
     * @return int
     */
    function osc_warn_expiration() {
        return (getPreference('warn_expiration'));
    }

    /**
     * Gets comments per page
     *
     * @return int
     */
    function osc_timezone() {
        return (getPreference('timezone'));
    }

    /**
     * Gets csrf session name
     *
     * @return int
     */
    function osc_csrf_name() {
        return (getPreference('csrf_name'));
    }

    /**
     * Gets if only users can post comments
     *
     * @return boolean
     */
    function osc_reg_user_post_comments() {
        return (getBoolPreference('reg_user_post_comments'));
    }

    /**
     * Gets if only users can contact to seller
     *
     * @return boolean
     */
    function osc_reg_user_can_contact() {
        return (getPreference('reg_user_can_contact'));
    }

    /**
     * Gets list of blacklsited terms for usernames
     *
     * @return string
     */
    function osc_username_blacklist() {
        return (getPreference('username_blacklist'));
    }

    /**
     * Gets if users are enabled or not
     *
     * @return boolean
     */
    function osc_users_enabled() {
        return (getBoolPreference('enabled_users'));
    }

    /**
     * Gets if user registration is enabled
     *
     * @return boolean
     */
    function osc_user_registration_enabled() {
        return (getBoolPreference('enabled_user_registration'));
    }

    /**
     * Gets is user validation is enabled or not
     *
     * @return boolean
     */
    function osc_user_validation_enabled() {
        return (getBoolPreference('enabled_user_validation'));
    }

    /**
     * Gets if validation for logged users is required or not
     *
     * @return boolean
     */
    function osc_logged_user_item_validation() {
        return (getBoolPreference('logged_user_item_validation'));
    }

    /**
     * Gets how many comments should be posted before auto-moderation
     *
     * @return int
     */
    function osc_moderate_comments() {
        return (getPreference('moderate_comments'));
    }

    /**
     * Gets if notification of new comments is enabled or not to admin
     *
     * @return boolean
     */
    function osc_notify_new_comment() {
        return (getBoolPreference('notify_new_comment'));
    }

    /**
     * Gets if notification of new comments is enabled or notto users
     *
     * @return boolean
     */
    function osc_notify_new_comment_user() {
        return (getBoolPreference('notify_new_comment_user'));
    }

    /**
     * Gets if nice urls are enabled or not
     *
     * @return boolean
     */
    function osc_rewrite_enabled() {
        return (getBoolPreference('rewriteEnabled'));
    }

    /**
     * Gets if mod rewrite is loaded or not (if apache runs on cgi mode, mod rewrite will not be detected)
     *
     * @return boolean
     */
    function osc_mod_rewrite_loaded() {
        return (getBoolPreference('mod_rewrite_loaded'));
    }

    /**
     * Gets if original images should be kept
     *
     * @return boolean
     */
    function osc_keep_original_image() {
        return (getBoolPreference('keep_original_image'));
    }

    /**
     * Force image aspect
     *
     * @return boolean
     */
    function osc_force_aspect_image() {
        return (getBoolPreference('force_aspect_image'));
    }

    /**
     * Gets if autocron is enabled
     *
     * @return boolean
     */
    function osc_auto_cron() {
        return (getBoolPreference('auto_cron'));
    }

    /**
     * Gets if recaptcha for items is enabled or not
     *
     * @return boolean
     */
    function osc_recaptcha_items_enabled() {
        return (getBoolPreference('enabled_recaptcha_items'));
    }

    /**
     * Gets how many seconds should an user wait to post a second item (0 for no waiting)
     *
     * @return int
     */
    function osc_items_wait_time() {
        return (getPreference('items_wait_time'));
    }

    /**
     * Gets how many items should be moderated to enable auto-moderation
     *
     * @return int
     */
    function osc_moderate_items() {
        return (getPreference('moderate_items'));
    }

    /**
     * Gets if only registered users can publish new items or anyone could
     *
     * @return boolean
     */
    function osc_reg_user_post() {
        return (getBoolPreference('reg_user_post'));
    }

    /**
     * Gets if the prices are o not enabled on the item's form
     *
     * @return boolean
     */
    function osc_price_enabled_at_items() {
        return (getBoolPreference('enableField#f_price@items'));
    }

    /**
     * Gets if images are o not enabled in item's form
     *
     * @return boolean
     */
    function osc_images_enabled_at_items() {
        return (getBoolPreference('enableField#images@items'));
    }

    /**
     * Gets how many images are allowed per item (o for unlimited)
     *
     * @return int
     */
    function osc_max_images_per_item() {
        return (getPreference('numImages@items'));
    }

    /**
     * Gets how many characters are allowed for the listings title
     *
     * @return int
     */
    function osc_max_characters_per_title() {
        $value = getPreference('title_character_length');
        return ( !empty($value) ? $value : 128);
    }

    /**
     * Gets how many characters are allowed for the listings description
     *
     * @return int
     */
    function osc_max_characters_per_description() {
        $value = getPreference('description_character_length');
        return ( !empty($value) ? $value : 4096);
    }

    /**
     * Gets if notification are sent to admin when a send-a-friend message is sent
     *
     * @return boolean
     */
    function osc_notify_contact_friends() {
        return(getBoolPreference('notify_contact_friends'));
    }

    /**
     * Gets if notification are sent to admin when a contact message is sent
     *
     * @return boolean
     */
    function osc_notify_contact_item() {
        return(getBoolPreference('notify_contact_item'));
    }

    /**
     * Gets item attachment is enabled
     *
     * @return boolean
     */
    function osc_item_attachment() {
        return(getBoolPreference('item_attachment'));
    }

    /**
     * Gets if contact attachment is enabled
     *
     * @return boolean
     */
    function osc_contact_attachment() {
        return(getBoolPreference('contact_attachment'));
    }

    /**
     * Gets if notification are sent to admin with new item
     *
     * @return boolean
     */
    function osc_notify_new_item() {
        return(getBoolPreference('notify_new_item'));
    }

    /**
     * Gets if notification are sent to admin with new user
     *
     * @return boolean
     */
    function osc_notify_new_user() {
        return(getBoolPreference('notify_new_user'));
    }

    /**
     * Gets if the mailserver requires authetification
     *
     * @return boolean
     */
    function osc_mailserver_auth() {
        return(getBoolPreference('mailserver_auth'));
    }

    /**
     * Gets if the mailserver requires authetification
     *
     * @return boolean
     */
    function osc_mailserver_pop() {
        return(getBoolPreference('mailserver_pop'));
    }


    //OTHER FUNCTIONS TO GET INFORMATION OF PREFERENCES
    /**
     * Gets the rewrite rules (generated via generate_rules.php at root folder)
     *
     * @return string
     */
    function osc_rewrite_rules() {
        return (getPreference('rewrite_rules'));
    }

    /**
     * Gets max kb of uploads
     *
     * @return int
     */
    function osc_max_size_kb() {
        return (getPreference('maxSizeKb'));
    }

    /**
     * Gets allowed extensions of uploads
     *
     * @return string
     */
    function osc_allowed_extension() {
        return (getPreference('allowedExt'));
    }

    /**
     * Gets if use of imagick is enabled or not
     *
     * @return string
     */
    function osc_use_imagick() {
        return (getBoolPreference('use_imagick'));
    }

    /**
     * Gets thumbnails' dimensions
     *
     * @return string
     */
    function osc_thumbnail_dimensions() {
        return (getPreference('dimThumbnail'));
    }

    /**
     * Gets preview images' dimensions
     *
     * @return string
     */
    function osc_preview_dimensions() {
        return (getPreference('dimPreview'));
    }

    /**
     * Gets normal size images' dimensions
     *
     * @return string
     */
    function osc_normal_dimensions() {
        return (getPreference('dimNormal'));
    }

    /**
     * Gets when was the last version check
     *
     * @return string
     */
    function osc_last_version_check() {
        return (getPreference('last_version_check'));
    }

    /**
     * Gets when was the last version check
     *
     * @return string
     */
    function osc_themes_last_version_check() {
        return (getPreference('themes_last_version_check'));
    }

    /**
     * Gets when was the last version check
     *
     * @return string
     */
    function osc_plugins_last_version_check() {
        return (getPreference('plugins_last_version_check'));
    }

    /**
     * Gets when was the last version check
     *
     * @return string
     */
    function osc_languages_last_version_check() {
        return (getPreference('languages_last_version_check'));
    }

    /**
     * Gets json response when checking if there is available a new version
     *
     * @return string
     */
    function osc_update_core_json() {
        return (getPreference('update_core_json'));
    }

    /**
     * Gets current version
     *
     * @return int
     */
    function osc_version() {
        return (getPreference('version'));
    }

    /**
     * Gets website's title
     *
     * @return string
     */
    function osc_page_title() {
        return (getPreference('pageTitle'));
    }

    /**
     * Gets website's default language
     *
     * @return string
     */
    function osc_language() {
        return(getPreference('language'));
    }

    /**
     * Gets website's admin default language
     *
     * @return string
     */
    function osc_admin_language() {
        return(getPreference('admin_language'));
    }

    /**
     * Gets current theme
     *
     * @return string
     */
    function osc_theme() {
        return(getPreference('theme'));
    }

    /**
     * Gets current admin theme
     *
     * @return string
     */
    function osc_admin_theme() {
        return(getPreference('admin_theme'));
    }

    /**
     * Gets website description
     *
     * @return string
     */
    function osc_page_description() {
        return(getPreference('pageDesc'));
    }

    /**
     * Gets contact email
     *
     * @return string
     */
    function osc_contact_email() {
        return(getPreference('contactEmail'));
    }

    /**
     * Gets date format
     *
     * @return string
     */
    function osc_date_format() {
        return(getPreference('dateFormat'));
    }

    /**
     * Gets time format
     *
     * @return string
     */
    function osc_time_format() {
        return(getPreference('timeFormat'));
    }

    /**
     * Gets week start day
     *
     * @return string
     */
    function osc_week_starts_at() {
        return(getPreference('weekStart'));
    }

    /**
     * Gets number of items to display on RSS
     *
     * @return int
     */
    function osc_num_rss_items() {
        return(getPreference('num_rss_items'));
    }

    /**
     * Gets default currency
     *
     * @return string
     */
    function osc_currency() {
        return(getPreference('currency'));
    }

    /**
     * Gets akismet key
     *
     * @return string
     */
    function osc_akismet_key() {
        return(getPreference('akismetKey'));
    }

    /**
     * Gets recaptcha private key
     *
     * @return string
     */
    function osc_recaptcha_private_key() {
        return(getPreference('recaptchaPrivKey'));
    }

    /**
     * Gets Osclass' market URL
     *
     * @return string
     */
    function osc_market_url($type = '', $code = '') {
        $url = getPreference('marketURL');
        switch ($type) {
            case 'plugins':
            case 'plugin':
                $url .= 'section/plugins/';
                if($code!='') {
                    $url .= 'code/'. $code;
                }
                break;
            case 'themes':
            case 'theme':
                $url .= 'section/themes/';
                if($code!='') {
                    $url .= 'code/'. $code;
                }
                break;
            case 'languages':
            case 'language':
                $url .= 'section/languages/';
                if($code!='') {
                    $url .= 'code/'. $code;
                }
                break;
            case 'purchases':
            case 'purchase':
                $url .= 'section/purchases/';
                break;
            default:
                break;
        }
        return $url;
    }

    /**
     * Gets market connect api key
     *
     * @return string
     */
    function osc_market_api_connect() {
        return(getPreference('marketAPIConnect'));
    }

    /**
     * Get Osclass' market url for count items in categories
     *
     * @return string
     */
    function osc_market_count_url() {
        $url = getPreference('marketURL');
        return $url . 'count/';
    }

    /**
     * Osclass' market url for get featured items in categories
     *
     * @return string
     */
    function osc_market_featured_url($type, $num = '') {
        $url = getPreference('marketURL');
        $url .= 'featured/';
        switch ($type) {
            case 'plugins':
                $url .= 'plugins/';
                break;
            case 'themes':
                $url .= 'themes/';
                break;
            case 'languages':
                $url .= 'languages/';
                break;
            default:
                break;
        }
        if($num!='') {
            $url .= 'num/'. $num;
        }
        return $url;
    }

    /**
     * Gets if third party sources are allowed to install new plugins and themes
     *
     * @return int
     */
    function osc_market_external_sources() {
        return(getBoolPreference('marketAllowExternalSources'));
    }

    /**
     * Market filters
     *
     * @return int
     */
    function osc_market_categories() {
        return(getPreference('marketCategories'));
    }

    /**
     * Market data update
     *
     * @return int
     */
    function osc_market_data_update() {
        return(getPreference('marketDataUpdate'));
    }

    /**
     * Gets recaptcha public key
     *
     * @return string
     */
    function osc_recaptcha_public_key() {
        return(getPreference('recaptchaPubKey'));
    }

    /**
     * Gets mailserver's type
     *
     * @return string
     */
    function osc_mailserver_type() {
        return(getPreference('mailserver_type'));
    }

    /**
     * Gets mailserver's host
     *
     * @return string
     */
    function osc_mailserver_host() {
        return(getPreference('mailserver_host'));
    }

    /**
     * Gets mailserver's port
     *
     * @return int
     */
    function osc_mailserver_port() {
        return(getPreference('mailserver_port'));
    }

    /**
    * Gets mail from
    *
    * @return string
    */
    function osc_mailserver_mail_from() {
        return(getPreference('mailserver_mail_from'));
    }

    /**
    * Gets name from
    *
    * @return string
    */
    function osc_mailserver_name_from() {
        return(getPreference('mailserver_name_from'));
    }

    /**
     * Gets mailserver's username
     *
     * @return string
     */
    function osc_mailserver_username() {
        return(getPreference('mailserver_username'));
    }

    /**
     * Gets mailserver's password
     *
     * @return string
     */
    function osc_mailserver_password() {
        return(getPreference('mailserver_password'));
    }

    /**
     * Gets if use SSL on the mailserver
     *
     * @return boolean
     */
    function osc_mailserver_ssl() {
        return(getPreference('mailserver_ssl'));
    }

    /**
     * Gets list of active plugins
     *
     * @return string
     */
    function osc_active_plugins() {
        return(getPreference('active_plugins'));
    }

    /**
     * Gets list of installed plugins
     *
     * @return string
     */
    function osc_installed_plugins() {
        return(getPreference('installed_plugins'));
    }

    /**
     * Gets default order field at search
     *
     * @return string
     */
    function osc_default_order_field_at_search() {
        return(getPreference('defaultOrderField@search'));
    }

    /**
     * Gets default order type at search
     *
     * @return string
     */
    function osc_default_order_type_at_search() {
        return(getPreference('defaultOrderType@search'));
    }

    /**
     * Gets default show as at search
     *
     * @return string
     */
    function osc_default_show_as_at_search() {
        return(getPreference('defaultShowAs@search'));
    }

    /**
     * Gets max results per page at search
     *
     * @return int
     */
    function osc_max_results_per_page_at_search() {
        return(getPreference('maxResultsPerPage@search'));
    }

    /**
     * Gets default results per page at search
     *
     * @return int
     */
    function osc_default_results_per_page_at_search() {
        return(getPreference('defaultResultsPerPage@search'));
    }

    /**
     * Gets max latest items
     *
     * @return int
     */
    function osc_max_latest_items() {
        return(getPreference('maxLatestItems@home'));
    }

    /**
     * Gets if save searches is enabled or not
     *
     * @return boolean
     */
    function osc_save_latest_searches() {
        return(getBoolPreference('save_latest_searches'));
    }

    function osc_purge_latest_searches() {
        return(getPreference('purge_latest_searches'));
    }

    /**
     * Gets how many seconds between item post to not consider it SPAM
     *
     * @return int
     */
    function osc_item_spam_delay() {
        return 60; // need to be changed
    }

    /**
     * Gets how many seconds between comment post to not consider it SPAM
     *
     * @return int
     */
    function osc_comment_spam_delay() {
        return 60; // need to be changed
    }

    /**
     * Gets if parent categories are enabled or not
     *
     * @return boolean
     */
    function osc_selectable_parent_categories() {
        return(getPreference('selectable_parent_categories'));
    }

    /**
     * Return max. number of latest items displayed at home index
     *
     * @return int
     */
    function osc_max_latest_items_at_home() {
        return(getPreference('maxLatestItems@home'));
    }

    /**
     * generic function to retrieve preferences
     *
     * @param string $key
     * @param string $section
     * @return string
     */
    function osc_get_preference($key, $section = 'osclass') {
        return getPreference($key, $section);
    }

    /**
     * generic function to retrieve preferences as bool
     *
     * @param string $key
     * @param string $section
     * @return string
     */
    function osc_get_bool_preference($key, $section = 'osclass') {
        $var = getPreference($key, $section);
        if($var==1 || $var=="1" || $var=="true" || $var==true) {
            return true;
        }
        return false;
    }

    /**
     * generic function to retrieve preferences
     *
     * @param string $section
     * @return string
     */
    function osc_get_preference_section($section = 'osclass') {
        $_P = Preference::newInstance();
        return $_P->getSection($section);
    }

    /**
     * generic function to insert/update preferences
     *
     * @param string $key
     * @param mixed $value
     * @param string $section
     * @param string $type
     * @return boolean
     */
    function osc_set_preference($key, $value = '', $section = 'osclass', $type = 'STRING') {
        return Preference::newInstance()->replace($key, $value, $section, $type);
    }

    /**
     * generic function to delete preferences
     *
     * @param string $key
     * @param string $section
     * @return boolean
     */
    function osc_delete_preference($key = '', $section = 'osclass') {
        return Preference::newInstance()->delete(array('s_name' => $key, 's_section' => $section));
    }

    /**
     * Reload preferences
     *
     * @return <array>
     */
    function osc_reset_preferences() {
        return Preference::newInstance()->toArray();
    }

    /**
     * Return if need mark images with text
     *
     * @return boolean
     */
    function osc_is_watermark_text() {
       $text = getPreference('watermark_text');
       if($text != ''){
           return true;
       } else {
           return false;
       }
    }

    /**
     * Return if need mark images with image
     *
     * @return boolean
     */
    function osc_is_watermark_image() {
        $image = getPreference('watermark_image');
        if($image != ''){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return watermark text color
     *
     * @return string
     */
    function osc_watermark_text_color() {
        return getPreference('watermark_text_color');
    }

    /**
     * Return watermark text
     *
     * @return string
     */
    function osc_watermark_text() {
        return getPreference('watermark_text');
    }

    /**
     * Return watermark place
     *
     * @return string
     */
    function osc_watermark_place() {
        return getPreference('watermark_place');
    }

    /**
     * Return subdomain type
     *
     * @return string
     */
    function osc_subdomain_type() {
        return getPreference('subdomain_type');
    }

    /**
     * Return subdomain host
     *
     * @return string
     */
    function osc_subdomain_host() {
        return getPreference('subdomain_host');
    }

    /**
     * Return version of recaptcha
     *
     * @return string
     */
    function osc_recaptcha_version() {
        return getPreference('recaptcha_version');
    }

    //PRIVATE FUNCTION (if there was a class :P)
    /**
     * Gets preference
     *
     * @param string $key
     * @return boolean
     */
    function getBoolPreference($key) {
        $_P = Preference::newInstance();

        if($_P->get($key)) {
            return true;
        } else {
            return false;
        }
    }

    //PRIVATE FUNCTION FOR GETTING NO BOOLEAN INFORMATION (if there was a class :P)
    /**
     * Gets preference
     *
     * @param string $key
     * @param string $section
     * @return string
     */
    function getPreference($key, $section = 'osclass') {
        $_P = Preference::newInstance();
        return $_P->get($key, $section);
    }
